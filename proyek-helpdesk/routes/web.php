<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminGedung\ReportController;
use App\Http\Controllers\HeadIT\ReportController as HeadITReportController;
use App\Http\Controllers\Teknisi\TaskController;
use App\Http\Controllers\HeadIT\TechnicianController;
use App\Http\Controllers\NotificationController; // Pastikan ini ada

/*
|--------------------------------------------------------------------------
| Rute Autentikasi
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';


/*
|--------------------------------------------------------------------------
| Rute Publik
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect('login');
});


/*
|--------------------------------------------------------------------------
| Rute yang Dilindungi (Wajib Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    | Rute Dashboard Universal
    */
    Route::get('/dashboard', function () {
        // [PERBAIKAN] Rute notifikasi DIPINDAHKAN KELUAR dari sini
        $role = auth()->user()->role;

        if ($role == 'admin_gedung') {
            return redirect()->route('admin.dashboard');
        } elseif ($role == 'kepala_it') {
            return redirect()->route('kepala.dashboard');
        } elseif ($role == 'teknisi') {
            return redirect()->route('teknisi.dashboard');
        } else {
            abort(404, 'Dashboard tidak ditemukan');
        }
    })->name('dashboard');

    /*
    | Rute Profil
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    | [BARU] Rute Notifikasi
    */
    Route::get('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    // [BARU] Rute untuk tandai semua sudah dibaca
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
    // [BARU] Rute untuk halaman semua notifikasi
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');


    // ==================================================================
    // GRUP RUTE BERDASARKAN ROLE (Peran)
    // ==================================================================

    /*
    | RUTE KHUSUS ADMIN GEDUNG
    */
    Route::middleware(['role:admin_gedung'])->prefix('admin-gedung')->name('admin.')->group(function () {
        Route::get('/dashboard', [ReportController::class, 'index'])->name('dashboard');
        Route::get('/report/create', [ReportController::class, 'create'])->name('report.create');
        Route::post('/report', [ReportController::class, 'store'])->name('report.store');
        Route::get('/report/{report}/rate', [ReportController::class, 'rateForm'])->name('report.rateForm');
        Route::post('/report/{report}/rate', [ReportController::class, 'storeRating'])->name('report.storeRating');
    });

    /*
    | RUTE KHUSUS KEPALA IT
    */
    Route::middleware(['role:kepala_it'])->prefix('kepala-it')->name('kepala.')->group(function () {
        Route::get('/dashboard', [HeadITReportController::class, 'index'])->name('dashboard');
        Route::get('/report/{report}/manage', [HeadITReportController::class, 'manage'])->name('report.manage');
        Route::post('/report/{report}/update', [HeadITReportController::class, 'update'])->name('report.update');
        // Export/Print view for completed/rated reports
        Route::get('/report/{report}/print', [HeadITReportController::class, 'printView'])->name('report.print');
        Route::resource('technicians', TechnicianController::class);
    });

    /*
    | RUTE KHUSUS TEKNISI
    */
    Route::middleware(['role:teknisi'])->prefix('teknisi')->name('teknisi.')->group(function () {
        Route::get('/dashboard', [TaskController::class, 'index'])->name('dashboard');
        Route::post('/task/{report}/start', [TaskController::class, 'start'])->name('task.start');
        Route::get('/task/{report}/complete', [TaskController::class, 'completeForm'])->name('task.completeForm');
        Route::post('/task/{report}/complete', [TaskController::class, 'storeCompletion'])->name('task.storeCompletion');
        Route::post('/task/{report}/claim', [TaskController::class, 'claim'])->name('task.claim');
    });

}); // <-- Akhir dari grup middleware('auth')
