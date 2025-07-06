<?php
namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Exam;
use App\Models\Question;
use App\Services\AdminExamsManagement;
use App\Services\AdminQuestionsManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Flasher\Prime\FlasherInterface;

class AdminExamsController extends Controller
{
    protected AdminExamsManagement $examService;
    protected AdminQuestionsManagement $questionService;

    public function __construct(AdminExamsManagement $examService, AdminQuestionsManagement $questionService)
    {
        $this->examService = $examService;
        $this->questionService = $questionService;
    }

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
        $data = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'duration' => 'required|integer|min:5|max:120',
            'passing_score' => 'required|integer|min:1|max:100',
            'is_active' => 'boolean',
        ]);

        $this->examService->store($data);

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
            $data = $request->validate([
                'course_id' => 'required|exists:courses,id',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'duration' => 'required|integer|min:5|max:120',
                'passing_score' => 'required|integer|min:1|max:100',
                'is_active' => 'boolean',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            foreach ($e->errors() as $field => $messages) {
                foreach ($messages as $message) {
                    flash()->error($field . ': ' . $message);
                }
            }
            return back()->withInput();
        }

        $this->examService->update($id, $data);

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

    public function storeQuestion(Request $request, $examId)
    {
        $data = $request->validate([
            'question_text' => 'required|string|max:1000',
            'answers' => 'required|array|size:4',
            'answers.*' => 'required|string|max:255',
            'correct_answer' => 'required|integer|min:1|max:4',
        ]);

        $this->questionService->store(
            $examId,
            $data['question_text'],
            $data['answers'],
            $data['correct_answer']
        );

        return redirect()->route('admin.exams.questions', $examId)->with('success', 'Question ajoutée avec succès !');
    }

    public function editQuestion($id)
    {
        $question = Question::with('choices')->findOrFail($id);
        $exam     = $question->exam; // Récupérer l'examen associé à la question

        return view('adminpanel.exams.edit_question', compact('question', 'exam'));
    }

    public function updateQuestion(Request $request, $id)
    {
        $data = $request->validate([
            'question_text' => 'required|string|max:1000',
            'choices' => 'required|array|size:4',
            'choices.*' => 'required|string|max:255',
            'correct_answer' => 'required|integer|min:1|max:4',
        ]);

        $question = Question::findOrFail($id);

        $this->questionService->update(
            $id,
            $data['question_text'],
            $data['choices'],
            $data['correct_answer']
        );

        return redirect()->route('admin.exams.questions', $question->exam_id)->with('success', 'Question mise à jour avec succès !');
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

}
