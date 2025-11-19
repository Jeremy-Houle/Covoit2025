@extends('layouts.app')

@section('title', 'Contact - Covoit2025')

@push('scripts')
    @vite(['resources/js/animations/contact-animations.js'])
@endpush

@push('styles')
<style>
.contact-page {
    min-height: 100vh;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    padding: calc(70px + var(--spacing-xl)) var(--spacing-md) var(--spacing-2xl);
}

.contact-container {
    max-width: 1200px;
    margin: 0 auto;
}

.contact-header {
    text-align: center;
    margin-bottom: var(--spacing-2xl);
}

.contact-header h1 {
    font-size: var(--font-size-3xl);
    font-weight: 800;
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: var(--spacing-sm);
}

.contact-header p {
    color: var(--gray-600);
    font-size: var(--font-size-lg);
    max-width: 600px;
    margin: 0 auto;
}

.contact-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-2xl);
    margin-bottom: var(--spacing-2xl);
}

.contact-info-card {
    background: var(--white);
    border-radius: var(--border-radius-xl);
    padding: var(--spacing-2xl);
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--gray-200);
}

.info-title {
    font-size: var(--font-size-xl);
    font-weight: 700;
    color: var(--primary-blue);
    margin-bottom: var(--spacing-xl);
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
}

.info-title i {
    color: var(--accent-yellow);
}

.info-item {
    display: flex;
    align-items: flex-start;
    gap: var(--spacing-md);
    padding: var(--spacing-md);
    margin-bottom: var(--spacing-sm);
    background: var(--gray-50);
    border-radius: var(--border-radius-lg);
    transition: var(--transition-normal);
}

.info-item:hover {
    background: var(--gray-100);
    transform: translateX(5px);
}

.info-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--gradient-primary);
    color: var(--white);
    border-radius: var(--border-radius-full);
    font-size: var(--font-size-lg);
    flex-shrink: 0;
}

.info-details h3 {
    font-size: var(--font-size-sm);
    font-weight: 600;
    color: var(--gray-500);
    margin-bottom: var(--spacing-xs);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-details p {
    font-size: var(--font-size-base);
    color: var(--gray-800);
    font-weight: 500;
    margin: 0;
    line-height: 1.5;
}

.info-details a {
    color: var(--primary-blue);
    text-decoration: none;
    transition: var(--transition-normal);
}

.info-details a:hover {
    color: var(--primary-blue-dark);
    text-decoration: underline;
}

.contact-form-card {
    background: var(--white);
    border-radius: var(--border-radius-xl);
    padding: var(--spacing-2xl);
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--gray-200);
}

.form-title {
    font-size: var(--font-size-xl);
    font-weight: 700;
    color: var(--primary-blue);
    margin-bottom: var(--spacing-xl);
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
}

.form-title i {
    color: var(--accent-yellow);
}

.form-group {
    margin-bottom: var(--spacing-md);
}

.form-label {
    display: block;
    margin-bottom: var(--spacing-xs);
    color: var(--gray-700);
    font-weight: 600;
    font-size: var(--font-size-sm);
}

.form-input,
.form-textarea {
    width: 100%;
    padding: var(--spacing-sm);
    border: 2px solid var(--gray-200);
    border-radius: var(--border-radius-lg);
    font-size: var(--font-size-sm);
    transition: var(--transition-normal);
    background: var(--white);
    color: var(--gray-800);
    font-family: 'Inter', sans-serif;
}

.form-input:focus,
.form-textarea:focus {
    outline: none;
    border-color: var(--primary-blue);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.form-textarea {
    min-height: 120px;
    resize: vertical;
}

.submit-btn {
    width: 100%;
    background: var(--gradient-primary);
    color: var(--white);
    padding: var(--spacing-sm) var(--spacing-lg);
    border: none;
    border-radius: var(--border-radius-lg);
    font-size: var(--font-size-base);
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition-normal);
    box-shadow: var(--shadow-blue);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--spacing-xs);
}

.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-xl);
}

.map-card {
    background: var(--white);
    border-radius: var(--border-radius-xl);
    padding: var(--spacing-lg);
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--gray-200);
    overflow: hidden;
}

.map-title {
    font-size: var(--font-size-xl);
    font-weight: 700;
    color: var(--primary-blue);
    margin-bottom: var(--spacing-md);
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
}

.map-title i {
    color: var(--accent-yellow);
}

.map-container {
    width: 100%;
    height: 400px;
    border-radius: var(--border-radius-lg);
    overflow: hidden;
    border: 2px solid var(--gray-200);
}

.hours-section {
    margin-top: var(--spacing-lg);
    padding: var(--spacing-md);
    background: var(--gray-50);
    border-radius: var(--border-radius-lg);
}

.hours-section h4 {
    font-size: var(--font-size-base);
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: var(--spacing-sm);
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
}

.hours-section p {
    color: var(--gray-600);
    font-size: var(--font-size-sm);
    margin: 0;
}

