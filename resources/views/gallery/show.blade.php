@extends('layouts.app')
@section('title', $album->title)

@push('styles')
<style>
.photo-grid {
    display:grid;
    grid-template-columns:repeat(auto-fill, minmax(200px, 1fr));
    gap:.75rem;
    margin-top:1.5rem;
}
.photo-item {
    position:relative;
    border-radius:8px;
    overflow:hidden;
    aspect-ratio:1;
    cursor:pointer;
    background:var(--light);
}
.photo-item img { width:100%; height:100%; object-fit:cover; transition:transform .3s; }
.photo-item:hover img { transform:scale(1.06); }
.photo-overlay {
    position:absolute; inset:0;
    background:rgba(15,32,68,0);
    display:flex; align-items:center; justify-content:center;
    transition:background .2s;
    color:#fff; font-size:1.5rem;
    opacity:0;
}
.photo-item:hover .photo-overlay { background:rgba(15,32,68,.4); opacity:1; }

/* Lightbox */
.lightbox {
    display:none; position:fixed; inset:0;
    background:rgba(0,0,0,.9); z-index:1000;
    align-items:center; justify-content:center;
}
.lightbox.active { display:flex; }
.lightbox img { max-width:90vw; max-height:85vh; object-fit:contain; border-radius:4px; }
.lightbox-close {
    position:fixed; top:1.5rem; right:1.5rem;
    color:#fff; font-size:2rem; cursor:pointer; background:none; border:none;
    line-height:1;
}
.lightbox-nav {
    position:fixed; top:50%; transform:translateY(-50%);
    background:rgba(255,255,255,.15); border:none; color:#fff;
    font-size:1.5rem; padding:1rem; cursor:pointer; border-radius:50%;
    transition:background .15s;
}
.lightbox-nav:hover { background:rgba(255,255,255,.3); }
.lightbox-prev { left:1.5rem; }
.lightbox-next { right:1.5rem; }
.lightbox-caption {
    position:fixed; bottom:1.5rem; left:50%; transform:translateX(-50%);
    background:rgba(0,0,0,.5); color:#fff; padding:.5rem 1.2rem;
    border-radius:99px; font-size:.85rem;
}
</style>
@endpush

@section('content')
<a href="{{ route('gallery.index') }}" class="btn btn-outline btn-sm mb-2">← Back to Gallery</a>

<div class="page-header flex justify-between items-center">
    <div>
        <h1>{{ $album->title }}</h1>
        <p>{{ $album->description ?? '' }} · {{ $album->photos->count() }} photos</p>
    </div>
</div>

<div class="photo-grid">
    @foreach($album->photos as $i => $photo)
        <div class="photo-item" onclick="openLightbox({{ $i }})">
            <img src="{{ Storage::url($photo->file_path) }}" alt="{{ $photo->caption }}">
            <div class="photo-overlay">🔍</div>
        </div>
    @endforeach
</div>

{{-- Lightbox --}}
<div class="lightbox" id="lightbox">
    <button class="lightbox-close" onclick="closeLightbox()">✕</button>
    <button class="lightbox-nav lightbox-prev" onclick="changePhoto(-1)">‹</button>
    <img src="" id="lightbox-img" alt="">
    <button class="lightbox-nav lightbox-next" onclick="changePhoto(1)">›</button>
    <div class="lightbox-caption" id="lightbox-caption"></div>
</div>

@push('scripts')
<script>
const photos = @json($album->photos->map(fn($p) => [
    'src'     => Storage::url($p->file_path),
    'caption' => $p->caption ?? '',
]));

let current = 0;

function openLightbox(i) {
    current = i;
    document.getElementById('lightbox').classList.add('active');
    updateLightbox();
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    document.getElementById('lightbox').classList.remove('active');
    document.body.style.overflow = '';
}

function changePhoto(dir) {
    current = (current + dir + photos.length) % photos.length;
    updateLightbox();
}

function updateLightbox() {
    document.getElementById('lightbox-img').src = photos[current].src;
    document.getElementById('lightbox-caption').textContent =
        photos[current].caption || `${current + 1} / ${photos.length}`;
}

document.addEventListener('keydown', (e) => {
    if (!document.getElementById('lightbox').classList.contains('active')) return;
    if (e.key === 'ArrowRight') changePhoto(1);
    if (e.key === 'ArrowLeft')  changePhoto(-1);
    if (e.key === 'Escape')     closeLightbox();
});
</script>
@endpush
@endsection
