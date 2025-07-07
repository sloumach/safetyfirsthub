<?php

namespace App\Services;

use App\Models\ExamUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Repositories\Interfaces\CertificateRepositoryInterface;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CertificateManagement
{
    public function __construct(protected CertificateRepositoryInterface $certRepo) {}

    public function generate(int $examId): array
    {
        $userId = Auth::id();
        $examUser = ExamUser::with('exam.course')->where('exam_id', $examId)->where('user_id', $userId)->firstOrFail();

        if ($examUser->score < $examUser->exam->score) {
            abort(403, 'You have not passed this exam.');
        }

        $existing = $this->certRepo->getByExamUserId($examUser->id);

        if ($existing) {
            if (!$existing->available) {
                abort(403, 'This certificate has expired.');
            }

            return $this->formatCertificateData($existing, $examUser);
        }

        $certificate = $this->certRepo->create([
            'exam_user_id'    => $examUser->id,
            'certificate_url' => Str::uuid(),
            'available'       => true,
            'user_id'         => $userId,
            'cert_num'        => $this->certRepo->getNextCertNumber(),
        ]);

        return $this->formatCertificateData($certificate, $examUser);
    }

    protected function formatCertificateData($certificate, $examUser): array
    {
        $user = $examUser->user;
        $course = $examUser->exam->course;
        $url = route('certificates.view', $certificate->certificate_url);
        $qr = base64_encode(QrCode::format('svg')->size(200)->generate(route('certificates.scan', $certificate->certificate_url)));

        return [
            'message'        => $certificate->wasRecentlyCreated ? 'Certificate generated successfully.' : 'Certificate already exists.',
            'user_firstname' => $user->firstname,
            'user_lastname'  => $user->lastname,
            'course_name'    => $course->name ?? 'Unknown Course',
            'cert_num'       => $certificate->cert_num,
            'certificate'    => [
                'url'     => $url,
                'qr_code' => $qr,
            ],
        ];
    }
}
