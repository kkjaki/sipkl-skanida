<?php

namespace App\Http\Controllers;

use App\Models\EvaluationIndicator;
use Illuminate\Http\Request;

class EvaluationIndicatorController extends Controller
{
    /**
     * Display a listing of evaluation indicators.
     */
    public function index()
    {
        $indicators = EvaluationIndicator::orderBy('id')->get();

        return view('evaluation-indicators.index', compact('indicators'));
    }

    /**
     * Store a newly created evaluation indicator.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:evaluation_indicators,name',
        ]);

        EvaluationIndicator::create($request->only('name'));

        return redirect()->route('evaluation-indicators.index')
            ->with('success', 'Indikator penilaian berhasil ditambahkan.');
    }

    /**
     * Update the specified evaluation indicator.
     */
    public function update(Request $request, EvaluationIndicator $evaluationIndicator)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:evaluation_indicators,name,' . $evaluationIndicator->id,
        ]);

        $evaluationIndicator->update($request->only('name'));

        return redirect()->route('evaluation-indicators.index')
            ->with('success', 'Indikator penilaian berhasil diupdate.');
    }

    /**
     * Remove the specified evaluation indicator.
     */
    public function destroy(EvaluationIndicator $evaluationIndicator)
    {
        $evaluationIndicator->delete();

        return redirect()->route('evaluation-indicators.index')
            ->with('success', 'Indikator penilaian berhasil dihapus.');
    }
}
