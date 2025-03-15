<?php

namespace App\Http\Controllers;

use App\Services\VideoProgressService;
use App\Services\HelperService;
use Illuminate\Http\Request;
use App\Models\SectionQuiz;
use App\Models\UserSectionAttempt;
use App\Models\VideoProgress;
use App\Models\Section;

class SectionQuizController extends Controller
{

    public function submitQuizAnswers(Request $request, $sectionId)
    {
        $request->validate([
            'quiz_id' => 'required|exists:section_quizzes,id',
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:section_quiz_questions,id',
            'answers.*.selected_choice_id' => 'nullable|exists:section_quiz_choices,id',
        ]);
    
        $user = auth()->user();
        $quiz = SectionQuiz::where('id', $request->quiz_id)
            ->where('section_id', $sectionId)
            ->firstOrFail();
    
        $questions = $quiz->questions()->with('choices')->get();
        $totalQuestions = $questions->count();
    
        if ($totalQuestions === 0) {
            return response()->json(['error' => 'Quiz has no questions.'], 400);
        }
    
        $correctAnswers = 0;
    
        foreach ($request->answers as $answer) {
            $question = $questions->firstWhere('id', $answer['question_id']);
            if (!$question) continue;
    
            // Vérifier si la réponse sélectionnée est correcte
            $correctChoice = $question->choices->where('is_correct', true)->first();
    
            if ($correctChoice && $correctChoice->id == $answer['selected_choice_id']) {
                $correctAnswers++;
            }
        }
    
        // Calculer le score
        $score = round(($correctAnswers / $totalQuestions) * 100);
        
        $passed = $score >= $quiz->passing_score;
  
    
        // ✅ Enregistrer la tentative de quiz
        UserSectionAttempt::create([
            'user_id' => $user->id,
            'section_id' => $sectionId,
            'quiz_id' => $quiz->id,
            'score' => $score,
            'passed' => $passed,
        ]);
    
        if ($passed) {
            // 🔓 Débloquer la section suivante (si existe)
            return response()->json([
                'message' => 'Quiz passed! Section unlocked.',
                'score' => $score,
                'passed' => $passed,
            ]);
        } else {
            // ❌ Échec → Remettre les vidéos comme "non terminées"
            VideoProgress::where('user_id', $user->id)
                ->where('section_id', $sectionId)
                ->update(['is_completed' => false]);
            return response()->json([
                'message' => 'Quiz failed. Try again.',
                'score' => $score,
                'passed' => $passed,
            ]);
        }
    
    }
    
}
