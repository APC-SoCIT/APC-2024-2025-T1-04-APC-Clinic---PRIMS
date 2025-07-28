<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->string('email')->nullable();
            $table->string('apc_id_number')->nullable();
            $table->string('first_name');
            $table->string('mi')->nullable();
            $table->string('last_name');
            $table->date('dob');
            $table->string('age')->nullable();
            $table->string('gender')->nullable();
            $table->string('street_number')->nullable();
            $table->string('barangay')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('country')->nullable();
            $table->string('contact_number')->nullable();
            $table->text('reason');
            $table->text('nationality')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('description');
            $table->text('allergies')->nullable();
            $table->text('past_medical_history')->nullable();
            $table->text('family_history')->nullable();
            $table->text('social_history')->nullable();
            $table->date('last_visited')->nullable();
            $table->text('pe')->nullable();
            $table->text('prescription')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('medical_records');
    }
};
