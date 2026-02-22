<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIndustryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'           => ['required', 'string', 'max:255', Rule::unique('industries', 'name')->whereNull('deleted_at')],
            'address'        => ['required', 'string'],
            'city'           => ['required', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'email'          => ['nullable', 'string', 'email', 'max:255', Rule::unique('industries', 'email')->whereNull('deleted_at'), 'required_without:phone'],
            'phone'          => ['nullable', 'string', 'max:30', 'required_without:email'],
            'pic_name'       => ['nullable', 'string', 'max:255'],
            'pic_position'   => ['nullable', 'string', 'max:255'],
            'nip'            => ['nullable', 'string', 'max:255'],
            'quotas'         => ['nullable', 'array'],
            'quotas.*'       => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'          => 'Nama industri wajib diisi.',
            'name.unique'            => 'Nama industri sudah terdaftar.',
            'address.required'       => 'Alamat wajib diisi.',
            'city.required'          => 'Kota wajib diisi.',
            'email.email'            => 'Format email tidak valid.',
            'email.unique'           => 'Email sudah terdaftar.',
            'email.required_without' => 'Email wajib diisi jika No. Telepon tidak diisi.',
            'phone.max'              => 'No. Telepon maksimal 30 karakter.',
            'phone.required_without' => 'No. Telepon wajib diisi jika Email tidak diisi.',
            'quotas.*.integer'       => 'Kuota harus berupa angka.',
            'quotas.*.min'           => 'Kuota minimal 0.',
        ];
    }
}
