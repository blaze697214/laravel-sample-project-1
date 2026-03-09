<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Syllabus extends Model
{
    /** @use HasFactory<\Database\Factories\SyllabusFactory> */
    use HasFactory;

    protected $fillable = [
        'programme_course_id',
        'rationale',
        'created_by',
        'is_submitted',
        'is_approved'
    ];

    public function programmeCourse()
    {
        return $this->belongsTo(Syllabus::class);
    } 
}
