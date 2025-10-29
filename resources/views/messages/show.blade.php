@extends('layouts.app')

@section('title', 'Conversation - Message')

@section('content')
    <div class="container" style="padding-top:100px;">
        <a href="{{ route('message.index') }}" class="btn btn-sm btn-outline-secondary mb-3">← Retour</a>

        <div class="card">
            <div class="card-header">
                Conversation avec {{ $otherName ?? ('Utilisateur #' . $otherId) }}
            </div>

            <div class="card-body" style="max-height:60vh;overflow:auto;">
                @if(isset($messages) && $messages->isNotEmpty())
                    @foreach($messages as $msg)
                        <div style="margin-bottom:12px;">
                            <div style="font-size:.9rem;color:#666;">
                                <strong>{{ ($msg->IdExpediteur == session('utilisateur_id')) ? 'Vous' : ($msg->ExpediteurNom ?? 'Utilisateur') }}</strong>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($msg->DateEnvoi)->setTimezone(config('app.timezone'))->locale('fr')->translatedFormat('j F Y H:i') }}
                                </small>
                            </div>
                            <div style="margin-top:6px;white-space:pre-wrap;">{{ $msg->LeMessage ?? $msg->Message ?? '' }}</div>
                        </div>
                    @endforeach
                @else
                    <p>Aucun message dans cette conversation.</p>
                @endif
            </div>

            <div class="card-footer">
                <form action="{{ route('message.send', $otherId) }}" method="POST">
                    @csrf
                    <div class="input-group">
                        <input name="message" class="form-control" placeholder="Écrire un message..." required>
                        <button class="btn btn-primary" type="submit">Envoyer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection