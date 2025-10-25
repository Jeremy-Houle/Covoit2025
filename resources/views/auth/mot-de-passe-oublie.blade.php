@extends('layouts.app')

@section('title', 'Mot de passe oublié - Covoit2025')

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
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.alert {
    padding: var(--spacing-sm);
    border-radius: var(--border-radius-lg);
    margin-bottom: var(--spacing-md);
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
    font-size: var(--font-size-sm);
    font-weight: 500;
}

.alert-success {
    background-color: #d1fae5;
    color: #065f46;
    border: 1px solid #6ee7b7;
}

.alert-error {
    background-color: #fee2e2;
    color: #991b1b;
    border: 1px solid #fca5a5;
}

.info-box {
    background-color: #dbeafe;
    border-left: 4px solid var(--primary-blue);
    padding: var(--spacing-md);
    border-radius: var(--border-radius-lg);
    margin-bottom: var(--spacing-md);
    font-size: var(--font-size-sm);
    color: var(--gray-700);
}

.auth-button {
    width: 100%;
    padding: var(--spacing-sm) var(--spacing-md);
    background: var(--gradient-primary);
    color: var(--white);
    border: none;
    border-radius: var(--border-radius-lg);
    font-size: var(--font-size-sm);
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition-normal);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--spacing-xs);
}

.auth-button:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.auth-button:active {
    transform: translateY(0);
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
                <p>Réinitialisation de votre mot de passe</p>
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

            <div class="info-box">
                Entrez votre adresse email et nous vous enverrons un lien pour réinitialiser votre mot de passe.
            </div>
            
            <form method="POST" action="{{ url('/forgot-password') }}" class="auth-form">
                @csrf
                <div class="form-group">
                    <label for="email" class="form-label">Adresse email</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="votre@email.com" required value="{{ old('email') }}">
                    @error('email')
                        <span style="color: #dc3545; font-size: 13px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
                
                <button type="submit" class="auth-button">
                    <i class="fas fa-paper-plane"></i> Envoyer le lien de réinitialisation
                </button>
            </form>
            
            <div class="auth-footer">
                <p><a href="/connexion">Retour à la connexion</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
