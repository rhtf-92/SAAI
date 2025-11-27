<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'program_id', 'name', 'type', 'modality', 'focus', 'date', 'document_url'
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function semesters()
    {
        return $this->belongsToMany(Semester::class, 'plan_periods');
    }

    public function academicPeriods()
    {
        return $this->hasMany(AcademicPeriod::class);
    }
}
