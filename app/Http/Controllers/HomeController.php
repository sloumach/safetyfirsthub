<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;


class HomeController extends Controller
{
    public function home () {
        $courses = Course::all();



        return view('index-2',compact('courses'));
    }


    public function policy () {



        return view('privacy-policy');
    }

    public function terms () {



        return view('terms-conditions');
    }

    public function faq () {



        return view('faq');
    }

    public function contact () {



        return view('contact');
    }
    public function about () {



        return view('about');
    }

}
