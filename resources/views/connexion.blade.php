@extends('layouts.app')

@section('title', 'Connexion - Covoit2025')

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
    max-width: 400px;
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

.form-label {
    display: block;
    margin-bottom: var(--spacing-xs);
    color: var(--gray-700);
    font-weight: 600;
    font-size: var(--font-size-xs);
}

.form-input {
    width: 100%;
    padding: var(--spacing-sm);
    border: 2px solid var(--gray-200);
    border-radius: var(--border-radius-lg);
    font-size: var(--font-size-sm);
    transition: var(--transition-normal);
    background: var(--white);
    color: var(--gray-800);
}

.form-input:focus {
    outline: none;
    border-color: var(--primary-blue);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.form-input::placeholder {
    color: var(--gray-400);
}

.checkbox-group {
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
    margin-bottom: var(--spacing-md);
}

.checkbox-group input[type="checkbox"] {
    width: 16px;
    height: 16px;
    accent-color: var(--primary-blue);
}

.checkbox-label {
    color: var(--gray-600);
    font-size: var(--font-size-xs);
    font-weight: 500;
    cursor: pointer;
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

.forgot-password {
    color: var(--primary-blue) !important;
    font-size: var(--font-size-xs) !important;
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
                <p>Bon retour ! Connectez-vous à votre compte</p>
            </div>
            
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                </div>
            @endif
            
            <form method="POST" action="/connexion" class="auth-form">
                @csrf
                <div class="form-group">
                    <label for="courriel" class="form-label">Adresse email</label>
                    <input type="email" id="courriel" name="courriel" class="form-input" placeholder="votre@email.com" required>
                </div>
                
                <div class="form-group">
                    <label for="mot_de_passe" class="form-label">Mot de passe</label>
                    <input type="password" id="mot_de_passe" name="mot_de_passe" class="form-input" placeholder="••••••••" required>
                </div>
                
                <div class="checkbox-group">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember" class="checkbox-label">Se souvenir de moi</label>
                </div>
                
                <button type="submit" class="auth-button">
                    <i class="fas fa-sign-in-alt"></i> Se connecter
                </button>
            </form>
            
            <div class="auth-footer">
                <p>Pas encore de compte ? <a href="/inscription">Créer un compte</a></p>
                <p><a href="/forgot-password" class="forgot-password">Mot de passe oublié ?</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
