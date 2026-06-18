<?php

namespace App\Http\Controllers\Admin;

use App\Actions\CreateCourseAction;
use App\Actions\UpdateCourseAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Course;
use Cloudinary\Cloudinary;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class DashboardController extends Controller
{
    public function __construct()
    {}

    public function index(): View
    {
        $latestCourses = Course::latest()->limit(5)->get();
        // $mostEnrolled = Course::latest()->limit(5)->get();

        return view('page.dashboard', [
            'activeNav' => 'courses',
            'latestCourses'   => $latestCourses,
            // 'mostEnrolled'    => $mostEnrolled,
            // 'stats'     => $stats,
        ]);
    }

    public function create(): View
    {
        return view('page.courses.create', [
            'activeNav' => 'courses',
        ]);
    }

    public function store(StoreCourseRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('thumbnail')) {
            $result               = $this->cloudinary->uploadApi()->upload(
                $request->file('thumbnail')->getRealPath(),
                ['folder' => 'lms/thumbnails']
            );
            $data['thumbnail']    = $result['public_id'];
            $data['thumbnail_url'] = $result['secure_url'];
        }

        $this->createCourse->handle($data, auth()->id());

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Kursus berhasil dibuat!');
    }

    public function show(Course $course): View
    {
        $course->load(['creator', 'chapters.lessons']);

        return view('page.courses.show', [
            'activeNav' => 'courses',
            'course'    => $course,
        ]);
    }

    public function edit(Course $course): View
    {
        return view('page.courses.create', [
            'activeNav' => 'courses',
            'course'    => $course,
        ]);
    }

    public function update(UpdateCourseRequest $request, Course $course): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('thumbnail')) {
            // Hapus thumbnail lama dari Cloudinary jika ada
            if ($course->thumbnail) {
                $this->cloudinary->uploadApi()->destroy($course->thumbnail);
            }

            $result               = $this->cloudinary->uploadApi()->upload(
                $request->file('thumbnail')->getRealPath(),
                ['folder' => 'lms/thumbnails']
            );
            $data['thumbnail']    = $result['public_id'];
            $data['thumbnail_url'] = $result['secure_url'];
        }

        $this->updateCourse->handle($course, $data);

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Kursus berhasil diperbarui!');
    }

    public function destroy(Course $course): RedirectResponse
    {
        // Hapus thumbnail dari Cloudinary jika ada
        if ($course->thumbnail) {
            $this->cloudinary->uploadApi()->destroy($course->thumbnail);
        }

        $course->delete();

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Kursus berhasil dihapus!');
    }
}
