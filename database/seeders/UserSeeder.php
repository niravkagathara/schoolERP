<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'Super Admin' => 'admin@school.com',
            'Admin'       => 'manager@school.com',
            'Teacher'     => 'teacher@school.com',
            'Student'     => 'student@school.com',
            'Parent'      => 'parent@school.com',
            'Accountant'  => 'fees@school.com',
            'Staff'       => 'staff@school.com',
        ];

        foreach ($roles as $role => $email) {
            $user = User::firstOrCreate([
                'email' => $email
            ], [
                'name' => $role . ' User',
                'password' => Hash::make('password')
            ]);

            $user->syncRoles([$role]);
        }
    }
}
