<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        $roles = Role::all();
        return view('super_admin.users.index', compact('users', 'roles'));
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name'
        ]);

        $user->syncRoles([$request->role]);

        return redirect()->back()->with('success', 'User role updated successfully');
    }

    public function permissions()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        return view('super_admin.permissions.index', compact('roles', 'permissions'));
    }

    public function updatePermissions(Request $request, Role $role)
    {
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->back()->with('success', 'Role permissions updated successfully');
    }
}
