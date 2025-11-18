<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Covoit2025 - Présentation du Projet</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #0a0f1e;
            color: #fff;
            overflow-x: hidden;
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            background: linear-gradient(135deg, #0a0f1e 0%, #1e3a8a 50%, #0a0f1e 100%);
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 2px, transparent 2px);
            background-size: 50px 50px;
            animation: moveGrid 20s linear infinite;
        }

        @keyframes moveGrid {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            padding: 2rem;
            max-width: 1200px;
        }

        .logo-title {
            font-size: 6rem;
            font-weight: 900;
            background: linear-gradient(135deg, #3b82f6, #fbbf24, #3b82f6);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientShift 3s ease infinite;
            margin-bottom: 1rem;
            text-shadow: 0 0 80px rgba(59, 130, 246, 0.5);
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .tagline {
            font-size: 2rem;
            font-weight: 300;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .description {
            font-size: 1.25rem;
            max-width: 800px;
            margin: 0 auto 3rem;
            line-height: 1.8;
            opacity: 0.8;
        }

        .btn-discover {
            display: inline-flex;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem 3rem;
            font-size: 1.25rem;
            font-weight: 700;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 40px rgba(59, 130, 246, 0.4);
            position: relative;
            overflow: hidden;
        }

        .btn-discover::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }

        .btn-discover:hover::before {
            left: 100%;
        }

        .btn-discover:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 15px 50px rgba(59, 130, 246, 0.6);
        }

        /* Section Styles */
        .section {
            padding: 6rem 2rem;
            position: relative;
        }

        .section-title {
            font-size: 3rem;
            font-weight: 800;
            text-align: center;
            margin-bottom: 3rem;
            background: linear-gradient(135deg, #3b82f6, #fbbf24);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Video Section */
        .video-section {
            background: #111827;
        }

        .video-container {
            max-width: 1200px;
            margin: 0 auto;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
            aspect-ratio: 16/9;
            background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .video-placeholder {
            text-align: center;
        }

        .video-placeholder i {
            font-size: 5rem;
            color: #3b82f6;
            margin-bottom: 1rem;
        }

        .video-placeholder p {
            font-size: 1.5rem;
            opacity: 0.7;
        }

        /* About Section */
        .about-section {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        }

        .about-content {
            max-width: 1000px;
            margin: 0 auto;
            font-size: 1.25rem;
            line-height: 2;
            text-align: center;
            opacity: 0.9;
        }

        /* Features Section */
        .features-section {
            background: #0a0f1e;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 1.5rem;
            max-width: 1600px;
            margin: 0 auto;
        }

        .feature-card {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            padding: 2.5rem;
            border-radius: 20px;
            text-align: center;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            border-color: #fbbf24;
            box-shadow: 0 20px 40px rgba(251, 191, 36, 0.3);
        }

        .feature-icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            color: #fbbf24;
        }

        .feature-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .feature-description {
            font-size: 1.1rem;
            opacity: 0.8;
            line-height: 1.6;
        }

        /* Screenshots Section */
        .screenshots-section {
            background: #111827;
        }

        .screenshots-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .screenshot-card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s ease;
            background: #1f2937;
            padding: 1rem;
        }

        .screenshot-card:hover {
            transform: scale(1.05);
        }

        .screenshot-img {
            width: 100%;
            height: 250px;
            background: linear-gradient(135deg, #374151 0%, #1f2937 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            font-size: 1rem;
            overflow: hidden;
        }

        .screenshot-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
            cursor: pointer;
            transition: opacity 0.3s ease;
        }

        .screenshot-img img:hover {
            opacity: 0.8;
        }

        /* Modal styles */
        .image-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.95);
            animation: fadeIn 0.3s ease;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .image-modal.active {
            display: block;
        }

        .modal-content {
            position: relative;
            max-width: 1200px;
            width: 90%;
            margin: 50px auto;
            animation: zoomIn 0.3s ease;
        }

        .modal-content img {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 10px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.8);
        }

        .modal-close {
            position: fixed;
            top: 20px;
            right: 30px;
            color: white;
            font-size: 50px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s ease;
            background: rgba(0, 0, 0, 0.5);
            border: none;
            padding: 5px 15px;
            border-radius: 50%;
            line-height: 1;
            z-index: 10000;
        }

        .modal-close:hover {
            color: #3b82f6;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes zoomIn {
            from { transform: scale(0.8); }
            to { transform: scale(1); }
        }

        .screenshot-title {
            margin-top: 1rem;
            font-size: 1.25rem;
            font-weight: 600;
            text-align: center;
        }

        /* Team Section */
        .team-section {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .team-member {
            text-align: center;
            padding: 2rem;
            background: rgba(30, 58, 138, 0.3);
            border-radius: 20px;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .team-member:hover {
            background: rgba(30, 58, 138, 0.5);
            border-color: #fbbf24;
            transform: translateY(-5px);
        }

        .team-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6, #fbbf24);
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            font-weight: 900;
            color: white;
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.4);
        }

        .team-name {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .team-role {
            color: #fbbf24;
            font-weight: 600;
        }

        /* Footer */
        .footer {
            background: #0a0f1e;
            padding: 3rem 2rem;
            text-align: center;
            border-top: 2px solid #1e3a8a;
        }

        .footer-content {
            max-width: 800px;
            margin: 0 auto;
        }

        .footer-logo {
            font-size: 2.5rem;
            font-weight: 900;
            background: linear-gradient(135deg, #3b82f6, #fbbf24);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
        }

        .footer-text {
            opacity: 0.7;
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }

        .btn-enter-site {
            display: inline-flex;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem 3rem;
            font-size: 1.25rem;
            font-weight: 700;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.4);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-enter-site::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }

        .btn-enter-site:hover::before {
            left: 100%;
        }

        .btn-enter-site:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 15px 50px rgba(59, 130, 246, 0.6);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.8s ease;
        }

        /* Responsive */
        @media (max-width: 1400px) {
            .features-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 1024px) {
            .features-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .logo-title {
                font-size: 3.5rem;
            }

            .tagline {
                font-size: 1.5rem;
            }

            .description {
                font-size: 1rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .features-grid,
            .screenshots-grid,
            .team-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content fade-in-up">
            <h1 class="logo-title">Covoit2025</h1>
            <p class="tagline">La Plateforme de Covoiturage Moderne</p>
            <p class="description">
                Découvrez une nouvelle façon de voyager ensemble ! Covoit2025 révolutionne 
                le covoiturage avec une interface moderne, sécurisée et intuitive.
            </p>
            <a href="/accueil" class="btn-discover">
                Entrer sur le site
            </a>
        </div>
    </section>

    <!-- Video Section -->
    <section class="section video-section" id="video">
        <h2 class="section-title">Vidéo de Présentation</h2>
        <div class="video-container">
            <div class="video-placeholder">
                <i class="fas fa-video"></i>
                <p>Vidéo de démonstration à venir</p>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="section about-section" id="about">
        <h2 class="section-title">À Propos du Projet</h2>
        <div class="about-content">
            <p>
                <strong>Covoit2025</strong> est une plateforme web moderne de covoiturage développée pour 
                connecter conducteurs et passagers de manière simple, rapide et sécurisée. 
                Notre mission est de rendre le transport partagé accessible à tous tout en 
                contribuant à la réduction de l'empreinte carbone.
            </p>
            <br>
            <p>
                Développé avec <span style="color: #fbbf24;">Laravel, PHP, et MySQL</span>, 
                notre application offre une expérience utilisateur fluide et des fonctionnalités 
                complètes pour faciliter chaque aspect du covoiturage.
            </p>
        </div>
    </section>

    <!-- Features Section -->
    <section class="section features-section" id="features">
        <h2 class="section-title">Fonctionnalités Principales</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-search-location"></i>
                </div>
                <h3 class="feature-title">Recherche Avancée</h3>
                <p class="feature-description">
                    Trouvez rapidement des trajets avec filtres par date, destination, et préférences personnalisées.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <h3 class="feature-title">Système de Notification</h3>
                <p class="feature-description">
                    Recevez des notifications automatiques avant vos trajets pour ne jamais oublier un départ.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <h3 class="feature-title">Messagerie Intégrée</h3>
                <p class="feature-description">
                    Communiquez facilement avec les conducteurs et passagers directement dans l'application.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-star"></i>
                </div>
                <h3 class="feature-title">Système d'Évaluation</h3>
                <p class="feature-description">
                    Consultez les avis et notations pour voyager en toute confiance avec des utilisateurs vérifiés.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <h3 class="feature-title">Trajets Favoris</h3>
                <p class="feature-description">
                    Sauvegardez vos trajets préférés pour y accéder rapidement lors de vos prochaines recherches.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <h3 class="feature-title">Google Maps Intégré</h3>
                <p class="feature-description">
                    Visualisez les itinéraires en temps réel avec calcul automatique de distance et temps.
                </p>
            </div>
        </div>
    </section>

    <!-- Screenshots Section -->
    <section class="section screenshots-section" id="screenshots">
        <h2 class="section-title">Captures d'Écran</h2>
        <div class="screenshots-grid">
            <div class="screenshot-card">
                <div class="screenshot-img">
                    <img src="{{ asset('images/Acceuil.png') }}" alt="Page d'Accueil">
                </div>
                <p class="screenshot-title">Page d'Accueil</p>
            </div>
            <div class="screenshot-card">
                <div class="screenshot-img">
                    <img src="{{ asset('images/Rerchercher_Trajet.png') }}" alt="Recherche de Trajets">
                </div>
                <p class="screenshot-title">Recherche de Trajets</p>
            </div>
            <div class="screenshot-card">
                <div class="screenshot-img">
                    <i class="fas fa-image" style="font-size: 3rem;"></i>
                </div>
                <p class="screenshot-title">Publication de Trajet</p>
            </div>
            <div class="screenshot-card">
                <div class="screenshot-img">
                    <img src="{{ asset('images/Messagerie.png') }}" alt="Messagerie">
                </div>
                <p class="screenshot-title">Messagerie</p>
            </div>
            <div class="screenshot-card">
                <div class="screenshot-img">
                    <img src="{{ asset('images/Mes-Reservation.png') }}" alt="Mes Réservations">
                </div>
                <p class="screenshot-title">Mes Réservations</p>
            </div>
            <div class="screenshot-card">
                <div class="screenshot-img">
                    <img src="{{ asset('images/Profil.png') }}" alt="Profil Utilisateur">
                </div>
                <p class="screenshot-title">Profil Utilisateur</p>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="section team-section" id="team">
        <h2 class="section-title">Notre Équipe</h2>
        <div class="team-grid">
            <div class="team-member">
                <div class="team-avatar">J</div>
                <h3 class="team-name">Jad</h3>
                <p class="team-role">Développeur Full-Stack</p>
            </div>
            <div class="team-member">
                <div class="team-avatar">F</div>
                <h3 class="team-name">Francis</h3>
                <p class="team-role">Développeur Full-Stack</p>
            </div>
            <div class="team-member">
                <div class="team-avatar">J</div>
                <h3 class="team-name">Jeremy</h3>
                <p class="team-role">Développeur Full-Stack</p>
            </div>
            <div class="team-member">
                <div class="team-avatar">O</div>
                <h3 class="team-name">Omeed</h3>
                <p class="team-role">Développeur Full-Stack</p>
            </div>
            <div class="team-member">
                <div class="team-avatar">A</div>
                <h3 class="team-name">Abdel</h3>
                <p class="team-role">Développeur Full-Stack</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <h2 class="footer-logo">Covoit2025</h2>
            <p class="footer-text">
                Collège Lionel-Groulx - Projet d'intégration 2025<br>
                Développé avec ❤️ par notre équipe
            </p>
            <a href="/accueil" class="btn-enter-site">
                Entrer sur le site
            </a>
        </div>
    </footer>

    <!-- Image Modal -->
    <div id="imageModal" class="image-modal">
        <div class="modal-content">
            <button class="modal-close" onclick="closeModal()">&times;</button>
            <img id="modalImage" src="" alt="Screenshot">
        </div>
    </div>

    <script>
        // Image Modal Functions
        function openModal(imageSrc) {
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImage');
            modal.classList.add('active');
            modalImg.src = imageSrc;
            modal.scrollTop = 0; // Scroll to top when opening
        }

        function closeModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.remove('active');
        }

        // Close modal when clicking on the background (not on the image)
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this || e.target.classList.contains('modal-content')) {
                closeModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });

        // Add click event to all screenshot images
        document.addEventListener('DOMContentLoaded', function() {
            const screenshotImages = document.querySelectorAll('.screenshot-img img');
            screenshotImages.forEach(img => {
                img.addEventListener('click', function() {
                    openModal(this.src);
                });
            });
        });

        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });

        // Scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.feature-card, .screenshot-card, .team-member').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'all 0.6s ease';
            observer.observe(el);
        });
    </script>
</body>
</html>

