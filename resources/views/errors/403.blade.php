@extends('layouts.app')
@section('title', 'Access Denied')

@section('content')
<div style="text-align:center;padding:5rem 2rem;max-width:500px;margin:0 auto">
    <div style="font-size:4rem;margin-bottom:1rem">🔒</div>
    <h1 style="font-family:'Playfair Display',serif;font-size:2rem;color:var(--navy);margin-bottom:.5rem">
        Access Denied
    </h1>
    <p style="color:var(--slate);margin-bottom:.5rem;font-size:1rem;line-height:1.7">
        {{ $exception->getMessage() ?: 'You do not have permission to access this page.' }}
    </p>
    <div style="background:#fffbf0;border:1px solid rgba(200,149,58,.3);border-left:4px solid var(--gold);border-radius:8px;padding:1rem;margin:1.5rem 0;font-size:.88rem;color:var(--slate)">
        <strong style="color:var(--navy)">Why am I seeing this?</strong><br>
        This action requires <strong>Administrator</strong> privileges.
        Only the school admin account can approve or reject alumni registrations.
    </div>
    <div class="flex gap-2" style="justify-content:center">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">← Back to Dashboard</a>
        <a href="{{ route('admin.pending') }}" class="btn btn-outline">View Pending List</a>
    </div>
    <p style="margin-top:1.5rem;font-size:.8rem;color:#aaa">
        Logged in as: <strong>{{ auth()->user()->name }}</strong>
        ({{ ucfirst(auth()->user()->role) }})
    </p>
</div>
@endsection