@extends('layouts.app')

@section('title', 'Accueil - Covoit2025')

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
    margin-bottom: var(--spacing-3xl);
    opacity: 0.9;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
    line-height: 1.6;
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

.how-it-works-section {
    padding: var(--spacing-3xl) 0;
    background: var(--gray-50);
}

.section-title {
    text-align: center;
    font-size: var(--font-size-4xl);
    font-weight: 700;
    color: var(--primary-blue);
    margin-bottom: var(--spacing-3xl);
}

.steps-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: var(--spacing-3xl);
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 var(--spacing-lg);
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
}

.benefits-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--spacing-3xl);
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 var(--spacing-lg);
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
        font-size: var(--font-size-3xl);
    }
    
    .value-card,
    .step-card,
    .benefit-card {
        padding: var(--spacing-lg);
    }
    
    .cta-button {
        width: 100%;
        max-width: 300px;
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
        <h1 class="hero-title">Voyagez ensemble aujourd'hui,<br>économisez ensemble toujours</h1>
        <p class="hero-subtitle">Découvrez comment nos utilisateurs fidèles voyagent avec Covoit2025</p>
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

<section class="cta-section">
    <h2 class="cta-title">Prêt à vous lancer ?</h2>
    <p class="cta-subtitle">Simple comme bonjour ! Gratuit. Facile. Avantageux.</p>
    <a href="/inscription" class="cta-button">
        <i class="fas fa-user-plus"></i> Créer un compte gratuitement
    </a>
</section>
@endsection