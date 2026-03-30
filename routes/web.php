<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\{
    ProfileController,
    NotificationController,
    CommentController,
    FileController
};
use App\Http\Controllers\Admin\{
    AdminDashboardController,
    AdminUserController,
    AdminJurusanController,
    AdminMapelController,
    AdminKelasController,
    AdminTahunAjaranController,
    AdminGuruMapelController,
    AdminController
};
use App\Http\Controllers\Guru\{
    GuruDashboardController,
    GuruKelasController,
    GuruMateriController,
    GuruTugasController,
    GuruPenilaianController,
    GuruAbsensiController,
    GuruDiskusiController,
    GuruQuizController
};
use App\Http\Controllers\Siswa\{
    SiswaDashboardController,
    SiswaKelasController,
    SiswaMateriController,
    SiswaTugasController,
    SiswaNilaiController,
    SiswaAbsensiController,
    SiswaDiskusiController,
    SiswaQuizController
};

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');

    // Password Reset
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // ------------------------------------------------------------------
    // Generic Dashboard Route (role-based redirect)
    // ------------------------------------------------------------------
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('guru')) {
            return redirect()->route('guru.dashboard');
        } elseif ($user->hasRole('siswa')) {
            return redirect()->route('siswa.dashboard');
        }
        return redirect()->route('login');
    })->name('dashboard');

    // ------------------------------------------------------------------
    // Generic Kelas Show Route (role-based redirect)
    // ------------------------------------------------------------------
    Route::get('/kelas/{id}', function ($id) {
        $user = auth()->user();
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.kelas.show', $id);
        } elseif ($user->hasRole('guru')) {
            return redirect()->route('guru.kelas.show', $id);
        } elseif ($user->hasRole('siswa')) {
            return redirect()->route('siswa.kelas.show', $id);
        }
        return redirect()->route('login');
    })->name('kelas.show');

    // ------------------------------------------------------------------
    // Profile Routes
    // ------------------------------------------------------------------
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo');
    Route::delete('/profile/photo', [ProfileController::class, 'removePhoto'])->name('profile.remove-avatar');

    // ------------------------------------------------------------------
    // Notification Routes
    // ------------------------------------------------------------------
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::put('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::put('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::delete('/notifications/{id}/delete', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');

    // ------------------------------------------------------------------
    // Comment Routes
    // ------------------------------------------------------------------
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{id}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::get('/comments/{type}/{id}', [CommentController::class, 'getComments'])->name('comments.getByType');

    // ------------------------------------------------------------------
    // File Routes
    // ------------------------------------------------------------------
    Route::post('/files/upload', [FileController::class, 'upload'])->name('files.upload');
    Route::get('/files/{path}/download', [FileController::class, 'download'])->name('files.download')->where('path', '.*');
    Route::delete('/files/{path}/delete', [FileController::class, 'delete'])->name('files.delete')->where('path', '.*');
    Route::get('/files/{path}/view', [FileController::class, 'show'])->name('files.view')->where('path', '.*');

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // User Management
        Route::resource('users', AdminUserController::class);
        Route::put('users/{id}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::get('users/export', [AdminUserController::class, 'export'])->name('users.export');

        // Jurusan (Majors)
        Route::resource('jurusan', AdminJurusanController::class);
        Route::put('jurusan/{id}/toggle-status', [AdminJurusanController::class, 'toggleStatus'])->name('jurusan.toggle-status');

        // Mata Pelajaran (Subjects)
        Route::resource('mapel', AdminMapelController::class);

        // Kelas (Classes)
        Route::resource('kelas', AdminKelasController::class);
        Route::get('kelas/{id}/members', [AdminKelasController::class, 'manageEnrollment'])->name('kelas.members');
        Route::post('kelas/{id}/enroll', [AdminKelasController::class, 'enrollSiswa'])->name('kelas.enroll');
        Route::delete('kelas/{id}/remove-member/{userId}', [AdminKelasController::class, 'removeMember'])->name('kelas.remove-member');

        // Tahun Ajaran (Academic Years)
        Route::resource('tahun-ajaran', AdminTahunAjaranController::class);
        Route::put('tahun-ajaran/{id}/set-active', [AdminTahunAjaranController::class, 'setActive'])->name('tahun-ajaran.set-active');

        // Guru Mapel (Teacher-Subject Assignments)
        Route::resource('guru-mapel', AdminGuruMapelController::class);

        // System Management
        Route::get('/logs', [AdminController::class, 'logs'])->name('logs');
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::put('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
        Route::get('/backup', [AdminController::class, 'showBackup'])->name('backup');
        Route::post('/backup', [AdminController::class, 'backup'])->name('backup.run');
        Route::post('/clear-cache', [AdminController::class, 'clearCache'])->name('clear-cache');
    });

    /*
    |--------------------------------------------------------------------------
    | Guru (Teacher) Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:guru')->prefix('guru')->name('guru.')->group(function () {

        // Dashboard
        Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('dashboard');

        // Kelas (Classes)
        Route::resource('kelas', GuruKelasController::class);
        Route::get('kelas/{id}/members', [GuruKelasController::class, 'members'])->name('kelas.members');
        Route::delete('kelas/{id}/remove-member/{userId}', [GuruKelasController::class, 'removeMember'])->name('kelas.remove-member');

        // Materi (Materials) - Global listing (no kelasId required)
        Route::get('materi', function () { return redirect()->route('guru.kelas.index'); })->name('materi.index');
        // Materi (Materials) - Scoped to Kelas
        Route::get('kelas/{kelasId}/materi', [GuruMateriController::class, 'index'])->name('kelas.materi.index');
        Route::get('kelas/{kelasId}/materi/create', [GuruMateriController::class, 'create'])->name('kelas.materi.create');
        Route::post('kelas/{kelasId}/materi', [GuruMateriController::class, 'store'])->name('kelas.materi.store');
        Route::put('materi/{id}/toggle-publish', [GuruMateriController::class, 'togglePublish'])->name('materi.toggle-publish');
        Route::post('materi/reorder', [GuruMateriController::class, 'reorder'])->name('materi.reorder');
        Route::resource('materi', GuruMateriController::class)->except(['index', 'create', 'store']);

        // Tugas (Assignments) - Global listing (no kelasId required)
        Route::get('tugas', function () { return redirect()->route('guru.kelas.index'); })->name('tugas.index');
        // Tugas (Assignments) - Scoped to Kelas
        Route::get('kelas/{kelasId}/tugas', [GuruTugasController::class, 'index'])->name('kelas.tugas.index');
        Route::get('kelas/{kelasId}/tugas/create', [GuruTugasController::class, 'create'])->name('kelas.tugas.create');
        Route::post('kelas/{kelasId}/tugas', [GuruTugasController::class, 'store'])->name('kelas.tugas.store');
        Route::resource('tugas', GuruTugasController::class)->except(['index', 'create', 'store']);

        // Penilaian (Grading) - Global listing (no tugasId required)
        Route::get('penilaian', function () { return redirect()->route('guru.kelas.index'); })->name('penilaian.index');
        // Penilaian (Grading) - Scoped to Tugas
        Route::get('tugas/{tugasId}/penilaian', [GuruPenilaianController::class, 'index'])->name('tugas.penilaian.index');
        Route::put('penilaian/{submissionId}/grade', [GuruPenilaianController::class, 'grade'])->name('penilaian.grade');
        Route::post('penilaian/grade-bulk', [GuruPenilaianController::class, 'gradeBulk'])->name('penilaian.grade-bulk');
        Route::get('penilaian/export/{kelasId}/{mapelId}', [GuruPenilaianController::class, 'exportNilai'])->name('penilaian.export');
        Route::get('penilaian/recap/{kelasId}', [GuruPenilaianController::class, 'recapNilai'])->name('penilaian.recap');

        // Absensi (Attendance) - Global listing (no kelasId required)
        Route::get('absensi', function () { return redirect()->route('guru.kelas.index'); })->name('absensi.index');
        // Absensi (Attendance) - Scoped to Kelas
        Route::get('kelas/{kelasId}/absensi', [GuruAbsensiController::class, 'index'])->name('kelas.absensi.index');
        Route::get('kelas/{kelasId}/absensi/create', [GuruAbsensiController::class, 'create'])->name('kelas.absensi.create');
        Route::post('kelas/{kelasId}/absensi', [GuruAbsensiController::class, 'store'])->name('kelas.absensi.store');
        Route::get('absensi/{id}', [GuruAbsensiController::class, 'show'])->name('absensi.show');
        Route::put('absensi/{id}', [GuruAbsensiController::class, 'update'])->name('absensi.update');
        Route::get('absensi/recap/{kelasId}', [GuruAbsensiController::class, 'recap'])->name('absensi.recap');
        Route::get('absensi/export/{kelasId}', [GuruAbsensiController::class, 'exportRecap'])->name('absensi.export');

        // Diskusi (Discussions)
        Route::post('diskusi', [GuruDiskusiController::class, 'store'])->name('diskusi.store');
        Route::put('diskusi/{id}', [GuruDiskusiController::class, 'update'])->name('diskusi.update');
        Route::delete('diskusi/{id}', [GuruDiskusiController::class, 'destroy'])->name('diskusi.destroy');

        // Quiz - Global listing (no kelasId required)
        Route::get('quiz', function () { return redirect()->route('guru.kelas.index'); })->name('quiz.index');
        // Quiz - Scoped to Kelas
        Route::get('kelas/{kelasId}/quiz', [GuruQuizController::class, 'index'])->name('kelas.quiz.index');
        Route::get('kelas/{kelasId}/quiz/create', [GuruQuizController::class, 'create'])->name('kelas.quiz.create');
        Route::post('kelas/{kelasId}/quiz', [GuruQuizController::class, 'store'])->name('kelas.quiz.store');
        Route::get('quiz/{id}', [GuruQuizController::class, 'show'])->name('quiz.show');
        Route::post('quiz/{id}/add-question', [GuruQuizController::class, 'addQuestion'])->name('quiz.add-question');
        Route::put('quiz/{quizId}/question/{questionId}', [GuruQuizController::class, 'updateQuestion'])->name('quiz.update-question');
        Route::delete('quiz/{quizId}/question/{questionId}', [GuruQuizController::class, 'deleteQuestion'])->name('quiz.delete-question');
        Route::get('quiz/{id}/results', [GuruQuizController::class, 'results'])->name('quiz.results');
    });

    /*
    |--------------------------------------------------------------------------
    | Siswa (Student) Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:siswa')->prefix('siswa')->name('siswa.')->group(function () {

        // Dashboard
        Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');

        // Kelas (Classes)
        Route::get('kelas', [SiswaKelasController::class, 'index'])->name('kelas.index');
        Route::get('kelas/join', [SiswaKelasController::class, 'joinClass'])->name('kelas.join');
        Route::post('kelas/join', [SiswaKelasController::class, 'join'])->name('kelas.storeJoin');
        Route::get('kelas/{id}', [SiswaKelasController::class, 'show'])->name('kelas.show');
        Route::delete('kelas/{id}/leave', [SiswaKelasController::class, 'leave'])->name('kelas.leave');

        // Materi (Materials)
        Route::get('kelas/{kelasId}/materi', [SiswaMateriController::class, 'index'])->name('materi.index');
        Route::get('materi/{id}', [SiswaMateriController::class, 'show'])->name('materi.show');

        // Tugas (Assignments)
        Route::get('kelas/{kelasId}/tugas', [SiswaTugasController::class, 'index'])->name('tugas.index');
        Route::get('tugas/{id}', [SiswaTugasController::class, 'show'])->name('tugas.show');
        Route::post('tugas/{id}/submit', [SiswaTugasController::class, 'submit'])->name('tugas.submit');
        Route::get('submissions', [SiswaTugasController::class, 'mySubmissions'])->name('submissions.index');
        Route::get('submissions/{id}', [SiswaTugasController::class, 'showSubmission'])->name('submissions.show');

        // Nilai (Grades)
        Route::get('nilai', [SiswaNilaiController::class, 'index'])->name('nilai.index');
        Route::get('nilai/kelas/{kelasId}', [SiswaNilaiController::class, 'byClass'])->name('nilai.by-kelas');
        Route::get('nilai/kelas/{kelasId}/mapel/{mapelId}', [SiswaNilaiController::class, 'byMapel'])->name('nilai.by-kelas-mapel');

        // Absensi (Attendance)
        Route::get('absensi', [SiswaAbsensiController::class, 'index'])->name('absensi.index');
        Route::get('absensi/kelas/{kelasId}', [SiswaAbsensiController::class, 'byClass'])->name('absensi.by-kelas');

        // Diskusi (Discussions)
        Route::post('diskusi', [SiswaDiskusiController::class, 'store'])->name('diskusi.store');
        Route::put('diskusi/{id}', [SiswaDiskusiController::class, 'update'])->name('diskusi.update');
        Route::delete('diskusi/{id}', [SiswaDiskusiController::class, 'destroy'])->name('diskusi.destroy');

        // Quiz
        Route::get('kelas/{kelasId}/quiz', [SiswaQuizController::class, 'index'])->name('quiz.index');
        Route::post('quiz/{id}/start', [SiswaQuizController::class, 'start'])->name('quiz.start');
        Route::post('quiz/submit-answer', [SiswaQuizController::class, 'submitAnswer'])->name('quiz.submit-answer');
        Route::post('quiz/{attemptId}/finish', [SiswaQuizController::class, 'finish'])->name('quiz.finish');
        Route::get('quiz/{attemptId}/result', [SiswaQuizController::class, 'result'])->name('quiz.result');
    });
});
