<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      Schema::create('announcement_stu_category', function (Blueprint $table) {
    $table->id();
    $table->foreignId('announcement_id')->constrained()->cascadeOnDelete();
    $table->foreignId('stu_category_id')->constrained()->cascadeOnDelete();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcement_stu_category');
    }
};
