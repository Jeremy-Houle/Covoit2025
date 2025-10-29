@extends('layouts.app')

@section('title', 'Mon Panier - Covoit2025')

@push('head')
<meta http-equiv="Permissions-Policy" content="unload=*">
@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('css/panier.css') }}?v={{ time() }}">
@endpush

@push('scripts')
@vite(['resources/js/panier.js'])
<script>
    window.csrfToken = '{{ csrf_token() }}';
    console.log('CSRF Token:', window.csrfToken);
</script>
@endpush
<script src="https://www.paypal.com/sdk/js?client-id=AWQqeyvmMlkT1LYUxQ-WRRLHao1rtwanQXVP9LTSYtoyCmJ1JKcKimTRLI5oVZ3kEBbRXQ2n0JqSXt9v&currency=CAD&enable-funding=paypal&disable-funding=card,credit&components=buttons"></script>
@section('content')
<div class="panier-page">
    <div class="panier-container">
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
                <a href="/rechercher" class="btn">
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
                                    </div>
                                    <div class="price-item">
                                        <span>Nombre de places :</span>
                                        <div class="places-control">
                                            <button type="button" class="btn-places btn-places-minus" 
                                                    data-paiement-id="{{ $paiement->IdPaiement }}"
                                                    {{ $paiement->NombrePlaces <= 1 ? 'disabled' : '' }}>
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <span class="places-count" data-paiement-id="{{ $paiement->IdPaiement }}" data-trajet-id="{{ $paiement->IdTrajet }}">{{ $paiement->NombrePlaces }}</span>
                                            <button type="button" class="btn-places btn-places-plus" 
                                                    data-paiement-id="{{ $paiement->IdPaiement }}">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
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
                                
                                <button type="button" 
                                        class="btn btn-remove" 
                                        onclick="supprimerDuPanier({{ $paiement->IdPaiement }}, '{{ $paiement->Depart }}', '{{ $paiement->Destination }}')">
                                    <i class="fas fa-trash"></i>
                                    Supprimer
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
                                    <div class="summary-price" data-paiement-id="{{ $paiement->IdPaiement }}">
                                        {{ $paiement->Montant }} $
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <hr class="summary-divider">

                        <div class="total-section">
                            <div class="total-item">
                                <span class="total-label">Total général :</span>
                                <span class="total-amount" data-paiements="{{ $paiements->pluck('Montant')->toJson() }}">{{ number_format($paiements->sum('Montant'), 2) }} $</span>
                            </div>
                        </div>

                        
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>


<div class="modal fade" id="paymentConfirmationModal" tabindex="-1" aria-labelledby="paymentConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content payment-modal">
            <div class="modal-header payment-modal-header">
                <div class="payment-header-content">
                    <div class="payment-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="payment-title-section">
                        <h5 class="modal-title" id="paymentConfirmationModalLabel">
                            Confirmation de paiement sécurisé
                        </h5>
                        <p class="payment-subtitle">Vérifiez les détails avant de procéder au paiement</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body payment-modal-body">
                <div class="confirmation-section">
                    <h4 class="confirmation-title">Confirmer le paiement</h4>
                    <p class="confirmation-text">Vérifiez les détails avant de procéder</p>
                </div>

                <div class="trajet-summary-card">
                    <div class="trajet-header">
                        <i class="fas fa-route"></i>
                        <h6>Détails du trajet</h6>
                    </div>
                    <div class="trajet-details-grid">
                        <div class="trajet-detail-item">
                            <span class="detail-label">Conducteur</span>
                            <span class="detail-value" id="modalConducteur"></span>
                        </div>
                        <div class="trajet-detail-item">
                            <span class="detail-label">Trajet</span>
                            <span class="detail-value">
                                <span id="modalDepart"></span> → <span id="modalDestination"></span>
                            </span>
                        </div>
                        <div class="trajet-detail-item">
                            <span class="detail-label">Date & Heure</span>
                            <span class="detail-value">
                                <span id="modalDate"></span> à <span id="modalHeure"></span>
                            </span>
                        </div>
                        <div class="trajet-detail-item">
                            <span class="detail-label">Places</span>
                            <span class="detail-value" id="modalPlaces"></span>
                        </div>
                    </div>
                </div>
                
                <div class="amount-summary-card">
                    <div class="amount-header">
                        <i class="fas fa-dollar-sign"></i>
                        <h6>Montant à payer</h6>
                    </div>
                    <div class="amount-display-large">
                        <span id="modalMontant" class="amount-value-large"></span>
                    </div>
                </div>

                <div class="payment-options-section">
                    <h6 class="payment-options-title">
                        <i class="fas fa-credit-card"></i>
                        Méthode de paiement
                    </h6>
                    
                    <div class="payment-options-grid">
                        <div class="payment-option-card" id="solde-option">
                            <div class="payment-option-header">
                                <div class="payment-option-icon solde-icon">
                                    <i class="fas fa-wallet"></i>
                                </div>
                                <div class="payment-option-info">
                                    <h6>Solde</h6>
                                    <p>Utilisez votre solde</p>
                                </div>
                            </div>
                            <div class="payment-option-action">
                                <button type="button" class="btn btn-solde" id="btn-payer-solde">
                                    Payer
                                </button>
                            </div>
                        </div>

                        <div class="payment-option-card" id="paypal-option">
                            <div class="payment-option-header">
                                <div class="payment-option-icon paypal-icon">
                                    <i class="fab fa-paypal"></i>
                                </div>
                                <div class="payment-option-info">
                                    <h6>PayPal</h6>
                                    <p>Paiement sécurisé</p>
                                </div>
                            </div>
                            <div class="payment-option-action">
                                <div id="modal-paypal-button-container"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer payment-modal-footer">
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">
                    Annuler
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

