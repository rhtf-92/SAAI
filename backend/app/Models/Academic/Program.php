<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'code', 'name', 'level'];

    public function plans()
    {
        return $this->hasMany(Plan::class);
    }
}
