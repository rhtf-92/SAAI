<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $fillable = [
        'id',
        'student_id',
        'term_id',
        'plan_id',
        'academic_period_id',
        'date',
        'status',
        'observation',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function details()
    {
        return $this->hasMany(EnrollmentDetail::class);
    }
}
