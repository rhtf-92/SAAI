<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnrollmentDetail extends Model
{
    protected $fillable = [
        'id',
        'enrollment_id',
        'course_id',
        'credits',
    ];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function course()
    {
        return $this->belongsTo(\App\Models\Academic\Course::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
