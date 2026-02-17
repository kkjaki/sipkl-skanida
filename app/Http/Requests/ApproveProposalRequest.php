<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApproveProposalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'quotas'   => ['required', 'array'],
            'quotas.*' => ['nullable', 'integer', 'min:0'],
        ];
    }

    /**
     * Custom validation: total quota must be > 0.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $quotas = $this->input('quotas', []);
            $totalQuota = array_sum(array_map('intval', $quotas));

            if ($totalQuota <= 0) {
                $validator->errors()->add(
                    'quotas',
                    'Anda tidak dapat memverifikasi industri tanpa mengisi kuota. Pastikan industri menerima minimal 1 siswa.'
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'quotas.required'  => 'Data alokasi kuota wajib diisi.',
            'quotas.*.integer' => 'Kuota harus berupa angka.',
            'quotas.*.min'     => 'Kuota minimal 0.',
        ];
    }
}
