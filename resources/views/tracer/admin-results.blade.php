@extends('layouts.app')
@section('title', 'Tracer Results — ' . $survey->title)

@push('styles')
<style>
.chart-card { background:var(--white); border-radius:10px; border:1px solid var(--light); padding:1.5rem; }
.chart-card h3 { font-family:'Playfair Display',serif; font-size:1.1rem; margin-bottom:1.2rem; color:var(--navy); }
.bar-row { display:flex; align-items:center; gap:.75rem; margin-bottom:.6rem; font-size:.83rem; }
.bar-label { min-width:180px; color:var(--slate); }
.bar-track { flex:1; background:var(--light); border-radius:99px; height:20px; overflow:hidden; }
.bar-fill  { height:100%; border-radius:99px; background:var(--navy); transition:width .5s; display:flex;align-items:center;justify-content:flex-end;padding-right:.4rem; }
.bar-fill span { color:#fff; font-size:.7rem; font-weight:700; }
.bar-count { min-width:30px; text-align:right; font-weight:700; color:var(--navy); }
</style>
@endpush

@section('content')
<div class="page-header flex justify-between items-center">
    <div>
        <h1>{{ $survey->title }}</h1>
        <p>{{ $responses->count() }} responses collected</p>
    </div>
    <a href="{{ route('admin.tracer.export', $survey) }}" class="btn btn-gold">⬇ Export CSV</a>
</div>

{{-- Summary Stats --}}
<div class="stats-grid mb-4">
    <div class="stat-card">
        <div class="stat-value">{{ $responses->count() }}</div>
        <div class="stat-label">Total Responses</div>
    </div>
    <div class="stat-card" style="border-top-color:#9ae6b4">
        <div class="stat-value">{{ $responses->filter->isEmployed()->count() }}</div>
        <div class="stat-label">Employed</div>
    </div>
    <div class="stat-card" style="border-top-color:#fbd38d">
        <div class="stat-value">{{ number_format($satisfactionAvg ?? 0, 1) }}/5</div>
        <div class="stat-label">Avg Satisfaction</div>
    </div>
    <div class="stat-card" style="border-top-color:#90cdf4">
        <div class="stat-value">
            {{ $responses->filter(fn($r) => $r->job_relevance === 'very_relevant')->count() }}
        </div>
        <div class="stat-label">Highly Relevant Jobs</div>
    </div>
</div>

<div class="two-col mb-3">
    {{-- Employment Chart --}}
    <div class="chart-card">
        <h3>📊 Employment Status</h3>
        @php $total = $responses->count() ?: 1; @endphp
        @foreach(\App\Models\TracerResponse::EMPLOYMENT_LABELS as $key => $label)
            @php $count = $employmentStats[$key] ?? 0; $pct = round($count/$total*100); @endphp
            <div class="bar-row">
                <span class="bar-label">{{ Str::limit($label, 28) }}</span>
                <div class="bar-track">
                    <div class="bar-fill" style="width:{{ $pct }}%">
                        @if($pct > 8)<span>{{ $pct }}%</span>@endif
                    </div>
                </div>
                <span class="bar-count">{{ $count }}</span>
            </div>
        @endforeach
    </div>

    {{-- Job Relevance Chart --}}
    <div class="chart-card">
        <h3>🎯 Job Relevance to Course</h3>
        @foreach(\App\Models\TracerResponse::RELEVANCE_LABELS as $key => $label)
            @php $count = $relevanceStats[$key] ?? 0; $pct = round($count/$total*100); @endphp
            <div class="bar-row">
                <span class="bar-label">{{ Str::limit($label, 22) }}</span>
                <div class="bar-track">
                    <div class="bar-fill" style="width:{{ $pct }}%;background:var(--gold)">
                        @if($pct > 8)<span style="color:var(--navy)">{{ $pct }}%</span>@endif
                    </div>
                </div>
                <span class="bar-count">{{ $count }}</span>
            </div>
        @endforeach
    </div>
</div>

{{-- Salary Distribution --}}
@if(!empty($salaryStats))
<div class="chart-card mb-3">
    <h3>💰 Salary Range Distribution</h3>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:.5rem 2rem">
        @foreach(\App\Models\TracerResponse::SALARY_RANGES as $key => $label)
            @php $count = $salaryStats[$key] ?? 0; $pct = round($count/$total*100); @endphp
            <div class="bar-row">
                <span class="bar-label">{{ $label }}</span>
                <div class="bar-track">
                    <div class="bar-fill" style="width:{{ $pct }}%;background:#4299e1">
                        @if($pct > 8)<span>{{ $pct }}%</span>@endif
                    </div>
                </div>
                <span class="bar-count">{{ $count }}</span>
            </div>
        @endforeach
    </div>
</div>
@endif

{{-- Individual Responses Table --}}
<h2 class="section-title">Individual Responses</h2>
<div class="card" style="padding:0">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Alumni</th><th>Batch</th><th>Status</th><th>Employer</th><th>Relevance</th><th>Satisfaction</th></tr>
            </thead>
            <tbody>
                @foreach($responses as $r)
                    <tr>
                        <td><strong>{{ $r->alumni->full_name }}</strong></td>
                        <td>{{ $r->alumni->graduation_year }}</td>
                        <td style="font-size:.8rem">{{ Str::limit($r->employment_label, 30) }}</td>
                        <td class="text-sm text-muted">
                            {{ $r->employer_name ?? '—' }}
                            @if($r->job_title) <br><span>{{ $r->job_title }}</span> @endif
                        </td>
                        <td class="text-sm">{{ \App\Models\TracerResponse::RELEVANCE_LABELS[$r->job_relevance] ?? '—' }}</td>
                        <td>
                            @if($r->overall_satisfaction)
                                @for($i=1;$i<=5;$i++)
                                    <span style="color:{{ $i<=$r->overall_satisfaction?'var(--gold)':'#d1d5db' }}">★</span>
                                @endfor
                            @else — @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
