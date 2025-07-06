<?php

namespace App\Repositories;

use App\Models\Certificate;
use App\Repositories\Interfaces\CertificateRepositoryInterface;

class CertificateRepository implements CertificateRepositoryInterface
{
    public function getByExamUserId(int $examUserId)
    {
        return Certificate::where('exam_user_id', $examUserId)->first();
    }

    public function create(array $data)
    {
        return Certificate::create($data);
    }

    public function getNextCertNumber(): int
    {
        $last = Certificate::max('cert_num');
        return $last ? $last + 1 : 1;
    }
}

