@extends('layouts.app')

@section('title', 'Mes réservations')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/reservations.css') }}?v={{ time() }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('content')
<div class="reservations-page">
    <div class="reservations-container">

        <div class="reservations-header">
            <h1 class="reservations-title">
                <i class="fas fa-calendar-alt"></i> Mes réservations
            </h1>
            <p class="reservations-subtitle">Gérez vos réservations de covoiturage</p>
        </div>

        @if(session('success'))
            <div class="alert success" id="success-alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert error" id="error-alert">
                <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
            </div>
        @endif

        <script>
            setTimeout(() => {
                const successAlert = document.getElementById('success-alert');
                const errorAlert = document.getElementById('error-alert');
                [successAlert, errorAlert].forEach(alert => {
                    if(alert){
                        alert.style.transition = 'opacity 0.8s';
                        alert.style.opacity = 0;
                        setTimeout(() => alert.remove(), 800);
                    }
                });
            }, 3000);
        </script>

        @forelse($reservations as $resa)
        @php
            $isConducteur = session('utilisateur_role') === 'Conducteur';
        @endphp

        <div class="reservation-card">

            <div class="reservation-header">
                @if($isConducteur)
                    <div class="conducteur-info">
                        <i class="fas fa-user-circle"></i>
                        <span class="conducteur-nom">Trajet #{{ $resa->IdTrajet }}</span>
                        <div class="small text-muted">Réservé par : {{ $resa->PrenomPassager ?? $resa->Prenom }} {{ $resa->NomPassager ?? $resa->Nom }}</div>
                    </div>
                @else
                    <div class="conducteur-info">
                        <i class="fas fa-user-circle"></i>
                        <span class="conducteur-nom">{{ $resa->PrenomConducteur }} {{ $resa->NomConducteur }}</span>
                    </div>
                @endif
                 <div class="reservation-status">
                     <span class="badge badge-reservation">Réservé</span>
                 </div>
             </div>

            <div class="reservation-route">
                <div class="route-points">
                    <div class="depart">
                        <i class="fas fa-map-marker-alt depart-icon"></i>
                        <span class="point-label">{{ $resa->Depart }}</span>
                    </div>
                    <div class="route-arrow">
                        <img src="{{ asset('images/flecheDest.png') }}" alt="Flèche" class="fleche-image">
                    </div>
                    <div class="destination">
                        <i class="fas fa-map-marker-alt destination-icon"></i>
                        <span class="point-label">{{ $resa->Destination }}</span>
                    </div>
                </div>
            </div>

            <div class="reservation-details">
                <div class="detail-item">
                    <i class="fas fa-calendar-alt"></i>
                    <span>{{ \Carbon\Carbon::parse($resa->DateTrajet)->format('d/m/Y') }}</span>
                </div>
                <div class="detail-item">
                    <i class="fas fa-clock"></i>
                    <span>{{ $resa->HeureTrajet }}</span>
                </div>
                <div class="detail-item">
                    <i class="fas fa-user-friends"></i>
                    <span>{{ $resa->PlacesReservees }} places</span>
                </div>
                <div class="detail-item">
                    <i class="fas fa-dollar-sign"></i>
                    <span>{{ number_format($resa->Prix * $resa->PlacesReservees, 2) }} $</span>
                </div>
            </div>

            <div class="reservation-actions">
                @if($isConducteur)
                    <a href="{{ route('messages.show', $resa->IdPassager ?? $resa->IdPassager) }}" class="btn-contact btn btn-sm btn-outline-primary" title="Contacter le passager">
                        <i class="fas fa-envelope"></i> Contacter le passager
                    </a>
                    <form action="{{ url('/mes-reservations/'.$resa->IdReservation.'/update') }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="PlacesReservees" value="{{ $resa->PlacesReservees }}">
                        <button type="submit" class="btn btn-sm btn-success" title="Confirmer la réservation">Confirmer</button>
                    </form>
                @else
                    <a href="{{ route('messages.show', $resa->IdConducteur) }}" class="btn-contact btn btn-sm btn-outline-primary" title="Contacter le conducteur">
                        <i class="fas fa-envelope"></i> Contacter le conducteur
                    </a>

                    <button type="button" class="btn-update" data-bs-toggle="modal" data-bs-target="#modifyReservationModal"
                        data-reservation-id="{{ $resa->IdReservation }}" data-trajet-id="{{ $resa->IdTrajet }}"
                        data-current-reserved="{{ $resa->PlacesReservees }}">
                        <i class="fas fa-edit"></i> Modifier
                    </button>

                    <form action="{{ route('mes-reservations.destroy', $resa->IdReservation) }}" method="POST" class="cancel-form" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-cancel">
                            <i class="fas fa-trash-alt"></i> Annuler
                        </button>
                    </form>
                @endif
            </div>

        </div>
        @empty
        <div class="empty-reservations">
            <div class="empty-reservations-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <h3>Pas de réservations</h3>
            <p>Vous n'avez aucun trajet réservé pour le moment.</p>
            <a href="/" class="btn">
                <i class="fas fa-search"></i> Rechercher des trajets
            </a>
        </div>
        @endforelse

    </div>
        </div>

