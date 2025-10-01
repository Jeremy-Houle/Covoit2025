@extends('layouts.app')

@section('title', 'Connexion')

@push('styles')
    @vite(['resources/css/auth.css'])
@endpush

@section('content')
<div class="auth-page connexion-page">
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1>CoVoit</h1>
            <p>Bon retour ! Connectez-vous à votre compte</p>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif
        
        <form method="POST" action="/connexion" class="auth-form">
            @csrf
            <div class="form-group">
                <label for="courriel">Adresse email</label>
                <input type="email" id="courriel" name="courriel" placeholder="votre@email.com" required>
            </div>
            
            <div class="form-group">
                <label for="mot_de_passe">Mot de passe</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" placeholder="••••••••" required>
            </div>
            
            <div class="checkbox-group">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember" class="checkbox-label">Se souvenir de moi</label>
            </div>
            
            <button type="submit" class="auth-button">Se connecter</button>
        </form>
        
        <div class="divider">
            <span>ou continuer avec</span>
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
            <p>Pas encore de compte ? <a href="/inscription">Créer un compte</a></p>
            <p><a href="#" class="forgot-password">Mot de passe oublié ?</a></p>
        </div>
    </div>
</div>
</div>
@endsection
