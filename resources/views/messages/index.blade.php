@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header pb-0 px-3">
            <h6 class="mb-0">My Messages</h6>
        </div>
        <div class="card-body pt-4 p-3">
            @if(session('error'))
                <div class="alert alert-danger text-white" role="alert">
                    {{ session('error') }}
                </div>
            @endif
            
            @if(count($users) > 0)
                <div class="list-group">
                    @foreach($users as $user)
                        <a href="{{ route('messages.chat', $user->id) }}" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <div>
                                    @if($user->avatar)
                                        <img src="{{ $user->avatar }}" class="avatar avatar-sm rounded-circle me-2">
                                    @else
                                        <div class="avatar avatar-sm bg-gradient-secondary rounded-circle text-white me-2 d-inline-flex align-items-center justify-content-center">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <span>{{ $user->name }}</span>
                                    <small class="text-muted ms-2">({{ ucfirst($user->role) }})</small>
                                </div>
                                <div class="unread-badge-{{ $user->id }}"></div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4">
                    <p class="text-muted">No users available to chat with.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .avatar-sm {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
</style>

<script>
    // Check for unread messages every 10 seconds
    function checkUnreadMessages() {
        @foreach($users as $user)
            fetch('{{ route("messages.unread.from", $user->id) }}')
                .then(response => response.json())
                .then(data => {
                    const badge = document.querySelector('.unread-badge-{{ $user->id }}');
                    if (data.count > 0) {
                        badge.innerHTML = `<span class="badge bg-danger rounded-pill">${data.count}</span>`;
                    } else {
                        badge.innerHTML = '';
                    }
                });
        @endforeach
    }
    
    // Initial check
    document.addEventListener('DOMContentLoaded', function() {
        checkUnreadMessages();
        
        // Set interval
        setInterval(checkUnreadMessages, 10000);
    });
</script>
@endsection

