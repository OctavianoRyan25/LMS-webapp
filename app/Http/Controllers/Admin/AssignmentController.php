<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAssignmentRequest;
use App\Http\Requests\UpdateAssignmentRequest;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Lesson;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class AssignmentController extends Controller
{
    public function index(Lesson $lesson): RedirectResponse
    {
        return redirect()->route('admin.lessons.edit', $lesson);
    }

    public function create(Lesson $lesson): View
    {
        $lesson->load('course');
        return view('page.assignments.create', [
            'activeNav' => 'courses',
            'lesson'    => $lesson,
        ]);
    }

    public function store(StoreAssignmentRequest $request, Lesson $lesson): RedirectResponse
    {
        $lesson->assignments()->create($request->validated());

        return redirect()
            ->route('admin.lessons.edit', $lesson)
            ->with('success', 'Tugas berhasil dibuat!');
    }

    public function edit(Assignment $assignment): View
    {
        $assignment->load('lesson.course');
        return view('page.assignments.create', [
            'activeNav'  => 'courses',
            'assignment' => $assignment,
            'lesson'     => $assignment->lesson,
        ]);
    }

    public function update(UpdateAssignmentRequest $request, Assignment $assignment): RedirectResponse
    {
        $assignment->update($request->validated());

        return redirect()
            ->route('admin.lessons.edit', $assignment->lesson_id)
            ->with('success', 'Tugas berhasil diperbarui!');
    }

    public function destroy(Assignment $assignment): RedirectResponse
    {
        $lessonId = $assignment->lesson_id;
        $assignment->delete();

        return redirect()
            ->route('admin.lessons.edit', $lessonId)
            ->with('success', 'Tugas berhasil dihapus!');
    }

    public function analytics(Assignment $assignment): View
    {
        $assignment->load(['lesson.course', 'submissions.user']);

        $submissions  = $assignment->submissions()->with('user')->latest()->get();
        $total        = $submissions->count();
        $graded       = $submissions->whereNotNull('score')->count();
        $ungraded     = $total - $graded;
        $avgScore     = $graded ? round($submissions->whereNotNull('score')->avg('score'), 1) : 0;

        return view('page.assignments.analytics', [
            'activeNav'   => 'courses',
            'assignment'  => $assignment,
            'submissions' => $submissions,
            'total'       => $total,
            'graded'      => $graded,
            'ungraded'    => $ungraded,
            'avgScore'    => $avgScore,
        ]);
    }

    /** Beri nilai submission (dipanggil via POST dari halaman analytics) */
    public function grade(Request $request, AssignmentSubmission $submission): RedirectResponse
    {
        $request->validate([
            'score'    => ['required', 'integer', 'min:0', 'max:100'],
            'feedback' => ['nullable', 'string', 'max:1000'],
        ]);

        $submission->update([
            'score'     => $request->score,
            'feedback'  => $request->feedback,
            'graded_at' => now(),
        ]);

        return back()->with('success', "Nilai untuk {$submission->user->name} berhasil disimpan!");
    }
}
