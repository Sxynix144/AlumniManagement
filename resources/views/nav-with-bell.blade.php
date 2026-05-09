{{--
    REPLACE the entire <ul class="nav-links"> block in layouts/app.blade.php
    This adds: notification bell, tracer study, FAQs links
--}}

<ul class="nav-links">
    @guest
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a href="{{ route('alumni.search') }}">Find My Record</a></li>
        <li><a href="{{ route('announcements.index') }}">Announcements</a></li>
        <li><a href="{{ route('newsletters.index') }}">Newsletters</a></li>
        <li><a href="{{ route('gallery.index') }}">Gallery</a></li>
        <li><a href="{{ route('faqs.index') }}">FAQs</a></li>
        <li><a href="{{ route('login') }}">Sign In</a></li>
        <li><a href="{{ route('alumni.register') }}" class="btn-nav">Register</a></li>
    @endguest

    @auth
        @if(auth()->user()->isStaff())
            <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li><a href="{{ route('events.index') }}">Events</a></li>
            <li><a href="{{ route('admin.announcements.index') }}">Announcements</a></li>
            <li><a href="{{ route('admin.tracer.index') }}">Tracer Study</a></li>
            <li><a href="{{ route('admin.faqs.index') }}">FAQs</a></li>
            @if(auth()->user()->isAdmin())
                <li><a href="{{ route('admin.directory') }}">Directory</a></li>
                <li><a href="{{ route('admin.requests.index') }}">Requests</a></li>
            @endif
        @else
            <li><a href="{{ route('home') }}">Home</a></li>
            <li><a href="{{ route('requests.index') }}">My Requests</a></li>
            <li><a href="{{ route('announcements.index') }}">Announcements</a></li>
            <li><a href="{{ route('newsletters.index') }}">Newsletters</a></li>
            <li><a href="{{ route('gallery.index') }}">Gallery</a></li>
            <li><a href="{{ route('tracer.show') }}">Tracer Survey</a></li>
            <li><a href="{{ route('faqs.index') }}">FAQs</a></li>
            <li><a href="{{ route('alumni.profile') }}">My Profile</a></li>
        @endif

        {{-- 🔔 Notification Bell --}}
        <li style="position:relative">
            <a href="{{ route('notifications.index') }}"
               style="position:relative;display:flex;align-items:center;color:rgba(255,255,255,.75);font-size:1.2rem"
               title="Notifications">
                🔔
                <span id="notif-count"
                      style="position:absolute;top:-6px;right:-6px;background:#e53e3e;color:#fff;border-radius:99px;font-size:.6rem;font-weight:700;padding:.1rem .35rem;min-width:16px;text-align:center;display:none">
                    0
                </span>
            </a>
        </li>

        <li>
            <form action="{{ route('logout') }}" method="POST" style="display:inline">
                @csrf
                <button type="submit" class="btn-nav"
                    style="border:none;cursor:pointer;background:rgba(200,149,58,.15);color:#f8f4ee;padding:.45rem 1.1rem;border-radius:6px;font-weight:600;font-size:.85rem;">
                    Log Out
                </button>
            </form>
        </li>
    @endauth
</ul>

{{-- Notification bell counter (polls every 60s) --}}
@auth
<script>
function loadNotifCount() {
    fetch('{{ route("notifications.count") }}')
        .then(r => r.json())
        .then(data => {
            const badge = document.getElementById('notif-count');
            if (badge) {
                badge.textContent = data.count;
                badge.style.display = data.count > 0 ? 'inline' : 'none';
            }
        }).catch(() => {});
}
loadNotifCount();
setInterval(loadNotifCount, 60000);
</script>
@endauth
