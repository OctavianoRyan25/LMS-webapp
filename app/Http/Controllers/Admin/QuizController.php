<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuizRequest;
use App\Http\Requests\UpdateQuizRequest;
use App\Models\Lesson;
use App\Models\Quiz;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class QuizController extends Controller
{
    public function index(Lesson $lesson): RedirectResponse
    {
        return redirect()->route('admin.lessons.edit', $lesson);
    }

    public function create(Lesson $lesson): View
    {
        $lesson->load('course');
        return view('page.quizzes.create', [
            'activeNav' => 'courses',
            'lesson'    => $lesson,
        ]);
    }

    public function store(StoreQuizRequest $request, Lesson $lesson): RedirectResponse
    {
        $data              = $request->validated();
        $data['questions'] = $this->parseQuestions($request);

        $lesson->quizzes()->create($data);

        return redirect()
            ->route('admin.lessons.edit', $lesson)
            ->with('success', 'Kuis berhasil dibuat!');
    }

    public function edit(Quiz $quiz): View
    {
        $quiz->load('lesson.course');
        return view('page.quizzes.create', [
            'activeNav' => 'courses',
            'quiz'      => $quiz,
            'lesson'    => $quiz->lesson,
        ]);
    }

    public function update(UpdateQuizRequest $request, Quiz $quiz): RedirectResponse
    {
        $data              = $request->validated();
        $data['questions'] = $this->parseQuestions($request);

        $quiz->update($data);

        return redirect()
            ->route('admin.lessons.edit', $quiz->lesson_id)
            ->with('success', 'Kuis berhasil diperbarui!');
    }

    public function destroy(Quiz $quiz): RedirectResponse
    {
        $lessonId = $quiz->lesson_id;
        $quiz->delete();

        return redirect()
            ->route('admin.lessons.edit', $lessonId)
            ->with('success', 'Kuis berhasil dihapus!');
    }

    public function analytics(Quiz $quiz): View
    {
        $quiz->load(['lesson.course', 'submissions.user']);

        $submissions = $quiz->submissions()->with('user')->latest()->get();
        $total       = $submissions->count();
        $passed      = $submissions->where('is_passed', true)->count();
        $avgScore    = $total ? round($submissions->avg('score'), 1) : 0;

        // Distribusi skor: <60, 60-74, 75-89, >=90
        $distribution = [
            'fail'    => $submissions->where('score', '<', 60)->count(),
            'low'     => $submissions->whereBetween('score', [60, 74])->count(),
            'medium'  => $submissions->whereBetween('score', [75, 89])->count(),
            'high'    => $submissions->where('score', '>=', 90)->count(),
        ];

        return view('page.quizzes.analytics', [
            'activeNav'    => 'courses',
            'quiz'         => $quiz,
            'submissions'  => $submissions,
            'total'        => $total,
            'passed'       => $passed,
            'avgScore'     => $avgScore,
            'distribution' => $distribution,
        ]);
    }

    /** Bangun array questions dari input form */
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
