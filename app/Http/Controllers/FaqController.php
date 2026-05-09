<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /** Public FAQ page */
    public function index()
    {
        $faqs = Faq::published()->get()->groupBy('category');
        $categories = $faqs->keys();
        return view('faqs.index', compact('faqs', 'categories'));
    }

    // ── Admin ─────────────────────────────────────────────────────────────────

    public function adminIndex()
    {
        $faqs = Faq::with('author')->orderBy('category')->orderBy('sort_order')->paginate(20);
        return view('faqs.admin-index', compact('faqs'));
    }

    public function create()
    {
        return view('faqs.create');
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'question'     => 'required|string|max:300',
            'answer'       => 'required|string|max:2000',
            'category'     => 'required|string|max:80',
            'sort_order'   => 'nullable|integer',
            'is_published' => 'nullable|boolean',
        ]);

        $v['created_by']   = auth()->id();
        $v['is_published'] = $request->boolean('is_published', true);
        $v['sort_order']   = $v['sort_order'] ?? 0;

        Faq::create($v);

        return redirect()->route('admin.faqs.index')
                         ->with('success', 'FAQ added.');
    }

    public function edit(Faq $faq)
    {
        return view('faqs.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        $v = $request->validate([
            'question'     => 'required|string|max:300',
            'answer'       => 'required|string|max:2000',
            'category'     => 'required|string|max:80',
            'sort_order'   => 'nullable|integer',
            'is_published' => 'nullable|boolean',
        ]);

        $v['is_published'] = $request->boolean('is_published');
        $v['sort_order']   = $v['sort_order'] ?? 0;

        $faq->update($v);

        return redirect()->route('admin.faqs.index')
                         ->with('success', 'FAQ updated.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return back()->with('success', 'FAQ deleted.');
    }
}
