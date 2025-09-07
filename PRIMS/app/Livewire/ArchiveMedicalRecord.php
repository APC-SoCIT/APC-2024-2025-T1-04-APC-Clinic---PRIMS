<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MedicalRecord;

class ArchiveMedicalRecord extends Component
{
    public $record;

    public function mount($record)
    {
        $this->record = $record;
    }

    public function archiveRecord()
    {
        MedicalRecord::where('id', $this->record)->update(['archived_at' => now()]);
        $this->dispatch('notify', style: 'success', message: 'Medical Record archived successfully.');
    }

    public function render()
    {
        return view('livewire.archive-medical-record');
    }

}
