@extends('layouts.admin')
@section('title', 'Tambah Guru')

@section('content')
<div class="max-w-2xl mx-auto">

    <!-- HEADER -->
    <a href="{{ url('/admin/teachers') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-indigo-600 transition-colors mb-4">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
        Kembali ke Data Guru
    </a>
    <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Tambah Data Guru</h2>
    <p class="text-slate-500 font-medium mt-1 mb-8">Isi data guru baru untuk membuat akun loginnya.</p>

    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 sm:p-8">

        <div class="flex items-start gap-3 bg-indigo-50 border border-indigo-100 p-4 rounded-2xl mb-6 text-sm text-indigo-800 font-medium">
            <svg class="w-5 h-5 shrink-0 mt-0.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p><strong class="font-extrabold">Info:</strong> Username otomatis menggunakan NIP. Password default adalah NIP + tanda bintang (*).</p>
        </div>

        <form action="{{ url('/admin/teachers') }}" method="POST">
            @csrf
            <div class="mb-5">
                <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-widest mb-2">NIP</label>
                <input type="text" name="nip" class="w-full px-4 py-3.5 bg-white border border-slate-200 rounded-2xl text-sm font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all" placeholder="Contoh: 198001" required>
                @error('nip') <span class="block mt-2 text-rose-500 text-sm font-semibold">{{ $message }}</span> @enderror
            </div>
            <div class="mb-8">
                <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-widest mb-2">Nama Lengkap</label>
                <input type="text" name="name" class="w-full px-4 py-3.5 bg-white border border-slate-200 rounded-2xl text-sm font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all" placeholder="Nama Guru" required>
            </div>
            <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ url('/admin/teachers') }}" class="inline-flex items-center px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl font-bold text-sm transition-all">Batal</a>
                <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-2xl font-bold text-sm shadow-lg shadow-indigo-200 transition-all">Simpan Data</button>
            </div>
        </form>
    </div>
</div>
@endsection