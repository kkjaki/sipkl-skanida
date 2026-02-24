<?php

namespace App\Http\Requests;

use App\Models\Internship;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateDailyJournalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $journal    = $this->route('journal');
        $internship = Internship::where('student_id', Auth::id())
            ->where('id', $journal->internship_id)
            ->firstOrFail();

        return [
            'date'              => [
                'required', 'date', 'before_or_equal:today',
                Rule::unique('daily_journals')
                    ->where('internship_id', $internship->id)
                    ->ignore($journal->id),
            ],
            'status_attendance' => ['required', Rule::in(['present', 'excused', 'sick'])],
            'activity'          => ['required_if:status_attendance,present', 'nullable', 'string', 'max:5000'],
            'attachment_path'   => [
                $this->attachmentRule($journal),
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
            'date.before_or_equal'       => 'Tanggal tidak boleh melebihi hari ini.',
            'date.unique'                => 'Anda sudah mengisi jurnal untuk tanggal ini.',
            'status_attendance.required' => 'Status kehadiran wajib dipilih.',
            'activity.required_if'       => 'Kegiatan wajib diisi jika status hadir.',
            'attachment_path.required'   => 'Bukti surat keterangan wajib diunggah untuk status sakit/izin.',
            'attachment_path.mimes'      => 'File harus berformat JPG, JPEG, PNG, atau PDF.',
            'attachment_path.max'        => 'Ukuran file maksimal 2MB.',
        ];
    }

    /**
     * Determine the attachment validation rule based on attendance status.
     * For sick/excused: required unless editing and file already exists.
     */
    private function attachmentRule($journal): string
    {
        if (in_array($this->input('status_attendance'), ['sick', 'excused'])) {
            return $journal->attachment_path ? 'nullable' : 'required';
        }

        return 'nullable';
    }
}
