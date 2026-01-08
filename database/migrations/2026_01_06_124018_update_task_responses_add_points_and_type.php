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
    Schema::table('task_responses', function (Blueprint $table) {
        $table->enum('response_type', ['complete', 'not_complete'])->change();
        $table->integer('d_married_points')->default(0)->after('response_type');
    });
}

public function down()
{
    Schema::table('task_responses', function (Blueprint $table) {
        $table->dropColumn('d_married_points');
        $table->enum('response_type', ['assignment_show','objection'])->change();
    });
}

};
