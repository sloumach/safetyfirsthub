<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionQuiz extends Model
{
    use HasFactory;

    protected $fillable = ['section_id', 'passing_score'];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function questions()
    {
        return $this->hasMany(SectionQuizQuestion::class, 'quiz_id');
    }
}
