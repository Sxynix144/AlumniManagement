@extends('layouts.app')
@section('title', 'Claim Profile')

@section('content')
<div class="page-header">
    <h1>Claim Your Profile</h1>
    <p>You're about to link this school record to a new account.</p>
</div>

<div class="two-col" style="align-items:start">
    {{-- Record Preview --}}
    <div class="card" style="border-top:4px solid var(--gold)">
        <h2 class="section-title">📋 Existing Record</h2>
        <table>
            <tr>
                <td style="font-weight:600;padding:.5rem .5rem .5rem 0;color:var(--slate);width:130px">Name</td>
                <td>{{ $alumni->full_name }}</td>
            </tr>
            <tr>
                <td style="font-weight:600;padding:.5rem .5rem .5rem 0;color:var(--slate)">Batch Year</td>
                <td>{{ $alumni->graduation_year }}</td>
            </tr>
            @if($alumni->course)
            <tr>
                <td style="font-weight:600;padding:.5rem .5rem .5rem 0;color:var(--slate)">Course</td>
                <td>{{ $alumni->course }}</td>
            </tr>
            @endif
            @if($alumni->student_number)
            <tr>
                <td style="font-weight:600;padding:.5rem .5rem .5rem 0;color:var(--slate)">Student No.</td>
                <td>{{ $alumni->student_number }}</td>
            </tr>
            @endif
        </table>

        <p class="text-sm text-muted mt-3">
            ⚠️ By claiming this record you confirm you are this person. Misrepresentation may result in account removal.
        </p>
    </div>

    {{-- Claim Form --}}
    <div class="card">
        <h2 class="section-title">Create Your Account</h2>

        <form method="POST" action="{{ route('alumni.claim', $alumni) }}">
            @csrf

            <div class="form-group">
                <label>Email Address *</label>
                <input type="email" name="email" value="{{ old('email') }}" required>
                @error('email')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Password *</label>
                <input type="password" name="password" required minlength="8">
                @error('password')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Confirm Password *</label>
                <input type="password" name="password_confirmation" required>
            </div>

            <hr style="margin:1.2rem 0;border:none;border-top:1px solid var(--light)">
            <p class="text-sm text-muted mb-2">Optional — fill in as much as you like:</p>

            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+63 9xx xxx xxxx">
            </div>
            <div class="two-col">
                <div class="form-group">
                    <label>Current Job Title</label>
                    <input type="text" name="current_job" value="{{ old('current_job') }}">
                </div>
                <div class="form-group">
                    <label>Company / Employer</label>
                    <input type="text" name="company" value="{{ old('company') }}">
                </div>
            </div>
            <div class="form-group">
                <label>City / Location</label>
                <input type="text" name="city" value="{{ old('city') }}" placeholder="Davao City">
            </div>
            <div class="form-group">
                <label>Short Bio</label>
                <textarea name="bio" rows="3" placeholder="A few words about yourself...">{{ old('bio') }}</textarea>
            </div>

            <button type="submit" class="btn btn-gold w-full" style="font-size:1rem;padding:.8rem">
                ✅ Confirm — This is Me
            </button>
        </form>
    </div>
</div>
@endsection
