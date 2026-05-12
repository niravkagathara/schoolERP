<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Services\StaffService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class StaffController extends Controller
{
    protected $staffService;

    public function __construct(StaffService $staffService)
    {
        $this->staffService = $staffService;
    }

    public function index()
    {
        $staffMembers = $this->staffService->getAllStaff();
        $roles = Role::whereNotIn('name', ['Super Admin', 'Student', 'Parent'])->get();
        return view('staff.index', compact('staffMembers', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required|exists:roles,name',
        ]);

        $this->staffService->createStaff($request->all());

        return redirect()->back()->with('success', 'Staff member added successfully');
    }

    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $teacher->user->id,
            'password' => 'nullable|min:8',
            'role' => 'required|exists:roles,name',
        ]);

        $this->staffService->updateStaff($teacher, $request->all());

        return redirect()->back()->with('success', 'Staff member updated successfully');
    }

    public function destroy(Teacher $teacher)
    {
        try {
            $this->staffService->deleteStaff($teacher);
            return redirect()->back()->with('success', 'Staff record removed');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
