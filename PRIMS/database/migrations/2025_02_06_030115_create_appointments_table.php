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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->dateTime('appointment_date');
            $table->enum('status', ['pending', 'approved', 'declined', 'completed', 'cancelled','started']);
            $table->text('reason_for_visit');
            $table->text('cancellation_reason')->nullable();
            $table->text('declination_reason')->nullable();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('clinic_staff_id')->nullable()->constrained('clinic_staff')->onDelete('set null');
            $table->foreignId('status_updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public $timestamps = true;

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
