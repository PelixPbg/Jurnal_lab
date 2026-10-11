@php
    $journal = $journals[$schedule->id] ?? null;
    $jStatus = $journal->status ?? null;
@endphp
<div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-4 sm:p-6 flex flex-col sm:flex-row items-stretch gap-6">
    <!-- Tanggal & Waktu -->
    <div class="bg-slate-50 rounded-2xl p-5 sm:w-48 flex flex-col justify-center shrink-0 border border-slate-100 relative overflow-hidden">
        <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $mode === 'riwayat' ? 'bg-slate-300' : 'bg-blue-600' }}"></div>
        <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">
            {{ \Carbon\Carbon::parse($schedule->date)->format('d M Y') }}
        </p>
        <h4 class="text-2xl font-extrabold {{ $mode === 'riwayat' ? 'text-slate-500' : 'text-blue-600' }} mb-1">
            {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
        </h4>
        <p class="text-sm font-semibold text-slate-500">s/d {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</p>
    </div>

    <div class="flex-1 flex flex-col justify-between">
        <div>
            <div class="flex flex-wrap items-start justify-between gap-4 mb-3">
                <h3 class="text-2xl font-extrabold text-slate-800 tracking-tight">{{ $schedule->subject }}</h3>

                {{-- Badge status sesuai kondisi jurnal --}}
                @if(!$journal)
                    @if($mode === 'riwayat')
                        <span class="inline-flex px-3 py-1.5 rounded-xl bg-slate-100 text-slate-500 text-xs font-bold border border-slate-200">Tidak Ada Jurnal</span>
                    @elseif($mode === 'mendatang')
                        <span class="inline-flex px-3 py-1.5 rounded-xl bg-blue-50 text-blue-600 text-xs font-bold border border-blue-100">Terjadwal</span>
                    @else
                        <span class="inline-flex px-3 py-1.5 rounded-xl bg-slate-100 text-slate-600 text-xs font-bold border border-slate-200">Belum Check-In</span>
                    @endif
                @elseif($jStatus === 'draft')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-600 text-xs font-bold border border-emerald-100">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Sedang Berlangsung
                    </span>
                @elseif($jStatus === 'submitted')
                    <span class="inline-flex px-3 py-1.5 rounded-xl bg-amber-50 text-amber-600 text-xs font-bold border border-amber-100">Menunggu Verifikasi</span>
                @elseif($jStatus === 'approved')
                    <span class="inline-flex px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-600 text-xs font-bold border border-emerald-100">Disetujui</span>
                @elseif($jStatus === 'revision')
                    <span class="inline-flex px-3 py-1.5 rounded-xl bg-rose-50 text-rose-600 text-xs font-bold border border-rose-100">Perlu Perbaikan</span>
                @endif
            </div>

            <div class="flex flex-wrap items-center gap-y-2 gap-x-6 text-sm font-semibold text-slate-600 mb-4">
                <div>Lab: <span class="text-slate-800 font-bold">{{ $schedule->lab->name ?? 'Lab Komputer' }}</span></div>
                <div>Guru: <span class="text-slate-800 font-bold">{{ $schedule->teacher->name ?? '-' }}</span></div>
            </div>

            <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 text-sm">
                <span class="font-extrabold text-slate-700">Kegiatan:</span>
                <span class="text-slate-600 font-medium">{{ $schedule->description ?? 'Praktikum sesuai modul.' }}</span>
            </div>

            @if($journal && $jStatus === 'revision' && $journal->teacher_notes)
                <div class="mt-3 bg-rose-50 rounded-2xl p-4 border border-rose-100 text-sm">
                    <span class="font-extrabold text-rose-700">Catatan Guru:</span>
                    <span class="text-rose-600 font-medium">{{ $journal->teacher_notes }}</span>
                </div>
            @endif
        </div>

        <!-- Tombol aksi -->
        <div class="mt-6 flex justify-end">
            @if(!$journal)
                @if($mode === 'hari-ini')
                    <a href="{{ url('/siswa/check-in/' . $schedule->id) }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm shadow-sm shadow-blue-600/20 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                        Check-In Sekarang
                    </a>
                @endif
            @elseif($jStatus === 'draft')
                <a href="{{ url('/siswa/check-out/' . $journal->id) }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-sm shadow-sm shadow-emerald-600/20 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Check-Out &amp; Isi Jurnal
                </a>
            @elseif($jStatus === 'revision')
                <a href="{{ url('/siswa/check-out/' . $journal->id) }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-rose-500 hover:bg-rose-600 text-white rounded-xl font-bold text-sm shadow-sm transition-all">
                    Perbaiki Jurnal &rarr;
                </a>
            @else
                <a href="{{ url('/siswa/riwayat/' . $journal->id) }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-sm transition-all">
                    Lihat Jurnal &rarr;
                </a>
            @endif
        </div>
    </div>
</div>