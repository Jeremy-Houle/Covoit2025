@extends('layouts.app')

@section('title', 'Accueil - Covoit2025')

@push('scripts')
    @vite(['resources/js/accueil.js'])
@endpush

@push('styles')
<style>

.hero-section {
    color: var(--white);
    padding: calc(70px + var(--spacing-3xl)) 0 var(--spacing-3xl);
    text-align: center;
    position: relative;
    overflow: hidden;
    min-height: 100vh;
    display: flex;
    align-items: center;
}

@media (hover: none) and (pointer: coarse) {
    .btn-voir-details,
    .btn-voir-plus,
    .cta-button {
        min-height: 48px;
        -webkit-tap-highlight-color: rgba(37, 99, 235, 0.3);
    }
    
    .value-card,
    .step-card,
    .benefit-card,
    .trajet-card {
        -webkit-tap-highlight-color: rgba(37, 99, 235, 0.1);
    }
    
    .nav-link {
        min-height: 44px;
    }
}

.hero-video {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    z-index: 0;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1;
}

.hero-content {
    position: relative;
    z-index: 2;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 var(--spacing-lg);
}

.hero-title {
    font-size: var(--font-size-5xl);
    font-weight: 800;
    margin-bottom: var(--spacing-lg);
    line-height: 1.2;
    text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
}

.hero-subtitle {
    font-size: var(--font-size-xl);
    margin-bottom: var(--spacing-lg);
    opacity: 0.9;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
    line-height: 1.6;
}

.hero-cta-button {
    background: var(--gradient-primary);
    color: var(--white);
    padding: var(--spacing-md) var(--spacing-2xl);
    border: none;
    border-radius: var(--border-radius-full);
    font-size: var(--font-size-base);
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition-normal);
    box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-xs);
    margin-top: var(--spacing-md);
}

.hero-cta-button:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(37, 99, 235, 0.5);
    color: var(--white);
    text-decoration: none;
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
}

.hero-cta-button i {
    font-size: 1.1rem;
}

.values-section {
    padding: var(--spacing-3xl) 0;
    background: var(--white);
}

.values-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: var(--spacing-3xl);
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 var(--spacing-lg);
}

.value-card {
    text-align: center;
    padding: var(--spacing-2xl);
    background: var(--white);
    border-radius: var(--border-radius-xl);
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--gray-200);
    transition: var(--transition-normal);
}

.value-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-xl);
}

.value-icon {
    width: 80px;
    height: 80px;
    background: var(--gradient-primary);
    border-radius: var(--border-radius-full);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto var(--spacing-lg);
    font-size: var(--font-size-3xl);
    color: var(--white);
    box-shadow: var(--shadow-blue);
}

.value-title {
    font-size: var(--font-size-2xl);
    font-weight: 700;
    color: var(--primary-blue);
    margin-bottom: var(--spacing-lg);
}

.value-description {
    color: var(--gray-600);
    line-height: 1.6;
    font-size: var(--font-size-base);
}

.trajets-populaires-section {
    padding: var(--spacing-3xl) 0;
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 50%, #f0f9ff 100%);
    position: relative;
}

.trajets-populaires-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-blue), var(--accent-yellow), var(--primary-blue));
}

.section-header {
    text-align: center;
    margin-bottom: var(--spacing-3xl);
}

.section-title {
    font-size: var(--font-size-4xl);
    font-weight: 800;
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: var(--spacing-sm);
}

.section-subtitle {
    color: var(--gray-600);
    font-size: var(--font-size-lg);
    max-width: 600px;
    margin: 0 auto;
}

.trajets-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: var(--spacing-lg);
    margin-bottom: var(--spacing-xl);
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
}

@media (max-width: 320px) {
    .trajets-grid {
        grid-template-columns: 1fr;
    }
}

.trajet-card {
    background: var(--white);
    border-radius: var(--border-radius-xl);
    box-shadow: var(--shadow-lg);
    padding: var(--spacing-lg);
    border: 2px solid transparent;
    transition: all var(--transition-normal);
    display: flex;
    flex-direction: column;
    gap: var(--spacing-sm);
}

.trajet-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-xl);
    border-color: var(--primary-blue);
}

.trajet-header {
    padding-bottom: var(--spacing-sm);
    border-bottom: 2px solid var(--gray-100);
}

.trajet-route {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: var(--spacing-xs);
}

.route-point {
    display: flex;
    align-items: center;
    gap: 6px;
    flex: 1;
    min-width: 0;
}

.route-point i {
    color: var(--primary-blue);
    font-size: var(--font-size-base);
    flex-shrink: 0;
}

.route-point span {
    font-size: var(--font-size-xs);
    font-weight: 600;
    color: var(--gray-800);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    display: block;
}

.route-arrow {
    color: var(--accent-yellow);
    font-size: var(--font-size-lg);
    flex-shrink: 0;
    padding: 0 4px;
}

.trajet-details {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
    padding: 6px var(--spacing-sm);
    background: var(--gray-50);
    border-radius: var(--border-radius-md);
}

.detail-item i {
    color: var(--primary-blue);
    font-size: var(--font-size-sm);
    width: 16px;
    text-align: center;
    flex-shrink: 0;
}

.detail-item span {
    color: var(--gray-700);
    font-size: var(--font-size-xs);
    font-weight: 500;
}

.trajet-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: var(--spacing-sm);
    border-top: 2px solid var(--gray-100);
}

.conducteur-info {
    display: flex;
    align-items: center;
    gap: 6px;
}

.conducteur-info i {
    color: var(--gray-500);
    font-size: var(--font-size-lg);
    flex-shrink: 0;
}

.conducteur-info span {
    color: var(--gray-700);
    font-weight: 600;
    font-size: var(--font-size-xs);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.prix-info .prix {
    font-size: var(--font-size-2xl);
    font-weight: 800;
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.btn-voir-details {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--spacing-xs);
    padding: 10px var(--spacing-md);
    background: var(--gradient-primary);
    color: var(--white);
    text-decoration: none;
    border-radius: var(--border-radius-lg);
    font-weight: 600;
    font-size: var(--font-size-xs);
    transition: all var(--transition-normal);
    box-shadow: var(--shadow-blue);
    margin-top: var(--spacing-xs);
}

.btn-voir-details:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-xl);
}

.no-trajets {
    text-align: center;
    padding: var(--spacing-3xl);
    background: var(--white);
    border-radius: var(--border-radius-xl);
    box-shadow: var(--shadow-lg);
}

.no-trajets i {
    font-size: var(--font-size-5xl);
    color: var(--gray-300);
    margin-bottom: var(--spacing-md);
}

.no-trajets p {
    color: var(--gray-600);
    font-size: var(--font-size-lg);
}

.voir-plus {
    text-align: center;
    margin-top: var(--spacing-2xl);
}

.btn-voir-plus {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-sm);
    padding: var(--spacing-md) var(--spacing-2xl);
    background: var(--gradient-secondary);
    color: var(--white);
    text-decoration: none;
    border-radius: var(--border-radius-lg);
    font-weight: 600;
    font-size: var(--font-size-base);
    transition: all var(--transition-normal);
    box-shadow: var(--shadow-lg);
}

.btn-voir-plus:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-xl);
}

@media (max-width: 1200px) {
    .values-grid {
        grid-template-columns: 1fr;
        gap: var(--spacing-xl);
    }
    
    .steps-grid {
        grid-template-columns: 1fr;
        gap: var(--spacing-xl);
    }
}

@media (max-width: 1024px) {
    .trajets-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: var(--spacing-md);
    }
    
    .benefits-grid {
        grid-template-columns: 1fr;
        gap: var(--spacing-xl);
    }
    
    .hero-section {
        padding: calc(70px + var(--spacing-2xl)) 0 var(--spacing-2xl);
    }
    
    .value-card,
    .step-card,
    .benefit-card {
        padding: var(--spacing-xl);
    }
}

