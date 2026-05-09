@extends('layouts.app')
@section('title', isset($announcement) ? 'Edit Announcement' : 'New Announcement')

@section('content')
<div class="page-header">
    <h1>{{ isset($announcement) ? 'Edit Announcement' : 'New Announcement' }}</h1>
</div>

<div class="card" style="max-width:760px">
    <form method="POST"
          action="{{ isset($announcement) ? route('admin.announcements.update', $announcement) : route('admin.announcements.store') }}">
        @csrf
        @if(isset($announcement)) @method('PATCH') @endif

        <div class="form-group">
            <label>Title *</label>
            <input type="text" name="title" value="{{ old('title', $announcement->title ?? '') }}" required>
            @error('title')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label>Category</label>
            <select name="category">
                @foreach(['General','Event','Office Notice','Opportunity','Deadline'] as $cat)
                    <option value="{{ $cat }}"
                        {{ old('category', $announcement->category ?? 'General') === $cat ? 'selected' : '' }}>
                        {{ $cat }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Body *</label>
            <textarea name="body" rows="12" required
                style="font-family:'DM Sans',sans-serif;line-height:1.7">{{ old('body', $announcement->body ?? '') }}</textarea>
            @error('body')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group" style="display:flex;align-items:center;gap:.75rem">
            <input type="hidden" name="is_published" value="0">
            <input type="checkbox" name="is_published" value="1" id="pub"
                {{ old('is_published', $announcement->is_published ?? false) ? 'checked' : '' }}
                style="width:16px;height:16px;accent-color:var(--gold)">
            <label for="pub" style="text-transform:none;letter-spacing:0;font-size:.9rem;cursor:pointer">
                Publish immediately
            </label>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="btn btn-gold" style="flex:1;padding:.8rem">
                {{ isset($announcement) ? 'Update' : 'Publish' }} Announcement
            </button>
            <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline" style="flex:1;text-align:center;padding:.8rem">Cancel</a>
        </div>
    </form>
</div>
@endsection
