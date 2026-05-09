@extends('layouts.guest')
@section('title', 'Reset Password')

@section('panel-heading')Reset your <em>password</em>@endsection
@section('panel-desc', 'No worries — enter your email and we\'ll send you a link to get back into your account.')

@section('content')

<h2>Forgot Password?</h2>
<p class="auth-subtitle">Enter your registered email and we'll send you a reset link.</p>

@if (session('status'))
    <div class="alert-success">{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <div class="form-group">
        <label>Email Address</label>
        <input type="email" name="email" value="{{ old('email') }}"
               placeholder="you@example.com" required autofocus>
        @error('email')<div class="form-error">{{ $message }}</div>@enderror
    </div>

    <button type="submit" class="btn-submit gold">Send Reset Link →</button>
</form>

<hr class="divider">

<div class="auth-footer">
    Remembered it? <a href="{{ route('login') }}">Back to login</a>
</div>

@endsection
