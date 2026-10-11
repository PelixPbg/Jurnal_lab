@extends('layouts.app')
@section('title', 'Jurnal Saya - Siswa')

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
    $userName = $student->name ?? Auth::user()->username;
    
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

        <!-- Menu Navigasi Utama (Dengan Presisi Senter Mutlak) -->
        <div class="flex-1 py-6 px-3 space-y-1.5 overflow-y-auto overflow-x-hidden">
            <!-- 1. Dashboard -->
            <a href="{{ url('/siswa/dashboard') }}" title="Dashboard" 
               :class="sidebarOpen ? 'flex items-center gap-3 px-4 py-3.5 text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-2xl font-bold text-sm transition-all' : 'flex items-center justify-center py-3.5 px-0 text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-2xl transition-all'">
                <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span x-show="sidebarOpen" x-transition.opacity class="truncate">Dashboard</span>
            </a>
            
            <!-- 2. Jadwal Lab -->
            <a href="{{ url('/siswa/jadwal') }}" title="Jadwal Lab" 
               :class="sidebarOpen ? 'flex items-center gap-3 px-4 py-3.5 text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-2xl font-bold text-sm transition-all' : 'flex items-center justify-center py-3.5 px-0 text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-2xl transition-all'">
                <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span x-show="sidebarOpen" x-transition.opacity class="truncate">Jadwal Lab</span>
            </a>

            <!-- 3. Jurnal Saya (Aktif) -->
            <a href="{{ url('/siswa/riwayat') }}" title="Jurnal Saya" 
               :class="sidebarOpen ? 'flex items-center gap-3 px-4 py-3.5 bg-blue-50 text-blue-700 rounded-2xl font-bold text-sm transition-all border border-blue-100/50' : 'flex items-center justify-center py-3.5 px-0 bg-blue-50 text-blue-700 rounded-2xl transition-all border border-blue-100/50'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                <span x-show="sidebarOpen" x-transition.opacity class="truncate">Jurnal Saya</span>
            </a>

            <!-- 4. Laporan Masalah -->
            <a href="{{ url('/siswa/laporan') }}" title="Laporan Masalah" 
               :class="sidebarOpen ? 'flex items-center gap-3 px-4 py-3.5 text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-2xl font-bold text-sm transition-all' : 'flex items-center justify-center py-3.5 px-0 text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-2xl transition-all'">
                <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span x-show="sidebarOpen" x-transition.opacity class="truncate">Laporan Masalah</span>
            </a>

            <div class="pt-4 mt-4 border-t border-slate-100"></div>

            <!-- 5. Profil Akun -->
            <a href="{{ url('/profil') }}" title="Profil Akun" 
               :class="sidebarOpen ? 'flex items-center gap-3 px-4 py-3.5 text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-2xl font-bold text-sm transition-all' : 'flex items-center justify-center py-3.5 px-0 text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-2xl transition-all'">
                <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                <span x-show="sidebarOpen" x-transition.opacity class="truncate">Profil Akun</span>
            </a>
        </div>

        <!-- Tombol Logout -->
        <div class="p-3 border-t border-slate-100 shrink-0">
            <a href="{{ url('/logout') }}" title="Keluar Akun" 
               :class="sidebarOpen ? 'flex items-center gap-3 px-4 py-3.5 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded-2xl font-bold text-sm transition-all' : 'flex items-center justify-center py-3.5 px-0 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded-2xl transition-all'">
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

        <!-- KONTEN UTAMA JURNAL SAYA -->
        <main class="flex-1 p-6 lg:p-8">
            <div class="mb-8">
                <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Jurnal Saya</h2>
                <p class="text-slate-500 font-medium mt-1">Daftar jurnal yang sedang dikerjakan serta riwayat penggunaan lab Anda.</p>
            </div>

            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                @if(isset($journals) && $journals->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-100 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest bg-slate-50/50">
                                    <th class="p-6">Tanggal & Waktu</th>
                                    <th class="p-6">Mata Pelajaran</th>
                                    <th class="p-6">Guru Pengajar</th>
                                    <th class="p-6">Lab & PC</th>
                                    <th class="p-6">Status Jurnal</th>
                                    <th class="p-6">Catatan Guru</th>
                                    <th class="p-6 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm font-semibold text-slate-700">
                                @foreach($journals as $journal)
                                <tr>
                                    <td class="p-6">{{ $journal->created_at->format('d M Y, H:i') }}</td>
                                    <td class="p-6 font-bold text-slate-900">{{ $journal->schedule->subject ?? '-' }}</td>
                                    <td class="p-6">{{ $journal->schedule->teacher->name ?? '-' }}</td>
                                    <td class="p-6">{{ $journal->lab->name ?? '-' }} - PC {{ $journal->pc_number ?? '-' }}</td>
                                    <td class="p-6">
                                        <span class="px-3 py-1 rounded-xl text-xs font-extrabold {{ $journal->status == 'approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-amber-50 text-amber-700 border border-amber-100' }}">
                                            {{ ucfirst($journal->status) }}
                                        </span>
                                    </td>
                                    <td class="p-6 text-slate-500">{{ $journal->teacher_notes ?? '-' }}</td>
                                    <td class="p-6 text-right">
                                        <a href="{{ url('/siswa/jurnal/' . $journal->id) }}" class="px-4 py-2 bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white rounded-xl text-xs font-bold transition-all">Detail</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-20 text-center">
                        <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-300 border border-slate-100 shadow-sm">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h3 class="text-xl font-extrabold text-slate-800 mb-1">Belum Ada Riwayat Jurnal</h3>
                        <p class="text-slate-500 font-medium text-sm">Anda belum memiliki catatan jurnal atau riwayat aktivitas laboratorium.</p>
                    </div>
                @endif
            </div>
        </main>
    </div>
</div>
@endsection