@extends('layouts.app')
@section('title', 'Profil Akun - Jurnal Lab')

@section('content')
<!-- Import Font Plus Jakarta Sans & Alpine.js -->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: #F4F7FE;
        margin: 0;
        overflow: hidden !important; /* Mencegah double scroll bar karena pakai fixed wrapper */
    }
    
    /* Menyembunyikan elemen navbar/margin bawaan layouts.app agar tidak mengganggu layout fixed ini */
    body > nav, body > header, #app > nav, #app > header { display: none !important; }
</style>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

@php
    $user = Auth::user();
    $role = $user->role;
    $name = $user->username;

    $idLabel = 'ID Pengguna';
    $idValue = '-';
    $subLabel = 'Divisi / Bagian';
    $subValue = '-';

    if($role == 'siswa' && $user->student) {
        $name = $user->student->name;
        $idLabel = 'Nomor Induk Siswa (NIS)';
        $idValue = $user->student->nis ?? '-';
        $subLabel = 'Kelas Akademik';
        $subValue = $user->student->classRoom->name ?? $user->student->class->name ?? '-';
    } elseif($role == 'guru' && $user->teacher) {
        $name = $user->teacher->name;
        $idLabel = 'NIP / NUPTK';
        $idValue = $user->teacher->nip ?? '-';
        $subLabel = 'Posisi';
        $subValue = 'Guru Pengajar';
    } elseif($role == 'admin') {
        $name = 'Administrator';
        $idLabel = 'Hak Akses';
        $idValue = 'Super Admin';
        $subLabel = 'Divisi / Bagian';
        $subValue = 'Manajemen Sistem';
    }

    $initial = strtoupper(substr($name, 0, 1));
    $words = explode(' ', $name);
    if(count($words) > 1) {
        $initial .= strtoupper(substr($words[1], 0, 1));
    }

    // ==========================================
    // TEMA: semua role (Admin, Guru, Siswa) memakai warna biru yang sama
    // Hanya judul aplikasi yang dibedakan untuk Admin
    // ==========================================
    $appTitle     = $role == 'admin' ? 'Admin Lab' : 'Jurnal Lab';
    $appSubtitle  = $role == 'admin' ? 'CONTROL PANEL' : 'LABORATORY SYSTEM';
    
    $logoGradient = 'from-blue-600 to-indigo-600 shadow-blue-600/20';
    $textHover    = 'hover:text-blue-600';
    $groupHover   = 'group-hover:text-blue-600';
    $bgHover      = 'hover:bg-blue-50/50';
    
    $activeMenu   = 'bg-blue-50 text-blue-700 border-blue-100/50';
    
    $avatarMini   = 'from-blue-100 to-indigo-50 text-blue-700';
    $avatarBig    = 'from-blue-600 to-indigo-600 shadow-blue-600/20';
    
    $badgeClass   = 'bg-blue-50 text-blue-600 border-blue-100';
    $cardGradient = 'from-blue-50';
    
    $btnClass     = 'bg-blue-600 shadow-blue-600/20 hover:bg-blue-700';
    $inputFocus   = 'focus:ring-blue-600 focus:border-blue-600';
@endphp

