<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Supervisor;

class StoreSupervisorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'nip'           => ['required', 'string', 'max:30', 'unique:supervisors,nip'],
            'department_id' => ['required', 'exists:departments,id'],
            'is_department_head' => [
                'nullable', 
                'boolean',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $exists = Supervisor::where('department_id', $this->department_id)
                            ->whereHas('user.roles', function ($q) {
                                $q->where('name', 'department_head');
                            })->exists();
                            
                        if ($exists) {
                            $fail('Program keahlian ini sudah memiliki Kepala Program.');
                        }
                    }
                }
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'          => 'Nama lengkap wajib diisi.',
            'email.email'            => 'Format email tidak valid.',
            'email.unique'           => 'Email sudah digunakan.',
            'nip.required'           => 'NIP wajib diisi.',
            'nip.unique'             => 'NIP sudah terdaftar.',
            'department_id.required' => 'Program Keahlian wajib dipilih.',
            'department_id.exists'   => 'Program Keahlian tidak valid.',
        ];
    }
}
