<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Profil {{ $user->Prenom }} {{ $user->Nom }}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  @vite(['resources/css/app.css', 'resources/css/profil.css', 'resources/js/app.js'])
</head>

<body>
  <div class="dashboard">
    <header class="header">
      <div class="header-left">
        <h1>Bonjour {{ $user->Prenom }} {{ $user->Nom }} !</h1>
        <p>Voici un aperçu de votre activité d'aujourd'hui</p>
      </div>
      <div class="header-right" onclick="window.location.href='/edit-profil'">
        <div class="profile-avatar">
          <img src="https://dummyimage.com/40x40/cffafe/000000&text={{ Str::upper(substr($user->Nom, 0, 2)) }}">

        </div>
      </div>
    </header>

    <main class="main-content">
      <!-- Actions rapides -->
      <section class="quick-actions">
        <h2>Actions rapides</h2>
        <div class="actions-grid">

          <div class="action-card">
            <div class="action-icon">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="24" height="24">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
              </svg>
            </div>
            <div class="action-content">
              <h3>Rechercher</h3>
              <p>Recherche de trajet</p>
            </div>
          </div>

          <div class="action-card">
            <div class="action-icon">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="24" height="24">
                <line x1="12" y1="19" x2="12" y2="5"></line>
                <polyline points="5 12 12 5 19 12"></polyline>
              </svg>
            </div>
            <div class="action-content">
              <h3>Publier</h3>
              <p>Publier un trajet</p>
            </div>
          </div>

          <div class="action-card">
            <div class="action-icon">
              <svg viewBox="0 0 24 24" fill="currentColor">
                <path
                  d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z" />
              </svg>
            </div>
            <div class="action-content">
              <h3>Mes réservations</h3>
              <p>Voir mes réservations</p>
            </div>
          </div>

          @if($user->Role === 'Passager')
            <div class="action-card" onclick="window.location.href='/cart'">
              <div class="action-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                  stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="24" height="24">
                  <circle cx="9" cy="21" r="1"></circle>
                  <circle cx="20" cy="21" r="1"></circle>
                  <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
              </div>
              <div class="action-content">
                <h3>Mon panier</h3>
                <p>Gérer mon panier</p>
              </div>
            </div>
          @endif
        </div>
      </section>

      <div class="content-grid">
        <!-- Colonne gauche -->
        <div class="left-column">
          <!-- Statistiques -->
          <section class="stats-section">
            <h2>Vos statistiques</h2>
            <div class="stats-grid">
              @if($user->Role === 'Conducteur')
                <div class="stat-card large">
                  <span class="stat-number large">{{ number_format($moyenneNote, 1) }} / 5</span>
                  <span class="stat-label">Note moyenne</span>
                </div>
              @endif
              <div class="stat-card large">
                <span class="stat-number large blue">{{ number_format($user->Solde, 2) }} $</span>
                <span class="stat-label">Solde</span>
              </div>
            </div>
          </section>

          <!-- Activité récente -->
          <section class="activity-section">
            <div class="section-header">
              <h2>Activité récente</h2>
            </div>
            <div class="activity-list">
              @forelse($activites as $act)
                <div class="activity-item">
                  <div class="activity-indicator {{ $act['couleur'] }}"></div>
                  <div class="activity-content">
                    <h4>{{ $act['titre'] }}</h4>
                    <p>{{ $act['description'] }}</p>
                  </div>
                </div>
              @empty
                <p>Aucune activité récente</p>
              @endforelse
            </div>
          </section>
        </div>

        <!-- Colonne droite -->
        <div class="right-column">
          <!-- Prochaines réservations -->
          <section class="bookings-section">
            <h2>Prochaines réservations</h2>
            @forelse($prochainesReservations as $resa)
              <div class="booking-item">
                <div class="booking-info">
                  <span class="booking-location">{{ $resa->Depart }}</span>
                  <span
                    class="booking-date">{{ \Carbon\Carbon::parse($resa->DateTrajet)->translatedFormat('l d M') }}</span>
                </div>
                <div class="booking-guests">
                  <div class="guest-avatars">
                    @foreach($resa->passagers as $p)
                      <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" alt="Passager" />
                    @endforeach
                  </div>
                  <span class="guest-count">/{{ $resa->PlacesDisponibles }} places</span>
                </div>
                <span class="booking-price">{{ $resa->Prix }} $</span>
              </div>
            @empty
              <p>Aucune réservation à venir</p>
            @endforelse
          </section>

          <!-- Messages récents -->
          <section class="messages-section">
            <div class="section-header">
              <h2>Messages récents</h2>
            </div>
            <div class="message-list">
              @forelse($messagesRecents as $msg)
                <div class="message-item">
                  <div class="message-avatar">
                    <img src="https://dummyimage.com/40x40/ffffff/000000&text={{ Str::upper(substr($msg->Nom, 0, 2)) }}">
                  </div>
                  <div class="message-content">
                    <h4>{{ $msg->Prenom }} {{ $msg->Nom }}</h4>
                    <p>{{ Str::limit($msg->LeMessage, 120) }}</p>
                  </div>
                </div>
              @empty
                <p>Aucun message récent</p>
              @endforelse
            </div>
          </section>
        </div>
      </div>
    </main>
  </div>
</body>

</html>