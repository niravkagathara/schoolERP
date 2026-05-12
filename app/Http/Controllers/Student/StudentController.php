<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreStudentRequest;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Services\StudentService;
use Illuminate\Http\Request;

use App\Services\AcademicService;

class StudentController extends Controller
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
        
        if ($user->hasRole('Student')) {
            abort(403, 'Unauthorized access to student directory');
        }

        $query = \App\Models\Student::with(['user', 'class', 'section']);

        if ($user->hasAnyRole(['Teacher', 'Staff']) && $user->teacher) {
            $allowedClasses = $user->teacher->subjects()->pluck('class_id')->unique()->toArray();
            $query->whereIn('class_id', $allowedClasses);
        }

        $students = $query->get();

        return view('students.index', compact('students'));
    }

    public function create()
    {
        $classes = $this->academicService->getAllClasses();
        return view('students.create', compact('classes'));
    }

    public function store(StoreStudentRequest $request)
    {
        $this->studentService->createStudent($request->validated());
        return redirect()->route('students.index')->with('success', 'Student admitted successfully');
    }

    public function show(Student $student)
    {
        $user = auth()->user();
        if ($user->hasRole('Student') && $user->student && $user->student->id !== $student->id) {
            abort(403, 'Unauthorized access to other student profile');
        }

        if ($user->hasAnyRole(['Teacher', 'Staff']) && $user->teacher) {
            $allowedClasses = $user->teacher->subjects()->pluck('class_id')->unique()->toArray();
            if (!in_array($student->class_id, $allowedClasses)) {
                abort(403, 'Unauthorized access to this student profile');
            }
        }

        $student->load(['user', 'class', 'section', 'parent', 'session', 'feePayments.fee.category']);
        return view('students.show', compact('student'));
    }

    public function destroy(Student $student)
    {
        $this->studentService->deleteStudent($student);
        return redirect()->back()->with('success', 'Student record deleted');
    }
}
