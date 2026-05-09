<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AlumniController extends Controller
{
    /**
     * Public search page — alumni enter name + graduation year.
     */
    public function search(Request $request)
    {
        $results = collect();
        $query   = $request->only(['name', 'graduation_year']);

        if ($request->filled('name')) {
            $results = Alumni::query()
                ->when($request->filled('graduation_year'), fn($q) =>
                    $q->byYear((int) $request->graduation_year)
                )
                ->get();
        }

        return view('alumni.search', compact('results', 'query'));
    }

    /**
     * Show a single placeholder record for claiming.
     */
    public function showClaim(Alumni $alumni)
    {
        abort_if($alumni->isClaimed(), 404, 'This profile has already been claimed.');
        return view('alumni.claim', compact('alumni'));
    }

    /**
     * Alumni claims an existing placeholder record and registers.
     */
    public function claim(Request $request, Alumni $alumni)
    {
        abort_if($alumni->isClaimed(), 409);

        $validated = $request->validate([
            'email'           => 'required|email|unique:users,email',
            'password'        => 'required|string|min:8|confirmed',
            'phone'           => 'nullable|string|max:20',
            'current_job'     => 'nullable|string|max:120',
            'company'         => 'nullable|string|max:120',
            'city'            => 'nullable|string|max:80',
            'bio'             => 'nullable|string|max:500',
        ]);

        // Create the linked user account
        $user = User::create([
            'name'     => $alumni->full_name,
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => 'alumni',
        ]);

        // Update alumni record
        $alumni->update([
            'user_id'     => $user->id,
            'email'       => $validated['email'],
            'phone'       => $validated['phone'],
            'current_job' => $validated['current_job'],
            'company'     => $validated['company'],
            'city'        => $validated['city'],
            'bio'         => $validated['bio'],
            'status'      => 'active',
        ]);

        Auth::login($user);

        // 🔑 Fire Registered event — triggers email verification
        event(new Registered($user));

        // Redirect to verification notice (not directly to profile)
        return redirect()->route('verification.notice')
                         ->with('success', 'Account created! Please check your email to verify your address.');
    }

    /**
     * Self-register if no matching record found.
     */
    public function createForm()
    {
        return view('alumni.register');
    }

    /**
     * Store a new self-registered alumni (status = pending, awaiting admin approval).
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'first_name'      => 'required|string|max:80',
        'last_name'       => 'required|string|max:80',
        'email'           => 'required|email|unique:users,email',
        'password'        => 'required|string|min:8|confirmed',
        'graduation_year' => 'required|integer|min:1970|max:' . date('Y'),
        'course'          => 'nullable|string|max:120',
        'phone'           => 'nullable|string|max:20',
        'current_job'     => 'nullable|string|max:120',
        'company'         => 'nullable|string|max:120',
        'city'            => 'nullable|string|max:80',
    ]);

    $user = User::create([
        'name'     => "{$validated['first_name']} {$validated['last_name']}",
        'email'    => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role'     => 'alumni',
    ]);

    Alumni::create([
        'user_id'         => $user->id,   // ✅ Fix: was null, now links to the user
        'first_name'      => $validated['first_name'],
        'last_name'       => $validated['last_name'],
        'email'           => $validated['email'],
        'graduation_year' => $validated['graduation_year'],
        'course'          => $validated['course'] ?? null,
        'phone'           => $validated['phone'] ?? null,
        'current_job'     => $validated['current_job'] ?? null,
        'company'         => $validated['company'] ?? null,
        'city'            => $validated['city'] ?? null,
        'status'          => 'pending',
    ]);

    Auth::login($user);

    event(new Registered($user));

    return redirect()->route('verification.notice')
                     ->with('success', 'Registration submitted! Please verify your email first, then wait for admin approval.');
}

    /**
     * Authenticated alumni views their own profile.
     * Requires email to be verified.
     */
    public function profile()
    {
        $alumni = Auth::user()->alumniProfile;

        if (!$alumni) {
            return redirect()->route('alumni.register');
        }

        $upcomingEvents = \App\Models\Event::where('status', 'published')
            ->where('event_date', '>=', now())
            ->whereHas('batches', fn($q) => $q->where('graduation_year', $alumni->graduation_year))
            ->with('rsvps')
            ->orderBy('event_date')
            ->take(5)
            ->get();

        return view('alumni.profile', compact('alumni', 'upcomingEvents'));
    }

    /**
     * Update the authenticated alumni's profile.
     */
    public function update(Request $request)
    {
        $alumni = Auth::user()->alumniProfile;
        abort_if(!$alumni, 404);

        $validated = $request->validate([
            'phone'       => 'nullable|string|max:20',
            'current_job' => 'nullable|string|max:120',
            'company'     => 'nullable|string|max:120',
            'city'        => 'nullable|string|max:80',
            'bio'         => 'nullable|string|max:500',
        ]);

        $alumni->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * RSVP to an event.
     */
    public function rsvp(Request $request, \App\Models\Event $event)
    {
        $alumni = Auth::user()->alumniProfile;
        abort_if(!$alumni, 403);

        $status = $request->validate(['status' => 'required|in:attending,not_attending,maybe'])['status'];

        \App\Models\Rsvp::updateOrCreate(
            ['event_id' => $event->id, 'alumni_id' => $alumni->id],
            ['status' => $status]
        );

        return back()->with('success', 'Your RSVP has been recorded.');
    }
}
