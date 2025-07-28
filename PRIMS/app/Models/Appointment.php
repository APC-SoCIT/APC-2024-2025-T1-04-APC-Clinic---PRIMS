<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'appointment_date',
        'status',
        'reason_for_visit',
        'cancellation_reason',
        'declination_reason',
        'patient_id',
        'clinic_staff_id',
        'status_updated_by',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class, 'appointment_id');
    }


    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'status_updated_by');
    }

    public function doctor()
    {
        return $this->belongsTo(ClinicStaff::class, 'clinic_staff_id');
    }

}
