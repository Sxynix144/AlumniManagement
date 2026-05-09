@extends('layouts.app')
@section('title', 'Manage FAQs')

@section('content')
<div class="page-header flex justify-between items-center">
    <div><h1>FAQs</h1><p>Manage frequently asked questions visible to all alumni.</p></div>
    <a href="{{ route('admin.faqs.create') }}" class="btn btn-gold">+ Add FAQ</a>
</div>

<div class="card" style="padding:0">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>#</th><th>Question</th><th>Category</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($faqs as $faq)
                    <tr>
                        <td class="text-muted text-sm">{{ $faq->sort_order }}</td>
                        <td>
                            <strong>{{ Str::limit($faq->question, 70) }}</strong>
                            <div class="text-sm text-muted">{{ Str::limit($faq->answer, 80) }}</div>
                        </td>
                        <td><span class="badge badge-draft">{{ $faq->category }}</span></td>
                        <td>
                            <span class="badge {{ $faq->is_published ? 'badge-active' : 'badge-pending' }}">
                                {{ $faq->is_published ? 'Published' : 'Hidden' }}
                            </span>
                        </td>
                        <td>
                            <div class="flex gap-1">
                                <a href="{{ route('admin.faqs.edit', $faq) }}" class="btn btn-sm btn-outline">Edit</a>
                                <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger"
                                        onclick="return confirm('Delete this FAQ?')">Del</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted" style="padding:2rem">No FAQs yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="pagination">{{ $faqs->links() }}</div>
@endsection