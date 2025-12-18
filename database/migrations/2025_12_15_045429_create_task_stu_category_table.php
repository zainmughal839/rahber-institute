<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('task_stu_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();
            $table->foreignId('stu_category_id')->constrained('stu_category')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['task_id','stu_category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_stu_category');
    }
};
