<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Section;
use App\Models\SectionQuiz;
use Illuminate\Http\Request;
use App\Models\SectionQuizChoice;
use App\Models\SectionQuizQuestion;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class AdminQuizController extends Controller
{
    public function index()
    {
        $quizzes = SectionQuiz::with('section')->get();
        return view('adminpanel.quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        $courses = Course::with('sections')->get(); // toutes les sections groupées par cours
        return view('adminpanel.quizzes.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'section_id' => 'required|exists:sections,id',
            'passing_score' => 'required|integer|min:0|max:100',
        ]);

        SectionQuiz::create($request->all());

        return redirect()->route('admin.quizzes.index')->with('success', 'Quiz ajouté avec succès.');
    }

    public function show($id)
    {
        $quiz = SectionQuiz::with('questions.choices')->findOrFail($id);
        return view('adminpanel.quizzes.show', compact('quiz'));
    }

    public function edit($id)
    {
        $quiz = SectionQuiz::findOrFail($id);
        $sections = Section::all();
        return view('adminpanel.quizzes.edit', compact('quiz', 'sections'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'section_id' => 'required|exists:sections,id',
            'passing_score' => 'required|integer|min:0|max:100',
        ]);

        $quiz = SectionQuiz::findOrFail($id);
        $quiz->update($request->all());

        return redirect()->route('admin.quizzes.index')->with('success', 'Quiz mis à jour avec succès.');
    }

    public function destroy($id)
    {
        SectionQuiz::findOrFail($id)->delete();
        return redirect()->route('admin.quizzes.index')->with('success', 'Quiz supprimé avec succès.');
    }
}
