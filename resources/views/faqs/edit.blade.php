@extends('layouts.app')
@section('title', isset($faq) ? 'Edit FAQ' : 'Add FAQ')

@section('content')
<div class="page-header">
    <h1>{{ isset($faq) ? 'Edit FAQ' : 'Add New FAQ' }}</h1>
</div>

<div class="card" style="max-width:700px">
    <form method="POST"
          action="{{ isset($faq) ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}">
        @csrf
        @if(isset($faq)) @method('PATCH') @endif

        <div class="form-group">
            <label>Question *</label>
            <input type="text" name="question"
                   value="{{ old('question', $faq->question ?? '') }}"
                   placeholder="e.g. How do I request my Alumni ID?" required>
            @error('question')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label>Answer *</label>
            <textarea name="answer" rows="6" required
                placeholder="Provide a clear, helpful answer...">{{ old('answer', $faq->answer ?? '') }}</textarea>
            @error('answer')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="two-col">
            <div class="form-group">
                <label>Category</label>
                <select name="category">
                    @foreach(['General','Alumni ID','Documents','Events','Membership','Other'] as $cat)
                        <option value="{{ $cat }}"
                            {{ old('category', $faq->category ?? 'General') === $cat ? 'selected' : '' }}>
                            {{ $cat }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Sort Order (lower = first)</label>
                <input type="number" name="sort_order"
                       value="{{ old('sort_order', $faq->sort_order ?? 0) }}" min="0">
            </div>
        </div>

        <div class="form-group" style="display:flex;align-items:center;gap:.75rem">
            <input type="hidden" name="is_published" value="0">
            <input type="checkbox" name="is_published" value="1" id="pub"
                   {{ old('is_published', $faq->is_published ?? true) ? 'checked' : '' }}
                   style="width:16px;height:16px;accent-color:var(--gold)">
            <label for="pub" style="text-transform:none;letter-spacing:0;font-size:.9rem;cursor:pointer">
                Publish (visible to all alumni)
            </label>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="btn btn-gold" style="flex:1;padding:.8rem">
                {{ isset($faq) ? 'Update FAQ' : 'Add FAQ' }}
            </button>
            <a href="{{ route('admin.faqs.index') }}" class="btn btn-outline"
               style="flex:1;text-align:center;padding:.8rem">Cancel</a>
        </div>
    </form>
</div>
@endsection
