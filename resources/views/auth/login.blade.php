@extends('layouts.guest')
@section('title', 'Log In')

@section('panel-heading')Welcome <em>back</em>@endsection
@section('panel-desc', 'Sign in to manage your alumni profile, RSVP to events, and stay connected with your school.')

@section('content')

<h2>Log In</h2>
<p class="auth-subtitle">Enter your credentials to access your account.</p>

{{-- Session Status (e.g. after password reset) --}}
@if (session('status'))
    <div class="alert-success">{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="form-group">
        <label>Email Address</label>
        <input type="email" name="email" value="{{ old('email') }}"
               placeholder="you@example.com" required autofocus autocomplete="username">
        @error('email')<div class="form-error">{{ $message }}</div>@enderror
    </div>

    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" placeholder="••••••••"
               required autocomplete="current-password">
        @error('password')<div class="form-error">{{ $message }}</div>@enderror
    </div>

    <div class="remember-row">
        <label>
            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
            Remember me
        </label>
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
        @endif
    </div>

    <button type="submit" class="btn-submit gold">Sign In →</button>
</form>

<hr class="divider">

<div class="auth-footer">
    Not registered yet?
    <a href="{{ route('alumni.search') }}">Find my record</a>
    or
    <a href="{{ route('alumni.register') }}">Join as alumni</a>
</div>

@endsection
