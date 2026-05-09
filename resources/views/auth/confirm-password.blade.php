@extends('layouts.guest')
@section('title', 'Confirm Password')

@section('panel-heading')Security <em>check</em>@endsection
@section('panel-desc', 'This is a protected area. Please confirm your password to continue.')

@section('content')

<div style="text-align:center;margin-bottom:1.5rem">
    <div style="width:60px;height:60px;background:var(--navy);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-size:1.6rem;">
        🔒
    </div>
    <h2 style="margin-bottom:.3rem">Confirm Password</h2>
    <p class="auth-subtitle" style="margin-bottom:0">This is a secure area. Please re-enter your password.</p>
</div>

<form method="POST" action="{{ route('password.confirm') }}">
    @csrf

    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" placeholder="••••••••"
               required autocomplete="current-password" autofocus>
        @error('password')<div class="form-error">{{ $message }}</div>@enderror
    </div>

    <button type="submit" class="btn-submit gold">Confirm & Continue →</button>
</form>

@endsection
