<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('session_program_program', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('session_program_id');
            $table->unsignedBigInteger('program_id');
            $table->timestamps();

            $table->foreign('session_program_id')->references('id')->on('session_program')->onDelete('cascade');
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');

            // Prevent duplicate mapping
            $table->unique(['session_program_id', 'program_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_program_program');
    }
};
