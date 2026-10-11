<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Jurnal Lab')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F4F7FE; margin: 0; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="text-gray-800">
@php
    $teacher  = Auth::user()->teacher;
    $userName = $teacher->name ?? Auth::user()->username;
    $initial  = strtoupper(substr($userName, 0, 1));
    $words    = explode(' ', trim($userName));
    if (count($words) > 1) { $initial .= strtoupper(substr($words[1], 0, 1)); }

    $iconHome     = 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6';
    $iconCalendar = 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z';
    $iconBook     = 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253';
    $iconMonitor  = 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z';
    $iconUser     = 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z';

    // Menu Laporan Masalah & Kondisi Lab sudah dihapus dari daftar di bawah
    $menus = [
        ['Dashboard',       '/guru/dashboard',  'guru/dashboard',    $iconHome],
        ['Jadwal Lab',      '/guru/jadwal',     'guru/jadwal*',      $iconCalendar],
        ['Jurnal Siswa',    '/guru/jurnal',     'guru/jurnal*',      $iconBook],
        ['Profil Akun',     '/profil',          'profil*',           $iconUser],
    ];

    $openBase   = 'flex items-center gap-3 px-4 py-3.5 rounded-2xl font-bold text-sm transition-all';
    $closedBase = 'flex items-center justify-center py-3.5 rounded-2xl transition-all';
    $activeCls  = 'bg-blue-50 text-blue-700 border border-blue-100/50';
    $idleCls    = 'text-slate-500 hover:text-slate-800 hover:bg-slate-50';
@endphp

<div class="flex min-h-screen bg-[#F4F7FE]" x-data="{ sidebarOpen: localStorage.getItem('sidebarOpen') !== 'false' }">

    <!-- ================= SIDEBAR ================= -->
    <aside :class="sidebarOpen ? 'w-72' : 'w-20'" class="hidden md:flex flex-col fixed inset-y-0 left-0 bg-white border-r border-slate-200 z-50 transition-all duration-300 ease-in-out overflow-hidden">

        <div class="h-20 flex items-center px-6 border-b border-slate-100 shrink-0 relative">
            <div x-show="sidebarOpen" x-transition.opacity class="flex items-center gap-3 overflow-hidden w-full justify-between">
                <div class="flex items-center gap-3 overflow-hidden">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-600/20 shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconMonitor }}"></path></svg>
                    </div>
                    <div class="truncate">
                        <h1 class="font-extrabold text-slate-800 leading-tight text-xl tracking-tight">Jurnal Lab</h1>
                        <p class="text-[9px] text-slate-500 font-bold tracking-widest uppercase">Laboratory System</p>
                    </div>
                </div>
                <button @click="sidebarOpen = false; localStorage.setItem('sidebarOpen', 'false')" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-slate-100 rounded-lg transition-colors shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path></svg>
                </button>
            </div>

            <div x-show="!sidebarOpen" x-transition.opacity class="w-full flex items-center justify-center">
                <button @click="sidebarOpen = true; localStorage.setItem('sidebarOpen', 'true')" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-slate-100 rounded-lg transition-colors">
                    <svg class="w-6 h-6 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path></svg>
                </button>
            </div>
        </div>

        <div class="flex-1 py-6 px-3 space-y-1.5 overflow-y-auto overflow-x-hidden">
            @foreach($menus as $i => $m)
                @if($m[0] === 'Profil Akun')
                    <div class="pt-4 mt-4 border-t border-slate-100"></div>
                @endif
                @php $isActive = request()->is($m[2]); $state = $isActive ? $activeCls : $idleCls; @endphp
                <a href="{{ url($m[1]) }}" title="{{ $m[0] }}"
                   :class="sidebarOpen ? '{{ $openBase }} {{ $state }}' : '{{ $closedBase }} {{ $state }}'">
                    <svg class="w-5 h-5 shrink-0 {{ $isActive ? '' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="{{ $m[3] }}"></path></svg>
                    <span x-show="sidebarOpen" x-transition.opacity class="truncate">{{ $m[0] }}</span>
                </a>
            @endforeach
        </div>

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

    <!-- ================= KONTEN KANAN ================= -->
    <div :class="sidebarOpen ? 'md:ml-72' : 'md:ml-20'" class="flex-1 flex flex-col min-h-screen min-w-0 transition-all duration-300 ease-in-out">

        <nav class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200/60 sticky top-0 z-40 flex items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center">
                <div x-show="!sidebarOpen" x-transition.opacity class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center shadow-md shadow-blue-600/20 shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconMonitor }}"></path></svg>
                    </div>
                    <div>
                        <h1 class="font-extrabold text-slate-800 leading-tight text-lg tracking-tight">Jurnal Lab</h1>
                        <p class="text-[9px] text-slate-400 font-bold tracking-widest uppercase">Laboratory System</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-5">
                <a href="{{ url('/profil') }}" class="flex items-center gap-3 group">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-extrabold text-slate-800 leading-none mb-1 group-hover:text-blue-600 transition-colors">{{ $userName }}</p>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Guru</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-100 to-indigo-50 text-blue-700 flex items-center justify-center font-extrabold text-sm border-2 border-white shadow-sm ring-1 ring-slate-200">
                        {{ $initial }}
                    </div>
                </a>
            </div>
        </nav>

        <main class="flex-1 p-6 lg:p-8">
            @yield('content')
        </main>
    </div>
</div>

{{-- Dialog konfirmasi kaca (pengganti confirm bawaan browser) --}}
@include('partials._konfirmasi')
</body>
</html>