<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'number', 'floor', 'capacity', 'location', 'observation'
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
