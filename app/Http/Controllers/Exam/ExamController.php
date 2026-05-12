<?php

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Services\ExamService;
use Illuminate\Http\Request;

use App\Services\AcademicService;

class ExamController extends Controller
{
    protected $examService;
    protected $academicService;

    public function __construct(ExamService $examService, AcademicService $academicService)
    {
        $this->examService = $examService;
        $this->academicService = $academicService;
    }

    public function index()
    {
        $user = auth()->user();
        $exams = $this->examService->getAllExams();
        $classes = $this->academicService->getAllClasses();

        if ($user->hasRole('Student') && $user->student) {
            $exams = $exams->where('class_id', $user->student->class_id);
            $classes = collect();
        } elseif ($user->hasAnyRole(['Teacher', 'Staff']) && $user->teacher) {
            $allowedClasses = $user->teacher->subjects()->pluck('class_id')->unique()->toArray();
            $exams = $exams->whereIn('class_id', $allowedClasses);
            $classes = $classes->whereIn('id', $allowedClasses);
        }

        return view('exams.index', compact('exams', 'classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'class_id' => 'required|exists:classes,id',
            'academic_session_id' => 'nullable',
        ]);

        $this->examService->createExam($request->all());

        return redirect()->back()->with('success', 'Exam created successfully');
    }

    public function marksEntry(Request $request)
    {
        $user = auth()->user();
        $exams = $this->examService->getAllExams();
        $subjects = $this->academicService->getAllSubjects();
        $classes = $this->academicService->getAllClasses();
        $students = [];

        if ($user->hasRole('Student')) {
            abort(403, 'Unauthorized access');
        } elseif ($user->hasAnyRole(['Teacher', 'Staff']) && $user->teacher) {
            $allowedClasses = $user->teacher->subjects()->pluck('class_id')->unique()->toArray();
            $allowedSubjects = $user->teacher->subjects()->pluck('subjects.id')->toArray();
            
            $exams = $exams->whereIn('class_id', $allowedClasses);
            $classes = $classes->whereIn('id', $allowedClasses);
            $subjects = $subjects->whereIn('id', $allowedSubjects);
        }

        if ($request->filled(['exam_id', 'class_id', 'section_id', 'subject_id'])) {
            if ($user->hasAnyRole(['Teacher', 'Staff']) && $user->teacher) {
                $allowedClasses = $user->teacher->subjects()->pluck('class_id')->unique()->toArray();
                $allowedSubjects = $user->teacher->subjects()->pluck('subjects.id')->toArray();
                
                if (!in_array($request->class_id, $allowedClasses) || !in_array($request->subject_id, $allowedSubjects)) {
                    abort(403, 'Unauthorized to view marks entry for this class or subject.');
                }
            }

            $students = $this->examService->getStudentsForMarks(
                $request->exam_id, 
                $request->class_id, 
                $request->section_id
            );
        }

        return view('exams.marks_entry', compact('exams', 'subjects', 'classes', 'students'));
    }

    public function storeMarks(Request $request)
    {
        $request->validate([
            'exam_id' => 'required',
            'subject_id' => 'required',
            'marks' => 'required|array',
        ]);

        $user = auth()->user();
        if ($user->hasAnyRole(['Teacher', 'Staff']) && $user->teacher) {
            $allowedSubjects = $user->teacher->subjects()->pluck('subjects.id')->toArray();
            $allowedClasses = $user->teacher->subjects()->pluck('class_id')->unique()->toArray();
            
            if (!in_array($request->subject_id, $allowedSubjects)) {
                abort(403, 'Unauthorized to store marks for this subject.');
            }

            $studentIds = array_keys($request->marks);
            $invalidStudents = \App\Models\Student::whereIn('id', $studentIds)
                ->whereNotIn('class_id', $allowedClasses)
                ->exists();
                
            if ($invalidStudents) {
                abort(403, 'Unauthorized to store marks for students outside your assigned classes.');
            }
        }

        $this->examService->storeMarks($request->all());

        return redirect()->back()->with('success', 'Marks saved successfully');
    }
}
