@extends('layouts.app')

@section('title', 'Profil ' . $user->Prenom . ' ' . $user->Nom)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profil.css') }}?v={{ time() }}">
@endpush

@section('content')
<div class="profil-page">
  <div class="profil-container">
    <div class="profil-header">
      <div class="profile-info">
        <div class="profile-avatar">
          <img src="https://ui-avatars.com/api/?name={{ $user->Prenom }}+{{ $user->Nom }}&background=2563eb&color=fff&size=80" alt="{{ $user->Prenom }} {{ $user->Nom }}">
        </div>
        <div class="profile-details">
          <h1 class="profil-title">{{ $user->Prenom }} {{ $user->Nom }}</h1>
          <div class="profile-meta">
            <span class="profile-role">{{ $user->Role }}</span>
            <span class="profile-status">En ligne</span>
          </div>
        </div>
      </div>
      <div class="profil-actions">
        <a href="/edit-profil" class="btn-edit-profil">
          <i class="fas fa-edit"></i> Modifier
        </a>
      </div>
    </div>
    
    <div class="dashboard">
      <main class="main-content">
        <section class="quick-actions">
          <h2>Actions rapides</h2>
          <div class="actions-grid">
            <a href="/rechercher" class="action-card">
              <div class="action-icon">
                <i class="fas fa-search"></i>
              </div>
              <div class="action-content">
                <h3>Rechercher</h3>
                <p>Recherche de trajet</p>
              </div>
            </a>

            <a href="/publier" class="action-card">
              <div class="action-icon">
                <i class="fas fa-plus"></i>
              </div>
              <div class="action-content">
                <h3>Publier</h3>
                <p>Publier un trajet</p>
              </div>
            </a>

            <a href="/mes-reservations" class="action-card">
              <div class="action-icon">
                <i class="fas fa-list"></i>
              </div>
              <div class="action-content">
                <h3>Mes réservations</h3>
                <p>Voir mes réservations</p>
              </div>
            </a>

            @if($user->Role === 'Passager')
              <a href="/cart" class="action-card">
                <div class="action-icon">
                  <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="action-content">
                  <h3>Mon panier</h3>
                  <p>Gérer mon panier</p>
                </div>
              </a>
            @endif
          </div>
        </section>

        <section class="stats-section">
          <h2>Vos statistiques</h2>
          <div class="stats-grid">
            @if($user->Role === 'Conducteur')
              <div class="stat-card large">
                <div class="stat-icon">
                  <i class="fas fa-star"></i>
                </div>
                <div>
                  <div class="stat-number large">{{ number_format($moyenneNote, 1) }} / 5</div>
                  <div class="stat-label">Note moyenne</div>
                </div>
              </div>
            @endif
            <div class="stat-card large">
              <div class="stat-icon">
                <i class="fas fa-wallet"></i>
              </div>
              <div>
                <div class="stat-number large">{{ number_format($user->Solde, 2) }} $</div>
                <div class="stat-label">Solde disponible</div>
              </div>
            </div>
          </div>
        </section>

        <section class="activity-section">
          <h2>Activité récente</h2>
          <div class="activity-list">
            @forelse($activites as $act)
              <div class="activity-item">
                <div class="activity-indicator {{ $act['couleur'] }}"></div>
                <div class="activity-content">
                  <h4>{{ $act['titre'] }}</h4>
                  <p>{{ $act['description'] }}</p>
                </div>
                <div class="activity-time">
                  <span>Il y a 2h</span>
                </div>
              </div>
            @empty
              <div class="activity-item">
                <div class="activity-content">
                  <h4>Aucune activité récente</h4>
                  <p>Vos activités récentes apparaîtront ici</p>
                </div>
              </div>
            @endforelse
          </div>
        </section>
      </main>

      <aside class="sidebar">
        <section class="bookings-section">
          <h2>Prochaines réservations</h2>
          @forelse($prochainesReservations as $resa)
            <div class="booking-item">
              <div class="booking-info">
                <span class="booking-location">{{ $resa->Depart ?? 'Départ' }} → {{ $resa->Destination ?? 'Destination' }}</span>
                <span class="booking-date">{{ \Carbon\Carbon::parse($resa->DateTrajet)->translatedFormat('l d M') }}</span>
              </div>
              <div class="booking-guests">
                <div class="guest-avatars">
                  @foreach($resa->passagers as $p)
                    <img src="https://ui-avatars.com/api/?name={{ $p->Prenom }}+{{ $p->Nom }}&background=2563eb&color=fff&size=28" alt="{{ $p->Prenom }}" />
                  @endforeach
                </div>
                <span class="guest-count">{{ $resa->PlacesReservees ?? 0 }}/{{ $resa->PlacesDisponibles ?? 4 }} places</span>
              </div>
              <span class="booking-price">{{ $resa->Prix ?? '0' }} $</span>
            </div>
          @empty
            <div class="booking-item">
              <div class="booking-info">
                <span class="booking-location">Aucune réservation</span>
                <span class="booking-date">Vos réservations apparaîtront ici</span>
              </div>
            </div>
          @endforelse
        </section>

        <section class="messages-section">
          <h2>Messages récents</h2>
          <div class="message-list">
            @forelse($messagesRecents as $msg)
              <div class="message-item">
                <div class="message-avatar">
                  <img src="https://ui-avatars.com/api/?name={{ $msg->Prenom }}+{{ $msg->Nom }}&background=2563eb&color=fff&size=40" alt="{{ $msg->Prenom }}">
                </div>
                <div class="message-content">
                  <h4>{{ $msg->Prenom }} {{ $msg->Nom }}</h4>
                  <p>{{ Str::limit($msg->LeMessage, 60) }}</p>
                </div>
                <div class="message-time">
                  <span>{{ \Carbon\Carbon::parse($msg->DateEnvoi)->diffForHumans() }}</span>
                </div>
              </div>
            @empty
              <div class="message-item">
                <div class="message-content">
                  <h4>Aucun message récent</h4>
                  <p>Vos messages récents apparaîtront ici</p>
                </div>
              </div>
            @endforelse
          </div>
        </section>
      </aside>
    </div>
  </div>
</div>
@endsection