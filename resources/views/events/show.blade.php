@extends('layouts.app')
@section('title', $event->title)

@section('content')
<div class="page-header flex justify-between items-center">
    <div>
        <h1>{{ $event->title }}</h1>
        <p>
            📅 {{ $event->event_date->format('F j, Y · g:i A') }}
            @if($event->venue) · 📍 {{ $event->venue }} @endif
        </p>
    </div>
    <div class="flex gap-2">
        <span class="badge badge-{{ $event->status }}">{{ ucfirst($event->status) }}</span>
        <a href="{{ route('events.edit', $event) }}" class="btn btn-sm btn-outline">Edit</a>
    </div>
</div>

{{-- RSVP Stats --}}
<div class="stats-grid" style="margin-bottom:1.5rem">
    <div class="stat-card">
        <div class="stat-value">{{ $rsvpStats['invited'] }}</div>
        <div class="stat-label">Invitations Sent</div>
    </div>
    <div class="stat-card" style="border-top-color:#9ae6b4">
        <div class="stat-value">{{ $rsvpStats['attending'] }}</div>
        <div class="stat-label">Attending</div>
    </div>
    <div class="stat-card" style="border-top-color:#fbd38d">
        <div class="stat-value">{{ $rsvpStats['maybe'] }}</div>
        <div class="stat-label">Maybe</div>
    </div>
    <div class="stat-card" style="border-top-color:#fc8181">
        <div class="stat-value">{{ $rsvpStats['not_attending'] }}</div>
        <div class="stat-label">Declined</div>
    </div>
</div>

<div class="two-col" style="align-items:start">

    {{-- Event Details + Actions --}}
    <div>
        @if($event->description)
        <div class="card mb-2">
            <h2 class="section-title">About this Event</h2>
            <p style="line-height:1.7;color:var(--slate)">{{ $event->description }}</p>
        </div>
        @endif

        <div class="card mb-2">
            <h2 class="section-title">Target Batches</h2>
            <div class="flex gap-1" style="flex-wrap:wrap">
                @foreach($event->batches->sortBy('graduation_year') as $batch)
                    <span class="badge badge-published" style="font-size:.85rem;padding:.35rem .75rem">
                        Batch {{ $batch->graduation_year }}
                    </span>
                @endforeach
            </div>
        </div>

        @if($event->isPublished())
        <div class="card" style="border-top:4px solid var(--gold)">
            <h2 class="section-title">📬 Send Invitations</h2>
            <p class="text-sm text-muted" style="margin-bottom:1rem">
                This will send email invitations to all active alumni in the selected batches
                who haven't been invited yet.
                <strong>{{ $rsvpStats['invited'] }}</strong> invitations already sent.
            </p>
            <form method="POST" action="{{ route('events.sendInvitations', $event) }}">
                @csrf
                <button type="submit" class="btn btn-primary w-full"
                    onclick="return confirm('Send invitations to eligible alumni now?')">
                    📧 Send Invitations Now
                </button>
            </form>
        </div>
        @else
            <div class="flash flash-error">
                ℹ️ Publish this event first before sending invitations.
            </div>
        @endif
    </div>

    {{-- Attendees List --}}
    <div class="card">
        <div class="flex justify-between items-center mb-2">
            <h2 class="section-title" style="margin-bottom:0;border:none">
                ✅ Confirmed Attendees ({{ $attendees->count() }})
            </h2>
            @if($attendees->isNotEmpty())
                <a href="{{ route('events.exportAttendees', $event) }}" class="btn btn-sm btn-gold">
                    ⬇ Export
                </a>
            @endif
        </div>

        @forelse($attendees as $a)
            <div style="padding:.65rem 0;border-bottom:1px solid var(--light)">
                <strong>{{ $a->full_name }}</strong>
                <span class="text-muted text-sm"> — Batch {{ $a->graduation_year }}</span>
                @if($a->current_job)
                    <div class="text-sm text-muted">{{ $a->current_job }} @if($a->company) @ {{ $a->company }} @endif</div>
                @endif
            </div>
        @empty
            <p class="text-muted text-sm" style="padding:.5rem 0">No RSVPs yet.</p>
        @endforelse
    </div>
</div>
@endsection
