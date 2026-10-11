<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Computer;
use App\Models\Lab;

class AdminComputerController extends Controller
{
    public function index()
    {
        // Menampilkan seluruh PC beserta nama Lab-nya
        $computers = Computer::with('lab')->orderBy('lab_id')->orderBy('code')->get();
        return view('admin.computers.index', compact('computers'));
    }

    public function create()
    {
        $labs = Lab::all();
        return view('admin.computers.create', compact('labs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lab_id' => 'required|exists:labs,id',
            'code' => 'required|string|unique:computers,code|max:255',
            'name' => 'nullable|string|max:255',
            'status' => 'required|in:baik,bermasalah,rusak',
        ]);

        Computer::create($request->all());
        return redirect('/admin/computers')->with('success', 'Data PC berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $computer = Computer::findOrFail($id);
        $labs = Lab::all();
        return view('admin.computers.edit', compact('computer', 'labs'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'lab_id' => 'required|exists:labs,id',
            'code' => 'required|string|max:255|unique:computers,code,'.$id,
            'name' => 'nullable|string|max:255',
            'status' => 'required|in:baik,bermasalah,rusak',
        ]);

        $computer = Computer::findOrFail($id);
        $computer->update($request->all());

        return redirect('/admin/computers')->with('success', 'Data PC berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $computer = Computer::findOrFail($id);
        $computer->delete();

        return redirect('/admin/computers')->with('success', 'Data PC berhasil dihapus!');
    }
}