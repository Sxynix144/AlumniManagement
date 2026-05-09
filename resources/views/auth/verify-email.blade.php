@extends('layouts.guest')
@section('title', 'Verify Your Email')

@section('panel-heading')Check your <em>inbox</em>@endsection
@section('panel-desc', 'A verification link has been sent to your email. Click it to activate your account and access all alumni features.')

@section('content')

<div style="text-align:center;margin-bottom:1.5rem">
    <div style="width:80px;height:80px;background:linear-gradient(135deg,var(--navy),var(--navy2));border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-size:2.2rem;box-shadow:0 4px 16px rgba(15,32,68,.2)">
        📧
    </div>
    <h2 style="margin-bottom:.4rem">Verify Your Email</h2>
    <p class="auth-subtitle" style="margin-bottom:0">
        We sent a verification link to<br>
        <strong style="color:var(--navy)">{{ auth()->user()->email }}</strong>
    </p>
</div>

@if(session('status') == 'verification-link-sent')
    <div class="alert-success" style="text-align:center">
        ✅ A new verification link has been sent to your email address.
    </div>
@endif

{{-- Steps --}}
<div style="background:var(--cream);border-radius:8px;padding:1.2rem;margin-bottom:1.5rem">
    <p style="font-size:.8rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--slate);margin-bottom:.8rem">What to do:</p>
    @foreach([
        ['📬', 'Open your email inbox'],
        ['🔍', 'Look for an email from AlumniConnect'],
        ['🖱️', 'Click the "Verify My Email Address" button'],
        ['✅', 'You\'ll be redirected back here automatically'],
    ] as [$icon, $text])
        <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:.5rem;font-size:.85rem;color:var(--navy)">
            <span>{{ $icon }}</span>
            <span>{{ $text }}</span>
        </div>
    @endforeach
</div>

<div style="background:#fffbf0;border:1px solid #f6e49a;border-left:3px solid var(--gold);border-radius:6px;padding:.75rem 1rem;margin-bottom:1.5rem;font-size:.82rem;color:#92400e">
    ⏰ The link expires in <strong>60 minutes</strong>. Check your spam folder if you don't see it.
</div>

<form method="POST" action="{{ route('verification.send') }}">
    @csrf
    <button type="submit" class="btn-submit gold">
        📨 Resend Verification Email
    </button>
</form>

<hr class="divider">

<div class="auth-footer">
    Wrong email address?
    <form method="POST" action="{{ route('logout') }}" style="display:inline">
        @csrf
        <button type="submit"
            style="background:none;border:none;color:var(--navy);font-weight:700;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:.88rem;">
            Log out and start over
        </button>
    </form>
</div>

@endsection
