<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Levels extends Model
{
    /** @use HasFactory<\Database\Factories\LevelsFactory> */
    use HasFactory;

    public function curriculumYear()
    {
        return $this->belongsTo(CurriculumYears::class);
    }

    public function courses()
    {
        return $this->hasMany(Courses::class);
    }
}
