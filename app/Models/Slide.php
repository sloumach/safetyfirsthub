<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Slide extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'title',
        'content',
        'file_path',
        'position',
    ];

    // Relation avec la section
    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
