<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('class_teacher_subject', function (Blueprint $table) {
            $table->id();

            $table->foreignId('class_teacher_id')
                ->constrained('class_teacher')
                ->cascadeOnDelete();

            $table->foreignId('subject_id')
                ->constrained('subjects')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['class_teacher_id', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_teacher_subject');
    }
};
