<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profil Samuel Leblanc</title>
    <link rel="stylesheet" href="profil.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @vite(['resources/css/app.css', 'resources/css/profil.css', 'resources/js/app.js'])
  </head>
  <body>
    <div class="dashboard">
      <header class="header">
        <div class="header-left">
          <h1>Bonjour Samuel !</h1>
          <p>Voici un aperçu de votre activité d'aujourd'hui</p>
        </div>
        <div class="header-right">
          <div class="profile">
            <div class="profile-info">
              <span class="profile-name">Samuel Leblanc</span>
              <span class="profile-role">Chauffeur</span>
              <span class="profile-status">En ligne</span>
            </div>
            <div class="profile-avatar">
              <img
                src="https://e7.pngegg.com/pngimages/84/165/png-clipart-united-states-avatar-organization-information-user-avatar-service-computer-wallpaper-thumbnail.png"
              />
            </div>
          </div>
        </div>
      </header>
      <main class="main-content">
        <section class="quick-actions">
          <h2>Actions rapides</h2>
          <div class="actions-grid">
            <div class="action-card">
              <div class="action-icon">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  width="24"
                  height="24"
                >
                  <circle cx="11" cy="11" r="8"></circle>
                  <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
              </div>
              <div class="action-content">
                <h3>Rechercher</h3>
                <p>Rechercher dans votre base</p>
              </div>
            </div>
            <div class="action-card">
              <div class="action-icon">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  width="24"
                  height="24"
                >
                  <line x1="12" y1="19" x2="12" y2="5"></line>
                  <polyline points="5 12 12 5 19 12"></polyline>
                </svg>
              </div>
              <div class="action-content">
                <h3>Publier</h3>
                <p>Publier un nouveau contenu</p>
              </div>
            </div>
            <div class="action-card">
              <div class="action-icon">
                <svg viewBox="0 0 24 24" fill="currentColor">
                  <path
                    d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"
                  />
                </svg>
              </div>
              <div class="action-content">
                <h3>Mes réservations</h3>
                <p>Voir mes réservations</p>
              </div>
            </div>
            <div class="action-card">
              <div class="action-icon">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  width="24"
                  height="24"
                >
                  <circle cx="9" cy="21" r="1"></circle>
                  <circle cx="20" cy="21" r="1"></circle>
                  <path
                    d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"
                  ></path>
                </svg>
              </div>
              <div class="action-content">
                <h3>Mon panier</h3>
                <p>Gérer mon panier</p>
              </div>
            </div>
          </div>
        </section>
        <div class="content-grid">
          <div class="left-column">
            <section class="stats-section">
              <!-- Section spéciale aux chauffeurs -->
              <h2>Vos statistiques</h2>
              <div class="stats-grid">
                <div class="stat-card">
                  <div class="stat-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                      <path
                        d="M16 6l2.29 2.29-4.88 4.88-4-4L2 16.59 3.41 18l6-6 4 4 6.3-6.29L22 12V6z"
                      />
                    </svg>
                  </div>
                  <div class="stat-content">
                    <span class="stat-number">540</span>
                    <span class="stat-label">Vues</span>
                  </div>
                </div>
                <div class="stat-card large">
                  <span class="stat-number large">4.9 / 5</span>
                  <span class="stat-label">Note moyenne</span>
                </div>
                <div class="stat-card large">
                  <span class="stat-number large blue">1400 $</span>
                  <span class="stat-label">Revenus mensuels</span>
                </div>
              </div>
            </section>
            <section class="activity-section">
              <div class="section-header">
                <h2>Activité récente</h2>
              </div>
              <div class="activity-list">
                <div class="activity-item">
                  <div class="activity-indicator green"></div>
                  <div class="activity-content">
                    <h4>Nouvelle commande</h4>
                    <p>Une de vos annonces vient d'être réservée</p>
                  </div>
                  <div class="activity-time">
                    <span>Il y a 5 min</span>
                    <button class="btn-link">Consulter</button>
                  </div>
                </div>
                <div class="activity-item">
                  <div class="activity-indicator orange"></div>
                  <div class="activity-content">
                    <h4>Nouveau avis reçu</h4>
                    <p>Avis 5 étoiles laissé par Jean Dupuis</p>
                    <div class="rating"><span>★★★★★</span></div>
                  </div>
                  <div class="activity-time">
                    <span>Il y a 1h</span>
                    <button class="btn-link">Voir</button>
                  </div>
                </div>
                <div class="activity-item">
                  <div class="activity-indicator blue"></div>
                  <div class="activity-content">
                    <h4>Paiement reçu</h4>
                    <p>Paiement pour la réservation</p>
                  </div>
                  <div class="activity-time">
                    <span>Il y a 2 jours</span>
                    <button class="btn-link">Détails</button>
                  </div>
                </div>
                <div class="activity-item">
                  <div class="activity-indicator blue"></div>
                  <div class="activity-content">
                    <h4>Nouveau message</h4>
                    <p>Avis 5 étoiles laissé par un message</p>
                  </div>
                  <div class="activity-time">
                    <span>Il y a 3 jours</span>
                    <button class="btn-primary">Répondre</button>
                  </div>
                </div>
              </div>
            </section>
          </div>
          <div class="right-column">
            <section class="bookings-section">
              <h2>Prochaines réservations</h2>
              <div class="booking-item">
                <div class="booking-info">
                  <span class="booking-location">Montreal</span>
                  <span class="booking-date">Lundi</span>
                </div>
                <div class="booking-guests">
                  <div class="guest-avatars">
                    <img
                      src="https://e7.pngegg.com/pngimages/84/165/png-clipart-united-states-avatar-organization-information-user-avatar-service-computer-wallpaper-thumbnail.png"
                    />
                    <img
                      src="https://e7.pngegg.com/pngimages/84/165/png-clipart-united-states-avatar-organization-information-user-avatar-service-computer-wallpaper-thumbnail.png"
                    />
                    <img
                      src="https://e7.pngegg.com/pngimages/84/165/png-clipart-united-states-avatar-organization-information-user-avatar-service-computer-wallpaper-thumbnail.png"
                    />
                  </div>
                  <span class="guest-count">3/4 places</span>
                </div>
                <span class="booking-price">100 $</span>
              </div>
              <div class="booking-item">
                <div class="booking-info">
                  <span class="booking-location">Mirabel</span>
                  <span class="booking-date">Mercredi</span>
                </div>
                <div class="booking-guests">
                  <div class="guest-avatars">
                    <img
                      src="https://e7.pngegg.com/pngimages/84/165/png-clipart-united-states-avatar-organization-information-user-avatar-service-computer-wallpaper-thumbnail.png"
                    />
                  </div>
                  <span class="guest-count">1/2 places</span>
                </div>
                <span class="booking-price">45 $</span>
              </div>
              <div class="booking-item">
                <div class="booking-info">
                  <span class="booking-location">Sorel</span>
                  <span class="booking-date">Jeudi</span>
                </div>
                <div class="booking-guests">
                  <div class="guest-avatars">
                    <img
                      src="https://e7.pngegg.com/pngimages/84/165/png-clipart-united-states-avatar-organization-information-user-avatar-service-computer-wallpaper-thumbnail.png"
                    />
                  </div>
                  <span class="guest-count">1/4 places</span>
                </div>
                <span class="booking-price">200 $</span>
              </div>
            </section>
            <section class="messages-section">
              <div class="section-header">
                <h2>Messages récents</h2>
              </div>

              <div class="message-list">
                <div class="message-item">
                  <div class="message-avatar">
                    <img
                      src="https://e7.pngegg.com/pngimages/84/165/png-clipart-united-states-avatar-organization-information-user-avatar-service-computer-wallpaper-thumbnail.png"
                    />
                  </div>
                  <div class="message-content">
                    <h4>Jean Dubois</h4>
                    <p>
                      dfdsjfnsdjnfdnksddfsdfdsfdsfsdfsdfdsfdsfsdf
                      fdsfdsfsdfdsfsdfdsfdsfdsfsdfsdfdsfdsfsdfsdf
                    </p>
                  </div>
                  <span class="message-time">Il y a 2 min</span>
                </div>
                <div class="message-item">
                  <div class="message-avatar">
                    <img
                      src="https://e7.pngegg.com/pngimages/84/165/png-clipart-united-states-avatar-organization-information-user-avatar-service-computer-wallpaper-thumbnail.png"
                    />
                  </div>
                  <div class="message-content">
                    <h4>Mathieu Tremblay</h4>
                    <p>
                      dfdsjfnsdjnfdnksddfsdfdsfdsfsdfsdfdsfdsfsdf
                      fdsfdsfsdfdsfsdfdsfdsfdsfsdfsdfdsfdsfsdfsdf
                    </p>
                  </div>
                  <span class="message-time">Il y a 1 jour</span>
                </div>
              </div>
            </section>
          </div>
        </div>
      </main>
    </div>
  </body>
</html>