@media (max-width: 768px) {
    .trajets-grid {
        grid-template-columns: 1fr;
        gap: var(--spacing-md);
    }
    
    .section-title {
        font-size: var(--font-size-2xl);
    }
    
    .section-subtitle {
        font-size: var(--font-size-base);
    }
    
    .trajet-card {
        padding: var(--spacing-md);
    }
    
    .hero-title {
        font-size: var(--font-size-3xl);
        margin-bottom: var(--spacing-md);
    }
    
    .hero-subtitle {
        font-size: var(--font-size-base);
        margin-bottom: var(--spacing-xl);
    }
    
    .hero-section {
        padding: calc(70px + var(--spacing-xl)) 0 var(--spacing-xl);
        min-height: 70vh;
    }
    
    .value-icon,
    .step-icon {
        width: 70px;
        height: 70px;
        font-size: var(--font-size-2xl);
    }
    
    .benefit-icon {
        width: 70px;
        height: 70px;
        font-size: var(--font-size-2xl);
        margin-bottom: var(--spacing-md);
    }
    
    .benefit-card {
        flex-direction: column;
        text-align: center;
        align-items: center;
    }
    
    .value-title,
    .step-title {
        font-size: var(--font-size-xl);
    }
    
    .benefit-content h3 {
        font-size: var(--font-size-xl);
    }
    
    .cta-title {
        font-size: var(--font-size-2xl);
    }
    
    .cta-subtitle {
        font-size: var(--font-size-base);
        margin-bottom: var(--spacing-xl);
    }
    
    .values-section,
    .how-it-works-section,
    .why-choose-section,
    .trajets-populaires-section,
    .cta-section {
        padding: var(--spacing-xl) 0;
    }
}

.how-it-works-section {
    padding: var(--spacing-3xl) 0;
    background: var(--gray-50);
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.section-title {
    text-align: center;
    font-size: var(--font-size-4xl);
    font-weight: 700;
    color: var(--primary-blue);
    margin-bottom: var(--spacing-3xl);
    width: 100%;
}

.steps-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: var(--spacing-3xl);
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 var(--spacing-lg);
    width: 100%;
    justify-items: center;
}

.step-card {
    text-align: center;
    padding: var(--spacing-2xl);
    background: var(--white);
    border-radius: var(--border-radius-xl);
    box-shadow: var(--shadow);
    border: 1px solid var(--gray-200);
    transition: var(--transition-normal);
    position: relative;
}

.step-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}

.step-number {
    position: absolute;
    top: -15px;
    left: 50%;
    transform: translateX(-50%);
    width: 40px;
    height: 40px;
    background: var(--gradient-primary);
    color: var(--white);
    border-radius: var(--border-radius-full);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: var(--font-size-lg);
    box-shadow: var(--shadow-blue);
}

.step-icon {
    width: 80px;
    height: 80px;
    background: var(--light-blue);
    border-radius: var(--border-radius-full);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: var(--spacing-lg) auto var(--spacing-lg);
    font-size: var(--font-size-3xl);
    color: var(--primary-blue);
    border: 3px solid var(--primary-blue);
}

.step-title {
    font-size: var(--font-size-2xl);
    font-weight: 600;
    color: var(--primary-blue);
    margin-bottom: var(--spacing-md);
}

.step-description {
    color: var(--gray-600);
    font-size: var(--font-size-base);
}

.why-choose-section {
    padding: var(--spacing-3xl) 0;
    background: var(--light-blue);
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.benefits-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--spacing-3xl);
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 var(--spacing-lg);
    width: 100%;
    justify-items: center;
}

.benefit-card {
    background: var(--white);
    padding: var(--spacing-2xl);
    border-radius: var(--border-radius-xl);
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--gray-200);
    transition: var(--transition-normal);
    display: flex;
    align-items: flex-start;
    gap: var(--spacing-lg);
}

.benefit-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-xl);
}

.benefit-icon {
    width: 80px;
    height: 80px;
    background: var(--gradient-secondary);
    border-radius: var(--border-radius-full);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: var(--font-size-3xl);
    color: var(--white);
    flex-shrink: 0;
    box-shadow: var(--shadow-lg);
}

