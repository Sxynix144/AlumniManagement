@extends('layouts.app')
@section('title', $announcement->title)

@section('content')
<div style="max-width:760px">
    <a href="{{ route('announcements.index') }}" class="btn btn-outline btn-sm mb-2">← Back to Announcements</a>

    <div class="card mt-2" style="padding:2.5rem">
        <span class="badge badge-published" style="margin-bottom:1rem;font-size:.75rem">{{ $announcement->category }}</span>
        <h1 style="font-family:'Playfair Display',serif;font-size:2rem;color:var(--navy);line-height:1.3;margin-bottom:1rem">
            {{ $announcement->title }}
        </h1>

        <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:2rem;padding-bottom:1.5rem;border-bottom:1px solid var(--light)">
            <div style="width:38px;height:38px;border-radius:50%;background:var(--navy);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700">
                {{ strtoupper(substr($announcement->author->name, 0, 1)) }}
            </div>
            <div>
                <div style="font-weight:600;font-size:.9rem">{{ $announcement->author->name }}</div>
                <div style="color:var(--slate);font-size:.8rem">
                    Administrator · {{ $announcement->published_at->format('F j, Y') }}
                </div>
            </div>
        </div>

        <div style="line-height:1.9;color:var(--navy);font-size:.97rem">
            {!! nl2br(e($announcement->body)) !!}
        </div>
    </div>
</div>

@if($recent->isNotEmpty())
<div style="max-width:760px;margin-top:2rem">
    <h2 class="section-title">More Announcements</h2>
    <div style="display:grid;gap:.8rem">
        @foreach($recent as $r)
            <a href="{{ route('announcements.show', $r) }}"
               style="display:flex;justify-content:space-between;align-items:center;background:var(--white);border:1px solid var(--light);border-radius:8px;padding:1rem 1.2rem;text-decoration:none;color:inherit;transition:box-shadow .15s"
               onmouseover="this.style.boxShadow='0 4px 12px rgba(15,32,68,.1)'"
               onmouseout="this.style.boxShadow='none'">
                <div>
                    <div style="font-weight:600;font-size:.9rem;color:var(--navy)">{{ $r->title }}</div>
                    <div style="font-size:.78rem;color:var(--slate);margin-top:.2rem">{{ $r->published_at->format('M d, Y') }}</div>
                </div>
                <span style="color:var(--gold);font-weight:700;font-size:.85rem">→</span>
            </a>
        @endforeach
    </div>
</div>
@endif
@endsection
