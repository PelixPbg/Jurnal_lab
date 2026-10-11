{{--
    Popup kaca untuk form Buat / Edit Jadwal Lab (guru).

    Cara pakai: taruh satu baris ini di halaman daftar jadwal, sebelum @endsection:

        @include('guru.schedules._modal')

    Link ke /guru/jadwal/create dan /guru/jadwal/{id}/edit di halaman itu otomatis
    membuka popup ini. Form dikirim lewat AJAX, jadi pesan error muncul di dalam popup
    dan isian guru tidak hilang. Kalau JavaScript gagal, link tetap membuka halaman penuh.
--}}
<style>
    @keyframes modalMasuk {
        from { opacity: 0; transform: translateY(16px) scale(.98); }
        to   { opacity: 1; transform: none; }
    }
    .modal-masuk { animation: modalMasuk .25s ease-out both; }
</style>

<div id="modal-jadwal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 sm:p-6" role="dialog" aria-modal="true" aria-label="Formulir jadwal lab">
    <!-- Latar blur (klik untuk menutup) -->
    <div data-modal-close class="absolute inset-0 bg-slate-900/30 backdrop-blur-md"></div>

    <!-- Kartu kaca -->
    <div class="modal-masuk relative w-full max-w-2xl rounded-3xl bg-white/50 backdrop-blur-xl border border-white/60 shadow-2xl shadow-indigo-300/40">
        <button type="button" data-modal-close aria-label="Tutup" title="Tutup" class="absolute top-4 right-4 z-10 p-2 text-slate-500 hover:text-slate-800 hover:bg-white/70 rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <div id="modal-jadwal-body" class="max-h-[88vh] overflow-y-auto p-8 sm:p-10"></div>
    </div>
</div>

<script>
(function () {
    const modal = document.getElementById('modal-jadwal');
    const body  = document.getElementById('modal-jadwal-body');
    if (!modal || !body) return;

    // Hanya link buat jadwal dan edit jadwal milik guru yang dibuka sebagai popup
    const POLA = /\/guru\/jadwal\/(create|\d+\/edit)\/?$/;

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

    // Tampilkan daftar error di kotak merah bagian atas form
    function tampilError(daftar) {
        const kotak = body.querySelector('[data-form-errors]');
        if (!kotak) { alert(daftar.join('\n')); return; }

        let ul = kotak.querySelector('ul');
        if (!ul) { ul = document.createElement('ul'); ul.className = 'list-disc list-inside space-y-1'; kotak.appendChild(ul); }
        ul.innerHTML = '';
        daftar.forEach(function (teks) {
            const li = document.createElement('li');
            li.textContent = teks;
            ul.appendChild(li);
        });

        kotak.classList.remove('hidden');
        body.scrollTop = 0;
    }

    function sembunyikanError() {
        const kotak = body.querySelector('[data-form-errors]');
        if (kotak) kotak.classList.add('hidden');
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
                window.location.href = url; // misal 403 / 404: tampilkan halaman aslinya
                return;
            }

            body.innerHTML = await res.text();
        } catch (e) {
            window.location.href = url;
        }
    }

    // Tangkap klik pada link buat / edit jadwal
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

    // Kirim form lewat AJAX supaya error tampil di dalam popup
    document.addEventListener('submit', async function (e) {
        const form = e.target;
        if (!body.contains(form) || !form.hasAttribute('data-ajax-form')) return;
        e.preventDefault();

        const tombol = form.querySelector('button[type="submit"]');
        const teksAsli = tombol ? tombol.textContent : '';
        if (tombol) {
            tombol.disabled = true;
            tombol.classList.add('opacity-70', 'cursor-not-allowed');
            tombol.textContent = 'Menyimpan...';
        }
        sembunyikanError();

        let berhasil = false;

        try {
            const res = await fetch(form.action, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                body: new FormData(form),
                credentials: 'same-origin'
            });

            if (res.redirected) { window.location.reload(); return; }

            if (res.ok) {
                const data = await res.json();
                berhasil = true;
                window.location.href = data.redirect || window.location.href;
                return;
            }

            if (res.status === 422) {
                const data = await res.json();
                const e422 = data.errors || {};
                const daftar = Array.isArray(e422) ? e422 : Object.values(e422).flat();
                tampilError(daftar.length ? daftar : ['Data yang dikirim belum valid.']);
            } else if (res.status === 419) {
                tampilError(['Sesi Anda sudah habis. Muat ulang halaman, lalu coba lagi.']);
            } else {
                tampilError(['Terjadi kesalahan di server. Coba lagi.']);
            }
        } catch (err) {
            tampilError(['Tidak dapat terhubung ke server. Periksa koneksi Anda, lalu coba lagi.']);
        }

        // Kembalikan tombol kalau belum berhasil, supaya guru bisa memperbaiki isiannya
        if (!berhasil && tombol) {
            tombol.disabled = false;
            tombol.classList.remove('opacity-70', 'cursor-not-allowed');
            tombol.textContent = teksAsli;
        }
    });

    // Kembali dari riwayat browser: pastikan popup tertutup
    window.addEventListener('pageshow', function (e) {
        if (e.persisted && terbuka()) tutup();
    });
})();
</script>