<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Http\Requests\Academic\StoreClassRequest;
use App\Models\SchoolClass;
use App\Services\AcademicService;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    protected $academicService;

    public function __construct(AcademicService $academicService)
    {
        $this->academicService = $academicService;
    }

    public function index()
    {
        $user = auth()->user();
        $classes = $this->academicService->getAllClasses();

        if ($user->hasRole('Student') && $user->student) {
            $classes = $classes->where('id', $user->student->class_id);
        } elseif ($user->hasAnyRole(['Teacher', 'Staff']) && $user->teacher) {
            $allowedClasses = $user->teacher->subjects()->pluck('class_id')->unique()->toArray();
            $classes = $classes->whereIn('id', $allowedClasses);
        }

        return view('academic.classes.index', compact('classes'));
    }

    public function store(StoreClassRequest $request)
    {
        $this->academicService->createClass($request->validated());

        return redirect()->back()->with('success', 'Class created successfully');
    }

    public function update(Request $request, SchoolClass $class)
    {
        $request->validate(['name' => 'required|unique:classes,name,' . $class->id]);
        $this->academicService->updateClass($class, $request->all());

        return redirect()->back()->with('success', 'Class updated successfully');
    }

    public function destroy(SchoolClass $class)
    {
        $this->academicService->deleteClass($class);
        return redirect()->back()->with('success', 'Class deleted successfully');
    }
}
