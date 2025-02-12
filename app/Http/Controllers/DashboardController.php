<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
            $user = auth()->user();
            $courses = $user->courses->map(function($course) {
                return [
                    'id' => $course->id,
                    'title' => $course->name,
                    'description' => $course->description,
                    'image' => $course->cover,
                    'duration' => $course->total_videos . ' videos',
                    'students' => $course->students ?? 0,
                ];
            });
            
        return view('userdashboard.dashboard-course', compact('courses'));
    }

    public function getCourses()
    {
        try {
            $user = auth()->user();
            $courses = $user->courses->map(function($course) {
                    return [
                        'id' => $course->id,
                        'name' => $course->name,
                        'description' => $course->description,
                        'cover' => $course->cover ? asset('storage/' . $course->cover) : null,
                        'total_videos' => $course->total_videos,
                        'students' => $course->students ?? 0,
                    ];
                });
            
            return response()->json($courses, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch courses'], 500);
        }
    }

    public function getCourse($id)
    {
        try {
            $course = Course::findOrFail($id);
            $coverUrl = $course->cover ? asset('storage/' . $course->cover) : null;
            
            return response()->json([
                'id' => $course->id,
                'name' => $course->name,
                'description' => $course->description,
                'cover' => $coverUrl,
                'total_videos' => $course->total_videos,
                'students' => $course->students ?? 0,
                'videoUrl' => $coverUrl, 
                'instructor' => 'John Doe',
                'price' => 'Free'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Course not found'], 404);
        }
    }
}
