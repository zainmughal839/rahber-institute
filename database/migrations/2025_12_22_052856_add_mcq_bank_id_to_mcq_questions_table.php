<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('mcq_questions', function (Blueprint $table) {
            $table->foreignId('mcq_bank_id')
                  ->after('id')
                  ->constrained('mcq_banks')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('mcq_questions', function (Blueprint $table) {
            $table->dropForeign(['mcq_bank_id']);
            $table->dropColumn('mcq_bank_id');
        });
    }
};
