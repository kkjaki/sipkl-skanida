<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePartnershipRequest;
use App\Models\Industry;
use App\Models\IndustryPartnership;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class IndustryPartnershipController extends Controller
{
    /**
     * Store a new partnership (MoU) for an industry.
     */
    public function store(StorePartnershipRequest $request, Industry $industry)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $industry) {
            $filePath = null;

            // Store MoU file if provided
            if (! empty($validated['mou_file'])) {
                $file = $validated['mou_file'];
                $fileName = 'mou_'.time().'.'.$file->extension();
                $filePath = $file->storeAs(
                    'mou_files/'.$industry->id,
                    $fileName,
                    'local'
                );
            }

            // Create partnership record
            IndustryPartnership::create([
                'industry_id' => $industry->id,
                'document_number' => $validated['document_number'] ?? null,
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'mou_file_path' => $filePath,
                'agreement_notes' => $validated['agreement_notes'] ?? null,
            ]);

            // Auto-update student submitter's internship dates
            if ($industry->student_submitter_id) {
                $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
                if ($activeYear) {
                    \App\Models\Internship::where('student_id', $industry->student_submitter_id)
                        ->where('industry_id', $industry->id)
                        ->where('academic_year_id', $activeYear->id)
                        ->update([
                            'start_date' => $validated['start_date'],
                            'actual_end_date' => $validated['end_date'],
                        ]);
                }
            }
        });

        Cache::forget('dashboard_stats');

        return redirect()->route('industries.show', $industry)
            ->with('success', 'MoU berhasil diunggah dan disimpan.')
            ->with('activeTab', 'partnerships'); // Auto-switch to partnerships tab
    }

    /**
     * Download MoU file.
     */
    public function download(IndustryPartnership $partnership)
    {
        if (empty($partnership->mou_file_path)) {
            return redirect()->back()->with('error', 'File MoU tidak tersedia untuk dokumen ini.');
        }

        if (! Storage::disk('local')->exists($partnership->mou_file_path)) {
            return redirect()->back()->with('error', 'File MoU tidak ditemukan.');
        }

        // Generate a more user-friendly filename for download
        $industryName = str_replace(' ', '_', $partnership->industry->name);
        $downloadName = 'MoU_'.$industryName.'_'.$partnership->start_date->format('Ymd').'.'.pathinfo($partnership->mou_file_path, PATHINFO_EXTENSION);

        return Storage::disk('local')->download(
            $partnership->mou_file_path,
            $downloadName
        );
    }

    /**
     * Delete a partnership and its file.
     */
    public function destroy(IndustryPartnership $partnership)
    {
        $industryId = $partnership->industry_id;
        $filePath = $partnership->mou_file_path;

        DB::transaction(function () use ($partnership, $filePath) {
            // Delete file from storage if it exists
            if (! empty($filePath) && Storage::disk('local')->exists($filePath)) {
                Storage::disk('local')->delete($filePath);
            }

            // Delete partnership record
            $partnership->delete();
        });

        Cache::forget('dashboard_stats');

        return redirect()->route('industries.show', $industryId)
            ->with('success', 'MoU berhasil dihapus.');
    }
}
