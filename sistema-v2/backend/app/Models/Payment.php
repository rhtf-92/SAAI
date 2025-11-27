<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'student_id',
        'debt_id',
        'amount',
        'paid_at',
        'payment_method',
        'operation_number',
        'observation',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function debt()
    {
        return $this->belongsTo(Debt::class);
    }
}
