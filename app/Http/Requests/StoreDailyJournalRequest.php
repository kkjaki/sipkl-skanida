<?php

namespace App\Http\Requests;

use App\Models\Internship;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreDailyJournalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $internship = Internship::where('student_id', Auth::id())
            ->where('status', 'ongoing')
            ->first();

        $startDate = $internship?->start_date?->format('Y-m-d') ?? now()->toDateString();
        $maxDate   = $internship
            ? min(now()->toDateString(), $internship->actual_end_date->format('Y-m-d'))
            : now()->toDateString();

        return [
            'date'              => ['required', 'date', "after_or_equal:{$startDate}", "before_or_equal:{$maxDate}"],
            'status_attendance' => ['required', Rule::in(['present', 'excused', 'sick'])],
            'activity'          => ['required_if:status_attendance,present', 'nullable', 'string', 'max:5000'],
            'attachment_path'   => [
                $this->attachmentRule(),
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:2048',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'date.required'              => 'Tanggal wajib diisi.',
            'date.after_or_equal'        => 'Tanggal tidak boleh sebelum tanggal mulai PKL Anda.',
            'date.before_or_equal'       => 'Tanggal tidak boleh melebihi hari ini atau tanggal akhir PKL.',
            'status_attendance.required' => 'Status kehadiran wajib dipilih.',
            'activity.required_if'       => 'Kegiatan wajib diisi jika status hadir.',
            'attachment_path.required'   => 'Bukti surat keterangan wajib diunggah untuk status sakit/izin.',
            'attachment_path.mimes'      => 'File harus berformat JPG, JPEG, PNG, atau PDF.',
            'attachment_path.max'        => 'Ukuran file maksimal 2MB.',
        ];
    }

    /**
     * Determine the attachment validation rule based on attendance status.
     * For sick/excused: attachment is required. Otherwise nullable.
     */
    private function attachmentRule(): string
    {
        return in_array($this->input('status_attendance'), ['sick', 'excused'])
            ? 'required'
            : 'nullable';
    }
}