.benefit-content h3 {
    font-size: var(--font-size-2xl);
    font-weight: 700;
    color: var(--primary-blue);
    margin-bottom: var(--spacing-md);
}

.benefit-content p {
    color: var(--gray-600);
    line-height: 1.6;
    font-size: var(--font-size-base);
}

.cta-section {
    padding: var(--spacing-3xl) 0;
    background: var(--white);
    text-align: center;
}

.cta-title {
    font-size: var(--font-size-4xl);
    font-weight: 700;
    color: var(--primary-blue);
    margin-bottom: var(--spacing-lg);
}

.cta-subtitle {
    font-size: var(--font-size-xl);
    color: var(--gray-600);
    margin-bottom: var(--spacing-3xl);
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.cta-button {
    background: var(--gradient-primary);
    color: var(--white);
    padding: var(--spacing-lg) var(--spacing-3xl);
    border: none;
    border-radius: var(--border-radius-full);
    font-size: var(--font-size-lg);
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition-normal);
    box-shadow: var(--shadow-blue);
    text-decoration: none;
    display: inline-block;
}

.cta-button:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-xl);
    color: var(--white);
    text-decoration: none;
}

@media (max-width: 1024px) {
    .values-grid,
    .steps-grid {
        grid-template-columns: 1fr;
        gap: var(--spacing-2xl);
    }
    
    .benefits-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .hero-title {
        font-size: var(--font-size-4xl);
    }
    
    .hero-subtitle {
        font-size: var(--font-size-lg);
    }
    
    .section-title {
        font-size: var(--font-size-3xl);
    }
    
    .cta-title {
        font-size: var(--font-size-3xl);
    }
    
    .cta-subtitle {
        font-size: var(--font-size-lg);
    }
}

@media (max-width: 480px) {
    .hero-title {
        font-size: var(--font-size-2xl);
        line-height: 1.3;
    }
    
    .hero-subtitle {
        font-size: var(--font-size-sm);
        padding: 0 var(--spacing-sm);
    }
    
    .hero-cta-button {
        font-size: 0.9rem;
        padding: var(--spacing-sm) var(--spacing-lg);
    }
    
    .hero-section {
        min-height: 60vh;
        padding: calc(60px + var(--spacing-lg)) 0 var(--spacing-lg);
    }
    
    .hero-content {
        padding: 0 var(--spacing-md);
    }
    
    .value-card,
    .step-card,
    .benefit-card {
        padding: var(--spacing-lg);
    }
    
    .value-icon,
    .step-icon {
        width: 60px;
        height: 60px;
        font-size: var(--font-size-xl);
        margin-bottom: var(--spacing-md);
    }
    
    .benefit-icon {
        width: 60px;
        height: 60px;
        font-size: var(--font-size-xl);
    }
    
    .value-title,
    .step-title {
        font-size: var(--font-size-lg);
        margin-bottom: var(--spacing-sm);
    }
    
    .benefit-content h3 {
        font-size: var(--font-size-lg);
    }
    
    .value-description,
    .step-description,
    .benefit-content p {
        font-size: var(--font-size-sm);
    }
    
    .section-title {
        font-size: var(--font-size-xl);
        margin-bottom: var(--spacing-lg);
    }
    
    .section-subtitle {
        font-size: var(--font-size-sm);
        padding: 0 var(--spacing-sm);
    }
    
    .cta-button {
        width: 100%;
        max-width: 100%;
        padding: var(--spacing-md) var(--spacing-lg);
        font-size: var(--font-size-base);
    }
    
    .cta-title {
        font-size: var(--font-size-xl);
    }
    
    .cta-subtitle {
        font-size: var(--font-size-sm);
        padding: 0 var(--spacing-md);
    }
    
    .trajet-route {
        flex-direction: column;
        align-items: flex-start;
        gap: var(--spacing-sm);
    }
    
    .route-arrow {
        transform: rotate(90deg);
        margin: 0 auto;
    }
    
    .route-point {
        width: 100%;
    }
    
    .trajet-footer {
        flex-direction: column;
        align-items: flex-start;
        gap: var(--spacing-sm);
    }
    
    .btn-voir-details,
    .btn-voir-plus {
        width: 100%;
        padding: var(--spacing-md);
        font-size: var(--font-size-sm);
    }
    
    .values-section,
    .how-it-works-section,
    .why-choose-section,
    .trajets-populaires-section,
    .cta-section {
        padding: var(--spacing-lg) 0;
    }
    
    .values-grid,
    .steps-grid,
    .benefits-grid {
        gap: var(--spacing-lg);
    }
    
    .step-number {
        width: 35px;
        height: 35px;
        font-size: var(--font-size-base);
    }
    
    .container {
        padding: 0 var(--spacing-md);
    }
}

