<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Services\AttendanceService;
use Illuminate\Http\Request;

use App\Services\AcademicService;

class AttendanceController extends Controller
{
    protected $attendanceService;
    protected $academicService;

    public function __construct(AttendanceService $attendanceService, AcademicService $academicService)
    {
        $this->attendanceService = $attendanceService;
        $this->academicService = $academicService;
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $classes = $this->academicService->getAllClasses();
        $students = [];

        if ($user->hasRole('Student')) {
            abort(403, 'Unauthorized access');
        } elseif ($user->hasAnyRole(['Teacher', 'Staff']) && $user->teacher) {
            $allowedClasses = $user->teacher->subjects()->pluck('class_id')->unique()->toArray();
            $classes = $classes->whereIn('id', $allowedClasses);
        }
        
        if ($request->filled(['class_id', 'section_id'])) {
            if ($user->hasAnyRole(['Teacher', 'Staff']) && $user->teacher) {
                $allowedClasses = $user->teacher->subjects()->pluck('class_id')->unique()->toArray();
                if (!in_array($request->class_id, $allowedClasses)) {
                    abort(403, 'Unauthorized to view attendance for this class.');
                }
            }
            $students = $this->attendanceService->getStudentsForAttendance($request->class_id, $request->section_id);
        }

        return view('attendance.index', compact('classes', 'students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required',
            'section_id' => 'required',
            'date' => 'required|date',
            'attendance' => 'required|array',
        ]);

        $user = auth()->user();
        if ($user->hasAnyRole(['Teacher', 'Staff']) && $user->teacher) {
            $allowedClasses = $user->teacher->subjects()->pluck('class_id')->unique()->toArray();
            if (!in_array($request->class_id, $allowedClasses)) {
                abort(403, 'Unauthorized to mark attendance for this class.');
            }

            $studentIds = array_keys($request->attendance);
            $invalidStudents = \App\Models\Student::whereIn('id', $studentIds)
                ->whereNotIn('class_id', $allowedClasses)
                ->exists();

            if ($invalidStudents) {
                abort(403, 'Unauthorized to mark attendance for students outside your assigned classes.');
            }
        }

        $this->attendanceService->markAttendance($request->all());

        return redirect()->back()->with('success', 'Attendance marked successfully');
    }

    public function report(Request $request)
    {
        $user = auth()->user();
        $classes = $this->academicService->getAllClasses();
        $attendanceData = [];

        if ($user->hasRole('Student')) {
            abort(403, 'Unauthorized access');
        } elseif ($user->hasAnyRole(['Teacher', 'Staff']) && $user->teacher) {
            $allowedClasses = $user->teacher->subjects()->pluck('class_id')->unique()->toArray();
            $classes = $classes->whereIn('id', $allowedClasses);
        }

        if ($request->filled(['class_id', 'section_id', 'date'])) {
            if ($user->hasAnyRole(['Teacher', 'Staff']) && $user->teacher) {
                $allowedClasses = $user->teacher->subjects()->pluck('class_id')->unique()->toArray();
                if (!in_array($request->class_id, $allowedClasses)) {
                    abort(403, 'Unauthorized to view attendance report for this class.');
                }
            }
            $attendanceData = $this->attendanceService->getAttendanceReport($request->class_id, $request->section_id, $request->date);
        }

        return view('attendance.report', compact('classes', 'attendanceData'));
    }

    public function studentCalendar(Student $student)
    {
        $user = auth()->user();
        if ($user->hasRole('Student') && $user->student && $user->student->id !== $student->id) {
            abort(403, 'Unauthorized access to other student attendance');
        }

        if ($user->hasAnyRole(['Teacher', 'Staff']) && $user->teacher) {
            $allowedClasses = $user->teacher->subjects()->pluck('class_id')->unique()->toArray();
            if (!in_array($student->class_id, $allowedClasses)) {
                abort(403, 'Unauthorized access to this student attendance');
            }
        }

        $attendance = StudentAttendance::where('student_id', $student->id)->get();
        $events = $attendance->map(function($item) {
            return [
                'title' => $item->status,
                'start' => $item->date,
                'backgroundColor' => $item->status == 'Present' ? '#25b003' : ($item->status == 'Absent' ? '#e62e05' : '#f5a623'),
                'borderColor' => $item->status == 'Present' ? '#25b003' : ($item->status == 'Absent' ? '#e62e05' : '#f5a623'),
            ];
        });

        return view('attendance.student_calendar', compact('student', 'events'));
    }
}
