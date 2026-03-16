<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitSubtopics extends Model
{
    /** @use HasFactory<\Database\Factories\UnitSubtopicsFactory> */
    use HasFactory;

    protected $fillable = [
        'unit_topic_id',
        'subtopic',
        'order'
    ];

    public function unitTopic()
{
    return $this->belongsTo(UnitTopics::class);
}
}
