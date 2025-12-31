<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mcq_paper_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mcq_paper_id')->constrained()->onDelete('cascade');
            $table->foreignId('mcq_question_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mcq_paper_questions');
    }
};
