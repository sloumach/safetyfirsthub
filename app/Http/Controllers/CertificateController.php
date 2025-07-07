<?php

namespace App\Http\Controllers;

use App\Models\ExamUser;
use App\Models\Certificate;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Services\CertificateManagement;



class CertificateController extends Controller
{
    public function __construct(protected CertificateManagement $certificateService) {}


    /**
     * Générer un certificat après la réussite d'un examen
     */

    public function generateCertificate($examId)
    {
        $data = $this->certificateService->generate($examId);
        return response()->json($data);
    }

    /**
     * 🔹 Nouvelle route intermédiaire après scan du QR Code
     */
    public function scanCertificate($certificate_url, Request $request)
    {

        $certificate = Certificate::where('certificate_url', $certificate_url)
            ->where('available', true)
            ->firstOrFail();
        // Ajouter une session spécifique au certificat
        $request->session()->put("certificate_access_{$certificate_url}", true);

        // Rediriger vers la page de visualisation du certificat
        return redirect()->route('certificates.view', [
            'certificate_url' => $certificate_url,
            /* 'cert_num' => $certificate->cert_num, */
        ]);

    }

    /**
     * Afficher un certificat (uniquement via QR code)
     */
    public function viewCertificate($certificate_url, Request $request)    {
        $certificate = Certificate::where('certificate_url', $certificate_url)
            ->where('available', true)
            ->with(['examUser.user', 'examUser.exam.course'])
            ->firstOrFail();

        if (!$request->session()->has("certificate_access_{$certificate_url}")) {
            abort(404);
        }

        // Generate QR code
        $qrCode = base64_encode(QrCode::format('svg')
            ->size(200)
            ->generate(route('certificates.scan', $certificate->certificate_url)));

        $data = [
            'cert_num'  => $certificate->cert_num,
            'firstname' => $certificate->examUser->user->firstname,
            'lastname' => $certificate->examUser->user->lastname,
            'course_name' => $certificate->examUser->exam->course->name,
            'duration' => $certificate->examUser->exam->duration ?? '2',
            'date' => $certificate->created_at->format('d/m/Y'),
            'qrCode' => $qrCode // Add QR code to the data
        ];

        $request->session()->forget("certificate_access_{$certificate_url}");

        return view('certificates.show', $data);
    }
}
