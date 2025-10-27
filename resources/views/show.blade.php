@extends('layouts.app')

@section('title', 'Conversation')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/reservations.css') }}?v={{ time() }}">
@endpush

@section('content')
<div class="container" style="padding-top:100px;">
    <div class="card">
        <div class="row" style="align-items:center; justify-content:space-between;">
            <div class="driver">
                <i class="fas fa-user-circle"></i>
                <div style="margin-left:10px;">
                    <div class="name">
                        Conversation avec
                        @if(isset($destinataire))
                            {{ $destinataire->Prenom }} {{ $destinataire->Nom }}
                        @else
                            Utilisateur #{{ $destinataireId }}
                        @endif
                    </div>
                    <div class="small text-muted">ID: {{ $destinataireId }}</div>
                </div>
            </div>

            <div>
                <a href="{{ url('/mes-reservations') }}" class="btn btn-outline-primary btn-sm">Retour</a>
            </div>
        </div>

        <hr>

        <div class="conversation" style="max-height:480px; overflow:auto; padding:8px 4px;">
            @forelse($messages as $msg)
                <div class="msg {{ $msg->IdExpediteur == session('utilisateur_id') ? 'me' : 'them' }}" style="margin-bottom:10px;">
                    <div class="small text-muted" style="margin-bottom:6px;">
                        @if($msg->IdExpediteur == session('utilisateur_id'))
                            Vous
                        @else
                            {{ trim(($msg->ExpediteurPrenom ?? '') . ' ' . ($msg->ExpediteurNom ?? '')) ?: 'Utilisateur' }}
                        @endif
                        • {{ \Carbon\Carbon::parse($msg->DateEnvoi)->format('d M Y H:i') }}
                    </div>
                    <div style="white-space:pre-wrap;">{{ $msg->LeMessage }}</div>
                </div>
            @empty
                <p class="text-muted">Aucun message pour l'instant. Envoyez le premier message au conducteur.</p>
            @endforelse
        </div>

        <form action="{{ route('messages.store') }}" method="POST" style="margin-top:12px;">
            @csrf
            <input type="hidden" name="IdDestinataire" value="{{ $destinataireId }}">
            <textarea name="LeMessage" class="form-control" rows="3" placeholder="Votre message..." required></textarea>
            <div style="margin-top:8px; display:flex; justify-content:flex-end; gap:8px;">
                <a href="{{ url('/rechercher') }}" class="btn btn-outline-primary btn-sm">Retour aux trajets</a>
                <button type="submit" class="btn btn-primary">Envoyer</button>
            </div>
        </form>
    </div>
</div>
@endsection