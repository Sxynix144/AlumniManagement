@extends('layouts.guest')
@section('title', 'Create Account')

@section('panel-heading')Join the <em>alumni network</em>@endsection
@section('panel-desc', 'Create your account to update your profile, RSVP to events, and reconnect with your batchmates.')

@section('content')

<h2>Create Account</h2>
<p class="auth-subtitle">Fill in your details to get started.</p>

<form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="two-col">
        <div class="form-group">
            <label>First Name</label>
            <input type="text" name="name" value="{{ old('name') }}"
                   placeholder="Juan" required autofocus>
            @error('name')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label>Last Name</label>
            <input type="text" name="last_name" value="{{ old('last_name') }}"
                   placeholder="dela Cruz">
        </div>
    </div>

    <div class="form-group">
        <label>Email Address</label>
        <input type="email" name="email" value="{{ old('email') }}"
               placeholder="you@example.com" required autocomplete="username">
        @error('email')<div class="form-error">{{ $message }}</div>@enderror
    </div>

    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" placeholder="Min. 8 characters"
               required autocomplete="new-password">
        @error('password')<div class="form-error">{{ $message }}</div>@enderror
    </div>

    <div class="form-group">
        <label>Confirm Password</label>
        <input type="password" name="password_confirmation"
               placeholder="Repeat password" required autocomplete="new-password">
    </div>

    <button type="submit" class="btn-submit gold">Create Account →</button>
</form>

<hr class="divider">

<div class="auth-footer">
    Already have an account? <a href="{{ route('login') }}">Log in here</a>
</div>

@endsection
