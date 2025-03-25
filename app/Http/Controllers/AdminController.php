<?php

namespace App\Http\Controllers;

use App\Services\CourseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Role;
use App\Models\Order;
use App\Models\Course;
use App\Models\User;
use Flasher\Prime\FlasherInterface;
use App\Jobs\ProcessVideoUpload;
use Illuminate\Support\Facades\Storage;



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

    public function addcourses()
    {        /* $course = Course::with(['sections.slides', 'sections.videos'])->findOrFail(17);
        return view('adminpanel.courses', compact('course'));
        dd($course); */
        try {
            $courses = $this->courseService->getAllCourses();
            return view('adminpanel.courses', compact('courses'));
        } catch (\Exception $e) {
            Log::error("Error in courses: " . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while fetching courses.');
        }
    }

    public function removecourses()
    {        /* $course = Course::with(['sections.slides', 'sections.videos'])->findOrFail(17);
        return view('adminpanel.courses', compact('course'));
        dd($course); */
        try {
            $courses = $this->courseService->getAllCourses();
            return view('adminpanel.removecourses', compact('courses'));
        } catch (\Exception $e) {
            Log::error("Error in courses: " . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while fetching courses.');
        }
    }

    public function addCourse(Request $request)
    {

        try {
            // 🔹 Validation des données
            $validatedData = $request->validate([
                'name'              => 'required|string|max:255',
                'total_videos'              => 'required|integer|min:1',
                'price'             => 'required|numeric|min:0',
                'category'          => 'required|string|max:255',
                'short_description' => 'required|string|max:500',
                'description'       => 'required|string|max:2000',
                'cover'             => 'required|file|mimes:jpg,jpeg,png|max:2048',
                'duration'          => 'required|integer|min:1', // Durée en mois
                // Sections validation
                'sections'                  => 'required|array',
                'sections.*.title'          => 'required|string|max:255',
                // Slides validation
                'sections.*.slides'                 => 'array',
                'sections.*.slides.*.title'         => 'required|string|max:255',
                'sections.*.slides.*.content'       => 'nullable|string|max:10000',
                'sections.*.slides.*.file'          => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240', // PDF / Image
                // Videos validation
                'sections.*.videos'                 => 'array',
                'sections.*.videos.*.title'         => 'required|string|max:255',
                'sections.*.videos.*.video'         => 'required|file|mimes:mp4,mov,avi|max:500000', // Jusqu'à 500MB
            ]);

            // 🔹 Sauvegarde de l'image de couverture
            $coverPath = $request->file('cover')->store('courses/covers', 'public');

            // 🔹 Création du cours
            $course = Course::create([
                'name'              => $validatedData['name'],
                'price'             => $validatedData['price'],
                'category'          => $validatedData['category'],
                'total_videos'          => $validatedData['total_videos'],
                'short_description' => $validatedData['short_description'],
                'description'       => $validatedData['description'],
                'cover'             => $coverPath,
                'students'          => 0,
                'duration'          => $validatedData['duration'],
            ]);

            // 🔹 Ajout des sections
            foreach ($validatedData['sections'] as $sectionData) {
                $section = $course->sections()->create([
                    'title' => $sectionData['title'],
                ]);

                // 🔹 Ajout des slides
                if (!empty($sectionData['slides'])) {
                    foreach ($sectionData['slides'] as $slideData) {
                        $filePath = null;

                        if (!empty($slideData['file'])) {
                            $filePath = $slideData['file']->store('courses/slides', 'public');
                        }

                        $section->slides()->create([
                            'title'    => $slideData['title'],
                            'content'  => json_encode($slideData['content']),
                            'file_path'=> $filePath,
                        ]);
                    }
                }

                // 🔹 Ajout des vidéos
                if (!empty($sectionData['videos'])) {
                    foreach ($sectionData['videos'] as $videoData) {
                        $file = $videoData['video'];
                        $tempVideoPath = "courses/videos/temp/" . uniqid() . '.' . $file->getClientOriginalExtension();

                        // 🔹 Stockage temporaire avant traitement en queue
                        Storage::disk('local')->put($tempVideoPath, file_get_contents($file));

                        // 🔹 Création d'une entrée vidéo en base de données
                        $video = $section->videos()->create([
                            'title' => $videoData['title'],
                            'video_path' => 'processing', // Statut en cours de traitement
                        ]);

                        // 🔹 Dispatch du job de traitement vidéo
                        ProcessVideoUpload::dispatch($video->id, $tempVideoPath);
                    }
                }
            }

            return response()->json(['message' => 'Course added successfully!'], 201);
        } catch (\Exception $e) {
            \Log::error("Error in addCourse: " . $e->getMessage());
            return response()->json(['error' => $e->getmessage() ], 500);
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
        } catch (\Illuminate\Validation\ValidationException $e) {
            foreach ($e->errors() as $field => $messages) {
                foreach ($messages as $message) {
                    flash()->error($field . ': ' . $message);
                }
            }
            return back()->withInput();
        }

        try {
            $validatedData = $request->all();


            $course = $this->courseService->updateCourse($id, $validatedData);

            if (!$course) {
                flash()->error('Failed to update course.');
                return back()->withInput();
            }

            flash()->success('Course updated successfully!');
            return redirect()->back();
        } catch (\Exception $e) {
            Log::error("Error in updateCourse: " . $e->getMessage());
            flash()->error('An error occurred while updating the course.');
            return back()->withInput();
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

