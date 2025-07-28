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
        Schema::create('patients', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('apc_id_number')->unique();
            $table->string('first_name');
            $table->string('middle_initial', 1)->nullable(); // Optional, single character
            $table->string('last_name');
            $table->string('email')->unique();
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->date('date_of_birth');
            $table->string('nationality')->nullable();
            $table->unsignedBigInteger('category_id')->nullable(); // Placeholder for foreign key
            $table->string('contact_number');
            $table->unsignedBigInteger('medical_history_id')->nullable(); // Placeholder for foreign key
            $table->unsignedBigInteger('emergencycontact_id')->nullable(); // Placeholder for foreign key
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
