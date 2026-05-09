@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
<div class="page-header flex justify-between items-center">
    <div>
        <h1>Admin Dashboard</h1>
        <p>Command center — alumni records, approvals, and events at a glance.</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.import.form') }}" class="btn btn-outline btn-sm">⬆ Import CSV</a>
        <a href="{{ route('admin.export') }}" class="btn btn-gold btn-sm">⬇ Export All</a>
    </div>
</div>

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value">{{ number_format($stats['total']) }}</div>
        <div class="stat-label">Active Alumni</div>
    </div>
    <div class="stat-card" style="border-top-color:#f6ad55">
        <div class="stat-value">{{ $stats['pending'] }}</div>
        <div class="stat-label">Pending Approval</div>
    </div>
    <div class="stat-card" style="border-top-color:#90cdf4">
        <div class="stat-value">{{ $stats['placeholder'] }}</div>
        <div class="stat-label">Unclaimed Records</div>
    </div>
    <div class="stat-card" style="border-top-color:#9ae6b4">
        <div class="stat-value">{{ $stats['events'] }}</div>
        <div class="stat-label">Total Events</div>
    </div>
</div>

<div class="two-col" style="align-items:start">

    {{-- Pending Approvals --}}
   {{-- Pending Approvals --}}
@foreach($pendingAlumni as $alumni)
    <div class="flex justify-between items-center" style="padding:.75rem 0;border-bottom:1px solid var(--light)">
        <div>
            <strong>{{ $alumni->full_name }}</strong>
            <div class="text-sm text-muted">Batch {{ $alumni->graduation_year }} · {{ $alumni->email }}</div>
        </div>
        <div class="flex gap-1 items-center">
            @if(auth()->user()->isAdmin())
                {{-- Only admin sees these buttons --}}
                <form method="POST" action="{{ route('admin.approve', $alumni) }}">
                    @csrf
                    <button class="btn btn-sm btn-success">✓ Approve</button>
                </form>
                <form method="POST" action="{{ route('admin.reject', $alumni) }}">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger"
                        onclick="return confirm('Reject this registration?')">✗</button>
                </form>
            @else
                {{-- Coordinator sees a notice instead --}}
                <span style="font-size:.75rem;color:var(--slate);background:var(--light);padding:.3rem .7rem;border-radius:99px;font-weight:600">
                    🔒 Admin Only
                </span>
            @endif
        </div>
    </div>
@endforeach

        @forelse($pendingAlumni as $alumni)
            <div class="flex justify-between items-center" style="padding:.75rem 0;border-bottom:1px solid var(--light)">
                <div>
                    <strong>{{ $alumni->full_name }}</strong>
                    <div class="text-sm text-muted">Batch {{ $alumni->graduation_year }} · {{ $alumni->email }}</div>
                </div>
                <div class="flex gap-1">
                    <form method="POST" action="{{ route('admin.approve', $alumni) }}">
                        @csrf
                        <button class="btn btn-sm btn-success">✓ Approve</button>
                    </form>
                    <form method="POST" action="{{ route('admin.reject', $alumni) }}">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Reject this registration?')">✗</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-muted text-sm" style="padding:.5rem 0">No pending approvals. 🎉</p>
        @endforelse
    </div>

    {{-- Recent Profile Updates --}}
    <div class="card">
        <div class="flex justify-between items-center mb-2">
            <h2 class="section-title" style="margin-bottom:0;border:none">🔄 Recent Updates</h2>
            <a href="{{ route('admin.directory') }}" class="btn btn-sm btn-outline">Full Directory</a>
        </div>

        @foreach($recentUpdates as $alumni)
            <div style="padding:.65rem 0;border-bottom:1px solid var(--light)">
                <div class="flex justify-between">
                    <strong style="font-size:.9rem">{{ $alumni->full_name }}</strong>
                    <span class="text-sm text-muted">{{ $alumni->updated_at->diffForHumans() }}</span>
                </div>
                <div class="text-sm text-muted">
                    Batch {{ $alumni->graduation_year }}
                    @if($alumni->current_job) · {{ $alumni->current_job }} @endif
                    @if($alumni->city) · {{ $alumni->city }} @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- Upcoming Events --}}
@if($upcomingEvents->isNotEmpty())
<div class="mt-4">
    <div class="flex justify-between items-center mb-2">
        <h2 class="section-title" style="margin-bottom:0;border:none">📅 Upcoming Events</h2>
        <a href="{{ route('events.index') }}" class="btn btn-sm btn-outline">Manage Events</a>
    </div>
    <div class="table-wrap card" style="padding:0">
        <table>
            <thead>
                <tr>
                    <th>Event</th><th>Date</th><th>Venue</th><th>RSVPs</th><th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($upcomingEvents as $event)
                    <tr>
                        <td><strong>{{ $event->title }}</strong></td>
                        <td>{{ $event->event_date->format('M j, Y') }}</td>
                        <td>{{ $event->venue ?? '—' }}</td>
                        <td>{{ $event->getAttendingCount() }} attending</td>
                        <td>
                            <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-outline">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
