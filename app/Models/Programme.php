<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
    /** @use HasFactory<\Database\Factories\ProgrammeFactory> */
    use HasFactory;

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Courses::class,'programme_course','programme_id','course_id')->withPivot('is_elective');
    }

    protected $fillable = [
        'name',
        'abbrevation'
    ];
}
