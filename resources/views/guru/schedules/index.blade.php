@extends('layouts.guru')
@section('title', 'Jadwal Lab - Guru')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Jadwal Penggunaan Lab</h2>
        <p class="text-slate-500 font-medium text-sm mt-1">Atur jadwal praktikum kelas Anda di laboratorium.</p>
    </div>
    <a href="{{ url('/guru/jadwal/create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold text-sm shadow-sm shadow-blue-600/20 transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
        Buat Jadwal Baru
    </a>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl text-sm font-bold">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="bg-slate-50/70 border-b border-slate-100 text-slate-400">
                    <th class="py-4 px-6 font-extrabold uppercase tracking-widest text-[11px]">Tanggal</th>
                    <th class="py-4 px-6 font-extrabold uppercase tracking-widest text-[11px]">Waktu</th>
                    <th class="py-4 px-6 font-extrabold uppercase tracking-widest text-[11px]">Laboratorium</th>
                    <th class="py-4 px-6 font-extrabold uppercase tracking-widest text-[11px]">Kelas</th>
                    <th class="py-4 px-6 font-extrabold uppercase tracking-widest text-[11px]">Mata Pelajaran</th>
                    <th class="py-4 px-6 font-extrabold uppercase tracking-widest text-[11px]">Status</th>
                    <th class="py-4 px-6 font-extrabold uppercase tracking-widest text-[11px] text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($schedules as $schedule)
                <tr class="hover:bg-slate-50/60 transition-colors">
                    <td class="py-4 px-6 font-bold text-slate-800">{{ \Carbon\Carbon::parse($schedule->date)->translatedFormat('d M Y') }}</td>
                    <td class="py-4 px-6 font-semibold text-blue-600">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
                    <td class="py-4 px-6 font-medium text-slate-600">{{ $schedule->lab->name ?? '-' }}</td>
                    <td class="py-4 px-6 font-bold text-slate-700">{{ $schedule->classRoom->name ?? '-' }}</td>
                    <td class="py-4 px-6 font-bold text-slate-800">{{ $schedule->subject }}</td>
                    <td class="py-4 px-6">
                        @if(\Carbon\Carbon::parse($schedule->date)->isPast())
                            <span class="inline-flex px-3 py-1.5 bg-slate-100 text-slate-500 rounded-xl text-xs font-bold border border-slate-200">Selesai</span>
                        @else
                            <span class="inline-flex px-3 py-1.5 bg-blue-50 text-blue-600 rounded-xl text-xs font-bold border border-blue-100">Terjadwal</span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-center">
                        <div class="inline-flex items-center gap-2">
                            <a href="{{ url('/guru/jadwal/' . $schedule->id . '/edit') }}" class="px-3.5 py-2 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-xl text-xs font-bold transition-colors">Edit</a>
                            <form action="{{ url('/guru/jadwal/' . $schedule->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3.5 py-2 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-xl text-xs font-bold transition-colors">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-16 text-center">
                        <h3 class="text-lg font-extrabold text-slate-800 mb-1">Belum ada jadwal</h3>
                        <p class="text-slate-400 font-medium text-sm">Klik "Buat Jadwal Baru" untuk menambahkan jadwal praktikum.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Popup kaca untuk Buat & Edit Jadwal --}}
@include('guru.schedules._modal')
@endsection