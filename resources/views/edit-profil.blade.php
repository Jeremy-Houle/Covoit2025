@extends('layouts.app')

@section('title', 'Modifier mon profil')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .edit-profil-page {
            background: #ffffff;
            min-height: 100vh; 
            margin: 0;
            padding: 0;
            padding-top: 60px;
            padding-bottom: 0; 
            width: 100%;
        }
        
        .edit-profil-title-section {
            text-align: center;
            padding: 30px 20px;
            margin: 0;
            width: 100%;
            background: #ffffff;
        }
        
        .edit-profil-title-section h1 {
            font-size: 28px;
            font-weight: 600;
            color: black;
            margin-bottom: 4px;
            display: block;
        }
        
        .edit-profil-title-section p {
            color: #686868;
            font-size: 14px;
            margin: 0;
            margin-bottom: 20px;
        }
        
        .edit-profil-title-section i {
            color: #0c7d9a;
            margin-right: 10px;
        }
    </style>
@endpush

@section('content')
<div class="edit-profil-page">
    <div class="edit-profil-title-section">
        <h1><i class="fas fa-user-edit"></i> Modifier mon profil</h1>
        <p>Mettez à jour vos informations personnelles</p>
    </div>

    <div class="container mt-5">
        <h2>Modifier mon profil</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('profil.update') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="Prenom" class="form-label">Prénom</label>
                <input type="text" class="form-control" id="Prenom" name="Prenom" value="{{ old('Prenom', $user->Prenom) }}" required>
                @error('Prenom') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label for="Nom" class="form-label">Nom</label>
                <input type="text" class="form-control" id="Nom" name="Nom" value="{{ old('Nom', $user->Nom) }}" required>
                @error('Nom') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label for="Courriel" class="form-label">Email</label>
                <input type="email" class="form-control" id="Courriel" name="Courriel" value="{{ old('Courriel', $user->Courriel) }}" required>
                @error('Courriel') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label for="MotDePasse" class="form-label">Mot de passe (laisser vide pour ne pas changer)</label>
                <input type="password" class="form-control" id="MotDePasse" name="MotDePasse">
                @error('MotDePasse') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label for="MotDePasse_confirmation" class="form-label">Confirmation mot de passe</label>
                <input type="password" class="form-control" id="MotDePasse_confirmation" name="MotDePasse_confirmation">
            </div>

            <button type="submit" class="btn btn-primary">Sauvegarder</button>
            <a href="/profil" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>
@endsection
