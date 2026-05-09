<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsletterController extends Controller
{
    public function index()
    {
        $newsletters = Newsletter::published()->paginate(8);
        return view('newsletters.index', compact('newsletters'));
    }

    public function show(Newsletter $newsletter)
    {
        abort_if(!$newsletter->is_published, 404);
        return view('newsletters.show', compact('newsletter'));
    }

    // ── Admin ──────────────────────────────────────────────────────────────

    public function adminIndex()
    {
        $newsletters = Newsletter::with('author')->orderByDesc('created_at')->paginate(15);
        return view('newsletters.admin-index', compact('newsletters'));
    }

    public function create()
    {
        return view('newsletters.create');
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'title'        => 'required|string|max:200',
            'description'  => 'nullable|string|max:500',
            'is_published' => 'nullable|boolean',
            'pdf_file'     => 'nullable|file|mimes:pdf|max:20480',
            'cover_image'  => 'nullable|image|max:5120',
        ]);

        $data = [
            'created_by'   => auth()->id(),
            'title'        => $v['title'],
            'description'  => $v['description'] ?? null,
            'is_published' => $request->boolean('is_published'),
            'published_at' => $request->boolean('is_published') ? now() : null,
        ];

        if ($request->hasFile('pdf_file')) {
            $data['file_path'] = $request->file('pdf_file')
                ->store('newsletters/pdfs', 'public');
        }

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')
                ->store('newsletters/covers', 'public');
        }

        Newsletter::create($data);

        return redirect()->route('admin.newsletters.index')
                         ->with('success', 'Newsletter published.');
    }

    public function destroy(Newsletter $newsletter)
    {
        if ($newsletter->file_path)   Storage::disk('public')->delete($newsletter->file_path);
        if ($newsletter->cover_image) Storage::disk('public')->delete($newsletter->cover_image);
        $newsletter->delete();
        return back()->with('success', 'Newsletter deleted.');
    }
}
