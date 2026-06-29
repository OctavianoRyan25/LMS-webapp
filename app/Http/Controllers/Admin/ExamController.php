<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExamRequest;
use App\Http\Requests\UpdateExamRequest;
use App\Models\Course;
use App\Models\Exam;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class ExamController extends Controller
{
    public function index(Course $course): RedirectResponse
    {
        return redirect()->route('admin.courses.show', $course);
    }

    public function create(Course $course): View
    {
        return view('page.exams.create', [
            'activeNav' => 'courses',
            'course'    => $course,
        ]);
    }

    public function store(StoreExamRequest $request, Course $course): RedirectResponse
    {
        $data              = $request->validated();
        $data['questions'] = $this->parseQuestions($request);

        $course->exams()->create($data);

        return redirect()
            ->route('admin.courses.show', $course)
            ->with('success', 'Ujian berhasil dibuat!');
    }

    public function edit(Exam $exam): View
    {
        $exam->load('course');
        return view('page.exams.create', [
            'activeNav' => 'courses',
            'exam'      => $exam,
            'course'    => $exam->course,
        ]);
    }

    public function update(UpdateExamRequest $request, Exam $exam): RedirectResponse
    {
        $data              = $request->validated();
        $data['questions'] = $this->parseQuestions($request);

        $exam->update($data);

        return redirect()
            ->route('admin.courses.show', $exam->course_id)
            ->with('success', 'Ujian berhasil diperbarui!');
    }

    public function destroy(Exam $exam): RedirectResponse
    {
        $courseId = $exam->course_id;
        $exam->delete();

        return redirect()
            ->route('admin.courses.show', $courseId)
            ->with('success', 'Ujian berhasil dihapus!');
    }

    public function analytics(Exam $exam): View
    {
        $exam->load(['course', 'submissions.user']);

        $submissions = $exam->submissions()->with('user')->latest()->get();
        $total       = $submissions->count();
        $passed      = $submissions->where('is_passed', true)->count();
        $avgScore    = $total ? round($submissions->avg('score'), 1) : 0;

        $distribution = [
            'fail'   => $submissions->where('score', '<', 60)->count(),
            'low'    => $submissions->whereBetween('score', [60, 74])->count(),
            'medium' => $submissions->whereBetween('score', [75, 89])->count(),
            'high'   => $submissions->where('score', '>=', 90)->count(),
        ];

        return view('page.exams.analytics', [
            'activeNav'    => 'courses',
            'exam'         => $exam,
            'submissions'  => $submissions,
            'total'        => $total,
            'passed'       => $passed,
            'avgScore'     => $avgScore,
            'distribution' => $distribution,
        ]);
    }

    private function parseQuestions(\Illuminate\Http\Request $request): array
    {
        $questions = [];
        $qTexts    = $request->input('q_text', []);
        $qOptions  = $request->input('q_options', []);
        $qCorrects = $request->input('q_correct', []);

        foreach ($qTexts as $i => $text) {
            if (trim($text) === '') continue;
            $questions[] = [
                'question' => $text,
                'options'  => array_values(array_filter($qOptions[$i] ?? [], fn($o) => trim($o) !== '')),
                'correct'  => (int) ($qCorrects[$i] ?? 0),
            ];
        }

        return $questions;
    }
}
