<footer class="site-footer">
    <div class="container">
        <div class="footer-main">
            <div class="footer-brand">
                <div class="footer-logo">
                    <i class="fa fa-car"></i> Covoit2025
                </div>
                <p class="footer-description">
                    Voyagez ensemble aujourd'hui, économisez ensemble toujours.
                    La plateforme de covoiturage moderne et sécurisée.
                </p>
                <div class="footer-social">
                    <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>

            <div class="footer-nav">
                <h4>Navigation</h4>
                <ul>
                    <li><a href="/">Accueil</a></li>
                    <li><a href="/rechercher">Rechercher un trajet</a></li>
                    <li><a href="/publier">Publier un trajet</a></li>
                    <li><a href="/mes-reservations">Mes réservations</a></li>
                </ul>
            </div>

            <div class="footer-info">
                <h4>Informations</h4>
                <ul>
                    <li><a href="/about">À propos</a></li>
                    <li><a href="/faq">FAQ</a></li>
                    <li><a href="/contact">Contact</a></li>
                    <li><a href="/privacy">Confidentialité</a></li>
                    <li><a href="/terms">Conditions d'utilisation</a></li>
                </ul>
            </div>

            <div class="footer-contact">
                <h4>Contact</h4>
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <span>covoit.2025@gmail.com</span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <span>+1 (555) 123-4567</span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Montréal, QC, Canada</span>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} Covoit2025. Tous droits réservés.</p>
            <p class="footer-tagline">Développé par Jad, Francis, Jeremy, Omeed et Abdel</p>
        </div>
    </div>
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA15cEUHuUk0chFVlLu9MtJ06aEYwoVqzc&callback=initMap&libraries=places">
        </script>

</footer>

<style>
    .site-footer {
        background: var(--gradient-hero);
        color: var(--white);
        margin-top: 0;
        position: relative;
        overflow: hidden;
    }

    .site-footer::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #fbbf24, #f59e0b, #fbbf24);
    }

    .footer-main {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1.5fr;
        gap: var(--spacing-2xl);
        margin-bottom: var(--spacing-lg);
        padding: var(--spacing-2xl) 0;
    }

    .footer-brand {
        max-width: 350px;
    }

    .footer-logo {
        font-size: var(--font-size-2xl);
        font-weight: 700;
        color: #fbbf24;
        margin-bottom: var(--spacing-lg);
        display: flex;
        align-items: center;
        gap: var(--spacing-sm);
    }

    .footer-logo i {
        font-size: var(--font-size-xl);
    }

    .footer-description {
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.6;
        margin-bottom: var(--spacing-xl);
        font-size: var(--font-size-sm);
    }

    .footer-social {
        display: flex;
        gap: var(--spacing-md);
    }

    .social-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        background: rgba(251, 191, 36, 0.1);
        border: 2px solid rgba(251, 191, 36, 0.3);
        border-radius: var(--border-radius-full);
        color: #fbbf24;
        text-decoration: none;
        transition: var(--transition-normal);
        font-size: var(--font-size-lg);
    }

    .social-link:hover {
        background: #fbbf24;
        color: var(--primary-blue-dark);
        transform: translateY(-3px);
        box-shadow: var(--shadow-lg);
    }

    .footer-nav h4,
    .footer-info h4,
    .footer-contact h4 {
        color: #fbbf24;
        font-size: var(--font-size-lg);
        font-weight: 600;
        margin-bottom: var(--spacing-lg);
        position: relative;
    }

    .footer-nav h4::after,
    .footer-info h4::after,
    .footer-contact h4::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 0;
        width: 40px;
        height: 3px;
        background: #fbbf24;
        border-radius: var(--border-radius-full);
    }

    .footer-nav ul,
    .footer-info ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-nav li,
    .footer-info li {
        margin-bottom: var(--spacing-sm);
    }

    .footer-nav a,
    .footer-info a {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        font-size: var(--font-size-sm);
        transition: var(--transition-normal);
        display: flex;
        align-items: center;
        padding: var(--spacing-xs) 0;
    }

    .footer-nav a:hover,
    .footer-info a:hover {
        color: #fbbf24;
        transform: translateX(5px);
    }

    .contact-item {
        display: flex;
        align-items: center;
        gap: var(--spacing-md);
        margin-bottom: var(--spacing-md);
        color: rgba(255, 255, 255, 0.8);
        font-size: var(--font-size-sm);
    }

    .contact-item i {
        color: #fbbf24;
        width: 20px;
        text-align: center;
        font-size: var(--font-size-base);
    }


    .footer-bottom {
        text-align: center;
        padding: var(--spacing-lg) 0;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .footer-bottom p {
        margin: var(--spacing-xs) 0;
        color: rgba(255, 255, 255, 0.7);
        font-size: var(--font-size-sm);
    }

    .footer-tagline {
        color: #fbbf24;
        font-style: italic;
        font-weight: 500;
    }

    @media (max-width: 1024px) {
        .footer-main {
            grid-template-columns: 1fr 1fr;
            gap: var(--spacing-2xl);
        }
    }

    @media (max-width: 768px) {
        .footer-main {
            grid-template-columns: 1fr;
            gap: var(--spacing-2xl);
            text-align: center;
        }

        .footer-brand {
            max-width: none;
        }

        .footer-nav a:hover,
        .footer-info a:hover {
            transform: none;
        }

        .footer-social {
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .footer-main {
            gap: var(--spacing-xl);
        }

        .footer-social {
            gap: var(--spacing-sm);
        }

        .social-link {
            width: 40px;
            height: 40px;
            font-size: var(--font-size-base);
        }
    }
</style>