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

    public function departments()
    {
        return $this->belongsToMany(Department::class,'department_course','course_id','department_id')->withPivot('is_elective');
    }

    public function electiveGroup()
    {
        return $this->belongsToMany(ElectiveGroup::class,'elective_group_courses');
    }

    public function classAwardConfigurations()
    {
        return $this->belongsToMany(ClassAwardConfiguration::class,'class_award_compulsory_courses');
    }
}
