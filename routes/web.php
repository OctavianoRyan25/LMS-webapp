<?php

use App\Http\Controllers\Admin\AssignmentController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\LessonController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

// ── Auth ──────────────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')->name('logout');

Route::redirect('/', '/admin/dashboard');

// ── Admin (Auth required) ─────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function (): void {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/quizzes/all',     [QuizController::class,       'index'])->name('quizzes.index');
    Route::get('/assignments/all', [AssignmentController::class, 'index'])->name('assignments.index');
    Route::get('/exams/all',       [ExamController::class,       'index'])->name('exams.index');

    // ── Course ────────────────────────────────────────────────────────────────
    Route::resource('courses', CourseController::class);

    // ── User ──────────────────────────────────────────────────────────────────
    Route::resource('users', UserController::class);

    // ── Lesson (shallow nested di bawah course) ───────────────────────────────
    Route::resource('courses.lessons', LessonController::class)->shallow();
    Route::delete('/lesson-files/{lessonFile}', [LessonController::class, 'destroyFile'])
        ->name('lesson-files.destroy');

    // ── Quiz (shallow nested di bawah lesson) ─────────────────────────────────
    Route::resource('lessons.quizzes', QuizController::class)->shallow();
    Route::get('/quizzes/{quiz}/analytics', [QuizController::class, 'analytics'])
        ->name('quizzes.analytics');

    // ── Assignment (shallow nested di bawah lesson) ───────────────────────────
    Route::resource('lessons.assignments', AssignmentController::class)->shallow();
    Route::get('/assignments/{assignment}/analytics', [AssignmentController::class, 'analytics'])
        ->name('assignments.analytics');
    Route::post('/assignment-submissions/{submission}/grade', [AssignmentController::class, 'grade'])
        ->name('assignment-submissions.grade');

    // ── Exam (shallow nested di bawah course) ─────────────────────────────────
    Route::resource('courses.exams', ExamController::class)->shallow();
    Route::get('/exams/{exam}/analytics', [ExamController::class, 'analytics'])
        ->name('exams.analytics');

    // ── Misc ──────────────────────────────────────────────────────────────────
    Route::get('/settings', fn() => view('page.settings', ['activeNav' => 'settings']))->name('settings');

    // ── Notifications ─────────────────────────────────────────────────────────
    Route::get('/notifications',                                [NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/{id}/read',                     [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all',                      [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::delete('/notifications/{id}',                        [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::post('/notifications/clear-read',                    [NotificationController::class, 'clearRead'])->name('notifications.clear-read');
});
