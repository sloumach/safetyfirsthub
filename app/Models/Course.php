<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        /* 'price_stripe', */
        'category',
        'total_videos',
        'short_description', // Nouvelle colonne
        'description',
        'cover',
        'students', // Nouvelle colonne
    ];

    /**
     * Relation Many-to-Many avec User (les utilisateurs ayant acheté ce cours)
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'course_user');
    }

    /**
     * Relation One-to-Many avec Wishlist (les utilisateurs ayant mis ce cours en favori)
     */
    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

