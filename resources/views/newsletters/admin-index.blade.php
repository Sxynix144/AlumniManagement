@extends('layouts.app')
@section('title', 'Manage Newsletters')

@section('content')
<div class="page-header flex justify-between items-center">
    <div>
        <h1>Newsletters</h1>
        <p>Upload and publish alumni newsletters and e-bulletins.</p>
    </div>
    <a href="{{ route('admin.newsletters.create') }}" class="btn btn-gold">+ Upload Newsletter</a>
</div>

<div class="card" style="padding:0">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Title</th><th>PDF</th><th>Status</th><th>Published</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($newsletters as $nl)
                    <tr>
                        <td><strong>{{ $nl->title }}</strong><br>
                            <span class="text-muted text-sm">{{ Str::limit($nl->description, 60) }}</span>
                        </td>
                        <td>
                            @if($nl->file_path)
                                <a href="{{ Storage::url($nl->file_path) }}" target="_blank" class="btn btn-sm btn-outline">📥 View</a>
                            @else
                                <span class="text-muted text-sm">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $nl->is_published ? 'badge-active' : 'badge-draft' }}">
                                {{ $nl->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </td>
                        <td class="text-sm text-muted">{{ $nl->published_at?->format('M j, Y') ?? '—' }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.newsletters.destroy', $nl) }}">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('Delete this newsletter?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted" style="padding:2rem">No newsletters yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="pagination">{{ $newsletters->links() }}</div>
@endsection
