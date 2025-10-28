@extends('layouts.app')

@section('title', 'Modifier mon profil')

@push('styles')
<style>

.edit-profil-page {
    background: var(--gray-50);
    min-height: 100vh;
    padding-top: 70px;
}

.edit-profil-container {
    max-width: 800px;
    margin: 0 auto;
    padding: var(--spacing-xl) var(--spacing-lg);
}

.edit-profil-header {
    text-align: center;
    margin-bottom: var(--spacing-lg);
    background: var(--white);
    padding: var(--spacing-lg);
    border-radius: var(--border-radius-xl);
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--gray-200);
}

.edit-profil-title {
    font-size: var(--font-size-2xl);
    font-weight: 700;
    color: var(--primary-blue);
    margin-bottom: var(--spacing-xs);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--spacing-sm);
}

.edit-profil-title i {
    color: var(--primary-blue);
}

.edit-profil-subtitle {
    color: var(--gray-600);
    font-size: var(--font-size-base);
    margin: 0;
}

.edit-profil-form {
    background: var(--white);
    padding: var(--spacing-xl);
    border-radius: var(--border-radius-xl);
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--gray-200);
}

.form-group {
    margin-bottom: var(--spacing-md);
}

.form-label {
    display: block;
    font-weight: 600;
    color: var(--gray-800);
    margin-bottom: var(--spacing-xs);
    font-size: var(--font-size-sm);
}

.form-control {
    width: 100%;
    padding: var(--spacing-sm);
    border: 2px solid var(--gray-200);
    border-radius: var(--border-radius-lg);
    font-size: var(--font-size-sm);
    transition: var(--transition-normal);
    background: var(--white);
}

.form-control:focus {
    outline: none;
    border-color: var(--primary-blue);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.form-control::placeholder {
    color: var(--gray-400);
}

.error-message {
    color: var(--error-red);
    font-size: var(--font-size-xs);
    margin-top: var(--spacing-xs);
    display: block;
}

.success-message {
    background: rgba(16, 185, 129, 0.1);
    border: 2px solid var(--success-green);
    color: var(--success-green);
    padding: var(--spacing-sm);
    border-radius: var(--border-radius-lg);
    margin-bottom: var(--spacing-md);
    font-weight: 500;
    font-size: var(--font-size-sm);
}

.form-actions {
    display: flex;
    gap: var(--spacing-sm);
    justify-content: flex-end;
    margin-top: var(--spacing-lg);
    padding-top: var(--spacing-md);
    border-top: 1px solid var(--gray-200);
}

.btn {
    padding: var(--spacing-sm) var(--spacing-lg);
    border-radius: var(--border-radius-lg);
    font-weight: 600;
    font-size: var(--font-size-sm);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-xs);
    transition: var(--transition-normal);
    border: none;
    cursor: pointer;
}

.btn-primary {
    background: var(--gradient-primary);
    color: var(--white);
    box-shadow: var(--shadow-blue);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-xl);
    color: var(--white);
    text-decoration: none;
}

.btn-secondary {
    background: var(--white);
    color: var(--gray-600);
    border: 2px solid var(--gray-200);
}

.btn-secondary:hover {
    background: var(--gray-50);
    border-color: var(--gray-300);
    transform: translateY(-1px);
    color: var(--gray-600);
    text-decoration: none;
}

@media (max-width: 768px) {
    .edit-profil-container {
        padding: var(--spacing-lg) var(--spacing-md);
    }
    
    .edit-profil-form {
        padding: var(--spacing-lg);
    }
    
    .edit-profil-header {
        padding: var(--spacing-md);
        margin-bottom: var(--spacing-md);
    }
    
    .edit-profil-title {
        font-size: var(--font-size-xl);
    }
    
    .form-actions {
        flex-direction: column;
        gap: var(--spacing-xs);
    }
    
    .btn {
        width: 100%;
        justify-content: center;
        padding: var(--spacing-sm) var(--spacing-md);
    }
}
</style>
@endpush

@section('content')
<div class="edit-profil-page">
    <div class="edit-profil-container">
        <div class="edit-profil-header">
            <h1 class="edit-profil-title">
                <i class="fas fa-user-edit"></i> Modifier mon profil
            </h1>
            <p class="edit-profil-subtitle">Mettez à jour vos informations personnelles</p>
        </div>

        <div class="edit-profil-form">
            @if(session('success'))
                <div class="success-message">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('profil.update') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="Prenom" class="form-label">Prénom</label>
                    <input type="text" class="form-control" id="Prenom" name="Prenom" value="{{ old('Prenom', $user->Prenom) }}" required>
                    @error('Prenom') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="Nom" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="Nom" name="Nom" value="{{ old('Nom', $user->Nom) }}" required>
                    @error('Nom') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="Courriel" class="form-label">Email</label>
                    <input type="email" class="form-control" id="Courriel" name="Courriel" value="{{ old('Courriel', $user->Courriel) }}" required>
                    @error('Courriel') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="MotDePasse" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="MotDePasse" name="MotDePasse" placeholder="Laisser vide pour ne pas changer">
                    @error('MotDePasse') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="MotDePasse_confirmation" class="form-label">Confirmation mot de passe</label>
                    <input type="password" class="form-control" id="MotDePasse_confirmation" name="MotDePasse_confirmation" placeholder="Confirmer le nouveau mot de passe">
                </div>

                <div class="form-group" style="margin-top: var(--spacing-lg); padding-top: var(--spacing-lg); border-top: 2px solid var(--gray-200);">
                    <label for="Role" class="form-label">
                        <i class="fas fa-user-tag" style="color: var(--primary-blue);"></i> Type de compte
                    </label>
                    <select name="Role" id="Role" class="form-control" style="cursor: pointer;">
                        <option value="Passager" {{ $user->Role === 'Passager' ? 'selected' : '' }}>👤 Passager</option>
                        <option value="Conducteur" {{ $user->Role === 'Conducteur' ? 'selected' : '' }}>🚗 Conducteur</option>
                    </select>
                    <small style="display: block; margin-top: 8px; color: var(--gray-600); font-size: 0.85rem;">
                        <i class="fas fa-info-circle"></i> Changez votre rôle selon vos besoins. Les conducteurs peuvent publier des trajets.
                    </small>
                </div>

                <div class="form-actions">
                    <a href="/profil" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Sauvegarder
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
