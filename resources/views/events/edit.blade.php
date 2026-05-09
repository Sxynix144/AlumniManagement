@extends('layouts.app')
@section('title', 'Edit Event')

@section('content')
<div class="page-header">
    <h1>Edit Event</h1>
    <p>Update the details for <strong>{{ $event->title }}</strong>.</p>
</div>

<div class="card" style="max-width:700px">
    <form method="POST" action="{{ route('events.update', $event) }}">
        @csrf @method('PATCH')

        <div class="form-group">
            <label>Event Title *</label>
            <input type="text" name="title" value="{{ old('title', $event->title) }}" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="4">{{ old('description', $event->description) }}</textarea>
        </div>
        <div class="two-col">
            <div class="form-group">
                <label>Event Date & Time *</label>
                <input type="datetime-local" name="event_date"
                    value="{{ old('event_date', $event->event_date->format('Y-m-d\TH:i')) }}" required>
            </div>
            <div class="form-group">
                <label>Venue</label>
                <input type="text" name="venue" value="{{ old('venue', $event->venue) }}">
            </div>
        </div>
        <div class="form-group">
            <label>Target Graduation Batches *</label>
            <p class="text-sm text-muted mb-1">Hold Ctrl / Cmd to select multiple years.</p>
            <select name="graduation_years[]" multiple required style="height:180px">
                @foreach($availableYears as $yr)
                    <option value="{{ $yr }}"
                        {{ in_array($yr, old('graduation_years', $selectedYears)) ? 'selected' : '' }}>
                        Batch {{ $yr }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status">
                @foreach(['draft', 'published', 'cancelled'] as $s)
                    <option value="{{ $s }}" {{ old('status', $event->status) === $s ? 'selected' : '' }}>
                        {{ ucfirst($s) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="btn btn-gold" style="flex:1;padding:.8rem">Save Changes</button>
            <a href="{{ route('events.show', $event) }}" class="btn btn-outline" style="flex:1;padding:.8rem;text-align:center">Cancel</a>
        </div>
    </form>
</div>
@endsection
