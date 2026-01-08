<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChallanStudentsTable extends Migration
{
    public function up()
    {
        Schema::create('challan_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('challan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->timestamps();

            $table->unique(['challan_id', 'student_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('challan_students');
    }
}