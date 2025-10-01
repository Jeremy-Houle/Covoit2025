@extends('layouts.app')

@section('title', 'À propos')

@section('content')
<section class="about-hero">
    <div class="about-hero-content">
        <h1 class="about-hero-title">À propos de Covoit2025</h1>
        <p class="about-hero-subtitle">Notre mission : révolutionner le covoiturage avec une plateforme moderne, sécurisée et conviviale</p>
    </div>
</section>

<section class="about-mission">
    <div class="container">
        <div class="mission-content">
            <div class="mission-text">
                <h2>Notre Mission</h2>
                <p>Covoit2025 est une plateforme dédiée au covoiturage moderne, sécurisée et conviviale. Notre mission est de faciliter les déplacements, réduire l'empreinte carbone et créer une communauté d'utilisateurs engagés.</p>
                <p>Nous croyons que voyager ensemble peut transformer notre façon de nous déplacer, en créant des connexions humaines tout en protégeant notre environnement.</p>
            </div>
            <div class="mission-stats">
                <div class="stat-item">
                    <div class="stat-number">1000+</div>
                    <div class="stat-label">Utilisateurs actifs</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Trajets par mois</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">95%</div>
                    <div class="stat-label">Satisfaction</div>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="about-team">
    <div class="container">
        <h2 class="section-title">Notre Équipe</h2>
        <div class="team-grid">
            <div class="team-member">
                <div class="member-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <h3>Jad</h3>
                <p>Développeur Full-Stack</p>
            </div>
            
            <div class="team-member">
                <div class="member-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <h3>Francis</h3>
                <p>Développeur Full-Stack</p>
            </div>
            
            <div class="team-member">
                <div class="member-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <h3>Jeremy</h3>
                <p>Développeur Full-Stack</p>
            </div>
            
            <div class="team-member">
                <div class="member-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <h3>Omeed</h3>
                <p>Développeur Full-Stack</p>
            </div>
            
            <div class="team-member">
                <div class="member-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <h3>Abdel</h3>
                <p>Développeur Full-Stack</p>
            </div>
        </div>
    </div>
</section>


@push('styles')
<style>
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 var(--spacing-lg);
}

.about-hero {
    background: var(--gradient-hero);
    color: var(--white);
    padding: calc(70px + var(--spacing-3xl)) 0 var(--spacing-3xl);
    text-align: center;
    position: relative;
    overflow: hidden;
}

.about-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.about-hero-content {
    position: relative;
    z-index: 1;
    max-width: 800px;
    margin: 0 auto;
    padding: 0 var(--spacing-lg);
}

.about-hero-title {
    font-size: var(--font-size-5xl);
    font-weight: 800;
    margin-bottom: var(--spacing-lg);
    line-height: 1.2;
    text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
}

.about-hero-subtitle {
    font-size: var(--font-size-xl);
    opacity: 0.9;
    line-height: 1.6;
}

.about-mission {
    padding: var(--spacing-3xl) 0;
    background: var(--white);
}

.mission-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: var(--spacing-3xl);
    align-items: center;
}

.mission-text h2 {
    font-size: var(--font-size-3xl);
    font-weight: 700;
    color: var(--primary-blue);
    margin-bottom: var(--spacing-lg);
}

.mission-text p {
    color: var(--gray-600);
    line-height: 1.6;
    margin-bottom: var(--spacing-lg);
    font-size: var(--font-size-base);
}

.mission-stats {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-lg);
}

.stat-item {
    text-align: center;
    padding: var(--spacing-lg);
    background: var(--light-blue);
    border-radius: var(--border-radius-xl);
    border: 2px solid var(--primary-blue);
}

.stat-number {
    font-size: var(--font-size-3xl);
    font-weight: 800;
    color: var(--primary-blue);
    margin-bottom: var(--spacing-xs);
}

.stat-label {
    color: var(--gray-600);
    font-weight: 600;
    font-size: var(--font-size-sm);
}

.section-title {
    text-align: center;
    font-size: var(--font-size-4xl);
    font-weight: 700;
    color: var(--primary-blue);
    margin-bottom: var(--spacing-3xl);
}

.about-team {
    padding: var(--spacing-3xl) 0;
    background: var(--white);
}

.team-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: var(--spacing-xl);
}

.team-member {
    text-align: center;
    padding: var(--spacing-lg);
    background: var(--light-blue);
    border-radius: var(--border-radius-xl);
    border: 2px solid var(--primary-blue);
    transition: var(--transition-normal);
}

.team-member:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}

.member-avatar {
    width: 80px;
    height: 80px;
    background: var(--gradient-primary);
    border-radius: var(--border-radius-full);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto var(--spacing-md);
    font-size: var(--font-size-2xl);
    color: var(--white);
    box-shadow: var(--shadow-blue);
}

.team-member h3 {
    font-size: var(--font-size-lg);
    font-weight: 700;
    color: var(--primary-blue);
    margin-bottom: var(--spacing-xs);
}

.team-member p {
    color: var(--gray-600);
    font-size: var(--font-size-sm);
    font-weight: 500;
}


@media (max-width: 1024px) {
    .mission-content {
        grid-template-columns: 1fr;
        gap: var(--spacing-2xl);
    }
    
    .team-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 768px) {
    .about-hero-title {
        font-size: var(--font-size-4xl);
    }
    
    .about-hero-subtitle {
        font-size: var(--font-size-lg);
    }
    
    .section-title {
        font-size: var(--font-size-3xl);
    }
    
    .team-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .mission-stats {
        flex-direction: row;
        justify-content: space-around;
    }
}

@media (max-width: 480px) {
    .about-hero-title {
        font-size: var(--font-size-3xl);
    }
    
    .team-grid {
        grid-template-columns: 1fr;
    }
    
    .mission-stats {
        flex-direction: column;
    }
    
    .team-member {
        padding: var(--spacing-lg);
    }
}
</style>
@endpush

@endsection

