<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumni', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('student_number')->nullable()->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->unsignedSmallInteger('graduation_year');
            $table->string('course')->nullable();
            $table->string('current_job')->nullable();
            $table->string('company')->nullable();
            $table->string('city')->nullable();
            $table->text('bio')->nullable();
            $table->string('profile_photo')->nullable();
            $table->enum('status', ['placeholder', 'pending', 'active'])->default('placeholder');
            $table->timestamps();

            $table->index(['graduation_year', 'status']);
            $table->index('last_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumni');
    }
};