<div class="modal fade" id="modifyReservationModal" tabindex="-1" aria-labelledby="modifyReservationModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="modifyReservationForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="modifyReservationModalLabel">Modifier la réservation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <p>Places disponibles pour ce trajet : <span id="availableSeats"></span></p>
                    <div class="mb-3">
                        <label for="newPlaces" class="form-label">Nombre de places à réserver</label>
                        <input type="number" class="form-control" id="newPlaces" name="PlacesReservees" min="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const modifyReservationModal = document.getElementById('modifyReservationModal');

    modifyReservationModal.addEventListener('show.bs.modal', async event => {
        const button = event.relatedTarget;
        const reservationId = button.getAttribute('data-reservation-id');
        const trajetId = button.getAttribute('data-trajet-id');
        const currentReserved = parseInt(button.getAttribute('data-current-reserved'), 10);

        const form = document.getElementById('modifyReservationForm');
        form.action = `/mes-reservations/${reservationId}/update`;

        let placesDisponibles = 0;
        try {
            const res = await fetch(`/trajets/${trajetId}/availability`);
            if(res.ok){
                const data = await res.json();
                placesDisponibles = parseInt(data.places_disponibles || 0, 10);
            }
        } catch(err){
            console.error('Erreur fetch disponibilité :', err);
        }

        document.getElementById('availableSeats').innerText = placesDisponibles;

        const maxPossible = placesDisponibles + currentReserved;
        const newPlacesInput = document.getElementById('newPlaces');
        newPlacesInput.max = maxPossible;
        newPlacesInput.min = 1;
        newPlacesInput.value = currentReserved;

        newPlacesInput.addEventListener('input', () => {
            let val = parseInt(newPlacesInput.value, 10);
            const max = parseInt(newPlacesInput.max, 10);
            const min = parseInt(newPlacesInput.min, 10);
            if (val > max) newPlacesInput.value = max;
            if (val < min) newPlacesInput.value = min;
        });
    });

    document.getElementById('modifyReservationForm').addEventListener('submit', function(e){
        const input = document.getElementById('newPlaces');
        const min = parseInt(input.min,10);
        const max = parseInt(input.max,10);
        const val = parseInt(input.value,10);

        if(isNaN(val) || val < min || val > max){
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: `Le nombre de places doit être entre ${min} et ${max}.`
            });
        }
    });

    const cancelForms = document.querySelectorAll('.cancel-form');
    cancelForms.forEach(form => {
        form.addEventListener('submit', function(e){
            e.preventDefault();
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: "Cette réservation sera annulée !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, annuler',
                cancelButtonText: 'Non, garder'
            }).then((result) => {
                if(result.isConfirmed){
                    form.submit();
                }
            });
        });
    });

});
</script>

@endsection
