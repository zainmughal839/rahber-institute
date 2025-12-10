<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_assignments', function (Blueprint $table) {
            $table->id();

            // Jo user assign ho raha hai
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Student ya Teacher dono ko support karne ke liye morph
            $table->morphs('assignable');
            // assignable_id
            // assignable_type

            // teacher ya student panel
            $table->enum('panel_type', ['student', 'teacher']);

            // Student/Teacher ka email autofill hoga
            $table->string('email');

            // Password user table main already store hota hai,
            // lekin flag rakh lein agar zarurat ho
            $table->boolean('password_set')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_assignments');
    }
};