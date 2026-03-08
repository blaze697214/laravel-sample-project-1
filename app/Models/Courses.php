<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courses extends Model
{
    /** @use HasFactory<\Database\Factories\CoursesFactory> */
    use HasFactory;

    public function curriculumYear()
    {
        return $this->belongsTo(CurriculumYears::class);
    }

    public function levels()
    {
        return $this->belongsTo(Levels::class);
    }
}
