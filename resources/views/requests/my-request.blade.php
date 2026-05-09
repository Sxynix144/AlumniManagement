@extends('layouts.app')
@section('title', 'My Requests')

@push('styles')
<style>
.req-type-grid {
    display:grid;
    grid-template-columns:repeat(auto-fill, minmax(200px, 1fr));
    gap:1rem;
    margin-bottom:2rem;
}
.req-type-card {
    background:var(--white);
    border:1.5px solid var(--light);
    border-radius:10px;
    padding:1.3rem;
    cursor:pointer;
    transition:all .15s;
    text-align:center;
}
.req-type-card:hover, .req-type-card.selected {
    border-color:var(--gold);
    box-shadow:0 0 0 3px rgba(200,149,58,.15);
    background:var(--cream);
}
.req-type-card .icon { font-size:2rem; margin-bottom:.5rem; }
.req-type-card .label { font-weight:700; font-size:.88rem; color:var(--navy); }
.req-type-card .desc  { font-size:.75rem; color:var(--slate); margin-top:.25rem; }

.timeline { position:relative; padding-left:2rem; }
.timeline::before {
    content:''; position:absolute; left:.5rem; top:0; bottom:0;
    width:2px; background:var(--light);
}
.timeline-item { position:relative; padding-bottom:1.5rem; }
.timeline-dot {
    position:absolute; left:-1.62rem; top:.25rem;
    width:14px; height:14px; border-radius:50%;
    background:var(--gold); border:2px solid var(--white);
    box-shadow:0 0 0 2px var(--gold);
}
.timeline-dot.pending    { background:#f6ad55; box-shadow:0 0 0 2px #f6ad55; }
.timeline-dot.processing { background:#63b3ed; box-shadow:0 0 0 2px #63b3ed; }
.timeline-dot.ready      { background:#68d391; box-shadow:0 0 0 2px #68d391; }
.timeline-dot.released   { background:#48bb78; box-shadow:0 0 0 2px #48bb78; }
.timeline-dot.rejected   { background:#fc8181; box-shadow:0 0 0 2px #fc8181; }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1>My Requests</h1>
    <p>Request alumni documents, IDs, and yearbooks. Track their status in real time.</p>
</div>

@if($alumni->status !== 'active')
    <div class="flash flash-error" style="margin-bottom:2rem">
        ⚠️ Your account is pending approval. Document requests are available once your profile is active.
    </div>
@else

{{-- ── New Request Form ────────────────────────────────────── --}}
<div class="card mb-4">
    <h2 class="section-title">📋 Start a New Request</h2>

    <form method="POST" action="{{ route('requests.store') }}" id="req-form">
        @csrf

        <div class="req-type-grid">
            @php
            $types = [
                'alumni_id'   => ['🪪','Alumni ID Card',       'Request your official alumni identification card.'],
                'yearbook'    => ['📖','Batch Yearbook',        'Get your graduation yearbook delivered or claimed.'],
                'transcript'  => ['📄','Transcript of Records', 'Official academic record for employment or further study.'],
                'certificate' => ['🏅','Certificate / Diploma', 'Replacement diploma or certificate of graduation.'],
                'other'       => ['📋','Other Request',         'Any other document or assistance from the alumni office.'],
            ];
            @endphp

            @foreach($types as $val => [$icon, $label, $desc])
                <div class="req-type-card" onclick="selectType('{{ $val }}', this)">
                    <div class="icon">{{ $icon }}</div>
                    <div class="label">{{ $label }}</div>
                    <div class="desc">{{ $desc }}</div>
                </div>
            @endforeach
        </div>

        <input type="hidden" name="type" id="req-type" value="{{ old('type') }}" required>
        @error('type')<div class="form-error mb-1">Please select a request type.</div>@enderror

        <div class="form-group">
            <label>Additional Notes (optional)</label>
            <textarea name="notes" rows="3"
                placeholder="Any special instructions, delivery address, or purpose...">{{ old('notes') }}</textarea>
        </div>

        <button type="submit" class="btn btn-gold" style="font-size:1rem;padding:.75rem 2rem">
            Submit Request →
        </button>
    </form>
</div>

@endif

{{-- ── Request History ─────────────────────────────────────── --}}
<h2 class="section-title">📂 Request History</h2>

@forelse($requests as $req)
    <div class="card mb-2" style="padding:1.2rem 1.5rem">
        <div class="flex justify-between items-center" style="flex-wrap:wrap;gap:.5rem;margin-bottom:.5rem">
            <div>
                <strong style="font-size:1rem">{{ $req->type_label }}</strong>
                <span class="text-muted text-sm" style="margin-left:.5rem">
                    Submitted {{ $req->created_at->format('M j, Y') }}
                </span>
            </div>
            <span class="badge {{ $req->status_color }}" style="font-size:.78rem">
                {{ ucfirst($req->status) }}
            </span>
        </div>

        @if($req->notes)
            <p class="text-sm text-muted" style="margin-bottom:.5rem">
                📝 {{ $req->notes }}
            </p>
        @endif

        @if($req->admin_remarks)
            <div style="background:var(--cream);border-left:3px solid var(--gold);padding:.6rem .9rem;border-radius:4px;font-size:.85rem;margin-top:.5rem">
                <strong>Admin note:</strong> {{ $req->admin_remarks }}
            </div>
        @endif

        @if($req->released_at)
            <p class="text-sm" style="color:var(--success);margin-top:.4rem">
                ✅ Released on {{ $req->released_at->format('M j, Y') }}
            </p>
        @endif

        {{-- Status progress bar --}}
        @php
        $steps   = ['pending','processing','ready','released'];
        $idx     = array_search($req->status, $steps);
        $width   = $req->status === 'rejected' ? 100 : (($idx + 1) / count($steps) * 100);
        $barColor= $req->status === 'rejected' ? '#fc8181' : 'var(--gold)';
        @endphp
        <div style="margin-top:.75rem;background:var(--light);border-radius:99px;height:6px;overflow:hidden">
            <div style="width:{{ $width }}%;height:100%;background:{{ $barColor }};transition:width .4s;border-radius:99px"></div>
        </div>
        <div style="display:flex;justify-content:space-between;margin-top:.3rem;font-size:.65rem;color:var(--slate);text-transform:uppercase;letter-spacing:.06em">
            <span>Pending</span><span>Processing</span><span>Ready</span><span>Released</span>
        </div>
    </div>
@empty
    <div class="card text-center" style="padding:3rem">
        <div style="font-size:3rem;margin-bottom:1rem">📭</div>
        <p class="text-muted">You haven't submitted any requests yet.</p>
    </div>
@endforelse

@push('scripts')
<script>
function selectType(val, el) {
    document.querySelectorAll('.req-type-card').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('req-type').value = val;
}
// Restore selected on validation error
const saved = document.getElementById('req-type').value;
if (saved) {
    document.querySelectorAll('.req-type-card').forEach(c => {
        if (c.querySelector('.label').textContent.toLowerCase().includes(saved.replace('_',' '))) {
            c.classList.add('selected');
        }
    });
}
</script>
@endpush
@endsection
