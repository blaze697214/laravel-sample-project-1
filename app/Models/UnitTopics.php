<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitTopics extends Model
{
    /** @use HasFactory<\Database\Factories\UnitTopicsFactory> */
    use HasFactory;

    protected $fillable = [
        'syllabus_unit_id',
        'type',
        'content',
        'order',
    ];

    public function syllabusUnit()
    {
        return $this->belongsTo(SyllabusUnits::class);
    }

    // public function subtopics()
    // {
    //     return $this->hasMany(UnitSubtopics::class, 'unit_topic_id');
    // }

    public function subtopics()
{
    return $this->hasMany(UnitSubtopics::class)
                ->orderBy('order');
}
}
