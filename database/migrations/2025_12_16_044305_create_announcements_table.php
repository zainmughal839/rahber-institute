<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();

            $table->json('audience')->nullable();

            $table->foreignId('teacher_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('session_program_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('announcement_cat_id')->nullable()->constrained('announcement_categories')->nullOnDelete();

            $table->string('title')->nullable();
            $table->text('teacher_desc')->nullable();

            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
