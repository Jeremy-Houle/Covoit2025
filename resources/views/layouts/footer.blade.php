<footer class="site-footer">
    <div class="footer-container">
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
                    <li><a href="/tarifs">Tarifs</a></li>
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
                    <span>contact@covoit2025.com</span>
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

        <div class="footer-divider"></div>

        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} Covoit2025. Tous droits réservés.</p>
            <p class="footer-tagline">Développé par Jad,Francis,Jeremy,Omeed et Abdel</p>
        </div>
    </div>
</footer>

<style>
.site-footer {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    color: #ffffff;
    margin-top: 60px;
    position: relative;
    overflow: hidden;
}

        body:has(.panier-page) .site-footer,
        body:has(.profil-page) .site-footer,
        body:has(.edit-profil-page) .site-footer,
        body:has(.auth-page) .site-footer,
        body:has(.about-page) .site-footer {
            margin-top: 0;
        }

.site-footer::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #ffd700, #ffed4e, #ffd700);
}

.footer-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 40px 20px 20px;
}

.footer-main {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1.5fr;
    gap: 40px;
    margin-bottom: 30px;
}

.footer-brand {
    max-width: 300px;
}

.footer-logo {
    font-size: 1.5em;
    font-weight: bold;
    color: #ffd700;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.footer-logo i {
    font-size: 1.2em;
}

.footer-description {
    color: #b8d4f0;
    line-height: 1.6;
    margin-bottom: 20px;
    font-size: 0.95em;
}

.footer-social {
    display: flex;
    gap: 12px;
}

.social-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    background: rgba(255, 215, 0, 0.1);
    border: 1px solid rgba(255, 215, 0, 0.3);
    border-radius: 50%;
    color: #ffd700;
    text-decoration: none;
    transition: all 0.3s ease;
}

.social-link:hover {
    background: #ffd700;
    color: #1e3c72;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 215, 0, 0.3);
}

.footer-nav h4,
.footer-info h4,
.footer-contact h4 {
    color: #ffd700;
    font-size: 1.1em;
    font-weight: 600;
    margin-bottom: 15px;
    position: relative;
}

.footer-nav h4::after,
.footer-info h4::after,
.footer-contact h4::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 0;
    width: 30px;
    height: 2px;
    background: #ffd700;
}

.footer-nav ul,
.footer-info ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-nav li,
.footer-info li {
    margin-bottom: 8px;
}

.footer-nav a,
.footer-info a {
    color: #b8d4f0;
    text-decoration: none;
    font-size: 0.9em;
    transition: color 0.3s ease;
    display: flex;
    align-items: center;
}

.footer-nav a:hover,
.footer-info a:hover {
    color: #ffd700;
    padding-left: 5px;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 12px;
    color: #b8d4f0;
    font-size: 0.9em;
}

.contact-item i {
    color: #ffd700;
    width: 16px;
    text-align: center;
}

.footer-divider {
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255, 215, 0, 0.3), transparent);
    margin: 20px 0;
}

.footer-bottom {
    text-align: center;
    padding-top: 20px;
}

.footer-bottom p {
    margin: 5px 0;
    color: #b8d4f0;
    font-size: 0.9em;
}

.footer-tagline {
    color: #ffd700;
    font-style: italic;
}

@media (max-width: 768px) {
    .footer-main {
        grid-template-columns: 1fr;
        gap: 30px;
        text-align: center;
    }
    
    .footer-brand {
        max-width: none;
    }
    
    .footer-container {
        padding: 30px 15px 15px;
    }
    
    .footer-nav a:hover,
    .footer-info a:hover {
        padding-left: 0;
    }
}

@media (max-width: 480px) {
    .footer-main {
        gap: 25px;
    }
    
    .footer-social {
        justify-content: center;
    }
}
</style>