<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MedicalRecord;

class MedicalRecordsTable extends Component
{
    public $records;
    public $expandedPatient = null;

    protected $listeners = ['recordAdded' => 'loadRecords'];

    public function mount()
    {
        $this->loadRecords();
    }

    public function loadRecords()
    {
        $this->records = MedicalRecord::query()
            ->selectRaw('MAX(id) as id')
            ->groupBy('apc_id_number')
            ->pluck('id');

        $this->records = MedicalRecord::with('diagnoses')
            ->whereIn('id', $this->records)
            ->orderByDesc('last_visited')
            ->get();
    }

    public function toggleExpand($apcId)
    {
        $this->expandedPatient = $this->expandedPatient === $apcId ? null : $apcId;
    }

    public function getPatientRecords($apcId)
    {
        return MedicalRecord::where('apc_id_number', $apcId)
            ->orderByDesc('last_visited')
            ->with('diagnoses')
            ->get();
    }

    public function render()
    {
        return view('livewire.medical-records-table');
    }
}

