<?php

namespace App\Http\Controllers;

use App\Models\AssessmentScore;
use App\Models\EvaluationIndicator;
use App\Models\Internship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssessmentController extends Controller
{
    /**
     * Display list of students under the logged-in supervisor.
     */
    public function index()
    {
        $supervisorId = Auth::id();

        $totalIndicators = EvaluationIndicator::count();

        $internships = Internship::where('supervisor_id', $supervisorId)
            ->with(['student.user', 'industry'])
            ->withCount('assessmentScores as scored_count')
            ->get();

        return view('assessments.index', compact('internships', 'totalIndicators'));
    }

    /**
     * Show the assessment form for a specific internship.
     */
    public function edit(Internship $internship)
    {
        // Security: pastikan internship milik supervisor yang login
        if ($internship->supervisor_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        $internship->load(['student.user', 'industry']);

        $evaluationIndicators = EvaluationIndicator::all();

        // Ambil skor yang sudah ada, keyed by indicator_id untuk akses O(1)
        $assessmentScores = $internship->assessmentScores
            ->keyBy('indicator_id');

        return view('assessments.form', compact('internship', 'evaluationIndicators', 'assessmentScores'));
    }

    /**
     * Update or create assessment scores for a specific internship.
     */
    public function update(Request $request, Internship $internship)
    {
        // Security: pastikan internship milik supervisor yang login
        if ($internship->supervisor_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        $request->validate([
            'scores'                        => 'required|array',
            'scores.*.indicator_id'         => 'required|exists:evaluation_indicators,id',
            'scores.*.industry_score'       => 'nullable|numeric|min:0|max:100',
            'scores.*.supervisor_score'     => 'nullable|numeric|min:0|max:100',
        ]);

        try {
            DB::transaction(function () use ($request, $internship) {
                foreach ($request->scores as $score) {
                    AssessmentScore::updateOrCreate(
                        [
                            'internship_id' => $internship->id,
                            'indicator_id'  => $score['indicator_id'],
                        ],
                        [
                            'score_industry' => $score['industry_score'] ?? null,
                            'score_school'   => $score['supervisor_score'] ?? null,
                        ]
                    );
                }
            });

            return redirect()
                ->route('supervisor.assessments.edit', $internship)
                ->with('success', 'Nilai berhasil disimpan.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal menyimpan nilai: ' . $e->getMessage())
                ->withInput();
        }
    }
}
