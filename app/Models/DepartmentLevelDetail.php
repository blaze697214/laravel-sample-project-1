<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentLevelDetail extends Model
{
    /** @use HasFactory<\Database\Factories\DepartmentLevelDetailFactory> */
    use HasFactory;

    protected $fillable = [
        'level_id',
        'department_id',
        'courses_offered',
        'compulsory_to_complete',
        'elective_to_complete',
        'th_hrs',
        'tu_hrs',
        'pr_hrs',
        'credits',
        'marks',
        'is_configured'
    ];

    public function level()
    {
        return $this->belongsTo(Levels::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
