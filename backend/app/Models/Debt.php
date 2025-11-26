<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
    protected $fillable = [
        'student_id',
        'payment_concept_id',
        'description',
        'amount',
        'due_date',
        'status',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function concept()
    {
        return $this->belongsTo(PaymentConcept::class, 'payment_concept_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
