<?php

namespace App\Http\Controllers;

use App\Models\SectionQuiz;
use Illuminate\Http\Request;
use App\Models\SectionQuizChoice;
use Illuminate\Support\Facades\DB;
use App\Models\SectionQuizQuestion;
use App\Http\Controllers\Controller;

class AdminQuizQuestionController extends Controller
{
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



    public function store(Request $request, $quiz_id)
    {
        $request->validate([
            'text' => 'required|string|max:500',
            'choices' => 'required|array|min:4', // Vérifie qu'on a bien 4 choix
            'choices.*' => 'required|string|max:255', // Chaque choix doit être un texte valide
            'correct_choice' => 'required|integer|min:1|max:4', // Doit être entre 1 et 4
        ]);

        DB::beginTransaction();

        try {
            // 🔹 Ajouter la question
            $question = SectionQuizQuestion::create([
                'quiz_id' => $quiz_id,
                'question_text' => $request->text,
            ]);

            // 🔹 Ajouter les choix
            foreach ($request->choices as $index => $choiceText) {
                SectionQuizChoice::create([
                    'question_id' => $question->id,
                    'choice_text' => $choiceText,
                    'is_correct' => ($index + 1 == $request->correct_choice), // Vérifie si c'est la bonne réponse
                ]);
            }

            DB::commit();

            return redirect()->route('admin.questions.index', $quiz_id)->with('success', 'Question et choix ajoutés avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de l\'ajout de la question et des choix.');
        }
    }


    public function edit($quiz_id, $question_id)
    {
        $quiz = SectionQuiz::findOrFail($quiz_id);
        $question = SectionQuizQuestion::with('choices')->findOrFail($question_id);


        return view('adminpanel.quizzes.questions.edit', compact('quiz', 'question'));
    }

    public function update(Request $request, $quiz_id, $question_id)
    {
        // ✅ Correction des noms de champs pour éviter les erreurs
        $request->validate([
            'question_text' => 'required|string|max:500', // Correction ici
            'choices' => 'required|array|min:4',
            'choices.*' => 'required|string|max:255',
            'correct_choice' => 'required|exists:section_quiz_choices,id',
        ]);
        // ✅ Trouver la question et la mettre à jour
        $question = SectionQuizQuestion::findOrFail($question_id);
        $question->question_text = $request->question_text; // Correction ici
        $question->save();

        // ✅ Mettre à jour les choix de réponse
        foreach ($question->choices as $index => $choice) {
            $choice->choice_text = $request->choices[$index + 1] ?? $choice->choice_text;
            $choice->is_correct = ($choice->id == $request->correct_choice);
            $choice->save();
        }

        return redirect()->route('admin.questions.index', $quiz_id)->with('success', 'Question mise à jour avec succès.');
    }



    public function destroy($quiz_id, $question_id)
    {
        SectionQuizQuestion::findOrFail($question_id)->delete();
        return redirect()->route('admin.questions.index', $quiz_id)->with('success', 'Question supprimée avec succès.');
    }
}

