<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ExamUser extends Pivot
{
    use HasFactory;

    protected $table = 'exam_users';

    protected $fillable = [
        'user_id',
        'exam_id',
        'score',
        'status',
        'started_at',
        'completed_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function certificate()
    {
        return $this->hasOne(Certificate::class, 'exam_user_id');
    }

}
