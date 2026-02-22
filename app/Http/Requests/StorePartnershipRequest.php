<?php

namespace App\Http\Requests;

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
            redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('openModal', true) // Flag to auto-open modal
                ->with('activeTab', 'partnerships') // Auto-switch to partnerships tab
        );
    }
}
