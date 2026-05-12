<?php

namespace App\Services;

use App\Models\Exam;
use App\Models\ExamMark;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class ExamService
{
    public function getAllExams()
    {
        $query = Exam::with(['class']);
        $user = auth()->user();

        if ($user->hasRole('Student') && $user->student) {
            $query->where('class_id', $user->student->class_id);
        } elseif ($user->hasAnyRole(['Teacher', 'Staff']) && $user->teacher) {
            $classIds = $user->teacher->subjects()->pluck('class_id')->unique()->toArray();
            $query->whereIn('class_id', $classIds);
        }

        return $query->get();
    }

    public function createExam(array $data)
    {
        return Exam::create($data);
    }

    public function getStudentsForMarks($examId, $classId, $sectionId)
    {
        return Student::with(['user'])
            ->where('class_id', $classId)
            ->where('section_id', $sectionId)
            ->get();
    }

    public function storeMarks(array $data)
    {
        return DB::transaction(function () use ($data) {
            $examId = $data['exam_id'];
            $subjectId = $data['subject_id'];

            foreach ($data['marks'] as $studentId => $marks) {
                ExamMark::updateOrCreate([
                    'exam_id' => $examId,
                    'student_id' => $studentId,
                    'subject_id' => $subjectId,
                ], [
                    'marks_obtained' => $marks,
                    'total_marks' => 100, // Default or passed value
                ]);
            }
            return true;
        });
    }

    public function calculateGrade($marks)
    {
        if ($marks >= 90) return 'A+';
        if ($marks >= 80) return 'A';
        if ($marks >= 70) return 'B';
        if ($marks >= 60) return 'C';
        if ($marks >= 50) return 'D';
        return 'F';
    }
}
