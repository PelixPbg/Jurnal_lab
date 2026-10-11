@extends('layouts.guru')
@section('title', 'Jurnal Siswa - Guru')

@section('content')
<div class="mb-8">
    <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Verifikasi Jurnal Siswa</h2>
    <p class="text-slate-500 font-medium text-sm mt-1">Periksa dan setujui jurnal praktikum yang dikirim siswa.</p>
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
                    <th class="py-4 px-6 font-extrabold uppercase tracking-widest text-[11px]">Nama Siswa</th>
                    <th class="py-4 px-6 font-extrabold uppercase tracking-widest text-[11px]">Kelas</th>
                    <th class="py-4 px-6 font-extrabold uppercase tracking-widest text-[11px]">Materi</th>
                    <th class="py-4 px-6 font-extrabold uppercase tracking-widest text-[11px]">Status</th>
                    <th class="py-4 px-6 font-extrabold uppercase tracking-widest text-[11px] text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($journals as $journal)
                <tr class="hover:bg-slate-50/60 transition-colors">
                    <td class="py-4 px-6 font-bold text-slate-800">{{ \Carbon\Carbon::parse($journal->schedule->date)->format('d M Y') }}</td>
                    <td class="py-4 px-6 font-bold text-slate-800">{{ $journal->student->name }}</td>
                    <td class="py-4 px-6 font-medium text-slate-600">{{ $journal->schedule->classRoom->name }}</td>
                    <td class="py-4 px-6 font-medium text-slate-600">{{ $journal->schedule->subject }}</td>
                    <td class="py-4 px-6">
                        @if($journal->status === 'submitted')
                            <span class="inline-flex px-3 py-1.5 bg-amber-50 text-amber-600 rounded-xl text-xs font-bold border border-amber-100">Menunggu Verifikasi</span>
                        @elseif($journal->status === 'approved')
                            <span class="inline-flex px-3 py-1.5 bg-emerald-50 text-emerald-600 rounded-xl text-xs font-bold border border-emerald-100">Disetujui</span>
                        @elseif($journal->status === 'revision')
                            <span class="inline-flex px-3 py-1.5 bg-rose-50 text-rose-600 rounded-xl text-xs font-bold border border-rose-100">Perlu Perbaikan</span>
                        @else
                            <span class="inline-flex px-3 py-1.5 bg-slate-100 text-slate-500 rounded-xl text-xs font-bold border border-slate-200">Draft (Sedang Aktif)</span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-center">
                        <a href="{{ url('/guru/jurnal/'.$journal->id) }}" class="inline-flex px-4 py-2 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-xl text-xs font-bold transition-colors">Lihat Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-16 text-center">
                        <h3 class="text-lg font-extrabold text-slate-800 mb-1">Belum ada jurnal</h3>
                        <p class="text-slate-400 font-medium text-sm">Jurnal siswa akan muncul di sini setelah mereka check-in.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection