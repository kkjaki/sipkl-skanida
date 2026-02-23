<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
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
        $studentId = $this->route('student');
        $student = \App\Models\Student::findOrFail($studentId);

        return [
            'name'          => 'required|string|max:255',
            'email'         => 'nullable|email|max:255|unique:users,email,' . $student->user_id,
            'nis'           => 'required|string|max:20|unique:students,user_id,' . $student->user_id . ',user_id',
            'class_name'    => 'required|string|max:50',
            'address'       => 'nullable|string|max:255',
            'phone'         => 'nullable|string|max:20',
            'department_id' => 'required|exists:departments,id',
            'reset_email'   => 'nullable|boolean',
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
