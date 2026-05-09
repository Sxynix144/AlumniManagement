<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Alumni Management System') — AlumniConnect</title>

    {{-- Google Fonts: Playfair Display + DM Sans --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --navy:   #0f2044;
            --navy2:  #1a3566;
            --gold:   #c8953a;
            --gold2:  #e8b44d;
            --cream:  #f8f4ee;
            --white:  #ffffff;
            --slate:  #4a5568;
            --light:  #edf2f7;
            --danger: #c53030;
            --success:#276749;
            --radius: 6px;
            --shadow: 0 2px 12px rgba(15,32,68,.10);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            color: var(--navy);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── NAV ── */
        nav {
            background: var(--navy);
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
            box-shadow: 0 2px 16px rgba(0,0,0,.25);
        }
        .nav-brand {
            display: flex;
            align-items: center;
            gap: .75rem;
            text-decoration: none;
        }
        .nav-brand-icon {
            width: 38px; height: 38px;
            background: var(--gold);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem;
            color: var(--navy);
            font-weight: 700;
        }
        .nav-brand-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem;
            color: var(--white);
            font-weight: 700;
            letter-spacing: .02em;
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            list-style: none;
        }
        .nav-links a {
            color: rgba(255,255,255,.75);
            text-decoration: none;
            font-size: .9rem;
            font-weight: 500;
            transition: color .15s;
        }
        .nav-links a:hover { color: var(--gold2); }
        .nav-links .btn-nav {
            background: var(--gold);
            color: var(--navy);
            padding: .45rem 1.1rem;
            border-radius: var(--radius);
            font-weight: 600;
            font-size: .85rem;
        }
        .nav-links .btn-nav:hover { background: var(--gold2); color: var(--navy); }

        /* ── FLASH MESSAGES ── */
        .flash {
            padding: .85rem 1.5rem;
            border-left: 4px solid;
            font-size: .9rem;
            font-weight: 500;
            margin: 1rem auto;
            max-width: 1100px;
            border-radius: var(--radius);
        }
        .flash-success { background: #f0fff4; border-color: var(--success); color: var(--success); }
        .flash-error   { background: #fff5f5; border-color: var(--danger);  color: var(--danger);  }

        /* ── MAIN CONTENT ── */
        main { flex: 1; padding: 2rem; max-width: 1100px; margin: 0 auto; width: 100%; }

        /* ── PAGE HEADER ── */
        .page-header {
            border-bottom: 2px solid var(--gold);
            padding-bottom: 1rem;
            margin-bottom: 2rem;
        }
        .page-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: var(--navy);
        }
        .page-header p { color: var(--slate); margin-top: .3rem; font-size: .95rem; }

        /* ── CARDS ── */
        .card {
            background: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 1.5rem;
            border: 1px solid rgba(15,32,68,.07);
        }

        /* ── STAT CARDS ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 1.4rem 1.5rem;
            box-shadow: var(--shadow);
            border-top: 4px solid var(--gold);
        }
        .stat-card .stat-value {
            font-family: 'Playfair Display', serif;
            font-size: 2.4rem;
            color: var(--navy);
            line-height: 1;
        }
        .stat-card .stat-label {
            font-size: .8rem;
            font-weight: 600;
            color: var(--slate);
            text-transform: uppercase;
            letter-spacing: .08em;
            margin-top: .4rem;
        }

        /* ── BUTTONS ── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .6rem 1.3rem;
            border-radius: var(--radius);
            font-family: 'DM Sans', sans-serif;
            font-size: .88rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: 2px solid transparent;
            transition: all .15s;
        }
        .btn-primary { background: var(--navy); color: var(--white); }
        .btn-primary:hover { background: var(--navy2); }
        .btn-gold { background: var(--gold); color: var(--navy); }
        .btn-gold:hover { background: var(--gold2); }
        .btn-outline { background: transparent; border-color: var(--navy); color: var(--navy); }
        .btn-outline:hover { background: var(--navy); color: var(--white); }
        .btn-danger { background: var(--danger); color: var(--white); }
        .btn-danger:hover { background: #9b2c2c; }
        .btn-sm { padding: .4rem .85rem; font-size: .8rem; }
        .btn-success { background: var(--success); color: var(--white); }

        /* ── FORM ELEMENTS ── */
        .form-group { margin-bottom: 1.2rem; }
        .form-group label {
            display: block;
            font-size: .85rem;
            font-weight: 600;
            color: var(--navy);
            margin-bottom: .4rem;
            text-transform: uppercase;
            letter-spacing: .05em;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: .65rem .9rem;
            border: 1.5px solid #d1d5db;
            border-radius: var(--radius);
            font-family: 'DM Sans', sans-serif;
            font-size: .9rem;
            color: var(--navy);
            background: var(--white);
            transition: border-color .15s;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(200,149,58,.15);
        }
        .form-error { color: var(--danger); font-size: .8rem; margin-top: .3rem; }

        /* ── TABLE ── */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: .88rem; }
        thead th {
            background: var(--navy);
            color: var(--white);
            padding: .75rem 1rem;
            text-align: left;
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .07em;
        }
        tbody td { padding: .75rem 1rem; border-bottom: 1px solid var(--light); }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover { background: var(--cream); }

        /* ── BADGES ── */
        .badge {
            display: inline-block;
            padding: .2rem .6rem;
            border-radius: 99px;
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
        }
        .badge-active      { background: #c6f6d5; color: #22543d; }
        .badge-pending     { background: #fef3c7; color: #92400e; }
        .badge-placeholder { background: var(--light); color: var(--slate); }
        .badge-published   { background: #bee3f8; color: #2a4365; }
        .badge-draft       { background: var(--light); color: var(--slate); }
        .badge-cancelled   { background: #fed7d7; color: var(--danger); }

        /* ── SECTION TITLE ── */
        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem;
            color: var(--navy);
            margin-bottom: 1rem;
            padding-bottom: .5rem;
            border-bottom: 1px solid var(--light);
        }

        /* ── GRID LAYOUT ── */
        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        .three-col { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem; }
        @media (max-width: 768px) {
            .two-col, .three-col { grid-template-columns: 1fr; }
            nav { padding: 0 1rem; }
            main { padding: 1rem; }
        }

        /* ── FOOTER ── */
        footer {
            background: var(--navy);
            color: rgba(255,255,255,.5);
            text-align: center;
            padding: 1.2rem;
            font-size: .8rem;
            margin-top: auto;
        }
        footer strong { color: var(--gold); }

        /* ── PAGINATION ── */
        .pagination { display: flex; gap: .5rem; margin-top: 1.5rem; justify-content: center; }
        .pagination a, .pagination span {
            padding: .45rem .85rem;
            border-radius: var(--radius);
            font-size: .85rem;
            text-decoration: none;
            border: 1.5px solid var(--light);
            color: var(--navy);
        }
        .pagination .active span { background: var(--navy); color: var(--white); border-color: var(--navy); }

        .mt-1 { margin-top: .5rem; }
        .mt-2 { margin-top: 1rem; }
        .mt-3 { margin-top: 1.5rem; }
        .mt-4 { margin-top: 2rem; }
        .mb-1 { margin-bottom: .5rem; }
        .mb-2 { margin-bottom: 1rem; }
        .flex  { display: flex; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-1 { gap: .5rem; }
        .gap-2 { gap: 1rem; }
        .text-sm { font-size: .85rem; }
        .text-muted { color: var(--slate); }
        .text-center { text-align: center; }
        .w-full { width: 100%; }
    </style>

    @stack('styles')
</head>
<body>

<nav>
    <a href="{{ route('home') }}" class="nav-brand">
        <div class="nav-brand-icon">A</div>
        <span class="nav-brand-name">AlumniConnect</span>
    </a>

    <ul class="nav-links">
    @guest
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a href="{{ route('alumni.search') }}">Find My Record</a></li>
        <li><a href="{{ route('announcements.index') }}">Announcements</a></li>
        <li><a href="{{ route('newsletters.index') }}">Newsletters</a></li>
        <li><a href="{{ route('gallery.index') }}">Gallery</a></li>
        <li><a href="{{ route('faqs.index') }}">FAQs</a></li>
        <li><a href="{{ route('login') }}">Log In</a></li>
        <li><a href="{{ route('alumni.register') }}" class="btn-nav">Join</a></li>
    @endguest

        @auth
            @if(auth()->user()->isStaff())
                <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('events.index') }}">Events</a></li>
                <li><a href="{{ route('admin.announcements.index') }}">Announcements</a></li>  
                <li><a href="{{ route('admin.newsletters.index') }}">Newsletters</a></li>
                <li><a href="{{ route('admin.gallery.index') }}">Gallery</a></li>
                <li><a href="{{ route('admin.requests.index') }}">Requests</a></li>
                <li><a href="{{ route('admin.tracer.index') }}">Tracer Study</a></li>
                <li><a href="{{ route('admin.faqs.index') }}">FAQs</a></li>
                @if(auth()->user()->isAdmin())
                    <li><a href="{{ route('admin.directory') }}">Directory</a></li>
                @endif

            @else
                
                <li><a href="{{ route('alumni.profile') }}">My Profile</a></li>
                <li><a href="{{ route('announcements.index') }}">Announcements</a></li>
                <li><a href="{{ route('newsletters.index') }}">Newsletters</a></li>
                <li><a href="{{ route('gallery.index') }}">Gallery</a></li>
                <li><a href="{{ route('faqs.index') }}">FAQs</a></li>
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
</nav>

@if(session('success'))
    <div style="max-width:1100px;margin:1rem auto 0;padding:0 2rem">
        <div class="flash flash-success">✓ {{ session('success') }}</div>
    </div>
@endif
@if(session('error'))
    <div style="max-width:1100px;margin:1rem auto 0;padding:0 2rem">
        <div class="flash flash-error">✗ {{ session('error') }}</div>
    </div>
@endif

<main>
    @yield('content')
</main>

<footer>
    <strong>AlumniConnect</strong> · Alumni Management System · &copy; {{ date('Y') }}
</footer>

@stack('scripts')
</body>
</html>
