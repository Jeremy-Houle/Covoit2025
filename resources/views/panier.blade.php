@extends('layouts.app')

@section('title', 'Mon Panier - Covoit2025')

@push('head')
<meta http-equiv="Permissions-Policy" content="unload=*">
@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('css/panier.css') }}?v={{ time() }}">
@endpush

@push('scripts')
<script src="{{ asset('js/panier.js') }}?v={{ time() }}"></script>
<script>
    window.csrfToken = '{{ csrf_token() }}';
</script>
@endpush
<script src="https://www.paypal.com/sdk/js?client-id=AWQqeyvmMlkT1LYUxQ-WRRLHao1rtwanQXVP9LTSYtoyCmJ1JKcKimTRLI5oVZ3kEBbRXQ2n0JqSXt9v&currency=CAD&enable-funding=paypal&disable-funding=card,credit&components=buttons"></script>
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
                                    </div>
                                    <div class="price-item">
                                        <span>Nombre de places :</span>
                                        <div class="places-control">
                                            <button type="button" class="btn-places btn-places-minus" 
                                                    data-paiement-id="{{ $paiement->IdPaiement }}"
                                                    {{ $paiement->NombrePlaces <= 1 ? 'disabled' : '' }}>
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <span class="places-count" data-paiement-id="{{ $paiement->IdPaiement }}">{{ $paiement->NombrePlaces }}</span>
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
                <div id="modal-paypal-button-container"></div>
            </div>
        </div>
    </div>
</div>

@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Variables pour stocker les données du trajet sélectionné
    let currentPaymentData = {};
    
    // Gestion des boutons "Payer ce trajet"
    const payButtons = document.querySelectorAll('.btn-pay');
    payButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Récupérer les données du trajet
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
            
            // Remplir le modal avec les données
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
    
    function createPayPalButton() {
       
        const container = document.getElementById('modal-paypal-button-container');
        container.innerHTML = '';
        
        // Vérifier que PayPal SDK est chargé
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
                    
                    // Créer un formulaire pour soumettre le paiement
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/payer-panier/${currentPaymentData.conducteurId}/${currentPaymentData.utilisateurId}/${currentPaymentData.paiementId}`;
                    form.style.display = 'none';
                    
                    // Ajouter le token CSRF
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = window.csrfToken;
                    form.appendChild(csrfInput);
                    
                    // Ajouter les données PayPal
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
                    
                    // Ajouter le formulaire au DOM et le soumettre
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