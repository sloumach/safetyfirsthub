<?php

namespace App\Http\Controllers;

use App\Services\CourseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Role;
use App\Models\Order;
use App\Models\Course;
use App\Models\User;

class AdminController extends Controller
{
    protected $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    public function index()
    {
        return view('adminpanel.index');
    }

    public function courses()
    {
        try {
            $courses = $this->courseService->getAllCourses();
            return view('adminpanel.courses', compact('courses'));
        } catch (\Exception $e) {
            Log::error("Error in courses: " . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while fetching courses.');
        }
    }

    public function addCourse(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'category' => 'required|string|max:255',
                'total_videos' => 'required|integer|min:1',
                'short_description' => 'required|string|max:500',
                'description' => 'required|string|max:2000',
                'cover' => 'required|file|mimes:jpg,jpeg,png|max:2048',
                'video' => 'required|file|mimes:mp4,mov,avi|max:50000', // 50MB max
            ]);

            // Sauvegarde de l'image de couverture
            $coverPath = $request->file('cover')->store('courses/covers', 'private');

            if (!$request->hasFile('video')) {
                
            }

            // Teste si le fichier est lisible
            $file = $request->file('video');
            if (!$file->isValid()) {
                
            }

            // Vérifie la taille du fichier
            if ($file->getSize() > 50000000) {
                
            }

            // Sauvegarde de la vidéo
            $videoPath = $request->file('video')->store('courses/videos', 'private');
            

            // Création du cours
            Course::create([
                'name' => $validatedData['name'],
                'price' => $validatedData['price'],
                'category' => $validatedData['category'],
                'total_videos' => $validatedData['total_videos'],
                'short_description' => $validatedData['short_description'],
                'description' => $validatedData['description'],
                'cover' => $coverPath,
                'video' => $videoPath, // Enregistre le chemin sécurisé de la vidéo
                'students' => 0,
            ]);

            return redirect()->back()->with('success', 'Course added successfully!');
        } catch (\Exception $e) {
            dd($e->getMessage());
            Log::error("Error in addCourse: " . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while adding the course.');
        }
    }


    public function updateCourse(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'category' => 'required|string|max:255',
                'total_videos' => 'required|integer|min:1',
                'short_description' => 'required|string|max:500',
                'description' => 'required|string|max:2000',
                'cover' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            ]);

            $course = $this->courseService->updateCourse($id, $validatedData);

            if (!$course) {
                return redirect()->back()->with('error', 'Failed to update course.');
            }

            return redirect()->back()->with('success', 'Course updated successfully!');
        } catch (\Exception $e) {
            Log::error("Error in updateCourse: " . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating the course.');
        }
    }

    public function deleteCourse($id)
    {
        try {
            $success = $this->courseService->deleteCourse($id);

            if (!$success) {
                return redirect()->back()->with('error', 'Failed to delete course.');
            }

            return redirect()->back()->with('success', 'Course deleted successfully!');
        } catch (\Exception $e) {
            Log::error("Error in deleteCourse: " . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while deleting the course.');
        }
    }

    public function finance()
    {
        try {
            $salesData = Order::selectRaw('DATE_FORMAT(orders.created_at, "%Y-%m") as month, orders.course_id, COUNT(*) as total_sales, SUM(payments.total_price) as total_revenue')
                ->join('payments', 'orders.payment_id', '=', 'payments.id')
                ->where('payments.status', 'completed')
                ->groupBy('month', 'orders.course_id')
                ->orderBy('month')
                ->get();

            $salesByCourse = [];
            $revenueByCourse = [];
            $courseNames = Course::pluck('name', 'id');

            foreach ($salesData as $sale) {
                $salesByCourse[$courseNames[$sale->course_id]][] = [
                    'month' => $sale->month,
                    'total_sales' => $sale->total_sales,
                ];
                $revenueByCourse[$courseNames[$sale->course_id]][] = [
                    'month' => $sale->month,
                    'total_revenue' => round($sale->total_revenue, 2),
                ];
            }

            $studentRole = Role::where('name', 'student')->first();
            $students = User::with(['orders.course'])
                ->whereHas('roles', function ($query) use ($studentRole) {
                    $query->where('role_id', $studentRole->id);
                })
                ->get();

            return view('adminpanel.finances', compact('salesByCourse', 'revenueByCourse', 'students'));
            } catch (\Exception $e) {
            Log::error("Error in finance: " . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while fetching financial data.');
        }
    }
    public function usersManagement()
    {
        $users = User::with(['roles', 'courses'])
            ->orderBy('created_at', 'desc')
            ->get(['id', 'firstname','lastname', 'email', 'created_at']);

        return view('adminpanel.usersmanagement', compact('users'));
    }
}

