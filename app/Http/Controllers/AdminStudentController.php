<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use App\Models\ClassRoom;
use App\Imports\StudentsImport;
use App\Exports\TemplateExport;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AdminStudentController extends Controller
{
    public function index()
    {
        // Ambil data siswa beserta relasi user dan kelasnya
        $students = Student::with(['user', 'classRoom'])->orderBy('class_id')->get();
        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        $classes = ClassRoom::all();
        return view('admin.students.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|string|unique:students,nis',
            'name' => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Buat Akun User (Login Siswa)
            $user = User::create([
                'username' => $request->nis, // NIS dijadikan username
                'password' => Hash::make($request->nis . '*'), // Password default: NIS + *
                'role' => 'siswa'
            ]);

            // 2. Buat Data Siswa
            Student::create([
                'user_id' => $user->id,
                'class_id' => $request->class_id,
                'nis' => $request->nis,
                'name' => $request->name,
            ]);
        });

        return redirect('/admin/students')->with('success', 'Data Siswa dan Akun Login berhasil ditambahkan!');
    }

    // Menampilkan halaman import siswa dari Excel
    public function importForm()
    {
        return view('admin.students.import');
    }

    // Memproses file Excel berisi banyak siswa
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:5120',
        ], [
            'file.required' => 'Pilih file Excel terlebih dahulu.',
            'file.mimes' => 'Format file harus .xlsx atau .xls.',
            'file.max' => 'Ukuran file maksimal 5 MB.',
        ]);

        // Pembuatan password (hash) per siswa butuh waktu, jadi batas waktu dilonggarkan
        @set_time_limit(300);

        $import = new StudentsImport();

        try {
            Excel::import($import, $request->file('file'));
        } catch (\Throwable $e) {
            return redirect('/admin/students/import')
                ->with('error', 'File tidak dapat dibaca. Pastikan memakai template yang disediakan.');
        }

        return redirect('/admin/students/import')->with('import_result', [
            'berhasil' => $import->berhasil,
            'dilewati' => $import->dilewati,
            'galat' => $import->galat,
        ]);
    }

    // Mengunduh template Excel kosong
    public function template()
    {
        return Excel::download(
            new TemplateExport(['nis', 'nama', 'kelas']),
            'template-import-siswa.xlsx'
        );
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);
        $classes = ClassRoom::all();
        return view('admin.students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        
        $request->validate([
            'nis' => 'required|string|unique:students,nis,'.$id,
            'name' => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id',
        ]);

        DB::transaction(function () use ($request, $student) {
            // Update data profil siswa
            $student->update([
                'nis' => $request->nis,
                'name' => $request->name,
                'class_id' => $request->class_id,
            ]);

            // Update username di tabel users (jika NIS diubah)
            $student->user->update([
                'username' => $request->nis,
            ]);
        });

        return redirect('/admin/students')->with('success', 'Data Siswa berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        
        // Hapus User-nya, otomatis Student ikut terhapus
        $student->user->delete();

        return redirect('/admin/students')->with('success', 'Data Siswa dan Akunnya berhasil dihapus!');
    }
}