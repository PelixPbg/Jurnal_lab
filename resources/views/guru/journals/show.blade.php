@extends('layouts.guru')
@section('title', 'Detail Jurnal Siswa - Guru')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <a href="{{ url('/guru/jurnal') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-blue-600 transition-colors mb-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Jurnal
        </a>
        <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Detail Jurnal Siswa</h2>
    </div>

    <!-- Info Utama -->
    <div class="relative bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 rounded-3xl p-6 sm:p-8 mb-6 overflow-hidden shadow-lg shadow-indigo-200">
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-56 h-56 bg-white opacity-10 rounded-full blur-3xl"></div>
        <div class="relative z-10 grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <p class="text-[11px] font-extrabold text-blue-200 uppercase tracking-widest mb-1">Nama Siswa</p>
                <p class="text-white font-extrabold text-lg">{{ $journal->student->name }} <span class="font-semibold text-blue-100 text-sm">({{ $journal->student->nis }})</span></p>
            </div>
            <div>
                <p class="text-[11px] font-extrabold text-blue-200 uppercase tracking-widest mb-1">Kelas</p>
                <p class="text-white font-extrabold text-lg">{{ $journal->schedule->classRoom->name }}</p>
            </div>
            <div>
                <p class="text-[11px] font-extrabold text-blue-200 uppercase tracking-widest mb-1">Mata Pelajaran</p>
                <p class="text-white font-extrabold text-lg">{{ $journal->schedule->subject }}</p>
            </div>
            <div>
                <p class="text-[11px] font-extrabold text-blue-200 uppercase tracking-widest mb-1">Waktu Penggunaan</p>
                <p class="text-white font-extrabold text-lg">
                    {{ $journal->check_in_at ? \Carbon\Carbon::parse($journal->check_in_at)->format('H:i') : '-' }} s/d
                    {{ $journal->check_out_at ? \Carbon\Carbon::parse($journal->check_out_at)->format('H:i') : 'Belum Selesai' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Aktivitas & Refleksi -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 sm:p-8 mb-6">
        <h3 class="text-lg font-extrabold text-slate-800 mb-5">Laporan Pembelajaran &amp; Analisis Siswa</h3>

        <div class="mb-5">
            <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest mb-2">Aktivitas / Pekerjaan yang Diselesaikan</p>
            <p class="bg-slate-50 p-4 border border-slate-100 rounded-2xl text-sm font-medium text-slate-700">{{ $journal->activity_notes ?? 'Belum diisi' }}</p>
        </div>

        <div>
            <p class="text-[11px] font-extrabold text-blue-500 uppercase tracking-widest mb-2">Analisis &amp; Kesimpulan Siswa (Refleksi)</p>
            <p class="bg-blue-50/60 p-4 border border-blue-100 rounded-2xl text-sm font-medium text-slate-700 italic">
                "{{ $journal->student_notes ?? 'Tidak ada refleksi.' }}"
            </p>
        </div>
    </div>

    <!-- Checklist -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden mb-6">
        <div class="px-6 sm:px-8 pt-6 sm:pt-8 pb-4">
            <h3 class="text-lg font-extrabold text-slate-800">Kondisi Fasilitas (Checklist)</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-slate-50/70 border-y border-slate-100 text-slate-400">
                        <th class="py-3 px-6 sm:px-8 font-extrabold uppercase tracking-widest text-[11px]">Item / Fasilitas</th>
                        <th class="py-3 px-6 font-extrabold uppercase tracking-widest text-[11px]">Sebelum (Check-In)</th>
                        <th class="py-3 px-6 font-extrabold uppercase tracking-widest text-[11px]">Sesudah (Check-Out)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($journal->checklists as $checklist)
                    <tr>
                        <td class="py-3.5 px-6 sm:px-8 font-bold text-slate-700">{{ $checklist->item_type }} - {{ $checklist->item_name }}</td>
                        <td class="py-3.5 px-6">
                            <span class="inline-flex px-3 py-1 rounded-xl text-xs font-bold uppercase border {{ $checklist->condition_before == 'bersih' || $checklist->condition_before == 'baik' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100' }}">{{ $checklist->condition_before }}</span>
                        </td>
                        <td class="py-3.5 px-6">
                            @if($checklist->condition_after)
                                <span class="inline-flex px-3 py-1 rounded-xl text-xs font-bold uppercase border {{ $checklist->condition_after == 'bersih' || $checklist->condition_after == 'baik' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100' }}">{{ $checklist->condition_after }}</span>
                            @else
                                <span class="text-slate-400 font-bold">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Laporan Masalah -->
    @if($journal->problemReports->count() > 0)
    <div class="mb-6 bg-rose-50 p-6 border border-rose-100 rounded-3xl">
        <h3 class="text-lg font-extrabold text-rose-700 mb-4">Laporan Masalah / Kerusakan</h3>
        <div class="space-y-3">
            @foreach($journal->problemReports as $report)
                <div class="bg-white p-4 rounded-2xl border border-rose-100 text-sm">
                    <p class="text-slate-700"><span class="font-extrabold">Perangkat:</span> {{ $report->item_name }}</p>
                    <p class="text-slate-700 mt-1"><span class="font-extrabold">Deskripsi:</span> {{ $report->problem_description }}</p>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Verifikasi -->
    @if($journal->status === 'submitted')
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 sm:p-8">
        <h3 class="text-lg font-extrabold text-slate-800 mb-4">Tindakan Guru (Verifikasi)</h3>
        <form action="{{ url('/guru/jurnal/'.$journal->id.'/verify') }}" method="POST">
            @csrf
            <div class="mb-5">
                <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-widest mb-2">Catatan Guru (Opsional)</label>
                <textarea name="teacher_notes" rows="3" class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none text-sm font-medium text-slate-800 transition-all" placeholder="Berikan evaluasi atau catatan tambahan..."></textarea>
            </div>
            <div class="flex flex-wrap gap-3">
                <button type="submit" name="status" value="approved" class="px-6 py-3 bg-emerald-600 text-white font-bold rounded-2xl hover:bg-emerald-700 transition-colors shadow-sm text-sm">
                    Setujui Jurnal
                </button>
                <button type="submit" name="status" value="revision" class="px-6 py-3 bg-rose-500 text-white font-bold rounded-2xl hover:bg-rose-600 transition-colors shadow-sm text-sm">
                    Minta Perbaikan
                </button>
            </div>
        </form>
    </div>
    @else
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 sm:p-8">
        <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest mb-2">Status Verifikasi</p>
        <span class="inline-flex px-3 py-1.5 rounded-xl text-xs font-bold uppercase border {{ $journal->status == 'approved' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100' }}">{{ $journal->status }}</span>
        <p class="mt-4 text-sm text-slate-700"><span class="font-extrabold">Catatan Guru:</span> {{ $journal->teacher_notes ?? '-' }}</p>
    </div>
    @endif
</div>
@endsection