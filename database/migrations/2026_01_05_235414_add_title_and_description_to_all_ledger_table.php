<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('all_ledger', function (Blueprint $table) {
            $table->string('title')->nullable()->after('ledger_category');
            $table->text('description_fee')->nullable()->after('title');
        });
    }

    public function down()
    {
        Schema::table('all_ledger', function (Blueprint $table) {
            $table->dropColumn(['title', 'description_fee']);
        });
    }
};