<script>
let currentDeleteData = {};

window.supprimerDuPanier = function(paiementId, depart, destination) {
    console.log('supprimerDuPanier appelée:', paiementId, depart, destination);
    
    // Sauvegarder les données pour la suppression
    currentDeleteData = {
        paiementId: paiementId,
        depart: depart,
        destination: destination
    };
    
    // Mettre à jour le texte du modal
    document.getElementById('deleteTrajetInfo').textContent = `${depart} → ${destination}`;
    
    // Ouvrir le modal
    const modal = document.getElementById('deleteConfirmationModal');
    if (modal) {
        const bootstrapModal = new bootstrap.Modal(modal, {
            backdrop: true,
            keyboard: true,
            focus: true
        });
        bootstrapModal.show();
    }
};

function confirmerSuppression() {
    if (!currentDeleteData.paiementId) {
        console.error('Aucune donnée de suppression disponible');
        return;
    }
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/remove-from-cart';
    form.style.display = 'none';
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = window.csrfToken || '{{ csrf_token() }}';
    form.appendChild(csrfToken);
    
    const paiementInput = document.createElement('input');
    paiementInput.type = 'hidden';
    paiementInput.name = 'paiement_id';
    paiementInput.value = currentDeleteData.paiementId;
    form.appendChild(paiementInput);
    
    // Fermer le modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
    if (modal) {
        modal.hide();
    }
    
    document.body.appendChild(form);
    console.log('Soumission du formulaire de suppression...');
    form.submit();
}

