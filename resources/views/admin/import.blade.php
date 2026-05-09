@extends('layouts.app')
@section('title', 'Import Alumni CSV')

@section('content')
<div class="page-header">
    <h1>Batch Import Alumni</h1>
    <p>Upload a CSV file to pre-populate the system with historical records from old spreadsheets.</p>
</div>

<div class="two-col" style="align-items:start">
    <div class="card">
        <h2 class="section-title">Upload CSV File</h2>
        <form method="POST" action="{{ route('admin.import') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>CSV File (max 10 MB)</label>
                <input type="file" name="csv_file" accept=".csv,.txt" required>
                @error('csv_file')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-primary w-full">⬆ Import Records</button>
        </form>
    </div>

    <div class="card" style="border-left:4px solid var(--gold)">
        <h2 class="section-title">📋 Required CSV Format</h2>
        <p class="text-sm text-muted" style="margin-bottom:1rem">
            The first row is treated as a header and skipped. Columns must be in this order:
        </p>
        <div style="background:var(--navy);color:#a8d8a8;padding:1rem;border-radius:var(--radius);font-family:monospace;font-size:.82rem;overflow-x:auto">
            student_number, first_name, last_name, graduation_year, course<br>
            2015-001, Juan, dela Cruz, 2015, BS Computer Science<br>
            2016-042, Maria, Santos, 2016, BS Nursing<br>
            , Ana, Reyes, 2014, BS Education
        </div>
        <ul style="margin-top:1rem;padding-left:1.2rem;font-size:.85rem;color:var(--slate);line-height:2">
            <li><code>student_number</code> — can be blank; used to prevent duplicate imports</li>
            <li><code>course</code> — optional fifth column</li>
            <li>All imported records are set to <strong>placeholder</strong> status</li>
            <li>Duplicates (matching student number) are automatically skipped</li>
        </ul>
    </div>
</div>
@endsection
