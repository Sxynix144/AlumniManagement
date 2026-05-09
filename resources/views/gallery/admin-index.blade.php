@extends('layouts.app')
@section('title', 'Manage Gallery')

@section('content')
<div class="page-header flex justify-between items-center">
    <div><h1>Photo Gallery</h1><p>Manage photo albums from alumni events.</p></div>
    <a href="{{ route('admin.gallery.create') }}" class="btn btn-gold">+ New Album</a>
</div>

<div class="card" style="padding:0">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Album</th><th>Photos</th><th>Status</th><th>Created</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($albums as $album)
                    <tr>
                        <td><strong>{{ $album->title }}</strong></td>
                        <td>{{ $album->photos_count }}</td>
                        <td><span class="badge {{ $album->is_published ? 'badge-active' : 'badge-draft' }}">
                            {{ $album->is_published ? 'Public' : 'Hidden' }}</span>
                        </td>
                        <td class="text-sm text-muted">{{ $album->created_at->format('M j, Y') }}</td>
                        <td>
                            <div class="flex gap-1">
                                <a href="{{ route('gallery.show', $album) }}" class="btn btn-sm btn-outline" target="_blank">View</a>
                                <form method="POST" action="{{ route('admin.gallery.destroy', $album) }}">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger"
                                        onclick="return confirm('Delete album and all photos?')">Del</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted" style="padding:2rem">No albums yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
