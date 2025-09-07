<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Supply;
use Carbon\Carbon;

class InventoryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'brand' => 'nullable|string',
            'name' => 'required|string',
            'category' => 'required|string',
            'dosage_form' => 'required|string',
            'strength_number' => 'required|integer',
            'strength_unit' => 'required|string',
            'date_supplied' => 'required|date',
            'quantity_received' => 'required|integer|min:1',
            'expiration_date' => 'nullable|date|after_or_equal:date_supplied',
        ]);

        // Check if the supply already exists
        $supply = Supply::firstOrCreate(
            [
                'name' => $request->name,
                'brand' => $request->brand,
                'category' => $request->category,
                'dosage_strength' => trim(($request->strength_number ?? '') . ' ' . ($request->strength_unit ?? '')),
                'dosage_form' => $request->dosage_form,
            ]
        );

        $clinicStaff = auth()->user()->clinicStaff;


        // Insert into inventory
        Inventory::create([
            'supply_id' => $supply->id,
            'quantity_received' => $request->quantity_received,
            'date_supplied' => Carbon::createFromFormat('m/d/Y', $request->date_supplied)->format('Y-m-d'),
            'expiration_date' => Carbon::createFromFormat('m/d/Y', $request->expiration_date)->format('Y-m-d'),
            'updated_by' => $clinicStaff->id,
        ]);

        return redirect()
            ->route('medical-inventory')
            ->with('toast', [
                'style' => 'success',
                'message' => 'Medicine added successfully!'
            ]);
    }

    public function show($id)
    {
        $item = Inventory::findOrFail($id); // Fetch the inventory item
        return view('inventory-details', compact('item'));
    }

}

