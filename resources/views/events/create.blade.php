@extends('layouts.app')
@section('title', 'Create Event')

@section('content')
<div class="page-header">
    <h1>Create New Event</h1>
    <p>Set up a reunion, homecoming, or alumni gathering and invite target batches.</p>
</div>

<div class="card" style="max-width:700px">
    <form method="POST" action="{{ route('events.store') }}">
        @csrf

        <div class="form-group">
            <label>Event Title *</label>
            <input type="text" name="title" value="{{ old('title') }}"
                   placeholder="e.g. Grand Alumni Homecoming 2025" required>
            @error('title')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="4"
                placeholder="Tell alumni what to expect at this event...">{{ old('description') }}</textarea>
        </div>

        <div class="two-col">
            <div class="form-group">
                <label>Event Date & Time *</label>
                <input type="datetime-local" name="event_date" value="{{ old('event_date') }}" required>
                @error('event_date')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Venue / Location</label>
                <input type="text" name="venue" value="{{ old('venue') }}"
                       placeholder="e.g. University Gymnasium">
            </div>
        </div>

        <div class="form-group">
            <label>Target Graduation Batches *</label>
            <p class="text-sm text-muted mb-1">Hold Ctrl / Cmd to select multiple years.</p>
            <select name="graduation_years[]" multiple required
                    style="height:180px">
                @foreach($availableYears as $yr)
                    <option value="{{ $yr }}"
                        {{ in_array($yr, old('graduation_years', [])) ? 'selected' : '' }}>
                        Batch {{ $yr }}
                    </option>
                @endforeach
            </select>
            @error('graduation_years')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="status">
                <option value="draft" {{ old('status','draft') === 'draft' ? 'selected' : '' }}>
                    Draft — save without sending
                </option>
                <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>
                    Published — ready to invite alumni
                </option>
            </select>
        </div>

        <button type="submit" class="btn btn-gold w-full" style="font-size:1rem;padding:.8rem">
            Create Event →
        </button>
    </form>
</div>
@endsection
