@extends('layouts.app')
@section('title', 'Admin Dashboard')

@push('styles')
<style>
.dash-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-top: 1.5rem;
}
@media(max-width:768px){ .dash-grid{ grid-template-columns:1fr; } }

/* ── Pending card ── */
.pending-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem 0;
    border-bottom: 1px solid var(--light);
}
.pending-item:last-child { border-bottom: none; }

.pending-avatar {
    width: 44px; height: 44px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--navy), #1a5276);
    color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-family: 'Playfair Display', serif;
    font-size: 1.1rem;
    font-weight: 700;
    flex-shrink: 0;
}

.pending-info { flex: 1; min-width: 0; }
.pending-name  { font-weight: 700; font-size: .92rem; color: var(--navy); }
.pending-meta  { font-size: .78rem; color: var(--slate); margin-top: .15rem; }
.pending-tags  { display: flex; gap: .4rem; flex-wrap: wrap; margin-top: .4rem; }
.pending-tag {
    font-size: .68rem;
    font-weight: 700;
    padding: .15rem .5rem;
    border-radius: 99px;
    text-transform: uppercase;
    letter-spacing: .05em;
}
.tag-new      { background: #bee3f8; color: #2a4365; }
.tag-batch    { background: #fef3c7; color: #92400e; }
.tag-email    { background: #c6f6d5; color: #22543d; }

.pending-actions { display: flex; gap: .4rem; flex-shrink: 0; }

/* Admin-only notice */
.admin-only-notice {
    display: flex;
    align-items: center;
    gap: .5rem;
    background: #fffbf0;
    border: 1px solid rgba(200,149,58,.3);
    border-radius: 8px;
    padding: .5rem .85rem;
    font-size: .78rem;
    color: #92400e;
    font-weight: 600;
    flex-shrink: 0;
}

/* ── Recent updates ── */
.update-item {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: .65rem 0;
    border-bottom: 1px solid var(--light);
}
.update-item:last-child { border-bottom: none; }
.update-avatar {
    width: 36px; height: 36px;
    border-radius: 50%;
    background: var(--gold);
    color: var(--navy);
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: .8rem; flex-shrink: 0;
}
.update-name { font-size: .88rem; font-weight: 600; color: var(--navy); }
.update-meta { font-size: .75rem; color: var(--slate); }
</style>
@endpush

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

{{-- ── Stats ── --}}
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

{{-- ── Tracer Quick Card ── --}}
@if(isset($tracerStats) && $tracerStats['active'])
<div class="card mb-3" style="border-left:4px solid var(--gold);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem">
    <div>
        <h3 style="font-family:'Playfair Display',serif;font-size:1.05rem;margin-bottom:.25rem">
            📊 Tracer Study
        </h3>
        <p class="text-sm text-muted">
            Active: <strong>{{ $tracerStats['active']->title }}</strong>
            · {{ $tracerStats['responses'] }} responses collected
        </p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.tracer.results', $tracerStats['active']) }}" class="btn btn-sm btn-primary">View Results</a>
        <a href="{{ route('admin.tracer.index') }}" class="btn btn-sm btn-outline">Manage →</a>
    </div>
</div>
@endif

{{-- ── Main Two-Column Grid ── --}}
<div class="dash-grid">

    {{-- ── Pending Approvals ── --}}
    <div class="card">
        <div class="flex justify-between items-center mb-2">
            <h2 class="section-title" style="margin-bottom:0;border:none">
                ⏳ Pending Registrations
                @if($stats['pending'] > 0)
                    <span style="background:#fed7d7;color:#c53030;border-radius:99px;font-size:.7rem;padding:.15rem .55rem;font-weight:700;margin-left:.4rem">
                        {{ $stats['pending'] }} new
                    </span>
                @endif
            </h2>
            @if($stats['pending'] > 0)
                <a href="{{ route('admin.pending') }}" class="btn btn-sm btn-outline">View All</a>
            @endif
        </div>

        @if(!auth()->user()->isAdmin())
            {{-- Coordinator notice --}}
            <div style="background:#fffbf0;border:1px solid rgba(200,149,58,.3);border-left:4px solid var(--gold);border-radius:8px;padding:1rem;margin-bottom:1rem">
                <div style="font-weight:700;color:var(--navy);font-size:.88rem;margin-bottom:.3rem">
                    🔒 Approval requires Admin access
                </div>
                <p style="font-size:.8rem;color:var(--slate);margin:0">
                    There {{ $stats['pending'] === 1 ? 'is' : 'are' }}
                    <strong>{{ $stats['pending'] }}</strong>
                    pending {{ Str::plural('registration', $stats['pending']) }} waiting for the school admin to review.
                </p>
            </div>
        @endif

        @forelse($pendingAlumni as $alumni)
            <div class="pending-item">
                <div class="pending-avatar">
                    {{ strtoupper(substr($alumni->first_name, 0, 1)) }}
                </div>
                <div class="pending-info">
                    <div class="pending-name">{{ $alumni->full_name }}</div>
                    <div class="pending-meta">{{ $alumni->email }}</div>
                    <div class="pending-tags">
                        <span class="pending-tag tag-new">🆕 New Registration</span>
                        <span class="pending-tag tag-batch">Batch {{ $alumni->graduation_year }}</span>
                        @if($alumni->course)
                            <span class="pending-tag" style="background:var(--light);color:var(--slate)">
                                {{ Str::limit($alumni->course, 25) }}
                            </span>
                        @endif
                    </div>
                    <div style="font-size:.72rem;color:#aaa;margin-top:.3rem">
                        Submitted {{ $alumni->created_at->diffForHumans() }}
                    </div>
                </div>

                @if(auth()->user()->isAdmin())
                    <div class="pending-actions">
                        <form method="POST" action="{{ route('admin.approve', $alumni) }}">
                            @csrf
                            <button class="btn btn-sm btn-success" title="Approve this registration">
                                ✓ Approve
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.reject', $alumni) }}">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"
                                onclick="return confirm('Permanently reject {{ $alumni->full_name }}\'s registration?')"
                                title="Reject and remove">
                                ✗
                            </button>
                        </form>
                    </div>
                @else
                    <div class="admin-only-notice">
                        🔒 Admin Only
                    </div>
                @endif
            </div>
        @empty
            <div style="text-align:center;padding:2rem;color:var(--slate)">
                <div style="font-size:2rem;margin-bottom:.5rem">🎉</div>
                <p style="font-size:.88rem">No pending approvals!</p>
            </div>
        @endforelse
    </div>

    {{-- ── Recent Updates ── --}}
    <div class="card">
        <div class="flex justify-between items-center mb-2">
            <h2 class="section-title" style="margin-bottom:0;border:none">🔄 Recent Updates</h2>
            <a href="{{ route('admin.directory') }}" class="btn btn-sm btn-outline">Full Directory</a>
        </div>

        @forelse($recentUpdates as $alumni)
            <div class="update-item">
                <div class="update-avatar">
                    {{ strtoupper(substr($alumni->first_name, 0, 1)) }}
                </div>
                <div style="flex:1;min-width:0">
                    <div class="update-name">{{ $alumni->full_name }}</div>
                    <div class="update-meta">
                        Batch {{ $alumni->graduation_year }}
                        @if($alumni->current_job) · {{ $alumni->current_job }} @endif
                        @if($alumni->city) · {{ $alumni->city }} @endif
                    </div>
                </div>
                <span style="font-size:.72rem;color:#aaa;white-space:nowrap">
                    {{ $alumni->updated_at->diffForHumans() }}
                </span>
            </div>
        @empty
            <p class="text-muted text-sm" style="padding:.5rem 0">No recent updates.</p>
        @endforelse
    </div>
</div>

{{-- ── Upcoming Events ── --}}
@if($upcomingEvents->isNotEmpty())
<div class="mt-4">
    <div class="flex justify-between items-center mb-2">
        <h2 class="section-title" style="margin-bottom:0;border:none">📅 Upcoming Events</h2>
        <a href="{{ route('events.index') }}" class="btn btn-sm btn-outline">Manage Events</a>
    </div>
    <div class="card" style="padding:0">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>Event</th><th>Date</th><th>Venue</th><th>RSVPs</th><th></th></tr>
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
</div>
@endif

@endsection