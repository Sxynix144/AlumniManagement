<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AdminController extends Controller
{
    /**
     * Admin dashboard — live stats + recent activity.
     */
    public function dashboard()
    {
        $stats = [
            'total'       => Alumni::active()->count(),
            'pending'     => Alumni::pending()->count(),
            'placeholder' => Alumni::placeholder()->count(),
            'events'      => Event::count(),
        ];

        $recentUpdates = Alumni::active()
            ->orderByDesc('updated_at')
            ->take(10)
            ->get();

        $pendingAlumni = Alumni::pending()
            ->orderBy('created_at')
            ->take(20)
            ->get();

        $upcomingEvents = Event::where('status', 'published')
            ->where('event_date', '>=', now())
            ->orderBy('event_date')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentUpdates', 'pendingAlumni', 'upcomingEvents'));
    }

    /**
     * List all pending registrations for review.
     */
    public function pending()
    {
        $pending = Alumni::pending()
            ->with('user')
            ->orderBy('created_at')
            ->paginate(20);

        return view('admin.pending', compact('pending'));
    }

    /**
     * Approve a pending alumni profile.
     */
 public function approve(Alumni $alumni)
{
    abort_if($alumni->status !== 'pending', 400, 'This record is not in a pending state.');

    $alumni->update(['status' => 'active']);

    // ✅ Also mark email as verified so they can access their profile
    if ($alumni->user && ! $alumni->user->hasVerifiedEmail()) {
        $alumni->user->markEmailAsVerified();
    }

    return back()->with('success', "{$alumni->full_name} has been approved.");
}
    public function reject(Alumni $alumni)
    {
        abort_if($alumni->status !== 'pending', 400);

        $user = $alumni->user;
        $alumni->delete();
        $user?->delete();

        return back()->with('success', 'Registration rejected and removed.');
    }

    /**
     * Browse the full alumni directory.
     */
    public function directory(Request $request)
    {
        $alumni = Alumni::active()
            ->when($request->filled('search'), fn($q) => $q->search($request->search))
            ->when($request->filled('year'),   fn($q) => $q->byYear((int) $request->year))
            ->orderBy('last_name')
            ->paginate(25)
            ->withQueryString();

        $years = Alumni::active()
            ->distinct()
            ->orderByDesc('graduation_year')
            ->pluck('graduation_year');

        return view('admin.directory', compact('alumni', 'years'));
    }

    /**
     * Export active alumni as a CSV file.
     */
    public function export(Request $request)
    {
        $year = $request->integer('year');

        $alumni = Alumni::active()
            ->when($year, fn($q) => $q->byYear($year))
            ->orderBy('graduation_year')
            ->orderBy('last_name')
            ->get([
                'student_number', 'first_name', 'last_name',
                'graduation_year', 'course', 'email', 'phone',
                'current_job', 'company', 'city',
            ]);

        $filename = 'alumni_export_' . ($year ?: 'all') . '_' . now()->format('Ymd') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($alumni) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Student No.', 'First Name', 'Last Name',
                'Batch Year', 'Course', 'Email', 'Phone',
                'Current Job', 'Company', 'City',
            ]);

            foreach ($alumni as $a) {
                fputcsv($handle, [
                    $a->student_number, $a->first_name, $a->last_name,
                    $a->graduation_year, $a->course, $a->email, $a->phone,
                    $a->current_job, $a->company, $a->city,
                ]);
            }

            fclose($handle);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Import alumni from a CSV file (batch import of historical data).
     */
    public function importForm()
    {
        return view('admin.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        $path    = $request->file('csv_file')->getRealPath();
        $handle  = fopen($path, 'r');
        $headers = fgetcsv($handle); // skip header row

        $imported = 0;
        $skipped  = 0;

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 4) { $skipped++; continue; }

            [$studentNumber, $firstName, $lastName, $graduationYear, $course] = array_pad($row, 5, null);

            $exists = Alumni::where('student_number', trim($studentNumber))->exists();
            if ($exists) { $skipped++; continue; }

            Alumni::create([
                'student_number'  => trim($studentNumber),
                'first_name'      => trim($firstName),
                'last_name'       => trim($lastName),
                'graduation_year' => (int) trim($graduationYear),
                'course'          => $course ? trim($course) : null,
                'status'          => 'placeholder',
            ]);

            $imported++;
        }

        fclose($handle);

        return back()->with('success', "Import complete. {$imported} records added, {$skipped} skipped.");
    }
}
