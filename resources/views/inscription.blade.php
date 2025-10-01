@extends('layouts.app')

@section('title', 'Inscription - Covoit2025')

@push('styles')
<style>

.auth-page {
    min-height: 100vh;
    background: var(--gradient-hero);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: calc(70px + var(--spacing-sm)) var(--spacing-md) var(--spacing-sm);
    position: relative;
    overflow: hidden;
}

.auth-page::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.auth-container {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 450px;
}

.auth-card {
    background: var(--white);
    border-radius: var(--border-radius-xl);
    box-shadow: var(--shadow-xl);
    padding: var(--spacing-xl);
    border: 1px solid var(--gray-200);
    backdrop-filter: blur(10px);
    position: relative;
    overflow: hidden;
}


.auth-header {
    text-align: center;
    margin-bottom: var(--spacing-lg);
}

.auth-header h1 {
    font-size: var(--font-size-2xl);
    font-weight: 800;
    color: var(--primary-blue);
    margin-bottom: var(--spacing-xs);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--spacing-xs);
}

.auth-header h1::before {
    content: '🚗';
    font-size: var(--font-size-xl);
}

.auth-header p {
    color: var(--gray-600);
    font-size: var(--font-size-sm);
    font-weight: 500;
}

.auth-form {
    margin-bottom: var(--spacing-lg);
}

.form-group {
    margin-bottom: var(--spacing-md);
}

.form-row {
    display: flex;
    gap: var(--spacing-sm);
}

.form-group.half-width {
    flex: 1;
}

.form-label {
    display: block;
    margin-bottom: var(--spacing-xs);
    color: var(--gray-700);
    font-weight: 600;
    font-size: var(--font-size-xs);
}

.form-input,
.form-select {
    width: 100%;
    padding: var(--spacing-sm);
    border: 2px solid var(--gray-200);
    border-radius: var(--border-radius-lg);
    font-size: var(--font-size-sm);
    transition: var(--transition-normal);
    background: var(--white);
    color: var(--gray-800);
}

.form-input:focus,
.form-select:focus {
    outline: none;
    border-color: var(--primary-blue);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.form-input::placeholder {
    color: var(--gray-400);
}

.form-select {
    cursor: pointer;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right var(--spacing-xs) center;
    background-repeat: no-repeat;
    background-size: 1.2em 1.2em;
    padding-right: var(--spacing-xl);
}

.auth-button {
    width: 100%;
    background: var(--gradient-primary);
    color: var(--white);
    padding: var(--spacing-sm);
    border: none;
    border-radius: var(--border-radius-lg);
    font-size: var(--font-size-sm);
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition-normal);
    box-shadow: var(--shadow-blue);
    margin-bottom: var(--spacing-md);
}

.auth-button:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-xl);
}

.divider {
    text-align: center;
    margin: var(--spacing-md) 0;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.divider::before {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--gray-200);
    margin-right: var(--spacing-sm);
}

.divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--gray-200);
    margin-left: var(--spacing-sm);
}

.divider span {
    color: var(--gray-500);
    font-size: var(--font-size-xs);
    font-weight: 500;
    white-space: nowrap;
}

.social-buttons {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-sm);
    margin-bottom: var(--spacing-md);
}

.social-button {
    width: 100%;
    padding: var(--spacing-sm);
    border: 2px solid var(--gray-200);
    border-radius: var(--border-radius-lg);
    font-size: var(--font-size-xs);
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition-normal);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--spacing-xs);
    background: var(--white);
    color: var(--gray-700);
}

.social-button:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    border-color: var(--primary-blue);
}

.google-button:hover {
    background: #4285f4;
    color: var(--white);
    border-color: #4285f4;
}

.facebook-button:hover {
    background: #1877f2;
    color: var(--white);
    border-color: #1877f2;
}

.social-icon {
    font-size: var(--font-size-sm);
}

.auth-footer {
    text-align: center;
}

.auth-footer p {
    color: var(--gray-600);
    margin: var(--spacing-xs) 0;
    font-size: var(--font-size-xs);
}

.auth-footer a {
    color: var(--primary-blue);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition-normal);
}

.auth-footer a:hover {
    color: var(--primary-blue-dark);
    text-decoration: underline;
}

.terms {
    color: var(--gray-500) !important;
    font-size: var(--font-size-xs) !important;
    line-height: 1.4;
}

.terms a {
    color: var(--primary-blue) !important;
}

@media (max-width: 768px) {
    .auth-page {
        padding: calc(70px + var(--spacing-xs)) var(--spacing-sm) var(--spacing-xs);
    }
    
    .auth-card {
        padding: var(--spacing-lg);
    }
    
    .auth-header h1 {
        font-size: var(--font-size-xl);
    }
    
    .form-row {
        flex-direction: column;
        gap: 0;
    }
}

@media (max-width: 480px) {
    .auth-card {
        padding: var(--spacing-md);
    }
    
    .auth-header h1 {
        font-size: var(--font-size-lg);
    }
}
</style>
@endpush

@section('content')
<div class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1>Covoit2025</h1>
                <p>Créez votre compte et commencez à voyager</p>
            </div>
            
            @if(session('error'))
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                </div>
            @endif
            
            <form method="POST" action="/inscription" class="auth-form">
                @csrf
                <div class="form-group">
                    <label for="nom_complet" class="form-label">Nom complet</label>
                    <input type="text" id="nom_complet" name="nom_complet" class="form-input" placeholder="Jean Dupont" required>
                </div>
                
                <div class="form-group">
                    <label for="courriel" class="form-label">Adresse email</label>
                    <input type="email" id="courriel" name="courriel" class="form-input" placeholder="votre@email.com" required>
                </div>
                
                <div class="form-group">
                    <label for="role" class="form-label">Rôle</label>
                    <select id="role" name="role" class="form-select" required>
                        <option value="">Sélectionnez votre rôle</option>
                        <option value="Conducteur">Conducteur</option>
                        <option value="Passager">Passager</option>
                    </select>
                </div>
                
                <div class="form-row">
                    <div class="form-group half-width">
                        <label for="mot_de_passe" class="form-label">Mot de passe</label>
                        <input type="password" id="mot_de_passe" name="mot_de_passe" class="form-input" placeholder="6+ caractères" required>
                    </div>
                    
                    <div class="form-group half-width">
                        <label for="confirmer_mot_de_passe" class="form-label">Confirmer</label>
                        <input type="password" id="confirmer_mot_de_passe" name="confirmer_mot_de_passe" class="form-input" placeholder="Répéter" required>
                    </div>
                </div>
                
                <button type="submit" class="auth-button">
                    <i class="fas fa-user-plus"></i> Créer mon compte
                </button>
            </form>
            
            <div class="divider">
                <span>ou s'inscrire avec</span>
            </div>
            
            <div class="social-buttons">
                <button class="social-button google-button">
                    <span class="social-icon">🌐</span>
                    Google
                </button>
                <button class="social-button facebook-button">
                    <span class="social-icon">📘</span>
                    Facebook
                </button>
            </div>
            
            <div class="auth-footer">
                <p>Déjà membre ? <a href="/connexion">Se connecter</a></p>
                <p class="terms">En vous inscrivant, vous acceptez nos <a href="#">conditions d'utilisation</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
