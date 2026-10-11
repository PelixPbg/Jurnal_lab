<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('lab_journals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained('lab_schedules')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->dateTime('check_in_at')->nullable();
            $table->dateTime('check_out_at')->nullable();
            $table->text('activity_notes')->nullable();
            $table->text('student_notes')->nullable(); // Catatan refleksi siswa
            $table->enum('status', ['draft', 'submitted', 'revision', 'approved'])->default('draft');
            $table->text('teacher_notes')->nullable();
            $table->dateTime('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void { 
        Schema::dropIfExists('lab_journals'); 
    }
};