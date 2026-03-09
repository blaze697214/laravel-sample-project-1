<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
    /** @use HasFactory<\Database\Factories\ProgrammeFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'abbrevation',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Courses::class, 'programme_course', 'programme_id', 'course_id')->withPivot('is_elective');
    }

    public function programmeLevelDetails()
    {
        return $this->hasMany(ProgrammeLevelDetail::class);
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
