{{--
    Popup kaca untuk halaman admin:
      - form Tambah / Edit  (Data Siswa, Guru, Kelas, Lab, Komputer)
      - Import Excel        (Data Siswa dan Data Guru)

    Cara pakai: taruh satu baris ini di halaman daftar, sebelum @endsection:

        @include('admin._modal_form')

    Link ke /admin/{students|teachers|classes|labs|computers}/create dan /{id}/edit, serta
    /admin/{students|teachers}/import otomatis membuka popup ini. Isi popup diambil dari halaman
    yang sudah ada, jadi controller tidak perlu diubah. Kalau JavaScript gagal, link tetap
    membuka halaman penuh.
--}}
<style>
    @keyframes modalMasuk {
        from { opacity: 0; transform: translateY(16px) scale(.98); }
        to   { opacity: 1; transform: none; }
    }
    .modal-masuk { animation: modalMasuk .25s ease-out both; }

    /* Kolom isian di dalam popup dibuat semi-transparan (efek kaca) */
    #modal-admin-body input:not([type=checkbox]):not([type=radio]):not([type=hidden]),
    #modal-admin-body select,
    #modal-admin-body textarea {
        background-color: rgba(255, 255, 255, 0.5);
        border-color: rgba(255, 255, 255, 0.7);
        -webkit-backdrop-filter: blur(4px);
        backdrop-filter: blur(4px);
    }
    #modal-admin-body input:not([type=checkbox]):not([type=radio]):not([type=hidden]):focus,
    #modal-admin-body select:focus,
    #modal-admin-body textarea:focus {
        background-color: rgba(255, 255, 255, 0.85);
    }
    #modal-admin-body a.bg-slate-100 {
        background-color: rgba(255, 255, 255, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.7);
        color: #334155;
    }
    #modal-admin-body a.bg-slate-100:hover {
        background-color: rgba(255, 255, 255, 0.8);
    }
</style>

<div id="modal-admin" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 sm:p-6" role="dialog" aria-modal="true" aria-label="Formulir data">
    <!-- Latar blur (klik untuk menutup) -->
    <div data-modal-close class="absolute inset-0 bg-slate-900/30 backdrop-blur-md"></div>

    <!-- Kartu kaca -->
    <div class="modal-masuk relative w-full max-w-2xl rounded-3xl bg-white/50 backdrop-blur-xl border border-white/60 shadow-2xl shadow-indigo-300/40">
        <button type="button" data-modal-close aria-label="Tutup" title="Tutup" class="absolute top-4 right-4 z-10 p-2 text-slate-500 hover:text-slate-800 hover:bg-white/70 rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <div id="modal-admin-body" class="max-h-[88vh] overflow-y-auto p-8 sm:p-10"></div>
    </div>
</div>

