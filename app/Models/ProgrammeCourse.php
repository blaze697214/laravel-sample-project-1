<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgrammeCourse extends Model
{
    //
    protected $table = 'programme_course';

    protected $fillable = [
        'programme_id',
        'course_id',
        'is_elective',
    ];

    public function programme()
    {
        return $this->belongsTo(Programme::class);
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
