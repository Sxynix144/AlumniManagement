<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('event_date');
            $table->string('venue')->nullable();
            $table->string('banner_image')->nullable();
            $table->enum('status', ['draft', 'published', 'cancelled'])->default('draft');
            $table->timestamps();
        });

        Schema::create('event_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->unsignedSmallInteger('graduation_year');
            $table->unique(['event_id', 'graduation_year']);
        });

        Schema::create('rsvps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('alumni_id')->constrained('alumni')->onDelete('cascade');
            $table->enum('status', ['attending', 'not_attending', 'maybe'])->default('attending');
            $table->timestamps();
            $table->unique(['event_id', 'alumni_id']);
        });

        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('alumni_id')->constrained('alumni')->onDelete('cascade');
            $table->string('token')->unique();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invitations');
        Schema::dropIfExists('rsvps');
        Schema::dropIfExists('event_batches');
        Schema::dropIfExists('events');
    }
};
