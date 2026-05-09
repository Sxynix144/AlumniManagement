@extends('layouts.app')
@section('title', 'Tracer Study Survey')

@push('styles')
<style>
.survey-hero {
    background: linear-gradient(135deg, var(--navy) 0%, #1a5276 100%);
    border-radius: 12px;
    padding: 2.5rem 3rem;
    margin-bottom: 2rem;
    color: white;
}
.survey-hero h1 { font-family:'Playfair Display',serif; font-size:2rem; margin-bottom:.4rem; }
.survey-hero p  { color:rgba(255,255,255,.65); max-width:560px; line-height:1.7; }

.step-card {
    background: var(--white);
    border-radius: 10px;
    border: 1px solid var(--light);
    padding: 2rem;
    margin-bottom: 1.5rem;
}
.step-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--cream);
}
.step-num {
    width: 36px; height: 36px;
    background: var(--navy);
    color: white;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: .9rem;
    flex-shrink: 0;
}
.step-header h3 { font-family:'Playfair Display',serif; font-size:1.2rem; margin: 0; }

.radio-grid { display: grid; grid-template-columns: 1fr 1fr; gap: .6rem; }
@media(max-width:600px){ .radio-grid{ grid-template-columns:1fr; } }

.radio-option {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: .75rem 1rem;
    border: 1.5px solid var(--light);
    border-radius: 8px;
    cursor: pointer;
    transition: all .15s;
    font-size: .88rem;
}
.radio-option:hover { border-color: var(--gold); background: var(--cream); }
.radio-option input[type="radio"] { accent-color: var(--gold); width: 16px; height: 16px; flex-shrink:0; }
.radio-option.selected { border-color: var(--gold); background: #fffbf0; }

.star-rating { display: flex; gap: .3rem; }
.star-btn {
    font-size: 2rem;
    background: none; border: none;
    cursor: pointer;
    color: #d1d5db;
    transition: color .1s;
    line-height: 1;
}
.star-btn.active, .star-btn:hover { color: var(--gold); }

.already-done {
    text-align: center;
    padding: 3rem;
}
.already-done .check { font-size: 4rem; margin-bottom: 1rem; }
</style>
@endpush

@section('content')


@if(!$survey)
    {{-- No survey available --}}
    <div class="card text-center" style="padding:3rem;max-width:500px;margin:0 auto">
        <div style="font-size:3rem;margin-bottom:1rem">📊</div>
        <h2 style="font-family:'Playfair Display',serif;margin-bottom:.5rem">No Active Survey</h2>
        <p class="text-muted">There is no active tracer study survey at this time. Please check back later.</p>
        <a href="{{ route('alumni.profile') }}" class="btn btn-primary mt-3">Back to Dashboard</a>
    </div>

@else
    {{-- Survey exists — show it --}}
    <div class="survey-hero">
        <div style="display:inline-block;background:rgba(255,255,255,.12);border-radius:99px;padding:.25rem .9rem;font-size:.72rem;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.8);margin-bottom:.75rem">
            📊 CHED Tracer Study
        </div>
        <h1>{{ $survey->title }}</h1>
        <p>{{ $survey->description ?? 'This tracer study helps the school improve its programs and meets CHED accreditation requirements.' }}</p>
    </div>
@if($existing)
    <div class="card already-done">
        <div class="check">✅</div>
        <h2 style="font-family:'Playfair Display',serif;margin-bottom:.5rem">You've already responded!</h2>
        <p class="text-muted" style="margin-bottom:1.5rem">You submitted your tracer study response on {{ $existing->created_at->format('F j, Y') }}. Thank you for participating!</p>
        <a href="{{ route('alumni.profile') }}" class="btn btn-primary">Back to My Profile</a>
    </div>
@else
@endif
<form method="POST" action="{{ route('tracer.store') }}" id="survey-form">
@csrf

{{-- STEP 1: Employment Status --}}
<div class="step-card">
    <div class="step-header">
        <div class="step-num">1</div>
        <div>
            <h3>Employment Status</h3>
            <p class="text-sm text-muted" style="margin:0">What is your current employment situation?</p>
        </div>
    </div>

    <div class="radio-grid">
        @foreach(\App\Models\TracerResponse::EMPLOYMENT_LABELS as $val => $label)
            <label class="radio-option {{ old('employment_status') === $val ? 'selected' : '' }}">
                <input type="radio" name="employment_status" value="{{ $val }}"
                       {{ old('employment_status') === $val ? 'checked' : '' }} required
                       onchange="document.querySelectorAll('.radio-option').forEach(el=>el.classList.remove('selected'));this.parentElement.classList.add('selected');toggleEmployed(this.value)">
                {{ $label }}
            </label>
        @endforeach
    </div>
    @error('employment_status')<div class="form-error mt-1">{{ $message }}</div>@enderror
</div>

{{-- STEP 2: Job Details (shown if employed) --}}
<div class="step-card" id="job-section" style="display:{{ old('employment_status') && in_array(old('employment_status'),['employed_related','employed_unrelated','self_employed']) ? 'block':'none' }}">
    <div class="step-header">
        <div class="step-num">2</div>
        <div>
            <h3>Job Details</h3>
            <p class="text-sm text-muted" style="margin:0">Tell us about your current position.</p>
        </div>
    </div>

    <div class="two-col">
        <div class="form-group">
            <label>Job Title / Position</label>
            <input type="text" name="job_title" value="{{ old('job_title') }}" placeholder="e.g. Software Engineer">
        </div>
        <div class="form-group">
            <label>Employer / Company Name</label>
            <input type="text" name="employer_name" value="{{ old('employer_name') }}" placeholder="e.g. Accenture Philippines">
        </div>
    </div>

    <div class="two-col">
        <div class="form-group">
            <label>Type of Employer</label>
            <select name="employer_type">
                <option value="">Select...</option>
                <option value="private"    {{ old('employer_type')==='private'    ?'selected':'' }}>Private Company</option>
                <option value="government" {{ old('employer_type')==='government' ?'selected':'' }}>Government / Public Sector</option>
                <option value="ngo"        {{ old('employer_type')==='ngo'        ?'selected':'' }}>NGO / Non-profit</option>
                <option value="self"       {{ old('employer_type')==='self'       ?'selected':'' }}>Self-employed / Own Business</option>
            </select>
        </div>
        <div class="form-group">
            <label>Monthly Salary Range</label>
            <select name="monthly_salary_range">
                <option value="">Select...</option>
                @foreach(\App\Models\TracerResponse::SALARY_RANGES as $val => $label)
                    <option value="{{ $val }}" {{ old('monthly_salary_range')===$val?'selected':'' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group">
        <label>How many months after graduation did you get your first job?</label>
        <input type="number" name="months_to_first_job" value="{{ old('months_to_first_job') }}"
               min="0" max="120" placeholder="e.g. 3">
    </div>

    <div class="form-group">
        <label>Is your job relevant to your course?</label>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.5rem;margin-top:.3rem">
            @foreach(\App\Models\TracerResponse::RELEVANCE_LABELS as $val => $label)
                <label class="radio-option">
                    <input type="radio" name="job_relevance" value="{{ $val }}"
                           {{ old('job_relevance')===$val?'checked':'' }}>
                    {{ $label }}
                </label>
            @endforeach
        </div>
    </div>
</div>

{{-- STEP 3: School Feedback --}}
<div class="step-card">
    <div class="step-header">
        <div class="step-num">3</div>
        <div>
            <h3>School Feedback</h3>
            <p class="text-sm text-muted" style="margin:0">Help us improve the school's programs.</p>
        </div>
    </div>

    <div class="form-group">
        <label>What skills or subjects from school were most useful in your career?</label>
        <textarea name="skills_learned" rows="3"
            placeholder="e.g. Thesis writing, internship program, laboratory work...">{{ old('skills_learned') }}</textarea>
    </div>

    <div class="form-group">
        <label>Do you have feedback or suggestions for the school's curriculum?</label>
        <textarea name="curriculum_feedback" rows="3"
            placeholder="e.g. Add more industry-relevant subjects, more OJT hours...">{{ old('curriculum_feedback') }}</textarea>
    </div>

    <div class="form-group">
        <label>Overall, how satisfied are you with your education at this school?</label>
        <div class="star-rating mt-1" id="star-rating">
            @for($i = 1; $i <= 5; $i++)
                <button type="button" class="star-btn {{ old('overall_satisfaction') >= $i ? 'active' : '' }}"
                        onclick="setStar({{ $i }})" data-val="{{ $i }}">★</button>
            @endfor
        </div>
        <input type="hidden" name="overall_satisfaction" id="star-input" value="{{ old('overall_satisfaction') }}">
        <div id="star-label" style="font-size:.82rem;color:var(--slate);margin-top:.3rem">
            {{ old('overall_satisfaction') ? ['','😞 Poor','😐 Fair','🙂 Good','😊 Very Good','🤩 Excellent'][old('overall_satisfaction')] : 'Click to rate' }}
        </div>
    </div>
</div>

<button type="submit" class="btn btn-gold w-full" style="font-size:1.05rem;padding:1rem">
    📊 Submit Tracer Study Response →
</button>
<p class="text-sm text-muted text-center mt-2">Your response is confidential and used only for accreditation and program improvement.</p>

</form>
@endif

@push('scripts')
<script>
function toggleEmployed(val) {
    const employed = ['employed_related','employed_unrelated','self_employed'];
    document.getElementById('job-section').style.display = employed.includes(val) ? 'block' : 'none';
}

const starLabels = ['','😞 Poor','😐 Fair','🙂 Good','😊 Very Good','🤩 Excellent'];

function setStar(n) {
    document.getElementById('star-input').value = n;
    document.getElementById('star-label').textContent = starLabels[n];
    document.querySelectorAll('.star-btn').forEach((btn, i) => {
        btn.classList.toggle('active', i < n);
    });
}
</script>
@endpush
@endsection
