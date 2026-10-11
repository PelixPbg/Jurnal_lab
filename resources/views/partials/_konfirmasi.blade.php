{{--
    Dialog konfirmasi kaca, pengganti confirm() bawaan browser.

    Cara pakai: taruh satu baris ini di layout (sebelum </body>):

        @include('partials._konfirmasi')

    Semua form / tombol yang memakai  onsubmit="return confirm('...')"  atau
    onclick="return confirm('...')"  otomatis memakai dialog ini, jadi halaman yang sudah ada
    tidak perlu diubah. Pesannya diambil dari teks di dalam confirm('...').
--}}
<style>
    @keyframes dkMasuk {
        from { opacity: 0; transform: translateY(12px) scale(.96); }
        to   { opacity: 1; transform: none; }
    }
    .dk-masuk { animation: dkMasuk .2s ease-out both; }
</style>

<div id="dialog-konfirmasi" class="fixed inset-0 z-[200] hidden items-center justify-center p-4" role="alertdialog" aria-modal="true" aria-labelledby="dk-judul" aria-describedby="dk-pesan">
    <!-- Latar blur (klik untuk membatalkan) -->
    <div id="dk-latar" class="absolute inset-0 bg-slate-900/30 backdrop-blur-md"></div>

    <!-- Kartu kaca -->
    <div class="dk-masuk relative w-full max-w-md rounded-3xl bg-white/60 backdrop-blur-xl border border-white/60 shadow-2xl shadow-indigo-300/40 p-8 text-center">
        <div id="dk-ikon" class="w-16 h-16 mx-auto mb-5 rounded-2xl border flex items-center justify-center"></div>

        <h3 id="dk-judul" class="text-xl font-extrabold text-slate-800 tracking-tight mb-2"></h3>
        <p id="dk-pesan" class="text-sm font-medium text-slate-600 leading-relaxed mb-7"></p>

        <div class="flex items-center justify-center gap-3">
            <button type="button" id="dk-batal" class="px-6 py-3 bg-white/60 hover:bg-white/90 border border-white/70 text-slate-700 font-bold text-sm rounded-2xl transition-all focus:outline-none focus:ring-2 focus:ring-slate-400/40">Batal</button>
            <button type="button" id="dk-ya" class="px-6 py-3 text-white font-bold text-sm rounded-2xl transition-all focus:outline-none focus:ring-2 focus:ring-offset-2"></button>
        </div>
    </div>
</div>

