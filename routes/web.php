<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LabScheduleController;
use App\Http\Controllers\StudentJournalController;
use App\Http\Controllers\TeacherJournalController;
use App\Http\Controllers\GuruPageController;
use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\AdminLabController;
use App\Http\Controllers\AdminComputerController;
use App\Http\Controllers\AdminClassController;
use App\Http\Controllers\AdminTeacherController;
use App\Http\Controllers\AdminStudentController;
use App\Http\Controllers\ProfileController;
use App\Notifications\JadwalBaruNotification;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Group Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin']);

    // Rute Monitoring Laporan
    Route::get('/reports', [AdminReportController::class, 'index']);
    Route::post('/reports/{id}/resolve', [AdminReportController::class, 'resolve']);

    // Rute Kelola Data Lab
    Route::get('/labs', [AdminLabController::class, 'index']);
    Route::get('/labs/create', [AdminLabController::class, 'create']);
    Route::post('/labs', [AdminLabController::class, 'store']);
    Route::get('/labs/{id}/edit', [AdminLabController::class, 'edit']);
    Route::put('/labs/{id}', [AdminLabController::class, 'update']);
    Route::delete('/labs/{id}', [AdminLabController::class, 'destroy']);

    // Rute Kelola Data PC
    Route::get('/computers', [AdminComputerController::class, 'index']);
    Route::get('/computers/create', [AdminComputerController::class, 'create']);
    Route::post('/computers', [AdminComputerController::class, 'store']);
    Route::get('/computers/{id}/edit', [AdminComputerController::class, 'edit']);
    Route::put('/computers/{id}', [AdminComputerController::class, 'update']);
    Route::delete('/computers/{id}', [AdminComputerController::class, 'destroy']);

    // Rute Kelola Data Kelas
    Route::get('/classes', [AdminClassController::class, 'index']);
    Route::get('/classes/create', [AdminClassController::class, 'create']);
    Route::post('/classes', [AdminClassController::class, 'store']);
    Route::get('/classes/{id}/edit', [AdminClassController::class, 'edit']);
    Route::put('/classes/{id}', [AdminClassController::class, 'update']);
    Route::delete('/classes/{id}', [AdminClassController::class, 'destroy']);

    // Rute Kelola Data Guru
    Route::get('/teachers', [AdminTeacherController::class, 'index']);
    Route::get('/teachers/create', [AdminTeacherController::class, 'create']);
    Route::get('/teachers/import', [AdminTeacherController::class, 'importForm']);
    Route::post('/teachers/import', [AdminTeacherController::class, 'import']);
    Route::get('/teachers/template', [AdminTeacherController::class, 'template']);
    Route::post('/teachers', [AdminTeacherController::class, 'store']);
    Route::get('/teachers/{id}/edit', [AdminTeacherController::class, 'edit']);
    Route::put('/teachers/{id}', [AdminTeacherController::class, 'update']);
    Route::delete('/teachers/{id}', [AdminTeacherController::class, 'destroy']);

    // Rute Kelola Data Siswa
    Route::get('/students', [AdminStudentController::class, 'index']);
    Route::get('/students/create', [AdminStudentController::class, 'create']);
    Route::get('/students/import', [AdminStudentController::class, 'importForm']);
    Route::post('/students/import', [AdminStudentController::class, 'import']);
    Route::get('/students/template', [AdminStudentController::class, 'template']);
    Route::post('/students', [AdminStudentController::class, 'store']);
    Route::get('/students/{id}/edit', [AdminStudentController::class, 'edit']);
    Route::put('/students/{id}', [AdminStudentController::class, 'update']);
    Route::delete('/students/{id}', [AdminStudentController::class, 'destroy']);
    
    // Rute Kondisi Lab (opsional jika dibutuhkan)
    Route::get('/kondisi-lab', [AdminLabController::class, 'kondisiLab']);
});

// Group Guru (Sudah otomatis punya awalan /guru karena menggunakan prefix('guru'))
Route::middleware(['auth', 'role:guru'])->prefix('guru')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'guru']);

    // Rute Lengkap Jadwal Lab Guru (CRUD)
    Route::get('/jadwal', [LabScheduleController::class, 'index']);
    Route::get('/jadwal/create', [LabScheduleController::class, 'create']);
    Route::post('/jadwal', [LabScheduleController::class, 'store']);
    Route::get('/jadwal/{id}/edit', [LabScheduleController::class, 'edit']);
    Route::put('/jadwal/{id}', [LabScheduleController::class, 'update']);
    Route::delete('/jadwal/{id}', [LabScheduleController::class, 'destroy']);

    // Jurnal Siswa
    Route::get('/jurnal', [TeacherJournalController::class, 'index']);
    Route::get('/jurnal/{id}', [TeacherJournalController::class, 'show']);
    Route::post('/jurnal/{id}/verify', [TeacherJournalController::class, 'verify']);

    // Laporan Masalah & Kondisi Lab
    Route::get('/laporan', [GuruPageController::class, 'laporan']);
    Route::get('/kondisi-lab', [GuruPageController::class, 'kondisiLab']);
});

// Group Siswa
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'siswa']);

    // Rute Jadwal Siswa (Ditambahkan di sini)
    Route::get('/jadwal', [StudentJournalController::class, 'scheduleList']);

    // Rute Check-In
    Route::get('/check-in/{schedule_id}', [StudentJournalController::class, 'checkInForm']);
    Route::post('/check-in/{schedule_id}', [StudentJournalController::class, 'processCheckIn']);

    // Rute Check-Out
    Route::get('/check-out/{journal_id}', [StudentJournalController::class, 'checkOutForm']);
    Route::post('/check-out/{journal_id}', [StudentJournalController::class, 'processCheckOut']);

    // Rute Riwayat Jurnal
    Route::get('/riwayat', fn () => redirect('/siswa/jadwal'));
    Route::get('/riwayat/{id}', [StudentJournalController::class, 'show']);

    // Rute Laporan Masalah Siswa
    Route::get('/laporan', [StudentJournalController::class, 'reportIndex']);
    Route::post('/laporan', [StudentJournalController::class, 'reportStore']);
});

// Group untuk semua user yang sudah login (Bisa diakses semua role)
Route::middleware(['auth'])->group(function () {

    // Halaman Profil
    Route::get('/profil', [ProfileController::class, 'index']);
    Route::post('/profil', [ProfileController::class, 'update']);

    // Halaman Daftar Notifikasi
    Route::get('/notifikasi', function() {
        $user = Illuminate\Support\Facades\Auth::user();

        // Ambil semua notifikasi
        $notifikasinya = $user->notifications;

        // Tandai sudah dibaca setelah datanya diambil
        $user->unreadNotifications->markAsRead();

        return view('notifikasi.index', compact('notifikasinya'));
    });
});