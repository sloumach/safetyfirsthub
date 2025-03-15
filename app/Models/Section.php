<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'position',
    ];

    // Relation avec le cours
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Relation avec les slides
    public function slides()
    {
        return $this->hasMany(Slide::class);
    }

    // Relation avec les vidéos
    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    // Relation avec les quiz
    public function quiz()
    {
        return $this->hasOne(SectionQuiz::class, 'section_id')->with('questions.choices');
    }
    

   
}
