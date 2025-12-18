<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('sessions_p', function (Blueprint $table) {
            $table->string('start_date', 10)->change();
            $table->string('end_date', 10)->change();
        });
    }

    public function down()
    {
        Schema::table('sessions_p', function (Blueprint $table) {
            $table->date('start_date')->change();
            $table->date('end_date')->change();
        });
    }
};
