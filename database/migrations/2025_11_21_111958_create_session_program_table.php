<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('session_program', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('session_id');
            $table->unsignedBigInteger('program_id');

            $table->foreign('session_id')->references('id')->on('sessions')->onDelete('cascade');
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_program');
    }
};
