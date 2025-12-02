<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();

            // Basic Info
            $table->string('name');
            $table->string('cnic')->nullable()->unique();
            $table->string('email')->nullable()->unique();
            $table->string('address')->nullable();

            // Main profile picture
            $table->string('picture')->nullable();

            // CNIC images
            $table->string('cnic_front_image')->nullable();
            $table->string('cnic_back_image')->nullable();

            /*
             * Academic detail → JSON format:
             * [
             *    { "degree": "BSCS", "institute": "PU", "passing_year": "2020", "image": "degree.jpg" },
             *    { "degree": "MCS", "institute": "UET", "passing_year": "2022", "image": "mcs.jpg" }
             * ]
             */
            $table->text('academic_details')->nullable();


            $table->timestamps();
            $table->softDeletes(); // for safe delete
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
