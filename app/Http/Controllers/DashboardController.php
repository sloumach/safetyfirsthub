<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        return view('userdashboard.dashboard-course',compact('courses'));
    }
}
