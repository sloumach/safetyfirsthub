<?php
namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Exam;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Flasher\Prime\FlasherInterface;

class AdminExamsController extends Controller
{
    public function listExams()
    {
        $exams = Exam::with('course')->get();
        return view('adminpanel.exams.index', compact('exams'));
    }

    public function createExam()
    {
        $courses = Course::all(); // Récupérer les cours disponibles

        return view('adminpanel.exams.create', compact('courses'));
    }

    public function storeExam(Request $request)
    {
        $rules = [
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'duration' => 'required|integer|min:5|max:120',
            'passing_score' => 'required|integer|min:1|max:100',
            'is_active' => 'boolean',
        ];

        foreach ($rules as $field => $rule) {
            $validator = Validator::make($request->all(), [$field => $rule]);
            if ($validator->fails()) {
                flash()->error($validator->errors()->first());
                return back()->withInput();
            }
        }

        $validatedData = $request->all();
        Exam::create($validatedData);
        return redirect()->route('admin.exams')->with('success', 'Exam created successfully!');
    }

    public function editExam($id)
    {
        $exam    = Exam::findOrFail($id);
        $courses = Course::all();
        return view('adminpanel.exams.edit', compact('exam', 'courses'));
    }

    public function updateExam(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'course_id'     => 'required|exists:courses,id',
                'title'         => 'required|string|max:255',
                'description'   => 'nullable|string',
                'duration'      => 'required|integer|min:5|max:120',
                'passing_score' => 'required|integer|min:1|max:100',
                'is_active'     => 'boolean',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            foreach ($e->errors() as $field => $messages) {
                foreach ($messages as $message) {
                    flash()->error($field . ': ' . $message);
                }
            }
            return back()->withInput();
        }

        $exam = Exam::findOrFail($id);
        $exam->update($validatedData);

        return redirect()->route('admin.exams')->with('success', 'Exam updated successfully!');
    }

    public function deleteExam($id)
    {
        $exam = Exam::findOrFail($id);
        $exam->delete();

        return redirect()->route('admin.exams')->with('success', 'Exam deleted successfully!');
    }

    public function listQuestions($exam_id)
    {
        $exam      = Exam::with('questions.choices')->findOrFail($exam_id);
        $questions = $exam->questions; // Récupérer les questions de l'examen
        return view('adminpanel.exams.questions', compact('exam', 'questions'));
    }

    public function createQuestion($exam_id)
    {
        $exam = Exam::findOrFail($exam_id);
        return view('adminpanel.exams.create_question', compact('exam'));
    }

    public function storeQuestion(Request $request, $exam_id)
    {
        $rules = [
            'question_text' => 'required|string|max:1000',
            'answers' => 'required|array|size:4',
            'answers.*' => 'required|string|max:255',
            'correct_answer' => 'required|integer|min:1|max:4',
        ];

        foreach ($rules as $field => $rule) {
            $validator = Validator::make($request->all(), [$field => $rule]);
            if ($validator->fails()) {
                flash()->error($validator->errors()->first());
                return back()->withInput();
            }
        }

        $validatedData = $request->all();
        $exam = Exam::findOrFail($exam_id);

        // Créer la question
        $question = $exam->questions()->create([
            'question_text' => $validatedData['question_text'],
        ]);

        // Vérifier que $question est bien créé avant d'ajouter les réponses
        if (! $question) {
           
            return redirect()->back()->with('error', 'Error creating question.');
        }

        // Ajouter les réponses
        foreach ($validatedData['answers'] as $index => $answerText) {
            $question->choices()->create([
                'choice_text' => $answerText,                                      // Assurez-vous que c'est bien `choice_text` et non `answer_text`
                'is_correct'  => ($index + 1 == $validatedData['correct_answer']), // Vérifie si c'est la bonne réponse
            ]);
        }

        return redirect()->route('admin.exams.questions', $exam_id)->with('success', 'Question ajoutée avec succès !');
    }

    public function deleteQuestion($id)
    {
        $question = Question::findOrFail($id);
        $exam_id  = $question->exam_id;
        $question->delete();

        return redirect()->route('admin.exams.questions', $exam_id)->with('success', 'Question deleted successfully!');
    }

    public function toggleExamStatus($id)
    {
        $exam            = Exam::findOrFail($id);
        $exam->is_active = ! $exam->is_active;
        $exam->save();

        return redirect()->back()->with('success', 'Exam status updated successfully!');
    }

    public function editQuestion($id)
    {
        $question = Question::with('choices')->findOrFail($id);
        $exam     = $question->exam; // Récupérer l'examen associé à la question

        return view('adminpanel.exams.edit_question', compact('question', 'exam'));
    }

    public function updateQuestion(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'question_text'  => 'required|string|max:1000',
                'choices'        => 'required|array|size:4',
                'choices.*'      => 'required|string|max:255',
                'correct_answer' => 'required|integer|min:1|max:4',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            foreach ($e->errors() as $field => $messages) {
                foreach ($messages as $message) {
                    flash()->error($field . ': ' . $message);
                }
            }
            return back()->withInput();
        }

        $question = Question::findOrFail($id);
        $exam_id  = $question->exam_id; // Récupérer l'ID de l'examen pour la redirection

        // Mise à jour du texte de la question
        $question->update([
            'question_text' => $validatedData['question_text'],
        ]);

        // Mettre à jour les choix existants
        foreach ($question->choices as $index => $choice) {
            $choice->update([
                'choice_text' => $validatedData['choices'][$index],
                'is_correct'  => ($index + 1 == $validatedData['correct_answer']), // Mettre à jour la bonne réponse
            ]);
        }

        return redirect()->route('admin.exams.questions', $exam_id)->with('success', 'Question updated successfully!');
    }

}
