<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Appointment;
use Carbon\Carbon;

class AddMedicalRecord extends Component
{
    public $apc_id_number, $email, $first_name, $mi, $last_name, $contact_number, $dob, $age, $gender, $street_number, $barangay, $city, $province, $zip_code, $country, $reason, $nationality, $description, $diagnosis, $allergies, $pe, $prescription;
    public $appointment_id;
    public $fromStaffCalendar = false;

    public $past_medical_history = [
        'Mumps' => null, 'Heart Disorder' => null, 'Bleeding Problem' => null, 'Hepatitis' => null,
        'Chicken Pox' => null, 'Dengue' => null, 'Kidney Disease' => null, 'Covid-19' => null
    ];

    public $family_history = [
        'Bronchial Asthma' => null, 'Diabetes Mellitus' => null, 'Thyroid Disorder' => null, 
        'Cancer' => null, 'Hypertension' => null, 'Liver Disease' => null, 'Epilepsy' => null
    ];

    public $social_history = [
        'Smoker' => null, 'Vape' => null, 'Alcohol' => null, 'Medications' => null
    ];

    public function mount($appointment_id = null, $fromStaffCalendar = false)
    {
        $this->appointment_id = $appointment_id;
        $this->fromStaffCalendar = in_array($fromStaffCalendar, [1, "1", true], true);
    
        if ($this->appointment_id) {
            $appointment = Appointment::with('patient')->find($this->appointment_id);
    
            if ($appointment && $appointment->patient) {
                $patient = $appointment->patient;
    
                // Autofill patient details
                $this->apc_id_number = $patient->apc_id_number;
                $this->email = $patient->email;
                $this->first_name = $patient->first_name;
                $this->mi = $patient->middle_initial;
                $this->last_name = $patient->last_name;
                $this->dob = $patient->date_of_birth;
                $this->age = $patient->date_of_birth ? Carbon::parse($patient->date_of_birth)->age : null;
                $this->gender = $patient->gender;
                $this->street_number = $patient->street_number;
                $this->barangay = $patient->barangay;
                $this->city = $patient->city;
                $this->province = $patient->province;
                $this->zip_code = $patient->zip_code;
                $this->country = $patient->country;
                $this->contact_number = $patient->contact_number;
                $this->nationality = $patient->nationality;
            }
        }
    }

    public function searchPatient()
    {
        $patient = Patient::where('apc_id_number', $this->apc_id_number)->first();

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

    private function resetPatientFields()
    {
        $this->email = null; $this->first_name = null; $this->mi = null; $this->last_name = null;
        $this->dob = null; $this->age = null; $this->gender = null; $this->street_number = null;
        $this->barangay = null; $this->city = null; $this->province = null; $this->zip_code = null;
        $this->country = null; $this->contact_number = null; $this->nationality = null;
    }

    public function calculateAge()
    {
        $this->age = $this->dob ? Carbon::parse($this->dob)->age : null;
    }

    protected $messages = [
        'reason.required' => 'Kindly answer the field.',
        'description.required' => 'Kindly provide a description.',
        'pe.required' => 'Kindly answer the field.',
        'diagnosis.required' => 'Please select a diagnosis.',
    ];

    public function submit()
    {
        $this->validate([
            'email' => 'required', 'apc_id_number' => 'required', 'first_name' => 'required',
            'last_name' => 'required', 'dob' => 'required|date', 'age' => 'required', 'gender' => 'required',
            'contact_number' => 'required', 'street_number' => 'required', 'barangay' => 'required',
            'city' => 'required', 'province' => 'required', 'zip_code' => 'required', 'country' => 'required',
            'nationality' => 'required', 'reason' => 'required', 'description' => 'required',
            'diagnosis' => 'required', 'pe' => 'required', 'prescription' => 'required',
        ]);

        $medicalRecord = MedicalRecord::updateOrCreate(
            ['apc_id_number' => $this->apc_id_number],
            [
                'email' => $this->email, 'first_name' => $this->first_name, 'mi' => $this->mi,
                'last_name' => $this->last_name, 'dob' => $this->dob, 'age' => $this->age,
                'gender' => $this->gender, 'contact_number' => $this->contact_number,
                'street_number' => $this->street_number, 'barangay' => $this->barangay,
                'city' => $this->city, 'province' => $this->province, 'zip_code' => $this->zip_code,
                'country' => $this->country, 'nationality' => $this->nationality,
                'reason' => $this->reason, 'description' => $this->description,
                'diagnosis' => $this->diagnosis, 'allergies' => $this->allergies,
                'past_medical_history' => json_encode($this->past_medical_history),
                'family_history' => json_encode($this->family_history),
                'social_history' => json_encode($this->social_history),
                'last_visited' => now(), 'pe' => $this->pe, 'prescription' => $this->prescription,
            ]
        );

        if ($this->fromStaffCalendar && $this->appointment_id) {
            Appointment::where('id', $this->appointment_id)->update(['status' => 'completed']);
        }

        $this->reset();
        $this->dispatch('recordAdded');
    }

    public function render()
    {
        return view('livewire.add-medical-record', [
            'buttonLabel' => $this->fromStaffCalendar ? 'Complete Appointment' : 'Submit',
        ]);
    }
}
