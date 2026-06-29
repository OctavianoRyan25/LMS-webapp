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

final class CourseController extends Controller
{
    public function __construct(
        private readonly CreateCourseAction $createCourse,
        private readonly UpdateCourseAction $updateCourse,
        private readonly Cloudinary         $cloudinary,
    ) {}

    public function index(Request $request): View
    {
        $courses = Course::query()
            ->with(['creator'])
            ->when($request->search, function ($query, $search): void {
                $query->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($request->status, function ($query, $status): void {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10);

        $stats = [
            'total'     => Course::count(),
            'published' => Course::where('status', 'published')->count(),
            'draft'     => Course::where('status', 'draft')->count(),
            'archived'  => Course::where('status', 'archived')->count(),
        ];

        return view('page.courses.index', [
            'activeNav' => 'courses',
            'courses'   => $courses,
            'stats'     => $stats,
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
        $course->load(['creator', 'lessons.files', 'exams.submissions']);

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
