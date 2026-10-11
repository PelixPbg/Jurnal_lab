<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Jurnal Lab')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F4F7FE; margin: 0; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="text-slate-800">
@php
    $userName = Auth::user()->username ?? 'Administrator';
    $initial  = strtoupper(substr($userName, 0, 1));
@endphp

<div class="flex min-h-screen bg-[#F4F7FE]" x-data="{ sidebarOpen: localStorage.getItem('sidebarOpen') !== 'false' }">

    <!-- ================= SIDEBAR ================= -->
    <aside :class="sidebarOpen ? 'w-72' : 'w-20'" class="hidden md:flex flex-col fixed inset-y-0 left-0 bg-white border-r border-slate-200 z-50 transition-all duration-300 ease-in-out overflow-hidden">

        <!-- Header Sidebar -->
        <div class="h-20 flex items-center px-6 border-b border-slate-100 shrink-0 relative">
            <div x-show="sidebarOpen" x-transition.opacity class="flex items-center gap-3 overflow-hidden w-full justify-between">
                <div class="flex items-center gap-3 overflow-hidden">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-600/20 shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <div class="truncate">
                        <h1 class="font-extrabold text-slate-800 leading-tight text-xl tracking-tight">Admin Lab</h1>
                        <p class="text-[9px] text-slate-500 font-bold tracking-widest uppercase">Control Panel</p>
                    </div>
                </div>
                <button @click="sidebarOpen = false; localStorage.setItem('sidebarOpen', 'false')" class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-slate-100 rounded-lg transition-colors shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path></svg>
                </button>
            </div>

            <div x-show="!sidebarOpen" x-transition.opacity class="w-full flex items-center justify-center">
                <button @click="sidebarOpen = true; localStorage.setItem('sidebarOpen', 'true')" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-slate-100 rounded-lg transition-colors">
                    <svg class="w-6 h-6 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path></svg>
                </button>
            </div>
        </div>

        <!-- Menu Sidebar -->
        <div class="flex-1 py-6 px-3 space-y-1.5 overflow-y-auto overflow-x-hidden">
            
            <!-- Dashboard -->
            <a href="{{ url('/admin/dashboard') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl font-bold text-sm transition-all {{ request()->is('admin/dashboard') ? 'bg-indigo-50 text-indigo-700 border border-indigo-100/50' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50' }}" :class="!sidebarOpen && 'justify-center'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span x-show="sidebarOpen" class="truncate">Dashboard</span>
            </a>

            <!-- Dropdown: Data Pengguna -->
            <div x-data="{ open: {{ request()->is('admin/students*') || request()->is('admin/teachers*') ? 'true' : 'false' }}, flyout: false, top: 0 }"
                 @mouseenter="if (!sidebarOpen) { top = $refs.btn.getBoundingClientRect().top; flyout = true }"
                 @mouseleave="flyout = false">
                <button x-ref="btn" @click="sidebarOpen ? open = !open : (sidebarOpen = true, localStorage.setItem('sidebarOpen', 'true'), open = true, flyout = false)" class="w-full flex items-center justify-between px-4 py-3.5 rounded-2xl font-bold text-sm transition-all text-slate-500 hover:text-slate-800 hover:bg-slate-50" :class="!sidebarOpen && 'justify-center'">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span x-show="sidebarOpen" class="truncate">Data Pengguna</span>
                    </div>
                    <svg x-show="sidebarOpen" :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open && sidebarOpen" x-transition class="pl-11 pr-4 mt-1 space-y-1">
                    <a href="{{ url('/admin/students') }}" class="block py-2.5 text-sm font-semibold {{ request()->is('admin/students*') ? 'text-indigo-600' : 'text-slate-500 hover:text-slate-800' }}">Data Siswa</a>
                    <a href="{{ url('/admin/teachers') }}" class="block py-2.5 text-sm font-semibold {{ request()->is('admin/teachers*') ? 'text-indigo-600' : 'text-slate-500 hover:text-slate-800' }}">Data Guru</a>
                </div>

                <!-- Popup submenu (muncul saat hover, hanya ketika sidebar ditutup) -->
                <div x-show="flyout && !sidebarOpen" x-cloak x-transition.opacity.duration.150ms class="fixed left-[68px] z-[60] pl-3" :style="'top:' + top + 'px'">
                    <div class="w-56 bg-white rounded-2xl border border-slate-200 shadow-xl shadow-slate-200/60 p-2">
                        <p class="px-3 pt-2 pb-1.5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Data Pengguna</p>
                        <a href="{{ url('/admin/students') }}" class="block px-3 py-2.5 rounded-xl text-sm font-semibold transition-colors {{ request()->is('admin/students*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50' }}">Data Siswa</a>
                        <a href="{{ url('/admin/teachers') }}" class="block px-3 py-2.5 rounded-xl text-sm font-semibold transition-colors {{ request()->is('admin/teachers*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50' }}">Data Guru</a>
                    </div>
                </div>
            </div>

            <!-- Data Kelas -->
            <a href="{{ url('/admin/classes') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl font-bold text-sm transition-all {{ request()->is('admin/classes*') ? 'bg-indigo-50 text-indigo-700 border border-indigo-100/50' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50' }}" :class="!sidebarOpen && 'justify-center'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                <span x-show="sidebarOpen" class="truncate">Data Kelas</span>
            </a>

            <!-- Dropdown: Data Laboratorium -->
            <div x-data="{ open: {{ request()->is('admin/labs*') || request()->is('admin/computers*') ? 'true' : 'false' }}, flyout: false, top: 0 }"
                 @mouseenter="if (!sidebarOpen) { top = $refs.btn.getBoundingClientRect().top; flyout = true }"
                 @mouseleave="flyout = false">
                <button x-ref="btn" @click="sidebarOpen ? open = !open : (sidebarOpen = true, localStorage.setItem('sidebarOpen', 'true'), open = true, flyout = false)" class="w-full flex items-center justify-between px-4 py-3.5 rounded-2xl font-bold text-sm transition-all text-slate-500 hover:text-slate-800 hover:bg-slate-50" :class="!sidebarOpen && 'justify-center'">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <span x-show="sidebarOpen" class="truncate">Data Laboratorium</span>
                    </div>
                    <svg x-show="sidebarOpen" :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open && sidebarOpen" x-transition class="pl-11 pr-4 mt-1 space-y-1">
                    <a href="{{ url('/admin/labs') }}" class="block py-2.5 text-sm font-semibold {{ request()->is('admin/labs*') ? 'text-indigo-600' : 'text-slate-500 hover:text-slate-800' }}">Data Lab</a>
                    <a href="{{ url('/admin/computers') }}" class="block py-2.5 text-sm font-semibold {{ request()->is('admin/computers*') ? 'text-indigo-600' : 'text-slate-500 hover:text-slate-800' }}">Data Komputer</a>
                </div>

                <!-- Popup submenu (muncul saat hover, hanya ketika sidebar ditutup) -->
                <div x-show="flyout && !sidebarOpen" x-cloak x-transition.opacity.duration.150ms class="fixed left-[68px] z-[60] pl-3" :style="'top:' + top + 'px'">
                    <div class="w-56 bg-white rounded-2xl border border-slate-200 shadow-xl shadow-slate-200/60 p-2">
                        <p class="px-3 pt-2 pb-1.5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Data Laboratorium</p>
                        <a href="{{ url('/admin/labs') }}" class="block px-3 py-2.5 rounded-xl text-sm font-semibold transition-colors {{ request()->is('admin/labs*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50' }}">Data Lab</a>
                        <a href="{{ url('/admin/computers') }}" class="block px-3 py-2.5 rounded-xl text-sm font-semibold transition-colors {{ request()->is('admin/computers*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50' }}">Data Komputer</a>
                    </div>
                </div>
            </div>

            <!-- Laporan Masalah -->
            <a href="{{ url('/admin/reports') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl font-bold text-sm transition-all {{ request()->is('admin/reports*') ? 'bg-indigo-50 text-indigo-700 border border-indigo-100/50' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50' }}" :class="!sidebarOpen && 'justify-center'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span x-show="sidebarOpen" class="truncate">Laporan Masalah</span>
            </a>

            <!-- Kondisi Lab -->
            <a href="{{ url('/admin/kondisi-lab') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl font-bold text-sm transition-all {{ request()->is('admin/kondisi-lab*') ? 'bg-indigo-50 text-indigo-700 border border-indigo-100/50' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50' }}" :class="!sidebarOpen && 'justify-center'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                <span x-show="sidebarOpen" class="truncate">Kondisi Lab</span>
            </a>

            <div class="pt-4 mt-4 border-t border-slate-100"></div>

            <!-- Profil Akun -->
            <a href="{{ url('/profil') }}" class="flex items-center gap-3 px-4 py-3.5 text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-2xl font-bold text-sm transition-all" :class="!sidebarOpen && 'justify-center'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                <span x-show="sidebarOpen" class="truncate">Profil Akun</span>
            </a>
        </div>

        <div class="p-3 border-t border-slate-100 shrink-0">
            <a href="{{ url('/logout') }}" class="flex items-center gap-3 px-4 py-3.5 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded-2xl font-bold text-sm transition-all" :class="!sidebarOpen && 'justify-center'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                <span x-show="sidebarOpen" class="truncate">Keluar Akun</span>
            </a>
        </div>
    </aside>

    <!-- ================= KONTEN KANAN ================= -->
    <div :class="sidebarOpen ? 'md:ml-72' : 'md:ml-20'" class="flex-1 flex flex-col min-h-screen min-w-0 transition-all duration-300 ease-in-out">
        <nav class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200/60 sticky top-0 z-40 flex items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center">
                <div x-show="!sidebarOpen" x-transition.opacity class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center shadow-md shadow-blue-600/20 shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <div>
                        <h1 class="font-extrabold text-slate-800 leading-tight text-lg tracking-tight">Admin Lab</h1>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-5">
                <a href="{{ url('/profil') }}" class="flex items-center gap-3 group">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-extrabold text-slate-800 leading-none mb-1 group-hover:text-indigo-600 transition-colors">{{ $userName }}</p>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Admin</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-indigo-100 to-purple-50 text-indigo-700 flex items-center justify-center font-extrabold text-sm border-2 border-white shadow-sm ring-1 ring-slate-200">
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