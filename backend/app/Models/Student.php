<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'plan_id',
        'code',
        'entry_year',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(\App\Models\Academic\Plan::class);
    }
}
