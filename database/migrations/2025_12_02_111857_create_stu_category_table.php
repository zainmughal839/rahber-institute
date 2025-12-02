<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stu_category', function (Blueprint $table) {
            $table->id();
            $table->string('name');            // Category name
            $table->text('desc')->nullable();  // Description (optional)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stu_category');
    }
};