@media (max-width: 360px) {
    .hero-title {
        font-size: var(--font-size-xl);
    }
    
    .hero-subtitle {
        font-size: 0.8rem;
    }
    
    .section-title {
        font-size: var(--font-size-lg);
    }
    
    .value-icon,
    .step-icon,
    .benefit-icon {
        width: 50px;
        height: 50px;
        font-size: var(--font-size-lg);
    }
    
    .value-title,
    .step-title,
    .benefit-content h3 {
        font-size: var(--font-size-base);
    }
    
    .cta-title {
        font-size: var(--font-size-lg);
    }
    
    .step-number {
        width: 30px;
        height: 30px;
        font-size: var(--font-size-sm);
        top: -12px;
    }
    
    .trajet-card {
        padding: var(--spacing-sm);
    }
}
</style>
@endpush

@section('content')
<section class="hero-section">
    <video class="hero-video" autoplay muted loop playsinline>
        <source src="{{ asset('Video/ride_share.mp4') }}" type="video/mp4">
        Votre navigateur ne supporte pas les vidéos HTML5.
    </video>
    <div class="hero-content">
        <h1 class="hero-title">Voyagez ensemble,<br>économisez ensemble </h1>
        <p class="hero-subtitle">Découvrez comment nos utilisateurs fidèles voyagent avec Covoit2025</p>
        <a href="/inscription" class="hero-cta-button">
            <i class="fas fa-user-plus"></i> Créer un compte gratuitement
        </a>
    </div>
</section>

<section class="values-section">
    <div class="values-grid">
        <div class="value-card animate-fade-in-up">
            <div class="value-icon">
                <i class="fas fa-handshake"></i>
            </div>
            <h3 class="value-title">Confiance</h3>
            <p class="value-description">
                Chez Covoit2025, nous croyons que chaque trajet commence par un lien de confiance. 
                Nos conducteurs et passagers s'appuient sur un système transparent d'avis et de profils 
                vérifiés afin que chaque voyage soit une expérience sereine.
            </p>
        </div>
        
        <div class="value-card animate-fade-in-up">
            <div class="value-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h3 class="value-title">Sécurité</h3>
            <p class="value-description">
                La sécurité est au cœur de tout ce que nous faisons. De la vérification des conducteurs 
                à la mise en place de fonctionnalités d'urgence accessibles en un clic, nous garantissons 
                que chaque déplacement soit aussi sûr que confortable.
            </p>
        </div>
        
        <div class="value-card animate-fade-in-up">
            <div class="value-icon">
                <i class="fas fa-heart"></i>
            </div>
            <h3 class="value-title">Honnêteté</h3>
            <p class="value-description">
                La transparence et l'honnêteté guident chaque interaction sur Covoit2025. Pas de frais cachés, 
                pas de détours imprévus : simplement des trajets clairs et équitables, où chacun respecte ses engagements.
            </p>
        </div>
    </div>
</section>

