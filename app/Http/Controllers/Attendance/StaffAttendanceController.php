<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StaffAttendance;
use Illuminate\Http\Request;

class StaffAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $staffMembers = User::role(['Teacher', 'Staff', 'Accountant', 'Admin'])->get();
        $date = $request->get('date', date('Y-m-d'));
        
        $attendance = StaffAttendance::where('date', $date)->get()->keyBy('user_id');

        return view('attendance.staff.index', compact('staffMembers', 'date', 'attendance'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'attendance' => 'required|array',
        ]);

        foreach ($request->attendance as $userId => $status) {
            StaffAttendance::updateOrCreate(
                ['user_id' => $userId, 'date' => $request->date],
                ['status' => $status]
            );
        }

        return redirect()->back()->with('success', 'Staff attendance updated successfully');
    }
}