<script>
(function () {
    const dialog = document.getElementById('dialog-konfirmasi');
    if (!dialog) return;

    const elLatar = document.getElementById('dk-latar');
    const elIkon  = document.getElementById('dk-ikon');
    const elJudul = document.getElementById('dk-judul');
    const elPesan = document.getElementById('dk-pesan');
    const btnBatal = document.getElementById('dk-batal');
    const btnYa    = document.getElementById('dk-ya');

    const SVG = {
        hapus:   '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>',
        selesai: '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>',
        umum:    '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
    };

    // Tampilan tiap jenis konfirmasi
    const MODE = {
        hapus: {
            judul: 'Hapus Data?',
            tombol: 'Ya, Hapus',
            ikon: 'bg-rose-50/80 text-rose-500 border-rose-100',
            ya: 'bg-rose-600 hover:bg-rose-700 shadow-lg shadow-rose-600/30 focus:ring-rose-500'
        },
        selesai: {
            judul: 'Tandai Selesai?',
            tombol: 'Ya, Tandai Selesai',
            ikon: 'bg-emerald-50/80 text-emerald-600 border-emerald-100',
            ya: 'bg-emerald-600 hover:bg-emerald-700 shadow-lg shadow-emerald-600/30 focus:ring-emerald-500'
        },
        umum: {
            judul: 'Konfirmasi',
            tombol: 'Ya, Lanjutkan',
            ikon: 'bg-indigo-50/80 text-indigo-600 border-indigo-100',
            ya: 'bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 shadow-lg shadow-indigo-200 focus:ring-indigo-500'
        }
    };

    function pilihMode(pesan) {
        const p = pesan.toLowerCase();
        if (p.indexOf('hapus') !== -1) return 'hapus';
        if (p.indexOf('tandai') !== -1 || p.indexOf('selesai') !== -1) return 'selesai';
        return 'umum';
    }

    let selesaiTanya = null;
    let overflowSebelumnya = '';

    function tutup(hasil) {
        dialog.classList.add('hidden');
        dialog.classList.remove('flex');
        document.body.style.overflow = overflowSebelumnya;
        if (selesaiTanya) {
            const fn = selesaiTanya;
            selesaiTanya = null;
            fn(hasil);
        }
    }

    // Tampilkan dialog, mengembalikan Promise<boolean> (true = lanjut)
    function tanya(pesan) {
        return new Promise(function (resolve) {
            if (selesaiTanya) selesaiTanya(false);
            selesaiTanya = resolve;

            const mode = MODE[pilihMode(pesan)];
            const kunci = pilihMode(pesan);

            elIkon.className = 'w-16 h-16 mx-auto mb-5 rounded-2xl border flex items-center justify-center ' + mode.ikon;
            elIkon.innerHTML = SVG[kunci];
            elJudul.textContent = mode.judul;
            elPesan.textContent = pesan;
            btnYa.className = 'px-6 py-3 text-white font-bold text-sm rounded-2xl transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 ' + mode.ya;
            btnYa.textContent = mode.tombol;

            overflowSebelumnya = document.body.style.overflow;
            document.body.style.overflow = 'hidden';
            dialog.classList.remove('hidden');
            dialog.classList.add('flex');

            // Fokus awal di "Batal" supaya Enter tidak langsung menghapus
            btnBatal.focus();
        });
    }

    btnBatal.addEventListener('click', function () { tutup(false); });
    btnYa.addEventListener('click', function () { tutup(true); });
    elLatar.addEventListener('click', function () { tutup(false); });

    document.addEventListener('keydown', function (e) {
        if (dialog.classList.contains('hidden')) return;

        if (e.key === 'Escape') {
            e.preventDefault();
            e.stopPropagation();
            tutup(false);
            return;
        }

        // Tab hanya berputar di antara dua tombol
        if (e.key === 'Tab') {
            e.preventDefault();
            (document.activeElement === btnBatal ? btnYa : btnBatal).focus();
        }
    }, true);

    // Ubah  onsubmit/onclick="return confirm('...')"  menjadi atribut data-konfirmasi
    function ubahConfirmBawaan() {
        document.querySelectorAll('[onsubmit*="confirm("], [onclick*="confirm("]').forEach(function (el) {
            const atribut = el.hasAttribute('onsubmit') && /confirm\(/.test(el.getAttribute('onsubmit')) ? 'onsubmit' : 'onclick';
            const cocok = (el.getAttribute(atribut) || '').match(/confirm\((['"])([\s\S]*?)\1\)/);
            if (!cocok) return;

            el.setAttribute('data-konfirmasi', cocok[2]);
            el.removeAttribute(atribut);
        });
    }
    ubahConfirmBawaan();

    // Cegat pengiriman form yang butuh konfirmasi
    document.addEventListener('submit', function (e) {
        const form   = e.target;
        const pemicu = e.submitter || null;
        const pesan  = (pemicu && pemicu.getAttribute('data-konfirmasi')) || form.getAttribute('data-konfirmasi');
        if (!pesan) return;

        e.preventDefault();
        e.stopImmediatePropagation();

        tanya(pesan).then(function (lanjut) {
            if (lanjut) HTMLFormElement.prototype.submit.call(form);
        });
    }, true);

    // Bisa dipakai dari kode lain: konfirmasi('Pesan...').then(function (ya) { ... })
    window.konfirmasi = tanya;
})();
</script>