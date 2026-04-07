<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProposalRequest;
use App\Http\Requests\UpdateProposalRequest;
use App\Models\Industry;
use App\Models\Internship;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class IndustryProposalController extends Controller
{
    /**
     * Show student's own proposals (Student).
     */
    public function index()
    {
        $userId = Auth::id();

        $proposals = Industry::where('student_submitter_id', $userId)
            ->latest()
            ->get();

        // Block proposal if student already has a plotting (internship)
        $hasPlotting = Internship::where('student_id', $userId)->exists();

        // Determine if student can propose again
        $canPropose = ! $hasPlotting && ! $proposals->contains(function ($p) {
            // Block if pending (is_synced=false) or verified+open
            return ! $p->is_synced || ($p->is_synced && $p->status === 'open');
        });

        return view('industries.my-proposals', compact('proposals', 'canPropose', 'hasPlotting'));
    }

    /**
     * Show the proposal form (Student).
     * Redirects to my-proposals if student already has an active (pending/verified) proposal.
     */
    public function create()
    {
        $userId = Auth::id();

        // Check: student already has a plotting (internship)
        $hasPlotting = Internship::where('student_id', $userId)->exists();

        if ($hasPlotting) {
            return redirect()->route('student.proposals.index')
                ->with('info', 'Anda sudah mendapatkan plotting PKL. Anda tidak dapat mengajukan lokasi baru.');
        }

        // Check: student has a pending proposal (is_synced = false, NOT blacklisted)
        $hasPending = Industry::where('student_submitter_id', $userId)
            ->where('is_synced', false)
            ->where('status', '!=', 'blacklisted')
            ->exists();

        if ($hasPending) {
            return redirect()->route('student.proposals.index')
                ->with('info', 'Anda masih memiliki pengajuan yang sedang diproses. Tunggu hingga Kaprog memberikan keputusan.');
        }

        // Check: student has a verified (is_synced = true, status = open) proposal
        $hasVerified = Industry::where('student_submitter_id', $userId)
            ->where('is_synced', true)
            ->where('status', 'open')
            ->exists();

        if ($hasVerified) {
            return redirect()->route('student.proposals.index')
                ->with('info', 'Pengajuan Anda sudah diverifikasi dan disetujui. Anda tidak bisa mengajukan lokasi lain.');
        }

        // Student can propose (no active proposal, or previous was rejected/blacklisted)
        return view('industries.propose');
    }

    /**
     * Store a new industry proposal from a student.
     */
    public function store(StoreProposalRequest $request)
    {
        $validated = $request->validated();
        $userId = Auth::id();

        // Double-check: block if student already has a plotting (internship)
        $hasPlotting = Internship::where('student_id', $userId)->exists();

        if ($hasPlotting) {
            return redirect()->route('student.proposals.index')
                ->with('info', 'Anda sudah mendapatkan plotting PKL. Anda tidak dapat mengajukan lokasi baru.');
        }

        // Double-check: block if pending or verified exists
        $hasActive = Industry::where('student_submitter_id', $userId)
            ->where(function ($q) {
                $q->where(function ($q1) {
                    $q1->where('is_synced', false)
                        ->where('status', '!=', 'blacklisted'); // pending but not rejected
                })
                    ->orWhere(function ($q2) {
                        $q2->where('is_synced', true)->where('status', 'open'); // verified
                    });
            })
            ->exists();

        if ($hasActive) {
            return redirect()->route('student.proposals.index')
                ->with('info', 'Anda sudah memiliki pengajuan aktif.');
        }

        // Duplicate check: case-insensitive name match
        $duplicate = Industry::whereRaw('LOWER(name) = ?', [strtolower($validated['name'])])->exists();

        if ($duplicate) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['name' => 'Nama industri ini sudah terdaftar di sistem. Silakan periksa kembali atau hubungi admin.']);
        }

        DB::transaction(function () use ($validated, $userId) {
            Industry::create([
                'name' => $validated['name'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'contact_person' => $validated['contact_person'] ?? null,
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'student_submitter_id' => $userId,
                'status' => 'open',
                'is_synced' => false,
            ]);
        });

        Cache::forget('dashboard_stats');

        return redirect()->route('student.proposals.index')
            ->with('success', 'Pengajuan lokasi PKL berhasil dikirim. Admin akan segera memverifikasi data Anda.');
    }

    /**
     * Show the form for editing the specified proposal.
     */
    public function edit(Industry $proposal)
    {
        $userId = Auth::id();

        if ($proposal->student_submitter_id !== $userId) {
            abort(403, 'Unauthorized action.');
        }

        if ($proposal->is_synced) {
            return redirect()->route('student.proposals.index')
                ->with('info', 'Proposal sudah diverifikasi dan tidak dapat diubah.');
        }

        return view('industries.edit-propose', compact('proposal'));
    }

    /**
     * Update the specified proposal in storage.
     */
    public function update(UpdateProposalRequest $request, Industry $proposal)
    {
        $userId = Auth::id();

        if ($proposal->student_submitter_id !== $userId) {
            abort(403, 'Unauthorized action.');
        }

        if ($proposal->is_synced) {
            return redirect()->route('student.proposals.index')
                ->with('info', 'Proposal sudah diverifikasi dan tidak dapat diubah.');
        }

        $validated = $request->validated();

        $proposal->update([
            'name' => $validated['name'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'contact_person' => $validated['contact_person'] ?? null,
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
        ]);

        return redirect()->route('student.proposals.index')
            ->with('success', 'Pengajuan lokasi PKL berhasil diperbarui.');
    }

    /**
     * Remove the specified proposal from storage.
     */
    public function destroy(Industry $proposal)
    {
        $userId = Auth::id();

        if ($proposal->student_submitter_id !== $userId) {
            abort(403, 'Unauthorized action.');
        }

        if ($proposal->is_synced) {
            return redirect()->route('student.proposals.index')
                ->with('info', 'Proposal sudah diverifikasi dan tidak dapat dihapus.');
        }

        $proposal->delete();

        Cache::forget('dashboard_stats');

        return redirect()->route('student.proposals.index')
            ->with('success', 'Pengajuan lokasi PKL berhasil dibatalkan dan dihapus.');
    }
}
