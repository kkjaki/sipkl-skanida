<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDailyJournalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date'              => ['required', 'date', 'before_or_equal:today'],
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
            'date.before_or_equal'       => 'Tanggal tidak boleh melebihi hari ini.',
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