<script>
(function () {
    const modal = document.getElementById('modal-admin');
    const body  = document.getElementById('modal-admin-body');
    if (!modal || !body) return;

    // Link tambah / edit (semua data) dan import Excel (siswa & guru) dibuka sebagai popup
    const POLA = /\/admin\/(students|teachers|classes|labs|computers)\/(create|\d+\/edit)\/?$|\/admin\/(students|teachers)\/import\/?$/;
    const POLA_IMPORT = /\/admin\/(students|teachers)\/import\/?$/;

    // Setelah import selesai, halaman di belakang popup perlu dimuat ulang supaya datanya muncul
    let perluMuatUlang = false;

    function terbuka() { return !modal.classList.contains('hidden'); }

    function buka() {
        perluMuatUlang = false;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function tutup() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
        body.innerHTML = '';

        if (perluMuatUlang) {
            perluMuatUlang = false;
            window.location.reload();
        }
    }

    function tampilSpinner() {
        body.innerHTML =
            '<div class="py-20 flex flex-col items-center justify-center text-slate-600">' +
            '<svg class="w-8 h-8 animate-spin text-indigo-600 mb-3" fill="none" viewBox="0 0 24 24">' +
            '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>' +
            '<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>' +
            '<p class="text-sm font-bold">Memuat formulir...</p></div>';
    }

    // Ambil judul + isi form dari halaman Tambah/Edit/Import, lalu tampilkan di popup
    function isiFormulir(html) {
        const dok   = new DOMParser().parseFromString(html, 'text/html');
        const judul = dok.querySelector('main h2');
        const kartu = judul && judul.parentElement ? judul.parentElement.querySelector(':scope > .bg-white') : null;
        if (!judul || !kartu || !kartu.querySelector('form')) return false;

        body.innerHTML = '';

        // Judul + keterangan
        const kepala = document.createElement('div');
        kepala.className = 'mb-8 pr-10';

        const h2 = document.createElement('h2');
        h2.className = 'text-2xl font-extrabold text-slate-800 tracking-tight';
        h2.textContent = judul.textContent.trim();
        kepala.appendChild(h2);

        const sub = judul.nextElementSibling;
        if (sub && sub.tagName === 'P') {
            const p = document.createElement('p');
            p.className = 'text-slate-600 font-medium text-sm mt-1';
            p.textContent = sub.textContent.trim();
            kepala.appendChild(p);
        }

        // Isi form (info + form)
        const isi = document.createElement('div');
        isi.innerHTML = kartu.innerHTML;

        // Tombol "Batal" menutup popup, bukan pindah halaman
        isi.querySelectorAll('a').forEach(function (a) {
            if (a.textContent.trim() === 'Batal') a.setAttribute('data-modal-close', '');
        });

        body.appendChild(kepala);
        body.appendChild(isi);

        const pertama = body.querySelector('input:not([type=hidden]):not([type=file]), select, textarea');
        if (pertama) pertama.focus({ preventScroll: true });

        return true;
    }

    // Baca halaman import setelah file diunggah.
    // Mengembalikan true (hasil sudah ditampilkan), teks (pesan galat), atau null (tidak dikenali)
    function bacaHasilImport(html) {
        const dok = new DOMParser().parseFromString(html, 'text/html');

        const judulHasil = Array.from(dok.querySelectorAll('main h3')).find(function (h) {
            return h.textContent.trim() === 'Hasil Import';
        });
        const kartu = judulHasil ? judulHasil.closest('.bg-white') : null;

        if (kartu) {
            body.innerHTML = '';

            const kepala = document.createElement('div');
            kepala.className = 'mb-6 pr-10';
            const h2 = document.createElement('h2');
            h2.className = 'text-2xl font-extrabold text-slate-800 tracking-tight';
            h2.textContent = 'Hasil Import';
            kepala.appendChild(h2);

            const isi = document.createElement('div');
            isi.innerHTML = kartu.innerHTML;

            // Buang judul ganda dan tautan "Lihat Data ..." (diganti tombol Selesai)
            const h3 = isi.querySelector('h3');
            if (h3) h3.remove();
            isi.querySelectorAll('a').forEach(function (a) { a.remove(); });

            const selesai = document.createElement('button');
            selesai.type = 'button';
            selesai.setAttribute('data-modal-close', '');
            selesai.className = 'mt-6 inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-2xl font-bold text-sm shadow-lg shadow-indigo-200 transition-all';
            selesai.textContent = 'Selesai';
            isi.appendChild(selesai);

            body.appendChild(kepala);
            body.appendChild(isi);
            body.scrollTop = 0;

            perluMuatUlang = true;
            return true;
        }

        // File tidak terbaca / judul kolom tidak sesuai: tampil sebagai kotak merah
        const galat = dok.querySelector('main div.bg-rose-50.border-rose-200');
        return galat ? galat.textContent.trim() : null;
    }

    async function muat(url) {
        buka();
        tampilSpinner();

        try {
            const res = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' },
                credentials: 'same-origin'
            });

            // Sesi habis / dialihkan / error: buka halaman biasa saja
            if (res.redirected || !res.ok) { window.location.href = url; return; }

            const html = await res.text();
            if (!isiFormulir(html)) window.location.href = url;
        } catch (e) {
            window.location.href = url;
        }
    }

    // Tampilkan daftar error di kotak merah di bawah judul
    function tampilError(daftar) {
        sembunyikanError();

        const kotak = document.createElement('div');
        kotak.setAttribute('data-form-errors', '');
        kotak.className = 'mb-6 p-4 bg-rose-50/80 backdrop-blur-sm border border-rose-200 text-rose-700 rounded-2xl text-sm font-bold';

        const ul = document.createElement('ul');
        ul.className = 'list-disc list-inside space-y-1';
        daftar.forEach(function (teks) {
            const li = document.createElement('li');
            li.textContent = teks;
            ul.appendChild(li);
        });
        kotak.appendChild(ul);

        const kepala = body.firstElementChild;
        if (kepala) kepala.insertAdjacentElement('afterend', kotak);
        else body.appendChild(kotak);

        body.scrollTop = 0;
    }

    function sembunyikanError() {
        body.querySelectorAll('[data-form-errors]').forEach(function (el) { el.remove(); });
    }

    // Susun pesan error dari respons server yang gagal
    async function pesanDariRespons(res) {
        if (res.status === 422) {
            try {
                const data = await res.json();
                const err = data.errors || {};
                const daftar = Array.isArray(err) ? err : Object.values(err).flat();
                if (daftar.length) return daftar;
            } catch (e) { /* abaikan, pakai pesan umum di bawah */ }
            return ['Data yang dikirim belum valid.'];
        }
        if (res.status === 419) return ['Sesi Anda sudah habis. Muat ulang halaman, lalu coba lagi.'];
        return ['Terjadi kesalahan di server. Coba lagi.'];
    }

    // Tangkap klik pada link tambah / edit / import
    document.addEventListener('click', function (e) {
        // Tombol tutup, latar blur, tombol "Batal", dan tombol "Selesai"
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

    // Kirim form lewat AJAX supaya error dan hasil tampil di dalam popup
    document.addEventListener('submit', async function (e) {
        const form = e.target;
        if (!body.contains(form)) return;
        e.preventDefault();

        const impor = POLA_IMPORT.test(form.action);

        const tombol = form.querySelector('button[type="submit"]');
        const teksAsli = tombol ? tombol.textContent : '';
        if (tombol) {
            tombol.disabled = true;
            tombol.classList.add('opacity-70', 'cursor-not-allowed');
            tombol.textContent = impor ? 'Mengimpor...' : 'Menyimpan...';
        }
        sembunyikanError();

        let selesai = false;

        try {
            const res = await fetch(form.action, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                body: new FormData(form),
                credentials: 'same-origin',
                // Tambah/Edit: redirect = berhasil (pesan sukses tampil setelah halaman dimuat ulang).
                // Import: ikuti redirect, lalu baca hasilnya dari halaman import.
                redirect: impor ? 'follow' : 'manual'
            });

            if (impor) {
                const tipe = res.headers.get('content-type') || '';
                if (res.ok && tipe.indexOf('text/html') !== -1) {
                    const hasil = bacaHasilImport(await res.text());
                    if (hasil === true) { selesai = true; return; }
                    tampilError([hasil || 'Hasil import tidak dapat dibaca. Muat ulang halaman untuk memeriksa datanya.']);
                } else {
                    tampilError(await pesanDariRespons(res));
                }
            } else {
                if (res.type === 'opaqueredirect' || (res.status >= 300 && res.status < 400) || res.ok) {
                    selesai = true;
                    window.location.reload();
                    return;
                }
                tampilError(await pesanDariRespons(res));
            }
        } catch (err) {
            tampilError(['Tidak dapat terhubung ke server. Periksa koneksi Anda, lalu coba lagi.']);
        }

        // Kembalikan tombol supaya isiannya bisa diperbaiki dan dikirim ulang
        if (!selesai && tombol) {
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