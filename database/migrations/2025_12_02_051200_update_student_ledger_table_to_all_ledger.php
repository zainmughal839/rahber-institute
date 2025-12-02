<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Rename table
        Schema::rename('student_ledger', 'all_ledger');

        Schema::table('all_ledger', function (Blueprint $table) {
            // Remove session_program_id
            if (Schema::hasColumn('all_ledger', 'session_program_id')) {
                $table->dropColumn('session_program_id');
            }

            // Add teacher_id
            $table->unsignedBigInteger('teacher_id')->nullable()->after('student_id');

            // Foreign key optional
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('set null');
        });
    }

    public function down()
    {
        // Reverse add/remove
        Schema::table('all_ledger', function (Blueprint $table) {
            // Remove teacher_id
            if (Schema::hasColumn('all_ledger', 'teacher_id')) {
                $table->dropForeign(['teacher_id']);
                $table->dropColumn('teacher_id');
            }

            // Add back session_program_id
            $table->unsignedBigInteger('session_program_id')->nullable();
        });

        // Rename table back
        Schema::rename('all_ledger', 'student_ledger');
    }
};
