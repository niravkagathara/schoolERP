<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Homework;
use App\Services\HomeworkService;
use Illuminate\Http\Request;

use App\Services\AcademicService;

class HomeworkController extends Controller
{
    protected $homeworkService;
    protected $academicService;

    public function __construct(HomeworkService $homeworkService, AcademicService $academicService)
    {
        $this->homeworkService = $homeworkService;
        $this->academicService = $academicService;
    }

    public function index()
    {
        $user = auth()->user();
        
        $homeworks = $this->homeworkService->getAllHomework();
        $classes = $this->academicService->getAllClasses();
        $subjects = $this->academicService->getAllSubjects();

        if ($user->hasRole('Student') && $user->student) {
            // Student only sees their class homework
            $homeworks = $homeworks->where('class_id', $user->student->class_id)
                                   ->where('section_id', $user->student->section_id);
            $classes = collect();
            $subjects = collect();
        } elseif ($user->hasAnyRole(['Teacher', 'Staff']) && $user->teacher) {
            // Teacher only sees homework, classes, and subjects they are assigned to
            $allowedClasses = $user->teacher->subjects()->pluck('class_id')->unique()->toArray();
            $allowedSubjects = $user->teacher->subjects()->pluck('subjects.id')->toArray();
            
            $homeworks = $homeworks->whereIn('class_id', $allowedClasses)
                                   ->whereIn('subject_id', $allowedSubjects);
            
            $classes = $classes->whereIn('id', $allowedClasses);
            $subjects = $subjects->whereIn('id', $allowedSubjects);
        }

        return view('homework.index', compact('homeworks', 'classes', 'subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required',
            'section_id' => 'required',
            'subject_id' => 'required',
            'title' => 'required',
            'due_date' => 'required|date',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:5120',
        ]);

        $user = auth()->user();
        if ($user->hasAnyRole(['Teacher', 'Staff']) && $user->teacher) {
            $allowedSubjects = $user->teacher->subjects()->pluck('subjects.id')->toArray();
            $allowedClasses = $user->teacher->subjects()->pluck('class_id')->unique()->toArray();
            
            if (!in_array($request->subject_id, $allowedSubjects) || !in_array($request->class_id, $allowedClasses)) {
                abort(403, 'Unauthorized to assign homework for this subject or class.');
            }
        }

        $this->homeworkService->createHomework($request->all());

        return redirect()->back()->with('success', 'Homework assigned successfully');
    }

    public function destroy(Homework $homework)
    {
        $user = auth()->user();
        if ($user->hasAnyRole(['Teacher', 'Staff']) && $user->teacher) {
            $allowedSubjects = $user->teacher->subjects()->pluck('subjects.id')->toArray();
            $allowedClasses = $user->teacher->subjects()->pluck('class_id')->unique()->toArray();
            
            if (!in_array($homework->subject_id, $allowedSubjects) || !in_array($homework->class_id, $allowedClasses)) {
                abort(403, 'Unauthorized to delete this homework.');
            }
        }

        $this->homeworkService->deleteHomework($homework);
        return redirect()->back()->with('success', 'Homework deleted');
    }
}
