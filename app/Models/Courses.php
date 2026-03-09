<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courses extends Model
{
    /** @use HasFactory<\Database\Factories\CoursesFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'abbrevation',
        'curriculum_year_id',
        'level_id'
    ];

    public function curriculumYear()
    {
        return $this->belongsTo(CurriculumYears::class);
    }

    public function levels()
    {
        return $this->belongsTo(Levels::class);
    }

    public function programmes()
    {
        return $this->belongsToMany(Programme::class,'programme_course','course_id','programme_id')->withPivot('is_elective');
    }
}
