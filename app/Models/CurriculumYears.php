<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurriculumYears extends Model
{
    /** @use HasFactory<\Database\Factories\CurriculumYearsFactory> */
    use HasFactory;

    public function creator()
    {
        return $this->belongsTo(User::class,'created_by');
    }

    public function levels()
    {
        return $this->hasMany(Levels::class,);
    }

    public function courses()
    {
        return $this->hasMany(Courses::class);
    }
}
