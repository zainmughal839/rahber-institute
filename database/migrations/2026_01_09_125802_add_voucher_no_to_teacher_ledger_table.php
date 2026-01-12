<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('teacher_ledger', function (Blueprint $table) {
            $table->string('voucher_no')->nullable()->after('id');
        });
    }

    public function down()
    {
        Schema::table('teacher_ledger', function (Blueprint $table) {
            $table->dropColumn('voucher_no');
        });
    }
};
