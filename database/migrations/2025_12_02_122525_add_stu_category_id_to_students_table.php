<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->unsignedBigInteger('stu_category_id')->nullable()->after('address');

            $table->foreign('stu_category_id')
                  ->references('id')
                  ->on('stu_category')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['stu_category_id']);
            $table->dropColumn('stu_category_id');
        });
    }
};