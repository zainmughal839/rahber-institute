<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('father_name');
            $table->string('cnic')->nullable();
            $table->string('phone')->nullable();
            $table->decimal('fees', 10, 2);

            $table->unsignedBigInteger('session_program_id'); // correct column

            $table->timestamps();

            $table->foreign('session_program_id')
                  ->references('id')->on('session_program')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('students');
    }
};
