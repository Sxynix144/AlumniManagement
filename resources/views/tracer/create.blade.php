@extends('layouts.app')
@section('title', 'Create Tracer Survey')

@section('content')
<div class="page-header">
    <h1>Create Tracer Survey</h1>
    <p>Set up a new tracer study survey for CHED accreditation requirements.</p>
</div>

<div class="card" style="max-width:640px">
    <form method="POST" action="{{ route('admin.tracer.store') }}">
        @csrf

        <div class="form-group">
            <label>Survey Title *</label>
            <input type="text" name="title" value="{{ old('title') }}"
                   placeholder="e.g. Class of 2023 Tracer Study" required>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="3"
                placeholder="Brief intro shown to alumni before they answer...">{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label>Target: Alumni who graduated how many years ago?</label>
            <select name="target_years_after">
                @for($i=1;$i<=5;$i++)
                    <option value="{{ $i }}" {{ old('target_years_after',1) == $i ? 'selected':'' }}>
                        {{ $i }} year{{ $i>1?'s':'' }} after graduation
                    </option>
                @endfor
            </select>
        </div>

        <div class="form-group" style="display:flex;align-items:center;gap:.75rem">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" id="active" checked
                   style="width:16px;height:16px;accent-color:var(--gold)">
            <label for="active" style="text-transform:none;letter-spacing:0;font-size:.9rem;cursor:pointer">
                Make this the active survey (alumni can answer it now)
            </label>
        </div>

        <button type="submit" class="btn btn-gold w-full" style="padding:.85rem;font-size:1rem">
            Create Survey →
        </button>
    </form>
</div>
@endsection
