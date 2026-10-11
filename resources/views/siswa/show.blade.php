@extends('layouts.app')
@section('title', 'Detail Jurnal Siswa')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Detail Jurnal Lab</h2>
            <p class="text-slate-500 font-medium text-sm mt-1">Rincian aktivitas dan kondisi fasilitas lab.</p>
        </div>
        <a href="{{ url('/siswa/dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 rounded-xl font-bold text-sm shadow-sm transition-all">
            &larr; Kembali
        </a>
    </div>

    <!-- Informasi Jadwal -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="bg-slate-50/70 px-8 py-5 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-extrabold text-slate-800 text-base tracking-tight">Informasi Jadwal</h3>
            
            <!-- Badge Status -->
            @if($journal->status == 'approved')
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-emerald-50 text-emerald-600 text-xs font-bold border border-emerald-100">Disetujui</span>
            @elseif($journal->status == 'submitted')
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-blue-50 text-blue-600 text-xs font-bold border border-blue-100">Terkirim</span>
            @else
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-amber-50 text-amber-600 text-xs font-bold border border-amber-100">Draft</span>
            @endif
        </div>
        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
            <div class="bg-slate-50/50 p-4 rounded-2xl border border-slate-100">
                <p class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-1">Mata Pelajaran</p>
                <p class="font-bold text-slate-800 text-base">{{ $journal->schedule->subject }}</p>
            </div>
            <div class="bg-slate-50/50 p-4 rounded-2xl border border-slate-100">
                <p class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-1">Guru Pengajar</p>
                <p class="font-bold text-slate-800 text-base">{{ $journal->schedule->teacher->name ?? '-' }}</p>
            </div>
            <div class="bg-slate-50/50 p-4 rounded-2xl border border-slate-100">
                <p class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-1">Laboratorium</p>
                <p class="font-bold text-slate-800 text-base">{{ $journal->schedule->lab->name }}</p>
            </div>
            <div class="bg-slate-50/50 p-4 rounded-2xl border border-slate-100">
                <p class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-1">Waktu Check-In / Check-Out</p>
                <p class="font-bold text-slate-800 text-base">
                    {{ \Carbon\Carbon::parse($journal->check_in_at)->format('H:i') }} - 
                    {{ $journal->check_out_at ? \Carbon\Carbon::parse($journal->check_out_at)->format('H:i') : 'Belum selesai' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Laporan Siswa -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="bg-slate-50/70 px-8 py-5 border-b border-slate-100">
            <h3 class="font-extrabold text-slate-800 text-base tracking-tight">Laporan & Refleksi Siswa</h3>
        </div>
        <div class="p-8 space-y-5 text-sm">
            <div>
                <p class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-2">Aktivitas Pembelajaran:</p>
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 text-slate-700 font-medium">
                    {{ $journal->activity_notes ?? 'Belum diisi' }}
                </div>
            </div>
            <div>
                <p class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-2">Analisis / Refleksi Diri:</p>
                <div class="bg-blue-50/50 p-4 rounded-2xl border border-blue-100/60 text-slate-700 font-medium italic">
                    "{{ $journal->student_notes ?? 'Belum ada refleksi' }}"
                </div>
            </div>
        </div>
    </div>

    <!-- Kondisi Fasilitas (Checklist) -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="bg-slate-50/70 px-8 py-5 border-b border-slate-100">
            <h3 class="font-extrabold text-slate-800 text-base tracking-tight">Kondisi Fasilitas Lab</h3>
        </div>
        <div class="overflow-x-auto p-4 sm:p-6">
            <table class="w-full text-left text-sm border-separate border-spacing-y-2">
                <thead>
                    <tr class="text-slate-400 uppercase text-[10px] font-extrabold tracking-wider">
                        <th class="py-3 px-4">Item / Fasilitas</th>
                        <th class="py-3 px-4">Sebelum (Masuk)</th>
                        <th class="py-3 px-4">Sesudah (Keluar)</th>
                    </tr>
                </thead>
                <tbody class="space-y-2">
                    @foreach($journal->checklists as $checklist)
                    <tr class="bg-slate-50/50 hover:bg-slate-50 transition-colors">
                        <td class="py-4 px-4 font-bold text-slate-800 rounded-l-2xl border-y border-l border-slate-100">{{ $checklist->item_type }} - {{ $checklist->item_name }}</td>
                        <td class="py-4 px-4 uppercase font-bold border-y border-slate-100 {{ in_array(strtolower($checklist->condition_before), ['baik', 'bersih']) ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $checklist->condition_before }}
                        </td>
                        <td class="py-4 px-4 uppercase font-bold rounded-r-2xl border-y border-r border-slate-100 {{ in_array(strtolower($checklist->condition_after), ['baik', 'bersih']) ? 'text-emerald-600' : (empty($checklist->condition_after) ? 'text-slate-400' : 'text-rose-600') }}">
                            {{ $checklist->condition_after ?? '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Feedback Guru -->
    @if($journal->teacher_notes)
    <div class="bg-emerald-50 rounded-3xl shadow-sm border border-emerald-100 overflow-hidden">
        <div class="p-8">
            <h3 class="font-extrabold text-emerald-800 text-base mb-2 flex items-center gap-2 tracking-tight">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Catatan & Verifikasi Guru
            </h3>
            <p class="text-sm font-medium text-emerald-700 mb-3">{{ $journal->teacher_notes }}</p>
            <p class="text-xs font-bold text-emerald-600/80">Diverifikasi pada: {{ \Carbon\Carbon::parse($journal->verified_at)->translatedFormat('d F Y, H:i') }}</p>
        </div>
    </div>
    @endif

</div>
@endsection