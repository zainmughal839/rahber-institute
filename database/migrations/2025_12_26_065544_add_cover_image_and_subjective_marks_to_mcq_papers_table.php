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
    Schema::table('mcq_papers', function (Blueprint $table) {
        $table->string('cover_image')->nullable()->after('description');
        $table->integer('total_subjective_marks')->default(0)->after('marks_per_mcq');
    });
}

public function down()
{
    Schema::table('mcq_papers', function (Blueprint $table) {
        $table->dropColumn(['cover_image', 'total_subjective_marks']);
    });
}
};
