<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\RfidCard;
use App\Models\Diagnosis;
use App\Models\PhysicalExamination;
use Carbon\Carbon;

class DentalForm extends Component
{
    public $apc_id_number, $email, $first_name, $mi, $last_name, $contact_number,
           $dob, $age, $gender, $street_number, $barangay, $city,
           $province, $zip_code, $country, $nationality;

    public function searchPatient()
    {
        $patient = Patient::where('apc_id_number', $this->apc_id_number)->first();

        if (!$patient) {
            $card = RfidCard::with('patient')
                ->where('rfid_uid', $this->apc_id_number)
                ->first();

            if ($card && $card->patient) {
                $patient = $card->patient;
                $this->apc_id_number = $patient->apc_id_number;
            }
        }

        if ($patient) {
            $this->email = $patient->email;
            $this->first_name = $patient->first_name;
            $this->mi = $patient->middle_initial;
            $this->last_name = $patient->last_name;
            $this->dob = $patient->date_of_birth;
            $this->gender = $patient->gender;
            $this->street_number = $patient->street_number;
            $this->barangay = $patient->barangay;
            $this->city = $patient->city;
            $this->province = $patient->province;
            $this->zip_code = $patient->zip_code;
            $this->country = $patient->country;
            $this->contact_number = $patient->contact_number;
            $this->nationality = $patient->nationality;
            $this->calculateAge();
        } else {
            $this->resetPatientFields();
        }
    }

    public function calculateAge()
    {
        $this->age = $this->dob ? Carbon::parse($this->dob)->age : null;
    }


    public $teeth = [
        'upper' => [],
        'lower' => []
    ];
    public $showModal = false;
    public $selectedTooth;
    public $selectedJaw;

    public function openModal($tooth, $jaw)
    {
        $this->selectedTooth = $tooth;
        $this->selectedJaw = $jaw;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function selectToothCondition($status)
    {
        $this->teeth[$this->selectedJaw][$this->selectedTooth] = $status;
        $this->closeModal();
    }

    public function submit()
    {
        // Example: save dental record to DB
        // DentalExam::create([
        //     'patient_id' => $this->apc_id_number,
        //     'teeth' => json_encode($this->teeth),
        // ]);
        session()->flash('success', 'Dental record submitted successfully!');
    }


    public function render()
    {
        return view('livewire.dental-form');
    }
}
