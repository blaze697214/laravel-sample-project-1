<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SemesterPlacement extends Model
{
    /** @use HasFactory<\Database\Factories\SemesterPlacementFactory> */
    use HasFactory;
    protected $fillable = [
        'programme_id',
        'curriculum_year_id',
        'academic_year_no',
        'semester_type',
        'placeable_id',
        'placeable_type'
    ];

    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }

    public function curriculumYear()
    {
        return $this->belongsTo(CurriculumYears::class);
    }


    public function placeable()
    {
        return $this->morphTo();
    }
}
