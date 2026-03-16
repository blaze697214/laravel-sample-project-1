<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Syllabus extends Model
{
    /** @use HasFactory<\Database\Factories\SyllabusFactory> */
    use HasFactory;

    protected $fillable = [
        'department_course_id',
        'rationale',
        'created_by',
        'is_submitted',
        'is_approved',
    ];

    public function departmentCourse()
    {
        return $this->belongsTo(DepartmentCourse::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function sections()
    {
        return $this->hasMany(SyllabusSection::class)->orderBy('order_no');
    }

    public function listItems()
    {
        return $this->hasMany(SyllabusListItems::class)
            ->orderBy('order_no');
    }

    public function units()
    {
        return $this->hasMany(SyllabusUnits::class)
            ->orderBy('order');
    }

    public function courseOutcomes()
    {
        return $this->hasMany(CourseOutcomes::class)
            ->orderBy('order');
    }

    public function practicalTasks()
    {
        return $this->hasMany(PracticalTasks::class)
            ->orderBy('order_no');
    }
}
