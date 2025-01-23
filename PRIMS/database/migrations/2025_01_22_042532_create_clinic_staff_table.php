<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clinic_staff', function (Blueprint $table) {
            $table->id('clinic_staff_id'); // Primary key
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('clinic_staff_fname');
            $table->string('clinic_staff_minitial', 1)->nullable(); // Optional, single character
            $table->string('clinic_staff_lname');
            $table->string('email')->unique();
            $table->string('clinic_staff_role');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinic_staff');
    }
};
