@extends('layouts.app')

@section('title', 'Publier - Covoit2025')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/publier.css') }}?v={{ time() }}">
    <style>
        .mes-trajets {
            margin-top: 20px;
            max-height: 500px;
            overflow-y: auto;
        }
        .mes-trajets table {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .mes-trajets thead {
            background-color: #f5f5f5;
        }
        .mes-trajets tbody tr:hover {
            background-color: #f0f8ff;
        }
        .trash-button {
            background: transparent;
            border: none;
            color: #e74c3c;
            cursor: pointer;
            font-size: 1.1rem;
        }
        .mes-trajets h2 {
            color: #333;
            margin-bottom: 15px;
        }
        
        .mes-favoris {
            margin-top: 40px;
        }
        
        .mes-favoris h2 {
            color: #333;
            margin-bottom: 15px;
        }
        
        .publier-main {
            width: 100%;
        }
        
        .empty-message {
            margin-top: 30px;
            color: #555;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .inline-form {
            display: inline;
        }

        .modal-overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }
        .modal-content {
            background: #fff;
            border-radius: 12px;
            padding: 25px 30px;
            max-width: 400px;
            width: 90%;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            animation: fadeIn 0.25s ease-in-out;
        }
        .modal-content h3 {
            margin-bottom: 10px;
            color: #333;
            font-size: var(--font-size-lg);
        }
        .modal-content p {
            color: #555;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }
        .modal-buttons {
            display: flex;
            justify-content: space-around;
            gap: var(--spacing-md);
        }
        .btn-cancel, .btn-confirm {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: 0.2s ease;
            flex: 1;
        }
        .btn-cancel {
            background: #e0e0e0;
            color: #333;
        }
        .btn-cancel:hover {
            background: #d6d6d6;
        }
        .btn-confirm {
            background: #e74c3c;
            color: white;
        }
        .btn-confirm:hover {
            background: #c0392b;
        }
        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(-10px);}
            to {opacity: 1; transform: translateY(0);}
        }
        
        .notification-consent {
            margin-top: var(--spacing-md);
        }
        
        .consent-checkbox {
            display: flex;
            gap: var(--spacing-md);
            padding: var(--spacing-md);
            background: var(--light-blue);
            border-radius: var(--border-radius-lg);
            border: 2px solid var(--primary-blue);
            cursor: pointer;
            transition: var(--transition-normal);
        }
        
        .consent-checkbox:hover {
            background: rgba(37, 99, 235, 0.15);
        }
        
        .consent-checkbox input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: var(--primary-blue);
            flex-shrink: 0;
        }
        
        .consent-content {
            display: flex;
            gap: var(--spacing-sm);
            align-items: flex-start;
            flex: 1;
        }
        
        .consent-content i {
            color: var(--primary-blue);
            font-size: var(--font-size-lg);
            flex-shrink: 0;
            margin-top: 2px;
        }
        
        .consent-text {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        
        .consent-text strong {
            color: var(--gray-800);
            font-size: var(--font-size-sm);
        }
        
        .consent-text small {
            color: var(--gray-600);
            font-size: 0.75rem;
            line-height: 1.4;
        }
        
        .publier-container {
            padding-top: 100px;
        }
        
        @media (max-width: 768px) {
            .publier-container {
                padding-top: 80px;
            }
            
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            .mes-trajets table,
            .mes-favoris table {
                font-size: var(--font-size-sm);
            }
            
            .mes-trajets th,
            .mes-favoris th {
                padding: var(--spacing-xs) var(--spacing-sm);
                font-size: 0.8rem;
            }
            
            .mes-trajets td,
            .mes-favoris td {
                padding: var(--spacing-xs) var(--spacing-sm);
                font-size: 0.75rem;
            }
            
            .mes-trajets h2,
            .mes-favoris h2 {
                font-size: var(--font-size-lg);
            }
            
            .trash-button {
                font-size: 1rem;
                padding: var(--spacing-xs);
            }
            
            .modal-content {
                width: 95%;
                padding: var(--spacing-lg);
            }
            
            .modal-content h3 {
                font-size: var(--font-size-base);
            }
            
            .modal-content p {
                font-size: var(--font-size-sm);
            }
            
            .consent-checkbox {
                padding: var(--spacing-sm);
                gap: var(--spacing-sm);
            }
            
            .consent-content i {
                font-size: var(--font-size-base);
            }
            
            .consent-text strong {
                font-size: 0.8rem;
            }
            
            .consent-text small {
                font-size: 0.7rem;
            }
        }
        
        @media (max-width: 480px) {
            .publier-container {
                padding-top: 70px;
            }
            
            .mes-trajets,
            .mes-favoris {
                margin-top: var(--spacing-md);
            }
            
            .mes-trajets h2,
            .mes-favoris h2 {
                font-size: var(--font-size-base);
                margin-bottom: var(--spacing-sm);
            }
            
            .mes-trajets table,
            .mes-favoris table {
                font-size: 0.75rem;
            }
            
            .mes-trajets th,
            .mes-favoris th {
                padding: 6px 8px;
                font-size: 0.7rem;
            }
            
            .mes-trajets td,
            .mes-favoris td {
                padding: 6px 8px;
                font-size: 0.7rem;
            }
            
            .trash-button {
                font-size: 0.9rem;
            }
            
            .btn {
                font-size: 0.7rem !important;
                padding: 4px 8px !important;
            }
            
            .modal-content {
                padding: var(--spacing-md);
            }
            
            .modal-content h3 {
                font-size: var(--font-size-sm);
                margin-bottom: var(--spacing-xs);
            }
            
            .modal-content p {
                font-size: 0.8rem;
                margin-bottom: var(--spacing-md);
            }
            
            .modal-buttons {
                gap: var(--spacing-sm);
            }
            
            .btn-cancel, .btn-confirm {
                padding: var(--spacing-xs) var(--spacing-md);
                font-size: 0.8rem;
            }
            
            .consent-checkbox {
                padding: var(--spacing-xs) var(--spacing-sm);
            }
            
            .consent-checkbox input[type="checkbox"] {
                width: 18px;
                height: 18px;
            }
            
            .consent-content i {
                font-size: var(--font-size-sm);
            }
            
            .consent-text strong {
                font-size: 0.75rem;
            }
            
            .consent-text small {
                font-size: 0.65rem;
            }
        }
        
        @media (max-width: 360px) {
            .publier-container {
                padding-top: 65px;
            }
            
            .mes-trajets h2,
            .mes-favoris h2 {
                font-size: var(--font-size-sm);
            }
            
            .mes-trajets th,
            .mes-favoris th,
            .mes-trajets td,
            .mes-favoris td {
                padding: 4px 6px;
                font-size: 0.65rem;
            }
            
            .modal-content {
                padding: var(--spacing-sm);
            }
            
            .modal-content h3 {
                font-size: 0.8rem;
            }
            
            .modal-content p {
                font-size: 0.75rem;
            }
            
            .btn-cancel, .btn-confirm {
                padding: 6px var(--spacing-sm);
                font-size: 0.75rem;
            }
        }
    </style>
@endpush

@section('content')
<div class="publier-page">
    <div class="container publier-container">
        <div class="page-header">
            <h1 class="page-title">
                <i class="fa fa-plus-circle"></i> Publier un trajet
            </h1>
            <p class="page-subtitle">Partagez votre trajet et trouvez des covoitureurs</p>
        </div>

        
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif
       

        <div class="publier-layout">
            <div class="publier-main">
                <div class="map-section">
                    <div class="map-container-publier">
                        <div id="map"></div>
                    </div>
                    <div class="map-info">
                        <i class="fa fa-info-circle"></i>
                        <span>Utilisez les champs de recherche pour définir votre itinéraire sur la carte</span>
                    </div>
                </div>

                <div class="mes-trajets">
                    @if($mesTrajets->isEmpty()) 
                        <p class="empty-message">Vous n'avez aucun trajet publié.</p>
                    @else
                        <h2>Mes trajets publiés</h2>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Départ</th>
                                        <th>Destination</th>
                                        <th>Date</th>
                                        <th>Heure</th>
                                        <th>Places</th>
                                        <th>Prix</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($mesTrajets as $trajet)
                                        <tr>
                                            <td>{{ $trajet->Depart }}</td>
                                            <td>{{ $trajet->Destination }}</td>
                                            <td>{{ $trajet->DateTrajet }}</td>
                                            <td>{{ $trajet->HeureTrajet }}</td>
                                            <td>{{ $trajet->PlacesDisponibles }}</td>
                                            <td>{{ $trajet->Prix }} $</td>
                                            <td>
                                                <form action="{{ route('trajets.cancel', $trajet->IdTrajet) }}" method="POST" class="delete-form" data-id="{{ $trajet->IdTrajet }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="trash-button open-modal" title="Supprimer" data-id="{{ $trajet->IdTrajet }}">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                <form action="{{ route('trajets.favoris') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="IdTrajet" value="{{ $trajet->IdTrajet }}">
                                                    <button type="submit" class="btn btn-sm btn-primary" title="Sauvegarder ce trajet">
                                                        <i class="fas fa-save"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <div class="mes-favoris">
                    @if($mesFavoris->isEmpty())
                        <p class="empty-message">Vous n'avez aucun trajet dans vos sauvegardés.</p>
                    @else
                        <h2>Mes trajets sauvegardés</h2>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Départ</th>
                                        <th>Destination</th>
                                        <th>Places</th>
                                        <th>Prix</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($mesFavoris as $favori)
                                        <tr>
                                            <td>{{ $favori->Depart }}</td>
                                            <td>{{ $favori->Destination }}</td>
                                            <td>{{ $favori->PlacesDisponibles }}</td>
                                            <td>{{ $favori->Prix }} $</td>
                                            <td>
                                                <div class="action-buttons">
                                                    <button title="Réajouter ce trajet"
                                                        class="btn btn-sm btn-success btn-reajouter" 
                                                        data-depart="{{ $favori->Depart }}" 
                                                        data-destination="{{ $favori->Destination }}" 
                                                        data-places="{{ $favori->PlacesDisponibles }}" 
                                                        data-prix="{{ $favori->Prix }}">
                                                        <i class="fas fa-redo"></i> Réajouter
                                                    </button>

                                                    <form action="{{ route('favoris.delete', $favori->IdFavori) }}" method="POST" class="inline-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                            <i class="fas fa-trash-alt"></i> Enlever
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
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
                            <input type="text" name="Depart" id="Depart" placeholder="Ex: Montréal, QC" maxlength="150" required>
                        </div>
                        <div class="form-group">
                            <label for="Destination"><i class="fa fa-flag-checkered"></i> Ville d'arrivée</label>
                            <input type="text" name="Destination" id="Destination" placeholder="Ex: Québec, QC" maxlength="150" required>
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
                            <input type="number" name="PlacesDisponibles" id="PlacesDisponibles" min="1" max="6" placeholder="1-6" required>
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

                    <div class="notification-consent">
                        <label class="consent-checkbox">
                            <input type="checkbox" name="RappelEmail" id="RappelEmail" value="1">
                            <span class="consent-content">
                                <i class="fas fa-bell"></i>
                                <div class="consent-text">
                                    <strong>Recevoir un rappel par email</strong>
                                    <small>Je souhaite recevoir un email de rappel 2 heures avant le départ de mon trajet</small>
                                </div>
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

<div id="deleteModal" class="modal-overlay" style="display:none;">
    <div class="modal-content">
        <h3><i class="fas fa-exclamation-triangle" style="color:#e74c3c;"></i> Supprimer ce trajet ?</h3>
        <p>Cette action est <strong>irréversible</strong>. Voulez-vous vraiment supprimer ce trajet ?</p>
        <div class="modal-buttons">
            <button type="button" id="cancelDelete" class="btn-cancel">Annuler</button>
            <button type="button" id="confirmDelete" class="btn-confirm">Supprimer</button>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const modal = document.getElementById("deleteModal");
    const confirmBtn = document.getElementById("confirmDelete");
    const cancelBtn = document.getElementById("cancelDelete");
    let currentForm = null;

    document.querySelectorAll(".open-modal").forEach(btn => {
        btn.addEventListener("click", function() {
            const form = this.closest(".delete-form");
            currentForm = form;
            modal.style.display = "flex";
        });
    });

    confirmBtn.addEventListener("click", function() {
        if (currentForm) currentForm.submit();
    });

    cancelBtn.addEventListener("click", function() {
        modal.style.display = "none";
        currentForm = null;
    });

    modal.addEventListener("click", function(e) {
        if (e.target === modal) {
            modal.style.display = "none";
            currentForm = null;
        }
    });
});
</script>

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

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const buttons = document.querySelectorAll(".btn-reajouter");

        buttons.forEach(button => {
            button.addEventListener("click", function () {
                const depart = this.getAttribute("data-depart");
                const destination = this.getAttribute("data-destination");
                const places = this.getAttribute("data-places");
                const prix = this.getAttribute("data-prix");

                document.getElementById("Depart").value = depart;
                document.getElementById("Destination").value = destination;
                document.getElementById("PlacesDisponibles").value = places;
                document.getElementById("Prix").value = prix;

                document.getElementById("DateTrajet").value = "";
                document.getElementById("HeureTrajet").value = "";

                afficherTrajet();

                document.getElementById("trajetForm").scrollIntoView({ behavior: "smooth" });
            });
        });
    });
</script>
@endsection
