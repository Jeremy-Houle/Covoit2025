@extends('layouts.app')

@section('title', 'Publier - Covoit2025')

@push('styles')
    @vite(['resources/css/publier.css'])
@endpush

@section('content')
    <div class="publish-container" style="padding-top: 100px; text-align: center;">
        <div class="container-ss">
            <div class=" map" id="map">

            </div>
            <div class="form-card">
                @if(session('success'))
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif
                <h3 style="margin-bottom: 20px;">Publier un trajet</h3>
                <form action="{{ route('trajets.store') }}" method="POST" id="trajetForm">
                    <div class="scroll-view ">
                        @csrf
                        <input type="hidden" name="IdConducteur" value="{{ session('utilisateur_id') }}">
                        <input type="hidden" name="NomConducteur" value="{{ session('utilisateur_prenom') }}">
                        <input type="hidden" name="Distance" id="Distance">


                        <label for="Depart">Départ:</label>
                        <input type="text" name="Depart" id="Depart" maxlength="150" required><br>

                        <label for="Destination">Destination:</label>
                        <input type="text" name="Destination" id="Destination" maxlength="150" required><br>

                        <label for="DateTrajet">Date du trajet:</label>
                        <input type="date" name="DateTrajet" id="DateTrajet" required><br>

                        <label for="HeureTrajet">Heure du trajet:</label>
                        <input type="time" name="HeureTrajet" id="HeureTrajet" required><br>

                        <label for="PlacesDisponibles">Places disponibles:</label>
                        <input type="number" name="PlacesDisponibles" id="PlacesDisponibles" min="1" required><br>

                        <label for="Prix">Prix ($):</label>
                        <input type="number" step="0.01" name="Prix" id="Prix" required><br>



                        <label for="TypeConversation">Type de conversation:</label>
                        <select name="TypeConversation" id="TypeConversation" required>
                            <option value="Silencieux">Silencieux</option>
                            <option value="Normal">Normal</option>
                            <option value="Bavard">Bavard</option>
                        </select><br>
                        <div class="option-group" style="margin-top: 20px;">
                            <label for="AnimauxAcceptes">
                                <input type="checkbox" name="AnimauxAcceptes" id="AnimauxAcceptes" value="1">
                                <span>Animaux Acceptés</span>
                            </label>

                            <label for="Musique">
                                <input type="checkbox" name="Musique" id="Musique" value="1">
                                <span>Musique</span>
                            </label>

                            <label for="Fumeur">
                                <input type="checkbox" name="Fumeur" id="Fumeur" value="1">
                                <span>Fumeur</span>
                            </label>
                        </div>

                    </div>
                    <button type="submit" id="submitTrajet">Créer Trajet</button>
                </form>
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
                styles: [
                    {
                        "featureType": "all",
                        "elementType": "geometry",
                        "stylers": [{ "color": "#e5e7eb" }]
                    },
                    {
                        "featureType": "all",
                        "elementType": "labels.text.fill",
                        "stylers": [{ "color": "#444444" }]
                    },
                    {
                        "featureType": "road",
                        "elementType": "geometry",
                        "stylers": [{ "color": "#cbd5e1" }]
                    },
                    {
                        "featureType": "water",
                        "elementType": "geometry",
                        "stylers": [{ "color": "#93c5fd" }]
                    },
                    {
                        "featureType": "poi",
                        "elementType": "geometry",
                        "stylers": [{ "color": "#f3f4f6" }]
                    }
                ]
            });

            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer({ map });

            departAutocomplete = new google.maps.places.Autocomplete(
                document.getElementById("Depart"), {
                componentRestrictions: { country: "ca" },
                fields: ["geometry", "name"]
            }
            );
            destinationAutocomplete = new google.maps.places.Autocomplete(
                document.getElementById("Destination"), {
                componentRestrictions: { country: "ca" },
                fields: ["geometry", "name"]
            }
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

        }


        function addMarker(type, position) {
            const color = type === "depart" ? "red" : "blue";

            const marker = new google.maps.Marker({
                position,
                map,
                icon: {
                    url: `http://maps.google.com/mapfiles/ms/icons/${color}-dot.png`,
                },
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

            if (!depart || !destination) {
                return
            }
            const request = {
                origin: depart,
                destination: destination,
                travelMode: google.maps.TravelMode.DRIVING,
            };

            directionsService.route(request, (result, status) => {
                if (status === google.maps.DirectionsStatus.OK) {
                    directionsRenderer.setDirections(result);

                    const distance = result.routes[0].legs[0].distance.value / 1000;
                    document.getElementById("Distance").value = distance.toFixed(2);

                } else {
                    alert("Erreur: " + status);
                }
            });



        }

        window.initMap = initMap;
    </script>
@endsection