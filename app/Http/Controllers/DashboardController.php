<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Video;
use App\Models\Course;
use App\Models\Section;
use App\Models\ExamUser;
use App\Models\VideoProgress;
use App\Models\UserSectionAttempt;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use App\Services\DashboardExamManagement;
use App\Services\DashboardCourseManagement;
use App\Services\DashboardCourseSectionManagement;




class DashboardController extends Controller
{

    public function __construct(
        protected DashboardCourseManagement $dashboardService,
        protected DashboardCourseSectionManagement $sectionService,
        protected DashboardExamManagement $examService
        ) {}

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
            $courses = $this->dashboardService->getUserCourses();
            return response()->json($courses, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getCourseSections($course_id)
    {
        try {
            $sections = $this->sectionService->getCourseSections($course_id);
            return response()->json(['sections' => $sections], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getVideoUrl($video_id)
    {
        $user = auth()->user();

        // 📌 Vérifier si la vidéo existe et récupérer sa section et son cours
        $video   = Video::with('section.course')->where('id', $video_id)->firstOrFail();
        $section = $video->section;
        $course  = $section->course;

        // 📌 Vérifier si l'utilisateur a bien accès au cours de cette vidéo
        if (! $user->courses->contains($course->id)) {
            return response()->json(['error' => 'Unauthorized access to this course.'], 403);
        }

        // 📌 Vérifier si le fichier vidéo existe
        if (! Storage::disk('private')->exists($video->video_path)) {
            return response()->json(['error' => 'Video file not found.'], 404);
        }

        // 📌 Générer une URL temporaire pour cette vidéo
        $signedUrl = URL::temporarySignedRoute(
            'section.video.stream',
            now()->addMinutes(60),
            ['video_id' => $video_id]// 📌 L'ID de la vidéo est maintenant utilisé
        );
        return response()->json(['video_url' => $signedUrl,'section_id'=>$section->id]);
    }

    public function getCourse($id)
    {
        try {
            $user = auth()->user();

            // Vérifier si l'utilisateur a acheté ce cours
            $course = $user->courses()->where('course_id', $id)->first();

            if (! $course) {
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
                'category'     => $course->category,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Course not found'], 404);
        }
    }

    public function streamVideo($video_id)
    {
        $user = auth()->user();

        // 📌 Vérifier si la vidéo existe et appartient à une section d'un cours
        $video = Video::with('section.course')->where('id', $video_id)->firstOrFail();
        $section = $video->section;
        $course = $section->course;

        // 📌 Vérifier si l'utilisateur a accès au cours
        if (!$user->courses->contains($course->id)) {
            abort(403, 'Unauthorized access to this course.');
        }

        // 📌 Vérifier si le fichier vidéo existe
        if (!Storage::disk('private')->exists($video->video_path)) {
            abort(404, 'Video file not found.');
        }

        // 📌 Streaming sécurisé
        return response()->stream(function () use ($video) {
            $stream = Storage::disk('private')->readStream($video->video_path);
            if (!$stream) {
                abort(500, 'Error opening video file.');
            }
            fpassthru($stream);
            fclose($stream);
        }, 200, [
            'Content-Type'        => 'video/mp4',
            'Content-Disposition' => 'inline', // ⚠️ Assure la lecture en streaming
            'Accept-Ranges'       => 'bytes', // 📌 Permet la lecture progressive
            'Cache-Control'       => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma'              => 'no-cache',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function serveCover($filename)
    {
        // Vérifier la signature de l'URL
        if (! request()->hasValidSignature()) {
            abort(403, 'Invalid or expired URL');
        }

        $path = "courses/covers/{$filename}";

        // Vérifier si l'image existe
        if (! Storage::disk('public')->exists($path)) {
            abort(404, 'Cover not found');
        }

        // Retourner l'image
        return response()->file(Storage::disk('public')->path($path));
    }

    public function getPassedExams()
    {
        try {
            $exams = $this->examService->getPassedExams();
            return response()->json($exams, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch passed exams'], 500);
        }
    }

        private function getcoverurl($filename)
    {
        return URL::temporarySignedRoute('cover.access', now()->addMinutes(30), ['filename' => $filename]);
    }

}
