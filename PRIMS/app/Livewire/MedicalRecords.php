<?php

use Livewire\Component;
use App\Models\MedicalRecord;

class MedicalRecords extends Component
{
    public $records;

    public function mount()
    {
        // Fetch all medical records
        $this->records = MedicalRecord::all();  // Adjust query if necessary
    }

    public function render()
    {
        return view('livewire.medical-records');  // This should point to livewire.medical-records.blade.php
    }
}
