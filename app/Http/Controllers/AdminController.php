<?php
namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Order;
use App\Models\Role;
use App\Models\Section;
use App\Models\Slide;
use App\Models\User;
use App\Models\Video;
use App\Services\CourseService;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Services\AdminCourseManagement;


class AdminController extends Controller
{
    protected $courseService;
    protected $flasher;
    protected AdminCourseManagement $adminCourseService;


    public function __construct(CourseService $courseService, FlasherInterface $flasher, AdminCourseManagement $adminCourseService,)
    {
        $this->courseService = $courseService;
        $this->flasher       = $flasher;
        $this->adminCourseService = $adminCourseService;
    }

    public function index()
    {
        return view('adminpanel.index');

    }

    public function addcourses()
    {
        try {
            $courses = $this->courseService->getAllCourses();
            return view('adminpanel.courses', compact('courses'));
        } catch (\Exception $e) {
            Log::error("Error in courses: " . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while fetching courses.');
        }
    }

    public function removecourses()
    {
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
            $validatedData = $request->validate([
                'name'                        => 'required|string|max:255',
                'total_videos'                => 'required|integer|min:0',
                'price'                       => 'required|numeric|min:0',
                'category'                    => 'required|string|max:255',
                'short_description'           => 'required|string|max:500',
                'description'                 => 'required|string|max:2000',
                'cover'                       => 'required|file|mimes:jpg,jpeg,png|max:4096',
                'duration'                    => 'required|integer|min:1',
                'sections'                    => 'required|array',
                'sections.*.title'            => 'required|string|max:255',
                'sections.*.slides'           => 'array',
                'sections.*.slides.*.title'   => 'required|string|max:255',
                'sections.*.slides.*.content' => 'nullable|string|max:10000',
                'sections.*.slides.*.file'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'sections.*.videos'           => 'array',
                'sections.*.videos.*.title'   => 'string|max:255',
                'sections.*.videos.*.video'   => 'file|mimes:mp4,mov,avi|max:500000',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            foreach ($e->errors() as $field => $messages) {
                foreach ($messages as $message) {
                    $this->flasher->addError($field . ': ' . $message);
                }
            }
            return back()->withInput();
        }

        try {
            $this->adminCourseService->storeCourseWithContent($validatedData, $request);
            $this->flasher->addSuccess('Course added successfully!');
        } catch (\Exception $e) {
            \Log::error("Error in addCourse: " . $e->getMessage());
            $this->flasher->addError('Something went wrong while adding the course.');
        }

        return redirect()->route('admincourses');
    }

    public function edit($id)
    {
        try {
            $course = Course::with(['sections.slides', 'sections.videos'])->findOrFail($id);
            return view('adminpanel.editcourse', compact('course'));
        } catch (\Exception $e) {
            Log::error("Error in edit course: " . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while fetching course details.');
        }
    }

    public function updateCourse(Request $request, $id)
    {
        $success = $this->courseManagement->updateCourse($request, $id);

        if ($success) {
            $this->flasher->addSuccess('Course updated successfully!');
        } else {
            $this->flasher->addError('An error occurred while updating the course.');
        }

        return back();
    }


    public function deleteCourse($id)
    {
        try {
            $success = $this->courseService->deleteCourse($id);

            if (! $success) {
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

            $salesByCourse   = [];
            $revenueByCourse = [];
            $courseNames     = Course::pluck('name', 'id');

            foreach ($salesData as $sale) {
                $salesByCourse[$courseNames[$sale->course_id]][] = [
                    'month'       => $sale->month,
                    'total_sales' => $sale->total_sales,
                ];
                $revenueByCourse[$courseNames[$sale->course_id]][] = [
                    'month'         => $sale->month,
                    'total_revenue' => round($sale->total_revenue, 2),
                ];
            }

            $studentRole = Role::where('name', 'student')->first();
            $students    = User::with(['orders.course'])
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
            ->get(['id', 'firstname', 'lastname', 'email', 'created_at','status']);

        return view('adminpanel.usersmanagement', compact('users'));
    }
    public function toggleStatus(User $user)
    {
        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();

        return response()->json(['status' => $user->status]);
    }
    public function userDetails(User $user)
    {
        $user->load([
            'courses.sections.videos.videoProgress',
            'examUsers.exam',
            'examUsers.certificate',
        ]);

        return view('adminpanel.userDetailModal', compact('user'));
    }


}
