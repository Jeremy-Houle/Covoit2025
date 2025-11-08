@extends('layouts.app')

@section('title', 'Historique des transactions - Covoit2025')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/panier.css') }}?v={{ time() }}">
<style>
    .historique-page {
        padding: 100px 20px 40px;
        min-height: 100vh;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    }

    .historique-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .historique-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .historique-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--primary-blue, #2563eb);
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
    }

    .historique-subtitle {
        font-size: 1.1rem;
        color: #666;
        margin-top: 10px;
    }

    .empty-history {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .empty-history-icon {
        font-size: 4rem;
        color: #ccc;
        margin-bottom: 20px;
    }

    .empty-history h3 {
        color: #333;
        margin-bottom: 10px;
    }

    .empty-history p {
        color: #666;
        margin-bottom: 30px;
    }

    .transaction-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .transaction-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .transaction-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }

    .transaction-status {
        display: flex;
        align-items: center;
    }

    .badge-paid {
        background: #10b981;
        color: white;
        padding: 8px 20px;
        border-radius: 20px;
        font-size: 0.95rem;
        font-weight: 700;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        text-transform: none;
        border: none;
        cursor: default;
        display: inline-block;
    }

    .badge-pending {
        background: #f59e0b;
        color: white;
        padding: 8px 20px;
        border-radius: 8px;
        font-size: 0.95rem;
        font-weight: 700;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        text-transform: none;
        border: none;
        cursor: default;
        display: inline-block;
    }

    .badge-cancelled {
        background: #ef4444;
        color: white;
        padding: 8px 20px;
        border-radius: 8px;
        font-size: 0.95rem;
        font-weight: 700;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        text-transform: none;
        border: none;
        cursor: default;
        display: inline-block;
    }

    .transaction-route {
        margin: 20px 0;
    }

    .route-points {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .depart, .destination {
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1;
    }

    .depart-icon {
        color: #ef4444;
        font-size: 1.2rem;
    }

    .destination-icon {
        color: #3b82f6;
        font-size: 1.2rem;
    }

    .route-arrow {
        flex-shrink: 0;
    }

    .transaction-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin: 20px 0;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #555;
    }

    .detail-item i {
        color: var(--primary-blue, #2563eb);
    }

    .transaction-pricing {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin: 20px 0;
    }

    .price-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .price-item.total {
        font-weight: 700;
        font-size: 1.1rem;
        padding-top: 10px;
        border-top: 2px solid #dee2e6;
        margin-top: 10px;
    }

    .price-value {
        color: var(--primary-blue, #2563eb);
        font-weight: 600;
    }

    .transaction-date {
        color: #666;
        font-size: 0.9rem;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #e9ecef;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: var(--primary-blue, #2563eb);
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        transition: background 0.2s;
        margin-top: 20px;
    }

    .btn-back:hover {
        background: #1d4ed8;
        color: white;
    }
</style>
@endpush

@section('content')
<div class="historique-page">
    <div class="historique-container">
        <div class="historique-header">
            <h1 class="historique-title">
                <i class="fas fa-history"></i>
                Historique de mes transactions
            </h1>
            <p class="historique-subtitle">Consultez toutes vos transactions de covoiturage</p>
        </div>

        @if($transactions->isEmpty())
            <div class="empty-history">
                <div class="empty-history-icon">
                    <i class="fas fa-receipt"></i>
                </div>
                <h3>Aucune transaction</h3>
                <p>Vous n'avez effectué aucune transaction pour le moment.</p>
                <a href="/rechercher" class="btn-back">
                    <i class="fas fa-search"></i>
                    Rechercher des trajets
                </a>
            </div>
        @else
            @foreach($transactions as $transaction)
                <div class="transaction-card">
                    <div class="transaction-header">
                        <div class="conducteur-info">
                            <i class="fas fa-user-circle"></i>
                            <span class="conducteur-nom">{{ $transaction->NomConducteur ?? $transaction->ConducteurNom ?? '' }} {{ $transaction->PrenomConducteur ?? $transaction->ConducteurPrenom ?? '' }}</span>
                        </div>
                        <div class="transaction-status">
                            @if($transaction->Statut === 'Payé' || $transaction->Statut === 'Complété')
                                <span class="badge-paid">Payé</span>
                            @elseif($transaction->Statut === 'En attente')
                                <span class="badge-pending">En attente</span>
                            @else
                                <span class="badge-cancelled">{{ $transaction->Statut }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="transaction-route">
                        <div class="route-points">
                            <div class="depart">
                                <i class="fas fa-map-marker-alt depart-icon"></i>
                                <span class="point-label">{{ $transaction->Depart }}</span>
                            </div>
                            <div class="route-arrow">
                                <i class="fas fa-arrow-right" style="color: #999;"></i>
                            </div>
                            <div class="destination">
                                <i class="fas fa-map-marker-alt destination-icon"></i>
                                <span class="point-label">{{ $transaction->Destination }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="transaction-details">
                        <div class="detail-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span>{{ \Carbon\Carbon::parse($transaction->DateTrajet)->format('d/m/Y') }}</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-clock"></i>
                            <span>{{ \Carbon\Carbon::parse($transaction->HeureTrajet)->format('H:i') }}</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-users"></i>
                            <span>{{ $transaction->NombrePlaces }} place(s)</span>
                        </div>
                        @if(isset($transaction->Distance))
                            <div class="detail-item">
                                <i class="fas fa-road"></i>
                                <span>{{ $transaction->Distance }} km</span>
                            </div>
                        @endif
                    </div>

                    <div class="transaction-pricing">
                        <div class="price-item">
                            <span>Prix par personne :</span>
                            <span class="price-value">{{ $transaction->NombrePlaces > 0 ? number_format(($transaction->PrixUnitaire ?? ($transaction->Montant / $transaction->NombrePlaces)), 2) : '0.00' }} $</span>
                        </div>
                        <div class="price-item">
                            <span>Nombre de places :</span>
                            <span>{{ $transaction->NombrePlaces }}</span>
                        </div>
                        <div class="price-item total">
                            <span>Total :</span>
                            <span class="price-value">{{ number_format($transaction->Montant, 2) }} $</span>
                        </div>
                    </div>

                    <div class="transaction-date">
                        <i class="fas fa-calendar"></i>
                        <strong>Date de la transaction :</strong> 
                        {{ \Carbon\Carbon::parse($transaction->DateTransaction ?? $transaction->DateCreation ?? now())->format('d/m/Y à H:i') }}
                        @if(isset($transaction->MethodePaiement))
                            | <strong>Méthode :</strong> {{ $transaction->MethodePaiement }}
                        @endif
                    </div>
                </div>
            @endforeach

            <div style="text-align: center; margin-top: 30px;">
                <a href="/profil" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                    Retour au profil
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

