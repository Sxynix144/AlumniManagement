@extends('layouts.app')
@section('title', 'My Dashboard')

@push('styles')
<style>
/* ── Dashboard Layout ─────────────────────────────────────── */
.dashboard {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 1.5rem;
    align-items: start;
}
@media(max-width:900px){ .dashboard{ grid-template-columns:1fr; } }

/* ── Profile Card ─────────────────────────────────────────── */
.profile-card {
    background: var(--white);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 24px rgba(15,32,68,.10);
    position: sticky;
    top: 1.5rem;
}
.profile-banner {
    height: 90px;
    background: linear-gradient(135deg, var(--navy) 0%, #1a5276 50%, #c8953a 100%);
    position: relative;
}
.profile-avatar {
    width: 80px; height: 80px;
    border-radius: 50%;
    background: var(--gold);
    border: 4px solid var(--white);
    display: flex; align-items: center; justify-content: center;
    font-family: 'Playfair Display', serif;
    font-size: 2rem;
    font-weight: 700;
    color: var(--navy);
    position: absolute;
    bottom: -40px;
    left: 50%;
    transform: translateX(-50%);
    box-shadow: 0 4px 16px rgba(15,32,68,.2);
}
.profile-body {
    padding: 3rem 1.5rem 1.5rem;
    text-align: center;
}
.profile-name {
    font-family: 'Playfair Display', serif;
    font-size: 1.3rem;
    color: var(--navy);
    margin-bottom: .2rem;
}
.profile-batch {
    font-size: .8rem;
    color: var(--slate);
    text-transform: uppercase;
    letter-spacing: .08em;
    margin-bottom: 1rem;
}
.profile-info-row {
    display: flex;
    align-items: center;
    gap: .6rem;
    padding: .5rem 0;
    border-top: 1px solid var(--light);
    font-size: .83rem;
    color: var(--slate);
    text-align: left;
}
.profile-info-row .icon { font-size: 1rem; flex-shrink:0; }
.profile-info-row strong { color: var(--navy); font-size:.72rem; text-transform:uppercase; letter-spacing:.06em; display:block; }

/* Profile completeness */
.completeness-bar {
    background: var(--light);
    border-radius: 99px;
    height: 8px;
    margin: .5rem 0 .25rem;
    overflow: hidden;
}
.completeness-fill {
    height: 100%;
    border-radius: 99px;
    background: linear-gradient(90deg, var(--gold), #e8b44d);
    transition: width .6s ease;
}

/* ── Main Content ─────────────────────────────────────────── */
.dash-main { display: flex; flex-direction: column; gap: 1.5rem; }

/* ── Welcome Banner ───────────────────────────────────────── */
.welcome-banner {
    background: linear-gradient(135deg, var(--navy) 0%, #1a3566 70%, #2a4a7f 100%);
    border-radius: 16px;
    padding: 1.8rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
    position: relative;
    overflow: hidden;
}
.welcome-banner::before {
    content:'';
    position:absolute; right:-40px; top:-40px;
    width:180px; height:180px;
    border-radius:50%;
    background:rgba(200,149,58,.12);
    pointer-events:none;
}
.welcome-text h2 {
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem;
    color: var(--white);
    margin-bottom: .3rem;
}
.welcome-text p { color: rgba(255,255,255,.65); font-size: .88rem; }
.welcome-stats {
    display: flex; gap: 1rem; flex-wrap:wrap;
}
.welcome-stat {
    background: rgba(255,255,255,.1);
    border: 1px solid rgba(255,255,255,.15);
    border-radius: 10px;
    padding: .7rem 1.1rem;
    text-align: center;
    min-width: 80px;
}
.welcome-stat .num {
    font-family: 'Playfair Display', serif;
    font-size: 1.6rem;
    color: var(--gold2);
    line-height: 1;
}
.welcome-stat .lbl {
    font-size: .65rem;
    color: rgba(255,255,255,.55);
    text-transform: uppercase;
    letter-spacing: .07em;
    margin-top: .2rem;
}

/* ── Section Cards ────────────────────────────────────────── */
.dash-card {
    background: var(--white);
    border-radius: 16px;
    box-shadow: 0 2px 16px rgba(15,32,68,.08);
    overflow: hidden;
}
.dash-card-header {
    padding: 1.1rem 1.5rem;
    border-bottom: 1px solid var(--light);
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.dash-card-header h3 {
    font-family: 'Playfair Display', serif;
    font-size: 1.05rem;
    color: var(--navy);
    display: flex;
    align-items: center;
    gap: .5rem;
}
.dash-card-body { padding: 1.2rem 1.5rem; }

/* ── RSVP Event Cards ─────────────────────────────────────── */
.event-item {
    display: flex;
    gap: 1rem;
    padding: .9rem 0;
    border-bottom: 1px solid var(--light);
    align-items: flex-start;
}
.event-item:last-child { border-bottom: none; }
.event-date-badge {
    background: var(--navy);
    color: var(--white);
    border-radius: 10px;
    padding: .5rem .7rem;
    text-align: center;
    min-width: 52px;
    flex-shrink: 0;
}
.event-date-badge .day {
    font-family: 'Playfair Display', serif;
    font-size: 1.4rem;
    line-height: 1;
    color: var(--gold2);
}
.event-date-badge .mon {
    font-size: .6rem;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: rgba(255,255,255,.6);
}
.event-info h4 { font-size: .92rem; font-weight: 700; color: var(--navy); margin-bottom: .2rem; }
.event-info p  { font-size: .78rem; color: var(--slate); margin-bottom: .5rem; }
.rsvp-btns { display: flex; gap: .4rem; flex-wrap: wrap; }
.rsvp-btn {
    padding: .3rem .7rem;
    border-radius: 99px;
    font-size: .72rem;
    font-weight: 700;
    border: 1.5px solid var(--light);
    background: transparent;
    color: var(--slate);
    cursor: pointer;
    transition: all .15s;
    font-family: 'DM Sans', sans-serif;
}
.rsvp-btn:hover { border-color: var(--gold); color: var(--navy); background: var(--cream); }
.rsvp-btn.active-attending { background: #c6f6d5; color: #22543d; border-color: #9ae6b4; }
.rsvp-btn.active-maybe     { background: #fef3c7; color: #92400e; border-color: #fbd38d; }
.rsvp-btn.active-not       { background: #fed7d7; color: #c53030; border-color: #fc8181; }

/* ── Request Status ───────────────────────────────────────── */
.req-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: .8rem 0;
    border-bottom: 1px solid var(--light);
    gap: 1rem;
}
.req-item:last-child { border-bottom: none; }
.req-type { font-size: .88rem; font-weight: 600; color: var(--navy); }
.req-date  { font-size: .75rem; color: var(--slate); margin-top: .1rem; }

/* ── Announcement Items ───────────────────────────────────── */
.ann-item {
    padding: .8rem 0;
    border-bottom: 1px solid var(--light);
}
.ann-item:last-child { border-bottom: none; }
.ann-item a { text-decoration: none; color: var(--navy); font-weight: 600; font-size: .9rem; }
.ann-item a:hover { color: var(--gold); }
.ann-item p { font-size: .78rem; color: var(--slate); margin: .2rem 0; }
.ann-meta   { font-size: .7rem; color: #aaa; }

/* ── Tracer Prompt ────────────────────────────────────────── */
.tracer-prompt {
    background: linear-gradient(135deg, #1a5276 0%, var(--navy) 100%);
    border-radius: 12px;
    padding: 1.3rem 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}
.tracer-prompt h4 { color: var(--white); font-size: .95rem; font-weight: 700; margin-bottom: .2rem; }
.tracer-prompt p  { color: rgba(255,255,255,.6); font-size: .8rem; }

/* ── Profile Edit Inline ──────────────────────────────────── */
.edit-toggle { display: none; }
.edit-toggle.show { display: block; }

/* ── Milestones ───────────────────────────────────────────── */
.milestone-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
    gap: .75rem;
}
.milestone {
    background: var(--cream);
    border-radius: 10px;
    padding: .9rem;
    text-align: center;
    border: 1.5px solid var(--light);
}
.milestone .m-icon { font-size: 1.8rem; margin-bottom: .3rem; }
.milestone .m-label { font-size: .72rem; font-weight: 700; color: var(--navy); text-transform: uppercase; letter-spacing: .06em; }
.milestone .m-val { font-size: .8rem; color: var(--slate); margin-top: .1rem; }
.milestone.earned { border-color: var(--gold); background: #fffbf0; }

.link-sm {
    font-size: .8rem;
    color: var(--navy);
    font-weight: 700;
    text-decoration: none;
}
.link-sm:hover { color: var(--gold); }
</style>
@endpush

@section('content')

@php
    $user   = auth()->user();
    $alumni = $user->alumniProfile;

    // Profile completeness
    $fields = ['phone','current_job','company','city','bio','email'];
    $filled = collect($fields)->filter(fn($f) => !empty($alumni->$f))->count();
    $completePct = round($filled / count($fields) * 100);

    // Years since graduation
    $yearsSince = date('Y') - $alumni->graduation_year;

    // Stats
    $eventsAttended = \App\Models\Rsvp::where('alumni_id', $alumni->id)
        ->where('status','attending')->count();
    $requestsCount  = \App\Models\AlumniRequest::where('alumni_id', $alumni->id)->count();
    $tracerDone     = \App\Models\TracerResponse::where('alumni_id', $alumni->id)->exists();

    // Upcoming events
    $upcomingEvents = \App\Models\Event::where('status','published')
        ->where('event_date','>=', now())
        ->whereHas('batches', fn($q) => $q->where('graduation_year', $alumni->graduation_year))
        ->with(['rsvps' => fn($q) => $q->where('alumni_id', $alumni->id)])
        ->orderBy('event_date')
        ->take(3)->get();

    // My requests
    $myRequests = \App\Models\AlumniRequest::where('alumni_id', $alumni->id)
        ->orderByDesc('created_at')->take(4)->get();

    // Latest announcements
    $announcements = \App\Models\Announcement::published()->take(3)->get();

    // Invitations from staff
    $invitations = $alumni->invitations()->with('event')->latest('sent_at')->take(5)->get();
@endphp

<div class="dashboard">

    {{-- ── LEFT: Profile Sidebar ─────────────────────────────── --}}
    <div>
        <div class="profile-card">
            <div class="profile-banner"></div>
            <div class="profile-avatar">
                {{ strtoupper(substr($alumni->first_name, 0, 1)) }}
            </div>
            <div class="profile-body">
                <div class="profile-name">{{ $alumni->full_name }}</div>
                <div class="profile-batch">Batch {{ $alumni->graduation_year }} · {{ $alumni->course ?? 'Alumni' }}</div>

                <span class="badge badge-{{ $alumni->status }}" style="margin-bottom:1rem;display:inline-block">
                    {{ ucfirst($alumni->status) }}
                </span>

                {{-- Profile completeness --}}
                <div style="text-align:left;margin-bottom:1rem">
                    <div style="display:flex;justify-content:space-between;font-size:.75rem;margin-bottom:.3rem">
                        <span style="font-weight:700;color:var(--navy)">Profile Completeness</span>
                        <span style="color:var(--gold);font-weight:700">{{ $completePct }}%</span>
                    </div>
                    <div class="completeness-bar">
                        <div class="completeness-fill" style="width:{{ $completePct }}%"></div>
                    </div>
                    @if($completePct < 100)
                        <div style="font-size:.72rem;color:var(--slate);margin-top:.25rem">
                            Fill in all fields to reach 100% ✨
                        </div>
                    @endif
                </div>

                {{-- Info rows --}}
                @if($alumni->email)
                <div class="profile-info-row">
                    <span class="icon">📧</span>
                    <div><strong>Email</strong>{{ $alumni->email }}</div>
                </div>
                @endif
                @if($alumni->phone)
                <div class="profile-info-row">
                    <span class="icon">📱</span>
                    <div><strong>Phone</strong>{{ $alumni->phone }}</div>
                </div>
                @endif
                @if($alumni->current_job)
                <div class="profile-info-row">
                    <span class="icon">💼</span>
                    <div><strong>Position</strong>{{ $alumni->current_job }}{{ $alumni->company ? ' @ '.$alumni->company : '' }}</div>
                </div>
                @endif
                @if($alumni->city)
                <div class="profile-info-row">
                    <span class="icon">📍</span>
                    <div><strong>Location</strong>{{ $alumni->city }}</div>
                </div>
                @endif
                @if($alumni->bio)
                <div class="profile-info-row" style="align-items:flex-start">
                    <span class="icon">📝</span>
                    <div><strong>Bio</strong>{{ $alumni->bio }}</div>
                </div>
                @endif

                {{-- Edit toggle --}}
                <button onclick="toggleEdit()" class="btn btn-outline w-full mt-2" style="margin-top:1rem">
                    ✏️ Edit My Info
                </button>

                {{-- Edit form (hidden by default) --}}
                <div id="edit-form" class="edit-toggle" style="margin-top:1rem;text-align:left">
                    <form method="POST" action="{{ route('alumni.update') }}">
                        @csrf @method('PATCH')
                        @foreach([
                            'phone'       => ['📱','Phone Number','text'],
                            'current_job' => ['💼','Job Title','text'],
                            'company'     => ['🏢','Company','text'],
                            'city'        => ['📍','City','text'],
                        ] as $field => [$icon, $label, $type])
                        <div class="form-group">
                            <label>{{ $icon }} {{ $label }}</label>
                            <input type="{{ $type }}" name="{{ $field }}"
                                   value="{{ old($field, $alumni->$field) }}">
                        </div>
                        @endforeach
                        <div class="form-group">
                            <label>📝 Bio</label>
                            <textarea name="bio" rows="2"
                                style="width:100%;padding:.6rem .8rem;border:1.5px solid #d1d5db;border-radius:6px;font-family:'DM Sans',sans-serif;font-size:.85rem">{{ old('bio', $alumni->bio) }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-gold w-full">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ── RIGHT: Main Dashboard ─────────────────────────────── --}}
    <div class="dash-main">

        {{-- Welcome Banner --}}
        <div class="welcome-banner">
            <div class="welcome-text">
                <h2>Welcome back, {{ $alumni->first_name }}! 👋</h2>
                <p>
                    You've been part of the alumni community for
                    <strong style="color:var(--gold2)">{{ $yearsSince }} {{ Str::plural('year', $yearsSince) }}</strong>.
                    Stay connected and keep your info updated.
                </p>
            </div>
            <div class="welcome-stats">
                <div class="welcome-stat">
                    <div class="num">{{ $yearsSince }}</div>
                    <div class="lbl">Years Since Grad</div>
                </div>
                <div class="welcome-stat">
                    <div class="num">{{ $eventsAttended }}</div>
                    <div class="lbl">Events Attended</div>
                </div>
                <div class="welcome-stat">
                    <div class="num">{{ $requestsCount }}</div>
                    <div class="lbl">Requests</div>
                </div>
                <div class="welcome-stat">
                    <div class="num">{{ $completePct }}%</div>
                    <div class="lbl">Profile Done</div>
                </div>
            </div>
        </div>

        {{-- Tracer Study Prompt --}}
        @if(!$tracerDone)
        <div class="tracer-prompt">
            <div>
                <h4>📊 Complete Your Tracer Study</h4>
                <p>Help the school improve its programs. Required for CHED accreditation. Takes only 3 minutes.</p>
            </div>
            <a href="{{ route('tracer.show') }}" class="btn btn-gold btn-sm" style="white-space:nowrap">
                Take Survey →
            </a>
        </div>
        @else
        <div style="background:#f0fff4;border:1px solid #9ae6b4;border-left:4px solid #48bb78;border-radius:10px;padding:1rem 1.3rem;display:flex;align-items:center;gap:.75rem">
            <span style="font-size:1.5rem">✅</span>
            <div>
                <div style="font-weight:700;color:#22543d;font-size:.9rem">Tracer Study Completed</div>
                <div style="font-size:.78rem;color:#276749">Thank you for participating! Your response helps improve the school.</div>
            </div>
        </div>
        @endif

        {{-- Invitations from Staff --}}
        @if($invitations->count() > 0)
        <div class="dash-card">
            <div class="dash-card-header">
                <h3>💌 Invitations from Staff</h3>
                <span style="font-size:.8rem;color:var(--gold);font-weight:700">{{ $invitations->where('opened_at', null)->count() }} unread</span>
            </div>
            <div class="dash-card-body">
                @foreach($invitations as $invitation)
                    <div class="event-item">
                        <div class="event-date-badge" style="background: {{ $invitation->opened_at ? 'var(--light)' : 'var(--gold)' }};color: {{ $invitation->opened_at ? 'var(--slate)' : 'var(--navy)' }}">
                            <div class="day" style="font-size:1rem;font-weight:700">{{ $invitation->sent_at->format('d') }}</div>
                            <div class="mon">{{ $invitation->sent_at->format('M') }}</div>
                        </div>
                        <div class="event-info" style="flex:1">
                            <h4>{{ $invitation->event->title }}</h4>
                            <p style="margin:0">📅 {{ $invitation->event->event_date->format('M d, Y') }} at {{ $invitation->event->event_date->format('g:i A') }}</p>
                            <p style="margin:.25rem 0 .5rem">{{ $invitation->opened_at ? '✅ Opened ' . $invitation->opened_at->diffForHumans() : '📧 Sent ' . $invitation->sent_at->diffForHumans() }}</p>
                            <form method="POST" action="{{ route('alumni.rsvp', $invitation->event) }}" class="rsvp-btns">
                                @csrf
                                @php
                                    $myRsvp = $invitation->event->rsvps()->where('alumni_id', $alumni->id)->first();
                                @endphp
                                <button type="submit" name="status" value="attending"
                                    class="rsvp-btn {{ $myRsvp?->status === 'attending' ? 'active-attending' : '' }}">
                                    ✅ Going
                                </button>
                                <button type="submit" name="status" value="maybe"
                                    class="rsvp-btn {{ $myRsvp?->status === 'maybe' ? 'active-maybe' : '' }}">
                                    🤔 Maybe
                                </button>
                                <button type="submit" name="status" value="not_attending"
                                    class="rsvp-btn {{ $myRsvp?->status === 'not_attending' ? 'active-not' : '' }}">
                                    ❌ Can't Go
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Two-column row --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem">

            {{-- Upcoming Events --}}
            <div class="dash-card">
                <div class="dash-card-header">
                    <h3>📅 Upcoming Events</h3>
                    <a href="{{ route('announcements.index') }}" class="link-sm">View all →</a>
                </div>
                <div class="dash-card-body">
                    @forelse($upcomingEvents as $event)
                        @php
                            $myRsvp = $event->rsvps->first();
                        @endphp
                        <div class="event-item">
                            <div class="event-date-badge">
                                <div class="day">{{ $event->event_date->format('d') }}</div>
                                <div class="mon">{{ $event->event_date->format('M Y') }}</div>
                            </div>
                            <div class="event-info" style="flex:1">
                                <h4>{{ $event->title }}</h4>
                                <p>📍 {{ $event->venue ?? 'Venue TBA' }}</p>
                                <form method="POST" action="{{ route('alumni.rsvp', $event) }}" class="rsvp-btns">
                                    @csrf
                                    <button type="submit" name="status" value="attending"
                                        class="rsvp-btn {{ $myRsvp?->status === 'attending' ? 'active-attending' : '' }}">
                                        ✅ Going
                                    </button>
                                    <button type="submit" name="status" value="maybe"
                                        class="rsvp-btn {{ $myRsvp?->status === 'maybe' ? 'active-maybe' : '' }}">
                                        🤔 Maybe
                                    </button>
                                    <button type="submit" name="status" value="not_attending"
                                        class="rsvp-btn {{ $myRsvp?->status === 'not_attending' ? 'active-not' : '' }}">
                                        ❌ Can't Go
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div style="text-align:center;padding:2rem;color:var(--slate)">
                            <div style="font-size:2rem;margin-bottom:.5rem">📭</div>
                            <p style="font-size:.85rem">No upcoming events for your batch.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- My Requests --}}
            <div class="dash-card">
                <div class="dash-card-header">
                    <h3>📋 My Requests</h3>
                    <a href="{{ route('requests.index') }}" class="link-sm">Submit new →</a>
                </div>
                <div class="dash-card-body">
                    @forelse($myRequests as $req)
                        <div class="req-item">
                            <div>
                                <div class="req-type">{{ $req->type_label }}</div>
                                <div class="req-date">{{ $req->created_at->format('M j, Y') }}</div>
                            </div>
                            <span class="badge {{ $req->status_color }}">
                                {{ ucfirst($req->status) }}
                            </span>
                        </div>
                    @empty
                        <div style="text-align:center;padding:2rem;color:var(--slate)">
                            <div style="font-size:2rem;margin-bottom:.5rem">📂</div>
                            <p style="font-size:.85rem">No requests yet.</p>
                            <a href="{{ route('requests.index') }}" class="link-sm">Submit your first request →</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Latest Announcements --}}
        <div class="dash-card">
            <div class="dash-card-header">
                <h3>📢 Latest Announcements</h3>
                <a href="{{ route('announcements.index') }}" class="link-sm">View all →</a>
            </div>
            <div class="dash-card-body">
                @forelse($announcements as $ann)
                    <div class="ann-item">
                        <a href="{{ route('announcements.show', $ann) }}">{{ $ann->title }}</a>
                        <p>{{ $ann->excerpt }}</p>
                        <div class="ann-meta">
                            <span class="badge badge-draft" style="font-size:.65rem">{{ $ann->category }}</span>
                            · {{ $ann->published_at->format('M j, Y') }}
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-sm">No announcements yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Milestones --}}
        <div class="dash-card">
            <div class="dash-card-header">
                <h3>🏆 My Milestones</h3>
            </div>
            <div class="dash-card-body">
                <div class="milestone-grid">
                    <div class="milestone earned">
                        <div class="m-icon">🎓</div>
                        <div class="m-label">Alumni</div>
                        <div class="m-val">Batch {{ $alumni->graduation_year }}</div>
                    </div>
                    <div class="milestone {{ $completePct === 100 ? 'earned' : '' }}">
                        <div class="m-icon">{{ $completePct === 100 ? '⭐' : '🔲' }}</div>
                        <div class="m-label">Full Profile</div>
                        <div class="m-val">{{ $completePct }}% done</div>
                    </div>
                    <div class="milestone {{ $eventsAttended > 0 ? 'earned' : '' }}">
                        <div class="m-icon">{{ $eventsAttended > 0 ? '🎉' : '🔲' }}</div>
                        <div class="m-label">Event Goer</div>
                        <div class="m-val">{{ $eventsAttended }} events</div>
                    </div>
                    <div class="milestone {{ $tracerDone ? 'earned' : '' }}">
                        <div class="m-icon">{{ $tracerDone ? '📊' : '🔲' }}</div>
                        <div class="m-label">Tracer Done</div>
                        <div class="m-val">{{ $tracerDone ? 'Completed' : 'Pending' }}</div>
                    </div>
                    <div class="milestone {{ $yearsSince >= 5 ? 'earned' : '' }}">
                        <div class="m-icon">{{ $yearsSince >= 5 ? '🥇' : '🔲' }}</div>
                        <div class="m-label">5-Year Club</div>
                        <div class="m-val">{{ $yearsSince }} yrs ago</div>
                    </div>
                    <div class="milestone {{ $requestsCount > 0 ? 'earned' : '' }}">
                        <div class="m-icon">{{ $requestsCount > 0 ? '📋' : '🔲' }}</div>
                        <div class="m-label">Requester</div>
                        <div class="m-val">{{ $requestsCount }} requests</div>
                    </div>
                </div>
            </div>
        </div>

    </div>{{-- end .dash-main --}}
</div>{{-- end .dashboard --}}

@push('scripts')
<script>
function toggleEdit() {
    const form = document.getElementById('edit-form');
    form.classList.toggle('show');
}
</script>
@endpush
@endsection
