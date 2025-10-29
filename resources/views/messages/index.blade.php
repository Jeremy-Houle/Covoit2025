@extends('layouts.app')

@section('title', 'Messages - Covoit2025')

@section('content')
<div class="container" style="padding-top:100px;">
    <h1>Messages</h1>

    @if($messages->isEmpty())
        <p>Aucun message.</p>
    @else
        @php
            $userId = session('utilisateur_id');
            $threads = [];
            
            foreach ($messages as $m) {
                $other = ($m->IdExpediteur == $userId) ? $m->IdDestinataire : $m->IdExpediteur;
                // conserver le premier rencontré (messages triés par DateEnvoi desc)
                if (!isset($threads[$other])) {
                    $threads[$other] = $m;
                }
            }
        @endphp

        <div class="list-group">
            @foreach($threads as $otherId => $last)
                <a href="{{ route('message.show', $otherId) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    <div style="min-width:0;">
                        <strong>{{ $last->otherName ?? 'Utilisateur' }}</strong>
                        <div style="font-size:.95rem;color:#555;white-space:nowrap;text-overflow:ellipsis;overflow:hidden;">
                            {{ \Illuminate\Support\Str::limit($last->LeMessage ?? $last->Message ?? '', 80) }}
                        </div>
                    </div>
                    <small class="text-muted">{{ $last->sentAt }}</small>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection