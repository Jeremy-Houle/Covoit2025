@extends('layouts.app')

@section('title', 'Historique des transactions - Covoit2025')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/panier.css') }}?v={{ time() }}">
<link rel="stylesheet" href="{{ asset('css/historique-transactions.css') }}?v={{ time() }}">
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

