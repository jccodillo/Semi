@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header pb-0 px-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <a href="{{ route('messages.index') }}" class="text-dark me-2">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    Chat with {{ $user->name }}
                </h6>
            </div>
        </div>
        <div class="card-body p-3">
            <div class="chat-messages p-3" id="chat-messages" style="height: 400px; overflow-y: auto;">
                @foreach($messages as $message)
                    <div class="message {{ $message->sender_id == Auth::id() ? 'text-end' : 'text-start' }} mb-3">
                        <div style="max-width: 70%; display: inline-block;" class="{{ $message->sender_id == Auth::id() ? 'ms-auto' : 'me-auto' }}">
                            <div class="message-bubble p-2 rounded {{ $message->sender_id == Auth::id() ? 'bg-gradient-primary text-white' : 'bg-light' }}">
                                {!! nl2br(e($message->message)) !!}
                            </div>
                            <small class="text-xs text-muted d-block mt-1">
                                {{ $message->created_at->format('M d, H:i') }}
                                @if($message->sender_id == Auth::id())
                                    · {{ $message->is_read ? 'Read' : 'Sent' }}
                                @endif
                            </small>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <form action="{{ route('messages.send') }}" method="POST" class="mt-3" id="message-form">
                @csrf
                <input type="hidden" name="receiver_id" value="{{ $user->id }}">
                <div class="input-group">
                    <input type="text" name="message" id="message-input" class="form-control" placeholder="Type your message..." required autocomplete="off">
                    <button type="submit" class="btn" style="background-color: #821131; color: white;">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .message-bubble {
        border-radius: 18px;
        word-break: break-word;
    }
    
    .text-end .message-bubble {
        border-bottom-right-radius: 5px;
    }
    
    .text-start .message-bubble {
        border-bottom-left-radius: 5px;
    }
    
    .bg-light {
        background-color: #f0f2f5 !important;
    }
</style>

<script>
    // Scroll to bottom of chat on page load
    document.addEventListener('DOMContentLoaded', function() {
        const chatContainer = document.getElementById('chat-messages');
        chatContainer.scrollTop = chatContainer.scrollHeight;
        
        // Handle form submission with AJAX
        const messageForm = document.getElementById('message-form');
        const messageInput = document.getElementById('message-input');
        
        messageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(messageForm);
            
            // Send the message via AJAX
            fetch('{{ route("messages.send") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => {
                if (response.ok) {
                    // Clear the input
                    messageInput.value = '';
                    
                    // Refresh chat
                    refreshChat();
                }
            });
        });
        
        // Auto-refresh chat every 5 seconds
        setInterval(refreshChat, 5000);
    });
    
    function refreshChat() {
        fetch(window.location.href)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newMessages = doc.getElementById('chat-messages');
                
                if (newMessages) {
                    const chatContainer = document.getElementById('chat-messages');
                    const wasAtBottom = chatContainer.scrollHeight - chatContainer.scrollTop <= chatContainer.clientHeight + 100;
                    
                    document.getElementById('chat-messages').innerHTML = newMessages.innerHTML;
                    
                    // Scroll to bottom if user was already at the bottom
                    if (wasAtBottom) {
                        chatContainer.scrollTop = chatContainer.scrollHeight;
                    }
                }
            });
    }
</script>
@endsection
