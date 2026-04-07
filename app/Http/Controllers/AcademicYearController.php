<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AcademicYearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $academicYears = AcademicYear::latest()->paginate(10);

        return view('academic-years.index', compact('academicYears'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('academic-years.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'regex:/^\d{4}\/\d{4}$/',
                'unique:academic_years,name',
            ],
        ], [
            'name.regex' => 'Format tahun ajaran harus YYYY/YYYY, contoh: 2023/2024.',
        ]);

        // Single Active Year Rule
        if ($request->boolean('is_active')) {
            AcademicYear::where('is_active', true)->update(['is_active' => false]);
        }

        AcademicYear::create([
            'name' => $request->name,
            'is_active' => $request->boolean('is_active'),
        ]);

        if ($request->boolean('is_active')) {
            $this->flushDashboardCache();
        }

        return redirect()->route('academic-years.index')
            ->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AcademicYear $academicYear)
    {
        return view('academic-years.edit', compact('academicYear'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AcademicYear $academicYear)
    {
        $wasActive = $academicYear->is_active;

        $request->validate([
            'name' => [
                'required',
                'string',
                'regex:/^\d{4}\/\d{4}$/',
                'unique:academic_years,name,'.$academicYear->id,
            ],
        ], [
            'name.regex' => 'Format tahun ajaran harus YYYY/YYYY, contoh: 2023/2024.',
        ]);

        // Single Active Year Rule
        if ($request->boolean('is_active')) {
            AcademicYear::where('is_active', true)
                ->where('id', '!=', $academicYear->id)
                ->update(['is_active' => false]);
        }

        $academicYear->update([
            'name' => $request->name,
            'is_active' => $request->boolean('is_active'),
        ]);

        if ($wasActive || $academicYear->is_active) {
            $this->flushDashboardCache();
        }

        return redirect()->route('academic-years.index')
            ->with('success', 'Tahun ajaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicYear $academicYear)
    {
        $hasRelatedData = $academicYear->industryAllocations()->exists()
            || $academicYear->supervisorAllocations()->exists()
            || $academicYear->internships()->exists()
            || $academicYear->students()->exists();

        if ($hasRelatedData) {
            return redirect()->route('academic-years.index')
                ->with('error', 'Tahun ajaran tidak dapat dihapus karena masih memiliki data tertaut.');
        }

        $academicYear->delete();

        return redirect()->route('academic-years.index')
            ->with('success', 'Tahun ajaran berhasil dihapus.');
    }

    /**
     * Activate a specific academic year (deactivates all others).
     */
    public function activate(AcademicYear $academicYear)
    {
        // Deactivate all
        AcademicYear::where('is_active', true)->update(['is_active' => false]);

        // Activate this one
        $academicYear->update(['is_active' => true]);

        $this->flushDashboardCache();

        return redirect()->route('academic-years.index')
            ->with('success', "Tahun ajaran \"{$academicYear->name}\" berhasil diaktifkan.");
    }

    private function flushDashboardCache(): void
    {
        Cache::forget('dashboard_stats');
        Cache::forget('dashboard_admin_stats');
        Cache::forget('dashboard_curriculum_stats');

        $supervisorIds = User::role('supervisor')->pluck('id');
        foreach ($supervisorIds as $id) {
            Cache::forget('dashboard_supervisor_'.$id);
        }
    }
}
