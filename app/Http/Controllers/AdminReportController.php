<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProblemReport;
use App\Models\Computer;
use Carbon\Carbon;

class AdminReportController extends Controller
{
    // Menampilkan semua laporan masalah
    public function index()
    {
        $reports = ProblemReport::with(['student.classRoom', 'computer', 'lab', 'journal.schedule.lab'])
            ->orderByRaw("FIELD(status, 'reported', 'reviewing', 'resolved')")
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.reports.index', compact('reports'));
    }

    // Menandai masalah telah diperbaiki
    public function resolve(Request $request, $id)
    {
        $report = ProblemReport::findOrFail($id);
        
        // Update laporan menjadi resolved
        $report->update([
            'status' => 'resolved',
            'resolved_at' => Carbon::now(),
            'resolution_notes' => 'Telah diperbaiki oleh Admin.',
        ]);

        // Kembalikan status PC menjadi 'baik' di master data komputer
        if ($report->computer_id) {
            Computer::where('id', $report->computer_id)->update(['status' => 'baik']);
        }

        return redirect('/admin/reports')->with('success', 'Laporan masalah berhasil diselesaikan dan status PC kembali Normal.');
    }
}