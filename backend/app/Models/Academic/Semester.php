<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;

    protected $fillable = [
        'year', 'number', 'type', 'start_date', 'end_date', 'is_active', 'is_enrollment_active'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'is_enrollment_active' => 'boolean',
    ];

    public function planPeriods()
    {
        return $this->hasMany(PlanPeriod::class);
    }
}
