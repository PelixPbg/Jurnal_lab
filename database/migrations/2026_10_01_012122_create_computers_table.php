<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('computers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_id')->constrained('labs')->onDelete('cascade');
            $table->string('code')->unique();
            $table->string('name')->nullable();
            $table->enum('status', ['baik', 'bermasalah', 'rusak'])->default('baik');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('computers'); }
};