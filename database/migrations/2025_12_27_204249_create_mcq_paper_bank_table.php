<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('mcq_paper_bank', function (Blueprint $table) {
    $table->id();
    $table->foreignId('mcq_paper_id')->constrained('mcq_papers')->cascadeOnDelete();
    $table->foreignId('mcq_bank_id')->constrained('mcq_banks')->cascadeOnDelete();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mcq_paper_bank');
    }
};
