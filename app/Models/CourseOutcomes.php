<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseOutcomes extends Model
{
    /** @use HasFactory<\Database\Factories\CourseOutcomesFactory> */
    use HasFactory;

    protected $fillable = [
        'syllabus_id',
        'co_code',
        'description',
        'order',
    ];

    public function syllabus()
    {
        return $this->belongsTo(Syllabus::class);
    }

    public function specificationRows()
    {
        return $this->hasMany(SpecificationTable::class);
    }

    public function coPoMappings()
    {
        return $this->hasMany(CoPoPsoMappings::class);
    }

    public function questionPaperProfiles()
    {
        return $this->hasMany(QuestionPaperProfiles::class);
    }

    public function questionBits()
    {
        return $this->hasMany(QuestionBits::class);
    }
}
