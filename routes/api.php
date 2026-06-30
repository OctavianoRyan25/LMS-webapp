<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — Digunakan oleh Flutter App
|--------------------------------------------------------------------------
*/

// ── Public (tanpa auth) ───────────────────────────────────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('/login',    [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

// ── Protected (perlu Bearer token) ───────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me',      [AuthController::class, 'me']);

});
