<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->foreignId('task_cat_id')->constrained('task_cat')->onDelete('cascade');

            // Task details
            $table->string('title');
            $table->text('desc')->nullable();

            // Task timings
            $table->dateTime('task_start');
            $table->dateTime('task_end');

            // Status + Flags
            $table->enum('status', ['pending', 'in-progress', 'completed', 'cancelled'])->default('pending');

            // Flag to check if completed
            $table->boolean('is_completed')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
