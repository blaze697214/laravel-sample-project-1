<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassAwardConfiguration extends Model
{
    /** @use HasFactory<\Database\Factories\ClassAwardConfigurationFactory> */
    use HasFactory;

    protected $fillable = [
        'programme_id',
        'curriculum_year_id',
        'total_class_award_courses',
        'created_by'
    ];

    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }

    public function curriculumYear()
    {
        return $this->belongsTo(CurriculumYears::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function electiveGroups()
    {
        return $this->belongsToMany(ElectiveGroup::class,'class_award_elective_groups');
    }

    public function courses()
    {
        return $this->belongsToMany(Courses::class,'class_award_compulsory_courses');
    }
}
