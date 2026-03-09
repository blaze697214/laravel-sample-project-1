<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseDetail extends Model
{
    /** @use HasFactory<\Database\Factories\CourseDetailFactory> */
    use HasFactory;
    protected $fillable = [
        'programme_course_id',
        'course_code',
        'th_hrs',
        'tu_hrs',
        'pr_hrs',
        'credits',
        'th_marks',
        'test_marks',
        'pr_marks',
        'or_marks',
        'tw_marks',
        'created_by'
    ];

    public function programmeCourse()
    {
        return $this->belongsTo(ProgrammeCourse::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
