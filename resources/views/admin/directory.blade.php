@extends('layouts.app')
@section('title', 'Alumni Directory')

@section('content')
<div class="page-header flex justify-between items-center">
    <div>
        <h1>Alumni Directory</h1>
        <p>Browse, search, and export the active alumni database.</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.export', request()->only('year')) }}" class="btn btn-gold btn-sm">
            ⬇ Export CSV
        </a>
    </div>
</div>

{{-- Search / Filter Bar --}}
<div class="card mb-2">
    <form method="GET" action="{{ route('admin.directory') }}" class="flex gap-2" style="flex-wrap:wrap;align-items:flex-end">
        <div class="form-group" style="margin:0;flex:1;min-width:200px">
            <label>Search Name / Student No.</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Type to search…">
        </div>
        <div class="form-group" style="margin:0;min-width:150px">
            <label>Filter by Batch Year</label>
            <select name="year">
                <option value="">All Years</option>
                @foreach($years as $yr)
                    <option value="{{ $yr }}" @selected(request('year') == $yr)>{{ $yr }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-1">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('admin.directory') }}" class="btn btn-outline">Reset</a>
        </div>
    </form>
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
                    <th>Current Job</th>
                    <th>City</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($alumni as $a)
                    <tr>
                        <td><strong>{{ $a->full_name }}</strong></td>
                        <td>{{ $a->graduation_year }}</td>
                        <td>{{ $a->course ?? '—' }}</td>
                        <td>{{ $a->email ?? '—' }}</td>
                        <td>{{ $a->phone ?? '—' }}</td>
                        <td>
                            @if($a->current_job)
                                {{ $a->current_job }}
                                @if($a->company) <span class="text-muted">@ {{ $a->company }}</span> @endif
                            @else —
                            @endif
                        </td>
                        <td>{{ $a->city ?? '—' }}</td>
                        <td><span class="badge badge-{{ $a->status }}">{{ ucfirst($a->status) }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted" style="padding:2rem">No alumni found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="pagination">{{ $alumni->links() }}</div>
@endsection
