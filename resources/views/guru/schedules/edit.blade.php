@extends('layouts.guru')
@section('title', 'Edit Jadwal Lab - Guru')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8">
        <a href="{{ url('/guru/jadwal') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-blue-600 transition-colors mb-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Jadwal
        </a>
        <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Edit Jadwal Penggunaan Lab</h2>
        <p class="text-slate-500 font-medium text-sm mt-1">Perbarui informasi jadwal penggunaan laboratorium komputer.</p>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 sm:p-8">
        @include('guru.schedules._form')
    </div>
</div>
@endsection