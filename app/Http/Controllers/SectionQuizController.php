<?php

namespace App\Http\Controllers;

use App\Services\VideoProgressService;
use App\Services\HelperService;
use Illuminate\Http\Request;
use App\Models\SectionQuiz;
use App\Models\UserSectionAttempt;
use App\Models\VideoProgress;
use App\Services\SectionQuizManagement;
use App\Models\Section;

class SectionQuizController extends Controller
{

    public function __construct(protected SectionQuizManagement $quizService) {}


    public function submitQuizAnswers(Request $request, $sectionId)
    {
        $data = $request->validate([
            'quiz_id' => 'required|exists:section_quizzes,id',
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:section_quiz_questions,id',
            'answers.*.selected_choice_id' => 'nullable|exists:section_quiz_choices,id',
        ]);

        try {
            $result = $quizService->submit($sectionId, $data['quiz_id'], $data['answers']);
            return response()->json($result, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

}
