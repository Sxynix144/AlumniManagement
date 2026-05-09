@extends('layouts.app')
@section('title', 'Pending Approvals')

@section('content')
<div class="page-header">
    <h1>Pending Approvals</h1>
    <p>Review and verify new alumni self-registrations before activating their accounts.</p>
</div>

<div class="card" style="padding:0">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Batch</th>
                    <th>Course</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Job / City</th>
                    <th>Submitted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pending as $alumni)
                    <tr>
                        <td><strong>{{ $alumni->full_name }}</strong></td>
                        <td>{{ $alumni->graduation_year }}</td>
                        <td>{{ $alumni->course ?? '—' }}</td>
                        <td>{{ $alumni->email ?? '—' }}</td>
                        <td>{{ $alumni->phone ?? '—' }}</td>
                        <td>
                            @if($alumni->current_job) {{ $alumni->current_job }} @endif
                            @if($alumni->city) · {{ $alumni->city }} @endif
                            @if(!$alumni->current_job && !$alumni->city) — @endif
                        </td>
                        <td class="text-sm text-muted">{{ $alumni->created_at->format('M j, Y') }}</td>
                        <td>
                            <div class="flex gap-1">
                                <form method="POST" action="{{ route('admin.approve', $alumni) }}">
                                    @csrf
                                    <button class="btn btn-sm btn-success">✓ Approve</button>
                                </form>
                                <form method="POST" action="{{ route('admin.reject', $alumni) }}">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger"
                                        onclick="return confirm('Permanently reject and remove this registration?')">
                                        ✗ Reject
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted" style="padding:2rem">
                            🎉 No pending approvals!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="pagination">
    {{ $pending->links() }}
</div>
@endsection
