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
     * Afficher les examens disponibles pour l'utilisateur.
     */

    public function availableExams()
{
    $user = Auth::user();


    // Vérifier si l'utilisateur a acheté ce cours
    if (!$user->courses->contains(2)) {
        abort(403, 'You do not have access to this course.');
    }

    // Récupérer les examens actifs pour ce cours
    $exams = Exam::where('course_id', 1)
                 ->where('is_active', true)
                 ->get();

    // Vérifier s'il y a des examens disponibles
    if ($exams->isEmpty()) {
        return redirect()->back()->with('error', 'No exams available for this course.');
    }

    // Sélectionner un examen au hasard
    $exam = $exams->random();

    // Charger les questions avec leurs choix
    $exam->load('questions.choices');
    dd($exam); //arret ici

    return view('exams.start', compact('exam'));
}



    /**
     * Lancer un examen pour l'utilisateur.
     */
    public function startExam($exam_id)
    {
        $user = Auth::user();
        $exam = Exam::findOrFail($exam_id);

        // Vérifier si l'utilisateur a déjà commencé cet examen
        $examUser = ExamUser::firstOrCreate(
            ['user_id' => $user->id, 'exam_id' => $exam_id],
            ['status' => 'in_progress', 'started_at' => now()]
        );

        return redirect()->route('user.exams.show', $exam->id);
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
    public function submitExam(Request $request, $exam_id)
    {
        $user = Auth::user();
        $exam = Exam::findOrFail($exam_id);
        $questions = $exam->questions;

        $validatedData = $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'exists:choices,id',
        ]);

        DB::beginTransaction();
        try {
            $correctAnswers = 0;
            foreach ($questions as $question) {
                $choice_id = $validatedData['answers'][$question->id] ?? null;

                if ($choice_id) {
                    $is_correct = $question->choices()->where('id', $choice_id)->where('is_correct', true)->exists();
                    if ($is_correct) {
                        $correctAnswers++;
                    }

                    UserAnswer::create([
                        'user_id' => $user->id,
                        'exam_id' => $exam->id,
                        'question_id' => $question->id,
                        'choice_id' => $choice_id,
                        'is_correct' => $is_correct,
                    ]);
                }
            }

            $score = round(($correctAnswers / $questions->count()) * 100);
            $status = $score >= $exam->passing_score ? 'completed' : 'failed';

            ExamUser::where('user_id', $user->id)
                ->where('exam_id', $exam->id)
                ->update(['score' => $score, 'status' => $status, 'completed_at' => now()]);

            DB::commit();

            return redirect()->route('user.exams.results', $exam->id)->with('success', 'Examen terminé.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Une erreur s\'est produite.');
        }
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
}

