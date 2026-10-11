<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassRoom;

class AdminClassController extends Controller
{
    public function index()
    {
        // Menampilkan kelas beserta jumlah siswanya
        $classes = ClassRoom::withCount('students')->get();
        return view('admin.classes.index', compact('classes'));
    }

    public function create()
    {
        return view('admin.classes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:classes,name',
        ]);

        ClassRoom::create($request->all());
        return redirect('/admin/classes')->with('success', 'Data Kelas berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $class = ClassRoom::findOrFail($id);
        return view('admin.classes.edit', compact('class'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:classes,name,'.$id,
        ]);

        $class = ClassRoom::findOrFail($id);
        $class->update($request->all());

        return redirect('/admin/classes')->with('success', 'Data Kelas berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $class = ClassRoom::findOrFail($id);
        $class->delete();

        return redirect('/admin/classes')->with('success', 'Data Kelas berhasil dihapus!');
    }
}