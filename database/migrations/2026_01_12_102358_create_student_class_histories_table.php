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
        Schema::create('student_class_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('old_class_subject_id')->nullable()->constrained('class_subjects')->onDelete('set null');
            $table->foreignId('new_class_subject_id')->constrained('class_subjects')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->timestamp('promoted_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_class_histories');
    }
};