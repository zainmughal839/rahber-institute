<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('student_ledger', function (Blueprint $table) {
            $table->string('ledger_category', 50)
                ->after('type')
                ->default('total_fee')
                ->comment('Category of ledger: total_fee, advance, monthly_fee, fine');
        });
    }

    public function down(): void
    {
        Schema::table('student_ledger', function (Blueprint $table) {
            $table->dropColumn('ledger_category');
        });
    }
};
