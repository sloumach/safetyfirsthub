<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


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
    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

}
