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
        'level_id',
        'owner_department_id',
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
        return $this->belongsToMany(Department::class, 'department_course', 'course_id', 'department_id')->withPivot('is_elective');
    }

    public function departmentCourses()
    {
        return $this->hasMany(DepartmentCourse::class, 'course_id');
    }


    public function ownerDepartment()
    {
        return $this->belongsTo(Department::class, 'owner_department_id');
    }

    public function electiveGroup()
    {
        return $this->belongsToMany(ElectiveGroup::class, 'elective_group_courses','course_id',
        'elective_group_id');
    }

    public function classAwardConfigurations()
    {
        return $this->belongsToMany(ClassAwardConfiguration::class, 'class_award_compulsory_courses','course_id','award_configurations_id');
    }
}
