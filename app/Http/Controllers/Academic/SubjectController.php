<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Http\Requests\Academic\StoreSubjectRequest;
use App\Models\Subject;
use App\Services\AcademicService;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    protected $academicService;

    public function __construct(AcademicService $academicService)
    {
        $this->academicService = $academicService;
    }

    public function index()
    {
        $user = auth()->user();
        $subjects = $this->academicService->getAllSubjects();
        $classes = \App\Models\SchoolClass::all();
        $teachers = \App\Models\Teacher::with('user')->get();

        if ($user->hasRole('Student') && $user->student) {
            $subjects = $subjects->where('class_id', $user->student->class_id);
            $classes = $classes->where('id', $user->student->class_id);
        } elseif ($user->hasAnyRole(['Teacher', 'Staff']) && $user->teacher) {
            $allowedClasses = $user->teacher->subjects()->pluck('class_id')->unique()->toArray();
            $allowedSubjects = $user->teacher->subjects()->pluck('subjects.id')->toArray();
            $subjects = $subjects->whereIn('id', $allowedSubjects);
            $classes = $classes->whereIn('id', $allowedClasses);
        }

        return view('academic.subjects.index', compact('subjects', 'classes', 'teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required',
            'class_id' => 'required|exists:classes,id',
            'teachers' => 'nullable|array',
            'teachers.*' => 'exists:teachers,id'
        ]);
        $this->academicService->createSubject($request->all());

        return redirect()->back()->with('success', 'Subject created successfully');
    }

    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required',
            'class_id' => 'required|exists:classes,id',
            'teachers' => 'nullable|array',
            'teachers.*' => 'exists:teachers,id'
        ]);
        $this->academicService->updateSubject($subject, $request->all());

        return redirect()->back()->with('success', 'Subject updated successfully');
    }

    public function destroy(Subject $subject)
    {
        $this->academicService->deleteSubject($subject);
        return redirect()->back()->with('success', 'Subject deleted successfully');
    }
}
