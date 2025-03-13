<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'expiration_date',
        'is_active',
        'usage_limit',
        'used_count'
    ];

    /**
     * Vérifie si le coupon est valide
     *
     * @return bool
     */
    public function isValid()
    {
        return $this->is_active &&
               Carbon::parse($this->expiration_date)->isFuture() &&
               ($this->usage_limit === null || $this->used_count < $this->usage_limit);
    }

    /**
     * Applique la réduction sur un montant donné
     *
     * @param float $subtotal
     * @return float
     */
    public function applyDiscount($subtotal)
    {
        if ($this->discount_type === 'fixed') {
            return max(0, $subtotal - $this->discount_value);
        }

        if ($this->discount_type === 'percentage') {
            return max(0, $subtotal * (1 - ($this->discount_value / 100)));
        }

        return $subtotal;
    }

        public function users()
    {
        return $this->belongsToMany(User::class, 'coupon_user')->withPivot('times_used')->withTimestamps();
    }

}
