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
    <div class="container" style="padding-top: 100px; text-align: center;">
        <h1>Rechercher des trajets</h1>
        <p>Veuillez entrer vos critères de recherche ci-dessous :</p>
        <div>
            <form id="searchForm" method="GET" action="/trajets/search">
                <input type="text" name="Depart" id="Depart" placeholder="Départ">
                <input type="text" name="Destination" id="Destination" placeholder="Destination">
                <button type="submit" class="btn btn-primary">Rechercher</button>
            </form>
        </div>

        <!-- AJOUTER : container pour la carte -->
        <div id="map" style="height:400px; width:100%; margin-top:16px;"></div>

        <!-- AJOUT : container pour les réservations de l'utilisateur -->
        <div id="myReservations" style="margin-top: 12px;">
            <!-- Rempli dynamiquement par resources/js/rechercher.js -->
        </div>

        <!-- AJOUT : container pour les résultats sous la carte -->
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


    <script>
        let pointDepart;
        let pointDestination;
        let map;
        let departAutocomplete;
        let destinationAutocomplete;
        let departMarker;
        let destinationMarker;

        window.initMap = function () {
            map = new google.maps.Map(document.getElementById("map"), {
                center: { lat: 45.5017, lng: -73.5673 },
                zoom: 6,
            });
            const departInput = document.getElementById("Depart");
            const destinationInput = document.getElementById("Destination");

            departAutocomplete = new google.maps.places.Autocomplete(departInput);
            destinationAutocomplete = new google.maps.places.Autocomplete(destinationInput);

            departAutocomplete.addListener('place_changed', () => {
                const place = departAutocomplete.getPlace();
                if (!place.geometry) return;

                const location = place.geometry.location;
                if (departMarker) departMarker.setMap(null);
                departMarker = new google.maps.Marker({
                    map: map,
                    position: location,
                    title: "Départ",
                    icon: "http://maps.google.com/mapfiles/ms/icons/red-dot.png"
                });
                map.setCenter(location);
            });

            destinationAutocomplete.addListener('place_changed', () => {
                const place = destinationAutocomplete.getPlace();
                if (!place.geometry) return;

                const location = place.geometry.location;
                if (destinationMarker) destinationMarker.setMap(null);
                destinationMarker = new google.maps.Marker({
                    map: map,
                    position: location,
                    title: "Destination",
                    icon: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png"
                });
                map.setCenter(location);
            });
        }
    </script>

@endsection

{{-- 2. Logique géographique (recherche par proximité)

Principe :

Le conducteur définit des coordonnées GPS pour départ et arrivée.

Le passager cherche un trajet proche de son point de départ et d’arrivée (dans un rayon de X km).

Logique :

Calculer la distance géographique (avec la formule de Haversine ou une fonction ST_Distance_Sphere() en SQL).

Exemple :

SELECT *
FROM trajets
WHERE ST_Distance_Sphere(point(depart_long, depart_lat), point(? , ?)) < 5000 AND ST_Distance_Sphere(point(arrivee_long,
    arrivee_lat), point(?, ?)) < 5000; Avantage : plus réaliste pour les grandes villes. Inconvénient : nécessite de
    gérer les coordonnées et des calculs plus lourds. -->--}}