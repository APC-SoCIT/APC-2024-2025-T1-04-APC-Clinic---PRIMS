<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MedicalRecord;

class ViewMedicalRecord extends Component
{
    public $record;
    public $past_medical_history = [];
    public $family_history = [];
    public $social_history = [];
    public $obgyne_history = [];
    public $immunizations = [];

    public function mount(MedicalRecord $record)
    {
        $this->record = $record;

        $this->past_medical_history = json_decode($record->past_medical_history ?? '[]', true);
        $this->family_history = json_decode($record->family_history ?? '[]', true);
        $this->social_history = json_decode($record->social_history ?? '[]', true);
        $this->obgyne_history = json_decode($record->obgyne_history ?? '[]', true);
        $this->immunizations = json_decode($record->immunizations ?? '[]', true);
    }

    public function render()
    {
        return view('livewire.view-medical-record', [
            'record' => $this->record,
            'physical_exams' => $this->record->physicalExaminations ?? [],
            'diagnoses' => $this->record->diagnoses ?? [],
        ]);
    }
}
