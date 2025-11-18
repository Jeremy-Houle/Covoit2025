@extends('layouts.app')

@section('content')
<div class="comment-page">
    <div class="comment-container" style="margin-top: 5%;">
        <h1>Ajouter un commentaire pour le trajet</h1>
        <p class="route-info">{{ $trajet->Depart }} → {{ $trajet->Destination }} le {{ \Carbon\Carbon::parse($trajet->DateTrajet)->format('d/m/Y') }}</p>

        @if(session('success'))
            <div class="alert success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert error">{{ session('error') }}</div>
        @endif

        <form action="{{ route('comments.store') }}" method="POST" class="comment-form">
            @csrf
            <input type="hidden" name="idTrajet" value="{{ $trajet->IdTrajet }}">

            <div class="form-group">
                <label for="commentaire">Votre commentaire :</label>
                <textarea name="commentaire" id="commentaire" rows="5" placeholder="Écrivez votre commentaire ici..." required>{{ old('commentaire') }}</textarea>
                @error('commentaire')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-submit">Envoyer le commentaire</button>
        </form>
    </div>
</div>
@endsection