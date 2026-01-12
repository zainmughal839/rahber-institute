<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expense_heads', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();           // Expense Head Name (e.g. Salary, Utilities)
            $table->string('code')->unique();           // Expense Head Code (e.g. EXP-001)
            $table->text('description')->nullable();    // Optional description
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expense_heads');
    }
};