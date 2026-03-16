<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyllabusUnits extends Model
{
    /** @use HasFactory<\Database\Factories\SyllabusUnitsFactory> */
    use HasFactory;

    protected $fillable = [
        'syllabus_id',
        'unit_no',
        'title',
        'hours',
        'order',
    ];

    public function syllabus()
    {
        return $this->belongsTo(Syllabus::class);
    }

    public function unitTopics()
    {
        return $this->hasMany(UnitTopics::class);
    }

    public function specificationRows()
    {
        return $this->hasMany(SpecificationTable::class);
    }

    // public function practicalTasks()
    // {
    //     return $this->hasMany(PracticalTasks::class, 'unit_id');
    // }

    public function questionPaperProfiles()
    {
        return $this->hasMany(QuestionPaperProfiles::class, 'unit_id');
    }

    public function learningOutcomes()
    {
        return $this->hasMany(UnitTopics::class)
            ->where('type', 'learning_outcome')
            ->orderBy('order');
    }

    public function topicsOnly()
    {
        return $this->hasMany(UnitTopics::class)
            ->where('type', 'topic')
            ->orderBy('order');
    }

    public function practicalTasks()
    {
        return $this->hasMany(PracticalTasks::class, 'unit_id')
            ->orderBy('order_no');
    }
}
