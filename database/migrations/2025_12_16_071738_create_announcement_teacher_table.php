<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('announcement_teacher', function (Blueprint $table) {
        $table->id();

        $table->foreignId('announcement_id')
              ->constrained()
              ->cascadeOnDelete();

        $table->foreignId('teacher_id')
              ->constrained()
              ->cascadeOnDelete();

        $table->timestamps();

        $table->unique(['announcement_id', 'teacher_id']);
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcement_teacher');
    }
};
