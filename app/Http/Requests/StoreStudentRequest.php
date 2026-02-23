<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
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
            'name'          => 'required|string|max:255',
            'email'         => 'nullable|email|max:255|unique:users,email',
            'nis'           => 'required|string|max:20|unique:students,nis',
            'class_name'    => 'required|string|max:50',
            'address'       => 'nullable|string|max:255',
            'phone'         => 'nullable|string|max:20',
            'department_id' => 'required|exists:departments,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required'          => 'Nama lengkap wajib diisi.',
            'email.email'            => 'Format email tidak valid.',
            'email.unique'           => 'Email sudah digunakan.',
            'nis.required'           => 'NIS wajib diisi.',
            'nis.unique'             => 'NIS sudah terdaftar.',
            'class_name.required'    => 'Kelas wajib diisi.',
            'address.string'         => 'Alamat harus berupa teks.',
            'department_id.required' => 'Kompetensi Keahlian wajib dipilih.',
            'department_id.exists'   => 'Kompetensi Keahlian tidak valid.',
        ];
    }
}
