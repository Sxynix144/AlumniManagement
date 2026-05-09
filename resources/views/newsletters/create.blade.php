@extends('layouts.app')
@section('title', 'Upload Newsletter')

@section('content')
<div class="page-header">
    <h1>Upload Newsletter</h1>
    <p>Publish a new alumni newsletter or e-bulletin.</p>
</div>

<div class="card" style="max-width:680px">
    <form method="POST" action="{{ route('admin.newsletters.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label>Newsletter Title *</label>
            <input type="text" name="title" value="{{ old('title') }}"
                   placeholder="e.g. 5th Alumni E-Newsletter" required>
            @error('title')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label>Short Description</label>
            <textarea name="description" rows="3"
                placeholder="Once a UMian, always a UMian!...">{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label>PDF File (max 20 MB)</label>
            <input type="file" name="pdf_file" accept=".pdf">
            @error('pdf_file')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label>Cover Image (optional)</label>
            <input type="file" name="cover_image" accept="image/*">
            @error('cover_image')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group" style="display:flex;align-items:center;gap:.75rem">
            <input type="hidden" name="is_published" value="0">
            <input type="checkbox" name="is_published" value="1" id="pub"
                   style="width:16px;height:16px;accent-color:var(--gold)">
            <label for="pub" style="text-transform:none;letter-spacing:0;font-size:.9rem;cursor:pointer">
                Publish immediately
            </label>
        </div>

        <button type="submit" class="btn btn-gold w-full" style="padding:.85rem;font-size:1rem">
            Upload & Publish →
        </button>
    </form>
</div>
@endsection
