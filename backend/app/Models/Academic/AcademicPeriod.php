<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Model;

class AcademicPeriod extends Model
{
    protected $fillable = [
        'id', 'plan_id', 'name', 'number'
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
