<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = ['name', 'class_id', 'academic_session_id'];

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
}
