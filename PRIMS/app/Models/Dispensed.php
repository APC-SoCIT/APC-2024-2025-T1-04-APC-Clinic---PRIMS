<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dispensed extends Model
{
    use HasFactory;

    protected $table = 'dispensed';

    protected $fillable = ['inventory_id', 'quantity_dispensed', 'date_dispensed', 'dispensed_by'];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function dispensedBy()
    {
        return $this->belongsTo(ClinicStaff::class, 'dispensed_by');
    }
}