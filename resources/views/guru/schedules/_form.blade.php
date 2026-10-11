{{--
    Isi form jadwal lab (Buat & Edit).
    Dipakai oleh halaman penuh (create / edit) dan popup (_modal).

    $modal = true  -> tampilan kaca + judul di dalam form (popup)
    $schedule ada  -> mode edit, kalau tidak ada -> mode buat
--}}
@php
    $glass = $modal ?? false;
    $edit  = isset($schedule);

    $input = $glass
        ? 'w-full px-4 py-3 rounded-2xl border border-white/70 bg-white/50 backdrop-blur-sm focus:bg-white/80 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none text-sm font-semibold text-slate-800 placeholder-slate-400 transition-all'
        : 'w-full px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none text-sm font-medium text-slate-800 transition-all';
    $label = $glass
        ? 'block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-2'
        : 'block text-xs font-extrabold text-slate-500 uppercase tracking-widest mb-2';
    $batal = $glass
        ? 'px-5 py-3 bg-white/50 hover:bg-white/80 border border-white/70 backdrop-blur-sm text-slate-700 font-bold rounded-2xl transition-colors text-sm'
        : 'px-5 py-3 bg-slate-100 text-slate-600 font-bold rounded-2xl hover:bg-slate-200 transition-colors text-sm';
@endphp

@if($glass)
    <div class="mb-8 pr-10">
        <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">{{ $edit ? 'Edit Jadwal Penggunaan Lab' : 'Buat Jadwal Penggunaan Lab' }}</h2>
        <p class="text-slate-600 font-medium text-sm mt-1">{{ $edit ? 'Perbarui informasi jadwal penggunaan laboratorium komputer.' : 'Isi detail jadwal praktikum untuk kelas Anda.' }}</p>
    </div>
@endif

{{-- Pesan error (dari server, atau diisi lewat JavaScript saat popup dikirim) --}}
<div data-form-errors class="{{ $errors->any() ? '' : 'hidden' }} mb-6 p-4 bg-rose-50/80 backdrop-blur-sm border border-rose-200 text-rose-700 rounded-2xl text-sm font-bold">
    <ul class="list-disc list-inside space-y-1">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>

<form action="{{ $edit ? url('/guru/jadwal/' . $schedule->id) : url('/guru/jadwal') }}" method="POST" data-ajax-form>
    @csrf
    @if($edit)
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
        <div>
            <label class="{{ $label }}">Laboratorium</label>
            <select name="lab_id" class="{{ $input }}" required>
                <option value="">-- Pilih Lab --</option>
                @foreach($labs as $lab)
                    <option value="{{ $lab->id }}" {{ (string) old('lab_id', $schedule->lab_id ?? '') === (string) $lab->id ? 'selected' : '' }}>{{ $lab->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="{{ $label }}">Kelas</label>
            <select name="class_id" class="{{ $input }}" required>
                <option value="">-- Pilih Kelas --</option>
                @foreach($classes as $kelas)
                    <option value="{{ $kelas->id }}" {{ (string) old('class_id', $schedule->class_id ?? '') === (string) $kelas->id ? 'selected' : '' }}>{{ $kelas->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mb-5">
        <label class="{{ $label }}">Mata Pelajaran</label>
        <input type="text" name="subject" value="{{ old('subject', $schedule->subject ?? '') }}" class="{{ $input }}" placeholder="Contoh: Pemrograman Web" required>
    </div>

    <div class="mb-5">
        <label class="{{ $label }}">Kegiatan (Opsional)</label>
        <input type="text" name="description" value="{{ old('description', $schedule->description ?? '') }}" class="{{ $input }}" placeholder="Contoh: Praktikum Laravel Dasar">
    </div>

    <div class="mb-5">
        <label class="{{ $label }}">Tanggal</label>
        <input type="date" name="date" value="{{ old('date', $schedule->date ?? '') }}" class="{{ $input }}" required>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">
        <div>
            <label class="{{ $label }}">Jam Mulai</label>
            <input type="time" name="start_time" value="{{ old('start_time', $schedule->start_time ?? '') }}" class="{{ $input }}" required>
        </div>
        <div>
            <label class="{{ $label }}">Jam Selesai</label>
            <input type="time" name="end_time" value="{{ old('end_time', $schedule->end_time ?? '') }}" class="{{ $input }}" required>
        </div>
    </div>

    <div class="flex items-center justify-end gap-3 pt-6 border-t {{ $glass ? 'border-white/60' : 'border-slate-100' }}">
        <a href="{{ url('/guru/jadwal') }}" data-modal-close class="{{ $batal }}">Batal</a>
        <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-700 transition-colors shadow-lg shadow-blue-600/30 text-sm">{{ $edit ? 'Simpan Perubahan' : 'Simpan Jadwal' }}</button>
    </div>
</form>