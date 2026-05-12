<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id', 'admission_no', 'class_id', 'section_id', 'academic_session_id', 'parent_id',
        'phone', 'address', 'dob', 'gender', 'document'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function parent()
    {
        return $this->belongsTo(SchoolParent::class, 'parent_id');
    }

    public function feePayments()
    {
        return $this->hasMany(FeePayment::class);
    }

    public function session()
    {
        return $this->belongsTo(AcademicSession::class, 'academic_session_id');
    }
}
