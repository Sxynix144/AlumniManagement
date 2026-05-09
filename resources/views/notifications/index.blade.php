@extends('layouts.app')
@section('title', 'Notifications')

@push('styles')
<style>
.notif-item {
    display: flex;
    gap: 1rem;
    padding: 1rem 1.2rem;
    border-bottom: 1px solid var(--light);
    transition: background .15s;
    align-items: flex-start;
}
.notif-item:last-child { border-bottom: none; }
.notif-item.unread { background: #fffbf0; border-left: 3px solid var(--gold); }
.notif-item:hover { background: var(--cream); }
.notif-icon {
    font-size: 1.5rem;
    width: 44px; height: 44px;
    background: var(--cream);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.notif-body { flex: 1; }
.notif-body strong { font-size: .92rem; color: var(--navy); display: block; margin-bottom: .2rem; }
.notif-body p     { font-size: .83rem; color: var(--slate); line-height: 1.5; margin-bottom: .3rem; }
.notif-time { font-size: .72rem; color: #aaa; }
.notif-actions { display: flex; gap: .5rem; align-items: center; }
</style>
@endpush

@section('content')
<div class="page-header flex justify-between items-center">
    <div>
        <h1>🔔 Notifications</h1>
        <p>Your recent alerts and updates.</p>
    </div>
    <button onclick="markAllRead()" class="btn btn-outline btn-sm">✓ Mark all as read</button>
</div>

<div class="card" style="padding:0">
    @forelse($notifications as $notif)
        <div class="notif-item {{ !$notif->is_read ? 'unread' : '' }}">
            <div class="notif-icon">{{ $notif->icon }}</div>
            <div class="notif-body">
                <strong>{{ $notif->title }}</strong>
                <p>{{ $notif->message }}</p>
                <span class="notif-time">{{ $notif->created_at->diffForHumans() }}</span>
            </div>
            <div class="notif-actions">
                @if($notif->link)
                    <a href="{{ route('notifications.read', $notif) }}" class="btn btn-sm btn-outline">View</a>
                @endif
                <form method="POST" action="{{ route('notifications.destroy', $notif) }}">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm" style="background:none;border:none;color:#aaa;cursor:pointer;font-size:1.1rem" title="Remove">✕</button>
                </form>
            </div>
        </div>
    @empty
        <div style="text-align:center;padding:3rem;color:var(--slate)">
            <div style="font-size:3rem;margin-bottom:1rem">🔔</div>
            <p>No notifications yet.</p>
        </div>
    @endforelse
</div>

<div class="pagination mt-2">{{ $notifications->links() }}</div>

@push('scripts')
<script>
function markAllRead() {
    fetch('{{ route("notifications.markAllRead") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        }
    }).then(() => {
        document.querySelectorAll('.notif-item.unread').forEach(el => el.classList.remove('unread'));
        // Update bell count
        const bell = document.getElementById('notif-count');
        if (bell) bell.style.display = 'none';
    });
}
</script>
@endpush
@endsection
