<?php

namespace App\Http\Requests;

use App\Models\IndustryPartnership;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePartnershipRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Add custom validation: prevent overlapping MoU dates for the same industry.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->hasAny(['start_date', 'end_date'])) {
                return; // Skip if basic date validation already failed
            }

            $industry = $this->route('industry');
            $startDate = $this->input('start_date');
            $endDate = $this->input('end_date');

            if (! $industry || ! $startDate || ! $endDate) {
                return;
            }

            $overlap = IndustryPartnership::where('industry_id', $industry->id)
                ->where('start_date', '<=', $endDate)
                ->where('end_date', '>=', $startDate)
                ->first();

            if ($overlap) {
                $validator->errors()->add(
                    'start_date',
                    'Periode MoU bertumpuk dengan MoU aktif ('
                    .$overlap->start_date->format('d M Y').' – '
                    .$overlap->end_date->format('d M Y')
                    .'). Hapus MoU lama terlebih dahulu jika ingin menggantinya.'
                );
            }
        });
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'document_number' => ['nullable', 'string', 'max:100',
                Rule::unique('industry_partnerships', 'document_number'),
            ],
            'start_date' => ['required', 'date', 'after_or_equal:2000-01-01', 'before_or_equal:2099-12-31'],
            'end_date' => ['required', 'date', 'after:start_date', 'before_or_equal:2099-12-31'],
            'mou_file' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
            'agreement_notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'document_number.unique' => 'Nomor dokumen MoU sudah terdaftar.',
            'start_date.required' => 'Tanggal mulai kerjasama wajib diisi.',
            'start_date.after_or_equal' => 'Tanggal mulai harus antara tahun 2000-2099.',
            'start_date.before_or_equal' => 'Tanggal mulai harus antara tahun 2000-2099.',
            'end_date.required' => 'Tanggal selesai kerjasama wajib diisi.',
            'end_date.after' => 'Tanggal selesai harus setelah tanggal mulai.',
            'end_date.before_or_equal' => 'Tanggal selesai harus antara tahun 2000-2099.',
            'mou_file.mimes' => 'File MoU harus berformat PDF, DOC, atau DOCX.',
            'mou_file.max' => 'Ukuran file MoU maksimal 5MB. Tip: Scan dokumen dengan resolusi 150-200 DPI untuk ukuran optimal.',
            'agreement_notes.max' => 'Catatan maksimal 1000 karakter.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     * Redirect back with modal flag to auto-reopen modal.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Validation\ValidationException(
            $validator,
            redirect()->route('partnerships.manage', $this->route('industry'))
                ->withErrors($validator)
                ->withInput()
                ->with('openModal', true) // Flag to auto-open modal
        );
    }
}
