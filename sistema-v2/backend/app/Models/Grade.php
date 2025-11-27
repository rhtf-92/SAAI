<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = [
        'enrollment_detail_id',
        'name',
        'type',
        'weight',
        'score',
    ];

    public function enrollmentDetail()
    {
        return $this->belongsTo(EnrollmentDetail::class);
    }
}
