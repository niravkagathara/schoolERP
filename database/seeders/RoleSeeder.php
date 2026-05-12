<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            'manage-users',
            'manage-academics',
            'manage-students',
            'mark-attendance',
            'view-attendance',
            'manage-fees',
            'view-fees',
            'manage-exams',
            'view-results',
            'manage-homework',
            'view-homework',
            'manage-staff',
            'view-reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Roles and Assign Permissions
        
        // Super Admin
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdmin->syncPermissions(Permission::all());

        // Admin
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $admin->syncPermissions([
            'manage-academics',
            'manage-students',
            'mark-attendance',
            'view-attendance',
            'manage-fees',
            'view-fees',
            'manage-exams',
            'view-results',
            'manage-homework',
            'view-homework',
            'manage-staff',
            'view-reports',
        ]);

        // Teacher
        $teacher = Role::firstOrCreate(['name' => 'Teacher']);
        $teacher->syncPermissions([
            'mark-attendance',
            'view-attendance',
            'manage-exams',
            'view-results',
            'manage-homework',
            'view-homework',
        ]);

        // Student
        $student = Role::firstOrCreate(['name' => 'Student']);
        $student->syncPermissions([
            'view-attendance',
            'view-fees',
            'view-results',
            'view-homework',
        ]);

        // Parent
        $parent = Role::firstOrCreate(['name' => 'Parent']);
        $parent->syncPermissions([
            'view-attendance',
            'view-fees',
            'view-results',
            'view-homework',
        ]);

        // Accountant
        $accountant = Role::firstOrCreate(['name' => 'Accountant']);
        $accountant->syncPermissions([
            'manage-fees',
            'view-fees',
            'view-reports',
        ]);

        // Staff
        $staff = Role::firstOrCreate(['name' => 'Staff']);
        $staff->syncPermissions([
            'view-attendance',
            'view-homework',
        ]);
    }
}
