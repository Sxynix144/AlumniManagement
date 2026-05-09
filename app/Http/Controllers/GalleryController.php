<?php

namespace App\Http\Controllers;

use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $albums = GalleryAlbum::with(['photos' => fn($q) => $q->take(1)])
            ->where('is_published', true)
            ->withCount('photos')
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('gallery.index', compact('albums'));
    }

    public function show(GalleryAlbum $album)
    {
        abort_if(!$album->is_published, 404);
        $album->load('photos', 'event');
        return view('gallery.show', compact('album'));
    }

    // ── Admin ──────────────────────────────────────────────────────────────

    public function adminIndex()
    {
        $albums = GalleryAlbum::withCount('photos')
            ->orderByDesc('created_at')->paginate(15);
        return view('gallery.admin-index', compact('albums'));
    }

    public function create()
    {
        return view('gallery.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:200',
            'description'  => 'nullable|string|max:500',
            'is_published' => 'nullable|boolean',
            'photos'       => 'required|array|min:1',
            'photos.*'     => 'image|max:8192',
        ]);

        $album = GalleryAlbum::create([
            'created_by'   => auth()->id(),
            'title'        => $request->title,
            'description'  => $request->description,
            'is_published' => $request->boolean('is_published'),
        ]);

        $coverSet = false;
        foreach ($request->file('photos') as $i => $file) {
            $path = $file->store('gallery', 'public');

            GalleryPhoto::create([
                'album_id'   => $album->id,
                'file_path'  => $path,
                'sort_order' => $i,
            ]);

            if (!$coverSet) {
                $album->update(['cover_photo' => $path]);
                $coverSet = true;
            }
        }

        return redirect()->route('admin.gallery.index')
                         ->with('success', 'Album created with ' . count($request->file('photos')) . ' photos.');
    }

    public function destroy(GalleryAlbum $album)
    {
        foreach ($album->photos as $photo) {
            Storage::disk('public')->delete($photo->file_path);
        }
        if ($album->cover_photo) Storage::disk('public')->delete($album->cover_photo);
        $album->delete();

        return back()->with('success', 'Album deleted.');
    }
}
