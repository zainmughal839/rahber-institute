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
    Schema::table('mcq_questions', function (Blueprint $table) {
        $table->dropColumn('mcq_paper_id');
        $table->foreignId('mcq_category_id')
              ->after('id')
              ->constrained('mcq_categories')
              ->cascadeOnDelete();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
