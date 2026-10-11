@extends('layouts.admin')
@section('title', 'Kondisi Laboratorium - Admin')

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Kondisi Laboratorium</h2>
    <p class="text-slate-500 font-medium mt-1">Pantau status dan ketersediaan perangkat komputer di setiap laboratorium.</p>
</div>

@if($labs->isEmpty())
    <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm p-12 text-center">
        <div class="w-20 h-20 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
        </div>
        <h3 class="text-xl font-extrabold text-slate-800 mb-1">Data Lab Kosong</h3>
        <p class="text-slate-500 font-medium text-sm">Belum ada data laboratorium yang didaftarkan ke sistem.</p>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($labs as $lab)
            @php
                // Kalkulasi persentase untuk progress bar
                $total = $lab->total_pc > 0 ? $lab->total_pc : 1; // Hindari pembagian dengan nol
                $persenBaik = round(($lab->pc_baik / $total) * 100);
                $persenBermasalah = round(($lab->pc_bermasalah / $total) * 100);
                $persenRusak = round(($lab->pc_rusak / $total) * 100);
            @endphp

            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden flex flex-col hover:shadow-md transition-shadow">
                <!-- Header Lab -->
                <div class="p-6 border-b border-slate-50 bg-slate-50/50 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-extrabold text-slate-800 leading-tight">{{ $lab->name }}</h3>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-0.5">Total {{ $lab->total_pc }} PC</p>
                        </div>
                    </div>
                </div>

                <!-- Body Statistik -->
                <div class="p-6 flex-1 flex flex-col justify-center">
                    
                    <!-- Indikator Angka -->
                    <div class="grid grid-cols-3 gap-2 mb-6 text-center">
                        <div class="bg-blue-50/50 rounded-xl p-3 border border-blue-100/50">
                            <p class="text-2xl font-extrabold text-blue-600">{{ $lab->pc_baik }}</p>
                            <p class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mt-1">Normal</p>
                        </div>
                        <div class="bg-amber-50/50 rounded-xl p-3 border border-amber-100/50">
                            <p class="text-2xl font-extrabold text-amber-500">{{ $lab->pc_bermasalah }}</p>
                            <p class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mt-1">Masalah</p>
                        </div>
                        <div class="bg-rose-50/50 rounded-xl p-3 border border-rose-100/50">
                            <p class="text-2xl font-extrabold text-rose-600">{{ $lab->pc_rusak }}</p>
                            <p class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mt-1">Rusak</p>
                        </div>
                    </div>

                    <!-- Progress Bar Visual -->
                    <div>
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-xs font-bold text-slate-700">Kesehatan PC</span>
                            <span class="text-xs font-extrabold text-blue-600">{{ $persenBaik }}% Normal</span>
                        </div>
                        <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden flex">
                            @if($lab->total_pc > 0)
                                <div class="h-full bg-blue-500" style="width: {{ $persenBaik }}%" title="{{ $persenBaik }}% Normal"></div>
                                <div class="h-full bg-amber-400" style="width: {{ $persenBermasalah }}%" title="{{ $persenBermasalah }}% Bermasalah"></div>
                                <div class="h-full bg-rose-500" style="width: {{ $persenRusak }}%" title="{{ $persenRusak }}% Rusak"></div>
                            @else
                                <div class="h-full bg-slate-200 w-full"></div>
                            @endif
                        </div>
                    </div>

                </div>

                <!-- Footer Aksi -->
                <div class="p-4 bg-white border-t border-slate-50">
                    <a href="{{ url('/admin/computers') }}" class="block w-full py-2.5 px-4 text-center text-sm font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-600 hover:text-white rounded-xl transition-colors">
                        Kelola PC Lab Ini
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection