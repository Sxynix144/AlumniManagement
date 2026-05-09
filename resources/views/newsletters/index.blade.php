@extends('layouts.app')
@section('title', 'Newsletters')

@push('styles')
<style>
.nl-hero { background:var(--navy); border-radius:12px; padding:2.5rem 3rem; margin-bottom:2.5rem; }
.nl-hero h1 { font-family:'Playfair Display',serif; color:var(--white); font-size:2rem; margin-bottom:.4rem; }
.nl-hero p  { color:rgba(255,255,255,.6); }
.nl-meta    { display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem; }
.nl-meta h2 { font-family:'Playfair Display',serif; font-size:1.4rem; }
.nl-meta span { font-size:.85rem; color:var(--slate); }

.nl-grid { display:grid; grid-template-columns:1fr 1fr; gap:1.2rem; }
@media(max-width:680px){ .nl-grid{ grid-template-columns:1fr; } }

.nl-card {
    background:var(--white);
    border-radius:10px;
    border:1px solid var(--light);
    display:flex; gap:1.2rem; align-items:flex-start;
    padding:1.4rem;
    transition:transform .2s, box-shadow .2s;
    text-decoration:none; color:inherit;
}
.nl-card:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(15,32,68,.1); }
.nl-cover {
    width:80px; height:80px; border-radius:8px;
    background:var(--cream);
    display:flex; align-items:center; justify-content:center;
    flex-shrink:0; overflow:hidden;
    border:1px solid var(--light);
}
.nl-cover img  { width:100%; height:100%; object-fit:cover; }
.nl-cover-icon { font-size:2rem; }
.nl-card h3    { font-size:.95rem; font-weight:700; color:var(--navy); margin-bottom:.4rem; line-height:1.4; }
.nl-card p     { font-size:.82rem; color:var(--slate); line-height:1.5; margin-bottom:.6rem; }
.nl-card-foot  { display:flex; justify-content:space-between; align-items:center; }
.nl-date       { font-size:.75rem; color:var(--slate); }
.nl-read        { color:var(--navy); font-weight:700; font-size:.8rem; }
</style>
@endpush

@section('content')
<div class="nl-hero">
    <span style="display:inline-block;background:rgba(200,149,58,.2);color:var(--gold2);border-radius:99px;padding:.2rem .8rem;font-size:.72rem;letter-spacing:.1em;text-transform:uppercase;margin-bottom:.75rem">Newsletters</span>
    <h1>Latest alumni newsletters</h1>
    <p>Browse recent updates, achievements, and stories from the alumni community.</p>
</div>

<div class="nl-meta">
    <h2>All Issues</h2>
    <span>Showing {{ $newsletters->count() }} of {{ $newsletters->total() }}</span>
</div>

<div class="nl-grid">
    @forelse($newsletters as $nl)
        <a href="{{ route('newsletters.show', $nl) }}" class="nl-card">
            <div class="nl-cover">
                @if($nl->cover_image)
                    <img src="{{ Storage::url($nl->cover_image) }}" alt="">
                @else
                    <span class="nl-cover-icon">📄</span>
                @endif
            </div>
            <div style="flex:1;min-width:0">
                <h3>{{ $nl->title }}</h3>
                @if($nl->description)
                    <p>{{ Str::limit($nl->description, 100) }}</p>
                @endif
                <div class="nl-card-foot">
                    <span class="nl-date">📅 Published: {{ $nl->published_at->format('M. d, Y') }}</span>
                    <span class="nl-read">Read More →</span>
                </div>
            </div>
        </a>
    @empty
        <div class="card text-center" style="grid-column:1/-1;padding:3rem">
            <p class="text-muted">No newsletters published yet.</p>
        </div>
    @endforelse
</div>

<div class="pagination mt-3">{{ $newsletters->links() }}</div>
@endsection
