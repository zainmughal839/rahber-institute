<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mcq_banks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('teacher_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->foreignId('mcq_category_id')
                  ->constrained('mcq_categories')
                  ->cascadeOnDelete();

            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('status')->default(1);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mcq_banks');
    }
};
