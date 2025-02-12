<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home () {



        return view('index-2');
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

}
