{{--
    REPLACE the <ul class="nav-links"> block inside your
    resources/views/layouts/app.blade.php with this updated version.
    It adds: Announcements, Newsletters, Gallery, My Requests links.
--}}

<ul class="nav-links">
    @guest
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a href="{{ route('alumni.search') }}">Find My Record</a></li>
        <li><a href="{{ route('announcements.index') }}">Announcements</a></li>
        <li><a href="{{ route('newsletters.index') }}">Newsletters</a></li>
        <li><a href="{{ route('gallery.index') }}">Gallery</a></li>
        <li><a href="{{ route('login') }}">Sign In</a></li>
        <li><a href="{{ route('alumni.register') }}" class="btn-nav">Register</a></li>
    @endguest

    @auth
        @if(auth()->user()->isStaff())
            <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li><a href="{{ route('events.index') }}">Events</a></li>
            <li><a href="{{ route('admin.announcements.index') }}">Announcements</a></li>
            <li><a href="{{ route('admin.newsletters.index') }}">Newsletters</a></li>
            <li><a href="{{ route('admin.gallery.index') }}">Gallery</a></li>
            <li><a href="{{ route('admin.requests.index') }}">Requests</a></li>
            @if(auth()->user()->isAdmin())
                <li><a href="{{ route('admin.directory') }}">Directory</a></li>
            @endif
        @else
            <li><a href="{{ route('home') }}">Home</a></li>
            <li><a href="{{ route('requests.index') }}">My Requests</a></li>
            <li><a href="{{ route('announcements.index') }}">Announcements</a></li>
            <li><a href="{{ route('newsletters.index') }}">Newsletters</a></li>
            <li><a href="{{ route('gallery.index') }}">Gallery</a></li>
            <li><a href="{{ route('alumni.profile') }}">My Profile</a></li>
        @endif

        <li>
            <form action="{{ route('logout') }}" method="POST" style="display:inline">
                @csrf
                <button type="submit" class="btn-nav" style="border:none;cursor:pointer;background:rgba(200,149,58,.15);color:#f8f4ee;padding:.45rem 1.1rem;border-radius:6px;font-weight:600;font-size:.85rem;">
                    Log Out
                </button>
            </form>
        </li>
    @endauth
</ul>
