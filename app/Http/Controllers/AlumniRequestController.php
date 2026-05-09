<?php

namespace App\Http\Controllers;

use App\Models\AlumniRequest;
use Illuminate\Http\Request;

class AlumniRequestController extends Controller
{
    /** Alumni: view their own requests */
    public function myRequests()
    {
        $alumni = auth()->user()->alumniProfile;
        abort_if(!$alumni, 404);

        $requests = AlumniRequest::where('alumni_id', $alumni->id)
            ->orderByDesc('created_at')
            ->get();

        return view('requests.my-request', compact('requests', 'alumni'));
    }

    /** Alumni: submit a new request */
    public function store(Request $request)
    {
        $alumni = auth()->user()->alumniProfile;
        abort_if(!$alumni || $alumni->status !== 'active', 403,
            'Only active alumni can submit requests.');

        $v = $request->validate([
            'type'  => 'required|in:alumni_id,yearbook,transcript,certificate,other',
            'notes' => 'nullable|string|max:500',
        ]);

        AlumniRequest::create([
            'alumni_id' => $alumni->id,
            'type'      => $v['type'],
            'notes'     => $v['notes'] ?? null,
            'status'    => 'pending',
        ]);

        return back()->with('success', "Request submitted! We'll process it shortly.");
    }

    // ── Admin ──────────────────────────────────────────────────────────────

    public function adminIndex(Request $request)
    {
        $requests = AlumniRequest::with('alumni')
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('type'),   fn($q) => $q->where('type',   $request->type))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        $counts = AlumniRequest::selectRaw('status, count(*) as total')
            ->groupBy('status')->pluck('total', 'status');

        return view('requests.admin-index', compact('requests', 'counts'));
    }

    public function updateStatus(Request $request, AlumniRequest $alumniRequest)
    {
        $v = $request->validate([
            'status'        => 'required|in:pending,processing,ready,released,rejected',
            'admin_remarks' => 'nullable|string|max:500',
        ]);

        $v['released_at'] = $v['status'] === 'released' ? now() : $alumniRequest->released_at;

        $alumniRequest->update($v);

        return back()->with('success', 'Request status updated.');
    }
}
