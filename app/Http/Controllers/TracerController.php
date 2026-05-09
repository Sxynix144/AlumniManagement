<?php

namespace App\Http\Controllers;

use App\Models\TracerSurvey;
use App\Models\TracerResponse;
use App\Models\NotificationLog;
use Illuminate\Http\Request;

class TracerController extends Controller
{
    // ── Alumni ────────────────────────────────────────────────────────────────

    /** Show the active survey form to the alumni */
    public function show()
{
    $user = auth()->user();

    // Staff should go to the admin tracer panel, not the alumni survey
    if ($user->isStaff()) {
        return redirect()->route('admin.tracer.index');
    }

    $alumni = $user->alumniProfile;
    abort_if(!$alumni || $alumni->status !== 'active', 403,
        'Only active alumni can access the tracer study.');

    $survey = TracerSurvey::active()->latest()->first();

    if (!$survey) {
        return view('tracer.survey', [
            'survey'   => null,
            'alumni'   => $alumni,
            'existing' => null,
        ]);
    }

    $existing = TracerResponse::where('survey_id', $survey->id)
        ->where('alumni_id', $alumni->id)
        ->first();

    return view('tracer.survey', compact('survey', 'alumni', 'existing'));
}

    /** Store alumni's tracer survey response */
    public function store(Request $request)
    {
        $alumni = auth()->user()->alumniProfile;
        abort_if(!$alumni || $alumni->status !== 'active', 403);

        $survey = TracerSurvey::active()->latest()->firstOrFail();

        // Prevent duplicate submissions
        $exists = TracerResponse::where('survey_id', $survey->id)
            ->where('alumni_id', $alumni->id)->exists();

        if ($exists) {
            return back()->with('error', 'You have already submitted this survey.');
        }

        $v = $request->validate([
            'employment_status'    => 'required|in:employed_related,employed_unrelated,self_employed,unemployed_looking,unemployed_not_looking,further_studies',
            'job_title'            => 'nullable|string|max:120',
            'employer_name'        => 'nullable|string|max:150',
            'employer_type'        => 'nullable|in:private,government,ngo,self',
            'monthly_salary_range' => 'nullable|string|max:30',
            'months_to_first_job'  => 'nullable|integer|min:0|max:120',
            'job_relevance'        => 'nullable|in:very_relevant,relevant,somewhat,not_relevant',
            'skills_learned'       => 'nullable|string|max:1000',
            'curriculum_feedback'  => 'nullable|string|max:1000',
            'overall_satisfaction' => 'nullable|integer|min:1|max:5',
        ]);

        $v['survey_id'] = $survey->id;
        $v['alumni_id'] = $alumni->id;

        TracerResponse::create($v);

        // Notify the alumni
        NotificationLog::send(
            auth()->id(),
            'tracer_survey',
            'Survey Submitted',
            'Thank you for completing the tracer study survey. Your response helps improve the school.',
            route('tracer.show')
        );

        return redirect()->route('alumni.profile')
                         ->with('success', 'Thank you! Your tracer study response has been submitted.');
    }

    // ── Admin ─────────────────────────────────────────────────────────────────

    /** Dashboard with charts & export */
    public function adminIndex()
    {
        $surveys = TracerSurvey::withCount('responses')->orderByDesc('created_at')->get();

        return view('tracer.admin-index', compact('surveys'));
    }

    public function adminResults(TracerSurvey $survey)
    {
        $responses = $survey->responses()->with('alumni')->get();

        // Stats for charts
        $employmentStats = $responses->groupBy('employment_status')
            ->map->count()->toArray();

        $relevanceStats = $responses->whereNotNull('job_relevance')
            ->groupBy('job_relevance')->map->count()->toArray();

        $satisfactionAvg = $responses->whereNotNull('overall_satisfaction')
            ->avg('overall_satisfaction');

        $salaryStats = $responses->whereNotNull('monthly_salary_range')
            ->groupBy('monthly_salary_range')->map->count()->toArray();

        return view('tracer.admin-results', compact(
            'survey', 'responses',
            'employmentStats', 'relevanceStats',
            'satisfactionAvg', 'salaryStats'
        ));
    }

    public function createSurvey()
    {
        return view('tracer.create');
    }

    public function storeSurvey(Request $request)
    {
        $v = $request->validate([
            'title'               => 'required|string|max:200',
            'description'         => 'nullable|string|max:500',
            'target_years_after'  => 'required|integer|min:1|max:10',
            'is_active'           => 'nullable|boolean',
        ]);

        $v['is_active'] = $request->boolean('is_active');

        TracerSurvey::create($v);

        return redirect()->route('admin.tracer.index')
                         ->with('success', 'Tracer survey created.');
    }

    /** Export responses as CSV */
    public function exportCsv(TracerSurvey $survey)
    {
        $responses = $survey->responses()->with('alumni')->get();

        $filename = 'tracer_study_' . $survey->id . '_' . now()->format('Ymd') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($responses) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Name', 'Batch Year', 'Course',
                'Employment Status', 'Job Title', 'Employer',
                'Employer Type', 'Salary Range', 'Months to First Job',
                'Job Relevance', 'Satisfaction (1-5)',
                'Skills Learned', 'Curriculum Feedback',
            ]);

            foreach ($responses as $r) {
                fputcsv($handle, [
                    $r->alumni->full_name,
                    $r->alumni->graduation_year,
                    $r->alumni->course,
                    $r->employment_label,
                    $r->job_title,
                    $r->employer_name,
                    $r->employer_type,
                    $r->monthly_salary_range,
                    $r->months_to_first_job,
                    TracerResponse::RELEVANCE_LABELS[$r->job_relevance] ?? '',
                    $r->overall_satisfaction,
                    $r->skills_learned,
                    $r->curriculum_feedback,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
