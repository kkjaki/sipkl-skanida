<?php

namespace App\Http\Requests;

use App\Models\Industry;
use Closure;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProposalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                function (string $attribute, mixed $value, Closure $fail) {
                    $proposal = $this->route('proposal');
                    $proposalId = $proposal ? $proposal->id : null;

                    $duplicate = Industry::whereRaw('LOWER(name) = ?', [strtolower($value)])
                        ->where('id', '!=', $proposalId)
                        ->exists();

                    if ($duplicate) {
                        $fail('Nama industri ini sudah terdaftar di sistem. Silakan periksa kembali atau hubungi admin.');
                    }
                },
            ],
            'address'                  => ['required', 'string'],
            'city'                     => ['required', 'string', 'max:255'],
            'contact_person'           => ['nullable', 'string', 'max:255'],
            'email'                    => ['nullable', 'string', 'email', 'max:255', 'required_without:phone'],
            'phone'                    => ['nullable', 'string', 'max:20', 'required_without:email'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'                     => 'Nama perusahaan wajib diisi.',
            'address.required'                  => 'Alamat wajib diisi.',
            'city.required'                     => 'Kota wajib diisi.',
            'email.email'                       => 'Format email tidak valid.',
            'email.required_without'            => 'Email wajib diisi jika No. Telepon / WA tidak diisi.',
            'phone.max'                         => 'No. Telepon / WA maksimal 20 karakter.',
            'phone.required_without'            => 'No. Telepon / WA wajib diisi jika Email tidak diisi.',
        ];
    }
}
