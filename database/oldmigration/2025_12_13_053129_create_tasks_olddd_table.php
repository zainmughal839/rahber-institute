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
       Schema::create('tasks_olddd', function (Blueprint $table) {
    $table->id();
    $table->json('audience'); // ['teacher','student']
    $table->foreignId('teacher_id')->nullable()->constrained()->nullOnDelete();
    $table->foreignId('task_cat_id')->nullable()->constrained('task_cat')->nullOnDelete();
    $table->foreignId('session_program_id')->nullable()->constrained()->nullOnDelete();
    $table->foreignId('stu_category_id')->nullable()->constrained('stu_category')->nullOnDelete();

    $table->string('title');
    $table->text('desc')->nullable();

    $table->dateTime('task_start')->nullable();
    $table->dateTime('task_end')->nullable();
    $table->dateTime('paper_date')->nullable();

    $table->string('teacher_heading')->nullable();
    $table->text('teacher_desc')->nullable();

    $table->string('student_heading')->nullable();
    $table->text('student_desc')->nullable();

    $table->boolean('is_completed')->default(false);

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks_olddd');
    }
};
