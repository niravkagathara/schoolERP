<?php

namespace App\Services;

use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentService
{
    public function getAllStudents()
    {
        $query = Student::with(['user', 'class', 'section']);
        $user = auth()->user();

        if ($user->hasRole('Student') && $user->student) {
            $query->where('id', $user->student->id);
        } elseif ($user->hasAnyRole(['Teacher', 'Staff']) && $user->teacher) {
            // Get classes for subjects taught by teacher
            $classIds = $user->teacher->subjects()->pluck('class_id')->unique()->toArray();
            $query->whereIn('class_id', $classIds);
        }

        return $query->get();
    }

    public function createStudent(array $data)
    {
        return DB::transaction(function () use ($data) {
            // 1. Create User
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            // 2. Assign Role
            $user->assignRole('Student');

            // 3. Handle Document Upload
            $documentPath = null;
            if (isset($data['document'])) {
                $documentPath = $data['document']->store('students/documents', 'public');
            }

            // 4. Create Student Profile
            $admissionNo = $data['admission_no'] ?? 'STU-' . date('Y') . '-' . mt_rand(1000, 9999);
            
            $student = Student::create([
                'user_id' => $user->id,
                'admission_no' => $admissionNo,
                'class_id' => $data['class_id'],
                'section_id' => $data['section_id'],
                'phone' => $data['phone'] ?? null,
                'address' => $data['address'] ?? null,
                'dob' => $data['dob'],
                'gender' => $data['gender'],
                'document' => $documentPath,
            ]);

            return $student;
        });
    }

    public function deleteStudent(Student $student)
    {
        return DB::transaction(function () use ($student) {
            $user = $student->user;
            $student->delete();
            $user->delete();
            return true;
        });
    }
}
