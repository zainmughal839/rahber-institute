<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mcq_attempts', function (Blueprint $table) {
            $table->integer('subjective_obtained')->default(0)->after('score');
            $table->integer('total_obtained')->default(0)->after('subjective_obtained');
        });
    }

    public function down(): void
    {
        Schema::table('mcq_attempts', function (Blueprint $table) {
            $table->dropColumn(['subjective_obtained', 'total_obtained']);
        });
    }
};