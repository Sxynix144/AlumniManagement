@extends('layouts.app')
@section('title', 'Manage Requests')

@section('content')
<div class="page-header">
    <h1>Alumni Requests</h1>
    <p>Review and process document requests from alumni.</p>
</div>

{{-- Status summary --}}
<div class="stats-grid" style="margin-bottom:1.5rem">
    @foreach(['pending'=>'⏳','processing'=>'⚙️','ready'=>'✅','released'=>'📦','rejected'=>'❌'] as $s => $icon)
        <div class="stat-card" style="border-top-color:{{ ['pending'=>'#f6ad55','processing'=>'#63b3ed','ready'=>'#68d391','released'=>'#48bb78','rejected'=>'#fc8181'][$s] }}">
            <div class="stat-value">{{ $counts[$s] ?? 0 }}</div>
            <div class="stat-label">{{ $icon }} {{ ucfirst($s) }}</div>
        </div>
    @endforeach
</div>

{{-- Filter bar --}}
<div class="card mb-2" style="padding:1rem">
    <form method="GET" action="{{ route('admin.requests.index') }}" class="flex gap-2" style="flex-wrap:wrap;align-items:flex-end">
        <div class="form-group" style="margin:0;min-width:160px">
            <label>Filter by Status</label>
            <select name="status" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                @foreach(['pending','processing','ready','released','rejected'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group" style="margin:0;min-width:160px">
            <label>Filter by Type</label>
            <select name="type" onchange="this.form.submit()">
                <option value="">All Types</option>
                @foreach(\App\Models\AlumniRequest::TYPE_LABELS as $val => $label)
                    <option value="{{ $val }}" {{ request('type') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <a href="{{ route('admin.requests.index') }}" class="btn btn-outline btn-sm">Reset</a>
    </form>
</div>

<div class="card" style="padding:0">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Alumni</th><th>Batch</th><th>Type</th><th>Notes</th><th>Submitted</th><th>Status</th><th>Action</th></tr>
            </thead>
            <tbody>
                @forelse($requests as $req)
                    <tr>
                        <td><strong>{{ $req->alumni->full_name }}</strong></td>
                        <td>{{ $req->alumni->graduation_year }}</td>
                        <td>{{ $req->type_label }}</td>
                        <td class="text-sm text-muted">{{ Str::limit($req->notes, 50) ?? '—' }}</td>
                        <td class="text-sm text-muted">{{ $req->created_at->format('M j, Y') }}</td>
                        <td><span class="badge {{ $req->status_color }}">{{ ucfirst($req->status) }}</span></td>
                        <td>
                            <form method="POST" action="{{ route('admin.requests.updateStatus', $req) }}"
                                  style="display:flex;gap:.4rem;align-items:center;flex-wrap:wrap">
                                @csrf @method('PATCH')
                                <select name="status" style="padding:.3rem .5rem;border-radius:4px;border:1.5px solid var(--light);font-size:.8rem">
                                    @foreach(['pending','processing','ready','released','rejected'] as $s)
                                        <option value="{{ $s }}" {{ $req->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                    @endforeach
                                </select>
                                <button class="btn btn-sm btn-primary">Update</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted" style="padding:2rem">No requests found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="pagination">{{ $requests->links() }}</div>
@endsection
