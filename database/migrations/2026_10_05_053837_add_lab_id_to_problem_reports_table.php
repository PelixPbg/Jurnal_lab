<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('problem_reports', function (Blueprint $table) {
            // Lab yang dipilih siswa saat membuat laporan (boleh kosong untuk data lama)
            $table->foreignId('lab_id')->nullable()->after('student_id')->constrained('labs')->nullOnDelete();
        });

        // Isi lab_id untuk laporan lama yang sudah punya komputer
        DB::statement('UPDATE problem_reports pr JOIN computers c ON pr.computer_id = c.id SET pr.lab_id = c.lab_id WHERE pr.lab_id IS NULL');

        // Isi lab_id untuk laporan lama yang berasal dari jurnal check-out
        DB::statement('UPDATE problem_reports pr JOIN lab_journals lj ON pr.journal_id = lj.id JOIN lab_schedules ls ON lj.schedule_id = ls.id SET pr.lab_id = ls.lab_id WHERE pr.lab_id IS NULL');
    }

    public function down(): void {
        Schema::table('problem_reports', function (Blueprint $table) {
            $table->dropConstrainedForeignId('lab_id');
        });
    }
};