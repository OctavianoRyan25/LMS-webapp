<?php

use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

// ── Auth Routes (Guest only) ─────────────────────────────────────────────────
Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ── Redirect root → dashboard ────────────────────────────────────────────────
Route::redirect('/', '/admin/dashboard');

// ── Admin Routes (Auth required) ─────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function (): void {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('courses', CourseController::class);

    Route::get('/settings', function () {
        return view('page.settings', ['activeNav' => 'settings']);
    })->name('settings');

    Route::get('/notifications', function () {
        return view('page.notifications', ['activeNav' => 'notifications']);
    })->name('notifications');
});
