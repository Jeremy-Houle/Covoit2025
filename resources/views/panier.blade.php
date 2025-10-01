@extends('layouts.app')

@section('title', 'Mon Panier - Covoit')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @vite(['resources/css/panier.css', 'resources/js/panier.js'])
@endpush

@section('content')
<div class="panier-page">
    <div class="panier-title-section">
        <h1><i class="fas fa-shopping-cart"></i> Mon Panier</h1>
        <p class="panier-subtitle">Gérez vos réservations de covoiturage</p>
    </div>
    
    <div class="panier-container">

        @if($paiements->isEmpty())
            <div class="empty-cart">
                <div class="empty-cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3>Votre panier est vide</h3>
                <p>Vous n'avez aucun trajet réservé pour le moment.</p>
                <a href="/" class="btn btn-primary btn-lg">
                    <i class="fas fa-search"></i> Rechercher des trajets
                </a>
            </div>
        @else
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
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
                                        <span class="price-value">{{ $paiement->Prix }} $</span>
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
                                        <img src="{{ asset('images/flecheDest.png') }}" alt="Flèche" class="fleche-Tiny">
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
                                <span class="total-amount" data-paiements='@json($paiements)'>Calcul en cours...</span>
                            </div>
                        </div>

                        
                    </div>
                </div>
            </div>
        @endif

        <div class="navigation-footer">
            <a href="/" class="btn btn-secondary btn-home">
                <i class="fas fa-home"></i> Retourner à l'accueil
            </a>
        </div>
    </div>

    <div class="modal fade" id="paymentConfirmationModal" tabindex="-1" aria-labelledby="paymentConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentConfirmationModalLabel">
                        <i class="fas fa-credit-card"></i> Confirmer le paiement
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div class="payment-confirmation-content">
                        <div class="confirmation-icon">
                            <i class="fas fa-question-circle"></i>
                        </div>
                        <h4>Confirmer votre paiement</h4>
                        <p>Êtes-vous sûr de vouloir procéder au paiement de ce trajet ?</p>
                        
                        <div class="montant-total">
                            <div class="total-label">Montant total :</div>
                            <div class="total-amount" id="modalMontant"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Annuler
                    </button>
                    <form id="confirmPaymentForm" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-credit-card"></i> Confirmer le paiement
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="paymentSuccessModal" tabindex="-1" aria-labelledby="paymentSuccessModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentSuccessModalLabel">
                        <i class="fas fa-check-circle"></i> Paiement réussi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div class="payment-confirmation">
                        <div class="confirmation-icon success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h4>Paiement effectué avec succès !</h4>
                        <p>Votre réservation a été validée et confirmée.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">
                        <i class="fas fa-check"></i> Parfait !
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection