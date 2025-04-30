<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    //

    public function index () {
        $courses = Course::all(); // Récupère tous les cours
        return view('courses', compact('courses')); // Passe les cours à la vue
    }
    public function singlecourse ($id) {


        $course = Course::findOrFail($id); // Récupérer le cours par ID

        return view('single-course', compact('course'));
    }

    public function search(Request $request)
    {
        $query = $request->input('search');

        if (!$query) {
            // si l'utilisateur a cliqué sans taper, on peut rediriger ou afficher tous les cours
            return redirect()->route('courses');
        }

        // Découper la recherche par mots
        $words = preg_split('/\s+/', $query, -1, PREG_SPLIT_NO_EMPTY);

        // Construire une requête dynamique
        $courses = Course::where(function($q) use ($words) {
            foreach ($words as $word) {
                $q->orWhere('name', 'LIKE', '%' . $word . '%');
            }
        })->get();

        return view('courses', compact('courses'));
    }
}
