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
                <form action="">
                    <div class="scroll-view ">
                        @csrf
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
                    <button type="submit">Créer Trajet</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        let map;
        let departMarker;
        let destinationMarker;
        let departAutocomplete;
        let destinationAutocomplete;

        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                center: { lat: 45.5017, lng: -73.5673 },
                zoom: 8
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