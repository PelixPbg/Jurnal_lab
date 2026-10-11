<?php
namespace App\Imports;

use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToCollection, WithHeadingRow
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

        if (!$pertama->has('nis') || !($pertama->has('nama') || $pertama->has('name')) || !$pertama->has('kelas')) {
            $this->galat = 'Judul kolom tidak sesuai. Baris pertama harus berisi: nis, nama, kelas. Unduh template yang disediakan supaya formatnya benar.';
            return;
        }

        // Peta nama kelas => id kelas (huruf besar/kecil dan spasi ganda diabaikan)
        $kelas = ClassRoom::all()
            ->mapWithKeys(fn ($c) => [self::norm($c->name) => $c->id])
            ->all();

        // Daftar NIS/username yang sudah dipakai (diperbarui terus selama proses,
        // jadi NIS dobel di dalam file yang sama juga ketahuan)
        $sudahAda = array_flip(array_map('strval', array_merge(
            Student::pluck('nis')->all(),
            User::pluck('username')->all()
        )));

        foreach ($rows as $index => $row) {
            $baris = $index + 2; // baris 1 di Excel adalah judul kolom

            $nis       = self::teks($row->get('nis'));
            $nama      = self::teks($row->get('nama') ?? $row->get('name'));
            $namaKelas = self::teks($row->get('kelas'));

            // Baris kosong dilewati tanpa dihitung sebagai masalah
            if ($nis === '' && $nama === '' && $namaKelas === '') {
                continue;
            }

            if ($nis === '' || $nama === '' || $namaKelas === '') {
                $this->lewati($baris, $nis, 'Data tidak lengkap (nis, nama, dan kelas wajib diisi)');
                continue;
            }

            if (mb_strlen($nama) > 255) {
                $this->lewati($baris, $nis, 'Nama terlalu panjang (maksimal 255 karakter)');
                continue;
            }

            $classId = $kelas[self::norm($namaKelas)] ?? null;
            if (!$classId) {
                $this->lewati($baris, $nis, 'Kelas "' . $namaKelas . '" tidak ditemukan di Data Kelas');
                continue;
            }

            if (isset($sudahAda[$nis])) {
                $this->lewati($baris, $nis, 'NIS sudah terdaftar (atau dobel di dalam file)');
                continue;
            }

            try {
                DB::transaction(function () use ($nis, $nama, $classId) {
                    // 1. Buat akun login siswa
                    $user = User::create([
                        'username' => $nis,
                        'password' => Hash::make($nis . '*'), // Password default: NIS + *
                        'role'     => 'siswa',
                    ]);

                    // 2. Buat data siswa
                    Student::create([
                        'user_id'  => $user->id,
                        'class_id' => $classId,
                        'nis'      => $nis,
                        'name'     => $nama,
                    ]);
                });

                $sudahAda[$nis] = true;
                $this->berhasil++;
            } catch (\Throwable $e) {
                $this->lewati($baris, $nis, 'Gagal disimpan ke database');
            }
        }
    }

    private function lewati(int $baris, string $data, string $alasan): void
    {
        $this->dilewati[] = ['baris' => $baris, 'data' => $data, 'alasan' => $alasan];
    }

    // Angka dari Excel (misal 10001.0) dijadikan teks bersih "10001"
    private static function teks($nilai): string
    {
        if (is_int($nilai) || is_float($nilai)) {
            return number_format($nilai, 0, '', '');
        }
        return trim((string) $nilai);
    }

    private static function norm(string $teks): string
    {
        return mb_strtolower(preg_replace('/\s+/', ' ', trim($teks)));
    }
}