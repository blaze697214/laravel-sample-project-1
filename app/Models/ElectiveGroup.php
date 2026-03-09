<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElectiveGroup extends Model
{
    /** @use HasFactory<\Database\Factories\ElectiveGroupFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'level_id',
        'programme_id',
        'min_select_count',
        'max_select_count',
    ];

    public function level()
    {
        return $this->belongsTo(Levels::class);
    }

    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Courses::class, 'elective_group_courses');
    }

    public function classAwardConfigurations()
    {
        return $this->belongsToMany(ClassAwardConfiguration::class, 'class_award_elective_groups');
    }

    public function semesterPlacements()
    {
        return $this->morphMany(SemesterPlacement::class, 'placeable');
    }
}
