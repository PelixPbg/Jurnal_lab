<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lab;

class AdminLabController extends Controller
{
    public function index()
    {
        $labs = Lab::withCount('computers')->get();
        return view('admin.labs.index', compact('labs'));
    }

    public function create()
    {
        return view('admin.labs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        Lab::create($request->all());
        return redirect('/admin/labs')->with('success', 'Data Lab berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $lab = Lab::findOrFail($id);
        return view('admin.labs.edit', compact('lab'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $lab = Lab::findOrFail($id);
        $lab->update($request->all());

        return redirect('/admin/labs')->with('success', 'Data Lab berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $lab = Lab::findOrFail($id);
        $lab->delete(); // PC dan Jadwal yang terhubung otomatis terhapus jika migration pakai onDelete('cascade')

        return redirect('/admin/labs')->with('success', 'Data Lab berhasil dihapus!');
    }

    // Tambahkan atau ubah fungsi ini di AdminLabController
    public function kondisiLab()
    {
        // Ambil data semua lab beserta jumlah PC berdasarkan statusnya
        // Pastikan di model Lab (app/Models/Lab.php) ada relasi: 
        // public function computers() { return $this->hasMany(Computer::class); }
        
        $labs = \App\Models\Lab::withCount([
            'computers as total_pc',
            'computers as pc_baik' => function ($query) {
                $query->where('status', 'baik');
            },
            'computers as pc_bermasalah' => function ($query) {
                $query->where('status', 'bermasalah');
            },
            'computers as pc_rusak' => function ($query) {
                $query->where('status', 'rusak');
            }
        ])->get();

        return view('admin.kondisi.index', compact('labs'));
    }
}