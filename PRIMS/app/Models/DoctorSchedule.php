<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorSchedule extends Model
{
    use HasFactory;

    protected $fillable = ['doctor_id', 'date', 'available_times'];
    
    protected $casts = [
        'available_times' => 'array', // Convert JSON to array automatically
    ];

    public function doctor()
    {
        return $this->belongsTo(ClinicStaff::class, 'doctor_id');
    }
}
