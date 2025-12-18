<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            
            // Audience type: teacher, student, or both
            $table->json('audience')->nullable(); // ["teacher", "student"]

            // Relations
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->unsignedBigInteger('task_cat_id')->nullable();
            $table->unsignedBigInteger('session_program_id')->nullable();
            $table->unsignedBigInteger('stu_category_id')->nullable(); // optional student category

            // Task details
            $table->string('title');
            $table->text('desc')->nullable();
            $table->datetime('task_start')->nullable();
            $table->datetime('task_end')->nullable();

            // Optional student-specific fields
            $table->datetime('paper_date')->nullable();
            $table->string('student_heading')->nullable();
            $table->text('student_desc')->nullable();

            // Optional teacher-specific fields
            $table->string('teacher_heading')->nullable();
            $table->text('teacher_desc')->nullable();

            $table->boolean('is_completed')->default(false);

            $table->timestamps();

            // Foreign keys
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('set null');
            $table->foreign('task_cat_id')->references('id')->on('task_cat')->onDelete('set null');
            $table->foreign('session_program_id')->references('id')->on('session_program')->onDelete('set null');
            $table->foreign('stu_category_id')->references('id')->on('stu_category')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
