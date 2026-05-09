<?php

use App\Http\Controllers\AlumniController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\AlumniRequestController;
use App\Http\Controllers\TracerController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\FaqController;
use Illuminate\Support\Facades\Route;

// ── Public Routes ─────────────────────────────────────────────────────────────

Route::get('/', fn() => view('home'))->name('home');

Route::get('/find-my-record',      [AlumniController::class, 'search'])->name('alumni.search');
Route::get('/register-as-alumni',  [AlumniController::class, 'createForm'])->name('alumni.register');
Route::post('/register-as-alumni', [AlumniController::class, 'store'])->name('alumni.store');
Route::get('/claim/{alumni}',      [AlumniController::class, 'showClaim'])->name('alumni.claim.show');
Route::post('/claim/{alumni}',     [AlumniController::class, 'claim'])->name('alumni.claim');

Route::get('/announcements',                [AnnouncementController::class, 'index'])->name('announcements.index');
Route::get('/announcements/{announcement}', [AnnouncementController::class, 'show'])->name('announcements.show');
Route::get('/newsletters',                  [NewsletterController::class,   'index'])->name('newsletters.index');
Route::get('/newsletters/{newsletter}',     [NewsletterController::class,   'show'])->name('newsletters.show');
Route::get('/gallery',                      [GalleryController::class,      'index'])->name('gallery.index');
Route::get('/gallery/{album}',              [GalleryController::class,      'show'])->name('gallery.show');
Route::get('/faqs',                         [FaqController::class,          'index'])->name('faqs.index');

// ── Authenticated + Verified Alumni Routes ────────────────────────────────────

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/my-profile',           [AlumniController::class,        'profile'])->name('alumni.profile');
    Route::patch('/my-profile',         [AlumniController::class,        'update'])->name('alumni.update');
    Route::post('/events/{event}/rsvp', [AlumniController::class,        'rsvp'])->name('alumni.rsvp');

    Route::get('/my-requests',          [AlumniRequestController::class, 'myRequests'])->name('requests.index');
    Route::post('/my-requests',         [AlumniRequestController::class, 'store'])->name('requests.store');

    Route::get('/tracer-study',         [TracerController::class,        'show'])->name('tracer.show');
    Route::post('/tracer-study',        [TracerController::class,        'store'])->name('tracer.store');

    Route::get('/notifications',                     [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/count',               [NotificationController::class, 'unreadCount'])->name('notifications.count');
    Route::get('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read',      [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::delete('/notifications/{notification}',   [NotificationController::class, 'destroy'])->name('notifications.destroy');
});

// ── Staff Routes — admin + coordinator (dashboard & content) ──────────────────

Route::middleware(['auth', 'staff'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/announcements',                     [AnnouncementController::class, 'adminIndex'])->name('announcements.index');
    Route::get('/announcements/create',              [AnnouncementController::class, 'create'])->name('announcements.create');
    Route::post('/announcements',                    [AnnouncementController::class, 'store'])->name('announcements.store');
    Route::get('/announcements/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('announcements.edit');
    Route::patch('/announcements/{announcement}',    [AnnouncementController::class, 'update'])->name('announcements.update');
    Route::delete('/announcements/{announcement}',   [AnnouncementController::class, 'destroy'])->name('announcements.destroy');

    Route::get('/newsletters',                 [NewsletterController::class, 'adminIndex'])->name('newsletters.index');
    Route::get('/newsletters/create',          [NewsletterController::class, 'create'])->name('newsletters.create');
    Route::post('/newsletters',                [NewsletterController::class, 'store'])->name('newsletters.store');
    Route::delete('/newsletters/{newsletter}', [NewsletterController::class, 'destroy'])->name('newsletters.destroy');

    Route::get('/gallery',              [GalleryController::class, 'adminIndex'])->name('gallery.index');
    Route::get('/gallery/create',       [GalleryController::class, 'create'])->name('gallery.create');
    Route::post('/gallery',             [GalleryController::class, 'store'])->name('gallery.store');
    Route::delete('/gallery/{album}',   [GalleryController::class, 'destroy'])->name('gallery.destroy');

    Route::get('/requests',                          [AlumniRequestController::class, 'adminIndex'])->name('requests.index');
    Route::patch('/requests/{alumniRequest}/status', [AlumniRequestController::class, 'updateStatus'])->name('requests.updateStatus');

    Route::get('/tracer',                  [TracerController::class, 'adminIndex'])->name('tracer.index');
    Route::get('/tracer/create',           [TracerController::class, 'createSurvey'])->name('tracer.create');
    Route::post('/tracer',                 [TracerController::class, 'storeSurvey'])->name('tracer.store');
    Route::get('/tracer/{survey}/results', [TracerController::class, 'adminResults'])->name('tracer.results');
    Route::get('/tracer/{survey}/export',  [TracerController::class, 'exportCsv'])->name('tracer.export');

    Route::get('/faqs',              [FaqController::class, 'adminIndex'])->name('faqs.index');
    Route::get('/faqs/create',       [FaqController::class, 'create'])->name('faqs.create');
    Route::post('/faqs',             [FaqController::class, 'store'])->name('faqs.store');
    Route::get('/faqs/{faq}/edit',   [FaqController::class, 'edit'])->name('faqs.edit');
    Route::patch('/faqs/{faq}',      [FaqController::class, 'update'])->name('faqs.update');
    Route::delete('/faqs/{faq}',     [FaqController::class, 'destroy'])->name('faqs.destroy');
});

// ── Admin-Only Routes ─────────────────────────────────────────────────────────

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/pending',                   [AdminController::class, 'pending'])->name('pending');
    Route::post('/alumni/{alumni}/approve',  [AdminController::class, 'approve'])->name('approve');
    Route::delete('/alumni/{alumni}/reject', [AdminController::class, 'reject'])->name('reject');
    Route::get('/directory',                 [AdminController::class, 'directory'])->name('directory');
    Route::get('/export',                    [AdminController::class, 'export'])->name('export');
    Route::get('/import',                    [AdminController::class, 'importForm'])->name('import.form');
    Route::post('/import',                   [AdminController::class, 'import'])->name('import');
});

// ── Events Routes (staff: admin + coordinator) ────────────────────────────────

Route::middleware(['auth', 'staff'])->prefix('events')->name('events.')->group(function () {
    Route::get('/',                          [EventController::class, 'index'])->name('index');
    Route::get('/create',                    [EventController::class, 'create'])->name('create');
    Route::post('/',                         [EventController::class, 'store'])->name('store');
    Route::get('/{event}',                   [EventController::class, 'show'])->name('show');
    Route::get('/{event}/edit',              [EventController::class, 'edit'])->name('edit');
    Route::patch('/{event}',                 [EventController::class, 'update'])->name('update');
    Route::post('/{event}/send-invitations', [EventController::class, 'sendInvitations'])->name('sendInvitations');
    Route::get('/{event}/export-attendees',  [EventController::class, 'exportAttendees'])->name('exportAttendees');
});

// ── Auth Routes (Breeze) ──────────────────────────────────────────────────────

require __DIR__ . '/auth.php';