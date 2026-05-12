<?php

namespace App\Services;

use App\Models\Homework;
use Illuminate\Support\Facades\Storage;

class HomeworkService
{
    public function getAllHomework()
    {
        $query = Homework::with(['class', 'section', 'subject'])->orderBy('due_date', 'desc');
        $user = auth()->user();

        if ($user->hasRole('Student') && $user->student) {
            $query->where('class_id', $user->student->class_id)
                  ->where('section_id', $user->student->section_id);
        } elseif ($user->hasAnyRole(['Teacher', 'Staff']) && $user->teacher) {
            $subjectIds = $user->teacher->subjects()->pluck('subjects.id')->toArray();
            $query->whereIn('subject_id', $subjectIds);
        }

        return $query->get();
    }

    public function createHomework(array $data)
    {
        $filePath = null;
        if (isset($data['file'])) {
            $filePath = $data['file']->store('homework', 'public');
        }

        return Homework::create([
            'class_id' => $data['class_id'],
            'section_id' => $data['section_id'],
            'subject_id' => $data['subject_id'],
            'title' => $data['title'],
            'description' => $data['description'],
            'due_date' => $data['due_date'],
            'file_path' => $filePath,
        ]);
    }

    public function deleteHomework(Homework $homework)
    {
        if ($homework->file_path) {
            Storage::disk('public')->delete($homework->file_path);
        }
        return $homework->delete();
    }
}
