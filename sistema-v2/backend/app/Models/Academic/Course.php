<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'id', 'academic_period_id', 'code', 'name', 'type', 'predecessor_code', 'hours', 'credits'
    ];

    public function academicPeriod()
    {
        return $this->belongsTo(AcademicPeriod::class);
    }
}
