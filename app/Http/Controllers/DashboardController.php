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

                // 🔹 Récupérer le dernier achat du cours
                $order = Order::where('user_id', $user->id)
                    ->whereHas('course', fn($query) => $query->where('course_id', $course->id))
                    ->latest()
                    ->first();

                if (! $order) {
                    return [
                        'id'           => $course->id,
                        'name'         => $course->name,
                        'description'  => $course->description,
                        'cover'        => $coverUrl,
                        'total_videos' => $course->total_videos,
                        'students'     => $course->students ?? 0,
                        'examcheck'    => false, // ❌ Aucun examen valide trouvé
                        'exam_id'      => null,
                        'short_description' => $course->short_description,
                    ];
                }
                $examcheck = ExamUser::query()
                    ->where('user_id', $user->id)
                    ->where('order_id', $order->id) // ✅ Vérifie uniquement les examens de CE paiement
                    ->whereHas('exam', fn($query) => $query->where('course_id', $course->id))
                    ->whereColumn('score', '>=', 'exams.passing_score') // ✅ Vérifie si la tentative est réussie
                    ->join('exams', 'exam_users.exam_id', '=', 'exams.id')
                    ->latest('exam_users.id') // ✅ Prend la dernière tentative réussie
                    ->value('exam_users.id'); // ✅ Récupère l'ID de la meilleure tentative

                return [
                    'id'           => $course->id,
                    'name'         => $course->name,
                    'description'  => $course->description,
                    'cover'        => $coverUrl,
                    'total_videos' => $course->total_videos,
                    'students'     => $course->students ?? 0,
                    'examcheck'    => (bool) $examcheck, // ✅ Vérification basée sur le score minimum requis
                    'exam_id'      => $examcheck,
                    'short_description' => $course->short_description,
                ];
            });

            return response()->json($courses, 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getmessage()], 500);
        }
    }

    public function getCourseSections($course_id)
    {
        $course = Course::with(['sections.videos', 'sections.quiz.questions.choices'])->findOrFail($course_id);
        $userId = auth()->id();

        return response()->json([
            'sections' => $course->sections->map(function ($section) use ($userId) {
                // Vérifier si l'utilisateur a réussi le quiz de cette section
                $userAttempt = \App\Models\UserSectionAttempt::where('user_id', $userId)
                    ->where('section_id', $section->id)
                    ->orderByDesc('created_at') // Dernière tentative en premier
                    ->first();

                return [
                    'id'     => $section->id,
                    'title'  => $section->title,
                    'videos' => $section->videos->map(function ($video) use ($section, $userId) {
                        return [
                            'id'           => $video->id,
                            'title'        => $video->title,
                            'duration'     => $video->duration,
                            'video_url'    => url("/sections/{$section->id}/video"),
                            'is_completed' => VideoProgress::where('user_id', $userId)
                                ->where('video_id', $video->id)
                                ->where('is_completed', true)
                                ->exists(),
                        ];
                    }),
                    'slides' => $section->slides->map(function ($slide) {
                        return [
                            'id'      => $slide->id,
                            'title'   => $slide->title,
                            'content' => $slide->content,
                        ];
                    }),
                    'quiz' => $section->quiz ? [
                        'id'             => $section->quiz->id,
                        'passing_score'  => $section->quiz->passing_score,
                        'is_attempted'   => $userAttempt !== null, // Si une tentative existe
                        'is_passed'      => $userAttempt ? $userAttempt->score >= $section->quiz->passing_score : false, // Vérifie si l'utilisateur a réussi
                        'score'          => $userAttempt->score ?? null, // Score obtenu
                        'questions'      => $section->quiz->questions->map(function ($question) {
                            return [
                                'id'      => $question->id,
                                'text'    => $question->question_text,
                                'choices' => $question->choices->map(function ($choice) {
                                    return [
                                        'id'   => $choice->id,
                                        'text' => $choice->choice_text,
                                    ];
                                }),
                            ];
                        }),
                    ] : null,
                ];
            }),
        ]);
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

    public function getVideo($id)
    {
        try {

        } catch (\Exception $th) {

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


    private function getcoverurl($filename)
    {
        return URL::temporarySignedRoute('cover.access', now()->addMinutes(30), ['filename' => $filename]);
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
            $user = auth()->user();

            // Récupérer les examens réussis par l'utilisateur
            $passedExams = ExamUser::where('user_id', $user->id)
                ->whereColumn('score', '>=', 'exams.passing_score')     // ✅ Vérifie si le score est suffisant
                ->join('exams', 'exam_users.exam_id', '=', 'exams.id')  // ✅ Joindre la table exams pour récupérer les détails
                ->join('courses', 'exams.course_id', '=', 'courses.id') // ✅ Joindre la table courses pour récupérer le nom du cours
                ->select(
                    'exam_users.id as exam_user_id',
                    'exams.id as exam_id',
                    'exams.title as exam_title',
                    'exams.passing_score',
                    'exam_users.score',
                    'exam_users.completed_at',
                    'courses.id as course_id',
                    'courses.name as course_name',
                    'courses.cover as course_cover'
                )
                ->get();

            // ✅ Ajouter l'URL du cover si disponible
            $passedExams->transform(function ($exam) {
                $exam->course_cover = $exam->course_cover ? $this->getcoverurl(basename($exam->course_cover)) : null;
                return $exam;
            });

            return response()->json($passedExams, 200);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch passed exams'], 500);
        }
    }

}
