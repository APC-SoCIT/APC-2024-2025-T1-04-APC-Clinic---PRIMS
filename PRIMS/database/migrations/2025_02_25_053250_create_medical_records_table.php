<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();

            // patient info
            $table->string('apc_id_number')->nullable();
            $table->string('first_name');
            $table->string('mi')->nullable();
            $table->string('last_name');
            $table->string('gender')->nullable();
            $table->string('age')->nullable();
            $table->date('dob');
            $table->string('email')->nullable();
            $table->string('street_number')->nullable();
            $table->string('barangay')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('country')->nullable();
            $table->string('contact_number')->nullable();
            $table->text('nationality')->nullable();

            // medical concern
            $table->text('reason');
            $table->text('description');
            $table->date('last_visited')->nullable();

            // medical history
            $table->text('allergies')->nullable();
            $table->json('past_medical_history')->nullable();
            $table->json('family_history')->nullable();
            $table->json('social_history')->nullable();
            $table->json('obgyne_history')->nullable();
            $table->text('hospitalization')->nullable();
            $table->text('operation')->nullable();
            $table->json('immunizations')->nullable();
            
            // prescription
            $table->text('prescription')->nullable();

            // appointment reference
            $table->foreignId('appointment_id')->nullable()->constrained('appointments')->onDelete('set null');
            
            $table->timestamps();
            $table->timestamp('archived_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('medical_records');
    }
};
