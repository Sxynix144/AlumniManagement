@extends('layouts.app')
@section('title', 'Announcements')

@push('styles')
<style>
.ann-hero {
    background: linear-gradient(135deg, var(--navy) 0%, #8b1a1a 100%);
    border-radius: 12px;
    padding: 3rem;
    margin-bottom: 2.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 2rem;
}
.ann-hero-left h1 { font-family:'Playfair Display',serif; color:var(--white); font-size:2.2rem; margin-bottom:.5rem; }
.ann-hero-left p  { color:rgba(255,255,255,.65); max-width:480px; line-height:1.7; }
.ann-hero-stats   { display:flex; gap:1.5rem; }
.ann-stat { background:rgba(255,255,255,.1); border-radius:8px; padding:.8rem 1.4rem; text-align:center; }
.ann-stat .num { font-family:'Playfair Display',serif; font-size:1.8rem; color:var(--gold2); }
.ann-stat .lbl { font-size:.75rem; color:rgba(255,255,255,.6); text-transform:uppercase; letter-spacing:.07em; }

.info-box {
    background:var(--white);
    border:1px solid var(--light);
    border-radius:10px;
    padding:1.2rem 1.5rem;
    display:flex; align-items:flex-start; gap:1rem;
    margin-bottom:2rem;
}
.info-icon { font-size:1.8rem; flex-shrink:0; }
.info-box h4 { font-size:.95rem; font-weight:700; color:var(--navy); margin-bottom:.25rem; }
.info-box p  { font-size:.83rem; color:var(--slate); line-height:1.5; }
.info-meta   { display:flex; gap:1.5rem; margin-top:.5rem; }
.info-meta-item { font-size:.78rem; color:var(--slate); }
.info-meta-item strong { display:block; font-size:.7rem; text-transform:uppercase; letter-spacing:.07em; color:#999; }

.filter-bar { display:flex; gap:.5rem; flex-wrap:wrap; margin-bottom:2rem; align-items:center; }
.filter-chip {
    padding:.35rem .9rem;
    border-radius:99px;
    font-size:.8rem;
    font-weight:600;
    text-decoration:none;
    border:1.5px solid var(--light);
    color:var(--slate);
    transition:all .15s;
}
.filter-chip:hover, .filter-chip.active {
    background:var(--navy); color:var(--white); border-color:var(--navy);
}

.ann-grid { display:grid; grid-template-columns:1fr 1fr; gap:1.2rem; margin-bottom:2rem; }
@media(max-width:720px){ .ann-grid { grid-template-columns:1fr; } }

.ann-card {
    background:var(--white);
    border-radius:10px;
    border:1px solid var(--light);
    padding:1.5rem;
    transition:transform .2s, box-shadow .2s;
    position:relative;
    text-decoration:none;
    color:inherit;
    display:flex;
    flex-direction:column;
}
.ann-card:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(15,32,68,.12); }
.ann-card-cat {
    display:inline-block;
    background:var(--cream);
    color:var(--navy);
    font-size:.7rem;
    font-weight:700;
    text-transform:uppercase;
    letter-spacing:.08em;
    padding:.2rem .6rem;
    border-radius:99px;
    margin-bottom:.8rem;
}
.ann-card h3 { font-size:1rem; font-weight:700; color:var(--navy); margin-bottom:.6rem; line-height:1.4; }
.ann-card p  { font-size:.85rem; color:var(--slate); line-height:1.6; flex:1; }
.ann-card-footer {
    display:flex; justify-content:space-between; align-items:center;
    margin-top:1rem; padding-top:.8rem;
    border-top:1px solid var(--light);
    font-size:.78rem; color:var(--slate);
}
.ann-read-more { color:var(--navy); font-weight:700; font-size:.8rem; }
</style>
@endpush

@section('content')
{{-- Hero --}}
<div class="ann-hero">
    <div class="ann-hero-left">
        <div style="display:inline-block;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);border-radius:99px;padding:.25rem .85rem;font-size:.75rem;color:rgba(255,255,255,.8);text-transform:uppercase;letter-spacing:.1em;margin-bottom:.75rem;">
            📢 Official alumni updates
        </div>
        <h1>Announcements that keep<br>alumni in sync.</h1>
        <p>Review official notices, upcoming activities, service reminders, and opportunities from the alumni office in one place.</p>
    </div>
    <div>
        <div class="info-box" style="background:rgba(255,255,255,.95);max-width:300px">
            <div class="info-icon">📣</div>
            <div>
                <h4>STAY INFORMED — One feed for official updates</h4>
                <p>Announcements cover events, deadlines, office notices, and opportunities published by administrators.</p>
                <div class="info-meta">
                    <div class="info-meta-item"><strong>SOURCE</strong>Alumni Office</div>
                    <div class="info-meta-item"><strong>FORMAT</strong>Quick summaries</div>
                </div>
            </div>
        </div>
        <div class="ann-hero-stats">
            <div class="ann-stat">
                <div class="num">{{ $announcements->total() }}</div>
                <div class="lbl">Total Posts</div>
            </div>
            <div class="ann-stat">
                <div class="num">{{ $announcements->count() }}</div>
                <div class="lbl">Showing</div>
            </div>
        </div>
    </div>
</div>

{{-- Category Filter --}}
<div class="filter-bar">
    <span style="font-size:.82rem;font-weight:600;color:var(--slate)">Filter:</span>
    <a href="{{ route('announcements.index') }}"
       class="filter-chip {{ !request('category') ? 'active' : '' }}">All</a>
    @foreach($categories as $cat)
        <a href="{{ route('announcements.index', ['category' => $cat]) }}"
           class="filter-chip {{ request('category') === $cat ? 'active' : '' }}">{{ $cat }}</a>
    @endforeach
</div>

{{-- Announcement Feed --}}
<h2 class="section-title">Announcement feed
    <span style="font-size:.8rem;font-weight:400;color:var(--slate);margin-left:.5rem">
        Browse the published updates in chronological order.
    </span>
</h2>

<div class="ann-grid">
    @forelse($announcements as $a)
        <a href="{{ route('announcements.show', $a) }}" class="ann-card">
            <span class="ann-card-cat">{{ $a->category }}</span>
            <h3>{{ $a->title }}</h3>
            <p>{{ $a->excerpt }}</p>
            <div class="ann-card-footer">
                <div>
                    <div style="display:flex;align-items:center;gap:.4rem">
                        <div style="width:24px;height:24px;border-radius:50%;background:var(--navy);display:flex;align-items:center;justify-content:center;color:#fff;font-size:.65rem;font-weight:700">
                            {{ strtoupper(substr($a->author->name, 0, 1)) }}
                        </div>
                        <span>{{ $a->author->name }}</span>
                    </div>
                    <div style="color:#aaa;margin-top:.2rem;font-size:.72rem">
                        📅 {{ $a->published_at->format('M d, Y') }}
                    </div>
                </div>
                <span class="ann-read-more">Read more →</span>
            </div>
        </a>
    @empty
        <div class="card text-center" style="grid-column:1/-1;padding:3rem">
            <p class="text-muted">No announcements yet.</p>
        </div>
    @endforelse
</div>

<div class="pagination">{{ $announcements->links() }}</div>
@endsection
