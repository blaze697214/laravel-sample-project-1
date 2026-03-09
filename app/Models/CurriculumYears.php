<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurriculumYears extends Model
{
    /** @use HasFactory<\Database\Factories\CurriculumYearsFactory> */
    use HasFactory;

    protected $fillable = [
        'year_start',
        'year_end',
        'total_credits',
        'total_marks',
        'created_by',
        'is_active',
        'is_locked'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class,'created_by');
    }

    public function levels()
    {
        return $this->hasMany(Levels::class,);
    }

    public function courses()
    {
        return $this->hasMany(Courses::class);
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
