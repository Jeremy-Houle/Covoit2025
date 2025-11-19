@extends('layouts.app')

@section('title', 'Connexion - Covoit2025')

@push('scripts')
    @vite(['resources/js/animations/connexion-animations.js'])
@endpush

@push('styles')
<style>

.auth-page {
    min-height: 100vh;
    background: linear-gradient(135deg, #dbeafe 0%, #93c5fd 20%, #60a5fa 40%, #3b82f6 60%, #2563eb 80%, #1e40af 100%);
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
    background: radial-gradient(circle at 20% 50%, rgba(251, 191, 36, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(59, 130, 246, 0.2) 0%, transparent 50%),
                url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    animation: floatBubbles 20s ease-in-out infinite;
}

@keyframes floatBubbles {
    0%, 100% { 
        transform: translate(0, 0) scale(1); 
    }
    50% { 
        transform: translate(20px, -20px) scale(1.05); 
    }
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
        padding: calc(70px + var(--spacing-sm)) var(--spacing-sm) var(--spacing-sm);
    }
    
    .auth-container {
        max-width: 100%;
        padding: 0 var(--spacing-sm);
    }
    
    .auth-card {
        padding: var(--spacing-lg);
    }
    
    .auth-header h1 {
        font-size: var(--font-size-xl);
    }
    
    .auth-header h1::before {
        font-size: var(--font-size-lg);
    }
    
    .auth-header p {
        font-size: 0.8rem;
    }
    
    .form-input {
        font-size: var(--font-size-sm);
        padding: var(--spacing-sm);
    }
    
    .auth-button {
        padding: var(--spacing-sm) var(--spacing-md);
    }
}

@media (max-width: 480px) {
    .auth-page {
        padding: calc(60px + var(--spacing-xs)) var(--spacing-xs) var(--spacing-xs);
    }
    
    .auth-container {
        padding: 0 var(--spacing-xs);
    }
    
    .auth-card {
        padding: var(--spacing-md);
    }
    
    .auth-header {
        margin-bottom: var(--spacing-md);
    }
    
    .auth-header h1 {
        font-size: var(--font-size-lg);
        gap: 6px;
    }
    
    .auth-header h1::before {
        font-size: var(--font-size-base);
    }
    
    .auth-header p {
        font-size: 0.75rem;
        line-height: 1.4;
    }
    
    .form-group {
        margin-bottom: var(--spacing-sm);
    }
    
    .form-label {
        font-size: 0.7rem;
        margin-bottom: 4px;
    }
    
    .form-input {
        padding: var(--spacing-xs) var(--spacing-sm);
        font-size: var(--font-size-sm);
    }
    
    .checkbox-group {
        margin-bottom: var(--spacing-sm);
    }
    
    .checkbox-group input[type="checkbox"] {
        width: 14px;
        height: 14px;
    }
    
    .checkbox-label {
        font-size: 0.7rem;
    }
    
    .auth-button {
        padding: var(--spacing-sm);
        font-size: var(--font-size-sm);
        margin-bottom: var(--spacing-sm);
    }
    
    .auth-form {
        margin-bottom: var(--spacing-md);
    }
    
    .divider {
        margin: var(--spacing-sm) 0;
    }
    
    .divider span {
        font-size: 0.7rem;
    }
    
    .social-buttons {
        gap: var(--spacing-xs);
        margin-bottom: var(--spacing-sm);
    }
    
    .social-button {
        padding: var(--spacing-xs) var(--spacing-sm);
        font-size: 0.7rem;
    }
    
    .social-icon {
        font-size: var(--font-size-xs);
    }
    
    .auth-footer p {
        font-size: 0.7rem;
        margin: 6px 0;
    }
    
    .forgot-password {
        font-size: 0.7rem !important;
    }
}

@media (max-width: 360px) {
    .auth-page {
        padding: calc(60px + 4px) 4px 4px;
    }
    
    .auth-container {
        padding: 0 4px;
    }
    
    .auth-card {
        padding: var(--spacing-sm);
    }
    
    .auth-header h1 {
        font-size: var(--font-size-base);
        gap: 4px;
    }
    
    .auth-header h1::before {
        font-size: var(--font-size-sm);
    }
    
    .auth-header p {
        font-size: 0.7rem;
    }
    
    .form-label {
        font-size: 0.65rem;
    }
    
    .form-input {
        padding: 6px var(--spacing-xs);
        font-size: 0.8rem;
    }
    
    .checkbox-label {
        font-size: 0.65rem;
    }
    
    .auth-button {
        font-size: 0.8rem;
        padding: var(--spacing-xs) var(--spacing-sm);
    }
    
    .social-button {
        font-size: 0.65rem;
        padding: 6px var(--spacing-xs);
    }
    
    .auth-footer p,
    .forgot-password {
        font-size: 0.65rem !important;
    }
}

/* Touch optimizations */
@media (hover: none) and (pointer: coarse) {
    .form-input {
        min-height: 44px;
    }
    
    .checkbox-group {
        min-height: 44px;
    }
    
    .checkbox-group input[type="checkbox"] {
        min-width: 20px;
        min-height: 20px;
    }
    
    .auth-button {
        min-height: 48px;
    }
    
    .social-button {
        min-height: 44px;
    }
    
    .auth-footer a {
        min-height: 44px;
        display: inline-flex;
        align-items: center;
        padding: var(--spacing-xs) 0;
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
