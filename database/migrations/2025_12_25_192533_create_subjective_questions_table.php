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
        Schema::create('subjective_questions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('mcq_paper_id')->constrained()->cascadeOnDelete();
    $table->text('question');
    $table->integer('marks');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjective_questions');
    }
};
