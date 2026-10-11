@extends('layouts.admin')
@section('title', 'Kelola Data Guru')

@section('content')
<!-- HEADER -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Kelola Data Guru</h2>
        <p class="text-slate-500 font-medium mt-1">Tambah, ubah, dan hapus data guru beserta akun loginnya.</p>
    </div>
    <div class="flex flex-wrap items-center gap-3">
        <a href="{{ url('/admin/teachers/import') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-white hover:bg-slate-50 text-slate-700 hover:text-indigo-600 border border-slate-200 rounded-2xl font-bold text-sm shadow-sm transition-all w-max">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
            Import Excel
        </a>
        <a href="{{ url('/admin/teachers/create') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-2xl font-bold text-sm shadow-lg shadow-indigo-200 transition-all w-max">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah Guru
        </a>
    </div>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm font-bold shadow-sm">{{ session('success') }}</div>
@endif

<!-- PENCARIAN + TABEL GURU -->
<div x-data="{
        q: '',
        jumlah: {{ $teachers->count() }},
        cocok(teks) {
            const kata = this.q.toLowerCase().trim().split(/\s+/).filter(Boolean);
            return kata.every(k => teks.includes(k));
        },
        hitung() {
            this.jumlah = Array.from(this.$refs.tabel.querySelectorAll('tr[data-cari]'))
                .filter(tr => this.cocok(tr.dataset.cari)).length;
        }
    }" x-init="$watch('q', () => hitung())">

    <!-- KOTAK PENCARIAN -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <div class="relative w-full sm:max-w-md">
            <svg class="w-5 h-5 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" x-model="q" @keydown.escape="q = ''" autocomplete="off" placeholder="Cari NIP atau nama guru..." class="w-full pl-12 pr-11 py-3.5 bg-white border border-slate-200 rounded-2xl text-sm font-semibold text-slate-800 placeholder-slate-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all">
            <button type="button" x-show="q !== ''" x-cloak @click="q = ''" title="Hapus pencarian" class="absolute right-3 top-1/2 -translate-y-1/2 p-1.5 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <p class="text-sm font-semibold text-slate-400">Menampilkan <span class="font-extrabold text-slate-700" x-text="jumlah"></span> dari {{ $teachers->count() }} guru</p>
    </div>

<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="py-4 px-6 text-xs font-extrabold text-slate-400 uppercase tracking-widest">NIP (Username)</th>
                    <th class="py-4 px-6 text-xs font-extrabold text-slate-400 uppercase tracking-widest">Nama Guru</th>
                    <th class="py-4 px-6 text-xs font-extrabold text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                </tr>
            </thead>
            <tbody x-ref="tabel" class="divide-y divide-slate-100">
                @foreach($teachers as $guru)
                <tr data-cari="{{ mb_strtolower($guru->nip . ' ' . $guru->name) }}" x-show="cocok($el.dataset.cari)" class="hover:bg-slate-50/70 transition-colors">
                    <td class="py-4 px-6">
                        <span class="inline-flex items-center px-3 py-1 rounded-lg bg-indigo-50 text-indigo-700 text-sm font-extrabold border border-indigo-100">{{ $guru->nip }}</span>
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-indigo-100 to-purple-50 text-indigo-700 flex items-center justify-center font-extrabold text-sm border-2 border-white shadow-sm ring-1 ring-slate-200 shrink-0">
                                {{ strtoupper(substr($guru->name, 0, 1)) }}
                            </div>
                            <span class="text-sm font-bold text-slate-800">{{ $guru->name }}</span>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ url('/admin/teachers/'.$guru->id.'/edit') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-100 rounded-xl text-xs font-bold transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Edit
                            </a>
                            <form action="{{ url('/admin/teachers/'.$guru->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus Guru ini? Akun loginnya juga akan terhapus.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-100 rounded-xl text-xs font-bold transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
                <!-- KETIKA PENCARIAN TIDAK MENEMUKAN HASIL -->
                <tr x-show="jumlah === 0 && q.trim() !== ''" x-cloak>
                    <td colspan="3" class="py-16 px-6 text-center">
                        <h3 class="text-xl font-extrabold text-slate-800 mb-1">Guru tidak ditemukan</h3>
                        <p class="text-slate-500 text-sm font-medium">Tidak ada hasil untuk "<span class="font-bold text-slate-700" x-text="q"></span>". Coba kata kunci lain.</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</div>
{{-- Popup kaca untuk Tambah & Edit --}}
@include('admin._modal_form')
@endsection