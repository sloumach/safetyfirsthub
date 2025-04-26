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

class AdminController extends Controller
{
    protected $courseService;
    protected $flasher;

    public function __construct(CourseService $courseService, FlasherInterface $flasher)
    {
        $this->courseService = $courseService;
        $this->flasher       = $flasher;
    }

    public function index()
    {
        return view('adminpanel.index');

    }

    public function addcourses()
    { /* $course = Course::with(['sections.slides', 'sections.videos'])->findOrFail(17);
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
            // 🔹 Validation des données
            $validatedData = $request->validate([
                'name'                        => 'required|string|max:255',
                'total_videos'                => 'required|integer|min:1',
                'price'                       => 'required|numeric|min:0',
                'category'                    => 'required|string|max:255',
                'short_description'           => 'required|string|max:500',
                'description'                 => 'required|string|max:2000',
                'cover'                       => 'required|file|mimes:jpg,jpeg,png|max:4096',
                'duration'                    => 'required|integer|min:1', // Durée en mois
                                                                           // Sections validation
                'sections'                    => 'required|array',
                'sections.*.title'            => 'required|string|max:255',
                // Slides validation
                'sections.*.slides'           => 'array',
                'sections.*.slides.*.title'   => 'required|string|max:255',
                'sections.*.slides.*.content' => 'nullable|string|max:10000',
                'sections.*.slides.*.file'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240', // PDF / Image
                                                                                                   // Videos validation
                'sections.*.videos'           => 'array',
                'sections.*.videos.*.title'   => 'string|max:255',
                'sections.*.videos.*.video'   => 'file|mimes:mp4,mov,avi|max:500000', // Jusqu'à 500MB
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

            DB::beginTransaction();
            // 🔹 Sauvegarde de l'image de couverture
            $coverPath = $request->file('cover')->store('courses/covers', 'public');

            // 🔹 Création du cours
            $course = Course::create([
                'name'              => $validatedData['name'],
                'price'             => $validatedData['price'],
                'category'          => $validatedData['category'],
                'total_videos'      => $validatedData['total_videos'],
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
                if (! empty($sectionData['slides'])) {
                    foreach ($sectionData['slides'] as $slideData) {
                        $filePath = null;

                        if (! empty($slideData['file'])) {
                            $filePath = $slideData['file']->store('courses/slides', 'public');
                        }

                        $section->slides()->create([
                            'title'     => $slideData['title'],
                            'content'   => json_encode($slideData['content']),
                            'file_path' => $filePath,
                        ]);
                    }
                }

                // 🔹 Ajout des vidéos
                if (! empty($sectionData['videos'])) {
                    foreach ($sectionData['videos'] as $videoData) {
                        $file              = $videoData['video'];
                        $originalExtension = $file->getClientOriginalExtension();
                        $filename          = uniqid() . '.' . $originalExtension;

                        $tempPath  = "courses/videos/temp/" . $filename;
                        $finalPath = "courses/videos/" . $filename;

                        // 🔸 Enregistrement temporaire
                        Storage::disk('local')->put($tempPath, file_get_contents($file));

                        // 🔸 Déplacement vers le dossier privé
                        Storage::disk('private')->put($finalPath, Storage::disk('local')->get($tempPath));

                        // 🔸 Suppression du fichier temporaire
                        Storage::disk('local')->delete($tempPath);

                        // 🔸 Sauvegarde en base
                        $section->videos()->create([
                            'title'      => $videoData['title'],
                            'video_path' => $finalPath,
                        ]);
                    }
                }
            }
            DB::commit();
            $this->flasher->addSuccess('Course added successfully! ');
            return redirect()->route('adminindex');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Error in addCourse: " . $e->getMessage());
            $this->flasher->addSuccess('Course added successfully! ');
            return redirect()->route('adminindex');
        }
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
        DB::beginTransaction();

        try {
            $course = Course::findOrFail($id);

            // Update cover image
            if ($request->hasFile('cover')) {
                if ($course->cover && Storage::exists($course->cover)) {
                    Storage::delete($course->cover);
                }
                $course->cover = $request->file('cover')->store('courses/covers', 'public');
            }

            // Update main course infos
            $course->fill($request->only([
                'name', 'category', 'price',
                'short_description', 'description',
                'duration', 'total_videos',
            ]))->save();

            // Sections
            $existingSectionIds = $course->sections()->pluck('id')->toArray();
            $newSectionIds = [];

            foreach ($request->input('sections', []) as $sectionIndex => $sectionData) {
                $sectionId = $sectionData['id'] ?? null;

                $section = $sectionId && in_array($sectionId, $existingSectionIds)
                    ? Section::find($sectionId)
                    : new Section(['course_id' => $course->id]);

                $section->title = $sectionData['title'];
                $section->save();

                $newSectionIds[] = $section->id;

                // Slides
                $existingSlideIds = $section->slides()->pluck('id')->toArray();
                $newSlideIds = [];

                foreach ($sectionData['slides'] ?? [] as $slideIndex => $slideData) {
                    $slideId = $slideData['id'] ?? null;

                    $slide = $slideId && in_array($slideId, $existingSlideIds)
                        ? Slide::find($slideId)
                        : new Slide(['section_id' => $section->id]);

                    $slide->title = $slideData['title'] ?? '';
                    $slide->content = $slideData['content'] ?? '';

                    if ($request->hasFile("sections.$sectionIndex.slides.$slideIndex.file")) {
                        if ($slide->file_path && Storage::exists($slide->file_path)) {
                            Storage::delete($slide->file_path);
                        }
                        $slide->file_path = $request->file("sections.$sectionIndex.slides.$slideIndex.file")
                            ->store('courses/slides', 'public');
                    }

                    $slide->save();
                    $newSlideIds[] = $slide->id;
                }

                // Delete removed slides
                $section->slides()->whereNotIn('id', $newSlideIds)->get()->each(function ($slide) {
                    if ($slide->file && Storage::exists($slide->file)) {
                        Storage::delete($slide->file);
                    }
                    $slide->delete();
                });

                // Videos
                $existingVideoIds = $section->videos()->pluck('id')->toArray();
                $newVideoIds = [];

                foreach ($sectionData['videos'] ?? [] as $videoIndex => $videoData) {
                    $videoId = $videoData['id'] ?? null;

                    $video = $videoId && in_array($videoId, $existingVideoIds)
                        ? Video::find($videoId)
                        : new Video(['section_id' => $section->id]);

                    $video->title = $videoData['title'] ?? '';

                    if ($request->hasFile("sections.$sectionIndex.videos.$videoIndex.video")) {
                        // Supprimer l'ancien fichier si existant
                        if ($video->video_path && Storage::disk('private')->exists($video->video_path)) {
                            Storage::disk('private')->delete($video->video_path);
                        }

                        // Uploader le nouveau
                        $video->video_path = $request->file("sections.$sectionIndex.videos.$videoIndex.video")
                            ->store('courses/videos', 'private');
                    }

                    // Sauvegarder si vidéo existante ou nouveau fichier
                    if ($video->exists || $video->video_path) {
                        $video->save();
                        $newVideoIds[] = $video->id;
                    }
                }

                // Delete removed videos
                $section->videos()->whereNotIn('id', $newVideoIds)->get()->each(function ($video) {
                    if ($video->video_path && Storage::disk('private')->exists($video->video_path)) {
                        Storage::disk('private')->delete($video->video_path);
                    }

                    $video->delete();
                });

            }

            // Delete removed sections
            $course->sections()->whereNotIn('id', $newSectionIds)->get()->each(function ($section) {
                $section->slides->each(function ($slide) {
                    if ($slide->file && Storage::exists($slide->file)) {
                        Storage::delete($slide->file);
                    }
                    $slide->delete();
                });

                $section->videos->each(function ($video) {
                    if ($video->video && Storage::exists($video->video)) {
                        Storage::delete($video->video);
                    }
                    $video->delete();
                });

                $section->delete();
            });

            DB::commit();

            $this->flasher->addSuccess('Course updated successfully!');
            return back();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error in updateCourse: " . $e->getMessage());
            $this->flasher->addError('An error occurred while updating the course.');
            return back();
        }
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
            ->get(['id', 'firstname', 'lastname', 'email', 'created_at']);

        return view('adminpanel.usersmanagement', compact('users'));
    }
}
