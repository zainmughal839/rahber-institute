<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mcq_papers', function (Blueprint $table) {
            $table->enum('result_mode', ['immediate', 'scheduled'])->default('immediate');
            $table->timestamp('result_date')->nullable(); // When result will be visible
        });
    }

    public function down(): void
    {
        Schema::table('mcq_papers', function (Blueprint $table) {
            $table->dropColumn(['result_mode', 'result_date']);
        });
    }
};