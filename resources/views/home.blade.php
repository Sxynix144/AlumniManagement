@extends('layouts.app')

@section('title', 'Home')

@push('styles')
<style>
    .hero {
        background: linear-gradient(135deg, var(--navy) 0%, var(--navy2) 60%, #2a4a7f 100%);
        color: var(--white);
        border-radius: 10px;
        padding: 4rem 3rem;
        margin-bottom: 3rem;
        position: relative;
        overflow: hidden;
    }
    .hero::before {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 300px; height: 300px;
        border-radius: 50%;
        background: rgba(200,149,58,.12);
    }
    .hero::after {
        content: '';
        position: absolute;
        bottom: -80px; left: 30%;
        width: 200px; height: 200px;
        border-radius: 50%;
        background: rgba(200,149,58,.07);
    }
    .hero-badge {
        display: inline-block;
        background: rgba(200,149,58,.2);
        color: var(--gold2);
        padding: .3rem .9rem;
        border-radius: 99px;
        font-size: .8rem;
        font-weight: 600;
        letter-spacing: .08em;
        text-transform: uppercase;
        margin-bottom: 1rem;
        border: 1px solid rgba(200,149,58,.3);
    }
    .hero h1 {
        font-family: 'Playfair Display', serif;
        font-size: 3rem;
        line-height: 1.15;
        margin-bottom: 1rem;
    }
    .hero h1 em { color: var(--gold2); font-style: normal; }
    .hero p { color: rgba(255,255,255,.75); font-size: 1.1rem; max-width: 560px; margin-bottom: 2rem; }
    .hero-actions { display: flex; gap: 1rem; flex-wrap: wrap; }
    .hero-actions .btn-gold { font-size: 1rem; padding: .8rem 2rem; }
    .hero-actions .btn-ghost {
        background: transparent;
        color: rgba(255,255,255,.8);
        border: 1.5px solid rgba(255,255,255,.3);
        font-size: 1rem; padding: .8rem 2rem;
        border-radius: var(--radius);
        text-decoration: none;
        font-weight: 600;
        transition: all .15s;
    }
    .hero-actions .btn-ghost:hover { border-color: var(--white); color: var(--white); }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
    }
    .feature-card {
        background: var(--white);
        border-radius: var(--radius);
        padding: 2rem;
        box-shadow: var(--shadow);
        border-bottom: 3px solid var(--gold);
        transition: transform .2s;
    }
    .feature-card:hover { transform: translateY(-3px); }
    .feature-icon {
        width: 48px; height: 48px;
        background: var(--navy);
        border-radius: var(--radius);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
        margin-bottom: 1rem;
    }
    .feature-card h3 {
        font-family: 'Playfair Display', serif;
        font-size: 1.2rem;
        margin-bottom: .5rem;
        color: var(--navy);
    }
    .feature-card p { color: var(--slate); font-size: .9rem; line-height: 1.6; }

    .cta-strip {
        background: var(--navy);
        border-radius: var(--radius);
        padding: 2rem 2.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.5rem;
        flex-wrap: wrap;
    }
    .cta-strip h2 { font-family: 'Playfair Display', serif; color: var(--white); font-size: 1.5rem; }
    .cta-strip p { color: rgba(255,255,255,.65); font-size: .9rem; margin-top: .25rem; }
</style>
@endpush

@section('content')

<div class="hero">
    <div class="hero-badge">🎓 Alumni Management System</div>
    <h1>Reconnect with your <em>school family</em></h1>
    <p>Update your information, join reunions, and stay part of the community — wherever life has taken you.</p>
    <div class="hero-actions">
        <a href="{{ route('alumni.search') }}" class="btn btn-gold">Find My Record</a>
        <a href="{{ route('alumni.register') }}" class="btn-ghost">Join as New Alumni</a>
    </div>
</div>

<div class="features-grid">
    <div class="feature-card">
        <div class="feature-icon">👤</div>
        <h3>Claim Your Profile</h3>
        <p>Search for your name in our historical records. If you're listed, claim and update your profile in minutes — no admin needed.</p>
    </div>
    <div class="feature-card">
        <div class="feature-icon">📬</div>
        <h3>Event Invitations</h3>
        <p>Receive personalized invitations to reunions, homecomings, and gatherings tailored to your graduation batch.</p>
    </div>
    <div class="feature-card">
        <div class="feature-icon">🤝</div>
        <h3>Stay Connected</h3>
        <p>The school keeps your current contact info so classmates and the institution can always find you — with your permission.</p>
    </div>
</div>

<div class="cta-strip">
    <div>
        <h2>Are you school staff?</h2>
        <p>Access the admin dashboard to manage alumni records, approve registrations, and coordinate events.</p>
    </div>
    <a href="{{ route('login') }}" class="btn btn-gold">Staff Login →</a>
</div>

@endsection
