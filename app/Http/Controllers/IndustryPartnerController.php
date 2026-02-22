<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Department;
use App\Models\Industry;
use App\Models\IndustryAllocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndustryPartnerController extends Controller
{
    /**
     * Show the confirmation form for industry partners.
     */
    public function edit(Industry $industry)
    {
        // Kondisi TERKUNCI: Jika sudah mengisi data PIC
        if ($industry->pic_name && $industry->pic_position) {
            return view('mitra.locked', compact('industry'));
        }

        $departments = Department::all();
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();
        
        $existingQuotas = $industry->allocations()
            ->where('academic_year_id', $activeYear->id)
            ->pluck('quota', 'department_id');

        return view('mitra.confirm', compact('industry', 'departments', 'activeYear', 'existingQuotas'));
    }
    

    /**
     * Update industry profile and allocations from partner portal.
     */
    public function update(Request $request, Industry $industry)
    {
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();


        // Lockout Check: Prevent updates if already submitted
        if ($industry->pic_name && $industry->pic_position) {
            return view('mitra.locked', compact('industry'));
        }

        $validated = $request->validate([
            'pic_name'     => ['required', 'string', 'max:255'],
            'pic_position' => ['required', 'string', 'max:255'],
            'nip'          => ['required', 'string', 'max:255'],
            'quotas'       => ['required', 'array'],
            'quotas.*'     => ['nullable', 'integer', 'min:0'],
        ]);

        // Validasi: Total kuota harus > 0
        $totalQuota = array_sum(array_map('intval', $validated['quotas']));
        if ($totalQuota <= 0) {
            return back()->withInput()->withErrors(['quotas' => 'Total kuota kesanggupan harus lebih dari 0.']);
        }

        DB::transaction(function () use ($validated, $industry, $activeYear) {
            // Update industry profile
            $industry->update([
                'pic_name'     => $validated['pic_name'],
                'pic_position' => $validated['pic_position'],
                'nip'          => $validated['nip'],
                'status'       => 'open',
            ]);

            // Sync allocations
            foreach ($validated['quotas'] as $deptId => $quota) {
                $quota = (int) ($quota ?? 0);
                
                if ($quota > 0) {
                    IndustryAllocation::updateOrCreate(
                        [
                            'industry_id'      => $industry->id,
                            'department_id'    => $deptId,
                            'academic_year_id' => $activeYear->id,
                        ],
                        ['quota' => $quota]
                    );
                } else {
                    IndustryAllocation::where([
                        'industry_id'      => $industry->id,
                        'department_id'    => $deptId,
                        'academic_year_id' => $activeYear->id,
                    ])->delete();
                }
            }
        });

        return redirect()->route('mitra.success');
    }

    /**
     * Show success page.
     */
    public function success()
    {
        return view('mitra.success');
    }
}
