<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /** Public feed */
    public function index(Request $request)
    {
        $announcements = Announcement::published()
            ->when($request->filled('category'), fn($q) => $q->where('category', $request->category))
            ->paginate(9)
            ->withQueryString();

        
$categories = Announcement::published()
    ->select('category')
    ->distinct()
    ->orderBy('category')
    ->pluck('category');

        return view('announcements.index', compact('announcements', 'categories'));
    }

    /** Single announcement */
    public function show(Announcement $announcement)
    {
        abort_if(!$announcement->is_published, 404);
        $recent = Announcement::published()->where('id', '!=', $announcement->id)->take(4)->get();
        return view('announcements.show', compact('announcement', 'recent'));
    }

    // ── Admin ──────────────────────────────────────────────────────────────

    public function adminIndex()
    {
        $announcements = Announcement::with('author')->orderByDesc('created_at')->paginate(15);
        return view('announcements.admin-index', compact('announcements'));
    }

    public function create()
    {
        return view('announcements.create');
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'title'        => 'required|string|max:200',
            'body'         => 'required|string',
            'category'     => 'required|string|max:60',
            'is_published' => 'nullable|boolean',
        ]);

        $v['created_by']    = auth()->id();
        $v['is_published']  = $request->boolean('is_published');
        $v['published_at']  = $v['is_published'] ? now() : null;

        Announcement::create($v);

        return redirect()->route('admin.announcements.index')
                         ->with('success', 'Announcement saved.');
    }

    public function edit(Announcement $announcement)
    {
        return view('announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $v = $request->validate([
            'title'        => 'required|string|max:200',
            'body'         => 'required|string',
            'category'     => 'required|string|max:60',
            'is_published' => 'nullable|boolean',
        ]);

        $wasPublished = $announcement->is_published;
        $v['is_published'] = $request->boolean('is_published');

        if ($v['is_published'] && !$wasPublished) {
            $v['published_at'] = now();
        }

        $announcement->update($v);

        return redirect()->route('admin.announcements.index')
                         ->with('success', 'Announcement updated.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return back()->with('success', 'Announcement deleted.');
    }
}
