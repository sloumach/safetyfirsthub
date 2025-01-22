<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'price',
        'category',
        'total_videos',
        'description',
        'cover',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'course_user');
    }

}
