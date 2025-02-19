<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class VideoProgress extends Model
{
    use HasFactory;



    protected $fillable = [
        'user_id',
        'course_id',
        'watched_segments',
        'total_duration',
        'is_completed'
    ];

    protected $casts = [
        'watched_segments' => 'array', // Cast JSON en array PHP automatiquement
        'is_completed' => 'boolean',
    ];

    /**
     * Relation avec l'utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec le cours.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
