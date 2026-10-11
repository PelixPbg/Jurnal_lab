{{-- Isi form Check-Out. Dipakai oleh halaman penuh (siswa/check_out) dan popup (siswa/_modal_form) --}}
<div class="mb-8 pr-10">
    <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight mb-2">Check-Out Penggunaan Lab</h2>
    <p class="text-slate-600 font-medium text-sm">Tulis aktivitas yang telah Anda lakukan dan laporkan kondisi akhir PC.</p>
</div>

<!-- Info Detail -->
<div class="bg-white/40 backdrop-blur-sm p-6 rounded-2xl border border-white/70 mb-8 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
    <div>
        <p class="text-xs font-extrabold text-slate-500 uppercase tracking-widest mb-1">Mata Pelajaran</p>
        <p class="font-bold text-slate-900">{{ $journal->schedule->subject }}</p>
    </div>
    <div>
        <p class="text-xs font-extrabold text-slate-500 uppercase tracking-widest mb-1">Laboratorium</p>
        <p class="font-bold text-slate-900">{{ $journal->schedule->lab->name }}</p>
    </div>
    <div>
        <p class="text-xs font-extrabold text-slate-500 uppercase tracking-widest mb-1">PC yang Digunakan</p>
        <p class="font-bold text-slate-900">{{ $checklistPC->item_name ?? 'Tidak diketahui' }}</p>
    </div>
    <div>
        <p class="text-xs font-extrabold text-slate-500 uppercase tracking-widest mb-1">Waktu Mulai</p>
        <p class="font-bold text-slate-900">{{ \Carbon\Carbon::parse($journal->check_in_at)->format('H:i') }} WIB</p>
    </div>
</div>

