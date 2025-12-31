<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mcq_papers', function (Blueprint $table) {
            $table->integer('subjective_time')->default(30)->after('per_mcqs_time'); // minutes
        });
    }

    public function down(): void
    {
        Schema::table('mcq_papers', function (Blueprint $table) {
            $table->dropColumn('subjective_time');
        });
    }
};