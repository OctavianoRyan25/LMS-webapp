<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLessonRequest;
use App\Http\Requests\UpdateLessonRequest;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonFile;
use Cloudinary\Cloudinary;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class LessonController extends Controller
{
    public function __construct(
        private readonly Cloudinary $cloudinary
    ) {}

    /**
     * Redirect ke course show — lesson dilist di sana.
     */
    public function index(Course $course): RedirectResponse
    {
        return redirect()->route('admin.courses.show', $course);
    }

    /**
     * Form tambah lesson baru untuk course tertentu.
     */
    public function create(Course $course): View
    {
        return view('page.lessons.create', [
            'activeNav' => 'courses',
            'course'    => $course,
        ]);
    }

    /**
     * Simpan lesson baru + upload video & lampiran ke Cloudinary.
     */
    public function store(StoreLessonRequest $request, Course $course): RedirectResponse
    {
        $data = $request->validated();

        // Upload video ke Cloudinary
        if ($request->hasFile('video')) {
            $result                  = $this->cloudinary->uploadApi()->upload(
                $request->file('video')->getRealPath(),
                ['folder' => 'lms/videos', 'resource_type' => 'video']
            );
            $data['video_url']       = $result['secure_url'];
            $data['video_public_id'] = $result['public_id'];
        }

        // validate order if exists
        if ($request->order) {
            $order = $request->order;
            $existingOrder = Lesson::where('course_id', $course->id)->where('order', $order)->first();
            if ($existingOrder) {
                return redirect()->back()->with('error', 'Order already exists');
            }
        }

        // Buat lesson
        $lesson = $course->lessons()->create([
            'title'            => $data['title'],
            'content'          => $data['content'] ?? null,
            'video_url'        => $data['video_url'] ?? null,
            'video_public_id'  => $data['video_public_id'] ?? null,
            'order'            => $data['order'] ?? ($course->lessons()->max('order') + 1),
            'duration_minutes' => $data['duration_minutes'] ?? null,
            'is_free_preview'  => $data['is_free_preview'] ?? false,
        ]);

        // Upload lampiran (bisa multiple)
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $result = $this->cloudinary->uploadApi()->upload(
                    $file->getRealPath(),
                    ['folder' => 'lms/attachments', 'resource_type' => 'raw']
                );
                $lesson->files()->create([
                    'file_type'            => 'attachment',
                    'file_name'            => $file->getClientOriginalName(),
                    'file_url'             => $result['secure_url'],
                    'cloudinary_public_id' => $result['public_id'],
                    'file_size'            => $file->getSize(),
                ]);
            }
        }

        return redirect()
            ->route('admin.courses.show', $course)
            ->with('success', "Lesson \"{$lesson->title}\" berhasil ditambahkan!");
    }

    /**
     * Form edit lesson.
     */
    public function edit(Lesson $lesson): View
    {
        $lesson->load(['course', 'files', 'quizzes', 'assignments']);

        return view('page.lessons.create', [
            'activeNav' => 'courses',
            'course'    => $lesson->course,
            'lesson'    => $lesson,
        ]);
    }

    /**
     * Update lesson + ganti video / tambah lampiran.
     */
    public function update(UpdateLessonRequest $request, Lesson $lesson): RedirectResponse
    {
        $data = $request->validated();

        // Ganti video jika upload baru
        if ($request->hasFile('video')) {
            // Hapus video lama dari Cloudinary
            if ($lesson->video_public_id) {
                $this->cloudinary->uploadApi()->destroy(
                    $lesson->video_public_id,
                    ['resource_type' => 'video']
                );
            }
            $result                  = $this->cloudinary->uploadApi()->upload(
                $request->file('video')->getRealPath(),
                ['folder' => 'lms/videos', 'resource_type' => 'video']
            );
            $data['video_url']       = $result['secure_url'];
            $data['video_public_id'] = $result['public_id'];
        }

        $filtered = array_filter($data, fn($v) => $v !== null);
        $lesson->update($filtered);

        // Tambah lampiran baru (tidak menghapus yang lama)
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $result = $this->cloudinary->uploadApi()->upload(
                    $file->getRealPath(),
                    ['folder' => 'lms/attachments', 'resource_type' => 'raw']
                );
                $lesson->files()->create([
                    'file_type'            => 'attachment',
                    'file_name'            => $file->getClientOriginalName(),
                    'file_url'             => $result['secure_url'],
                    'cloudinary_public_id' => $result['public_id'],
                    'file_size'            => $file->getSize(),
                ]);
            }
        }

        return redirect()
            ->route('admin.courses.show', $lesson->course_id)
            ->with('success', "Lesson \"{$lesson->title}\" berhasil diperbarui!");
    }

    /**
     * Hapus lesson + video + semua lampiran dari Cloudinary.
     */
    public function destroy(Lesson $lesson): RedirectResponse
    {
        $courseId = $lesson->course_id;

        // Hapus video dari Cloudinary
        if ($lesson->video_public_id) {
            $this->cloudinary->uploadApi()->destroy(
                $lesson->video_public_id,
                ['resource_type' => 'video']
            );
        }

        // Hapus semua lampiran dari Cloudinary
        foreach ($lesson->files as $file) {
            $this->cloudinary->uploadApi()->destroy(
                $file->cloudinary_public_id,
                ['resource_type' => 'raw']
            );
        }

        $title = $lesson->title;
        $lesson->delete(); // cascades ke lesson_files via DB

        return redirect()
            ->route('admin.courses.show', $courseId)
            ->with('success', "Lesson \"{$title}\" berhasil dihapus!");
    }

    /**
     * Hapus satu lampiran spesifik dari lesson.
     */
    public function destroyFile(LessonFile $lessonFile): RedirectResponse
    {
        $courseId = $lessonFile->lesson->course_id;

        $this->cloudinary->uploadApi()->destroy(
            $lessonFile->cloudinary_public_id,
            ['resource_type' => 'raw']
        );

        $lessonFile->delete();

        return back()->with('success', 'Lampiran berhasil dihapus.');
    }
}
