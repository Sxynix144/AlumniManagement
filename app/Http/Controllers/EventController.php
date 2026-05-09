<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\Event;
use App\Models\EventBatch;
use App\Models\Invitation;
use App\Mail\EventInvitationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EventController extends Controller
{
    /**
     * List all events (staff view).
     */
    public function index()
    {
        $events = Event::with(['creator', 'rsvps', 'batches'])
            ->orderByDesc('event_date')
            ->paginate(15);

        return view('events.index', compact('events'));
    }

    /**
     * Show event creation form.
     */
    public function create()
    {
        $availableYears = Alumni::active()
            ->distinct()
            ->orderByDesc('graduation_year')
            ->pluck('graduation_year');

        return view('events.create', compact('availableYears'));
    }

    /**
     * Store a new event.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:200',
            'description'      => 'nullable|string|max:2000',
            'event_date'       => 'required|date|after:now',
            'venue'            => 'nullable|string|max:200',
            'graduation_years' => 'required|array|min:1',
            'graduation_years.*' => 'integer',
            'status'           => 'required|in:draft,published',
        ]);

        $event = Event::create([
            'created_by'  => auth()->id(),
            'title'       => $validated['title'],
            'description' => $validated['description'],
            'event_date'  => $validated['event_date'],
            'venue'       => $validated['venue'],
            'status'      => $validated['status'],
        ]);

        foreach ($validated['graduation_years'] as $year) {
            EventBatch::create(['event_id' => $event->id, 'graduation_year' => $year]);
        }

        return redirect()->route('events.show', $event)
                         ->with('success', 'Event created. You can now send invitations.');
    }

    /**
     * Show event detail + RSVP stats.
     */
    public function show(Event $event)
    {
        $event->load(['batches', 'creator', 'invitations.alumni', 'rsvps.alumni']);

        $rsvpStats = [
            'attending'     => $event->rsvps->where('status', 'attending')->count(),
            'not_attending' => $event->rsvps->where('status', 'not_attending')->count(),
            'maybe'         => $event->rsvps->where('status', 'maybe')->count(),
            'invited'       => $event->invitations->count(),
        ];

        $attendees = $event->rsvps()
            ->where('status', 'attending')
            ->with('alumni')
            ->get()
            ->pluck('alumni');

        return view('events.show', compact('event', 'rsvpStats', 'attendees'));
    }

    /**
     * Send email invitations to target batches.
     */
    public function sendInvitations(Event $event)
{
    abort_if(!$event->isPublished(), 400, 'Event must be published before sending invitations.');

    $years = $event->getGraduationYears();

    $targetAlumni = Alumni::active()
        ->whereIn('graduation_year', $years)
        ->whereNotNull('email')
        ->whereDoesntHave('invitations', fn($q) => $q->where('event_id', $event->id))
        ->get();

    $sent   = 0;
    $failed = 0;

    foreach ($targetAlumni as $alumnus) {
        $token = \Illuminate\Support\Str::random(64);

        Invitation::create([
            'event_id'  => $event->id,
            'alumni_id' => $alumnus->id,
            'token'     => $token,
            'sent_at'   => now(),
        ]);

        try {
            // Send email immediately
            Mail::to($alumnus->email)
                ->send(new \App\Mail\EventInvitationMail($event, $alumnus, $token));

            // Create in-app notification (only if alumni has a user account)
            if ($alumnus->user_id) {
                \App\Models\NotificationLog::send(
                    $alumnus->user_id,
                    'event_invite',
                    "You're Invited: {$event->title}",
                    "You have been invited to attend {$event->title} on " .
                        $event->event_date->format('F j, Y') .
                        ($event->venue ? " at {$event->venue}" : '') .
                        ". Check your email for the full invitation.",
                    route('alumni.profile')
                );
            }

            $sent++;

        } catch (\Exception $e) {
            // Log the failure but continue sending to others
            \Log::error("Failed to send invitation to {$alumnus->email}: " . $e->getMessage());
            $failed++;
        }
    }

    $message = "Invitations sent to {$sent} alumni.";
    if ($failed > 0) {
        $message .= " {$failed} failed (check logs).";
    }

    return back()->with('success', $message);
}

    /**
     * Coordinator updates an event (edit).
     */
    public function edit(Event $event)
    {
        $availableYears = Alumni::active()
            ->distinct()
            ->orderByDesc('graduation_year')
            ->pluck('graduation_year');

        $selectedYears = $event->batches()->pluck('graduation_year')->toArray();

        return view('events.edit', compact('event', 'availableYears', 'selectedYears'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title'              => 'required|string|max:200',
            'description'        => 'nullable|string|max:2000',
            'event_date'         => 'required|date',
            'venue'              => 'nullable|string|max:200',
            'graduation_years'   => 'required|array|min:1',
            'graduation_years.*' => 'integer',
            'status'             => 'required|in:draft,published,cancelled',
        ]);

        $event->update([
            'title'       => $validated['title'],
            'description' => $validated['description'],
            'event_date'  => $validated['event_date'],
            'venue'       => $validated['venue'],
            'status'      => $validated['status'],
        ]);

        $event->batches()->delete();
        foreach ($validated['graduation_years'] as $year) {
            EventBatch::create(['event_id' => $event->id, 'graduation_year' => $year]);
        }

        return redirect()->route('events.show', $event)->with('success', 'Event updated.');
    }

    /**
     * Export attendee list for an event as CSV.
     */
    public function exportAttendees(Event $event)
    {
        $attendees = $event->rsvps()
            ->where('status', 'attending')
            ->with('alumni')
            ->get()
            ->pluck('alumni');

        $filename = 'attendees_' . Str::slug($event->title) . '_' . now()->format('Ymd') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($attendees, $event) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Name', 'Batch Year', 'Email', 'Phone', 'Current Job', 'City']);

            foreach ($attendees as $a) {
                fputcsv($handle, [
                    $a->full_name, $a->graduation_year,
                    $a->email, $a->phone,
                    $a->current_job, $a->city,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
