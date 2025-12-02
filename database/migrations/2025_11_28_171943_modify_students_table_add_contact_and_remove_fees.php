<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            // remove fees if exists
            if (Schema::hasColumn('students', 'fees')) {
                $table->dropColumn('fees');
            }

            // add new fields
            if (!Schema::hasColumn('students', 'email')) {
                $table->string('email')->nullable()->after('phone');
            }

            if (!Schema::hasColumn('students', 'address')) {
                $table->text('address')->nullable()->after('email');
            }

            if (!Schema::hasColumn('students', 'description')) {
                $table->text('description')->nullable()->after('address');
            }
        });
    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'fees')) {
                $table->decimal('fees', 10, 2)->default(0)->after('rollnum');
            }

            if (Schema::hasColumn('students', 'description')) {
                $table->dropColumn('description');
            }

            if (Schema::hasColumn('students', 'address')) {
                $table->dropColumn('address');
            }

            if (Schema::hasColumn('students', 'email')) {
                $table->dropColumn('email');
            }
        });
    }
};
