<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LabSchedule;
use App\Models\LabJournal;
use App\Models\JournalChecklist;
use App\Models\Computer;
use App\Models\Lab; // Tambahan Model Lab
use App\Models\ProblemReport; // Tambahan Model ProblemReport
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StudentJournalController extends Controller
{
    // Menampilkan Form Check-In
    public function checkInForm(Request $request, $schedule_id)
    {
        $schedule = LabSchedule::with('lab')->findOrFail($schedule_id);
        $siswa = Auth::user()->student;

        // Cek apakah jadwal ini benar untuk kelas siswa tersebut
        if ($schedule->class_id !== $siswa->class_id) {
            abort(403, 'Akses Ditolak. Jadwal ini bukan untuk kelas Anda.');
        }

        // Cek apakah siswa sudah pernah check-in di jadwal ini
        $existingJournal = LabJournal::where('schedule_id', $schedule_id)
            ->where('student_id', $siswa->id)
            ->first();

        if ($existingJournal) {
            $pesan = 'Anda sudah melakukan check-in pada jadwal ini.';

            // Dibuka dari popup: kirim pesan saja, jangan redirect
            if ($request->ajax()) {
                return response()->json(['message' => $pesan], 409);
            }

            return redirect('/siswa/dashboard')->with('error', $pesan);
        }

        // Ambil daftar PC yang ada di Lab tersebut
        $computers = Computer::where('lab_id', $schedule->lab_id)->get();

        // Dibuka dari popup: kirim isi formnya saja, tanpa layout halaman
        if ($request->ajax()) {
            return view('siswa._check_in_form', compact('schedule', 'computers'));
        }

        return view('siswa.check_in', compact('schedule', 'computers'));
    }


    // Memproses Data Check-In
    public function processCheckIn(Request $request, $schedule_id)
    {
        $request->validate([
            'computer_id' => 'required|exists:computers,id',
            'condition_before' => 'required|in:baik,bermasalah,rusak',
            'room_cleanliness_before' => 'required|in:bersih,kotor',
            'notes' => 'nullable|string'
        ]);

        $siswa = Auth::user()->student;
        $computer = Computer::findOrFail($request->computer_id);

        // PC yang rusak tidak boleh dipakai (pengaman di server, kalau pilihan di form dilewati)
        if ($computer->status === 'rusak') {
            return redirect('/siswa/dashboard')
                ->with('error', 'PC ' . $computer->code . ' sedang rusak dan tidak dapat dipilih. Silakan check-in ulang dan pilih PC lain.');
        }

        // 1. Buat Jurnal Baru
        $journal = LabJournal::create([
            'schedule_id' => $schedule_id,
            'student_id' => $siswa->id,
            'check_in_at' => Carbon::now(),
            'status' => 'draft',
        ]);

        // 2. Checklist Kondisi Awal PC
        JournalChecklist::create([
            'journal_id' => $journal->id,
            'item_type' => 'PC',
            'item_name' => $computer->code,
            'condition_before' => $request->condition_before,
            'notes' => $request->notes,
        ]);

        // 3. Checklist Kebersihan Ruangan
        JournalChecklist::create([
            'journal_id' => $journal->id,
            'item_type' => 'Ruangan',
            'item_name' => 'Kebersihan Lab',
            'condition_before' => $request->room_cleanliness_before,
        ]);

        return redirect('/siswa/dashboard')
            ->with(
                'success',
                'Berhasil Check-In! Selamat menggunakan laboratorium.'
            );
    }


    // Menampilkan Form Check-Out
    public function checkOutForm(Request $request, $journal_id)
    {
        $siswa = Auth::user()->student;

        // Ambil data jurnal beserta jadwal dan checklist awalnya
        $journal = LabJournal::with([
            'schedule.lab',
            'checklists'
        ])->findOrFail($journal_id);

        // Validasi kepemilikan dan status
        if ($journal->student_id !== $siswa->id) {
            abort(403, 'Akses Ditolak.');
        }

        // Boleh check-out jika masih draft, atau dikembalikan guru untuk diperbaiki (revision)
        if (!in_array($journal->status, ['draft', 'revision'])) {
            $pesan = 'Jurnal ini sudah diselesaikan.';

            // Dibuka dari popup: kirim pesan saja, jangan redirect
            if ($request->ajax()) {
                return response()->json(['message' => $pesan], 409);
            }

            return redirect('/siswa/dashboard')->with('error', $pesan);
        }

        // Ambil data PC dari checklist awal
        $checklistPC = $journal->checklists
            ->where('item_type', 'PC')
            ->first();

        // Dibuka dari popup: kirim isi formnya saja, tanpa layout halaman
        if ($request->ajax()) {
            return view('siswa._check_out_form', compact('journal', 'checklistPC'));
        }

        return view(
            'siswa.check_out',
            compact('journal', 'checklistPC')
        );
    }


    // Memproses Data Check-Out
    public function processCheckOut(Request $request, $journal_id)
    {
        $request->validate([
            'activity_notes' => 'required|string',
            'student_notes' => 'required|string',
            'condition_after' => 'required|in:baik,bermasalah,rusak',
            'room_cleanliness_after' => 'required|in:bersih,kotor',
            'problem_description' => 'nullable|string|required_if:condition_after,bermasalah,rusak',
        ]);

        $journal = LabJournal::with('checklists')
            ->findOrFail($journal_id);

        // Pastikan jurnal ini milik siswa yang sedang login
        abort_if($journal->student_id !== Auth::user()->student->id, 403, 'Akses Ditolak.');

        // Hanya jurnal draft / revision yang boleh dikirim
        if (!in_array($journal->status, ['draft', 'revision'])) {
            return redirect('/siswa/dashboard')
                ->with('error', 'Jurnal ini sudah diselesaikan.');
        }

        // Ambil checklist PC
        $checklistPC = $journal->checklists
            ->where('item_type', 'PC')
            ->first();

        // Update kondisi PC
        if ($checklistPC) {
            $checklistPC->update([
                'condition_after' => $request->condition_after,
            ]);
        }

        // Ambil checklist kebersihan ruangan
        $checklistRoom = $journal->checklists
            ->where('item_type', 'Ruangan')
            ->first();

        // Update kondisi kebersihan ruangan
        if ($checklistRoom) {
            $checklistRoom->update([
                'condition_after' => $request->room_cleanliness_after,
            ]);
        }

        // Buat laporan masalah jika PC bermasalah atau rusak
        if (
            $request->condition_after !== 'baik'
            && $checklistPC
        ) {
            $computer = Computer::where(
                'code',
                $checklistPC->item_name
            )->first();

            ProblemReport::create([
                'journal_id' => $journal->id,
                'student_id' => $journal->student_id,
                'computer_id' => $computer
                    ? $computer->id
                    : null,
                'item_name' => 'PC ' . $checklistPC->item_name,
                'problem_description' => $request->problem_description,
                'status' => 'reported',
            ]);

            // Update status PC di master data
            if ($computer) {
                $computer->update([
                    'status' => $request->condition_after
                ]);
            }
        }

        // Simpan aktivitas, refleksi siswa,
        // waktu check-out dan status jurnal
        $journal->update([
            'check_out_at' => Carbon::now(),
            'activity_notes' => $request->activity_notes,
            'student_notes' => $request->student_notes,
            'status' => 'submitted',
        ]);

        return redirect('/siswa/dashboard')
            ->with(
                'success',
                'Berhasil Check-Out! Analisis dan Jurnal Anda telah dikirim ke Guru.'
            );
    }


    // Menampilkan Riwayat Jurnal Siswa
    public function history()
    {
        $siswa = Auth::user()->student;

        // Ambil semua jurnal milik siswa yang login
        $journals = LabJournal::where(
            'student_id',
            $siswa->id
        )
            ->with([
                'schedule.lab',
                'schedule.teacher',
                'checklists'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        return view(
            'siswa.history',
            compact('journals')
        );
    }

    // Menampilkan Detail Jurnal Siswa
    public function show($id)
    {
        $journal = LabJournal::with(['schedule.teacher', 'schedule.lab', 'checklists'])
                    ->where('student_id', Auth::user()->student->id)
                    ->findOrFail($id);

        return view('siswa.show', compact('journal'));
    }

    // Halaman Jadwal Lab & Jurnal (tab: Hari Ini, Mendatang, Riwayat)
    public function scheduleList()
    {
        $student = Auth::user()->student;

        // Ambil jadwal yang sesuai dengan class_id siswa tersebut
        $schedules = LabSchedule::where('class_id', $student->class_id)
            ->with(['lab', 'teacher', 'classRoom'])
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        // Jurnal milik siswa ini, dikelompokkan berdasarkan schedule_id
        $journals = LabJournal::where('student_id', $student->id)
            ->whereIn('schedule_id', $schedules->pluck('id'))
            ->get()
            ->keyBy('schedule_id');

        $hariIni = $schedules
            ->filter(fn ($s) => Carbon::parse($s->date)->isToday())
            ->values();

        $mendatang = $schedules
            ->filter(fn ($s) => Carbon::parse($s->date)->isFuture())
            ->values();

        $riwayat = $schedules
            ->filter(fn ($s) => Carbon::parse($s->date)->isPast() && !Carbon::parse($s->date)->isToday())
            ->sortByDesc('date')
            ->values();

        return view('siswa.jadwal.index', compact('hariIni', 'mendatang', 'riwayat', 'journals'));
    }

    // =========================================================
    // BAGIAN LAPORAN YANG SUDAH DIPERBAIKI (TOMBOL DINAMIS LAB)
    // =========================================================
    
    public function reportIndex()
    {
        $student = Auth::user()->student;

        // Ambil semua data lab untuk ditampilkan di dropdown (Memperbaiki Dropdown kosong)
        $labs = Lab::all();

        // Ambil data ProblemReport
        $reports = collect();
        if (class_exists(\App\Models\ProblemReport::class)) {
            $reports = ProblemReport::where('student_id', $student->id)
                            ->orderBy('created_at', 'desc')
                            ->get();
        }

        return view('siswa.laporan.index', compact('labs', 'reports'));
    }

    public function reportStore(Request $request)
    {
        // Validasi input
        $request->validate([
            'lab_id' => 'required',
            'component' => 'required|string',
            'pc_number' => 'required_if:component,PC,Mouse,Keyboard,Monitor',
            'description' => 'required|string|min:5',
        ]);

        if (class_exists(\App\Models\ProblemReport::class)) {
            $itemName = $request->component;
            $computer_id = null;
            $computer = null;

            // KONDISI 1: Jika yang bermasalah adalah Perangkat Komputer (PC/Mouse/Keyboard)
            if (in_array($request->component, ['PC', 'Mouse', 'Keyboard', 'Monitor']) && $request->pc_number) {
                $itemName = $request->component . ' - PC ' . $request->pc_number;
                
                // Cari PC di lab yang dipilih: cocokkan kode persis dulu (contoh: PC-002),
                // kalau tidak ketemu baru pakai pencarian LIKE sebagai cadangan
                $computer = \App\Models\Computer::where('lab_id', $request->lab_id)
                                    ->where('code', 'PC-' . str_pad((string) $request->pc_number, 3, '0', STR_PAD_LEFT))
                                    ->first()
                            ?? \App\Models\Computer::where('lab_id', $request->lab_id)
                                    ->where('code', 'LIKE', '%' . $request->pc_number . '%')
                                    ->first();
                if($computer) {
                    $computer_id = $computer->id;
                }
            } 
            // KONDISI 2: Jika yang bermasalah fasilitas lain (AC/Meja/Lainnya) dan user mengisi kolom teks posisinya
            elseif ($request->filled('other_device')) {
                $itemName = $request->component . ' - ' . $request->other_device;
            }

            // Simpan laporan ke database
            // Simpan laporan ke database
            \App\Models\ProblemReport::create([
                'student_id' => Auth::user()->student->id,
                'lab_id' => $request->lab_id,
                'journal_id' => null, // Definisikan null secara eksplisit
                'computer_id' => $computer_id,
                'item_name' => $itemName, 
                'problem_description' => $request->description,
                'status' => 'reported',
            ]);

            // Ubah status PC di master data supaya halaman Kondisi Lab ikut berubah.
            // Hanya dari 'baik' ke 'bermasalah', jadi PC yang sudah 'rusak' tidak diturunkan.
            if ($computer && $computer->status === 'baik') {
                $computer->update(['status' => 'bermasalah']);
            }
        }

        return redirect('/siswa/laporan')->with('success', 'Laporan masalah berhasil dikirim ke Administrator!');
    }
}