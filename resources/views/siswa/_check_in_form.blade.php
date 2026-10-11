{{-- Isi form Check-In. Dipakai oleh halaman penuh (siswa/check_in) dan popup (siswa/_modal_form) --}}
<div class="mb-8 pr-10">
    <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight mb-2">Check-In Penggunaan Lab</h2>
    <p class="text-slate-600 font-medium text-sm">Pilih PC yang Anda gunakan dan laporkan kondisi awalnya sebelum mulai belajar.</p>
</div>

<div class="bg-white/40 backdrop-blur-sm p-5 rounded-2xl border border-white/70 mb-8 text-sm space-y-1.5">
    <p class="text-slate-700"><strong>Mata Pelajaran:</strong> <span class="font-bold text-slate-900">{{ $schedule->subject }}</span></p>
    <p class="text-slate-700"><strong>Laboratorium:</strong> <span class="font-bold text-slate-900">{{ $schedule->lab->name }}</span></p>
    <p class="text-slate-700"><strong>Waktu:</strong> <span class="font-bold text-slate-900">{{ date('H:i', strtotime($schedule->start_time)) }} - {{ date('H:i', strtotime($schedule->end_time)) }}</span></p>
</div>

<form action="{{ url('/siswa/check-in/'.$schedule->id) }}" method="POST" class="space-y-6">
    @csrf
    
    <!-- Pilihan PC -->
    <div>
        <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-2">Pilih PC yang Anda Gunakan</label>
        <select name="computer_id" class="w-full bg-white/50 backdrop-blur-sm border border-white/70 text-slate-800 rounded-xl px-4 py-3.5 focus:outline-none focus:bg-white/80 focus:ring-2 focus:ring-blue-600/40 focus:border-blue-500 font-semibold text-sm transition-all" required>
            <option value="">-- Pilih PC --</option>
            @foreach($computers as $pc)
                <option value="{{ $pc->id }}" {{ $pc->status === 'rusak' ? 'disabled' : '' }}>{{ $pc->code }} {{ $pc->status === 'rusak' ? '(rusak, tidak dapat dipilih)' : ($pc->status != 'baik' ? '('.$pc->status.')' : '') }}</option>
            @endforeach
        </select>
        @if($computers->contains('status', 'rusak'))
            <p class="text-xs text-slate-500 font-semibold mt-2">PC yang berstatus rusak tidak dapat dipilih.</p>
        @endif
    </div>

    <!-- Kondisi Awal -->
    <div>
        <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-3">Kondisi Awal PC & Aksesoris</label>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <label class="flex items-center gap-3 p-3.5 rounded-xl border border-white/70 bg-white/40 backdrop-blur-sm cursor-pointer hover:bg-white/70 has-[:checked]:bg-white/80 has-[:checked]:border-blue-400 has-[:checked]:ring-2 has-[:checked]:ring-blue-500/20 transition-all font-semibold text-sm text-slate-700">
                <input type="radio" name="condition_before" value="baik" class="text-blue-600 focus:ring-blue-500" required>
                <span>Normal / Baik</span>
            </label>
            <label class="flex items-center gap-3 p-3.5 rounded-xl border border-white/70 bg-white/40 backdrop-blur-sm cursor-pointer hover:bg-white/70 has-[:checked]:bg-white/80 has-[:checked]:border-amber-400 has-[:checked]:ring-2 has-[:checked]:ring-amber-500/20 transition-all font-semibold text-sm text-slate-700">
                <input type="radio" name="condition_before" value="bermasalah" class="text-yellow-600 focus:ring-yellow-500">
                <span>Bermasalah</span>
            </label>
            <label class="flex items-center gap-3 p-3.5 rounded-xl border border-white/70 bg-white/40 backdrop-blur-sm cursor-pointer hover:bg-white/70 has-[:checked]:bg-white/80 has-[:checked]:border-rose-400 has-[:checked]:ring-2 has-[:checked]:ring-rose-500/20 transition-all font-semibold text-sm text-slate-700">
                <input type="radio" name="condition_before" value="rusak" class="text-red-600 focus:ring-red-500">
                <span>Rusak / Mati</span>
            </label>
        </div>
    </div>

    <!-- Kondisi Kebersihan Ruangan (Tambahan Baru) -->
    <div>
        <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-3">Kebersihan Ruangan Saat Masuk</label>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <label class="flex items-center gap-3 p-3.5 rounded-xl border border-white/70 bg-white/40 backdrop-blur-sm cursor-pointer hover:bg-white/70 has-[:checked]:bg-white/80 has-[:checked]:border-blue-400 has-[:checked]:ring-2 has-[:checked]:ring-blue-500/20 transition-all font-semibold text-sm text-slate-700">
                <input type="radio" name="room_cleanliness_before" value="bersih" class="text-blue-600 focus:ring-blue-500" required>
                <span>Bersih & Rapih</span>
            </label>
            <label class="flex items-center gap-3 p-3.5 rounded-xl border border-white/70 bg-white/40 backdrop-blur-sm cursor-pointer hover:bg-white/70 has-[:checked]:bg-white/80 has-[:checked]:border-rose-400 has-[:checked]:ring-2 has-[:checked]:ring-rose-500/20 transition-all font-semibold text-sm text-slate-700">
                <input type="radio" name="room_cleanliness_before" value="kotor" class="text-red-600 focus:ring-red-500">
                <span>Kotor / Berantakan</span>
            </label>
        </div>
    </div>

    <!-- Catatan Opsional -->
    <div>
        <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-2">Catatan Tambahan (Opsional)</label>
        <textarea name="notes" rows="3" class="w-full bg-white/50 backdrop-blur-sm border border-white/70 text-slate-800 placeholder-slate-400 rounded-xl p-4 focus:outline-none focus:bg-white/80 focus:ring-2 focus:ring-blue-600/40 focus:border-blue-500 font-semibold text-sm transition-all" placeholder="Misal: Mouse agak macet, layar kotor, dll..."></textarea>
    </div>

    <div class="flex items-center justify-end gap-3 pt-2">
        <a href="{{ url('/siswa/dashboard') }}" data-modal-close class="px-6 py-3.5 bg-white/50 hover:bg-white/80 border border-white/70 backdrop-blur-sm text-slate-700 font-bold text-sm rounded-xl transition-all">Batal</a>
        <button type="submit" class="px-6 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-blue-600/30 transition-all">
            Mulai Gunakan Lab
        </button>
    </div>
</form>