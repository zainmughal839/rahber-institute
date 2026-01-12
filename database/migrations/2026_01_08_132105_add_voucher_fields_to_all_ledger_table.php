<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('all_ledger', function (Blueprint $table) {
            // 1. First, change type to string (preserves all existing values like 'debit', 'credit')
            $table->string('type')->default('debit')->change();

            // 2. Update ledger_category if needed (optional)
            $table->string('ledger_category')->default('general')->change();

            // 3. Add new fields
            $table->string('voucher_no')->nullable()->unique()->after('challan_no');
            $table->date('voucher_date')->nullable()->after('voucher_no');
            $table->string('image_path')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('all_ledger', function (Blueprint $table) {
            $table->dropColumn(['voucher_no', 'voucher_date', 'image_path']);
        });
    }
};