<section class="how-it-works-section">
    <h2 class="section-title">Comment ça marche ?</h2>
    <div class="steps-grid">
        <div class="step-card">
            <div class="step-number">1</div>
            <div class="step-icon">
                <i class="fas fa-search"></i>
            </div>
            <h3 class="step-title">Recherchez</h3>
            <p class="step-description">Parmi nos nombreux trajets disponibles</p>
        </div>
        
        <div class="step-card">
            <div class="step-number">2</div>
            <div class="step-icon">
                <i class="fas fa-credit-card"></i>
            </div>
            <h3 class="step-title">Réservez</h3>
            <p class="step-description">Facilement en ligne avec notre système sécurisé</p>
        </div>
        
        <div class="step-card">
            <div class="step-number">3</div>
            <div class="step-icon">
                <i class="fas fa-car"></i>
            </div>
            <h3 class="step-title">Voyagez</h3>
            <p class="step-description">Et le tour est joué ! Profitez de votre trajet</p>
        </div>
    </div>
</section>

<section class="why-choose-section">
    <h2 class="section-title">Pourquoi nous choisir ?</h2>
    <div class="benefits-grid">
        <div class="benefit-card">
            <div class="benefit-icon">
                <i class="fas fa-leaf"></i>
            </div>
            <div class="benefit-content">
                <h3>Écologique</h3>
                <p>Environ le quart des émissions CO2 mondiales est dû au transport. Le covoiturage est l'une des meilleures options pour réduire nos émissions et protéger notre planète !</p>
            </div>
        </div>
        
        <div class="benefit-card">
            <div class="benefit-icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="benefit-content">
                <h3>Économique</h3>
                <p>Avec le prix de l'essence en constante augmentation, ne payez plus des centaines de dollars en essence par année ! Partagez les coûts et économisez ensemble.</p>
            </div>
        </div>
    </div>
</section>

<section class="trajets-populaires-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">🔥 Trajets Populaires</h2>
            <p class="section-subtitle">Découvrez les derniers trajets disponibles et réservez votre place dès maintenant</p>
        </div>
        
        @if($trajetsPopulaires->isEmpty())
            <div class="no-trajets">
                <i class="fas fa-car-side"></i>
                <p>Aucun trajet disponible pour le moment. Revenez plus tard !</p>
            </div>
        @else
            <div class="trajets-grid">
                @foreach($trajetsPopulaires as $trajet)
                    <div class="trajet-card">
                        <div class="trajet-header">
                            <div class="trajet-route">
                                <div class="route-point">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>{{ $trajet->Depart }}</span>
                                </div>
                                <div class="route-arrow">
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                                <div class="route-point">
                                    <i class="fas fa-flag-checkered"></i>
                                    <span>{{ $trajet->Destination }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="trajet-details">
                            <div class="detail-item">
                                <i class="fas fa-calendar-alt"></i>
                                <span>{{ \Carbon\Carbon::parse($trajet->DateTrajet)->format('d/m/Y') }}</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-clock"></i>
                                <span>{{ \Carbon\Carbon::parse($trajet->HeureTrajet)->format('H:i') }}</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-user-friends"></i>
                                <span>{{ $trajet->PlacesDisponibles }} place(s)</span>
                            </div>
                        </div>
                        
                        <div class="trajet-footer">
                            <div class="conducteur-info">
                                <i class="fas fa-user-circle"></i>
                                <span>{{ $trajet->ConducteurPrenom }}</span>
                            </div>
                            <div class="prix-info">
                                <span class="prix">{{ $trajet->Prix }}$</span>
                            </div>
                        </div>
                        
                        <a href="/rechercher" class="btn-voir-details">
                            <i class="fas fa-search"></i> Voir les détails
                        </a>
                    </div>
                @endforeach
            </div>
            
            <div class="voir-plus">
                <a href="/rechercher" class="btn-voir-plus">
                    Voir tous les trajets <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        @endif
    </div>
</section>

<section class="cta-section">
    <h2 class="cta-title">Prêt à vous lancer ?</h2>
    <p class="cta-subtitle">Simple comme bonjour ! Gratuit. Facile. Avantageux.</p>
    <a href="/inscription" class="cta-button">
        <i class="fas fa-user-plus"></i> Créer un compte gratuitement
    </a>
</section>
@endsection