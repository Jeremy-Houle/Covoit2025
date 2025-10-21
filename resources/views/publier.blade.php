@extends('layouts.app')

@section('title', 'Publier - Covoit2025')

@push('styles')
    @vite(['resources/css/publier.css'])
@endpush

@section('content')
    <div class="container" style="padding-top: 100px; text-align: center;">
        <div class="container-ss">
            <div class="shadowed-card map" id="map" style="padding: 15%;">

            </div>
            <div class="shadowed-card">
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

                        <label for="AnimauxAcceptes">
                            <input type="checkbox" name="AnimauxAcceptes" id="AnimauxAcceptes" value="1">
                            Animaux Acceptés
                        </label><br>

                        <label for="TypeConversation">Type de conversation:</label>
                        <select name="TypeConversation" id="TypeConversation" required>
                            <option value="Silencieux">Silencieux</option>
                            <option value="Normal">Normal</option>
                            <option value="Bavard">Bavard</option>
                        </select><br>

                        <label for="Musique">
                            <input type="checkbox" name="Musique" id="Musique" value="1">
                            Musique
                        </label><br>

                        <label for="Fumeur">
                            <input type="checkbox" name="Fumeur" id="Fumeur" value="1">
                            Fumeur
                        </label><br>

                    </div>
                    <button type="submit" hidden id="submitTrajet">Créer Trajet</button>
                    <button id="verifyTrajet">Vérifier Trajet</button>
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
            });

            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer({ map });

            departAutocomplete = new google.maps.places.Autocomplete(
                document.getElementById("Depart")
            );
            destinationAutocomplete = new google.maps.places.Autocomplete(
                document.getElementById("Destination")
            );

            departAutocomplete.bindTo("bounds", map);
            destinationAutocomplete.bindTo("bounds", map);

            departAutocomplete.addListener("place_changed", () => {
                const place = departAutocomplete.getPlace();
                if (!place.geometry) return;
                addMarker("depart", place.geometry.location);
            });

            destinationAutocomplete.addListener("place_changed", () => {
                const place = destinationAutocomplete.getPlace();
                if (!place.geometry) return;
                addMarker("destination", place.geometry.location);
            });

            document.getElementById("verifyTrajet").addEventListener("click", function (e) {
                e.preventDefault(); 

                afficherTrajet(); 

                document.getElementById("verifyTrajet").setAttribute("hidden", true);
                document.getElementById("submitTrajet").removeAttribute("hidden");
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
                alert("Veuillez remplir les deux champs.");
                return;
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