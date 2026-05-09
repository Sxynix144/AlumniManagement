@extends('layouts.app')
@section('title', 'Events')

@section('content')
<div class="page-header flex justify-between items-center">
    <div>
        <h1>Events</h1>
        <p>Manage alumni reunions, homecomings, and gatherings.</p>
    </div>
    <a href="{{ route('events.create') }}" class="btn btn-gold">+ Create Event</a>
</div>

<div class="card" style="padding:0">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Event</th><th>Date</th><th>Venue</th><th>Target Batches</th>
                    <th>Invited</th><th>Attending</th><th>Status</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($events as $event)
                    <tr>
                        <td><strong>{{ $event->title }}</strong></td>
                        <td>{{ $event->event_date->format('M j, Y') }}</td>
                        <td>{{ $event->venue ?? '—' }}</td>
                        <td>
                            @foreach($event->batches->pluck('graduation_year')->sort() as $yr)
                                <span class="badge badge-draft" style="margin:.1rem">{{ $yr }}</span>
                            @endforeach
                        </td>
                        <td>{{ $event->invitations->count() }}</td>
                        <td>{{ $event->rsvps->where('status','attending')->count() }}</td>
                        <td><span class="badge badge-{{ $event->status }}">{{ ucfirst($event->status) }}</span></td>
                        <td>
                            <div class="flex gap-1">
                                <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-outline">View</a>
                                <a href="{{ route('events.edit', $event) }}" class="btn btn-sm btn-outline">Edit</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted" style="padding:2rem">
                            No events yet. <a href="{{ route('events.create') }}">Create your first event →</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="pagination">{{ $events->links() }}</div>
@endsection
