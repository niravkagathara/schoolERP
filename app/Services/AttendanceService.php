<?php

namespace App\Services;

use App\Models\StudentAttendance;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    public function getStudentsForAttendance($classId, $sectionId)
    {
        return Student::with('user')
            ->where('class_id', $classId)
            ->where('section_id', $sectionId)
            ->get();
    }

    public function markAttendance(array $data)
    {
        return DB::transaction(function () use ($data) {
            $date = $data['date'];
            $classId = $data['class_id'];
            $sectionId = $data['section_id'];

            foreach ($data['attendance'] as $studentId => $status) {
                StudentAttendance::updateOrCreate([
                    'student_id' => $studentId,
                    'date' => $date,
                ], [
                    'class_id' => $classId,
                    'section_id' => $sectionId,
                    'status' => $status,
                ]);
            }
            return true;
        });
    }

    public function getAttendanceReport($classId, $sectionId, $date)
    {
        return StudentAttendance::with('student.user')
            ->where('class_id', $classId)
            ->where('section_id', $sectionId)
            ->where('date', $date)
            ->get();
    }
}
