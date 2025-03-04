<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class VideoProgress extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'video_id', 'watched_segments', 'total_duration', 'is_completed'];

    protected $casts = [
        'watched_segments' => 'array',
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
     * Relation avec le video.
     */
    public function video()
    {
        return $this->belongsTo(Video::class);
    }

}
