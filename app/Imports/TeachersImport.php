<?php
namespace App\Imports;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TeachersImport implements ToCollection, WithHeadingRow
{
    public int $berhasil = 0;
    public array $dilewati = [];
    public ?string $galat = null;

    public function collection(Collection $rows)
    {
        // Pastikan file tidak kosong dan judul kolomnya sesuai template
        $pertama = $rows->first();

        if ($pertama === null) {
            $this->galat = 'File kosong. Isi data mulai dari baris ke-2, di bawah baris judul kolom.';
            return;
        }

        if (!$pertama->has('nip') || !($pertama->has('nama') || $pertama->has('name'))) {
            $this->galat = 'Judul kolom tidak sesuai. Baris pertama harus berisi: nip, nama. Unduh template yang disediakan supaya formatnya benar.';
            return;
        }

        // Daftar NIP/username yang sudah dipakai (diperbarui terus selama proses,
        // jadi NIP dobel di dalam file yang sama juga ketahuan)
        $sudahAda = array_flip(array_map('strval', array_merge(
            Teacher::pluck('nip')->all(),
            User::pluck('username')->all()
        )));

        foreach ($rows as $index => $row) {
            $baris = $index + 2; // baris 1 di Excel adalah judul kolom

            $nip  = self::teks($row->get('nip'));
            $nama = self::teks($row->get('nama') ?? $row->get('name'));

            // Baris kosong dilewati tanpa dihitung sebagai masalah
            if ($nip === '' && $nama === '') {
                continue;
            }

            if ($nip === '' || $nama === '') {
                $this->lewati($baris, $nip, 'Data tidak lengkap (nip dan nama wajib diisi)');
                continue;
            }

            if (mb_strlen($nama) > 255) {
                $this->lewati($baris, $nip, 'Nama terlalu panjang (maksimal 255 karakter)');
                continue;
            }

            if (isset($sudahAda[$nip])) {
                $this->lewati($baris, $nip, 'NIP sudah terdaftar (atau dobel di dalam file)');
                continue;
            }

            try {
                DB::transaction(function () use ($nip, $nama) {
                    // 1. Buat akun login guru
                    $user = User::create([
                        'username' => $nip,
                        'password' => Hash::make($nip . '*'), // Password default: NIP + *
                        'role'     => 'guru',
                    ]);

                    // 2. Buat profil guru
                    Teacher::create([
                        'user_id' => $user->id,
                        'nip'     => $nip,
                        'name'    => $nama,
                    ]);
                });

                $sudahAda[$nip] = true;
                $this->berhasil++;
            } catch (\Throwable $e) {
                $this->lewati($baris, $nip, 'Gagal disimpan ke database');
            }
        }
    }

    private function lewati(int $baris, string $data, string $alasan): void
    {
        $this->dilewati[] = ['baris' => $baris, 'data' => $data, 'alasan' => $alasan];
    }

    // Angka dari Excel (misal 198001.0) dijadikan teks bersih "198001"
    private static function teks($nilai): string
    {
        if (is_int($nilai) || is_float($nilai)) {
            return number_format($nilai, 0, '', '');
        }
        return trim((string) $nilai);
    }
}