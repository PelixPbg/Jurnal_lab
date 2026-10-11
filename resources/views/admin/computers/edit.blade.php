@extends('layouts.admin')
@section('title', 'Edit PC')

@section('content')
<div class="max-w-2xl mx-auto">

    <!-- HEADER -->
    <a href="{{ url('/admin/computers') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-indigo-600 transition-colors mb-4">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
        Kembali ke Data PC
    </a>
    <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Edit Komputer</h2>
    <p class="text-slate-500 font-medium mt-1 mb-8">Perbarui data komputer dan statusnya.</p>

    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 sm:p-8">
        <form action="{{ url('/admin/computers/'.$computer->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-5">
                <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-widest mb-2">Pilih Laboratorium</label>
                <select name="lab_id" class="w-full px-4 py-3.5 bg-white border border-slate-200 rounded-2xl text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 transition-all" required>
                    @foreach($labs as $lab)
                        <option value="{{ $lab->id }}" {{ $computer->lab_id == $lab->id ? 'selected' : '' }}>{{ $lab->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-5">
                <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-widest mb-2">Kode PC</label>
                <input type="text" name="code" value="{{ $computer->code }}" class="w-full px-4 py-3.5 bg-white border border-slate-200 rounded-2xl text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 transition-all" required>
            </div>
            <div class="mb-5">
                <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-widest mb-2">Nama PC (Opsional)</label>
                <input type="text" name="name" value="{{ $computer->name }}" class="w-full px-4 py-3.5 bg-white border border-slate-200 rounded-2xl text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 transition-all">
            </div>
            <div class="mb-8">
                <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-widest mb-2">Status Saat Ini</label>
                <select name="status" class="w-full px-4 py-3.5 bg-white border border-slate-200 rounded-2xl text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 transition-all" required>
                    <option value="baik" {{ $computer->status == 'baik' ? 'selected' : '' }}>Normal / Baik</option>
                    <option value="bermasalah" {{ $computer->status == 'bermasalah' ? 'selected' : '' }}>Bermasalah</option>
                    <option value="rusak" {{ $computer->status == 'rusak' ? 'selected' : '' }}>Rusak / Mati</option>
                </select>
            </div>
            <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ url('/admin/computers') }}" class="inline-flex items-center px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl font-bold text-sm transition-all">Batal</a>
                <button type="submit" class="inline-flex items-center px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-2xl font-bold text-sm shadow-lg shadow-amber-200 transition-all">Update Data</button>
            </div>
        </form>
    </div>
</div>
@endsection