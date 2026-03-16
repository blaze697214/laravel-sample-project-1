<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoPoPsoMappings extends Model
{
    /** @use HasFactory<\Database\Factories\CoPoPsoMappingsFactory> */
    use HasFactory;

    public function programmeOutcome()
{
    return $this->belongsTo(ProgrammeOutcomes::class);
}
}
