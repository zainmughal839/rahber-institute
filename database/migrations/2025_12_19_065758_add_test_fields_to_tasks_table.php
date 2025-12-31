<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {

            $table->boolean('has_test')->default(false)->after('is_completed');

            $table->unsignedBigInteger('test_category_id')->nullable()->after('has_test');
            $table->enum('test_type', ['oral','written'])->nullable();
            $table->string('test_title')->nullable();
            $table->text('test_desc')->nullable();
            $table->text('test_orientation')->nullable();

            $table->dateTime('result_announce_at')->nullable();
            $table->dateTime('paper_submit_at')->nullable();

            $table->integer('total_marks')->nullable();
            $table->integer('passing_marks')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn([
                'has_test',
                'test_category_id',
                'test_type',
                'test_title',
                'test_desc',
                'test_orientation',
                'result_announce_at',
                'paper_submit_at',
                'total_marks',
                'passing_marks'
            ]);
        });
    }
};
