@extends('layouts.app')

@section('title', 'Publier - Covoit2025')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/publier.css') }}?v={{ time() }}">
@endpush

@section('content')
    <div class="publier-page">
        <div class="container" style="padding-top: 100px;">
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fa fa-plus-circle"></i> Publier un trajet
                </h1>
                <p class="page-subtitle">Partagez votre trajet et trouvez des covoitureurs</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            <div class="publier-layout">
                <div class="map-section">
                    <div class="map-container-publier">
                        <div id="map"></div>
                    </div>
                    <div class="map-info">
                        <i class="fa fa-info-circle"></i>
                        <span>Utilisez les champs de recherche pour définir votre itinéraire sur la carte</span>
                    </div>
                </div>

                <div class="form-section">
                    <form action="{{ route('trajets.store') }}" method="POST" id="trajetForm" class="modern-form">
                        @csrf
                        <input type="hidden" name="IdConducteur" value="{{ session('utilisateur_id') }}">
                        <input type="hidden" name="NomConducteur" value="{{ session('utilisateur_prenom') }}">
                        <input type="hidden" name="Distance" id="Distance">

                        <div class="form-group-title">
                            <i class="fa fa-route"></i> Itinéraire
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="Depart"><i class="fa fa-map-marker-alt"></i> Ville de départ</label>
                                <input type="text" name="Depart" id="Depart" placeholder="Ex: Montréal, QC" maxlength="150"
                                    required>
                            </div>

                            <div class="form-group">
                                <label for="Destination"><i class="fa fa-flag-checkered"></i> Ville d'arrivée</label>
                                <input type="text" name="Destination" id="Destination" placeholder="Ex: Québec, QC"
                                    maxlength="150" required>
                            </div>
                        </div>

                        <div class="form-group-title">
                            <i class="fa fa-calendar"></i> Date et heure
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="DateTrajet"><i class="fa fa-calendar-day"></i> Date du trajet</label>
                                <input type="date" name="DateTrajet" id="DateTrajet" required>
                            </div>

                            <div class="form-group">
                                <label for="HeureTrajet"><i class="fa fa-clock"></i> Heure de départ</label>
                                <input type="time" name="HeureTrajet" id="HeureTrajet" required>
                            </div>
                        </div>

                        <div class="form-group-title">
                            <i class="fa fa-users"></i> Places et tarif
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="PlacesDisponibles"><i class="fa fa-chair"></i> Places disponibles</label>
                                <input type="number" name="PlacesDisponibles" id="PlacesDisponibles" min="1" max="6"
                                    placeholder="1-6" required>
                            </div>

                            <div class="form-group">
                                <label for="Prix"><i class="fa fa-dollar-sign"></i> Prix par place</label>
                                <input type="number" step="0.01" name="Prix" id="Prix" placeholder="0.00" required>
                            </div>
                        </div>

                        <div class="form-group-title">
                            <i class="fa fa-cog"></i> Préférences
                        </div>

                        <div class="form-group">
                            <label for="TypeConversation"><i class="fa fa-comments"></i> Type de conversation</label>
                            <select name="TypeConversation" id="TypeConversation" class="form-select" required>
                                <option value="Silencieux">🤫 Silencieux</option>
                                <option value="Normal" selected>💬 Normal</option>
                                <option value="Bavard">🗣️ Bavard</option>
                            </select>
                        </div>

                        <div class="preferences-grid">
                            <label class="checkbox-card">
                                <input type="checkbox" name="AnimauxAcceptes" id="AnimauxAcceptes" value="1">
                                <span class="checkbox-content">
                                    <i class="fa fa-paw"></i>
                                    <span>Animaux acceptés</span>
                                </span>
                            </label>

                            <label class="checkbox-card">
                                <input type="checkbox" name="Musique" id="Musique" value="1">
                                <span class="checkbox-content">
                                    <i class="fa fa-music"></i>
                                    <span>Musique</span>
                                </span>
                            </label>

                            <label class="checkbox-card">
                                <input type="checkbox" name="Fumeur" id="Fumeur" value="1">
                                <span class="checkbox-content">
                                    <i class="fa fa-smoking"></i>
                                    <span>Fumeur</span>
                                </span>
                            </label>
                        </div>

                        <button type="submit" id="submitTrajet" class="btn-submit">
                            <i class="fa fa-paper-plane"></i>
                            <span>Publier le trajet</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let map, directionsService, directionsRenderer;
        let departAutocomplete, destinationAutocomplete;
        let departMarker, destinationMarker;

        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                center: { lat: 45.5019, lng: -73.5674 },
                zoom: 10,
            });

            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer({ map });

            setTimeout(() => {
                departAutocomplete = new google.maps.places.Autocomplete(
                    document.getElementById("Depart"),
                    { componentRestrictions: { country: "ca" }, fields: ["geometry", "name"] }
                );
                destinationAutocomplete = new google.maps.places.Autocomplete(
                    document.getElementById("Destination"),
                    { componentRestrictions: { country: "ca" }, fields: ["geometry", "name"] }
                );

                departAutocomplete.bindTo("bounds", map);
                destinationAutocomplete.bindTo("bounds", map);

                departAutocomplete.addListener("place_changed", () => {
                    const place = departAutocomplete.getPlace();
                    if (!place.geometry) return;
                    addMarker("depart", place.geometry.location);
                    afficherTrajet();
                });

                destinationAutocomplete.addListener("place_changed", () => {
                    const place = destinationAutocomplete.getPlace();
                    if (!place.geometry) return;
                    addMarker("destination", place.geometry.location);
                    afficherTrajet();
                });

                const autocompleteService = new google.maps.places.AutocompleteService();
                const placesService = new google.maps.places.PlacesService(map);

                setupEnterSelect("Depart", "depart", autocompleteService, placesService);
                setupEnterSelect("Destination", "destination", autocompleteService, placesService);

            }, 100);
        }

        function addMarker(type, position) {
            const color = type === "depart" ? "red" : "blue";
            const marker = new google.maps.Marker({
                position,
                map,
                icon: { url: `http://maps.google.com/mapfiles/ms/icons/${color}-dot.png` },
            });

            if (type === "depart") {
                if (departMarker) departMarker.setMap(null);
                departMarker = marker;
            } else {
                if (destinationMarker) destinationMarker.setMap(null);
                destinationMarker = marker;
            }

            map.panTo(position);
        }

        function afficherTrajet() {
            const depart = document.getElementById("Depart").value;
            const destination = document.getElementById("Destination").value;
            if (!depart || !destination) return;

            directionsService.route(
                {
                    origin: depart,
                    destination: destination,
                    travelMode: google.maps.TravelMode.DRIVING,
                },
                (result, status) => {
                    if (status === google.maps.DirectionsStatus.OK) {
                        directionsRenderer.setDirections(result);
                        const distance = result.routes[0].legs[0].distance.value / 1000;
                        document.getElementById("Distance").value = distance.toFixed(2);
                    }
                }
            );
        }

        function setupEnterSelect(inputId, type, autocompleteService, placesService) {
            const input = document.getElementById(inputId);
            input.addEventListener("keydown", (e) => {
                if (e.key === "Enter" || e.key == "Tab") {
                    e.preventDefault();
                    const query = input.value.trim();
                    if (!query) return;

                    autocompleteService.getPlacePredictions(
                        { input: query, componentRestrictions: { country: "ca" } },
                        (predictions, status) => {
                            if (status === google.maps.places.PlacesServiceStatus.OK && predictions?.length > 0) {
                                const first = predictions[0];
                                placesService.getDetails({ placeId: first.place_id }, (place, status2) => {
                                    if (status2 === google.maps.places.PlacesServiceStatus.OK && place.geometry) {
                                        addMarker(type, place.geometry.location);
                                        input.value = place.formatted_address || place.name;
                                        afficherTrajet();

                                    }
                                });
                            }
                        }
                    );
                }
            });

            input.addEventListener("blur", (e) => {
                e.preventDefault();
                const query = input.value.trim();
                if (!query) return;
                autocompleteService.getPlacePredictions(
                    { input: query, componentRestrictions: { country: "ca" } },
                    (predictions, status) => {
                        if (status === google.maps.places.PlacesServiceStatus.OK && predictions?.length > 0) {
                            const first = predictions[0];
                            placesService.getDetails({ placeId: first.place_id }, (place, status2) => {
                                if (status2 === google.maps.places.PlacesServiceStatus.OK && place.geometry) {
                                    addMarker(type, place.geometry.location);
                                    input.value = place.formatted_address || place.name;
                                    afficherTrajet();

                                }
                            });
                        }
                    }
                );
            });
        }

        window.initMap = initMap;

    </script>
@endsection