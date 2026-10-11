<?php

namespace App\Http\Controllers;

use App\Models\Computer;
use App\Models\Lab;
use App\Models\ProblemReport;

class GuruPageController extends Controller
{
    // Menu "Laporan Masalah" milik guru: daftar semua laporan dari siswa
    public function laporan()
    {
        $laporan = ProblemReport::latest()->get();
        return view('guru.laporan.index', compact('laporan'));
    }

    // Menu "Kondisi Lab": ringkasan status PC per lab
    public function kondisiLab()
    {
        $labs = Lab::all();
        $computers = Computer::all()->groupBy('lab_id');
        return view('guru.kondisi.index', compact('labs', 'computers'));
    }
}