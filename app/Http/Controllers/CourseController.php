<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CourseController extends Controller
{
    //

    public function index () {



        return view('courses');
    }
    public function singlecourse () {



        return view('single-course');
    }
}
