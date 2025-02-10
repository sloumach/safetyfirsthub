<?php

namespace App\Services;

use App\Models\Course;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CourseService
{
    public function getAllCourses()
    {
        return Course::all();
    }

    public function createCourse($validatedData)
    {
        try {
            // Stocker l'image
            $coverPath = $validatedData['cover']->store('courses/covers', 'public');

            return Course::create([
                'price' => $validatedData['price'],
                'name' => $validatedData['name'],
                'category' => $validatedData['category'],
                'total_videos' => $validatedData['total_videos'],
                'short_description' => $validatedData['short_description'],
                'description' => $validatedData['description'],
                'cover' => $coverPath,
                'students' => 0,
            ]);
        } catch (\Exception $e) {
            Log::error("Error in createCourse: " . $e->getMessage());
            return null;
        }
    }

    public function updateCourse($id, $validatedData)
    {
        try {
            $course = Course::findOrFail($id);

            if (isset($validatedData['cover'])) {
                // Supprimer l'ancienne image
                Storage::disk('public')->delete($course->cover);

                // Stocker la nouvelle image
                $coverPath = $validatedData['cover']->store('courses/covers', 'public');
                $validatedData['cover'] = $coverPath;
            }

            $course->update($validatedData);
            return $course;
        } catch (\Exception $e) {
            Log::error("Error in updateCourse: " . $e->getMessage());
            return null;
        }
    }

    public function deleteCourse($id)
    {
        try {
            $course = Course::findOrFail($id);

            // Supprimer l'image
            Storage::disk('public')->delete($course->cover);

            // Supprimer le cours
            return $course->delete();
        } catch (\Exception $e) {
            Log::error("Error in deleteCourse: " . $e->getMessage());
            return false;
        }
    }
}
