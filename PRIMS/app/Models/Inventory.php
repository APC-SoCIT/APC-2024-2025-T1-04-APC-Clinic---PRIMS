<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'inventory';

    protected $fillable = ['supply_id', 'quantity_received', 'date_supplied', 'expiration_date', 'updated_by'];

    public function supply()
    {
        return $this->belongsTo(Supply::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(ClinicStaff::class, 'updated_by');
    }

    public function getRemainingStockAttribute()
    {
        $dispensed = Dispensed::where('inventory_id', $this->id)->sum('quantity_dispensed');
        return $this->quantity_received - $dispensed;
    }

    public function dispensed()
    {
        return $this->hasMany(Dispensed::class);
    }

}