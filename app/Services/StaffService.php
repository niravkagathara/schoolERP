<?php

namespace App\Services;

use App\Models\User;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StaffService
{
    public function getAllStaff()
    {
        return Teacher::with(['user'])->get();
    }

    public function createStaff(array $data)
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $user->assignRole($data['role'] ?? 'Teacher');

            $teacher = Teacher::create([
                'user_id' => $user->id,
                'phone' => $data['phone'] ?? null,
                'address' => $data['address'] ?? null,
                'designation' => $data['designation'] ?? 'Teacher',
                'joining_date' => $data['joining_date'] ?? date('Y-m-d'),
                'qualification' => $data['qualification'] ?? null,
            ]);

            return $teacher;
        });
    }

    public function updateStaff(Teacher $teacher, array $data)
    {
        return DB::transaction(function () use ($teacher, $data) {
            $user = $teacher->user;
            $user->update([
                'name' => $data['name'],
                'email' => $data['email'],
            ]);

            if (!empty($data['password'])) {
                $user->update(['password' => Hash::make($data['password'])]);
            }

            if (!empty($data['role'])) {
                $user->syncRoles([$data['role']]);
            }

            $teacher->update([
                'phone' => $data['phone'] ?? $teacher->phone,
                'address' => $data['address'] ?? $teacher->address,
                'designation' => $data['designation'] ?? $teacher->designation,
                'joining_date' => $data['joining_date'] ?? $teacher->joining_date,
                'qualification' => $data['qualification'] ?? $teacher->qualification,
            ]);

            return $teacher;
        });
    }

    public function deleteStaff(Teacher $teacher)
    {
        if ($teacher->subjects()->count() > 0) {
            throw new \Exception('Cannot delete staff member. They are still assigned to subjects.');
        }

        return DB::transaction(function () use ($teacher) {
            $user = $teacher->user;
            $teacher->delete();
            $user->delete();
            return true;
        });
    }
}
