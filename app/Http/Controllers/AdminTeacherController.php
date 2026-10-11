<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\User;
use App\Imports\TeachersImport;
use App\Exports\TemplateExport;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AdminTeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with('user')->get();
        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('admin.teachers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|string|unique:teachers,nip',
            'name' => 'required|string|max:255',
        ]);

        // Gunakan Transaction agar jika salah satu gagal, semua dibatalkan
        DB::transaction(function () use ($request) {
            // 1. Buat Akun User (Login)
            $user = User::create([
                'username' => $request->nip, // NIP dijadikan username
                'password' => Hash::make($request->nip . '*'), // Password default: NIP + *
                'role' => 'guru'
            ]);

            // 2. Buat Profil Guru
            Teacher::create([
                'user_id' => $user->id,
                'nip' => $request->nip,
                'name' => $request->name,
            ]);
        });

        return redirect('/admin/teachers')->with('success', 'Data Guru dan Akun Login berhasil dibuat!');
    }

    // Menampilkan halaman import guru dari Excel
    public function importForm()
    {
        return view('admin.teachers.import');
    }

    // Memproses file Excel berisi banyak guru
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:5120',
        ], [
            'file.required' => 'Pilih file Excel terlebih dahulu.',
            'file.mimes' => 'Format file harus .xlsx atau .xls.',
            'file.max' => 'Ukuran file maksimal 5 MB.',
        ]);

        // Pembuatan password (hash) per guru butuh waktu, jadi batas waktu dilonggarkan
        @set_time_limit(300);

        $import = new TeachersImport();

        try {
            Excel::import($import, $request->file('file'));
        } catch (\Throwable $e) {
            return redirect('/admin/teachers/import')
                ->with('error', 'File tidak dapat dibaca. Pastikan memakai template yang disediakan.');
        }

        return redirect('/admin/teachers/import')->with('import_result', [
            'berhasil' => $import->berhasil,
            'dilewati' => $import->dilewati,
            'galat' => $import->galat,
        ]);
    }

    // Mengunduh template Excel kosong
    public function template()
    {
        return Excel::download(
            new TemplateExport(['nip', 'nama']),
            'template-import-guru.xlsx'
        );
    }

    public function edit($id)
    {
        $teacher = Teacher::findOrFail($id);
        return view('admin.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);
        
        $request->validate([
            'nip' => 'required|string|unique:teachers,nip,'.$id,
            'name' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($request, $teacher) {
            // Update nama di tabel teachers
            $teacher->update([
                'nip' => $request->nip,
                'name' => $request->name,
            ]);

            // Update username di tabel users jika NIP berubah
            $teacher->user->update([
                'username' => $request->nip,
            ]);
        });

        return redirect('/admin/teachers')->with('success', 'Data Guru berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $teacher = Teacher::findOrFail($id);
        
        // Hapus User-nya, otomatis Teacher ikut terhapus (karena cascade di migration)
        $teacher->user->delete();

        return redirect('/admin/teachers')->with('success', 'Data Guru dan Akunnya berhasil dihapus!');
    }
}