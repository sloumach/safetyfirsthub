<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'title',
        'video_path',
        'duration',
    ];

    // Relation avec la section
    public function section()
    {
        return $this->belongsTo(Section::class);
    }
    public function videoProgress()
{
    return $this->hasMany(VideoProgress::class);
}

}
