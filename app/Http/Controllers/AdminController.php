<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function index () {




        return view('adminpanel.index');
    }
    public function courses () {




        return view('adminpanel.courses');
    }

    public function addcourse () {




        return view('adminpanel.courses');
    }

}
