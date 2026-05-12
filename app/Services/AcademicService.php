<?php

namespace App\Services;

use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;

class AcademicService
{
    // Classes
    public function getAllClasses()
    {
        $query = SchoolClass::with('sections')->withCount(['sections', 'subjects']);
        $user = auth()->user();

        if ($user->hasRole('Student') && $user->student) {
            $query->where('id', $user->student->class_id);
        } elseif ($user->hasAnyRole(['Teacher', 'Staff']) && $user->teacher) {
            $classIds = $user->teacher->subjects()->pluck('class_id')->unique()->toArray();
            $query->whereIn('id', $classIds);
        }

        return $query->get();
    }

    public function createClass(array $data)
    {
        return SchoolClass::create($data);
    }

    public function updateClass(SchoolClass $class, array $data)
    {
        return $class->update($data);
    }

    public function deleteClass(SchoolClass $class)
    {
        return $class->delete();
    }

    // Sections
    public function getAllSections()
    {
        $query = Section::with('class');
        $user = auth()->user();

        if ($user->hasRole('Student') && $user->student) {
            $query->where('class_id', $user->student->class_id)
                  ->where('id', $user->student->section_id);
        } elseif ($user->hasAnyRole(['Teacher', 'Staff']) && $user->teacher) {
            $classIds = $user->teacher->subjects()->pluck('class_id')->unique()->toArray();
            $query->whereIn('class_id', $classIds);
        }

        return $query->get();
    }

    public function createSection(array $data)
    {
        return Section::create($data);
    }

    public function deleteSection(Section $section)
    {
        return $section->delete();
    }

    // Subjects
    public function getAllSubjects()
    {
        $query = Subject::with(['class', 'teachers.user']);
        $user = auth()->user();

        if ($user->hasRole('Student') && $user->student) {
            $query->where('class_id', $user->student->class_id);
        } elseif ($user->hasAnyRole(['Teacher', 'Staff']) && $user->teacher) {
            $subjectIds = $user->teacher->subjects()->pluck('subjects.id')->toArray();
            $query->whereIn('id', $subjectIds);
        }

        return $query->get();
    }

    public function createSubject(array $data)
    {
        $subject = Subject::create([
            'name' => $data['name'],
            'code' => $data['code'],
            'class_id' => $data['class_id'] ?? null,
        ]);

        if (isset($data['teachers'])) {
            $subject->teachers()->sync($data['teachers']);
        }

        return $subject;
    }

    public function updateSubject(Subject $subject, array $data)
    {
        $subject->update([
            'name' => $data['name'],
            'code' => $data['code'],
            'class_id' => $data['class_id'] ?? null,
        ]);

        if (isset($data['teachers'])) {
            $subject->teachers()->sync($data['teachers']);
        }

        return $subject;
    }

    public function deleteSubject(Subject $subject)
    {
        return $subject->delete();
    }
}
