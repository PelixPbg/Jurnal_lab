<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LabJournal;
use App\Models\LabSchedule;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TeacherJournalController extends Controller
{
    // Menampilkan daftar jurnal dari jadwal yang dibuat oleh guru yang login
    public function index()
    {
        $teacher = Auth::user()->teacher;
        
        // Ambil ID jadwal milik guru ini
        $scheduleIds = LabSchedule::where('teacher_id', $teacher->id)->pluck('id');

        // Ambil jurnal yang berelasi dengan jadwal tersebut, urutkan yang butuh verifikasi (submitted) di atas
        $journals = LabJournal::whereIn('schedule_id', $scheduleIds)
            ->with(['schedule.classRoom', 'student'])
            ->orderByRaw("FIELD(status, 'submitted', 'revision', 'approved', 'draft')")
            ->orderBy('created_at', 'desc')
            ->get();

        return view('guru.journals.index', compact('journals'));
    }

    // Menampilkan detail jurnal dan form verifikasi
    public function show($id)
    {
        $teacher = Auth::user()->teacher;
        
        $journal = LabJournal::with(['schedule.classRoom', 'schedule.lab', 'student', 'checklists', 'problemReports'])
            ->findOrFail($id);

        // Pastikan jurnal ini milik jadwal guru yang sedang login
        if ($journal->schedule->teacher_id !== $teacher->id) {
            abort(403, 'Akses Ditolak. Jurnal ini bukan dari kelas Anda.');
        }

        return view('guru.journals.show', compact('journal'));
    }

    // Memproses verifikasi (Setujui atau Minta Perbaikan)
    public function verify(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,revision',
            'teacher_notes' => 'nullable|string'
        ]);

        $journal = LabJournal::findOrFail($id);
        
        $journal->update([
            'status' => $request->status,
            'teacher_notes' => $request->teacher_notes,
            'verified_at' => Carbon::now(),
        ]);

        $pesan = $request->status === 'approved' ? 'Jurnal berhasil disetujui.' : 'Jurnal dikembalikan ke siswa untuk perbaikan.';

        return redirect('/guru/jurnal')->with('success', $pesan);
    }
}