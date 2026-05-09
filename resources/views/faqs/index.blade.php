@extends('layouts.app')
@section('title', 'FAQs')

@push('styles')
<style>
.faq-hero { background:var(--navy); border-radius:12px; padding:2.5rem 3rem; margin-bottom:2.5rem; }
.faq-hero h1 { font-family:'Playfair Display',serif; color:var(--white); font-size:2rem; margin-bottom:.4rem; }
.faq-hero p  { color:rgba(255,255,255,.6); }

.faq-search {
    max-width: 500px;
    margin: 0 auto 2.5rem;
    position: relative;
}
.faq-search input {
    width: 100%;
    padding: .85rem 1rem .85rem 3rem;
    border: 2px solid var(--light);
    border-radius: 99px;
    font-size: .95rem;
    font-family: 'DM Sans', sans-serif;
    transition: border-color .15s;
}
.faq-search input:focus { outline: none; border-color: var(--gold); }
.faq-search-icon {
    position: absolute;
    left: 1rem; top: 50%;
    transform: translateY(-50%);
    color: var(--slate);
    font-size: 1.1rem;
    pointer-events: none;
}

.faq-category { margin-bottom: 2.5rem; }
.faq-category-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.3rem;
    color: var(--navy);
    margin-bottom: 1rem;
    padding-bottom: .5rem;
    border-bottom: 2px solid var(--gold);
    display: flex; align-items: center; gap: .5rem;
}

.faq-item {
    border: 1px solid var(--light);
    border-radius: 8px;
    margin-bottom: .5rem;
    overflow: hidden;
    background: var(--white);
}
.faq-question {
    width: 100%;
    background: none;
    border: none;
    padding: 1.1rem 1.3rem;
    text-align: left;
    font-family: 'DM Sans', sans-serif;
    font-size: .95rem;
    font-weight: 600;
    color: var(--navy);
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    transition: background .15s;
}
.faq-question:hover { background: var(--cream); }
.faq-question.open  { background: var(--cream); color: var(--gold); }
.faq-arrow { font-size: 1.1rem; transition: transform .25s; flex-shrink:0; }
.faq-question.open .faq-arrow { transform: rotate(180deg); }
.faq-answer {
    padding: 0 1.3rem;
    max-height: 0;
    overflow: hidden;
    transition: max-height .3s ease, padding .3s;
    font-size: .9rem;
    color: var(--slate);
    line-height: 1.8;
}
.faq-answer.open {
    max-height: 500px;
    padding: 0 1.3rem 1.2rem;
}

.no-results { text-align:center; padding:3rem; color:var(--slate); display:none; }
</style>
@endpush

@section('content')
<div class="faq-hero">
    <span style="display:inline-block;background:rgba(200,149,58,.2);color:var(--gold2);border-radius:99px;padding:.2rem .8rem;font-size:.72rem;letter-spacing:.1em;text-transform:uppercase;margin-bottom:.75rem">❓ Help Center</span>
    <h1>Frequently Asked Questions</h1>
    <p>Find answers to common questions about alumni services, IDs, documents, and events.</p>
</div>

<div class="faq-search">
    <span class="faq-search-icon">🔍</span>
    <input type="text" id="faq-search" placeholder="Search questions..." oninput="filterFaqs(this.value)">
</div>

@forelse($faqs as $category => $items)
    <div class="faq-category" data-category="{{ $category }}">
        <div class="faq-category-title">
            @php
            $catIcons = ['General'=>'📋','Events'=>'📅','Alumni ID'=>'🪪','Documents'=>'📄','Membership'=>'👤','Other'=>'💬'];
            @endphp
            {{ $catIcons[$category] ?? '📌' }} {{ $category }}
        </div>

        @foreach($items as $faq)
            <div class="faq-item" data-question="{{ strtolower($faq->question) }}" data-answer="{{ strtolower($faq->answer) }}">
                <button class="faq-question" onclick="toggleFaq(this)">
                    <span>{{ $faq->question }}</span>
                    <span class="faq-arrow">▾</span>
                </button>
                <div class="faq-answer">
                    {!! nl2br(e($faq->answer)) !!}
                </div>
            </div>
        @endforeach
    </div>
@empty
    <div class="card text-center" style="padding:3rem">
        <p class="text-muted">No FAQs published yet.</p>
    </div>
@endforelse

<div class="no-results" id="no-results">
    <div style="font-size:3rem;margin-bottom:1rem">🤔</div>
    <p>No matching questions found. Try different keywords.</p>
    @auth
        <p class="mt-2 text-sm">Still need help? <a href="{{ route('requests.index') }}" style="color:var(--navy);font-weight:700">Submit a request →</a></p>
    @endauth
</div>

@push('scripts')
<script>
function toggleFaq(btn) {
    const answer = btn.nextElementSibling;
    const isOpen = btn.classList.contains('open');

    // Close all
    document.querySelectorAll('.faq-question.open').forEach(b => {
        b.classList.remove('open');
        b.nextElementSibling.classList.remove('open');
    });

    if (!isOpen) {
        btn.classList.add('open');
        answer.classList.add('open');
        btn.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
}

function filterFaqs(term) {
    const q = term.toLowerCase().trim();
    let anyVisible = false;

    document.querySelectorAll('.faq-item').forEach(item => {
        const match = !q || item.dataset.question.includes(q) || item.dataset.answer.includes(q);
        item.style.display = match ? '' : 'none';
        if (match) anyVisible = true;
    });

    document.querySelectorAll('.faq-category').forEach(cat => {
        const hasVisible = [...cat.querySelectorAll('.faq-item')].some(i => i.style.display !== 'none');
        cat.style.display = hasVisible ? '' : 'none';
    });

    document.getElementById('no-results').style.display = anyVisible ? 'none' : 'block';
}
</script>
@endpush
@endsection
