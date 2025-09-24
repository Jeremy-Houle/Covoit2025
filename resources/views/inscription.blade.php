@extends('layouts.app')

@section('title', 'Inscription')

@push('styles')
    @vite(['resources/css/auth.css'])
@endpush

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1>CoVoit</h1>
            <p>Créez votre compte et commencez à voyager</p>
        </div>
        
        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif
        
        <form method="POST" action="/inscription" class="auth-form">
            @csrf
            <div class="form-group">
                <label for="nom_complet">Nom complet</label>
                <input type="text" id="nom_complet" name="nom_complet" placeholder="Jean Dupont" required>
            </div>
            
            <div class="form-group">
                <label for="courriel">Adresse email</label>
                <input type="email" id="courriel" name="courriel" placeholder="votre@email.com" required>
            </div>
            
            <div class="form-group">
                <label for="role">Rôle</label>
                <select id="role" name="role" required>
                    <option value="">Sélectionnez votre rôle</option>
                    <option value="Conducteur">Conducteur</option>
                    <option value="Passager">Passager</option>
                </select>
            </div>
            
            <div class="form-row">
                <div class="form-group half-width">
                    <label for="mot_de_passe">Mot de passe</label>
                    <input type="password" id="mot_de_passe" name="mot_de_passe" placeholder="6+ caractères" required>
                </div>
                
                <div class="form-group half-width">
                    <label for="confirmer_mot_de_passe">Confirmer</label>
                    <input type="password" id="confirmer_mot_de_passe" name="confirmer_mot_de_passe" placeholder="Répéter" required>
                </div>
            </div>
            
            <button type="submit" class="auth-button">Créer mon compte</button>
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
@endsection
