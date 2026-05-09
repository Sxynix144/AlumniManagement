@extends('layouts.guest')
@section('title', 'Register as Alumni')

@section('panel-heading')Join the <em>alumni network</em>@endsection
@section('panel-desc', 'Add yourself to the directory. An admin will verify and approve your registration.')

@section('content')

<h2>Register as Alumni</h2>
<p class="auth-subtitle">Fill in your details to get started.</p>

<form method="POST" action="{{ route('alumni.store') }}">
    @csrf

    <div class="two-col">
        <div class="form-group">
            <label>First Name *</label>
            <input type="text" name="first_name" value="{{ old('first_name') }}" required autofocus>
            @error('first_name')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label>Last Name *</label>
            <input type="text" name="last_name" value="{{ old('last_name') }}" required>
            @error('last_name')<div class="form-error">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="two-col">
        <div class="form-group">
            <label>Graduation Year *</label>
            <input type="number" name="graduation_year" value="{{ old('graduation_year') }}"
                   min="1970" max="{{ date('Y') }}" placeholder="{{ date('Y') }}" required>
            @error('graduation_year')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label>Course / Degree</label>
            <input type="text" name="course" value="{{ old('course') }}" placeholder="e.g. BS Computer Science">
        </div>
    </div>

    <div class="two-col">
        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+63 9xx xxx xxxx">
        </div>
        <div class="form-group">
            <label>City</label>
            <input type="text" name="city" value="{{ old('city') }}" placeholder="Davao City">
        </div>
    </div>

    <div class="two-col">
        <div class="form-group">
            <label>Current Job</label>
            <input type="text" name="current_job" value="{{ old('current_job') }}">
        </div>
        <div class="form-group">
            <label>Company</label>
            <input type="text" name="company" value="{{ old('company') }}">
        </div>
    </div>

    <hr style="margin:1rem 0;border:none;border-top:1px solid var(--light)">

    <div class="form-group">
        <label>Email Address *</label>
        <input type="email" name="email" value="{{ old('email') }}"
               placeholder="you@example.com" required autocomplete="username">
        @error('email')<div class="form-error">{{ $message }}</div>@enderror
    </div>

    <div class="two-col">
        <div class="form-group">
            <label>Password *</label>
            <input type="password" name="password"
                   placeholder="Min. 8 characters" required minlength="8">
            @error('password')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label>Confirm Password *</label>
            <input type="password" name="password_confirmation"
                   placeholder="Repeat password" required>
        </div>
    </div>

    <button type="submit" class="btn-submit gold">
        Submit Registration →
    </button>
    <p style="font-size:.78rem;color:var(--slate);text-align:center;margin-top:.75rem">
        A verification email will be sent after registration.
        Your profile needs admin approval before full activation.
    </p>
</form>

<hr class="divider">

<div class="auth-footer">
    Already have an account? <a href="{{ route('login') }}">Log in here</a> ·
    <a href="{{ route('alumni.search') }}">Find my record</a>
</div>

@endsection