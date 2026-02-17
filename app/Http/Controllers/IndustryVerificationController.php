<?php

namespace App\Http\Controllers;

use App\Models\Industry;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class IndustryVerificationController extends Controller
{
    /**
     * Get the department_id of the authenticated Kaprog.
     */
    private function kaprogDepartmentId(): int
    {
        return Auth::user()->supervisor->department_id;
    }

    /**
     * Scope: only proposals from students in this Kaprog's department.
     */
    private function proposalsForMyDepartment()
    {
        $deptId = $this->kaprogDepartmentId();

        return Industry::whereNotNull('student_submitter_id')
            ->whereHas('studentSubmitter', function ($q) use ($deptId) {
                $q->whereHas('student', fn($s) => $s->where('department_id', $deptId));
            });
    }

    /**
     * List all student proposals for this Kaprog's department.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $proposals = $this->proposalsForMyDepartment()
            ->with('studentSubmitter')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('city', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('verification.index', compact('proposals', 'search'));
    }

    /**
     * Show detail of a student proposal (read-only) + approve/reject buttons.
     */
    public function show(string $id)
    {
        $deptId = $this->kaprogDepartmentId();

        $industry = $this->proposalsForMyDepartment()
            ->with('studentSubmitter')
            ->findOrFail($id);

        return view('verification.show', compact('industry'));
    }

    /**
     * Approve (sync) a student proposal.
     * Sets is_synced = true. Quota allocation is Admin's responsibility.
     */
    public function approve(string $id)
    {
        $industry = $this->proposalsForMyDepartment()->findOrFail($id);

        if ($industry->is_synced) {
            return redirect()->route('verification.index')
                ->with('info', 'Industri ini sudah disinkronisasi.');
        }

        $industry->update([
            'is_synced' => true,
            'status'    => 'open',
        ]);

        Cache::forget('dashboard_stats');

        return redirect()->route('verification.index')
            ->with('success', 'Sinkronisasi kurikulum berhasil. Industri telah diverifikasi dan menunggu input kuota oleh Humas/Admin.');
    }

    /**
     * Reject a student proposal.
     * Sets status = blacklisted. Does NOT set is_synced = true.
     */
    public function reject(string $id)
    {
        $industry = $this->proposalsForMyDepartment()->findOrFail($id);

        if ($industry->is_synced) {
            return redirect()->route('verification.index')
                ->with('info', 'Industri ini sudah disinkronisasi dan tidak dapat ditolak dari sini.');
        }

        $industry->update([
            'status' => 'blacklisted',
        ]);

        Cache::forget('dashboard_stats');

        return redirect()->route('verification.index')
            ->with('success', 'Pengajuan industri telah ditolak karena tidak sesuai kurikulum.');
    }

    /**
     * Unreject (give second chance) a previously rejected proposal.
     * Resets status from blacklisted back to open so it can be reviewed again.
     */
    public function unreject(string $id)
    {
        $industry = $this->proposalsForMyDepartment()->findOrFail($id);

        if ($industry->status !== 'blacklisted') {
            return redirect()->route('verification.index')
                ->with('info', 'Industri ini tidak dalam status ditolak.');
        }

        $industry->update([
            'status' => 'open',
        ]);

        Cache::forget('dashboard_stats');

        return redirect()->route('verification.show', $id)
            ->with('success', 'Status penolakan dicabut. Anda dapat meninjau ulang pengajuan ini.');
    }

    /**
     * Unsync (revoke approval) a previously synced proposal.
     * Only allowed if no students are linked via internships.
     */
    public function unsync(string $id)
    {
        $industry = $this->proposalsForMyDepartment()->findOrFail($id);

        if (!$industry->is_synced) {
            return redirect()->route('verification.index')
                ->with('info', 'Industri ini belum disinkronisasi.');
        }

        // Check if any students are linked via internships
        $hasInternships = $industry->internships()->exists();

        if ($hasInternships) {
            return redirect()->route('verification.show', $id)
                ->with('error', 'Tidak dapat membatalkan sinkronisasi. Terdapat siswa yang sudah tertaut ke industri ini. Hapus keterkaitan siswa terlebih dahulu.');
        }

        $industry->update([
            'is_synced' => false,
        ]);

        Cache::forget('dashboard_stats');

        return redirect()->route('verification.show', $id)
            ->with('success', 'Sinkronisasi dicabut. Industri kembali ke status menunggu verifikasi.');
    }
}
