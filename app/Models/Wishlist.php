<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'course_id'];

    // Relation avec l'utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec le produit
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
