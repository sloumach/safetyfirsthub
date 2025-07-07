<?php

namespace App\Http\Controllers;

use App\Models\SectionQuiz;
use Illuminate\Http\Request;
use App\Models\SectionQuizChoice;
use Illuminate\Support\Facades\DB;
use App\Models\SectionQuizQuestion;
use App\Http\Controllers\Controller;
use App\Services\AdminQuizQuestionsManagement;


class AdminQuizQuestionController extends Controller
{

    protected AdminQuizQuestionsManagement $questionService;

    public function __construct(AdminQuizQuestionsManagement $questionService)
    {
        $this->questionService = $questionService;
    }

    public function index($quiz_id)
    {
        $quiz  = SectionQuiz::with('questions.choices')->findOrFail($quiz_id);

        return view('adminpanel.quizzes.questions.index', compact('quiz'));
    }

    public function create($quiz_id)
    {
        $quiz = SectionQuiz::findOrFail($quiz_id);
        return view('adminpanel.quizzes.questions.create', compact('quiz'));
    }

    public function store(Request $request, $quizId)
    {
        $data = $request->validate([
            'text' => 'required|string|max:500',
            'choices' => 'required|array|min:4',
            'choices.*' => 'required|string|max:255',
            'correct_choice' => 'required|integer|min:1|max:4',
        ]);

        try {
            $this->questionService->store($quizId, $data['text'], $data['choices'], $data['correct_choice']);
            return redirect()->route('admin.questions.index', $quizId)->with('success', 'Question et choix ajoutés avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'ajout de la question et des choix.');
        }
    }

    public function edit($quiz_id, $question_id)
    {
        $quiz = SectionQuiz::findOrFail($quiz_id);
        $question = SectionQuizQuestion::with('choices')->findOrFail($question_id);


        return view('adminpanel.quizzes.questions.edit', compact('quiz', 'question'));
    }

    public function update(Request $request, $quizId, $questionId)
    {
        $data = $request->validate([
            'question_text' => 'required|string|max:500',
            'choices' => 'required|array|min:4',
            'choices.*' => 'required|string|max:255',
            'correct_choice' => 'required|exists:section_quiz_choices,id',
        ]);

        $this->questionService->update(
            $questionId,
            $data['question_text'],
            $data['choices'],
            $data['correct_choice']
        );

        return redirect()->route('admin.questions.index', $quizId)->with('success', 'Question mise à jour avec succès.');
    }

    public function destroy($quiz_id, $question_id)
    {
        SectionQuizQuestion::findOrFail($question_id)->delete();
        return redirect()->route('admin.questions.index', $quiz_id)->with('success', 'Question supprimée avec succès.');
    }
}