document.addEventListener('DOMContentLoaded', function() {
    let currentPaymentData = {};
    
    // Event listener pour le bouton de confirmation de suppression
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
            confirmerSuppression();
        });
    }
    
    const payButtons = document.querySelectorAll('.btn-pay');
    payButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            currentPaymentData = {
                conducteurId: this.dataset.conducteurId,
                utilisateurId: this.dataset.utilisateurId,
                paiementId: this.dataset.paiementId,
                montant: this.dataset.montant,
                conducteurNom: this.dataset.conducteurNom,
                depart: this.dataset.depart,
                destination: this.dataset.destination,
                date: this.dataset.date,
                heure: this.dataset.heure,
                places: this.dataset.places
            };
            
            document.getElementById('modalConducteur').textContent = currentPaymentData.conducteurNom;
            document.getElementById('modalDepart').textContent = currentPaymentData.depart;
            document.getElementById('modalDestination').textContent = currentPaymentData.destination;
            document.getElementById('modalDate').textContent = currentPaymentData.date;
            document.getElementById('modalHeure').textContent = currentPaymentData.heure;
            document.getElementById('modalPlaces').textContent = currentPaymentData.places;
            document.getElementById('modalMontant').textContent = `${currentPaymentData.montant} $`;
            
          
            createPayPalButton();
            
         
            const modal = document.getElementById('paymentConfirmationModal');
            if (modal) {
                const bootstrapModal = new bootstrap.Modal(modal, {
                    backdrop: true,
                    keyboard: true,
                    focus: true
                });
                bootstrapModal.show();
            }
        });
    });
    
    document.getElementById('btn-payer-solde').addEventListener('click', function() {
        payerAvecSolde();
    });
    
    function payerAvecSolde() {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/payer-panier/${currentPaymentData.conducteurId}/${currentPaymentData.utilisateurId}/${currentPaymentData.paiementId}`;
        form.style.display = 'none';
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = window.csrfToken;
        form.appendChild(csrfInput);
        
        const paymentTypeInput = document.createElement('input');
        paymentTypeInput.type = 'hidden';
        paymentTypeInput.name = 'payment_type';
        paymentTypeInput.value = 'solde';
        form.appendChild(paymentTypeInput);
        
        const amountInput = document.createElement('input');
        amountInput.type = 'hidden';
        amountInput.name = 'amount';
        amountInput.value = currentPaymentData.montant;
        form.appendChild(amountInput);
        
        const modal = bootstrap.Modal.getInstance(document.getElementById('paymentConfirmationModal'));
        if (modal) {
            modal.hide();
        }
        
        document.body.appendChild(form);
        form.submit();
    }
    
    function createPayPalButton() {
       
        const container = document.getElementById('modal-paypal-button-container');
        container.innerHTML = '';
        
        if (typeof paypal === 'undefined') {
            console.error('PayPal SDK non chargé');
            container.innerHTML = '<div class="alert alert-warning">Erreur de chargement PayPal</div>';
            return;
        }
        
       
        paypal.Buttons({
            fundingSource: paypal.FUNDING.PAYPAL,
            disableFunding: ['card', 'credit'],
            
            style: {
                layout: 'vertical',
                color: 'gold',
                shape: 'rect',
                label: 'paypal',
                height: 45
            },
            
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: { 
                            value: currentPaymentData.montant,
                            currency_code: 'CAD'
                        }
                    }]
                });
            },
            
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    console.log('Paiement PayPal approuvé:', details);
                    
                  
                    const modal = bootstrap.Modal.getInstance(document.getElementById('paymentConfirmationModal'));
                    if (modal) {
                        modal.hide();
                    }
                    
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/payer-panier/${currentPaymentData.conducteurId}/${currentPaymentData.utilisateurId}/${currentPaymentData.paiementId}`;
                    form.style.display = 'none';
                    
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = window.csrfToken;
                    form.appendChild(csrfInput);
                    
                    const paymentTypeInput = document.createElement('input');
                    paymentTypeInput.type = 'hidden';
                    paymentTypeInput.name = 'payment_type';
                    paymentTypeInput.value = 'paypal';
                    form.appendChild(paymentTypeInput);
                    
                    const paypalOrderInput = document.createElement('input');
                    paypalOrderInput.type = 'hidden';
                    paypalOrderInput.name = 'paypal_order_id';
                    paypalOrderInput.value = details.id;
                    form.appendChild(paypalOrderInput);
                    
                    const paypalStatusInput = document.createElement('input');
                    paypalStatusInput.type = 'hidden';
                    paypalStatusInput.name = 'paypal_status';
                    paypalStatusInput.value = details.status;
                    form.appendChild(paypalStatusInput);
                    
                    const amountInput = document.createElement('input');
                    amountInput.type = 'hidden';
                    amountInput.name = 'amount';
                    amountInput.value = currentPaymentData.montant;
                    form.appendChild(amountInput);
                    
                    document.body.appendChild(form);
                    form.submit();
                });
            },
            
            onError: function(err) {
                console.error('Erreur PayPal:', err);
                alert('Erreur PayPal: ' + err.message);
            }
        }).render('#modal-paypal-button-container').catch(function(err) {
            console.error('Erreur lors du rendu du bouton PayPal:', err);
            container.innerHTML = '<div class="alert alert-danger">Erreur de chargement du bouton PayPal</div>';
        });
    }
});
</script>

<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content delete-modal">
            <div class="modal-header delete-modal-header">
                <div class="delete-header-content">
                    <div class="delete-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="delete-title-section">
                        <h5 class="modal-title" id="deleteConfirmationModalLabel">
                            Confirmer la suppression
                        </h5>
                        <p class="delete-subtitle">Cette action est irréversible</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body delete-modal-body">
                <div class="confirmation-section">
                    <div class="confirmation-icon">
                        <i class="fas fa-trash-alt"></i>
                    </div>
                    <div class="confirmation-content">
                        <h6 class="confirmation-title">Supprimer ce trajet du panier ?</h6>
                        <p class="confirmation-text" id="deleteTrajetInfo">
                        </p>
                        <div class="warning-note">
                            <i class="fas fa-info-circle"></i>
                            <span>Les places seront remises disponibles dans le trajet</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer delete-modal-footer">
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                    Annuler
                </button>
                <button type="button" class="btn btn-delete" id="confirmDeleteBtn">
                    <i class="fas fa-trash"></i>
                    Supprimer définitivement
                </button>
            </div>
        </div>
    </div>
</div>