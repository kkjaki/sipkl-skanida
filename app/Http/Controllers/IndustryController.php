<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApproveProposalRequest;
use App\Http\Requests\StoreIndustryRequest;
use App\Http\Requests\UpdateIndustryRequest;
use App\Models\AcademicYear;
use App\Models\Department;
use App\Models\Industry;
use App\Models\IndustryAllocation;
use App\Models\Internship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class IndustryController extends Controller
{
    /**
     * Display a listing of the resource (Admin).
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $filter = $request->query('filter');

        $industries = Industry::with('studentSubmitter')
            ->when($filter === 'proposal', function ($query) {
                $query->proposedByStudents();
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%")
                        ->orWhere('contact_person', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('industries.index', compact('industries', 'search', 'filter'));
    }

    /**
     * Show the form for creating a new resource (Admin Master Data).
     */
    public function create()
    {
        $departments = Department::all();

        return view('industries.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage (Admin Master Data).
     * Admin-created industries are always is_synced = true, status = open.
     */
    public function store(StoreIndustryRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            $industry = Industry::create([
                'name' => $validated['name'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'contact_person' => $validated['contact_person'] ?? null,
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'pic_name' => $validated['pic_name'] ?? null,
                'pic_position' => $validated['pic_position'] ?? null,
                'nip' => $validated['nip'] ?? null,
                'is_synced' => true,
                'status' => 'open',
                'student_submitter_id' => null,
            ]);

            $this->syncAllocations($industry, $validated['quotas'] ?? []);
        });

        Cache::forget('dashboard_stats');

        return redirect()->route('industries.index')
            ->with('success', 'Data industri berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource (Admin).
     */
    public function edit(string $id)
    {
        $industry = Industry::with('allocations')->findOrFail($id);
        $departments = Department::all();
        $activeYear = AcademicYear::where('is_active', true)->first();

        $existingQuotas = $activeYear
            ? $industry->allocations
                ->where('academic_year_id', $activeYear->id)
                ->pluck('quota', 'department_id')
            : collect();

        // Check for ongoing internships
        $hasOngoingInternships = Internship::where('industry_id', $industry->id)
            ->where('status', 'ongoing')
            ->exists();

        return view('industries.edit', compact('industry', 'departments', 'existingQuotas', 'hasOngoingInternships'));
    }

    /**
     * Update the specified resource in storage (Admin).
     */
    public function update(UpdateIndustryRequest $request, string $id)
    {
        $validated = $request->validated();
        $industry = Industry::findOrFail($id);

        DB::transaction(function () use ($validated, $industry) {
            $industry->update([
                'name' => $validated['name'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'contact_person' => $validated['contact_person'] ?? null,
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'pic_name' => $validated['pic_name'] ?? null,
                'pic_position' => $validated['pic_position'] ?? null,
                'nip' => $validated['nip'] ?? null,
            ]);

            // Only sync quotas if industry is verified and not blacklisted
            if ($industry->is_synced && $industry->status !== 'blacklisted') {
                // Block quota changes if ongoing internships exist
                $hasOngoing = Internship::where('industry_id', $industry->id)
                    ->where('status', 'ongoing')
                    ->exists();

                if (! $hasOngoing) {
                    $this->syncAllocations($industry, $validated['quotas'] ?? []);
                }
            }
        });

        Cache::forget('dashboard_stats');

        return redirect()->route('industries.index')
            ->with('success', 'Data industri berhasil diperbarui.');
    }

    /**
     * Show the quota allocation form for a verified industry (Admin).
     * Only for industries that have been synced (is_synced = true) by Kaprog.
     */
    public function allocate(string $id)
    {
        $industry = Industry::with(['studentSubmitter', 'allocations'])->findOrFail($id);

        if (! $industry->is_synced) {
            return redirect()->route('industries.index')
                ->with('error', 'Industri ini belum diverifikasi oleh Kepala Program. Hubungi Kaprog terkait untuk sinkronisasi kurikulum terlebih dahulu.');
        }

        $departments = Department::all();
        $activeYear = AcademicYear::where('is_active', true)->first();

        $existingQuotas = $activeYear
            ? $industry->allocations
                ->where('academic_year_id', $activeYear->id)
                ->pluck('quota', 'department_id')
            : collect();

        // Check for ongoing internships
        $hasOngoingInternships = Internship::where('industry_id', $industry->id)
            ->where('status', 'ongoing')
            ->exists();

        return view('industries.allocate', compact('industry', 'departments', 'existingQuotas', 'hasOngoingInternships'));
    }

    /**
     * Store/update quota allocations for a verified industry (Admin).
     * Hard validation: is_synced must be true.
     */
    public function storeAllocation(ApproveProposalRequest $request, string $id)
    {
        $validated = $request->validated();
        $industry = Industry::findOrFail($id);

        if (! $industry->is_synced) {
            return redirect()->route('industries.index')
                ->with('error', 'Industri ini belum diverifikasi oleh Kepala Program.');
        }

        // Block quota changes if ongoing internships exist
        $hasOngoing = Internship::where('industry_id', $industry->id)
            ->where('status', 'ongoing')
            ->exists();

        if ($hasOngoing) {
            return redirect()->route('industries.index')
                ->with('error', 'Kuota tidak dapat diubah karena terdapat siswa yang sedang PKL (ongoing) di industri ini.');
        }

        DB::transaction(function () use ($validated, $industry) {
            $this->syncAllocations($industry, $validated['quotas'] ?? []);
        });

        Cache::forget('dashboard_stats');

        return redirect()->route('industries.index')
            ->with('success', 'Alokasi kuota industri berhasil disimpan.');
    }

    /**
     * Display the specified industry with partnerships (MoU).
     */
    public function show(Industry $industry)
    {
        $industry->load([
            'partnerships' => fn ($q) => $q->latest(),
            'allocations.department',
            'studentSubmitter',
        ]);

        return view('industries.show', compact('industry'));
    }

    /**
     * Remove the specified resource from storage (Admin - Soft Delete).
     */
    public function destroy(string $id)
    {
        $industry = Industry::findOrFail($id);
        $industry->delete();

        Cache::forget('dashboard_stats');

        return redirect()->route('industries.index')
            ->with('success', 'Data industri berhasil dihapus.');
    }

    /**
     * Sync industry allocations for the active academic year.
     * Uses updateOrCreate for idempotency, deletes allocations with 0 quota.
     */
    private function syncAllocations(Industry $industry, array $quotas): void
    {
        $activeYear = AcademicYear::where('is_active', true)->first();

        if (! $activeYear) {
            return;
        }

        foreach ($quotas as $departmentId => $qty) {
            $qty = (int) ($qty ?? 0);

            if ($qty > 0) {
                IndustryAllocation::updateOrCreate(
                    [
                        'industry_id' => $industry->id,
                        'department_id' => $departmentId,
                        'academic_year_id' => $activeYear->id,
                    ],
                    ['quota' => $qty]
                );
            } else {
                IndustryAllocation::where([
                    'industry_id' => $industry->id,
                    'department_id' => $departmentId,
                    'academic_year_id' => $activeYear->id,
                ])->delete();
            }
        }
    }
}
