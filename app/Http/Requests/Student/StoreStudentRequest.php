<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'admission_no' => 'nullable|string|unique:students,admission_no',
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'dob' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'document' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ];
    }
}
