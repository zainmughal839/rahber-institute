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
    Schema::create('program_part_subjects', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('program_part_id');
        $table->unsignedBigInteger('subject_id');
        $table->timestamps();

        $table->foreign('program_part_id')->references('id')->on('program_parts')->onDelete('cascade');
        $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_part_subjects');
    }
};
