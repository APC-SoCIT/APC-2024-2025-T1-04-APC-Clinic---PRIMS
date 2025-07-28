<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MedicalRecord;

class MedicalRecordsTable extends Component
{
    public $records;

    protected $listeners = ['recordAdded' => 'loadRecords'];

    public function mount()
    {
        $this->loadRecords();
    }

    public function loadRecords()
    {
        $this->records = MedicalRecord::all();
    }

    public function render()
    {
        return view('livewire.medical-records-table');
    }
}

