<?php
namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Order;
use App\Models\Choice;
use App\Models\ExamUser;
use App\Models\Question;
use App\Models\UserAnswer;
use Illuminate\Http\Request;
use App\Models\VideoProgress;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserExamsController extends Controller
{

    /**
     * Lancer un examen pour l'utilisateur.
     */
    public function startExam($course_id)
    {
        $user = Auth::user();

        // Vérifier si l'utilisateur a regardé la vidéo avant de passer l'examen
        $progress = VideoProgress::where('user_id', $user->id)
            ->where('course_id', $course_id)
            ->first();

        if (! $progress || ! $progress->is_completed) {
            return response()->json(['error' => 'You must watch the course video before taking the exam.'], 403);
        }

        // Vérifier si l'utilisateur a acheté ce cours
        if (! $user->courses->contains($course_id)) {
            return response()->json(['error' => 'You do not have access to this course'], 403);
        }

        // Récupérer l'ID du dernier achat pour ce cours
        $order = Order::where('user_id', $user->id)
            ->whereHas('course', function ($query) use ($course_id) {
                $query->where('course_id', $course_id);
            })
            ->latest()
            ->first();

        if (! $order) {
            return response()->json(['error' => 'No valid purchase found for this course.'], 403);
        }

        // Sélectionner un examen actif pour ce cours
        $exam = Exam::where('course_id', $course_id)
            ->where('is_active', true)
            ->inRandomOrder()
            ->first();

        if (! $exam) {
            return response()->json(['error' => 'No exams available for this course'], 404);
        }

        // Vérifier combien de fois l'utilisateur a tenté cet examen avec le dernier achat
        $attempts = ExamUser::where('user_id', $user->id)
            ->where('order_id', $order->id) // Vérifier seulement les tentatives de CE paiement
            ->count();

        if ($attempts >= 3) {
            return response()->json(['error' => 'You have reached the maximum number of attempts (3) for this purchase.'], 403);
        }

        // Créer une nouvelle session d'examen pour cet utilisateur
        $sessionId = DB::table('exam_users')->insertGetId([
            'user_id'    => $user->id,
            'exam_id'    => $exam->id,
            'order_id'   => $order->id, // Associe l'examen à l'achat
            'status'     => 'in_progress',
            'started_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'session_id'    => $sessionId,
            'exam_id'       => $exam->id,
            'title'         => $exam->title,
            'description'   => $exam->description,
            'duration'      => $exam->duration,
            'passing_score' => $exam->passing_score,
        ]);
    }


    /**
     * Afficher les questions de l'examen.
     */
    public function showExam($exam_id)
    {
        $user = Auth::user();
        $exam = Exam::with('questions.choices')->findOrFail($exam_id);

        // Vérifier si l'utilisateur est bien inscrit à cet examen
        $examUser = ExamUser::where('user_id', $user->id)
            ->where('exam_id', $exam_id)
            ->where('status', 'in_progress')
            ->first();

        if (! $examUser) {
            return redirect()->route('user.exams.available')->with('error', 'Vous ne pouvez pas accéder à cet examen.');
        }

        return view('exams.show', compact('exam'));
    }

    /**
     * Enregistrer les réponses de l'utilisateur.
     */

    public function submitAnswer(Request $request, $session_id)
    {
        $user = Auth::user();

        // 🔹 Vérifier si la session d'examen est active
        $examUser = ExamUser::where('id', $session_id)
            ->where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->first();

        if (! $examUser) {
            return response()->json(['error' => 'No active exam session found.'], 403);
        }

        // 🔹 Valider la requête (le choix peut être null si le user ne répond pas)
        $validatedData = $request->validate([
            'question_id' => 'required|exists:questions,id',
            'choice_id'   => 'nullable|exists:choices,id',
        ]);

        // 🔹 Vérifier si cette question appartient bien à l'examen
        $question = Question::where('id', $validatedData['question_id'])
            ->where('exam_id', $examUser->exam_id)
            ->first();

        if (! $question) {
            return response()->json(['error' => 'Invalid question for this exam.'], 403);
        }

        // 🔹 Vérifier si l'utilisateur a déjà répondu à cette question dans cette tentative
        $existingAnswer = UserAnswer::where('exam_user_id', $examUser->id)
            ->where('question_id', $validatedData['question_id'])
            ->first();

        if ($existingAnswer) {
            return response()->json(['error' => 'You have already answered this question.'], 403);
        }

        // 🔹 Déterminer si la réponse est correcte
        $is_correct = false;
        if ($validatedData['choice_id']) {
            $is_correct = Choice::where('id', $validatedData['choice_id'])->where('is_correct', true)->exists();
        }

        // 🔹 Enregistrer la réponse avec `exam_user_id`
        UserAnswer::create([
            'user_id'      => $user->id,
            'exam_id'      => $examUser->exam_id,
            'exam_user_id' => $examUser->id, // ✅ Associer à la session de l'examen
            'question_id'  => $validatedData['question_id'],
            'choice_id'    => $validatedData['choice_id'],
            'is_correct'   => $is_correct,
        ]);

        return response()->json(['message' => 'Answer saved.']);
    }

    /**
     * Afficher le résultat d'un examen.
     */
    public function examResults($exam_session_id)
    {
        $user = Auth::user();

        // Vérifier si l'utilisateur a bien une session terminée pour cet examen
        $examUser = ExamUser::where('user_id', $user->id)
            ->where('id', $exam_session_id)
            ->where('status', 'completed')
            ->firstOrFail();

        return response()->json([
            'message'       => 'Exam completed successfully.',
            'score'         => $examUser->score,
            'status'        => $examUser->score >= $examUser->exam->passing_score ? 'passed' : 'failed',
            'passing_score' => $examUser->exam->passing_score,
        ]);
    }

    /**
     * Afficher les examens déjà passés par l'utilisateur.
     */
    public function userExamHistory()
    {
        $user  = Auth::user();
        $exams = ExamUser::where('user_id', $user->id)->with('exam')->get();

        return view('exams.history', compact('exams'));
    }

    public function getNextQuestion($exam_session_id)
    {
        $user = Auth::user();

        // Vérifier si l'utilisateur a une session active
        $examUser = ExamUser::where('id', $exam_session_id)
            ->where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->firstOrFail();

        // Récupérer l'examen associé
        $exam = Exam::findOrFail($examUser->exam_id);

        // 🔹 Récupérer les questions auxquelles l'utilisateur a répondu DANS CETTE SESSION
        $answeredQuestions = UserAnswer::where('user_id', $user->id)
            ->where('exam_id', $exam->id)
            ->where('exam_user_id', $examUser->id) // ✅ On filtre par session actuelle !
            ->pluck('question_id');

        // 🔹 Trouver la prochaine question non répondue dans CETTE SESSION
        $nextQuestion = $exam->questions()
            ->whereNotIn('id', $answeredQuestions)
            ->orderBy('id')
            ->first();

        // 🔹 Si toutes les questions ont été répondues → Fin de l'examen
        if (! $nextQuestion) {
            // 🔹 Calculer le score UNIQUEMENT pour cette session
            $totalQuestions = $exam->questions()->count();
            $correctAnswers = UserAnswer::where('user_id', $user->id)
                ->where('exam_id', $exam->id)
                ->where('exam_user_id', $examUser->id)
                ->where('is_correct', true)
                ->count();

            $score = round(($correctAnswers / $totalQuestions) * 100);

            // 🔹 Vérifier si le candidat a réussi ou échoué
            $status = $score >= $exam->passing_score ? 'passed' : 'failed';

            // 🔹 Marquer cette tentative comme complétée avec le score
            $examUser->update([
                'status'       => 'completed',
                'score'        => $score,
                'completed_at' => now(),
            ]);

            // If exam failed, reset video progress completion status
            if ($status === 'failed') {
                VideoProgress::where('user_id', $user->id)
                    ->where('course_id', $exam->course_id)
                    ->update(['is_completed' => false]);
            }

            // 🔹 Vérifier le nombre total de tentatives
            $attempts = ExamUser::where('user_id', $user->id)
                ->whereHas('exam', function ($query) use ($exam) {
                    $query->where('course_id', $exam->course_id);
                })
                ->count();

            if ($status === 'failed' && $attempts < 3) {
                return response()->json([
                    'message'        => "Exam failed. You have " . (3 - $attempts) . " attempts left.",
                    'exam_completed' => true,
                    'retry_allowed'  => true,
                    'attempts_left'  => 3 - $attempts,
                    'video_reset'    => true  // Added to inform frontend
                ]);
            }

            return response()->json([
                'message'        => 'Exam completed. Redirecting to results.',
                'exam_completed' => true,
                'score'         => $score,
                'status'        => $status,
                'retry_allowed' => false,
                'video_reset'   => ($status === 'failed')  // Added to inform frontend
            ]);
        }

        // 🔹 Retourner la prochaine question
        return response()->json([
            'question'       => [
                'id'      => $nextQuestion->id,
                'text'    => $nextQuestion->question_text,
                'choices' => $nextQuestion->choices->map(function ($choice) {
                    return [
                        'id'   => $choice->id,
                        'text' => $choice->choice_text,
                    ];
                }),
            ],
            'exam_completed' => false,
        ]);
    }

    public function markExamAsCompleted($session_id)
    {
        $examSession = ExamUser::where('id', $session_id)
            ->where('status', 'in_progress')
            ->first();

        if ($examSession) {
            $examSession->update([
                'status'       => 'completed',
                'completed_at' => now(),
            ]);
        }

        return response()->json(['message' => 'Exam session marked as completed.']);
    }

    public function updateProgress(Request $request)
    {
        $request->validate([
            'current_time'   => 'required|integer|min:0',
            'total_duration' => 'required|integer|min:1',
            'section_id'     => 'required|exists:sections,id',
            'is_completed'   => 'boolean'
        ]);

        $user = Auth::user();
        $section_id = $request->section_id;

        $progress = VideoProgress::firstOrCreate(
            ['user_id' => $user->id, 'section_id' => $section_id],
            ['total_duration' => $request->total_duration, 'watched_segments' => json_encode([])]
        );

        $segments = json_decode($progress->watched_segments, true) ?? [];
        $segments[] = $request->current_time;

        $isCompleted = $this->checkCompletion($segments, $request->total_duration);

        // Met à jour la progression de la vidéo
        $progress->update([
            'watched_segments' => json_encode($segments),
            'is_completed'     => $isCompleted,
        ]);

        // Vérifier si toutes les vidéos de la section sont terminées
        if ($isCompleted) {
            $this->checkSectionCompletion($user, $section_id);
        }

        return response()->json([
            'message' => 'Progress updated',
            'is_completed' => $progress->is_completed
        ]);
    }

    private function checkSectionCompletion($user, $section_id)
    {
        $section = Section::findOrFail($section_id);

        // Vérifier si toutes les vidéos de la section sont complétées
        $allCompleted = $section->videos->every(function ($video) use ($user) {
            return VideoProgress::where('user_id', $user->id)
                ->where('video_id', $video->id)
                ->where('is_completed', true)
                ->exists();
        });

        if ($allCompleted) {
            // Vérifier si toutes les sections du cours sont complétées
            $this->checkCourseCompletion($user, $section->course_id);
        }
    }

    private function checkCourseCompletion($user, $course_id)
    {
        $course = Course::findOrFail($course_id);

        $allSectionsCompleted = $course->sections->every(function ($section) use ($user) {
            return VideoProgress::where('user_id', $user->id)
                ->where('section_id', $section->id)
                ->where('is_completed', true)
                ->exists();
        });

        if ($allSectionsCompleted) {
            CourseProgress::updateOrCreate(
                ['user_id' => $user->id, 'course_id' => $course_id],
                ['is_completed' => true]
            );
        }
    }

    private function checkCompletion($segments, $total_duration)
    {
        sort($segments);
        $watched_time = count($segments) * 10; // Approximation de l'avancement par 10s
        $completion_threshold = $total_duration * 0.9; // 90% threshold
        return $watched_time >= $completion_threshold;
    }

    public function checkProgress($course_id)
    {
        $user = auth()->user();

        // Récupérer la progression de l'utilisateur sur ce cours
        $progress = VideoProgress::where('user_id', $user->id)
            ->where('course_id', $course_id)
            ->first();

        if (!$progress) {
            return response()->json([
                'watched' => false,
                'message' => 'No progress found for this course.'
            ], 404);
        }

        return response()->json([
            'watched' => $progress->is_completed,
            'progress' => $progress->watched_segments,
            'message' => $progress->is_completed ? 'Course video fully watched.' : 'Course video not fully watched yet.'
        ]);
    }

    public function markAsCompleted(Request $request)
    {
        $request->validate([
            'current_time'   => 'required|integer|min:0',
            'total_duration' => 'required|integer|min:1',
            'course_id'      => 'required|exists:courses,id',
        ]);

        $user = Auth::user();
        $course_id = $request->course_id;

        if (!$user->courses->contains($course_id)) {
            return response()->json(['error' => 'You do not have access to this course'], 403);
        }

        $progress = VideoProgress::firstOrCreate(
            ['user_id' => $user->id, 'course_id' => $course_id],
            ['total_duration' => $request->total_duration, 'watched_segments' => json_encode([])]
        );

        // Only mark as completed if we're at the end of the video
        if ($request->current_time >= ($request->total_duration - 1)) {
            $progress->update([
                'is_completed' => true,
                'watched_segments' => json_encode(array_unique(array_merge(
                    json_decode($progress->watched_segments, true) ?? [],
                    [$request->total_duration]
                )))
            ]);
        }

        return response()->json([
            'message' => 'Video progress updated',
            'is_completed' => $progress->is_completed
        ]);
    }

    public function resetProgress(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $user = Auth::user();
        $course_id = $request->course_id;

        if (!$user->courses->contains($course_id)) {
            return response()->json(['error' => 'You do not have access to this course'], 403);
        }

        $progress = VideoProgress::where('user_id', $user->id)
            ->where('course_id', $course_id)
            ->first();

        if ($progress) {
            $progress->update([
                'is_completed' => false,
                'watched_segments' => json_encode([])
            ]);
        }

        return response()->json([
            'message' => 'Progress reset successfully',
            'is_completed' => false
        ]);
    }

}
