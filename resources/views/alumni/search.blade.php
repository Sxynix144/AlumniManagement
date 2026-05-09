@extends('layouts.app')
@section('title', 'Find My Record')

@section('content')
<div class="page-header">
    <h1>Find My Record</h1>
    <p>Search for your name and graduation year in our alumni directory.</p>
</div>

<div class="card" style="max-width:600px;margin-bottom:2rem">
    <form method="GET" action="{{ route('alumni.search') }}">
        <div class="form-group">
            <label>Your Name</label>
            <input type="text" name="name" placeholder="e.g. Juan dela Cruz"
                   value="{{ $query['name'] ?? '' }}" required>
        </div>
        <div class="form-group">
            <label>Graduation Year (Optional)</label>
            <input type="number" name="graduation_year" placeholder="e.g. 2015"
                   min="1970" max="{{ date('Y') }}"
                   value="{{ $query['graduation_year'] ?? '' }}">
        </div>
        <button type="submit" class="btn btn-primary w-full">🔍 Search</button>
    </form>
</div>

@if($results->isNotEmpty())
    <h2 class="section-title">{{ $results->count() }} result(s) found</h2>
    <div style="display:grid;gap:1rem;margin-bottom:2rem">
        @foreach($results as $alumnus)
            <div class="card flex items-center justify-between gap-2" style="flex-wrap:wrap">
                <div>
                    <strong style="font-size:1.05rem">{{ $alumnus->full_name }}</strong>
                    <div class="text-muted text-sm mt-1">
                        Batch {{ $alumnus->graduation_year }}
                        @if($alumnus->course) · {{ $alumnus->course }} @endif
                        @if($alumnus->city) · {{ $alumnus->city }} @endif
                    </div>
                </div>
                <div class="flex gap-1 items-center">
                    @if($alumnus->isClaimed())
                        <span class="badge badge-active">Profile Claimed</span>
                    @else
                        <span class="badge badge-placeholder">Unclaimed</span>
                        <a href="{{ route('alumni.claim.show', $alumnus) }}" class="btn btn-gold btn-sm">
                            This is Me →
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@elseif(request()->filled('name'))
    <div class="card text-center" style="padding:3rem;max-width:500px">
        <div style="font-size:3rem;margin-bottom:1rem">🔎</div>
        <h3 style="font-family:'Playfair Display',serif;margin-bottom:.5rem">No record found</h3>
        <p class="text-muted" style="margin-bottom:1.5rem">
            We couldn't find <strong>{{ request('name') }}</strong> in our database.
            You can add yourself to the list.
        </p>
        <a href="{{ route('alumni.register') }}" class="btn btn-primary">Add Me to the List</a>
    </div>
@endif

<div style="margin-top:2rem">
    <p class="text-muted text-sm">Not in the system yet?
        <a href="{{ route('alumni.register') }}" style="color:var(--navy);font-weight:600">
            Register as a new alumni →
        </a>
    </p>
</div>
@endsection
