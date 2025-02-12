<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
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
            $user    = auth()->user();
            $courses = $user->courses->map(function ($course) {
                return [
                    'id'           => $course->id,
                    'name'         => $course->name,
                    'description'  => $course->description,
                    'cover'        => $course->cover ? asset('storage/' . $course->cover) : null,
                    'total_videos' => $course->total_videos,
                    'students'     => $course->students ?? 0,
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
            $user = auth()->user(); // Récupérer l'utilisateur connecté

            // Vérifier si l'utilisateur a acheté ce cours
            $course = $user->courses()->where('course_id', $id)->first();

            if (! $course) {
                return response()->json(['error' => 'You do not have access to this course'], 403);
            }

            $coverUrl = $course->cover ? asset('storage/' . $course->cover) : null;

            return response()->json([
                'id'           => $course->id,
                'name'         => $course->name,
                'description'  => $course->description,
                'cover'        => $coverUrl,
                'total_videos' => $course->total_videos,
                'students'     => $course->students ?? 0,
                'videoUrl'     => $coverUrl, // Mettre l'URL de la vidéo ici
                'instructor'   => 'John Doe',
                'price'        => 'Free',
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

    public function streamVideo(Course $course)
    {
        $user = auth()->user();

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

}
