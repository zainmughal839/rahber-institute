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
    Schema::create('program_parts', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('program_id');
        $table->string('part_name'); // Example: Part 1, Semester 1, Term A
        $table->timestamps();

        $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_parts');
    }
};
