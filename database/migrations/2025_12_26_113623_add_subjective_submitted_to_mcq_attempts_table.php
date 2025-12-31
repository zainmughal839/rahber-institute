<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mcq_attempts', function (Blueprint $table) {
            $table->boolean('subjective_submitted')->default(false)->after('score');
        });
    }

    public function down(): void
    {
        Schema::table('mcq_attempts', function (Blueprint $table) {
            $table->dropColumn('subjective_submitted');
        });
    }
};