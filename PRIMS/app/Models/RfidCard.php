<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RfidCard extends Model
{
    protected $fillable = ['patient_id', 'rfid_uid'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}


