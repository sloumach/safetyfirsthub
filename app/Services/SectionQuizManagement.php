<?php

namespace App\Services;

use App\Models\SectionQuiz;
use App\Models\UserSectionAttempt;
use App\Models\VideoProgress;
use Illuminate\Support\Facades\Auth;

class SectionQuizManagement
{
    public function submit(int $sectionId, int $quizId, array $answers): array
    {
        $user = Auth::user();

        $quiz = SectionQuiz::where('id', $quizId)
            ->where('section_id', $sectionId)
            ->firstOrFail();

        $questions = $quiz->questions()->with('choices')->get();
        $totalQuestions = $questions->count();

        if ($totalQuestions === 0) {
            throw new \Exception('Quiz has no questions.');
        }

        $correctAnswers = 0;

        foreach ($answers as $answer) {
            $question = $questions->firstWhere('id', $answer['question_id']);
            if (!$question) continue;

            $correctChoice = $question->choices->where('is_correct', true)->first();
            if ($correctChoice && $correctChoice->id == $answer['selected_choice_id']) {
                $correctAnswers++;
            }
        }

        $score = round(($correctAnswers / $totalQuestions) * 100);
        $passed = $score >= $quiz->passing_score;

        UserSectionAttempt::create([
            'user_id'    => $user->id,
            'section_id' => $sectionId,
            'quiz_id'    => $quiz->id,
            'score'      => $score,
            'passed'     => $passed,
        ]);

        if (! $passed) {
            VideoProgress::where('user_id', $user->id)
                ->where('section_id', $sectionId)
                ->update(['is_completed' => false]);
        }

        return [
            'score'  => $score,
            'passed' => $passed,
            'message' => $passed
                ? 'Quiz passed! Section unlocked.'
                : 'Quiz failed. Try again.',
        ];
    }
}
