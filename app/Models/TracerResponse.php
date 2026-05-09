<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TracerResponse extends Model
{
    protected $table = 'tracer_responses';

    protected $fillable = [
        'survey_id', 'alumni_id',
        'employment_status', 'job_title', 'employer_name',
        'employer_type', 'monthly_salary_range', 'months_to_first_job',
        'job_relevance', 'skills_learned', 'curriculum_feedback',
        'overall_satisfaction',
    ];

    const EMPLOYMENT_LABELS = [
        'employed_related'       => '💼 Employed — Related to Course',
        'employed_unrelated'     => '💼 Employed — Unrelated to Course',
        'self_employed'          => '🏢 Self-Employed / Business Owner',
        'unemployed_looking'     => '🔍 Unemployed — Actively Looking',
        'unemployed_not_looking' => '⏸️ Unemployed — Not Looking',
        'further_studies'        => '📚 Pursuing Further Studies',
    ];

    const RELEVANCE_LABELS = [
        'very_relevant'  => '⭐⭐⭐⭐ Very Relevant',
        'relevant'       => '⭐⭐⭐ Relevant',
        'somewhat'       => '⭐⭐ Somewhat Relevant',
        'not_relevant'   => '⭐ Not Relevant',
    ];

    const SALARY_RANGES = [
        'below_10k'   => 'Below ₱10,000',
        '10k_15k'     => '₱10,000 – ₱15,000',
        '15k_25k'     => '₱15,000 – ₱25,000',
        '25k_40k'     => '₱25,000 – ₱40,000',
        '40k_60k'     => '₱40,000 – ₱60,000',
        'above_60k'   => 'Above ₱60,000',
    ];

    public function survey()  { return $this->belongsTo(TracerSurvey::class); }
    public function alumni()  { return $this->belongsTo(Alumni::class); }

    public function getEmploymentLabelAttribute(): string
    {
        return self::EMPLOYMENT_LABELS[$this->employment_status] ?? $this->employment_status;
    }

    public function isEmployed(): bool
    {
        return in_array($this->employment_status, ['employed_related', 'employed_unrelated', 'self_employed']);
    }
}
