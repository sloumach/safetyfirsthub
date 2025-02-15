<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamUser;
use App\Models\Question;
use App\Models\UserAnswer;
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
            return response()->json(['error' => 'No exams available for this course'], 404);
        }

        // Vérifier si l'utilisateur a déjà terminé cet examen
        $existingSession = ExamUser::where('user_id', $user->id)
                                   ->where('exam_id', $exam->id)
                                   ->where('status', 'completed')
                                   ->first();

        if ($existingSession) {
            return response()->json(['error' => 'You have already completed this exam.'], 403);
        }

        // Créer une session d'examen s'il n'en a pas encore
        $examSession = ExamUser::firstOrCreate(
            ['user_id' => $user->id, 'exam_id' => $exam->id, 'status' => 'in_progress'],
            ['started_at' => now()]
        );

        return response()->json([
            'session_id' => $examSession->id,
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

         $validatedData = $request->validate([
             'question_id' => 'required|exists:questions,id',
             'choice_id' => 'required|exists:choices,id',
         ]);

         // Stocker la réponse de l'utilisateur
         $userAnswers = json_decode($examSession->user_answers, true) ?? [];
         $userAnswers[$validatedData['question_id']] = $validatedData['choice_id'];

         // Vérifier si la réponse est correcte
         $isCorrect = Choice::where('id', $validatedData['choice_id'])->where('is_correct', true)->exists();

         // Sauvegarde la réponse
         UserAnswer::updateOrCreate(
             [
                 'user_id' => $examSession->user_id,
                 'exam_id' => $examSession->exam_id,
                 'question_id' => $validatedData['question_id']
             ],
             [
                 'choice_id' => $validatedData['choice_id'],
                 'is_correct' => $isCorrect
             ]
         );

         // Vérifier si c'était la dernière question
         $totalQuestions = Question::where('exam_id', $examSession->exam_id)->count();
         $answeredQuestions = count($userAnswers);

         if ($answeredQuestions >= $totalQuestions) {
             // ✅ 1. Calculer le score final
             $correctAnswers = UserAnswer::where('exam_id', $examSession->exam_id)
                                         ->where('user_id', $examSession->user_id)
                                         ->where('is_correct', true)
                                         ->count();

             $score = round(($correctAnswers / $totalQuestions) * 100);

             // ✅ 2. Déterminer si l'examen est réussi ou échoué
             $status = $score >= $examSession->exam->passing_score ? 'passed' : 'failed';

             // ✅ 3. Mettre à jour la session d'examen
             $examSession->update([
                 'status' => 'completed',
                 'score' => $score,
                 'completed_at' => now(),
             ]);

             return response()->json([
                 'message' => 'Exam completed.',
                 'exam_completed' => true,
                 'score' => $score,
                 'status' => $status,
             ]);
         }

         return response()->json([
             'message' => 'Answer saved',
             'next_question' => route('exam.question', ['session_id' => $examSession->id])
         ]);
     }



    /**
     * Afficher le résultat d'un examen.
     */
    public function examResults($exam_id)
    {
        $user = Auth::user();
        $examUser = ExamUser::where('user_id', $user->id)->where('exam_id', $exam_id)->firstOrFail();

        return view('exams.results', compact('examUser'));
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

