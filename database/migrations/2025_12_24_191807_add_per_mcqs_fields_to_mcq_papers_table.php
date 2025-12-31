<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('mcq_papers', function (Blueprint $table) {
            $table->integer('per_mcqs_num')->default(0)->after('description');
            $table->integer('per_mcqs_time')->default(60)->after('per_mcqs_num'); // in minutes
        });
    }

    public function down()
    {
        Schema::table('mcq_papers', function (Blueprint $table) {
            $table->dropColumn(['per_mcqs_num', 'per_mcqs_time']);
        });
    }
};