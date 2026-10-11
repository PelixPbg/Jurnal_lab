@extends('layouts.admin')
@section('title', 'Import Data Siswa')

@section('content')
@php $hasil = session('import_result'); @endphp
<div class="max-w-3xl mx-auto">

    <!-- HEADER -->
    <a href="{{ url('/admin/students') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-indigo-600 transition-colors mb-4">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
        Kembali ke Data Siswa
    </a>
    <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Import Data Siswa</h2>
    <p class="text-slate-500 font-medium mt-1 mb-8">Tambahkan banyak siswa sekaligus dari file Excel, lengkap dengan akun loginnya.</p>

    @if(session('error'))
        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-sm font-bold shadow-sm">{{ session('error') }}</div>
    @endif

    <!-- HASIL IMPORT -->
    @if($hasil)
        @if(!empty($hasil['galat']))
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-sm font-bold shadow-sm">{{ $hasil['galat'] }}</div>
        @else
            <div class="mb-6 bg-white rounded-3xl border border-slate-100 shadow-sm p-6 sm:p-8">
                <h3 class="text-xl font-extrabold text-slate-800 tracking-tight mb-5">Hasil Import</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="rounded-2xl bg-emerald-50 border border-emerald-100 p-5">
                        <p class="text-xs font-extrabold text-emerald-600 uppercase tracking-widest mb-1">Berhasil</p>
                        <p class="text-3xl font-extrabold text-emerald-700">{{ $hasil['berhasil'] }}</p>
                    </div>
                    <div class="rounded-2xl bg-amber-50 border border-amber-100 p-5">
                        <p class="text-xs font-extrabold text-amber-600 uppercase tracking-widest mb-1">Dilewati</p>
                        <p class="text-3xl font-extrabold text-amber-700">{{ count($hasil['dilewati']) }}</p>
                    </div>
                </div>

                @if(count($hasil['dilewati']) > 0)
                    <p class="text-sm font-semibold text-slate-500 mt-6 mb-3">Baris berikut tidak dimasukkan. Perbaiki di Excel, lalu import ulang hanya baris tersebut.</p>
                    <div class="overflow-x-auto rounded-2xl border border-slate-100">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-100">
                                    <th class="py-3 px-4 text-xs font-extrabold text-slate-400 uppercase tracking-widest">Baris</th>
                                    <th class="py-3 px-4 text-xs font-extrabold text-slate-400 uppercase tracking-widest">NIS</th>
                                    <th class="py-3 px-4 text-xs font-extrabold text-slate-400 uppercase tracking-widest">Alasan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($hasil['dilewati'] as $lewat)
                                <tr>
                                    <td class="py-3 px-4 text-sm font-bold text-slate-500">{{ $lewat['baris'] }}</td>
                                    <td class="py-3 px-4 text-sm font-extrabold text-slate-800">{{ $lewat['data'] !== '' ? $lewat['data'] : '-' }}</td>
                                    <td class="py-3 px-4 text-sm font-medium text-rose-600">{{ $lewat['alasan'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <a href="{{ url('/admin/students') }}" class="inline-flex items-center gap-2 mt-6 px-5 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl font-bold text-sm transition-all">Lihat Data Siswa &rarr;</a>
            </div>
        @endif
    @endif

    <!-- PETUNJUK & FORM UPLOAD -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 sm:p-8">

        <div class="flex items-start gap-3 bg-indigo-50 border border-indigo-100 p-4 rounded-2xl mb-6 text-sm text-indigo-800 font-medium">
            <svg class="w-5 h-5 shrink-0 mt-0.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p><strong class="font-extrabold">Info:</strong> Username menggunakan NIS. Password default adalah NIS + (*). NIS yang sudah terdaftar akan dilewati.</p>
        </div>

        <h3 class="text-lg font-extrabold text-slate-800 tracking-tight mb-4">Langkah Import</h3>
        <ol class="space-y-4 mb-8 text-sm font-medium text-slate-600">
            <li class="flex gap-3">
                <span class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 font-extrabold flex items-center justify-center shrink-0">1</span>
                <div>
                    <p class="font-bold text-slate-800">Unduh template Excel</p>
                    <a href="{{ url('/admin/students/template') }}" class="inline-flex items-center gap-2 mt-2 px-4 py-2 bg-white hover:bg-slate-50 text-indigo-600 border border-indigo-100 rounded-xl font-bold text-xs shadow-sm transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Download Template
                    </a>
                </div>
            </li>
            <li class="flex gap-3">
                <span class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 font-extrabold flex items-center justify-center shrink-0">2</span>
                <div>
                    <p class="font-bold text-slate-800">Isi data mulai dari baris ke-2</p>
                    <p class="mt-1">Kolom <strong>nis</strong>, <strong>nama</strong>, dan <strong>kelas</strong>. Isi kelas persis seperti di Data Kelas, misalnya <span class="font-bold text-slate-800">X PPLG 1</span>.</p>
                </div>
            </li>
            <li class="flex gap-3">
                <span class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 font-extrabold flex items-center justify-center shrink-0">3</span>
                <div>
                    <p class="font-bold text-slate-800">Unggah file di bawah ini</p>
                    <p class="mt-1">Format <strong>.xlsx</strong> atau <strong>.xls</strong>, maksimal 5 MB.</p>
                </div>
            </li>
        </ol>

        <form action="{{ url('/admin/students/import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-8">
                <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-widest mb-2">File Excel</label>
                <input type="file" name="file" accept=".xlsx,.xls" required class="block w-full text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-2xl cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all file:mr-4 file:py-3.5 file:px-5 file:rounded-l-2xl file:border-0 file:bg-indigo-50 file:text-indigo-700 file:font-bold file:text-sm hover:file:bg-indigo-100">
                @error('file') <span class="block mt-2 text-rose-500 text-sm font-semibold">{{ $message }}</span> @enderror
            </div>
            <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ url('/admin/students') }}" class="inline-flex items-center px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl font-bold text-sm transition-all">Batal</a>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-2xl font-bold text-sm shadow-lg shadow-indigo-200 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Import Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection