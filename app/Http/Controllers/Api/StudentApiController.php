<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\Homework;
use App\Models\FeePayment;
use Illuminate\Http\Request;

class StudentApiController extends Controller
{
    public function getStudents()
    {
        return response()->json(Student::with(['user', 'class', 'section'])->get());
    }

    public function getAttendance(Request $request)
    {
        $attendance = StudentAttendance::where('student_id', $request->user()->student_profile->id ?? 0)->get();
        return response()->json($attendance);
    }

    public function getHomework()
    {
        return response()->json(Homework::with(['class', 'subject'])->orderBy('due_date', 'desc')->get());
    }

    public function getFees(Request $request)
    {
        $payments = FeePayment::where('student_id', $request->user()->student_profile->id ?? 0)
            ->with(['fee.category'])
            ->get();
        return response()->json($payments);
    }
}
