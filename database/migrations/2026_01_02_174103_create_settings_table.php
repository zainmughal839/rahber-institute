<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('logo')->nullable();           // public/storage/logos/logo.png
            $table->string('invoice_logo')->nullable();    // اگر الگ چاہیے
            $table->string('favicon')->nullable();        // favicon.ico
            $table->timestamps();
        });

        // Default settings insert
        DB::table('settings')->insert([
            'company_name' => 'Your Institute Name',
            'address'      => '123 Main Street, City, Province, Pakistan',
            'phone'        => '0000-0000000',
            'email'        => 'info@college.edu.pk',
            'logo'         => null,
            'invoice_logo' => null,
            'favicon'      => null,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};