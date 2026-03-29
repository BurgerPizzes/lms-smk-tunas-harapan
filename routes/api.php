<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    ApiAuthController,
    ApiKelasController,
    ApiMateriController,
    ApiTugasController,
    ApiSubmissionController,
    ApiGradeController,
    ApiNotificationController
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| All API routes return JSON responses. Public routes are available
| without authentication, while protected routes require a valid
| Sanctum token.
|
*/

/*
|--------------------------------------------------------------------------
| Public API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('api')->name('api.')->group(function () {

    // Authentication
    Route::post('/login', [ApiAuthController::class, 'login'])->name('login');
    Route::post('/register', [ApiAuthController::class, 'register'])->name('register');
});

/*
|--------------------------------------------------------------------------
| Authenticated API Routes (Sanctum)
|--------------------------------------------------------------------------
*/

Route::prefix('api')->name('api.')->middleware('auth:sanctum')->group(function () {

    // ------------------------------------------------------------------
    // Authentication
    // ------------------------------------------------------------------
    Route::post('/logout', [ApiAuthController::class, 'logout'])->name('logout');
    Route::get('/me', [ApiAuthController::class, 'me'])->name('me');

    // ------------------------------------------------------------------
    // Kelas (Classes) Resources
    // ------------------------------------------------------------------
    Route::apiResource('kelas', ApiKelasController::class);
    Route::post('/kelas/join', [ApiKelasController::class, 'join'])->name('kelas.join');
    Route::get('/kelas/{id}/materi', [ApiMateriController::class, 'indexByKelas'])->name('kelas.materi.index');
    Route::get('/kelas/{id}/tugas', [ApiTugasController::class, 'indexByKelas'])->name('kelas.tugas.index');

    // ------------------------------------------------------------------
    // Materi (Materials)
    // ------------------------------------------------------------------
    Route::get('/materi/{id}', [ApiMateriController::class, 'show'])->name('materi.show');

    // ------------------------------------------------------------------
    // Tugas (Assignments)
    // ------------------------------------------------------------------
    Route::get('/tugas/{id}', [ApiTugasController::class, 'show'])->name('tugas.show');

    // ------------------------------------------------------------------
    // Submissions
    // ------------------------------------------------------------------
    Route::get('/tugas/{tugasId}/submissions', [ApiSubmissionController::class, 'index'])->name('submissions.index');
    Route::get('/submissions/{id}', [ApiSubmissionController::class, 'show'])->name('submissions.show');
    Route::post('/submissions/{id}/grade', [ApiSubmissionController::class, 'grade'])->name('submissions.grade');

    // ------------------------------------------------------------------
    // Grades (Nilai)
    // ------------------------------------------------------------------
    Route::get('/kelas/{kelasId}/mapel/{mapelId}/grades', [ApiGradeController::class, 'index'])->name('grades.index');
    Route::get('/kelas/{kelasId}/grades/export', [ApiGradeController::class, 'export'])->name('grades.export');

    // ------------------------------------------------------------------
    // Notifications
    // ------------------------------------------------------------------
    Route::get('/notifications', [ApiNotificationController::class, 'index'])->name('notifications.index');
    Route::put('/notifications/{id}/read', [ApiNotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::put('/notifications/read-all', [ApiNotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::get('/notifications/unread-count', [ApiNotificationController::class, 'unreadCount'])->name('notifications.unread-count');
});
