@extends('layouts.app')

@section('title', 'Messages - Covoit2025')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/messages.css') }}?v={{ time() }}">
@endpush

@section('content')
<div class="messages-page">
    <div class="messages-container">
        <div class="messages-header fade-in">
            <h1 class="messages-title">
                <i class="fas fa-comments"></i>
                Mes Messages
            </h1>
            <p class="messages-subtitle">Gérez vos conversations de covoiturage</p>
        </div>

        @if($messages->isEmpty())
            <div class="empty-messages fade-in">
                <div class="empty-messages-icon">
                    <i class="far fa-comment-dots"></i>
                </div>
                <h3>Aucun message</h3>
                <p>Vous n'avez pas encore de conversations.</p>
                <p>Commencez à discuter avec d'autres utilisateurs depuis vos réservations !</p>
                <a href="/mes-reservations" class="btn btn-primary" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); border: none; padding: 12px 30px; border-radius: 25px; margin-top: 15px;">
                    <i class="fas fa-calendar-alt"></i> Voir mes réservations
                </a>
            </div>
        @else
            <div class="conversations-list fade-in">
                @foreach($threads as $otherId => $last)
                    <a href="{{ route('message.show', $otherId) }}" class="conversation-item">
                        <div class="conversation-avatar">
                            {{ strtoupper(substr($last->otherName ?? 'U', 0, 1)) }}
                        </div>
                        <div class="conversation-content">
                            <div class="conversation-header">
                                <span class="conversation-name">{{ $last->otherName ?? 'Utilisateur' }}</span>
                                <span class="conversation-time">{{ $last->sentAt }}</span>
                            </div>
                            <div class="conversation-preview">
                                {{ \Illuminate\Support\Str::limit($last->LeMessage ?? $last->Message ?? '', 60) }}
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection