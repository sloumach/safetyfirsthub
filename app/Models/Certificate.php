<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_user_id',
        'certificate_url',
        'available',
        'user_id',
        'cert_num',
    ];

    public function examUser()
    {
        return $this->belongsTo(ExamUser::class, 'exam_user_id');
    }
}