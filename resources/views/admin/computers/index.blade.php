@extends('layouts.admin')
@section('title', 'Kelola Data PC')

@section('content')
<!-- HEADER -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Kelola Data PC / Komputer</h2>
        <p class="text-slate-500 font-medium mt-1">Tambah, ubah, dan hapus data komputer beserta statusnya.</p>
    </div>
    <a href="{{ url('/admin/computers/create') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-2xl font-bold text-sm shadow-lg shadow-indigo-200 transition-all w-max">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
        Tambah PC
    </a>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm font-bold shadow-sm">{{ session('success') }}</div>
@endif

<!-- TABEL PC -->
<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="py-4 px-6 text-xs font-extrabold text-slate-400 uppercase tracking-widest">Kode PC</th>
                    <th class="py-4 px-6 text-xs font-extrabold text-slate-400 uppercase tracking-widest">Nama (Opsional)</th>
                    <th class="py-4 px-6 text-xs font-extrabold text-slate-400 uppercase tracking-widest">Lokasi Lab</th>
                    <th class="py-4 px-6 text-xs font-extrabold text-slate-400 uppercase tracking-widest">Status</th>
                    <th class="py-4 px-6 text-xs font-extrabold text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($computers as $pc)
                <tr class="hover:bg-slate-50/70 transition-colors">
                    <td class="py-4 px-6">
                        <span class="inline-flex items-center px-3 py-1 rounded-lg bg-indigo-50 text-indigo-700 text-sm font-extrabold border border-indigo-100">{{ $pc->code }}</span>
                    </td>
                    <td class="py-4 px-6 text-sm font-semibold text-slate-700">{{ $pc->name ?? '-' }}</td>
                    <td class="py-4 px-6 text-sm font-semibold text-slate-600">{{ $pc->lab->name ?? 'Lab Dihapus' }}</td>
                    <td class="py-4 px-6">
                        <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs uppercase font-extrabold tracking-wider border
                            {{ $pc->status == 'baik' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : ($pc->status == 'bermasalah' ? 'bg-amber-50 text-amber-600 border-amber-100' : 'bg-rose-50 text-rose-600 border-rose-100') }}">
                            {{ $pc->status }}
                        </span>
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ url('/admin/computers/'.$pc->id.'/edit') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-100 rounded-xl text-xs font-bold transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Edit
                            </a>
                            <form action="{{ url('/admin/computers/'.$pc->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus PC ini?')">
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
            </tbody>
        </table>
    </div>
</div>
{{-- Popup kaca untuk Tambah & Edit --}}
@include('admin._modal_form')
@endsection