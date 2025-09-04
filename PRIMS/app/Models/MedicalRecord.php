<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'apc_id_number',
        'first_name',
        'mi',
        'last_name',
        'dob',
        'age',
        'gender',
        'contact_number',
        'street_number',
        'barangay',
        'city',
        'province',
        'zip_code',
        'country',
        'nationality',
        'reason',
        'description',
        'allergies',
        'past_medical_history',
        'family_history',
        'social_history',
        'obgyne_history',
        'hospitalization',
        'operation',
        'immunizations',
        'physical_examination',
        'prescription',
        'last_visited',
        'appointment_id',
        'archived_at',
    ];

    protected static function booted()
    {
        static::addGlobalScope('notArchived', function ($query) {
            $query->whereNull('archived_at');
        });
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }

        public function diagnoses()
    {
        return $this->hasMany(Diagnosis::class);
    }

    public function physicalExaminations()
    {
        return $this->hasMany(PhysicalExamination::class);
    }

    public function scopeArchived($query)
    {
        return $query->whereNotNull('archived_at');
    }
}