.open-badge {
    display: inline-block;
    padding: var(--spacing-xs) var(--spacing-sm);
    background: #10b981;
    color: var(--white);
    border-radius: var(--border-radius-full);
    font-size: var(--font-size-xs);
    font-weight: 600;
    margin-left: var(--spacing-xs);
}

@media (max-width: 1024px) {
    .contact-page {
        padding: calc(70px + var(--spacing-lg)) var(--spacing-md) var(--spacing-xl);
    }
    
    .contact-content {
        gap: var(--spacing-xl);
    }
}

@media (max-width: 768px) {
    .contact-page {
        padding: calc(70px + var(--spacing-md)) var(--spacing-sm) var(--spacing-xl);
    }
    
    .contact-header {
        margin-bottom: var(--spacing-xl);
    }
    
    .contact-header h1 {
        font-size: var(--font-size-2xl);
    }
    
    .contact-header p {
        font-size: var(--font-size-base);
        padding: 0 var(--spacing-sm);
    }
    
    .contact-content {
        grid-template-columns: 1fr;
        gap: var(--spacing-lg);
    }
    
    .contact-info-card,
    .contact-form-card,
    .map-card {
        padding: var(--spacing-xl);
    }
    
    .info-title,
    .form-title,
    .map-title {
        font-size: var(--font-size-lg);
        margin-bottom: var(--spacing-md);
    }
    
    .info-item {
        padding: var(--spacing-sm) var(--spacing-md);
        gap: var(--spacing-sm);
    }
    
    .info-icon {
        width: 35px;
        height: 35px;
        font-size: var(--font-size-base);
    }
    
    .info-details h3 {
        font-size: 0.7rem;
    }
    
    .info-details p {
        font-size: var(--font-size-sm);
    }
    
    .form-input,
    .form-textarea {
        padding: var(--spacing-sm);
        font-size: var(--font-size-sm);
    }
    
    .submit-btn {
        padding: var(--spacing-sm) var(--spacing-md);
        font-size: var(--font-size-sm);
    }
    
    .map-container {
        height: 300px;
    }
    
    .hours-section {
        padding: var(--spacing-sm) var(--spacing-md);
    }
    
    .hours-section h4 {
        font-size: var(--font-size-sm);
    }
    
    .hours-section p {
        font-size: 0.8rem;
    }
}

@media (max-width: 480px) {
    .contact-page {
        padding: calc(60px + var(--spacing-sm)) var(--spacing-xs) var(--spacing-lg);
    }
    
    .contact-header {
        margin-bottom: var(--spacing-lg);
    }
    
    .contact-header h1 {
        font-size: var(--font-size-xl);
        padding: 0 var(--spacing-xs);
    }
    
    .contact-header p {
        font-size: var(--font-size-sm);
        padding: 0 var(--spacing-xs);
        line-height: 1.5;
    }
    
    .contact-content {
        gap: var(--spacing-md);
    }
    
    .contact-info-card,
    .contact-form-card,
    .map-card {
        padding: var(--spacing-md);
    }
    
    .info-title,
    .form-title,
    .map-title {
        font-size: var(--font-size-base);
        margin-bottom: var(--spacing-sm);
        gap: var(--spacing-xs);
    }
    
    .info-title i,
    .form-title i,
    .map-title i {
        font-size: var(--font-size-sm);
    }
    
    .info-item {
        padding: var(--spacing-xs) var(--spacing-sm);
        gap: var(--spacing-xs);
        margin-bottom: var(--spacing-xs);
    }
    
    .info-icon {
        width: 32px;
        height: 32px;
        font-size: var(--font-size-sm);
    }
    
    .info-details h3 {
        font-size: 0.65rem;
        margin-bottom: 2px;
    }
    
    .info-details p {
        font-size: var(--font-size-sm);
        line-height: 1.4;
    }
    
    .form-group {
        margin-bottom: var(--spacing-sm);
    }
    
    .form-label {
        font-size: var(--font-size-sm);
        margin-bottom: 4px;
    }
    
    .form-input,
    .form-textarea {
        padding: var(--spacing-xs) var(--spacing-sm);
        font-size: var(--font-size-sm);
    }
    
    .form-textarea {
        min-height: 100px;
    }
    
    .submit-btn {
        padding: var(--spacing-sm);
        font-size: var(--font-size-sm);
        gap: var(--spacing-xs);
    }
    
    .map-container {
        height: 250px;
    }
    
    .hours-section {
        margin-top: var(--spacing-sm);
        padding: var(--spacing-xs) var(--spacing-sm);
    }
    
    .hours-section h4 {
        font-size: var(--font-size-sm);
        margin-bottom: var(--spacing-xs);
        flex-wrap: wrap;
    }
    
    .hours-section p {
        font-size: 0.75rem;
    }
    
    .open-badge {
        font-size: 0.65rem;
        padding: 2px var(--spacing-xs);
    }
}

