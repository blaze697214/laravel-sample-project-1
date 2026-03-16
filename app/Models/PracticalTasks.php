<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticalTasks extends Model
{
    /** @use HasFactory<\Database\Factories\PracticalTasksFactory> */
    use HasFactory;

    protected $fillable = [
        'syllabus_id',
        'unit_id',
        'lab_learning_outcome',
        'exercise',
        'hours',
        'order_no',
    ];

    public function syllabus()
    {
        return $this->belongsTo(Syllabus::class);
    }

    public function unit()
    {
        return $this->belongsTo(SyllabusUnits::class, 'unit_id');
    }
}
