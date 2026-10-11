<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Jurnal Lab</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js (untuk tombol lihat password & status tombol) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #F4F7FE; 
        }
        [x-cloak] { display: none !important; }

        @keyframes naik {
            from { opacity: 0; transform: translateY(14px); }
            to   { opacity: 1; transform: none; }
        }
        .anim-naik { animation: naik .5s ease-out both; }

        /* Netralkan warna biru bawaan autofill Chrome supaya kolom tetap bernuansa abu-abu */
        input:-webkit-autofill,
        input:-webkit-autofill:hover {
            -webkit-box-shadow: 0 0 0 1000px #f8fafc inset !important;
            -webkit-text-fill-color: #1e293b !important;
            caret-color: #1e293b;
            transition: background-color 9999s ease-in-out 0s;
        }
        input:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0 1000px #ffffff inset !important;
            -webkit-text-fill-color: #1e293b !important;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 sm:p-6 relative overflow-x-hidden">

    <!-- Dekorasi latar belakang -->
    <div class="pointer-events-none fixed -top-32 -left-32 w-96 h-96 bg-blue-300 rounded-full blur-3xl opacity-30"></div>
    <div class="pointer-events-none fixed -bottom-32 -right-32 w-96 h-96 bg-indigo-300 rounded-full blur-3xl opacity-30"></div>

    <!-- Card Container -->
    <div class="anim-naik relative bg-white rounded-[2rem] shadow-2xl shadow-slate-300/40 flex w-full max-w-5xl overflow-hidden border border-slate-100">
        
        <!-- Sisi Kiri: Visual / Branding (Akan sembunyi di layar HP) -->
        <div class="hidden md:flex md:w-1/2 bg-gradient-to-br from-blue-600 via-blue-600 to-indigo-700 flex-col justify-between p-10 text-white relative overflow-hidden">
            <!-- Pola titik + ornamen dekorasi -->
            <div class="absolute inset-0 opacity-60" style="background-image: radial-gradient(rgba(255,255,255,0.14) 1px, transparent 1px); background-size: 22px 22px;"></div>
            <div class="absolute top-0 left-0 w-72 h-72 bg-white rounded-full mix-blend-overlay filter blur-3xl opacity-10 transform -translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 right-0 w-80 h-80 bg-indigo-900 rounded-full mix-blend-multiply filter blur-3xl opacity-40 transform translate-x-1/3 translate-y-1/3"></div>

            <!-- Logo -->
            <div class="relative z-10 flex items-center gap-3">
                <div class="w-11 h-11 bg-white/15 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/25 shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <span class="text-lg font-extrabold tracking-tight">Jurnal Lab</span>
            </div>

            <!-- Teks utama + fitur -->
            <div class="relative z-10 py-8">
                <h1 class="text-3xl font-extrabold leading-tight tracking-tight mb-3">
                    Kelola aktivitas<br>laboratorium dengan mudah.
                </h1>
                <p class="text-blue-100 text-sm font-medium leading-relaxed max-w-sm mb-6">
                    Sistem Informasi Manajemen & Monitoring Penggunaan Laboratorium Komputer.
                </p>

                <ul class="space-y-3">
                    <li class="flex items-center gap-3">
                        <span class="w-9 h-9 rounded-xl bg-white/15 border border-white/20 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </span>
                        <span class="text-sm font-semibold text-blue-50">Jurnal check-in &amp; check-out praktikum</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="w-9 h-9 rounded-xl bg-white/15 border border-white/20 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        </span>
                        <span class="text-sm font-semibold text-blue-50">Verifikasi jurnal oleh guru pengampu</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="w-9 h-9 rounded-xl bg-white/15 border border-white/20 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </span>
                        <span class="text-sm font-semibold text-blue-50">Pantau kondisi PC &amp; laporan kerusakan</span>
                    </li>
                </ul>
            </div>

            <p class="relative z-10 text-xs font-semibold text-blue-100">Untuk Admin, Guru, dan Siswa</p>
        </div>

        <!-- Sisi Kanan: Form Login -->
        <div class="w-full md:w-1/2 p-8 sm:p-10 md:p-12 flex flex-col justify-center">

            <!-- Logo ringkas (hanya tampil di HP, karena sisi kiri disembunyikan) -->
            <div class="md:hidden flex items-center gap-3 mb-6">
                <div class="w-11 h-11 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-600/20">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <h1 class="font-extrabold text-slate-800 leading-tight text-xl tracking-tight">Jurnal Lab</h1>
                    <p class="text-[9px] text-slate-500 font-bold tracking-widest uppercase">Laboratory System</p>
                </div>
            </div>

            <div class="mb-6">
                <h2 class="text-3xl font-extrabold text-slate-800 mb-2 tracking-tight">Selamat Datang 👋</h2>
                <p class="text-slate-500 font-medium text-sm">Silakan masuk menggunakan akun Anda.</p>
            </div>

            <!-- Notifikasi Error (Desain Baru) -->
            @if ($errors->any())
                <div role="alert" class="bg-rose-50 border border-rose-200 p-4 mb-5 rounded-2xl shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-rose-100 rounded-full flex items-center justify-center shrink-0">
                            <svg class="h-5 w-5 text-rose-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <p class="text-sm text-rose-700 font-bold">{{ $errors->first() }}</p>
                    </div>
                </div>
            @endif

            <form action="{{ url('/login') }}" method="POST" class="space-y-4"
                  x-data="{ show: false, caps: false, loading: false }"
                  @submit="loading = true"
                  @pageshow.window="loading = false">
                @csrf
                
                <!-- Input Username -->
                <div>
                    <label class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-widest mb-2" for="username">
                        Username (Admin / NIP / NIS)
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <input id="username" name="username" type="text" required {{ old('username') ? '' : 'autofocus' }}
                            value="{{ old('username') }}" autocomplete="username"
                            class="block w-full pl-11 pr-4 py-3 {{ $errors->any() ? 'bg-rose-50/40 border-rose-300' : 'bg-slate-50 border-slate-200' }} border rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-600 focus:border-blue-600 text-sm font-bold text-slate-800 placeholder-slate-400 placeholder:font-medium transition-all outline-none" 
                            placeholder="Masukkan username Anda">
                    </div>
                </div>

                <!-- Input Password -->
                <div>
                    <label class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-widest mb-2" for="password">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input id="password" name="password" type="password" required {{ old('username') ? 'autofocus' : '' }}
                            :type="show ? 'text' : 'password'" autocomplete="current-password"
                            @keyup="caps = $event.getModifierState && $event.getModifierState('CapsLock')"
                            @keydown="caps = $event.getModifierState && $event.getModifierState('CapsLock')"
                            class="block w-full pl-11 pr-12 py-3 {{ $errors->any() ? 'bg-rose-50/40 border-rose-300' : 'bg-slate-50 border-slate-200' }} border rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-600 focus:border-blue-600 text-sm font-bold text-slate-800 placeholder-slate-400 placeholder:font-medium transition-all outline-none" 
                            placeholder="••••••••">

                        <!-- Tombol mata: lihat / sembunyikan password -->
                        <button type="button" @click="show = !show"
                                :aria-label="show ? 'Sembunyikan password' : 'Tampilkan password'"
                                :title="show ? 'Sembunyikan password' : 'Tampilkan password'"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-blue-600 focus:outline-none focus:text-blue-600 transition-colors">
                            <!-- Ikon mata (password tersembunyi) -->
                            <svg x-show="!show" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <!-- Ikon mata dicoret (password terlihat) -->
                            <svg x-show="show" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Peringatan Caps Lock -->
                    <p x-show="caps" x-cloak x-transition.opacity class="mt-2 flex items-center gap-1.5 text-xs font-bold text-amber-600">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Caps Lock sedang aktif
                    </p>
                </div>

                <!-- Checkbox Ingat Saya -->
                <div class="flex items-center justify-between pt-1">
                    <div class="flex items-center gap-2.5">
                        <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded cursor-pointer">
                        <label for="remember_me" class="block text-sm font-semibold text-slate-600 cursor-pointer select-none">
                            Ingat sesi saya
                        </label>
                    </div>
                </div>

                <!-- Tombol Login -->
                <div class="pt-1">
                    <button type="submit" :disabled="loading" :class="loading && 'opacity-80 cursor-not-allowed'"
                        class="w-full flex justify-center items-center gap-2 py-3 px-4 border border-transparent rounded-xl shadow-lg shadow-blue-600/25 text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all active:scale-[0.98]">
                        <span x-show="!loading" class="inline-flex items-center gap-2">
                            Masuk ke Sistem
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </span>
                        <span x-show="loading" x-cloak class="inline-flex items-center gap-2">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                            Memproses...
                        </span>
                    </button>
                </div>

                <p class="text-center text-xs font-semibold text-slate-400">
                    Lupa password? Hubungi administrator lab.
                </p>
            </form>
            
            <!-- Footer Form -->
            <div class="mt-6 pt-5 border-t border-slate-100 text-center text-xs font-bold text-slate-400 uppercase tracking-wider">
                &copy; {{ date('Y') }} Jurnal Lab. All rights reserved.
            </div>
        </div>
    </div>

</body>
</html>