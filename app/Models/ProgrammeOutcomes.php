<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgrammeOutcomes extends Model
{
    /** @use HasFactory<\Database\Factories\ProgrammeOutcomesFactory> */
    use HasFactory;

    protected $fillable = [
        'programme_id',
        'curriculum_year_id',
        'code',
        'type',
        'description',
        'order',
    ];

    public function department()
{
    return $this->belongsTo(Department::class);
}

public function curriculumYear()
{
    return $this->belongsTo(CurriculumYears::class);
}

public function coPoMappings()
{
    return $this->hasMany(CoPoPsoMappings::class);
}
}
