<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamUser;
use App\Models\Question;
use App\Models\UserAnswer;
use App\Models\Choice;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserExamsController extends Controller
{


    /**
     * Lancer un examen pour l'utilisateur.
     */
    public function startExam($course_id)
{
    $user = Auth::user();

    // Vérifier si l'utilisateur a acheté ce cours
    if (!$user->courses->contains($course_id)) {
        return response()->json(['error' => 'You do not have access to this course'], 403);
    }

    // Sélectionner un examen actif pour ce cours
    $exam = Exam::where('course_id', $course_id)
                ->where('is_active', true)
                ->inRandomOrder()
                ->first();

    if (!$exam) {
        dd('No exams available for this course');
        return response()->json(['error' => 'No exams available for this course'], 404);
    }

    // Vérifier si l'utilisateur a déjà une session pour cet examen (toute session existante)
    $examSession = ExamUser::where('user_id', $user->id)
                           ->where('exam_id', $exam->id)
                           ->first();

    // Si l'utilisateur a déjà terminé cet examen, on empêche de recommencer
    if ($examSession && $examSession->status === 'completed') {
        return response()->json(['error' => 'You have already completed this exam.'], 403);
    }

    // Si l'utilisateur n'a PAS encore de session active, on en crée une nouvelle
    if (!$examSession) {
        $examSession = DB::table('exam_users')->insertGetId([
            'user_id' => $user->id,
            'exam_id' => $exam->id,
            'status' => 'in_progress',
            'started_at' => now(),
            'created_at' => now(), // Si timestamps sont activés
            'updated_at' => now(),
        ]);
    }

    return response()->json([
        'session_id' => $examSession,
        'exam_id' => $exam->id,
        'title' => $exam->title,
        'description' => $exam->description,
        'duration' => $exam->duration,
        'passing_score' => $exam->passing_score
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

        if (!$examUser) {
            return redirect()->route('user.exams.available')->with('error', 'Vous ne pouvez pas accéder à cet examen.');
        }

        return view('exams.show', compact('exam'));
    }

    /**
     * Enregistrer les réponses de l'utilisateur.
     */


     public function submitAnswer(Request $request, $session_id)
{
    $examSession = ExamUser::findOrFail($session_id);

    if ($examSession->status === 'completed') {
        return response()->json(['error' => 'Exam is already completed'], 403);
    }

    // Autoriser `choice_id` à être null
    $validatedData = $request->validate([
        'question_id' => 'required|exists:questions,id',
        'choice_id' => 'nullable|exists:choices,id', // ✅ Autorise `null`
    ]);

    // Si `choice_id` est `null`, la réponse est fausse
    $is_correct = false;
    if ($validatedData['choice_id']) {
        $is_correct = Choice::where('id', $validatedData['choice_id'])->where('is_correct', true)->exists();
    }

    // Enregistrer la réponse de l'utilisateur
    UserAnswer::create([
        'user_id' => auth()->id(),
        'exam_id' => $examSession->exam_id,
        'question_id' => $validatedData['question_id'],
        'choice_id' => $validatedData['choice_id'],
        'is_correct' => $is_correct,
    ]);

    // Passer à la question suivante
    return response()->json(['message' => 'Answer saved']);
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
        'message' => 'Exam completed successfully.',
        'score' => $examUser->score,
        'status' => $examUser->score >= $examUser->exam->passing_score ? 'passed' : 'failed',
        'passing_score' => $examUser->exam->passing_score,
    ]);
}


    /**
     * Afficher les examens déjà passés par l'utilisateur.
     */
    public function userExamHistory()
    {
        $user = Auth::user();
        $exams = ExamUser::where('user_id', $user->id)->with('exam')->get();

        return view('exams.history', compact('exams'));
    }
    public function getNextQuestion($exam_session_id)
    {
        $user = Auth::user();

        // Vérifier si l'utilisateur a bien une session d'examen active
        $examUser = ExamUser::where('id', $exam_session_id)
                            ->where('user_id', $user->id)
                            ->where('status', 'in_progress')
                            ->firstOrFail();

        // Récupérer l'examen associé
        $exam = Exam::findOrFail($examUser->exam_id);

        // Récupérer les questions auxquelles l'utilisateur n'a pas encore répondu
        $answeredQuestions = UserAnswer::where('user_id', $user->id)
                                       ->where('exam_id', $exam->id)
                                       ->pluck('question_id');

        $nextQuestion = $exam->questions()
                             ->whereNotIn('id', $answeredQuestions) // Exclure les questions déjà répondues
                             ->inRandomOrder()
                             ->first();

        // Vérifier si toutes les questions ont été répondues
        if (!$nextQuestion) {
            $examUser->status = 'completed';
            $examUser->save();
            return response()->json([
                'message' => 'Exam completed. Redirecting to results.',
                'exam_completed' => true
            ]);
        }

        return response()->json([
            'question' => [
                'id' => $nextQuestion->id,
                'text' => $nextQuestion->question_text,
                'choices' => $nextQuestion->choices->map(function ($choice) {
                    return [
                        'id' => $choice->id,
                        'text' => $choice->choice_text,
                    ];
                }),
            ],
            'exam_completed' => false
        ]);
    }




}

