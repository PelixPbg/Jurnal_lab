<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Teacher;
use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\Lab;
use App\Models\Computer;
use App\Models\LabSchedule;
use App\Models\LabJournal;
use App\Models\JournalChecklist;
use App\Models\ProblemReport;
use Carbon\Carbon;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // 1. ADMIN
        User::create([
            'username' => 'admin',
            'password' => Hash::make('admin*'),
            'role' => 'admin'
        ]);

        // 2. GURU (5 Guru)
        for ($i = 1; $i <= 5; $i++) {
            $nip = '19800' . $i;
            $user = User::create([
                'username' => $nip,
                'password' => Hash::make($nip . '*'),
                'role' => 'guru'
            ]);
            Teacher::create([
                'user_id' => $user->id,
                'nip' => $nip,
                // Menggunakan nama depan + nama belakang agar tanpa gelar
                'name' => $faker->firstName . ' ' . $faker->lastName 
            ]);
        }

        // 3. KELAS & SISWA
        $kelasNames = ['X PPLG 1', 'X PPLG 2', 'XI PPLG 1', 'XI PPLG 2', 'XII PPLG 1', 'XII PPLG 2'];
        $nisCounter = 10001;

        foreach ($kelasNames as $kelasName) {
            $kelas = ClassRoom::create(['name' => $kelasName]);

            for ($j = 1; $j <= 36; $j++) {
                $nis = (string)$nisCounter;
                $user = User::create([
                    'username' => $nis,
                    'password' => Hash::make($nis . '*'),
                    'role' => 'siswa'
                ]);
                Student::create([
                    'user_id' => $user->id,
                    'class_id' => $kelas->id,
                    'nis' => $nis,
                    // Menggunakan nama depan + nama belakang agar tanpa gelar
                    'name' => $faker->firstName . ' ' . $faker->lastName 
                ]);
                $nisCounter++;
            }
        }

        // 4. LAB & PC
        $pcCounter = 1;
        for ($i = 1; $i <= 3; $i++) {
            $lab = Lab::create([
                'name' => 'Lab Komputer ' . $i,
                'location' => 'Lantai ' . $i,
                'description' => 'Fasilitas Praktik Lab ' . $i
            ]);

            for ($j = 1; $j <= 36; $j++) {
                Computer::create([
                    'lab_id' => $lab->id,
                    'code' => 'PC-' . str_pad($pcCounter, 3, '0', STR_PAD_LEFT),
                    'name' => 'Client ' . $pcCounter,
                    'status' => 'baik'
                ]);
                $pcCounter++;
            }
        }

        // 5. DUMMY TRANSAKSI (Jadwal, Jurnal, Checklist, Laporan)
        $schedule = LabSchedule::create([
            'lab_id' => 1,
            'class_id' => 1,
            'teacher_id' => 1,
            'subject' => 'Pemrograman Web',
            'activity' => 'Membuat UI Laravel',
            'date' => Carbon::now()->toDateString(),
            'start_time' => '08:00:00',
            'end_time' => '10:00:00',
            'status' => 'completed'
        ]);

        // Jurnal Siswa 1 (Normal, Lancar)
        $student1 = Student::where('nis', '10001')->first();
        $journal1 = LabJournal::create([
            'schedule_id' => $schedule->id,
            'student_id' => $student1->id,
            'check_in_at' => Carbon::now()->setTime(8, 0),
            'check_out_at' => Carbon::now()->setTime(10, 0),
            'activity_notes' => 'Selesai membuat view dashboard',
            'status' => 'approved',
            'teacher_notes' => 'Bagus, lanjutkan.',
            'verified_at' => Carbon::now()
        ]);
        JournalChecklist::create([
            'journal_id' => $journal1->id,
            'item_type' => 'PC',
            'item_name' => 'PC-001',
            'condition_before' => 'baik',
            'condition_after' => 'baik'
        ]);

        // Jurnal Siswa 2 (Ada Masalah di Akhir)
        $student2 = Student::where('nis', '10002')->first();
        $journal2 = LabJournal::create([
            'schedule_id' => $schedule->id,
            'student_id' => $student2->id,
            'check_in_at' => Carbon::now()->setTime(8, 0),
            'check_out_at' => Carbon::now()->setTime(9, 45),
            'activity_notes' => 'Praktek terhenti karena PC hang',
            'status' => 'submitted'
        ]);
        JournalChecklist::create([
            'journal_id' => $journal2->id,
            'item_type' => 'PC',
            'item_name' => 'PC-002',
            'condition_before' => 'baik',
            'condition_after' => 'bermasalah',
            'notes' => 'PC sering mati sendiri'
        ]);
        ProblemReport::create([
            'journal_id' => $journal2->id,
            'student_id' => $student2->id,
            'computer_id' => 2,
            'item_name' => 'PC',
            'problem_description' => 'PC tiba-tiba bluescreen dan tidak bisa menyala',
            'status' => 'reported'
        ]);
    }
}