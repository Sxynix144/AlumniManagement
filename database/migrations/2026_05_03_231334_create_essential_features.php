<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Only create if doesn't exist
        if (!Schema::hasTable('notifications_log')) {
            Schema::create('notifications_log', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('type');
                $table->string('title');
                $table->text('message');
                $table->string('link')->nullable();
                $table->boolean('is_read')->default(false);
                $table->timestamps();
                $table->index(['user_id', 'is_read']);
            });
        }

        if (!Schema::hasTable('faqs')) {
            Schema::create('faqs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
                $table->string('question');
                $table->text('answer');
                $table->string('category')->default('General');
                $table->unsignedInteger('sort_order')->default(0);
                $table->boolean('is_published')->default(true);
                $table->timestamps();
                $table->index(['category', 'is_published']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('notifications_log');
    }
};