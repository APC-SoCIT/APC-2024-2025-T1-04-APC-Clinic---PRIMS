<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MedicalRecord;
use App\Models\Patient;

class ViewMedicalRecord extends Component
{
    public $record;
    public $past_medical_history = [];
    public $family_history = [];
    public $social_history = [];

    // Use $record instead of $id for proper binding
    public function mount(MedicalRecord $record)
    {
        $this->record = $record;

        // Ensure null values are handled properly
        $this->past_medical_history = json_decode($record->past_medical_history ?? '[]', true);
        $this->family_history = json_decode($record->family_history ?? '[]', true);
        $this->social_history = json_decode($record->social_history ?? '[]', true);
    }

    public function render()
    {
        return view('livewire.view-medical-record', ['record' => $this->record]);
    }
}