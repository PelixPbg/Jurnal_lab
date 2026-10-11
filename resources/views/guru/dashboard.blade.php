@extends('layouts.guru')
@section('title', 'Dashboard Guru - Jurnal Lab')

@section('content')
@php
    $userName = $guru->name ?? Auth::user()->username;
    $totalJadwal    = $schedules->count();
    $jurnalMasuk    = $jurnalMasuk ?? 0;
@endphp

<!-- HERO -->
<div class="relative bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 rounded-3xl p-8 sm:p-10 mb-10 overflow-hidden shadow-lg shadow-indigo-200">
    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 right-40 w-40 h-40 bg-blue-300 opacity-20 rounded-full blur-2xl"></div>
    <div class="relative z-10">
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 text-white text-xs font-bold tracking-wider mb-4 border border-white/20 backdrop-blur-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            GURU PENGAMPU
        </span>
        <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-2 tracking-tight">Halo, {{ $userName }}!</h2>
        <p class="text-blue-100 text-sm sm:text-base font-medium">Pantau jadwal dan verifikasi jurnal siswa di laboratorium Anda hari ini.</p>
    </div>
</div>

<!-- STATISTIK (2 kolom: Laporan Masalah & PC Bermasalah ditangani Admin) -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-10">
    
    <!-- 1. Jadwal Hari Ini -->
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex flex-col justify-between relative overflow-hidden">
        <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-bl-full opacity-50"></div>
        <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center mb-4 relative z-10">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        </div>
        <div class="relative z-10">
            <p class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-1">Jadwal Hari Ini</p>
            <h3 class="text-3xl font-extrabold text-slate-800">{{ $totalJadwal }}</h3>
        </div>
    </div>

    <!-- 2. Jurnal Masuk -->
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex flex-col justify-between relative overflow-hidden">
        <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-50 rounded-bl-full opacity-50"></div>
        <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-4 relative z-10">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div class="relative z-10">
            <p class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-1">Jurnal Masuk</p>
            <h3 class="text-3xl font-extrabold text-slate-800">{{ $jurnalMasuk }}</h3>
        </div>
    </div>

</div>

<!-- JADWAL HARI INI -->
<div class="mb-6 flex items-center justify-between">
    <h3 class="text-xl font-extrabold text-slate-800 tracking-tight">Jadwal Lab Anda Hari Ini</h3>
    <a href="{{ url('/guru/jadwal') }}" class="text-sm font-bold text-blue-600 hover:text-blue-700">Kelola Jadwal &rarr;</a>
</div>

<div class="space-y-4">
    @forelse($schedules as $schedule)
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-4 sm:p-6 flex flex-col sm:flex-row items-stretch gap-6">
            <div class="bg-slate-50 rounded-2xl p-5 sm:w-48 flex flex-col justify-center shrink-0 border border-slate-100 relative overflow-hidden">
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-blue-600"></div>
                <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Waktu Mulai</p>
                <h4 class="text-2xl font-extrabold text-blue-600 mb-1">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}</h4>
                <p class="text-sm font-semibold text-slate-500">s/d {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</p>
            </div>

            <div class="flex-1 flex flex-col justify-between">
                <div>
                    <h3 class="text-2xl font-extrabold text-slate-800 tracking-tight mb-3">{{ $schedule->subject }}</h3>
                    <div class="flex flex-wrap items-center gap-y-2 gap-x-6 text-sm font-semibold text-slate-600 mb-4">
                        <div>Lab: <span class="text-slate-800 font-bold">{{ $schedule->lab->name ?? 'Lab Komputer' }}</span></div>
                        <div>Kelas: <span class="text-slate-800 font-bold">{{ $schedule->classRoom->name ?? '-' }}</span></div>
                    </div>
                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 text-sm">
                        <span class="font-extrabold text-slate-700">Kegiatan:</span>
                        <span class="text-slate-600 font-medium">{{ $schedule->description ?? 'Praktikum sesuai materi.' }}</span>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <a href="{{ url('/guru/jurnal') }}" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm shadow-sm transition-all">
                        Lihat Jurnal Siswa &rarr;
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-3xl border border-slate-100 p-16 text-center shadow-sm">
            <h3 class="text-2xl font-extrabold text-slate-800 mb-2">Tidak ada jadwal hari ini</h3>
            <p class="text-slate-500 font-medium">Anda belum punya jadwal penggunaan lab untuk hari ini.</p>
        </div>
    @endforelse
</div>
@endsection