@media (max-width: 360px) {
    .contact-page {
        padding: calc(60px + var(--spacing-xs)) var(--spacing-xs) var(--spacing-md);
    }
    
    .contact-header h1 {
        font-size: var(--font-size-lg);
    }
    
    .contact-header p {
        font-size: 0.8rem;
    }
    
    .contact-info-card,
    .contact-form-card,
    .map-card {
        padding: var(--spacing-sm);
    }
    
    .info-title,
    .form-title,
    .map-title {
        font-size: var(--font-size-sm);
    }
    
    .info-item {
        padding: var(--spacing-xs);
    }
    
    .info-icon {
        width: 28px;
        height: 28px;
        font-size: 0.8rem;
    }
    
    .info-details h3 {
        font-size: 0.6rem;
    }
    
    .info-details p {
        font-size: 0.8rem;
    }
    
    .form-label {
        font-size: 0.8rem;
    }
    
    .form-input,
    .form-textarea {
        padding: var(--spacing-xs);
        font-size: 0.8rem;
    }
    
    .submit-btn {
        font-size: 0.8rem;
        padding: var(--spacing-xs) var(--spacing-sm);
    }
    
    .map-container {
        height: 220px;
    }
    
    .hours-section h4 {
        font-size: 0.8rem;
    }
    
    .hours-section p {
        font-size: 0.7rem;
    }
    
    .open-badge {
        font-size: 0.6rem;
        padding: 1px 4px;
    }
}

/* Touch optimizations */
@media (hover: none) and (pointer: coarse) {
    .info-item {
        -webkit-tap-highlight-color: rgba(37, 99, 235, 0.05);
    }
    
    .info-item:hover {
        transform: none;
    }
    
    .form-input,
    .form-textarea {
        min-height: 44px;
    }
    
    .submit-btn {
        min-height: 48px;
    }
    
    .info-details a {
        min-height: 44px;
        display: inline-flex;
        align-items: center;
    }
}
</style>
@endpush

@section('content')
<div class="contact-page">
    <div class="contact-container">
        <div class="contact-header">
            <h1>Contactez-nous</h1>
            <p>Une question ? Besoin d'aide ? Notre équipe est là pour vous aider !</p>
            
            @if(session('success'))
                <div style="background: #10b981; color: white; padding: 1rem; border-radius: 8px; margin-top: 1rem; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div style="background: #ef4444; color: white; padding: 1rem; border-radius: 8px; margin-top: 1rem; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                </div>
            @endif
            
            @if($errors->any())
                <div style="background: #ef4444; color: white; padding: 1rem; border-radius: 8px; margin-top: 1rem;">
                    <i class="fas fa-exclamation-circle"></i>
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        
        <div class="contact-content">
            <div class="contact-info-card">
                <h2 class="info-title">
                    <i class="fas fa-info-circle"></i>
                    Informations de contact
                </h2>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="info-details">
                        <h3>Adresse</h3>
                        <p>Collège Lionel-Groulx<br>
                        100 Rue Duquet<br>
                        Sainte-Thérèse, QC J7E 3G6</p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="info-details">
                        <h3>Téléphone</h3>
                        <p><a href="tel:+14504303120">(450) 430-3120</a></p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="info-details">
                        <h3>Email</h3>
                        <p><a href="mailto:covoit.2025@gmail.com">covoit.2025@gmail.com</a></p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-globe"></i>
                    </div>
                    <div class="info-details">
                        <h3>Province</h3>
                        <p>Québec, Canada</p>
                    </div>
                </div>
                
                <div class="hours-section">
                    <h4>
                        <i class="fas fa-clock"></i>
                        Heures d'ouverture
                        <span class="open-badge">Ouvert</span>
                    </h4>
                    <p>Ferme à 23h00</p>
                </div>
            </div>
            
            <div class="contact-form-card">
                <h2 class="form-title">
                    <i class="fas fa-paper-plane"></i>
                    Envoyez-nous un message
                </h2>
                
                <form action="/contact" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="form-label">Nom complet</label>
                        <input type="text" id="name" name="name" class="form-input" placeholder="Jean Dupont" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">Adresse email</label>
                        <input type="email" id="email" name="email" class="form-input" placeholder="votre@email.com" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="subject" class="form-label">Sujet</label>
                        <input type="text" id="subject" name="subject" class="form-input" placeholder="Objet de votre message" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="message" class="form-label">Message</label>
                        <textarea id="message" name="message" class="form-textarea" placeholder="Écrivez votre message ici..." required></textarea>
                    </div>
                    
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-paper-plane"></i>
                        Envoyer le message
                    </button>
                </form>
            </div>
        </div>
        
        <div class="map-card">
            <h2 class="map-title">
                <i class="fas fa-map"></i>
                Notre localisation
            </h2>
            <div class="map-container">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5578.849507925318!2d-73.84558958791854!3d45.64229122141742!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4cc927d79caa0ebd%3A0xb2a64be2206a1929!2sColl%C3%A8ge%20Lionel-Groulx!5e0!3m2!1sen!2sca!4v1763419383456!5m2!1sen!2sca" 
                    width="100%" 
                    height="100%" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success') || session('error') || $errors->any())
            window.scrollTo({ top: 0, behavior: 'smooth' });
        @endif
    });
</script>
@endpush
