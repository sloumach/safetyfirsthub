<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name']; // Permet d'ajouter des rôles facilement

    // Relation avec les utilisateurs
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
