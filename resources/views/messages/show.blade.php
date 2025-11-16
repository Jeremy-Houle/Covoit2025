@extends('layouts.app')

@section('title', 'Conversation - Messages')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/messages.css') }}?v={{ time() }}">
@endpush

@section('content')
<div class="chat-page">
    <div class="chat-container">
        <a href="{{ route('message.index') }}" class="chat-back-button">
            <i class="fas fa-arrow-left"></i> Retour aux messages
        </a>

        <div class="chat-card fade-in">
            <div class="chat-header">
                <div class="chat-avatar">
                    {{ strtoupper(substr($otherName, 0, 1)) }}
                </div>
                <div class="chat-user-info">
                    <h2 class="chat-user-name">{{ $otherName ?? ('Utilisateur #' . $otherId) }}</h2>
                </div>
            </div>

            <div class="chat-body" id="chatBody">
                @if(isset($messages) && $messages->isNotEmpty())
                    <div class="chat-messages">
                        @foreach($messages as $msg)
                            @php
                                $isSent = $msg->IdExpediteur == $userId;
                                $senderName = $isSent ? $currentUserName : $otherName;
                                $senderInitial = strtoupper(substr($senderName, 0, 1));
                            @endphp
                            
                            <div class="chat-message {{ $isSent ? 'sent' : 'received' }}">
                                <div class="message-avatar">
                                    {{ $senderInitial }}
                                </div>
                                <div class="message-content">
                                    <div class="message-bubble">
                                        <p class="message-text">{{ $msg->LeMessage ?? $msg->Message ?? '' }}</p>
                                    </div>
                                    <div class="message-info">
                                        <span class="message-sender">{{ $senderName }}</span>
                                        <span class="message-time">
                                            {{ \Carbon\Carbon::parse($msg->DateEnvoi, 'UTC')->setTimezone(config('app.timezone', 'America/Toronto'))->locale('fr')->translatedFormat('H:i') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-chat">
                        <div class="empty-chat-icon">
                            <i class="far fa-comments"></i>
                        </div>
                        <p>Aucun message dans cette conversation.</p>
                        <p>Envoyez un message pour commencer !</p>
                    </div>
                @endif
            </div>

            <div class="chat-footer">
                <form action="{{ route('message.send', $otherId) }}" method="POST" id="messageForm">
                    @csrf
                    <div class="chat-input-group">
                        <input 
                            type="text" 
                            name="message" 
                            class="chat-input" 
                            placeholder="Écrivez votre message..." 
                            required
                            autocomplete="off"
                            id="messageInput">
                        <button type="submit" class="chat-send-button">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatBody = document.getElementById('chatBody');
    const messageForm = document.getElementById('messageForm');
    const messageInput = document.getElementById('messageInput');
    
    function scrollToBottom() {
        chatBody.scrollTop = chatBody.scrollHeight;
    }
    
    scrollToBottom();
    
    messageInput.focus();
    
    messageInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            messageForm.submit();
        }
    });
    
    messageForm.addEventListener('submit', function() {
        const sendButton = this.querySelector('.chat-send-button');
        sendButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    });
    
    async function updateBadge() {
        try {
            const response = await fetch('/api/messages/unread-count', {
                method: 'GET',
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                const badge = document.getElementById('messageBadge');
                if (badge) {
                    if (data.count > 0) {
                        badge.textContent = data.count > 99 ? '99+' : data.count;
                        badge.style.display = 'flex';
                    } else {
                        badge.style.display = 'none';
                    }
                }
            }
        } catch (error) {
            console.error('Erreur lors de la mise à jour du badge:', error);
        }
    }
    
    setTimeout(updateBadge, 500);
});
</script>
@endsection