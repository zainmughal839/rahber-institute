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
    Schema::table('subjective_answer_images', function (Blueprint $table) {
        $table->enum('type', ['student', 'teacher'])->default('student')->after('image_path');
    });
}

public function down()
{
    Schema::table('subjective_answer_images', function (Blueprint $table) {
        $table->dropColumn('type');
    });
}
};
