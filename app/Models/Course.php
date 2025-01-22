<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'course_user');
    }

}
