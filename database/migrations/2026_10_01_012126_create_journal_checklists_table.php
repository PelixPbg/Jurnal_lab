<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('journal_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_id')->constrained('lab_journals')->onDelete('cascade');
            $table->string('item_type'); 
            $table->string('item_name')->nullable(); 
            $table->string('condition_before')->default('baik');
            $table->string('condition_after')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void { 
        Schema::dropIfExists('journal_checklists'); 
    }
};