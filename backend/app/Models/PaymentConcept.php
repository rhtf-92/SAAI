<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentConcept extends Model
{
    protected $fillable = [
        'code',
        'name',
        'amount',
        'is_recurring',
    ];
}
