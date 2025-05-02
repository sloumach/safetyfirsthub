<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Certificate;
use App\Models\ExamUser;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class CertificateController extends Controller
{
    /**
     * Générer un certificat après la réussite d'un examen
     */
    public function generateCertificate($exam_id)
    {
        // Find the exam_user record for the current user and exam
        $examUser = ExamUser::where('exam_id', $exam_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Vérifier si l'utilisateur a réussi l'examen
        if ($examUser->score < $examUser->exam->score) {
            return response()->json(['error' => 'You have not passed this exam.'], 403);
        }

        // Récupérer l'utilisateur connecté
        $user = auth()->user();

        // Récupérer le nom du cours associé à l'examen
        $courseName = $examUser->exam->course->name ?? 'Unknown Course';

        // Vérifier si un certificat existe déjà pour cet examen
        $certificate = Certificate::where('exam_user_id', $examUser->id)->first();

        if ($certificate) {
            if (!$certificate->available) {
                return response()->json(['error' => 'This certificate has expired.'], 403);
            }

            // 🔹 Si le certificat est valide, on retourne directement ses informations
            return response()->json([
                'message'        => 'Certificate already exists.',
                'user_firstname' => $user->firstname,
                'user_lastname'  => $user->lastname,
                'course_name'    => $courseName,
                'cert_num'  => $certificate->cert_num,
                'certificate'    => [
                    'url'       => route('certificates.view', $certificate->certificate_url),
                    'qr_code'   => base64_encode(QrCode::format('svg')->size(200)->generate(route('certificates.scan', $certificate->certificate_url))),

                ],
            ]);

        }

        // 🔹 Générer une URL unique pour le certificat
        $certificate_url = Str::uuid()->toString();
        // 🔹 Calcul du prochain numéro de certificat
        $lastCertNum = Certificate::max('cert_num');
        $nextCertNum = $lastCertNum ? $lastCertNum + 1 : 1;

        // 🔹 Créer un nouveau certificat
        $certificate = Certificate::create([
            'exam_user_id'    => $examUser->id,
            'certificate_url' => $certificate_url,
            'available'       => true, // Valide par défaut
            'user_id'         => $examUser->user_id,
            'cert_num'        => $nextCertNum,
        ]);

        return response()->json([
            'message'        => 'Certificate generated successfully.',
            'user_firstname' => $user->firstname,
            'user_lastname'  => $user->lastname,
            'course_name'    => $courseName,
            'cert_num'  => $certificate->cert_num,
            'certificate'    => [
                'url'      => route('certificates.view', $certificate->certificate_url),
                'qr_code'  => base64_encode(QrCode::format('svg')->size(200)->generate(route('certificates.scan', $certificate->certificate_url))),
            ],
        ]);
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
