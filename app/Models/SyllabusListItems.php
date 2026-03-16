<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyllabusListItems extends Model
{
    /** @use HasFactory<\Database\Factories\SyllabusListItemsFactory> */
    use HasFactory;

    protected $fillable = [
        'syllabus_id',
        'type',
        'content',
        'order_no',
    ];

    public function syllabus()
    {
        return $this->belongsTo(Syllabus::class);
    }
}
