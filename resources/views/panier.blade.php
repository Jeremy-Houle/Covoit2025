@extends('layouts.app')

@section('title', 'Mon Panier - Covoit2025')

@push('styles')
@vite(['resources/css/panier.css'])
@endpush

@push('scripts')
@vite(['resources/js/panier.js'])
@endpush

@section('content')
<div class="panier-page">
    <div class="panier-container">
        <!-- Header Section -->
        <div class="panier-header">
            <h1 class="panier-title">
                <i class="fas fa-shopping-cart"></i> Mon Panier
            </h1>
            <p class="panier-subtitle">Gérez vos réservations de covoiturage</p>
        </div>

        @if($paiements->isEmpty())
            <div class="empty-cart">
                <div class="empty-cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3>Votre panier est vide</h3>
                <p>Vous n'avez aucun trajet réservé pour le moment.</p>
                <a href="/" class="btn">
                    <i class="fas fa-search"></i> Rechercher des trajets
                </a>
            </div>
        @else
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('payment_success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('payment_success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ session('error') }}
                </div>
            @endif
            <div class="main-content">
                <div class="trajets-section">
                    <h2><i class="fas fa-route"></i> Vos trajets réservés</h2>
                    
                    @foreach($paiements as $paiement)
                        <div class="trajet-card">
                            <div class="trajet-header">
                                <div class="conducteur-info">
                                    <i class="fas fa-user-circle"></i>
                                    <span class="conducteur-nom">{{ $paiement->ConducteurNom }} {{ $paiement->ConducteurPrenom }}</span>
                                </div>
                                <div class="trajet-status">
                                    <span class="badge badge-waiting">En attente de paiement</span>
                                </div>
                            </div>

                            <div class="trajet-route">
                                <div class="route-points">
                                    <div class="depart">
                                        <i class="fas fa-map-marker-alt depart-icon"></i>
                                        <span class="point-label">{{ $paiement->Depart }}</span>
                                    </div>
                                    <div class="route-arrow">
                                        <img src="{{ asset('images/flecheDest.png') }}" alt="Flèche" class="fleche-image">
                                    </div>
                                    <div class="destination">
                                        <i class="fas fa-map-marker-alt destination-icon"></i>
                                        <span class="point-label">{{ $paiement->Destination }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="trajet-details">
                                <div class="detail-row">
                                    <div class="detail-item">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span>{{ \Carbon\Carbon::parse($paiement->DateTrajet)->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-clock"></i>
                                        <span>{{ \Carbon\Carbon::parse($paiement->HeureTrajet)->format('H:i') }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-road"></i>
                                        <span>{{ $paiement->Distance }} km</span>
                                    </div>
                                </div>
                            </div>

                            <div class="trajet-pricing">
                                <div class="price-breakdown">
                                    <div class="price-item">
                                        <span>Prix par personne :</span>
                                        <span class="price-value">{{ $paiement->NombrePlaces > 0 ? number_format($paiement->Montant / $paiement->NombrePlaces, 2) : '0.00' }} $</span>
                                        <!-- Debug: Montant={{ $paiement->Montant }}, Places={{ $paiement->NombrePlaces }}, Calcul={{ $paiement->Montant / $paiement->NombrePlaces }} -->
                                    </div>
                                    <div class="price-item">
                                        <span>Nombre de places :</span>
                                        <span class="price-value">{{ $paiement->NombrePlaces }}</span>
                                    </div>
                                    <div class="price-item total">
                                        <span>Total :</span>
                                        <span class="price-value">{{ $paiement->Montant }} $</span>
                                    </div>
                                </div>
                            </div>

                            <div class="trajet-actions">
                                <button type="button" class="btn btn-pay" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#paymentConfirmationModal"
                                        data-conducteur-id="{{ $paiement->IdConducteur }}"
                                        data-utilisateur-id="{{ session('utilisateur_id', 1) }}"
                                        data-paiement-id="{{ $paiement->IdPaiement }}"
                                        data-conducteur-nom="{{ $paiement->ConducteurNom }} {{ $paiement->ConducteurPrenom }}"
                                        data-depart="{{ $paiement->Depart }}"
                                        data-destination="{{ $paiement->Destination }}"
                                        data-date="{{ \Carbon\Carbon::parse($paiement->DateTrajet)->format('d/m/Y') }}"
                                        data-heure="{{ \Carbon\Carbon::parse($paiement->HeureTrajet)->format('H:i') }}"
                                        data-places="{{ $paiement->NombrePlaces }}"
                                        data-montant="{{ $paiement->Montant }}">
                                    <i class="fas fa-credit-card"></i>
                                    Payer ce trajet
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="summary-section">
                    <div class="summary-card">
                        <h3><i class="fas fa-receipt"></i> Récapitulatif</h3>
                        
                        <div class="summary-items">
                            @foreach($paiements as $paiement)
                                <div class="summary-item">
                                    <div class="summary-route">
                                        <span class="route-text">{{ $paiement->Depart }}</span>
                                        <i class="fas fa-arrow-right" style="color: var(--gray-400); font-size: 12px;"></i>
                                        <span class="route-text">{{ $paiement->Destination }}</span>
                                    </div>
                                    <div class="summary-price">
                                        {{ $paiement->Montant }} $
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <hr class="summary-divider">

                        <div class="total-section">
                            <div class="total-item">
                                <span class="total-label">Total général :</span>
                                <span class="total-amount" data-paiements="{{ $paiements->pluck('Montant')->toJson() }}">Calcul en cours...</span>
                            </div>
                        </div>

                        
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>

<!-- Modal de confirmation de paiement -->
<div class="modal fade" id="paymentConfirmationModal" tabindex="-1" aria-labelledby="paymentConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentConfirmationModalLabel">
                    <i class="fas fa-credit-card"></i> Confirmation de paiement
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="payment-summary">
                    <h6><i class="fas fa-route"></i> Détails du trajet</h6>
                    <div class="trajet-details-modal">
                        <p><strong>Conducteur :</strong> <span id="modalConducteur"></span></p>
                        <p><strong>Trajet :</strong> <span id="modalDepart"></span> → <span id="modalDestination"></span></p>
                        <p><strong>Date :</strong> <span id="modalDate"></span> à <span id="modalHeure"></span></p>
                        <p><strong>Places :</strong> <span id="modalPlaces"></span></p>
                    </div>
                    
                    <hr>
                    
                    <div class="payment-amount">
                        <h6><i class="fas fa-dollar-sign"></i> Montant à payer</h6>
                        <div class="amount-display">
                            <span id="modalMontant" class="amount-value"></span>
                        </div>
                    </div>
                    
                    <div class="payment-warning">
                        
                           
                       
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Annuler
                </button>
                <form id="confirmPaymentForm" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-credit-card"></i> Confirmer le paiement
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection