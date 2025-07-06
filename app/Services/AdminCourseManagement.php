<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use Illuminate\Support\Facades\Log;
use App\Models\{Course, Section, Slide, Video};


class AdminCourseManagement
{
    protected CourseRepositoryInterface $courseRepo;

    public function __construct(CourseRepositoryInterface $courseRepo)
    {
        $this->courseRepo = $courseRepo;
    }

    public function storeCourseWithContent(array $validatedData, $request): Course
    {
        DB::beginTransaction();

        try {
            $coverPath = $request->file('cover')->store('courses/covers', 'public');

            $course = $this->courseRepo->createCourse([
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

            foreach ($validatedData['sections'] as $sectionData) {
                $section = $course->sections()->create(['title' => $sectionData['title']]);

                // Slides
                if (!empty($sectionData['slides'])) {
                    foreach ($sectionData['slides'] as $slideData) {
                        $filePath = null;
                        if (!empty($slideData['file'])) {
                            $filePath = $slideData['file']->store('courses/slides', 'public');
                        }

                        $section->slides()->create([
                            'title'     => $slideData['title'],
                            'content'   => json_encode($slideData['content']),
                            'file_path' => $filePath,
                        ]);
                    }
                }

                // Videos
                if (!empty($sectionData['videos'])) {
                    foreach ($sectionData['videos'] as $videoData) {
                        $file              = $videoData['video'];
                        $filename          = uniqid() . '.' . $file->getClientOriginalExtension();
                        $tempPath          = "courses/videos/temp/" . $filename;
                        $finalPath         = "courses/videos/" . $filename;

                        Storage::disk('local')->put($tempPath, file_get_contents($file));
                        Storage::disk('private')->put($finalPath, Storage::disk('local')->get($tempPath));
                        Storage::disk('local')->delete($tempPath);

                        $section->videos()->create([
                            'title'      => $videoData['title'],
                            'video_path' => $finalPath,
                        ]);
                    }
                }
            }

            DB::commit();
            return $course;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateCourse(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $course = Course::findOrFail($id);

            // 🖼️ Image de couverture
            if ($request->hasFile('cover')) {
                if ($course->cover && Storage::exists($course->cover)) {
                    Storage::delete($course->cover);
                }
                $course->cover = $request->file('cover')->store('courses/covers', 'public');
            }

            // 📝 Mise à jour des infos du cours
            $course->fill($request->only([
                'name', 'category', 'price',
                'short_description', 'description',
                'duration', 'total_videos',
            ]))->save();

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

                // 🔄 Slides
                $existingSlideIds = $section->slides()->pluck('id')->toArray();
                $newSlideIds = [];

                foreach ($sectionData['slides'] ?? [] as $slideIndex => $slideData) {
                    $slideId = $slideData['id'] ?? null;

                    $slide = $slideId && in_array($slideId, $existingSlideIds)
                        ? Slide::find($slideId)
                        : new Slide(['section_id' => $section->id]);

                    $slide->title   = $slideData['title'] ?? '';
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

                $section->slides()->whereNotIn('id', $newSlideIds)->each(function ($slide) {
                    if ($slide->file_path && Storage::exists($slide->file_path)) {
                        Storage::delete($slide->file_path);
                    }
                    $slide->delete();
                });

                // 🔄 Vidéos
                $existingVideoIds = $section->videos()->pluck('id')->toArray();
                $newVideoIds = [];

                foreach ($sectionData['videos'] ?? [] as $videoIndex => $videoData) {
                    $videoId = $videoData['id'] ?? null;

                    $video = $videoId && in_array($videoId, $existingVideoIds)
                        ? Video::find($videoId)
                        : new Video(['section_id' => $section->id]);

                    $video->title = $videoData['title'] ?? '';

                    if ($request->hasFile("sections.$sectionIndex.videos.$videoIndex.video")) {
                        if ($video->video_path && Storage::disk('private')->exists($video->video_path)) {
                            Storage::disk('private')->delete($video->video_path);
                        }
                        $video->video_path = $request->file("sections.$sectionIndex.videos.$videoIndex.video")
                            ->store('courses/videos', 'private');
                    }

                    if ($video->exists || $video->video_path) {
                        $video->save();
                        $newVideoIds[] = $video->id;
                    }
                }

                $section->videos()->whereNotIn('id', $newVideoIds)->each(function ($video) {
                    if ($video->video_path && Storage::disk('private')->exists($video->video_path)) {
                        Storage::disk('private')->delete($video->video_path);
                    }
                    $video->delete();
                });
            }

            $course->sections()->whereNotIn('id', $newSectionIds)->each(function ($section) {
                $section->slides->each(function ($slide) {
                    if ($slide->file_path && Storage::exists($slide->file_path)) {
                        Storage::delete($slide->file_path);
                    }
                    $slide->delete();
                });

                $section->videos->each(function ($video) {
                    if ($video->video_path && Storage::disk('private')->exists($video->video_path)) {
                        Storage::disk('private')->delete($video->video_path);
                    }
                    $video->delete();
                });

                $section->delete();
            });

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error in updateCourse: " . $e->getMessage());
            return false;
        }
    }
}

