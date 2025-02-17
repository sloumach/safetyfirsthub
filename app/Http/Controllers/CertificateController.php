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
    public function generateCertificate($exam_user_id)
    {
        $examUser = ExamUser::findOrFail($exam_user_id);

        // Vérifier si l'utilisateur a réussi l'examen
        if ($examUser->score < $examUser->exam->passing_score) {
            return response()->json(['error' => 'You have not passed this exam.'], 403);
        }

        // Vérifier si un certificat existe déjà et s'il est encore valide
        $certificate = Certificate::where('exam_user_id', $examUser->id)->first();

        if ($certificate) {
            if (!$certificate->available) {
                return response()->json(['error' => 'This certificate has expired.'], 403);
            }
        } else {
            // Générer une URL unique pour le certificat
            $certificate_url = Str::uuid()->toString();

            // Créer le certificat
            $certificate = Certificate::create([
                'exam_user_id'   => $examUser->id,
                'certificate_url'=> $certificate_url,
                'available'      => true, // Valide par défaut
            ]);
        }

        return response()->json([
            'message'     => 'Certificate generated successfully.',
            'certificate' => [
                'url'      => route('certificates.scan', $certificate->certificate_url), // 📌 Nouveau lien !
                'qr_code'  => QrCode::size(200)->generate(route('certificates.scan', $certificate->certificate_url)),
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
        return redirect()->route('certificates.view', ['certificate_url' => $certificate_url]);
    }

    /**
     * Afficher un certificat (uniquement via QR code)
     */
    public function viewCertificate($certificate_url, Request $request)
    {
        $certificate = Certificate::where('certificate_url', $certificate_url)
            ->where('available', true)
            ->firstOrFail();

        // Vérifier si l'accès est autorisé (QR Code uniquement)
        if (!$request->session()->has("certificate_access_{$certificate_url}")) {
            abort(404);
        }

        // ❌ Supprimer la session après affichage (Empêche réutilisation)
        $request->session()->forget("certificate_access_{$certificate_url}");

        return view('certificates.show', compact('certificate'));
    }
}
