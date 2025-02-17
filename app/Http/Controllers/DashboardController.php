<?php
namespace App\Http\Controllers;
use App\Models\Course;
use App\Models\ExamUser;


use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

use Symfony\Component\HttpFoundation\StreamedResponse;


class DashboardController extends Controller
{
    public function index()
    {
        $user    = auth()->user();
        $courses = $user->courses->map(function ($course) {
            return [
                'id'          => $course->id,
                'title'       => $course->name,
                'description' => $course->description,
                'image'       => $course->cover,
                'duration'    => $course->total_videos . ' videos',
                'students'    => $course->students ?? 0,
            ];
        });

        return view('userdashboard.dashboard-course', compact('courses'));
    }
    public function getCourses()
    {
        try {
            $user = auth()->user();
            
            $courses = $user->courses->map(function ($course) use ($user) {
                $coverUrl = $course->cover ? $this->getcoverurl(basename($course->cover)) : null;
    
                // 🔹 Vérifier si l'utilisateur a réussi au moins un examen pour ce cours
                $examcheck = ExamUser::where('user_id', $user->id)
                    ->whereHas('exam', function ($query) use ($course) {
                        $query->where('course_id', $course->id);
                    })
                    ->whereColumn('score', '>=', 'exams.passing_score') // ✅ Vérifier si le score est suffisant
                    ->join('exams', 'exam_users.exam_id', '=', 'exams.id') // ✅ Joindre pour accéder à `passing_score`
                    ->exists();
    
                return [
                    'id'           => $course->id,
                    'name'         => $course->name,
                    'description'  => $course->description,
                    'cover'        => $coverUrl,
                    'total_videos' => $course->total_videos,
                    'students'     => $course->students ?? 0,
                    'examcheck'    => $examcheck, // ✅ Vérification basée sur le score minimum requis
                ];
            });
    
            return response()->json($courses, 200);
    
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch courses'], 500);
        }
    }
    

    public function getCourse($id)
{
    try {
        $user = auth()->user();

        // Vérifier si l'utilisateur a acheté ce cours
        $course = $user->courses()->where('course_id', $id)->first();
        
        if (!$course) {
            return response()->json(['error' => 'You do not have access to this course'], 403);
        }

        // Récupérer l'URL signée du cover
        $coverUrl = $course->cover ? $this->getcoverurl(basename($course->cover)) : null;

        return response()->json([
            'id'           => $course->id,
            'name'         => $course->name,
            'description'  => $course->description,
            'cover'        => $coverUrl,
            'total_videos' => $course->total_videos,
            'students'     => $course->students ?? 0,
            'instructor'   => 'John Doe',
            'price'        => 'Free',
            'email'        => $user->email,
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Course not found'], 404);
    }
}


    public function getVideo($id){
        try {


        } catch (\Exception $th) {


        }
    }

    public function streamVideo($id)
    {
        $user = auth()->user();
        
        $course = $user->courses()->where('course_id', $id)->first();

        // Vérifier si l'utilisateur a acheté le cours
        if (!$user->courses->contains($course->id)) {
            
            abort(403, 'Unauthorized access to this course.');
        }

        // Vérifier si le fichier existe
        if (!Storage::disk('private')->exists($course->video)) {
            abort(404, 'Video not found.');
        }

            // Streaming sécurisé
        return response()->stream(function () use ($course) {
            $stream = Storage::disk('private')->readStream($course->video);

            if (!$stream) {
                abort(500, 'Error opening video file.');
            }

            fpassthru($stream);
            fclose($stream);
        }, 200, [
            'Content-Type' => 'video/mp4',
            'Content-Disposition' => 'inline', // Empêche le téléchargement avec "Save As"
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
        ]);
    }
    private function getcoverurl($filename)
    {
        return URL::temporarySignedRoute('cover.access', now()->addMinutes(30), ['filename' => $filename]);
    }
    

    public function serveCover($filename)
    {
        // Vérifier la signature de l'URL
        if (!request()->hasValidSignature()) {
            abort(403, 'Invalid or expired URL');
        }

        $path = "courses/covers/{$filename}";



        // Vérifier si l'image existe
        if (!Storage::disk('private')->exists($path)) {
            abort(404, 'Cover not found');
        }

        // Retourner l'image
        return response()->file(Storage::disk('private')->path($path));
    }



}
