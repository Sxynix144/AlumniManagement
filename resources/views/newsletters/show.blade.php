@extends('layouts.app')
@section('title', $newsletter->title)

@section('content')
<div style="max-width:760px">
    <a href="{{ route('newsletters.index') }}" class="btn btn-outline btn-sm mb-2">← Back to Newsletters</a>

    <div class="card mt-2" style="padding:2.5rem">
        @if($newsletter->cover_image)
            <img src="{{ Storage::url($newsletter->cover_image) }}" alt=""
                 style="width:100%;max-height:300px;object-fit:cover;border-radius:8px;margin-bottom:1.5rem">
        @endif

        <h1 style="font-family:'Playfair Display',serif;font-size:1.9rem;margin-bottom:.5rem">{{ $newsletter->title }}</h1>
        <p style="color:var(--slate);font-size:.85rem;margin-bottom:1.5rem">
            📅 {{ $newsletter->published_at->format('F j, Y') }}
        </p>

        @if($newsletter->description)
            <p style="color:var(--slate);line-height:1.7;margin-bottom:1.5rem">{{ $newsletter->description }}</p>
        @endif

        @if($newsletter->file_path)
            <a href="{{ Storage::url($newsletter->file_path) }}" target="_blank"
               class="btn btn-primary" style="font-size:1rem;padding:.85rem 2rem">
                📥 Download PDF
            </a>
        @else
            <p class="text-muted text-sm">No PDF attached to this newsletter.</p>
        @endif
    </div>
</div>
@endsection
