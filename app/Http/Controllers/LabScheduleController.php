<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LabSchedule;
use App\Models\Lab;
use App\Models\ClassRoom;
use Illuminate\Support\Facades\Auth;

class LabScheduleController extends Controller
{
    public function index()
    {
        $teacher = Auth::user()->teacher;
        // Menampilkan jadwal yang dibuat oleh guru yang sedang login
        $schedules = LabSchedule::where('teacher_id', $teacher->id)
            ->with(['lab', 'classRoom'])
            ->orderBy('date', 'desc')
            ->get();
            
        return view('guru.schedules.index', compact('schedules'));
    }

    public function create(Request $request)
    {
        $labs = Lab::all();
        $classes = ClassRoom::all();

        // Dibuka dari popup: kirim isi formnya saja, tanpa layout halaman
        if ($request->ajax()) {
            return view('guru.schedules._form', ['labs' => $labs, 'classes' => $classes, 'modal' => true]);
        }

        return view('guru.schedules.create', compact('labs', 'classes'));
    }

    public function store(Request $request)
    {
        // Tangkap data dengan fleksibel mengantisipasi berbagai nama atribut di HTML form
        $labId       = $request->input('lab_id') ?? $request->input('laboratory_id');
        $classRoomId = $request->input('class_room_id') ?? $request->input('class_id') ?? $request->input('classroom_id') ?? $request->input('kelas_id');
        $subject     = $request->input('subject') ?? $request->input('mapel');
        $date        = $request->input('date') ?? $request->input('tanggal');
        $startTime   = $request->input('start_time') ?? $request->input('jam_mulai');
        $endTime     = $request->input('end_time') ?? $request->input('jam_selesai');
        $description = $request->input('description') ?? $request->input('keterangan');

        // Validasi manual yang ramah dan langsung mendeteksi jika ada yang kosong
        $pesanError = $this->cekIsian($labId, $classRoomId, $subject, $date, $startTime, $endTime);
        if ($pesanError) {
            // Dikirim dari popup: balas JSON supaya error tampil di dalam popup
            if ($request->ajax()) {
                return response()->json(['errors' => [$pesanError]], 422);
            }
            return back()->withInput()->withErrors(['msg' => $pesanError]);
        }

        // Simpan jadwal ke database menggunakan kolom class_id sesuai struktur tabelmu
        $schedule = LabSchedule::create([
            'teacher_id'    => Auth::user()->teacher->id,
            'lab_id'        => $labId,
            'class_id'      => $classRoomId,
            'subject'       => $subject,
            'date'          => $date,
            'start_time'    => $startTime,
            'end_time'      => $endTime,
            'description'   => $description,
        ]);

        // Kirim notifikasi ke siswa yang ada di kelas tersebut
        $students = \App\Models\Student::where('class_id', $classRoomId)->get();
        foreach ($students as $student) {
            if ($student->user) {
                $student->user->notify(new \App\Notifications\JadwalBaruNotification($schedule->id));
            }
        }

        $pesan = 'Jadwal lab berhasil dibuat dan notifikasi dikirim ke siswa!';

        if ($request->ajax()) {
            session()->flash('success', $pesan);
            return response()->json(['redirect' => url('/guru/jadwal')]);
        }

        return redirect('/guru/jadwal')->with('success', $pesan);
    }

    // --- TAMBAHAN FUNGSI EDIT, UPDATE, & DESTROY ---
    
    public function edit(Request $request, $id)
    {
        $schedule = LabSchedule::findOrFail($id);
        $labs = Lab::all();
        $classes = ClassRoom::all();

        // Dibuka dari popup: kirim isi formnya saja, tanpa layout halaman
        if ($request->ajax()) {
            return view('guru.schedules._form', ['schedule' => $schedule, 'labs' => $labs, 'classes' => $classes, 'modal' => true]);
        }

        return view('guru.schedules.edit', compact('schedule', 'labs', 'classes'));
    }

    public function update(Request $request, $id)
    {
        $schedule = LabSchedule::findOrFail($id);

        $labId       = $request->input('lab_id') ?? $request->input('laboratory_id');
        $classRoomId = $request->input('class_room_id') ?? $request->input('class_id') ?? $request->input('classroom_id') ?? $request->input('kelas_id');
        $subject     = $request->input('subject');
        $date        = $request->input('date');
        $startTime   = $request->input('start_time');
        $endTime     = $request->input('end_time');

        $pesanError = $this->cekIsian($labId, $classRoomId, $subject, $date, $startTime, $endTime);
        if ($pesanError) {
            if ($request->ajax()) {
                return response()->json(['errors' => [$pesanError]], 422);
            }
            return back()->withInput()->withErrors(['msg' => $pesanError]);
        }
        
        $schedule->update([
            'lab_id'      => $labId,
            'class_id'    => $classRoomId,
            'subject'     => $subject,
            'date'        => $date,
            'start_time'  => $startTime,
            'end_time'    => $endTime,
            'description' => $request->input('description'),
        ]);

        $pesan = 'Jadwal lab berhasil diperbarui!';

        if ($request->ajax()) {
            session()->flash('success', $pesan);
            return response()->json(['redirect' => url('/guru/jadwal')]);
        }

        return redirect('/guru/jadwal')->with('success', $pesan);
    }

    public function destroy($id)
    {
        $schedule = LabSchedule::findOrFail($id);
        $schedule->delete();

        return redirect('/guru/jadwal')->with('success', 'Jadwal lab berhasil dihapus!');
    }

    // Pemeriksaan isian jadwal. Mengembalikan pesan error, atau null kalau semuanya benar.
    private function cekIsian($labId, $classRoomId, $subject, $date, $startTime, $endTime): ?string
    {
        if (!$labId || !$classRoomId || !$subject || !$date || !$startTime || !$endTime) {
            return 'Semua kolom wajib diisi dengan lengkap, pastikan Lab dan Kelas sudah dipilih!';
        }

        if (strtotime($endTime) <= strtotime($startTime)) {
            return 'Jam selesai harus lebih besar dari jam mulai.';
        }

        return null;
    }
}