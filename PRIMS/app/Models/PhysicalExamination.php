<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PhysicalExamination extends Model
{
    use HasFactory;

    protected $table = 'physical_exams';

    protected $fillable = [
        'medical_record_id',
        'section',
        'normal',
        'findings',
    ];

    protected $casts = [
        'normal' => 'boolean',
    ];

    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }
}
