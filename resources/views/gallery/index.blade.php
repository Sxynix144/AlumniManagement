@extends('layouts.app')
@section('title', 'Photo Gallery')

@push('styles')
<style>
.gallery-hero { background:var(--navy); border-radius:12px; padding:2.5rem 3rem; margin-bottom:2.5rem; }
.gallery-hero h1 { font-family:'Playfair Display',serif; color:var(--white); font-size:2rem; margin-bottom:.4rem; }
.gallery-hero p  { color:rgba(255,255,255,.6); }

.album-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(260px,1fr)); gap:1.2rem; }
.album-card {
    background:var(--white);
    border-radius:10px;
    overflow:hidden;
    border:1px solid var(--light);
    text-decoration:none;
    color:inherit;
    transition:transform .2s, box-shadow .2s;
    display:block;
}
.album-card:hover { transform:translateY(-4px); box-shadow:0 12px 28px rgba(15,32,68,.14); }
.album-cover {
    width:100%; height:180px;
    object-fit:cover;
    background:var(--cream);
    display:flex; align-items:center; justify-content:center;
    font-size:3rem;
}
.album-cover img { width:100%; height:100%; object-fit:cover; }
.album-info { padding:1rem 1.2rem; }
.album-info h3 { font-weight:700; font-size:.95rem; color:var(--navy); margin-bottom:.3rem; }
.album-info p  { font-size:.8rem; color:var(--slate); margin-bottom:.5rem; }
.album-count   { font-size:.75rem; color:var(--slate); }
</style>
@endpush

@section('content')
<div class="gallery-hero">
    <span style="display:inline-block;background:rgba(200,149,58,.2);color:var(--gold2);border-radius:99px;padding:.2rem .8rem;font-size:.72rem;letter-spacing:.1em;text-transform:uppercase;margin-bottom:.75rem">📸 Photo Gallery</span>
    <h1>Alumni Memories</h1>
    <p>Browse photos from reunions, homecomings, and alumni events through the years.</p>
</div>

<div class="flex justify-between items-center mb-2">
    <h2 class="section-title" style="margin-bottom:0;border:none">All Albums</h2>
    <span class="text-sm text-muted">{{ $albums->total() }} albums</span>
</div>

<div class="album-grid">
    @forelse($albums as $album)
        <a href="{{ route('gallery.show', $album) }}" class="album-card">
            <div class="album-cover">
                @if($album->cover_photo)
                    <img src="{{ Storage::url($album->cover_photo) }}" alt="{{ $album->title }}">
                @else
                    🖼️
                @endif
            </div>
            <div class="album-info">
                <h3>{{ $album->title }}</h3>
                @if($album->description)
                    <p>{{ Str::limit($album->description, 80) }}</p>
                @endif
                <span class="album-count">📷 {{ $album->photos_count }} photos · {{ $album->created_at->format('M Y') }}</span>
            </div>
        </a>
    @empty
        <div class="card text-center" style="grid-column:1/-1;padding:3rem">
            <p class="text-muted">No albums yet.</p>
        </div>
    @endforelse
</div>

<div class="pagination mt-3">{{ $albums->links() }}</div>
@endsection
