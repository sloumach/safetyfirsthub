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
}
