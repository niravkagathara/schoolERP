<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Http\Requests\Academic\StoreSectionRequest;
use App\Models\Section;
use App\Models\SchoolClass;
use App\Services\AcademicService;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    protected $academicService;

    public function __construct(AcademicService $academicService)
    {
        $this->academicService = $academicService;
    }

    public function index()
    {
        $user = auth()->user();
        $sections = $this->academicService->getAllSections();
        $classes = SchoolClass::all();

        if ($user->hasRole('Student') && $user->student) {
            $sections = $sections->where('class_id', $user->student->class_id)
                                 ->where('id', $user->student->section_id);
            $classes = $classes->where('id', $user->student->class_id);
        } elseif ($user->hasAnyRole(['Teacher', 'Staff']) && $user->teacher) {
            $allowedClasses = $user->teacher->subjects()->pluck('class_id')->unique()->toArray();
            $sections = $sections->whereIn('class_id', $allowedClasses);
            $classes = $classes->whereIn('id', $allowedClasses);
        }

        return view('academic.sections.index', compact('sections', 'classes'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required', 'class_id' => 'required']);
        $this->academicService->createSection($request->all());

        return redirect()->back()->with('success', 'Section created successfully');
    }

    public function update(Request $request, Section $section)
    {
        $request->validate(['name' => 'required', 'class_id' => 'required']);
        $section->update($request->all());

        return redirect()->back()->with('success', 'Section updated successfully');
    }

    public function destroy(Section $section)
    {
        $this->academicService->deleteSection($section);
        return redirect()->back()->with('success', 'Section deleted successfully');
    }
}