@if ($errors->any())
    <div class="bg-rose-50/80 backdrop-blur-sm border border-rose-200 text-rose-700 px-5 py-4 rounded-2xl mb-6 text-sm font-semibold">
        <ul class="list-disc list-inside space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ url('/siswa/check-out/'.$journal->id) }}" method="POST" class="space-y-6">
    @csrf
    
    <!-- Aktivitas Pembelajaran -->
    <div>
        <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-2">Aktivitas / Tugas yang Dikerjakan <span class="text-red-500">*</span></label>
        <textarea name="activity_notes" rows="3" class="w-full bg-white/50 backdrop-blur-sm border border-white/70 text-slate-800 placeholder-slate-400 rounded-xl p-4 focus:outline-none focus:bg-white/80 focus:ring-2 focus:ring-blue-600/40 focus:border-blue-500 font-semibold text-sm transition-all" placeholder="Misal: Menyelesaikan modul 1 instalasi Laravel dan membuat database..." required></textarea>
    </div>

    <!-- Analisis / Refleksi Siswa (Tambahan Edukasi) -->
    <div>
        <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-1">Analisis & Refleksi Penggunaan Lab <span class="text-red-500">*</span></label>
        <p class="text-xs text-slate-500 font-medium mb-2.5">Tuliskan kesimpulan belajarmu hari ini dan apakah penggunaan lab sudah ideal sesuai aturan kerja?</p>
        <textarea name="student_notes" rows="3" class="w-full bg-white/50 backdrop-blur-sm border border-white/70 text-slate-800 placeholder-slate-400 rounded-xl p-4 focus:outline-none focus:bg-white/80 focus:ring-2 focus:ring-blue-600/40 focus:border-blue-500 font-semibold text-sm transition-all" placeholder="Misal: Hari ini belajar lancar, namun saya menyadari posisi duduk yang ideal sangat penting. Lab ditinggalkan dalam keadaan rapi..." required></textarea>
    </div>

    <!-- Kondisi Akhir PC -->
    <div>
        <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-3">Kondisi Akhir PC & Aksesoris <span class="text-red-500">*</span></label>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <label class="flex items-center gap-3 p-3.5 rounded-xl border border-white/70 bg-white/40 backdrop-blur-sm cursor-pointer hover:bg-white/70 has-[:checked]:bg-white/80 has-[:checked]:border-blue-400 has-[:checked]:ring-2 has-[:checked]:ring-blue-500/20 transition-all font-semibold text-sm text-slate-700">
                <input type="radio" name="condition_after" value="baik" class="text-blue-600 focus:ring-blue-500" required onclick="toggleProblemBox(false)">
                <span>Normal / Baik</span>
            </label>
            <label class="flex items-center gap-3 p-3.5 rounded-xl border border-white/70 bg-white/40 backdrop-blur-sm cursor-pointer hover:bg-white/70 has-[:checked]:bg-white/80 has-[:checked]:border-amber-400 has-[:checked]:ring-2 has-[:checked]:ring-amber-500/20 transition-all font-semibold text-sm text-slate-700">
                <input type="radio" name="condition_after" value="bermasalah" class="text-yellow-600 focus:ring-yellow-500" onclick="toggleProblemBox(true)">
                <span>Bermasalah</span>
            </label>
            <label class="flex items-center gap-3 p-3.5 rounded-xl border border-white/70 bg-white/40 backdrop-blur-sm cursor-pointer hover:bg-white/70 has-[:checked]:bg-white/80 has-[:checked]:border-rose-400 has-[:checked]:ring-2 has-[:checked]:ring-rose-500/20 transition-all font-semibold text-sm text-slate-700">
                <input type="radio" name="condition_after" value="rusak" class="text-red-600 focus:ring-red-500" onclick="toggleProblemBox(true)">
                <span>Rusak / Mati</span>
            </label>
        </div>
        <p class="text-xs text-slate-500 font-semibold mt-2.5">Kondisi saat check-in: <strong class="text-slate-700">{{ ucfirst($checklistPC->condition_before ?? 'baik') }}</strong></p>
    </div>

    <!-- Kondisi Kebersihan Ruangan Akhir -->
    <div>
        <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-3">Kebersihan Ruangan Saat Ditinggalkan <span class="text-red-500">*</span></label>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <label class="flex items-center gap-3 p-3.5 rounded-xl border border-white/70 bg-white/40 backdrop-blur-sm cursor-pointer hover:bg-white/70 has-[:checked]:bg-white/80 has-[:checked]:border-blue-400 has-[:checked]:ring-2 has-[:checked]:ring-blue-500/20 transition-all font-semibold text-sm text-slate-700">
                <input type="radio" name="room_cleanliness_after" value="bersih" class="text-blue-600 focus:ring-blue-500" required>
                <span>Bersih & Rapih</span>
            </label>
            <label class="flex items-center gap-3 p-3.5 rounded-xl border border-white/70 bg-white/40 backdrop-blur-sm cursor-pointer hover:bg-white/70 has-[:checked]:bg-white/80 has-[:checked]:border-rose-400 has-[:checked]:ring-2 has-[:checked]:ring-rose-500/20 transition-all font-semibold text-sm text-slate-700">
                <input type="radio" name="room_cleanliness_after" value="kotor" class="text-red-600 focus:ring-red-500">
                <span>Masih Kotor</span>
            </label>
        </div>
    </div>

    <!-- Laporan Masalah (Muncul kalau pilih bermasalah/rusak) -->
    <div class="hidden" id="problem_box">
        <label class="block text-xs font-extrabold text-rose-600 uppercase tracking-widest mb-2">Deskripsi Kerusakan / Masalah <span class="text-red-500">*</span></label>
        <textarea name="problem_description" id="problem_description" rows="3" class="w-full border border-rose-200 bg-rose-50/60 backdrop-blur-sm text-slate-800 placeholder-slate-400 rounded-xl p-4 focus:outline-none focus:bg-rose-50/90 focus:ring-2 focus:ring-rose-500/40 focus:border-rose-500 font-semibold text-sm transition-all" placeholder="Jelaskan masalahnya secara detail... (Misal: Mouse tiba-tiba tidak merespon saat diklik)"></textarea>
    </div>

    <div class="flex items-center justify-end gap-3 pt-2">
        <a href="{{ url('/siswa/dashboard') }}" data-modal-close class="px-6 py-3.5 bg-white/50 hover:bg-white/80 border border-white/70 backdrop-blur-sm text-slate-700 font-bold text-sm rounded-xl transition-all">Batal</a>
        <button type="submit" class="px-6 py-3.5 bg-amber-500 hover:bg-amber-600 text-white font-bold text-sm rounded-xl shadow-lg shadow-amber-500/30 transition-all">
            Selesai & Kirim Jurnal
        </button>
    </div>
</form>