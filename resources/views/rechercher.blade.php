@extends('layouts.app')

@section('title', 'Rechercher - Covoit2025')

@push('scripts')
    @vite(['resources/js/rechercher.js'])
    <script>
        window.csrfToken = '{{ csrf_token() }}';
        // expose le rôle de l'utilisateur au JS (vide si non connecté)
        window.userRole = {!! json_encode(session('utilisateur_role', '')) !!};
    </script>
@endpush


@section('content')
    <div class="container" style="padding-top: 100px;">
        <h1>Rechercher des trajets</h1>
        <p>Veuillez entrer vos critères de recherche ci-dessous :</p>

        <form id="searchForm" method="GET" action="/trajets/search">
            <div style="display:flex;gap:16px;align-items:flex-start;">
                <!-- Gauche : recherche (Depart/Destination en haut) + carte + résultats -->
                <div style="flex:1; min-width:0;">
                    <div style="display:flex;gap:8px;flex-wrap:wrap;justify-content:flex-start;align-items:center;margin-bottom:12px;">
                        <input type="text" name="Depart" id="Depart" placeholder="Départ" class="form-control" style="max-width:280px;">
                        <button type="button" id="swapBtn" class="btn btn-outline-secondary" title="Intervertir départ/destination" style="height:38px;align-self:center;">⇄</button>
                        <input type="text" name="Destination" id="Destination" placeholder="Destination" class="form-control" style="max-width:280px;">
                        <input type="date" name="DateTrajet" class="form-control" style="max-width:170px;">
                    </div>

                    <!-- Map -->
                    <div id="map" style="height:400px; width:100%; margin-top:8px;"></div>

                    <!-- Réservations et résultats sous la carte -->
                    <div id="myReservations" style="margin-top: 12px;">
                        <!-- Rempli dynamiquement par resources/js/rechercher.js -->
                    </div>

                    <div id="results" style="margin-top: 8px;">
                        @isset($trajets)
                            @forelse($trajets as $t)
                                @php
                                    $maxPlaces = max(0, (int)($t->PlacesDisponibles ?? 0));
                                    $userRole = session('utilisateur_role', '');
                                    $isConducteur = (strtolower($userRole) === 'conducteur');
                                @endphp
                                <div class="trajet card mb-2 p-2" data-id="{{ $t->IdTrajet }}">
                                    <div><strong>{{ $t->NomConducteur ?? "Trajet #{$t->IdTrajet}" }}</strong></div>
                                    <div>Départ: {{ $t->Depart }} — Destination: {{ $t->Destination }}</div>
                                    <div>Date: {{ $t->DateTrajet }} — Heure: {{ $t->HeureTrajet }}</div>
                                    <div>Places: <span class="places-dispo">{{ $t->PlacesDisponibles }}</span> — Prix: {{ number_format($t->Prix, 2) }}$</div>

                                    @if(!$isConducteur)
                                        <div class="reserve-controls" style="display:flex;gap:6px;align-items:center;justify-content:center;margin-top:6px;">
                                            <select class="places-select form-select form-select-sm" data-id="{{ $t->IdTrajet }}" 
                                                style="width:48px;max-width:48px;padding:.12rem .25rem;font-size:.82rem;height:30px;" 
                                                {{ $maxPlaces === 0 ? 'disabled' : '' }}>
                                                @for($i = 1; $i <= max(1, $maxPlaces); $i++)
                                                    <option value="{{ $i }}" {{ $i > $maxPlaces ? 'disabled' : '' }}>{{ $i }}</option>
                                                @endfor
                                            </select>
                                            <button class="btn-add btn btn-sm btn-primary" data-id="{{ $t->IdTrajet }}" 
                                                {{ $maxPlaces === 0 ? 'disabled' : '' }}>Réserver ce trajet</button>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <p>Aucun trajet trouvé.</p>
                            @endforelse
                        @endisset
                    </div>
                </div>

                <!-- Droite : filtres / options -->
                <aside style="width:320px;border-left:1px solid #eee;padding-left:12px;box-sizing:border-box;">
                    <h4 style="margin-top:0;margin-bottom:8px;">Filtres & Options</h4>

                    <label style="display:block;margin-bottom:8px;">
                        Prix max
                        <input type="number" name="PrixMax" step="0.01" min="0" placeholder="Prix max" class="form-control" style="width:140px;">
                    </label>

                    <label style="display:block;margin-bottom:8px;">
                        Places ≥
                        <input type="number" name="PlacesMin" min="1" placeholder="Places ≥" class="form-control" style="width:100px;">
                    </label>

                    <label style="display:block;margin-bottom:8px;">
                        TypeConversation
                        <select name="TypeConversation" class="form-control" style="width:160px;">
                            <option value="">Type conv.</option>
                            <option value="Silencieux">Silencieux</option>
                            <option value="Normal">Normal</option>
                            <option value="Bavard">Bavard</option>
                        </select>
                    </label>

                    <div style="display:flex;flex-direction:column;gap:6px;margin-top:6px;">
                        <label style="display:flex;align-items:center;gap:8px;">
                            <input type="checkbox" name="AnimauxAcceptes" value="1"> Animaux acceptés
                        </label>
                        <label style="display:flex;align-items:center;gap:8px;">
                            <input type="checkbox" name="Musique" value="1"> Musique
                        </label>
                        <label style="display:flex;align-items:center;gap:8px;">
                            <input type="checkbox" name="Fumeur" value="1"> Fumeur
                        </label>
                    </div>

                    <div style="margin-top:14px;">
                        <button type="submit" class="btn btn-primary">Rechercher</button>
                        <button type="reset" class="btn btn-outline-secondary" style="margin-left:8px;">Réinitialiser</button>
                    </div>
                </aside>
            </div>
        </form>
    </div>


    <script>
        let pointDepart;
        let pointDestination;
        let map;
        let departAutocomplete;
        let destinationAutocomplete;
        let departMarker;
        let destinationMarker;
        let directionsService;
        let directionsRenderer;

        window.initMap = function () {
            map = new google.maps.Map(document.getElementById("map"), {
                center: { lat: 45.5017, lng: -73.5673 },
                zoom: 6,
            });

            // Directions
            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer({ suppressMarkers: false, preserveViewport: false });
            directionsRenderer.setMap(map);

            const departInput = document.getElementById("Depart");
            const destinationInput = document.getElementById("Destination");
            const form = document.getElementById("searchForm");
            const swapBtn = document.getElementById("swapBtn");

            departAutocomplete = new google.maps.places.Autocomplete(departInput);
            destinationAutocomplete = new google.maps.places.Autocomplete(destinationInput);

            function drawRoute() {
                if (!departMarker || !destinationMarker) {
                    directionsRenderer.set('directions', null);
                    return;
                }

                const request = {
                    origin: departMarker.getPosition(),
                    destination: destinationMarker.getPosition(),
                    travelMode: google.maps.TravelMode.DRIVING,
                    drivingOptions: { departureTime: new Date() }
                };

                directionsService.route(request, (result, status) => {
                    if (status === google.maps.DirectionsStatus.OK || status === 'OK') {
                        directionsRenderer.setDirections(result);
                        try {
                            const bounds = new google.maps.LatLngBounds();
                            const leg = result.routes[0].legs[0];
                            bounds.extend(leg.start_location);
                            bounds.extend(leg.end_location);
                            map.fitBounds(bounds);
                        } catch (e) {}
                    } else {
                        directionsRenderer.set('directions', null);
                    }
                });
            }

            function clearRoute() {
                if (directionsRenderer) directionsRenderer.set('directions', null);
            }

            function placeToMarker(place, isDepart) {
                if (!place || !place.geometry) return;
                const location = place.geometry.location;
                if (isDepart) {
                    if (departMarker) departMarker.setMap(null);
                    departMarker = new google.maps.Marker({
                        map: map,
                        position: location,
                        title: "Départ",
                        icon: "http://maps.google.com/mapfiles/ms/icons/red-dot.png"
                    });
                } else {
                    if (destinationMarker) destinationMarker.setMap(null);
                    destinationMarker = new google.maps.Marker({
                        map: map,
                        position: location,
                        title: "Destination",
                        icon: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png"
                    });
                }
                drawRoute();
            }

            function removeDepartMarker() {
                if (departMarker) {
                    departMarker.setMap(null);
                    departMarker = null;
                }
                clearRoute();
            }
            function removeDestinationMarker() {
                if (destinationMarker) {
                    destinationMarker.setMap(null);
                    destinationMarker = null;
                }
                clearRoute();
            }

            // swap handler: values + marker refs + icons/titles
            if (swapBtn) {
                swapBtn.addEventListener('click', () => {
                    // swap input values
                    const tmpVal = departInput.value;
                    departInput.value = destinationInput.value;
                    destinationInput.value = tmpVal;

                    // swap marker references
                    const tmpMarker = departMarker;
                    departMarker = destinationMarker;
                    destinationMarker = tmpMarker;

                    // update marker titles/icons to match leur nouveau rôle
                    if (departMarker) {
                        departMarker.setTitle('Départ');
                        departMarker.setIcon("http://maps.google.com/mapfiles/ms/icons/red-dot.png");
                    }
                    if (destinationMarker) {
                        destinationMarker.setTitle('Destination');
                        destinationMarker.setIcon("http://maps.google.com/mapfiles/ms/icons/blue-dot.png");
                    }

                    // redraw route (will clear if one is missing)
                    drawRoute();
                });
            }

            departAutocomplete.addListener('place_changed', () => {
                const place = departAutocomplete.getPlace();
                if (!place || !place.geometry) return;
                placeToMarker(place, true);
            });

            destinationAutocomplete.addListener('place_changed', () => {
                const place = destinationAutocomplete.getPlace();
                if (!place || !place.geometry) return;
                placeToMarker(place, false);
            });

            // si l'utilisateur vide le champ (supprime texte), enlever le marker correspondant
            departInput.addEventListener('input', () => {
                if (!departInput.value || departInput.value.trim() === '') {
                    removeDepartMarker();
                }
            });
            destinationInput.addEventListener('input', () => {
                if (!destinationInput.value || destinationInput.value.trim() === '') {
                    removeDestinationMarker();
                }
            });

            // si le champ perd le focus et aucun marker n'existe mais les deux valeurs sont présentes essayer de géocoder
            departInput.addEventListener('blur', () => {
                if (!departMarker && departInput.value && destinationInput.value) {
                    const geocoder = new google.maps.Geocoder();
                    geocoder.geocode({ address: departInput.value }, (results, status) => {
                        if (status === 'OK' && results[0]) {
                            placeToMarker(results[0], true);
                        }
                    });
                }
            });
            destinationInput.addEventListener('blur', () => {
                if (!destinationMarker && departInput.value && destinationInput.value) {
                    const geocoder = new google.maps.Geocoder();
                    geocoder.geocode({ address: destinationInput.value }, (results, status) => {
                        if (status === 'OK' && results[0]) {
                            placeToMarker(results[0], false);
                        }
                    });
                }
            });

            // gérer reset du formulaire (bouton Réinitialiser)
            if (form) {
                form.addEventListener('reset', () => {
                    // timeout pour laisser le navigateur vider les champs avant nettoyage
                    setTimeout(() => {
                        removeDepartMarker();
                        removeDestinationMarker();
                        clearRoute();
                        // recentrer la carte si vous voulez
                        map.setCenter({ lat: 45.5017, lng: -73.5673 });
                        map.setZoom(6);
                    }, 50);
                });
            }
        }
    </script>

@endsection
