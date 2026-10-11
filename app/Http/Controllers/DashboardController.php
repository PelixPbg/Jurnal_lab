<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LabSchedule;
use App\Models\LabJournal;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function admin() {
        // Mengambil statistik dasar
        $totalSiswa = \App\Models\Student::count();
        $totalGuru = \App\Models\Teacher::count();
        $totalLab = \App\Models\Lab::count();
        
        // Menghitung PC yang rusak atau bermasalah
        $pcRusak = \App\Models\Computer::whereIn('status', ['bermasalah', 'rusak'])->count();
        
        // Menghitung laporan masalah yang belum diselesaikan
        $laporanBaru = \App\Models\ProblemReport::where('status', 'reported')->count();

        // Mengembalikan view dengan data yang sesuai nama variabel di dashboard admin
        return view('admin.dashboard', compact(
            'totalSiswa', 'totalGuru', 'totalLab', 'pcRusak', 'laporanBaru'
        ));
    }

    public function guru() {
        $guru = Auth::user()->teacher;
        $today = Carbon::now()->toDateString();

        $schedules = LabSchedule::where('teacher_id', $guru->id)
            ->where('date', $today)
            ->with('lab')
            ->get();

        $jurnalMasuk = LabJournal::whereIn('schedule_id', $schedules->pluck('id'))->count();
        $pcBermasalah = \App\Models\Computer::whereIn('status', ['bermasalah', 'rusak'])->count();
        $laporanPending = \App\Models\ProblemReport::where('status', 'reported')->count();

        return view('guru.dashboard', compact('guru', 'schedules', 'jurnalMasuk', 'pcBermasalah', 'laporanPending'));
    }

    public function siswa() {
        $siswa = Auth::user()->student()->with('classRoom')->first();
        $today = Carbon::now()->toDateString();

        $schedules = LabSchedule::where('class_id', $siswa->class_id)
            ->where('date', $today)
            ->with(['lab', 'teacher'])
            ->get();

        $journals = LabJournal::where('student_id', $siswa->id)
            ->whereIn('schedule_id', $schedules->pluck('id'))
            ->get()
            ->keyBy('schedule_id');

        // Statistik (semua jurnal milik siswa ini)
        $semuaJurnal = LabJournal::where('student_id', $siswa->id)->withCount('problemReports')->get();
        $jurnalSelesai = $semuaJurnal->where('status', 'approved')->count();
        $menungguVerifikasi = $semuaJurnal->where('status', 'submitted')->count();
        $laporanMasalah = $semuaJurnal->sum('problem_reports_count');

        return view('siswa.dashboard', compact(
            'siswa', 'schedules', 'journals',
            'jurnalSelesai', 'menungguVerifikasi', 'laporanMasalah'
        ));
    }
}