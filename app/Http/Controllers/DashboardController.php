<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\FeePayment;
use Illuminate\Http\Request;

use App\Services\StudentService;
use App\Services\AcademicService;
use App\Services\FeeService;

class DashboardController extends Controller
{
    protected $studentService;
    protected $academicService;

    public function __construct(StudentService $studentService, AcademicService $academicService)
    {
        $this->studentService = $studentService;
        $this->academicService = $academicService;
    }

    public function index()
    {
        $user = auth()->user();
        
        $totalStudents = $this->studentService->getAllStudents()->count();
        $totalStaff = Teacher::count();
        
        $totalFees = 0;
        if ($user->hasRole('Student') && $user->student) {
            $totalFees = FeePayment::where('student_id', $user->student->id)->sum('amount_paid');
        } elseif ($user->hasRole('Super Admin') || $user->hasRole('Admin') || $user->hasRole('Accountant')) {
            $totalFees = FeePayment::sum('amount_paid');
        }

        $recentAdmissions = collect();
        if ($user->hasRole('Super Admin') || $user->hasRole('Admin')) {
            $recentAdmissions = Student::with('user', 'class')->latest()->take(5)->get();
        } elseif ($user->hasAnyRole(['Teacher', 'Staff']) && $user->teacher) {
            $classIds = $user->teacher->subjects()->pluck('class_id')->unique()->toArray();
            $recentAdmissions = Student::with('user', 'class')->whereIn('class_id', $classIds)->latest()->take(5)->get();
        }

        $data = [
            'total_students' => $totalStudents,
            'total_staff' => $totalStaff,
            'total_fees' => $totalFees,
            'current_session' => \App\Models\AcademicSession::where('is_current', true)->first(),
            'recent_admissions' => $recentAdmissions,
        ];

        return view('dashboard', $data);
    }
}
