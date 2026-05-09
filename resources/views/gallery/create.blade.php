@extends('layouts.app')
@section('title', 'Create Album')

@section('content')
<div class="page-header">
    <h1>Create Photo Album</h1>
    <p>Upload photos from an alumni event or reunion.</p>
</div>

<div class="card" style="max-width:680px">
    <form method="POST" action="{{ route('admin.gallery.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label>Album Title *</label>
            <input type="text" name="title" value="{{ old('title') }}"
                   placeholder="e.g. Grand Homecoming 2025 · Batch Night" required>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="2"
                placeholder="Brief description of the event...">{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label>Photos * (select multiple)</label>
            <input type="file" name="photos[]" accept="image/*" multiple required
                   id="photo-input" onchange="previewPhotos(this)">
            @error('photos')<div class="form-error">{{ $message }}</div>@enderror
            @error('photos.*')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div id="preview-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(80px,1fr));gap:.4rem;margin-bottom:1rem"></div>

        <div class="form-group" style="display:flex;align-items:center;gap:.75rem">
            <input type="hidden" name="is_published" value="0">
            <input type="checkbox" name="is_published" value="1" id="pub" checked
                   style="width:16px;height:16px;accent-color:var(--gold)">
            <label for="pub" style="text-transform:none;letter-spacing:0;font-size:.9rem;cursor:pointer">
                Make album public immediately
            </label>
        </div>

        <button type="submit" class="btn btn-gold w-full" style="padding:.85rem;font-size:1rem">
            Upload Album →
        </button>
    </form>
</div>

@push('scripts')
<script>
function previewPhotos(input) {
    const grid = document.getElementById('preview-grid');
    grid.innerHTML = '';
    Array.from(input.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.cssText = 'width:100%;aspect-ratio:1;object-fit:cover;border-radius:6px;border:2px solid var(--light)';
            grid.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
}
</script>
@endpush
@endsection
