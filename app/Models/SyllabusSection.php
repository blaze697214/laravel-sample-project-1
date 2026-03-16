<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyllabusSection extends Model
{
    /** @use HasFactory<\Database\Factories\SyllabusSectionFactory> */
    use HasFactory;

    protected $fillable = [
        'syllabus_id',
        'title',
        'section_template_id',
        'order_no',
    ];

    public function syllabus()
    {
        return $this->belongsTo(Syllabus::class);
    }

    public function sectionTemplate()
    {
        return $this->belongsTo(SectionTemplate::class);
    }

    public function tutorialPoints()
    {
        return $this->hasMany(Tutorial::class, 'section_id');
    }

    public function selfLearningContents()
    {
        return $this->hasMany(SelfLearning::class, 'section_id');
    }
}
