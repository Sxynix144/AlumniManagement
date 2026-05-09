@extends('layouts.app')
@section('title', 'Tracer Study Management')

@section('content')
<div class="page-header flex justify-between items-center">
    <div>
        <h1>Tracer Study</h1>
        <p>Manage surveys and view alumni employment reports for CHED accreditation.</p>
    </div>
    <a href="{{ route('admin.tracer.create') }}" class="btn btn-gold">+ New Survey</a>
</div>

<div class="card" style="padding:0">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Survey Title</th><th>Responses</th><th>Status</th><th>Created</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($surveys as $survey)
                    <tr>
                        <td><strong>{{ $survey->title }}</strong></td>
                        <td>{{ $survey->responses_count }} alumni</td>
                        <td>
                            <span class="badge {{ $survey->is_active ? 'badge-active' : 'badge-draft' }}">
                                {{ $survey->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="text-sm text-muted">{{ $survey->created_at->format('M j, Y') }}</td>
                        <td>
                            <div class="flex gap-1">
                                <a href="{{ route('admin.tracer.results', $survey) }}" class="btn btn-sm btn-primary">📊 Results</a>
                                <a href="{{ route('admin.tracer.export', $survey) }}" class="btn btn-sm btn-gold">⬇ CSV</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted" style="padding:2rem">No surveys yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
