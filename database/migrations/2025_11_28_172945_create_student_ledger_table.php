<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('student_ledger', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->decimal('amount', 12, 2);
            $table->enum('type', ['credit', 'debit'])->default('credit'); // credit = payment received
            $table->string('description')->nullable();
            $table->unsignedBigInteger('session_program_id')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            // session_program is optional
            $table->foreign('session_program_id')->references('id')->on('session_program')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_ledger');
    }
};
