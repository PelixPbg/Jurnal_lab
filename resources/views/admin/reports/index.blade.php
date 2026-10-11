@extends('layouts.admin')
@section('title', 'Laporan Kerusakan PC')

@section('content')
<!-- HEADER -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Laporan Masalah / Kerusakan PC</h2>
        <p class="text-slate-500 font-medium mt-1">Pantau laporan dari siswa dan tandai yang sudah diperbaiki.</p>
    </div>
    <a href="{{ url('/admin/dashboard') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-white hover:bg-slate-50 text-slate-600 hover:text-indigo-600 border border-slate-200 rounded-2xl font-bold text-sm shadow-sm transition-all w-max">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
        Kembali ke Dashboard
    </a>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm font-bold shadow-sm">{{ session('success') }}</div>
@endif

<!-- TABEL LAPORAN -->
<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="py-4 px-6 text-xs font-extrabold text-slate-400 uppercase tracking-widest whitespace-nowrap">Tanggal</th>
                    <th class="py-4 px-6 text-xs font-extrabold text-slate-400 uppercase tracking-widest whitespace-nowrap">Pelapor (Siswa)</th>
                    <th class="py-4 px-6 text-xs font-extrabold text-slate-400 uppercase tracking-widest whitespace-nowrap">Lokasi Lab</th>
                    <th class="py-4 px-6 text-xs font-extrabold text-slate-400 uppercase tracking-widest whitespace-nowrap">Perangkat</th>
                    <th class="py-4 px-6 text-xs font-extrabold text-slate-400 uppercase tracking-widest whitespace-nowrap">Deskripsi Masalah</th>
                    <th class="py-4 px-6 text-xs font-extrabold text-slate-400 uppercase tracking-widest whitespace-nowrap">Status</th>
                    <th class="py-4 px-6 text-xs font-extrabold text-slate-400 uppercase tracking-widest whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($reports as $report)
                <tr class="hover:bg-slate-50/70 transition-colors">
                    <td class="py-4 px-6 text-sm font-semibold text-slate-600 whitespace-nowrap">{{ \Carbon\Carbon::parse($report->created_at)->format('d M Y, H:i') }}</td>
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-indigo-100 to-purple-50 text-indigo-700 flex items-center justify-center font-extrabold text-sm border-2 border-white shadow-sm ring-1 ring-slate-200 shrink-0">
                                {{ strtoupper(substr($report->student->name, 0, 1)) }}
                            </div>
                            <div>
                                <span class="block text-sm font-extrabold text-slate-800">{{ $report->student->name }}</span>
                                <span class="block text-xs font-semibold text-slate-400">{{ $report->student->classRoom->name }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6 text-sm font-semibold text-slate-600">{{ $report->lab->name ?? $report->journal->schedule->lab->name ?? '-' }}</td>
                    <td class="py-4 px-6">
                        <span class="inline-flex items-center px-3 py-1 rounded-lg bg-indigo-50 text-indigo-700 text-sm font-extrabold border border-indigo-100">{{ $report->item_name }}</span>
                    </td>
                    <td class="py-4 px-6 text-sm font-medium text-rose-600 min-w-[220px]">{{ $report->problem_description }}</td>
                    <td class="py-4 px-6">
                        @if($report->status === 'reported')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-rose-50 text-rose-600 text-xs font-bold border border-rose-100 whitespace-nowrap">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span> Belum Ditangani
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-xl bg-emerald-50 text-emerald-600 text-xs font-bold border border-emerald-100 whitespace-nowrap">Selesai (Diperbaiki)</span>
                        @endif
                    </td>
                    <td class="py-4 px-6">
                        @if($report->status === 'reported')
                            <form action="{{ url('/admin/reports/'.$report->id.'/resolve') }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm shadow-emerald-600/20 transition-all whitespace-nowrap" onclick="return confirm('Tandai PC ini sudah diperbaiki dan kondisinya kembali normal?')">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    Tandai Selesai
                                </button>
                            </form>
                        @else
                            <span class="text-sm font-semibold text-slate-400 whitespace-nowrap">{{ \Carbon\Carbon::parse($report->resolved_at)->format('d M Y') }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-16 px-6 text-center">
                        <h3 class="text-xl font-extrabold text-slate-800 mb-1">Belum ada laporan kerusakan</h3>
                        <p class="text-slate-500 text-sm font-medium">Belum ada laporan kerusakan. PC terpantau aman!</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection