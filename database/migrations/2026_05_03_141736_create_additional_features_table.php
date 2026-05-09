<?php
// ─────────────────────────────────────────────────────────────────────────────
// Migration: announcements, newsletters, gallery_albums, gallery_photos, requests
// Run: php artisan migrate
// ─────────────────────────────────────────────────────────────────────────────

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── ANNOUNCEMENTS ──────────────────────────────────────────────────────
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('body');
            $table->string('category')->default('General'); // General, Event, Office, Opportunity
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->index(['is_published', 'published_at']);
        });

        // ── NEWSLETTERS ───────────────────────────────────────────────────────
        Schema::create('newsletters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path')->nullable();   // uploaded PDF
            $table->string('cover_image')->nullable(); // thumbnail
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        // ── GALLERY ───────────────────────────────────────────────────────────
        Schema::create('gallery_albums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('event_id')->nullable()->constrained('events')->onDelete('set null');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('cover_photo')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('gallery_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('album_id')->constrained('gallery_albums')->onDelete('cascade');
            $table->string('file_path');
            $table->string('caption')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // ── ALUMNI REQUESTS ───────────────────────────────────────────────────
        Schema::create('alumni_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumni_id')->constrained('alumni')->onDelete('cascade');
            $table->enum('type', [
                'alumni_id',
                'yearbook',
                'transcript',
                'certificate',
                'other',
            ]);
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'processing', 'ready', 'released', 'rejected'])
                  ->default('pending');
            $table->text('admin_remarks')->nullable();
            $table->timestamp('released_at')->nullable();
            $table->timestamps();
            $table->index(['alumni_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumni_requests');
        Schema::dropIfExists('gallery_photos');
        Schema::dropIfExists('gallery_albums');
        Schema::dropIfExists('newsletters');
        Schema::dropIfExists('announcements');
    }
};
