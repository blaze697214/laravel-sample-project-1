<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Levels extends Model
{
    /** @use HasFactory<\Database\Factories\LevelsFactory> */
    use HasFactory;

    protected $fillable = [
        'curriculum_year_id',
        'name',
        'order_no',
        'is_audit'
    ];

    public function curriculumYear()
    {
        return $this->belongsTo(CurriculumYears::class);
    }

    public function courses()
    {
        return $this->hasMany(Courses::class,'level_id');
    }

    public function departmentLevelDetails()
    {
        return $this->hasMany(DepartmentLevelDetail::class);
    }

    public function electiveGroups()
    {   
        return $this->hasMany(ElectiveGroup::class);
    }
}
