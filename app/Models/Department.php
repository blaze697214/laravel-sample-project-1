<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    /** @use HasFactory<\Database\Factories\DepartmentFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'abbrevation',
        'type'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Courses::class, 'department_course', 'department_id', 'course_id')->withPivot('is_elective');
    }

    public function departmentLevelDetails()
    {
        return $this->hasMany(DepartmentLevelDetail::class);
    }

    public function electiveGroups()
    {   
        return $this->hasMany(ElectiveGroup::class);
    }

    public function classAwardConfigurations()
    {
        return $this->hasMany(ClassAwardConfiguration::class);
    }

    public function semesterPlacements()
    {
        return $this->hasMany(SemesterPlacement::class);
    }
    
}
