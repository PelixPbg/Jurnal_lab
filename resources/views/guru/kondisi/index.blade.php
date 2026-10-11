@extends('layouts.guru')
@section('title', 'Kondisi Lab - Jurnal Lab')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Kondisi Lab</h2>
    <p class="text-slate-500 font-medium text-sm">Status perangkat komputer di setiap laboratorium.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
    @forelse($labs as $lab)
        @php
            $pcs = $computers->get($lab->id, collect());
            $rusak = $pcs->whereIn('status', ['bermasalah', 'rusak'])->count();
            $total = $pcs->count();
            $baik = $total - $rusak;
        @endphp
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-extrabold text-slate-800">{{ $lab->name }}</h3>
                <span class="inline-flex px-3 py-1.5 rounded-xl text-xs font-bold border
                    {{ $rusak > 0 ? 'bg-amber-50 text-amber-600 border-amber-100' : 'bg-emerald-50 text-emerald-600 border-emerald-100' }}">
                    {{ $rusak > 0 ? 'Perlu Perhatian' : 'Kondisi Baik' }}
                </span>
            </div>
            <div class="grid grid-cols-3 gap-3 text-center">
                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                    <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Total PC</p>
                    <p class="text-2xl font-extrabold text-slate-800">{{ $total }}</p>
                </div>
                <div class="bg-emerald-50 rounded-2xl p-4 border border-emerald-100">
                    <p class="text-[10px] font-extrabold text-emerald-500 uppercase tracking-widest mb-1">Baik</p>
                    <p class="text-2xl font-extrabold text-emerald-600">{{ $baik }}</p>
                </div>
                <div class="bg-rose-50 rounded-2xl p-4 border border-rose-100">
                    <p class="text-[10px] font-extrabold text-rose-500 uppercase tracking-widest mb-1">Bermasalah</p>
                    <p class="text-2xl font-extrabold text-rose-600">{{ $rusak }}</p>
                </div>
            </div>
        </div>
    @empty
        <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-100 p-16 text-center shadow-sm">
            <h3 class="text-xl font-extrabold text-slate-800">Belum ada data lab</h3>
        </div>
    @endforelse
</div>
@endsection