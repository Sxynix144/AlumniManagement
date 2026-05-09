@extends('layouts.guest')
@section('title', 'Set New Password')

@section('panel-heading')Set a new <em>password</em>@endsection
@section('panel-desc', 'Choose a strong password to keep your alumni profile secure.')

@section('content')

<h2>Set New Password</h2>
<p class="auth-subtitle">Enter and confirm your new password below.</p>

<form method="POST" action="{{ route('password.store') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    <div class="form-group">
        <label>Email Address</label>
        <input type="email" name="email" value="{{ old('email', $request->email) }}"
               required autofocus autocomplete="username">
        @error('email')<div class="form-error">{{ $message }}</div>@enderror
    </div>

    <div class="form-group">
        <label>New Password</label>
        <input type="password" name="password" placeholder="Min. 8 characters"
               required autocomplete="new-password">
        @error('password')<div class="form-error">{{ $message }}</div>@enderror
    </div>

    <div class="form-group">
        <label>Confirm New Password</label>
        <input type="password" name="password_confirmation"
               placeholder="Repeat password" required autocomplete="new-password">
    </div>

    <button type="submit" class="btn-submit gold">Reset Password →</button>
</form>

@endsection
