<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tracer_surveys')) {
            Schema::create('tracer_surveys', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->integer('target_years_after')->default(1);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('tracer_responses')) {
            Schema::create('tracer_responses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('survey_id')->constrained('tracer_surveys')->onDelete('cascade');
                $table->foreignId('alumni_id')->constrained('alumni')->onDelete('cascade');
                $table->enum('employment_status', [
                    'employed_related','employed_unrelated','self_employed',
                    'unemployed_looking','unemployed_not_looking','further_studies'
                ]);
                $table->string('job_title')->nullable();
                $table->string('employer_name')->nullable();
                $table->string('employer_type')->nullable();
                $table->string('monthly_salary_range')->nullable();
                $table->integer('months_to_first_job')->nullable();
                $table->enum('job_relevance', ['very_relevant','relevant','somewhat','not_relevant'])->nullable();
                $table->text('skills_learned')->nullable();
                $table->text('curriculum_feedback')->nullable();
                $table->integer('overall_satisfaction')->nullable();
                $table->timestamps();
                $table->unique(['survey_id', 'alumni_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tracer_responses');
        Schema::dropIfExists('tracer_surveys');
    }
};