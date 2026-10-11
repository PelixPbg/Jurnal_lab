{{--
    Popup kaca untuk form Check-In dan Check-Out.

    Cara pakai: taruh satu baris ini di halaman yang punya tombol check-in / check-out
    (misalnya halaman jadwal dan dashboard siswa), sebelum @endsection:

        @include('siswa._modal_form')

    Link ke /siswa/check-in/{id} dan /siswa/check-out/{id} di halaman itu akan
    otomatis membuka popup ini. Kalau JavaScript gagal, link tetap membuka halaman penuh.
--}}
<style>
    @keyframes modalMasuk {
        from { opacity: 0; transform: translateY(16px) scale(.98); }
        to   { opacity: 1; transform: none; }
    }
    .modal-masuk { animation: modalMasuk .25s ease-out both; }
</style>

<div id="modal-form" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 sm:p-6" role="dialog" aria-modal="true" aria-label="Formulir jurnal lab">
    <!-- Latar blur (klik untuk menutup) -->
    <div data-modal-close class="absolute inset-0 bg-slate-900/30 backdrop-blur-md"></div>

    <!-- Kartu kaca -->
    <div class="modal-masuk relative w-full max-w-2xl rounded-3xl bg-white/50 backdrop-blur-xl border border-white/60 shadow-2xl shadow-indigo-300/40">
        <button type="button" data-modal-close aria-label="Tutup" title="Tutup" class="absolute top-4 right-4 z-10 p-2 text-slate-500 hover:text-slate-800 hover:bg-white/70 rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <div id="modal-form-body" class="max-h-[88vh] overflow-y-auto p-8 sm:p-10"></div>
    </div>
</div>

<script>
(function () {
    const modal = document.getElementById('modal-form');
    const body  = document.getElementById('modal-form-body');
    if (!modal || !body) return;

    // Hanya link check-in / check-out milik siswa yang dibuka sebagai popup
    const POLA = /\/siswa\/check-(in|out)\/\d+\/?$/;

    function terbuka() { return !modal.classList.contains('hidden'); }

    function buka() {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function tutup() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
        body.innerHTML = '';
    }

    function tampilSpinner() {
        body.innerHTML =
            '<div class="py-20 flex flex-col items-center justify-center text-slate-600">' +
            '<svg class="w-8 h-8 animate-spin text-blue-600 mb-3" fill="none" viewBox="0 0 24 24">' +
            '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>' +
            '<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>' +
            '<p class="text-sm font-bold">Memuat formulir...</p></div>';
    }

    function tampilPesan(teks) {
        body.innerHTML = '';
        const kotak = document.createElement('div');
        kotak.className = 'text-center py-8';

        const p = document.createElement('p');
        p.className = 'text-slate-700 font-bold mb-5';
        p.textContent = teks;

        const tombol = document.createElement('button');
        tombol.type = 'button';
        tombol.setAttribute('data-modal-close', '');
        tombol.className = 'px-6 py-3 bg-white/60 hover:bg-white/90 border border-white/70 text-slate-700 font-bold text-sm rounded-xl transition-all';
        tombol.textContent = 'Tutup';

        kotak.appendChild(p);
        kotak.appendChild(tombol);
        body.appendChild(kotak);
    }

    async function muat(url) {
        buka();
        tampilSpinner();

        try {
            const res = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' },
                credentials: 'same-origin'
            });

            // Sesi habis / dialihkan: buka halaman biasa saja
            if (res.redirected) { window.location.href = url; return; }

            if (!res.ok) {
                const tipe = res.headers.get('content-type') || '';
                if (tipe.includes('application/json')) {
                    const data = await res.json();
                    tampilPesan(data.message || 'Formulir tidak dapat dibuka.');
                    return;
                }
                window.location.href = url; // misal 403: tampilkan halaman aslinya
                return;
            }

            body.innerHTML = await res.text();
        } catch (e) {
            window.location.href = url;
        }
    }

    // Tangkap klik pada link check-in / check-out
    document.addEventListener('click', function (e) {
        // Tombol tutup, latar blur, dan tombol "Batal"
        const penutup = e.target.closest('[data-modal-close]');
        if (penutup && terbuka()) {
            e.preventDefault();
            tutup();
            return;
        }

        const link = e.target.closest('a[href]');
        if (!link) return;
        if (e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
        if (!POLA.test(link.getAttribute('href'))) return;

        e.preventDefault();
        muat(link.href);
    });

    // Esc untuk menutup
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && terbuka()) tutup();
    });

    // Tombol kirim berubah jadi "Memproses..." supaya tidak terkirim dua kali
    document.addEventListener('submit', function (e) {
        if (!body.contains(e.target)) return;
        const tombol = e.target.querySelector('button[type="submit"]');
        if (tombol) {
            tombol.disabled = true;
            tombol.classList.add('opacity-70', 'cursor-not-allowed');
            tombol.textContent = 'Memproses...';
        }
    });

    // Kembali dari riwayat browser: pastikan popup tertutup
    window.addEventListener('pageshow', function (e) {
        if (e.persisted && terbuka()) tutup();
    });

    // Dipakai oleh form check-out (kotak deskripsi masalah)
    window.toggleProblemBox = function (tampil) {
        const kotak = document.getElementById('problem_box');
        const isian = document.getElementById('problem_description');
        if (!kotak || !isian) return;
        if (tampil) {
            kotak.classList.remove('hidden');
            isian.setAttribute('required', 'required');
        } else {
            kotak.classList.add('hidden');
            isian.removeAttribute('required');
            isian.value = '';
        }
    };
})();
</script>