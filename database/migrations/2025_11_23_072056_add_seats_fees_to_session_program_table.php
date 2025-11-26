<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('session_program', function (Blueprint $table) {
            $table->integer('seats')->nullable()->after('program_id');
            $table->decimal('fees', 10, 2)->nullable()->after('seats');
        });
    }

    public function down()
    {
        Schema::table('session_program', function (Blueprint $table) {
            $table->dropColumn(['seats', 'fees']);
        });
    }
};
