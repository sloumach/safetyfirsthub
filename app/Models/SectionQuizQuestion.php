<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionQuizQuestion extends Model
{
    use HasFactory;

    protected $fillable = ['quiz_id', 'question_text'];

    public function quiz()
    {
        return $this->belongsTo(SectionQuiz::class, 'quiz_id');
    }

    public function choices()
    {
        return $this->hasMany(SectionQuizChoice::class, 'question_id');
    }
}
