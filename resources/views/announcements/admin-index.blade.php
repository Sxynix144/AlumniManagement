@extends('layouts.app')
@section('title', 'Manage Announcements')

@section('content')
<div class="page-header flex justify-between items-center">
    <div>
        <h1>Announcements</h1>
        <p>Publish official updates, notices, and opportunities for alumni.</p>
    </div>
    <a href="{{ route('admin.announcements.create') }}" class="btn btn-gold">+ New Announcement</a>
</div>

<div class="card" style="padding:0">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Title</th><th>Category</th><th>By</th><th>Status</th><th>Published</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($announcements as $a)
                    <tr>
                        <td><strong>{{ $a->title }}</strong></td>
                        <td>{{ $a->category }}</td>
                        <td>{{ $a->author->name }}</td>
                        <td>
                            <span class="badge {{ $a->is_published ? 'badge-active' : 'badge-draft' }}">
                                {{ $a->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </td>
                        <td class="text-sm text-muted">
                            {{ $a->published_at?->format('M j, Y') ?? '—' }}
                        </td>
                        <td>
                            <div class="flex gap-1">
                                <a href="{{ route('admin.announcements.edit', $a) }}" class="btn btn-sm btn-outline">Edit</a>
                                <form method="POST" action="{{ route('admin.announcements.destroy', $a) }}">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger"
                                        onclick="return confirm('Delete this announcement?')">Del</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted" style="padding:2rem">No announcements yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="pagination">{{ $announcements->links() }}</div>
@endsection
