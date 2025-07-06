<?php

namespace App\Repositories\Interfaces;

interface CertificateRepositoryInterface
{
    public function getByExamUserId(int $examUserId);
    public function create(array $data);
    public function getNextCertNumber(): int;
}

