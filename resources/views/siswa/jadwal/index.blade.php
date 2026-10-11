@extends('layouts.app')
@section('title', 'Jadwal Lab - Siswa')

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
    [x-cloak] { display: none !important; }
</style>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

@php
    $student = Auth::user()->student;
    $kelasName = $student->classRoom->name ?? $student->class->name ?? 'Kelas Belum Diatur';
    $userName = $student->name ?? Auth::user()->username;

    $initial = strtoupper(substr($userName, 0, 1));
    $words = explode(' ', $userName);
    if(count($words) > 1) {
        $initial .= strtoupper(substr($words[1], 0, 1));
    }

    $hariIni   = $hariIni ?? collect();
    $mendatang = $mendatang ?? collect();
    $riwayat   = $riwayat ?? collect();
    $journals  = $journals ?? collect();

    // Tab awal: Hari Ini
    $tabClsActive = 'bg-blue-600 text-white shadow-sm shadow-blue-600/20';
    $tabClsIdle   = 'bg-white text-slate-500 hover:text-slate-800 border border-slate-200';
@endphp

<!-- WRAPPER UTAMA DENGAN LOCALSTORAGE ALPINE.JS -->
<div class="flex min-h-screen bg-[#F4F7FE]" x-data="{ sidebarOpen: localStorage.getItem('sidebarOpen') !== 'false' }">

    <!-- ================= SIDEBAR KIRI ================= -->
    <aside :class="sidebarOpen ? 'w-72' : 'w-20'" class="hidden md:flex flex-col fixed inset-y-0 left-0 bg-white border-r border-slate-200 z-50 transition-all duration-300 ease-in-out overflow-hidden">

        <!-- HEADER SIDEBAR -->
        <div class="h-20 flex items-center px-6 border-b border-slate-100 shrink-0 relative">

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

                <button @click="sidebarOpen = false; localStorage.setItem('sidebarOpen', 'false')" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-slate-100 rounded-lg transition-colors shrink-0">
                    <svg class="w-5 h-5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path></svg>
                </button>
            </div>

            <div x-show="!sidebarOpen" x-transition.opacity class="w-full flex items-center justify-center">
                <button @click="sidebarOpen = true; localStorage.setItem('sidebarOpen', 'true')" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-slate-100 rounded-lg transition-colors">
                    <svg class="w-6 h-6 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path></svg>
                </button>
            </div>

        </div>

        <!-- Menu Navigasi Utama -->
        <div class="flex-1 py-6 px-3 space-y-1.5 overflow-y-auto overflow-x-hidden">
            <!-- 1. Dashboard -->
            <a href="{{ url('/siswa/dashboard') }}" title="Dashboard"
               :class="sidebarOpen ? 'flex items-center gap-3 px-4 py-3.5 text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-2xl font-bold text-sm transition-all' : 'flex items-center justify-center py-3.5 px-0 text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-2xl transition-all'">
                <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span x-show="sidebarOpen" x-transition.opacity class="truncate">Dashboard</span>
            </a>

            <!-- 2. Jadwal Lab (Aktif) -->
            <a href="{{ url('/siswa/jadwal') }}" title="Jadwal Lab"
               :class="sidebarOpen ? 'flex items-center gap-3 px-4 py-3.5 bg-blue-50 text-blue-700 rounded-2xl font-bold text-sm transition-all border border-blue-100/50' : 'flex items-center justify-center py-3.5 px-0 bg-blue-50 text-blue-700 rounded-2xl transition-all border border-blue-100/50'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span x-show="sidebarOpen" x-transition.opacity class="truncate">Jadwal Lab</span>
            </a>

            <!-- 3. Laporan Masalah -->
            <a href="{{ url('/siswa/laporan') }}" title="Laporan Masalah"
               :class="sidebarOpen ? 'flex items-center gap-3 px-4 py-3.5 text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-2xl font-bold text-sm transition-all' : 'flex items-center justify-center py-3.5 px-0 text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-2xl transition-all'">
                <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span x-show="sidebarOpen" x-transition.opacity class="truncate">Laporan Masalah</span>
            </a>

            <div class="pt-4 mt-4 border-t border-slate-100"></div>

            <!-- 4. Profil Akun -->
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
    <div :class="sidebarOpen ? 'md:ml-72' : 'md:ml-20'" class="flex-1 flex flex-col min-h-screen min-w-0 transition-all duration-300 ease-in-out">

        <!-- TOPBAR -->
        <nav class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200/60 sticky top-0 z-40 flex items-center justify-between px-4 sm:px-6 lg:px-8">
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

        <!-- KONTEN UTAMA JADWAL LAB -->
        <main class="flex-1 p-6 lg:p-8" x-data="{ tab: 'hari-ini' }">
            <div class="mb-8">
                <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Jadwal Lab &amp; Jurnal</h2>
                <p class="text-slate-500 font-medium mt-1">Jadwal penggunaan lab untuk kelas <span class="font-extrabold text-slate-700">{{ $kelasName }}</span>. Lakukan check-in dan check-out dari sini.</p>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm font-bold shadow-sm">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-sm font-bold shadow-sm">{{ session('error') }}</div>
            @endif

            <!-- TAB -->
            <div class="flex flex-wrap gap-2 mb-6">
                <button type="button" @click="tab = 'hari-ini'"
                    :class="tab === 'hari-ini' ? '{{ $tabClsActive }}' : '{{ $tabClsIdle }}'"
                    class="px-5 py-2.5 rounded-2xl text-sm font-bold transition-all">
                    Hari Ini <span class="ml-1 opacity-80">({{ $hariIni->count() }})</span>
                </button>
                <button type="button" @click="tab = 'mendatang'"
                    :class="tab === 'mendatang' ? '{{ $tabClsActive }}' : '{{ $tabClsIdle }}'"
                    class="px-5 py-2.5 rounded-2xl text-sm font-bold transition-all">
                    Mendatang <span class="ml-1 opacity-80">({{ $mendatang->count() }})</span>
                </button>
                <button type="button" @click="tab = 'riwayat'"
                    :class="tab === 'riwayat' ? '{{ $tabClsActive }}' : '{{ $tabClsIdle }}'"
                    class="px-5 py-2.5 rounded-2xl text-sm font-bold transition-all">
                    Riwayat <span class="ml-1 opacity-80">({{ $riwayat->count() }})</span>
                </button>
            </div>

            <!-- TAB: HARI INI -->
            <div x-show="tab === 'hari-ini'" x-cloak class="space-y-4">
                @forelse($hariIni as $schedule)
                    @include('siswa.jadwal._card', ['mode' => 'hari-ini'])
                @empty
                    <div class="bg-white rounded-3xl border border-slate-200/80 p-16 text-center shadow-sm">
                        <h3 class="text-xl font-extrabold text-slate-800 mb-1">Tidak ada jadwal hari ini</h3>
                        <p class="text-slate-500 font-medium text-sm">Anda tidak memiliki jadwal penggunaan lab untuk hari ini.</p>
                    </div>
                @endforelse
            </div>

            <!-- TAB: MENDATANG -->
            <div x-show="tab === 'mendatang'" x-cloak class="space-y-4">
                @forelse($mendatang as $schedule)
                    @include('siswa.jadwal._card', ['mode' => 'mendatang'])
                @empty
                    <div class="bg-white rounded-3xl border border-slate-200/80 p-16 text-center shadow-sm">
                        <h3 class="text-xl font-extrabold text-slate-800 mb-1">Belum ada jadwal mendatang</h3>
                        <p class="text-slate-500 font-medium text-sm">Jadwal baru dari guru akan muncul di sini.</p>
                    </div>
                @endforelse
            </div>

            <!-- TAB: RIWAYAT -->
            <div x-show="tab === 'riwayat'" x-cloak class="space-y-4">
                @forelse($riwayat as $schedule)
                    @include('siswa.jadwal._card', ['mode' => 'riwayat'])
                @empty
                    <div class="bg-white rounded-3xl border border-slate-200/80 p-16 text-center shadow-sm">
                        <h3 class="text-xl font-extrabold text-slate-800 mb-1">Belum ada riwayat</h3>
                        <p class="text-slate-500 font-medium text-sm">Riwayat jadwal dan jurnal Anda akan muncul di sini.</p>
                    </div>
                @endforelse
            </div>
        </main>
    </div>
</div>
@include('siswa._modal_form')
@endsection