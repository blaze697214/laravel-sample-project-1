<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentCourse extends Model
{
    //
    protected $table = 'department_course';

    protected $fillable = [
        'department_id',
        'course_id',
        'is_elective',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function course()
    {
        return $this->belongsTo(Courses::class);
    }

    public function courseDetail()
    {
        return $this->hasOne(CourseDetail::class);
    }

    public function syllabus()
    {
        return $this->hasOne(Syllabus::class);
    }

    public function semesterPlacements()
    {
        return $this->morphMany(SemesterPlacement::class, 'placeable');
    }
}
