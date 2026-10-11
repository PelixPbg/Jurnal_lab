@extends('layouts.app')
@section('title', 'Dashboard Siswa - Jurnal Lab')

@section('content')
<!-- Import Font Plus Jakarta Sans & Alpine.js -->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: #F4F7FE;
        margin: 0;
    }
    nav.bg-white.shadow-sm { display: none !important; }
</style>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

@php
    $student = Auth::user()->student;
    $kelasName = $student->classRoom->name ?? $student->class->name ?? 'Kelas Belum Diatur';

    $totalJadwal = $schedules->count() ?? 0;
    $jurnalSelesai = $jurnalSelesai ?? 0;
    $menungguVerif = $menungguVerifikasi ?? 0;
    $laporan = $laporanMasalah ?? 0;

    $userName = Auth::user()->student->name ?? Auth::user()->username;
    $initial = strtoupper(substr($userName, 0, 1));
    $words = explode(' ', $userName);
    if(count($words) > 1) {
        $initial .= strtoupper(substr($words[1], 0, 1));
    }
@endphp

<!-- WRAPPER UTAMA DENGAN LOCALSTORAGE ALPINE.JS -->
<div class="flex min-h-screen bg-[#F4F7FE]" x-data="{ sidebarOpen: localStorage.getItem('sidebarOpen') !== 'false' }">

    <!-- ================= SIDEBAR KIRI ================= -->
    <aside :class="sidebarOpen ? 'w-72' : 'w-20'" class="hidden md:flex flex-col fixed inset-y-0 left-0 bg-white border-r border-slate-200 z-50 transition-all duration-300 ease-in-out overflow-hidden">

        <!-- HEADER SIDEBAR -->
        <div class="h-20 flex items-center px-6 border-b border-slate-100 shrink-0 relative">

            <!-- Logo lengkap di dalam sidebar (Tampil saat sidebar DIBUKA) -->
            <div x-show="sidebarOpen" x-transition.opacity class="flex items-center gap-3 overflow-hidden w-full justify-between">
                <div class="flex items-center gap-3 overflow-hidden">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-600/20 shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <div class="truncate">
                        <h1 class="font-extrabold text-slate-800 leading-tight text-xl tracking-tight">Jurnal Lab</h1>
                        <p class="text-[9px] text-slate-500 font-bold tracking-widest uppercase">Laboratory System</p>
                    </div>
                </div>

                <!-- Tombol Panah Tutup Sidebar -->
                <button @click="sidebarOpen = false; localStorage.setItem('sidebarOpen', 'false')" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-slate-100 rounded-lg transition-colors shrink-0">
                    <svg class="w-5 h-5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path></svg>
                </button>
            </div>

            <!-- Tombol Panah Buka Sidebar (Tampil saat sidebar DITUTUP) -->
            <div x-show="!sidebarOpen" x-transition.opacity class="w-full flex items-center justify-center">
                <button @click="sidebarOpen = true; localStorage.setItem('sidebarOpen', 'true')" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-slate-100 rounded-lg transition-colors">
                    <svg class="w-6 h-6 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path></svg>
                </button>
            </div>

        </div>

        <!-- Menu Navigasi Utama (Dengan Presisi Dinamis) -->
        <div class="flex-1 py-6 px-3 space-y-1.5 overflow-y-auto overflow-x-hidden">
            <!-- 1. Dashboard (Aktif) -->
            <a href="{{ url('/siswa/dashboard') }}" title="Dashboard"
               :class="sidebarOpen
                    ? 'flex items-center gap-3 px-4 py-3.5 bg-blue-50 text-blue-700 rounded-2xl font-bold text-sm transition-all border border-blue-100/50'
                    : 'flex items-center justify-center py-3.5 bg-blue-50 text-blue-700 rounded-2xl transition-all border border-blue-100/50'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span x-show="sidebarOpen" x-transition.opacity class="truncate">Dashboard</span>
            </a>

            <!-- 2. Jadwal Lab -->
            <a href="{{ url('/siswa/jadwal') }}" title="Jadwal Lab"
               :class="sidebarOpen
                    ? 'flex items-center gap-3 px-4 py-3.5 text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-2xl font-bold text-sm transition-all'
                    : 'flex items-center justify-center py-3.5 text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-2xl transition-all'">
                <svg class="w-5 h-5 text-slate-400 group-hover:text-blue-500 transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span x-show="sidebarOpen" x-transition.opacity class="truncate">Jadwal Lab</span>
            </a>


            <!-- 4. Laporan Masalah -->
            <a href="{{ url('/siswa/laporan') }}" title="Laporan Masalah"
               :class="sidebarOpen
                    ? 'flex items-center gap-3 px-4 py-3.5 text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-2xl font-bold text-sm transition-all'
                    : 'flex items-center justify-center py-3.5 text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-2xl transition-all'">
                <svg class="w-5 h-5 text-slate-400 group-hover:text-rose-500 transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span x-show="sidebarOpen" x-transition.opacity class="truncate">Laporan Masalah</span>
            </a>

            <div class="pt-4 mt-4 border-t border-slate-100"></div>

            <!-- 5. Profil Akun -->
            <a href="{{ url('/profil') }}" title="Profil Akun"
               :class="sidebarOpen
                    ? 'flex items-center gap-3 px-4 py-3.5 text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-2xl font-bold text-sm transition-all'
                    : 'flex items-center justify-center py-3.5 text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-2xl transition-all'">
                <svg class="w-5 h-5 text-slate-400 group-hover:text-indigo-500 transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                <span x-show="sidebarOpen" x-transition.opacity class="truncate">Profil Akun</span>
            </a>
        </div>

        <!-- 6. Tombol Logout -->
        <div class="p-3 border-t border-slate-100 shrink-0">
            <a href="{{ url('/logout') }}" title="Keluar Akun"
               :class="sidebarOpen
                    ? 'flex items-center gap-3 px-4 py-3.5 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded-2xl font-bold text-sm transition-all'
                    : 'flex items-center justify-center py-3.5 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded-2xl transition-all'">
                <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                <span x-show="sidebarOpen" x-transition.opacity class="truncate">Keluar Akun</span>
            </a>
        </div>
    </aside>

    <!-- ================= AREA KONTEN KANAN ================= -->
    <div :class="sidebarOpen ? 'md:ml-72' : 'md:ml-20'" class="flex-1 flex flex-col min-h-screen transition-all duration-300 ease-in-out">

        <!-- TOPBAR -->
        <nav class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200/60 sticky top-0 z-40 flex items-center justify-between px-4 sm:px-6 lg:px-8">

            <!-- LOGO DI TOPBAR (Hanya muncul saat sidebar ditutup) -->
            <div class="flex items-center">
                <div x-show="!sidebarOpen" x-transition.opacity class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center shadow-md shadow-blue-600/20 shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h1 class="font-extrabold text-slate-800 leading-tight text-lg tracking-tight">Jurnal Lab</h1>
                        <p class="text-[9px] text-slate-400 font-bold tracking-widest uppercase">Laboratory System</p>
                    </div>
                </div>
            </div>

            <!-- SISI KANAN TOPBAR -->
            <div class="flex items-center gap-5">
                <a href="{{ url('/notifikasi') }}" class="relative p-2.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50/50 rounded-full transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"></path></svg>
                    @if(Auth::user()->unreadNotifications->count() > 0)
                        <span class="absolute top-2 right-2 flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500 border-2 border-white"></span>
                        </span>
                    @endif
                </a>

                <a href="{{ url('/profil') }}" class="flex items-center gap-3 group">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-extrabold text-slate-800 leading-none mb-1 group-hover:text-blue-600 transition-colors">{{ $userName }}</p>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Siswa</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-100 to-indigo-50 text-blue-700 flex items-center justify-center font-extrabold text-sm border-2 border-white shadow-sm ring-1 ring-slate-200">
                        {{ $initial }}
                    </div>
                </a>
            </div>
        </nav>

        <!-- DASHBOARD CONTENT -->
        <main class="flex-1 p-6 lg:p-8">

            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm font-bold shadow-sm">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-sm font-bold shadow-sm">{{ session('error') }}</div>
            @endif


            <!-- HERO SECTION -->
            <div class="relative bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 rounded-3xl p-8 sm:p-10 mb-10 overflow-hidden shadow-lg shadow-indigo-200">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 right-40 w-40 h-40 bg-blue-300 opacity-20 rounded-full blur-2xl"></div>

                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 text-white text-xs font-bold tracking-wider mb-4 border border-white/20 backdrop-blur-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            {{ $kelasName }}
                        </span>
                        <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-2 tracking-tight">
                            Halo, {{ $userName }}!
                        </h2>
                        <p class="text-blue-100 text-sm sm:text-base font-medium">Pantau jadwal dan lengkapi aktivitas laboratorium Anda hari ini.</p>
                    </div>
                </div>
            </div>

            <!-- STATISTIC CARDS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-10">
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

                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex flex-col justify-between relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-50 rounded-bl-full opacity-50"></div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-4 relative z-10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="relative z-10">
                        <p class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-1">Jurnal Selesai</p>
                        <h3 class="text-3xl font-extrabold text-slate-800">{{ $jurnalSelesai }}</h3>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex flex-col justify-between relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-amber-50 rounded-bl-full opacity-50"></div>
                    <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center mb-4 relative z-10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="relative z-10">
                        <p class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-1">Menunggu Verif</p>
                        <h3 class="text-3xl font-extrabold text-slate-800">{{ $menungguVerif }}</h3>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex flex-col justify-between relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-rose-50 rounded-bl-full opacity-50"></div>
                    <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center mb-4 relative z-10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div class="relative z-10">
                        <p class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-1">Laporan Masalah</p>
                        <h3 class="text-3xl font-extrabold text-slate-800">{{ $laporan }}</h3>
                    </div>
                </div>
            </div>

            <!-- JADWAL LAB SECTION -->
            <div class="mb-6">
                <h3 class="text-xl font-extrabold text-slate-800 tracking-tight">Informasi Lab yang Sedang Digunakan</h3>
            </div>

            <div class="space-y-4">
                @if(isset($schedules) && $schedules->count() > 0)
                    @foreach($schedules as $schedule)
                    @php
                        // Cari jurnal siswa untuk jadwal ini (null = belum check-in)
                        $journal = isset($journals) ? ($journals[$schedule->id] ?? null) : null;
                        $jStatus = $journal->status ?? null;
                    @endphp
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-4 sm:p-6 flex flex-col sm:flex-row items-stretch gap-6">
                        <div class="bg-slate-50 rounded-2xl p-5 sm:w-48 flex flex-col justify-center shrink-0 border border-slate-100 relative overflow-hidden">
                            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-blue-600"></div>
                            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Waktu Mulai</p>
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <h4 class="text-2xl font-extrabold text-blue-600">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}</h4>
                            </div>
                            <p class="text-sm font-semibold text-slate-500">s/d {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</p>
                        </div>

                        <div class="flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex flex-wrap items-start justify-between gap-4 mb-3">
                                    <h3 class="text-2xl font-extrabold text-slate-800 tracking-tight">{{ $schedule->subject }}</h3>

                                    {{-- Badge status sesuai kondisi jurnal --}}
                                    @if(!$journal)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 text-slate-600 text-xs font-bold border border-slate-200">Belum Check-In</span>
                                    @elseif($jStatus === 'draft')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-600 text-xs font-bold border border-emerald-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Sedang Berlangsung
                                        </span>
                                    @elseif($jStatus === 'submitted')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-50 text-amber-600 text-xs font-bold border border-amber-100">Menunggu Verifikasi</span>
                                    @elseif($jStatus === 'approved')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-600 text-xs font-bold border border-emerald-100">Disetujui</span>
                                    @elseif($jStatus === 'revision')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-rose-50 text-rose-600 text-xs font-bold border border-rose-100">Perlu Perbaikan</span>
                                    @endif
                                </div>

                                <div class="flex flex-wrap items-center gap-y-2 gap-x-6 text-sm font-semibold text-slate-600 mb-4">
                                    <div>Lab: <span class="text-slate-800 font-bold">{{ $schedule->lab->name ?? 'Lab Komputer' }}</span></div>
                                    <div>Guru: <span class="text-slate-800 font-bold">{{ $schedule->teacher->name ?? 'Bapak/Ibu Guru' }}</span></div>
                                </div>

                                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 text-sm">
                                    <span class="font-extrabold text-slate-700">Kegiatan:</span>
                                    <span class="text-slate-600 font-medium">{{ $schedule->description ?? 'Mengikuti instruksi praktikum.' }}</span>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end">
                                @if(!$journal)
                                    {{-- Belum ada jurnal: CHECK-IN --}}
                                    <a href="{{ url('/siswa/check-in/' . $schedule->id) }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm shadow-sm shadow-blue-600/20 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                                        Check-In Sekarang
                                    </a>
                                @elseif($jStatus === 'draft')
                                    {{-- Sudah check-in, belum selesai: CHECK-OUT --}}
                                    <a href="{{ url('/siswa/check-out/' . $journal->id) }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-sm shadow-sm shadow-emerald-600/20 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                        Check-Out &amp; Isi Jurnal
                                    </a>
                                @elseif($jStatus === 'revision')
                                    <a href="{{ url('/siswa/check-out/' . $journal->id) }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-rose-500 hover:bg-rose-600 text-white rounded-xl font-bold text-sm shadow-sm transition-all">
                                        Perbaiki Jurnal &rarr;
                                    </a>
                                @else
                                    {{-- submitted / approved: lihat detail --}}
                                    <a href="{{ url('/siswa/riwayat/' . $journal->id) }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-sm transition-all">
                                        Lihat Jurnal &rarr;
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="bg-white rounded-3xl border border-slate-100 p-16 text-center shadow-sm">
                        <h3 class="text-2xl font-extrabold text-slate-800 mb-2">Tidak ada jadwal hari ini</h3>
                        <p class="text-slate-500 font-medium">Bagus! Anda tidak memiliki jadwal penggunaan lab untuk hari ini.</p>
                    </div>
                @endif
            </div>
        </main>
    </div>
</div>

{{-- Popup kaca untuk Check-In & Check-Out --}}
@include('siswa._modal_form')
@endsection