@extends('layouts.admin')
@section('title', 'Dashboard Admin - Jurnal Lab')

@section('content')
@php
    $userName = Auth::user()->username ?? 'Administrator';
    // Dummy variables (Nanti diganti query beneran di Controller ya)
    $totalSiswa = $totalSiswa ?? 320;
    $totalGuru = $totalGuru ?? 15;
    $laporanBaru = $laporanBaru ?? 4;
    $pcRusak = $pcRusak ?? 12;
@endphp

<!-- HERO SECTION -->
<div class="relative bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 rounded-3xl p-8 sm:p-10 mb-10 overflow-hidden shadow-lg shadow-indigo-200">
    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 right-40 w-40 h-40 bg-blue-300 opacity-20 rounded-full blur-2xl"></div>
    <div class="relative z-10">
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 text-white text-xs font-bold tracking-wider mb-4 border border-white/20 backdrop-blur-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            ADMINISTRATOR
        </span>
        <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-2 tracking-tight">Halo, {{ $userName }}!</h2>
        <p class="text-blue-100 text-sm sm:text-base font-medium">Pantau seluruh data pengguna, kondisi lab, dan laporan masalah hari ini.</p>
    </div>
</div>

<!-- STATISTIK (4 Kolom) -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-10">
    
    <!-- 1. Total Siswa -->
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex flex-col justify-between relative overflow-hidden">
        <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-bl-full opacity-50"></div>
        <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center mb-4 relative z-10">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </div>
        <div class="relative z-10">
            <p class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-1">Total Siswa Aktif</p>
            <h3 class="text-3xl font-extrabold text-slate-800">{{ $totalSiswa }}</h3>
        </div>
    </div>

    <!-- 2. Total Guru -->
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex flex-col justify-between relative overflow-hidden">
        <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-50 rounded-bl-full opacity-50"></div>
        <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-4 relative z-10">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
        </div>
        <div class="relative z-10">
            <p class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-1">Total Guru</p>
            <h3 class="text-3xl font-extrabold text-slate-800">{{ $totalGuru }}</h3>
        </div>
    </div>

    <!-- 3. Laporan Masalah -->
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex flex-col justify-between relative overflow-hidden">
        <div class="absolute top-0 right-0 w-24 h-24 bg-rose-50 rounded-bl-full opacity-50"></div>
        <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center mb-4 relative z-10">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <div class="relative z-10">
            <p class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-1">Laporan Baru</p>
            <h3 class="text-3xl font-extrabold text-slate-800">{{ $laporanBaru }}</h3>
        </div>
    </div>

    <!-- 4. PC Rusak -->
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex flex-col justify-between relative overflow-hidden">
        <div class="absolute top-0 right-0 w-24 h-24 bg-amber-50 rounded-bl-full opacity-50"></div>
        <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center mb-4 relative z-10">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
        </div>
        <div class="relative z-10">
            <p class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-1">PC Bermasalah</p>
            <h3 class="text-3xl font-extrabold text-slate-800">{{ $pcRusak }}</h3>
        </div>
    </div>

</div>

<!-- QUICK ACTION / SHORTCUT -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-extrabold text-slate-800 tracking-tight">Tindakan Cepat</h3>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <a href="{{ url('/admin/reports') }}" class="p-4 rounded-2xl border border-slate-200 bg-slate-50 hover:bg-indigo-50 hover:border-indigo-100 transition-colors flex flex-col items-center justify-center text-center group">
                <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-slate-400 group-hover:text-indigo-600 mb-3 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <span class="font-bold text-sm text-slate-700 group-hover:text-indigo-700">Tinjau Laporan</span>
            </a>
            <a href="{{ url('/admin/computers/create') }}" class="p-4 rounded-2xl border border-slate-200 bg-slate-50 hover:bg-purple-50 hover:border-purple-100 transition-colors flex flex-col items-center justify-center text-center group">
                <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-slate-400 group-hover:text-purple-600 mb-3 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                </div>
                <span class="font-bold text-sm text-slate-700 group-hover:text-purple-700">Tambah PC</span>
            </a>
        </div>
    </div>

    <!-- AREA LAPORAN TERBARU BISA DITARUH DI SINI -->
    <div class="bg-indigo-900 rounded-3xl shadow-sm p-8 flex flex-col justify-center relative overflow-hidden text-white">
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white opacity-5 rounded-full blur-2xl"></div>
        <h3 class="text-2xl font-extrabold mb-2 tracking-tight">Sistem Berjalan Normal</h3>
        <p class="text-indigo-200 text-sm font-medium leading-relaxed mb-6">Seluruh data tercatat dengan aman. Jangan lupa selalu pantau laporan masalah dari siswa untuk menjaga kondisi laboratorium tetap optimal.</p>
        <a href="{{ url('/admin/kondisi-lab') }}" class="inline-flex items-center gap-2 bg-indigo-500 hover:bg-indigo-400 text-white px-5 py-2.5 rounded-xl text-sm font-bold w-max transition-colors">
            Cek Kondisi Lab &rarr;
        </a>
    </div>
</div>
@endsection