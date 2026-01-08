<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('task_cat', function (Blueprint $table) {
        $table->integer('d_married_points')->default(0)->after('desc');
    });
}

public function down()
{
    Schema::table('task_cat', function (Blueprint $table) {
        $table->dropColumn('d_married_points');
    });
}

};
