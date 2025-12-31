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
        Schema::create('mcq_answers', function (Blueprint $table) {
    $table->id();
    $table->foreignId('mcq_attempt_id')->constrained()->cascadeOnDelete();
    $table->foreignId('mcq_question_id')->constrained()->cascadeOnDelete();
    $table->enum('selected_option',['a','b','c','d']);
    $table->boolean('is_correct');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mcq_answers');
    }
};