<!-- WRAPPER UTAMA (Fixed Full Screen Flexbox untuk menghilangkan gap padding dari layouts.app) -->
<div class="fixed inset-0 flex bg-[#F4F7FE] z-[9999]" x-data="{ sidebarOpen: localStorage.getItem('sidebarOpen') !== 'false' }">

    <!-- ================= SIDEBAR KIRI ================= -->
    <aside :class="sidebarOpen ? 'w-72' : 'w-20'" class="hidden md:flex flex-col bg-white border-r border-slate-200 transition-all duration-300 ease-in-out overflow-hidden shrink-0">

        <!-- HEADER SIDEBAR -->
        <div class="h-20 flex items-center px-6 border-b border-slate-100 shrink-0 relative">

            <!-- Logo lengkap di dalam sidebar (Tampil saat sidebar DIBUKA) -->
            <div x-show="sidebarOpen" x-transition.opacity class="flex items-center gap-3 overflow-hidden w-full justify-between">
                <div class="flex items-center gap-3 overflow-hidden">
                    <div class="w-10 h-10 bg-gradient-to-br {{ $logoGradient }} rounded-xl flex items-center justify-center shadow-lg shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $role == 'admin' ? 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z' : 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z' }}"></path></svg>
                    </div>
                    <div class="truncate">
                        <h1 class="font-extrabold text-slate-800 leading-tight text-xl tracking-tight">{{ $appTitle }}</h1>
                        <p class="text-[9px] text-slate-500 font-bold tracking-widest uppercase">{{ $appSubtitle }}</p>
                    </div>
                </div>

                <!-- Tombol Panah Tutup Sidebar -->
                <button @click="sidebarOpen = false; localStorage.setItem('sidebarOpen', 'false')" class="p-1.5 text-slate-400 {{ $textHover }} hover:bg-slate-100 rounded-lg transition-colors shrink-0">
                    <svg class="w-5 h-5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path></svg>
                </button>
            </div>

            <!-- Tombol Panah Buka Sidebar (Tampil saat sidebar DITUTUP) -->
            <div x-show="!sidebarOpen" x-transition.opacity class="w-full flex items-center justify-center">
                <button @click="sidebarOpen = true; localStorage.setItem('sidebarOpen', 'true')" class="p-2 text-slate-400 {{ $textHover }} hover:bg-slate-100 rounded-lg transition-colors">
                    <svg class="w-6 h-6 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path></svg>
                </button>
            </div>

        </div>

        <!-- Menu Navigasi Utama -->
        <div class="flex-1 py-6 px-3 space-y-1.5 overflow-y-auto overflow-x-hidden">
            @if($role == 'siswa')
                <a href="{{ url('/siswa/dashboard') }}" title="Dashboard"
                   :class="sidebarOpen ? 'flex items-center gap-3 px-4 py-3.5 text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-2xl font-bold text-sm transition-all' : 'flex items-center justify-center py-3.5 px-0 text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-2xl transition-all'">
                    <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span x-show="sidebarOpen" x-transition.opacity class="truncate">Dashboard</span>
                </a>
                <a href="{{ url('/siswa/jadwal') }}" title="Jadwal Lab"
                   :class="sidebarOpen ? 'flex items-center gap-3 px-4 py-3.5 text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-2xl font-bold text-sm transition-all' : 'flex items-center justify-center py-3.5 px-0 text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-2xl transition-all'">
                    <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span x-show="sidebarOpen" x-transition.opacity class="truncate">Jadwal Lab</span>
                </a>
                <a href="{{ url('/siswa/laporan') }}" title="Laporan Masalah"
                   :class="sidebarOpen ? 'flex items-center gap-3 px-4 py-3.5 text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-2xl font-bold text-sm transition-all' : 'flex items-center justify-center py-3.5 px-0 text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-2xl transition-all'">
                    <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span x-show="sidebarOpen" x-transition.opacity class="truncate">Laporan Masalah</span>
                </a>
            @elseif($role == 'guru')
                @php
                    $guruMenus = [
                        ['Dashboard',       '/guru/dashboard',   'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                        ['Jadwal Lab',      '/guru/jadwal',      'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                        ['Jurnal Siswa',    '/guru/jurnal',      'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                    ];
                @endphp
                @foreach($guruMenus as $m)
                    <a href="{{ url($m[1]) }}" title="{{ $m[0] }}"
                       :class="sidebarOpen ? 'flex items-center gap-3 px-4 py-3.5 text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-2xl font-bold text-sm transition-all' : 'flex items-center justify-center py-3.5 px-0 text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-2xl transition-all'">
                        <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="{{ $m[2] }}"></path></svg>
                        <span x-show="sidebarOpen" x-transition.opacity class="truncate">{{ $m[0] }}</span>
                    </a>
                @endforeach
            @elseif($role == 'admin')
                <!-- Dashboard -->
                <a href="{{ url('/admin/dashboard') }}" title="Dashboard"
                   :class="sidebarOpen ? 'flex items-center gap-3 px-4 py-3.5 text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-2xl font-bold text-sm transition-all' : 'flex items-center justify-center py-3.5 px-0 text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-2xl transition-all'">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span x-show="sidebarOpen" class="truncate">Dashboard</span>
                </a>

                <!-- Dropdown: Data Pengguna -->
                <div x-data="{ open: false }">
                    <button @click="sidebarOpen ? open = !open : (sidebarOpen = true, open = true)" title="Data Pengguna"
                            class="w-full transition-all text-slate-500 hover:text-slate-800 hover:bg-slate-50"
                            :class="sidebarOpen ? 'flex items-center justify-between px-4 py-3.5 rounded-2xl font-bold text-sm' : 'flex items-center justify-center py-3.5 px-0 rounded-2xl'">
                        <div :class="sidebarOpen ? 'flex items-center gap-3' : 'flex justify-center w-full'">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <span x-show="sidebarOpen" class="truncate">Data Pengguna</span>
                        </div>
                        <svg x-show="sidebarOpen" :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <!-- Sub-menu hanya tampil jika dropdown terbuka DAN sidebar dalam posisi terbuka -->
                    <div x-show="open && sidebarOpen" x-transition class="pl-11 pr-4 mt-1 space-y-1">
                        <a href="{{ url('/admin/students') }}" class="block py-2.5 text-sm font-semibold text-slate-500 hover:text-slate-800">Data Siswa</a>
                        <a href="{{ url('/admin/teachers') }}" class="block py-2.5 text-sm font-semibold text-slate-500 hover:text-slate-800">Data Guru</a>
                    </div>
                </div>

                <!-- Data Kelas -->
                <a href="{{ url('/admin/classes') }}" title="Data Kelas"
                   :class="sidebarOpen ? 'flex items-center gap-3 px-4 py-3.5 text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-2xl font-bold text-sm transition-all' : 'flex items-center justify-center py-3.5 px-0 text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-2xl transition-all'">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    <span x-show="sidebarOpen" class="truncate">Data Kelas</span>
                </a>

                <!-- Dropdown: Data Laboratorium -->
                <div x-data="{ open: false }">
                    <button @click="sidebarOpen ? open = !open : (sidebarOpen = true, open = true)" title="Data Laboratorium"
                            class="w-full transition-all text-slate-500 hover:text-slate-800 hover:bg-slate-50"
                            :class="sidebarOpen ? 'flex items-center justify-between px-4 py-3.5 rounded-2xl font-bold text-sm' : 'flex items-center justify-center py-3.5 px-0 rounded-2xl'">
                        <div :class="sidebarOpen ? 'flex items-center gap-3' : 'flex justify-center w-full'">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <span x-show="sidebarOpen" class="truncate">Data Laboratorium</span>
                        </div>
                        <svg x-show="sidebarOpen" :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <!-- Sub-menu hanya tampil jika dropdown terbuka DAN sidebar dalam posisi terbuka -->
                    <div x-show="open && sidebarOpen" x-transition class="pl-11 pr-4 mt-1 space-y-1">
                        <a href="{{ url('/admin/labs') }}" class="block py-2.5 text-sm font-semibold text-slate-500 hover:text-slate-800">Data Lab</a>
                        <a href="{{ url('/admin/computers') }}" class="block py-2.5 text-sm font-semibold text-slate-500 hover:text-slate-800">Data Komputer</a>
                    </div>
                </div>

                <!-- Laporan Masalah -->
                <a href="{{ url('/admin/reports') }}" title="Laporan Masalah"
                   :class="sidebarOpen ? 'flex items-center gap-3 px-4 py-3.5 text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-2xl font-bold text-sm transition-all' : 'flex items-center justify-center py-3.5 px-0 text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-2xl transition-all'">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span x-show="sidebarOpen" class="truncate">Laporan Masalah</span>
                </a>

                <!-- Kondisi Lab -->
                <a href="{{ url('/admin/kondisi-lab') }}" title="Kondisi Lab"
                   :class="sidebarOpen ? 'flex items-center gap-3 px-4 py-3.5 text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-2xl font-bold text-sm transition-all' : 'flex items-center justify-center py-3.5 px-0 text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-2xl transition-all'">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <span x-show="sidebarOpen" class="truncate">Kondisi Lab</span>
                </a>
            @endif

            <div class="pt-4 mt-4 border-t border-slate-100"></div>

            <!-- Profil Akun (Aktif) -->
            <a href="{{ url('/profil') }}" title="Profil Akun"
               :class="sidebarOpen ? 'flex items-center gap-3 px-4 py-3.5 {{ $activeMenu }} rounded-2xl font-bold text-sm transition-all border' : 'flex items-center justify-center py-3.5 px-0 {{ $activeMenu }} rounded-2xl transition-all border'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
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
    <div class="flex-1 flex flex-col h-screen overflow-y-auto relative transition-all duration-300 ease-in-out bg-[#F4F7FE]">

        <!-- TOPBAR -->
        <nav class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200/60 sticky top-0 z-40 flex items-center justify-between px-4 sm:px-6 lg:px-8 shrink-0">

            <!-- LOGO DI TOPBAR (Hanya muncul saat sidebar ditutup) -->
            <div class="flex items-center">
                <div x-show="!sidebarOpen" x-transition.opacity class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br {{ $logoGradient }} rounded-xl flex items-center justify-center shadow-md shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $role == 'admin' ? 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z' : 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z' }}"></path></svg>
                    </div>
                    <div>
                        <h1 class="font-extrabold text-slate-800 leading-tight text-lg tracking-tight">{{ $appTitle }}</h1>
                        <p class="text-[9px] text-slate-400 font-bold tracking-widest uppercase">{{ $appSubtitle }}</p>
                    </div>
                </div>
            </div>

            <!-- SISI KANAN TOPBAR -->
            <div class="flex items-center gap-5">
                @if($role != 'guru')
                <a href="{{ url('/notifikasi') }}" class="relative p-2.5 text-slate-400 {{ $textHover }} {{ $bgHover }} rounded-full transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"></path></svg>
                </a>
                @endif

                <a href="{{ url('/profil') }}" class="flex items-center gap-3 group">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-extrabold text-slate-800 leading-none mb-1 {{ $groupHover }} transition-colors">{{ $name }}</p>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ $role }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr {{ $avatarMini }} flex items-center justify-center font-extrabold text-sm border-2 border-white shadow-sm ring-1 ring-slate-200">
                        {{ $initial }}
                    </div>
                </a>
            </div>
        </nav>

        <!-- KONTEN UTAMA PROFIL -->
        <main class="flex-1 p-6 lg:p-8">
            <div class="mb-8">
                <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Pengaturan Profil & Keamanan</h2>
                <p class="text-slate-500 font-medium mt-1">Kelola kredensial akun dan lihat informasi identitas Anda di sistem.</p>
            </div>

            @if(session('success'))
                <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm font-bold flex items-center gap-3 shadow-sm">
                    <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-8 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl text-sm font-bold shadow-sm">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                <!-- KARTU IDENTITAS PROFIL -->
                <div class="lg:col-span-5">
                    <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm text-center relative overflow-hidden h-full">
                        <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-b {{ $cardGradient }} to-white"></div>

                        <div class="relative z-10 flex flex-col items-center">
                            <div class="w-28 h-28 mb-5 rounded-full bg-gradient-to-br {{ $avatarBig }} flex items-center justify-center text-white text-3xl font-extrabold shadow-xl ring-4 ring-white">
                                {{ $initial }}
                            </div>

                            <h3 class="text-2xl font-extrabold text-slate-800 tracking-tight">{{ $name }}</h3>
                            <div class="mt-2 mb-8">
                                <span class="inline-block {{ $badgeClass }} px-4 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-widest border">
                                    {{ $role }}
                                </span>
                            </div>

                            <div class="w-full bg-slate-50/70 border border-slate-100 rounded-2xl p-5 text-left mb-6">
                                <div class="mb-4">
                                    <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">{{ $idLabel }}</p>
                                    <p class="text-base font-bold text-slate-800">{{ $idValue }}</p>
                                </div>
                                <div class="pt-4 border-t border-slate-200/60">
                                    <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">{{ $subLabel }}</p>
                                    <p class="text-base font-bold text-slate-800">{{ $subValue }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-2.5 text-left px-2">
                                <div class="text-amber-500 mt-0.5 shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <p class="text-[11px] font-semibold text-slate-500 leading-relaxed">
                                    Data identitas dan kelas bersifat resmi dari sekolah dan hanya dapat diubah oleh Administrator sistem.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FORM KEAMANAN AKUN -->
                <div class="lg:col-span-7">
                    <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm h-full">
                        <div class="mb-8 border-b border-slate-100 pb-5">
                            <h3 class="text-xl font-extrabold text-slate-800 tracking-tight mb-2">Keamanan Akun</h3>
                            <p class="text-sm font-medium text-slate-500">Gunakan password yang kuat untuk menjaga keamanan akun Anda.</p>
                        </div>

                        <form action="{{ url('/profil') }}" method="POST" class="space-y-6">
                            @csrf

                            <div>
                                <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-widest mb-2">Nama Lengkap (Terkunci)</label>
                                <div class="relative">
                                    <input type="text" value="{{ $name }}" disabled class="w-full bg-slate-50 border border-slate-200 text-slate-500 rounded-xl px-4 py-3.5 pr-10 font-bold text-sm cursor-not-allowed">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="password" class="block text-xs font-extrabold text-slate-700 uppercase tracking-widest mb-2">Password Baru</label>
                                <input type="password" id="password" name="password" placeholder="Minimal 6 karakter" class="w-full bg-white border border-slate-300 text-slate-800 rounded-xl px-4 py-3.5 focus:ring-2 {{ $inputFocus }} font-semibold text-sm">
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-xs font-extrabold text-slate-700 uppercase tracking-widest mb-2">Konfirmasi Password Baru</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ketik ulang password baru" class="w-full bg-white border border-slate-300 text-slate-800 rounded-xl px-4 py-3.5 focus:ring-2 {{ $inputFocus }} font-semibold text-sm">
                            </div>

                            <div class="pt-6 flex items-center justify-end gap-4 border-t border-slate-100 mt-8">
                                <button type="reset" class="px-6 py-3 bg-white border border-slate-300 text-slate-700 rounded-xl text-sm font-bold hover:bg-slate-50 transition-colors">
                                    Batal
                                </button>
                                <button type="submit" class="px-6 py-3 {{ $btnClass }} text-white rounded-xl text-sm font-bold shadow-md transition-all active:scale-95">
                                    Perbarui Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>
@endsection