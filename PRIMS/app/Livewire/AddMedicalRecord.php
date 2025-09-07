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

class AddMedicalRecord extends Component
{
    public $apc_id_number, $email, $first_name, $mi, $last_name, $contact_number, $dob, $age, $gender, $street_number, $barangay, $city, $province, $zip_code, $country, $reason, $nationality, $description, $allergies, $hospitalization, $operation, $prescription;
    public $appointment_id;
    public $fromStaffCalendar = false;
    public $diagnoses = [];
    public $sections = [
        'General Appearance', 'Skin', 'Head and Scalp', 'Eyes (OD)', 'Eyes (OS)', 
        'Corrected (OD)', 'Corrected (OS)', 'Pupils', 'Ears, Eardrums', 'Nose, Sinuses', 
        'Mouth, Throat', 'Neck, Thyroid', 'Chest, Breast, Axilla', 
        'Heart- Cardiovascular', 'Lungs- Respiratory', 'Abdomen', 'Back, Flanks', 
        'Musculoskeletal', 'Extremities', 'Reflexes', 'Neurological'
    ];
    public $physical_examinations = [];
    public $diagnosisOptions = [
        'Hypertension', 'BP Monitoring', 'Bradycardia', 'Hypotension', 'Angina', 'URTI', 'Pneumonia', 'PTB', 'Bronchitis', 'Lung Pathology', 'Acute Bronchitis', 'Acute Gastroenteritis', 'GERD', 'Hemorrhoids', 'Anorexia', 'Ligament Sprain', 'Muscle Strain', 'Costochondritis', 'Soft Tissue Contusion', 'Fracture', 'Gouty Arthritis', 'Plantar Fasciitis', 'Dislocation', 'Conjunctivitis', 'Stye', 'Foreign Body', 'Stomatitis', 'Epistaxis', 'Otitis Media', 'Foreign Body Removal', 'Tension Headache', 'Migraine', 'Vertigo', 'Hyperventilation Syndrome', 'Insomnia', 'Seizure', 'Bell\'s Palsy', 'Folliculitis', 'Acne', 'Burn', 'Wound Dressing', 'Infected Wound', 'Blister Wound', 'Seborrheic Dermatitis', 'Bruise/Hematoma', 'Urinary Tract Infection', 'Renal Disease', 'Urolithiasis', 'Hypoglycemia', 'Dyslipidemia', 'Diabetes Mellitus', 'Dysmenorrhea', 'Hormonal Imbalance', 'Pregnancy', 'Leukemia', 'Blood Dyscrasia', 'Anemia', 'Lacerated Wound', 'Punctured Wound', 'Animal Bite', 'Superficial Abrasions', 'Contact Dermatitis', 'Allergic Rhinitis', 'Bronchial Asthma', 'Hypersensitivity', 'Post Traumatic Stress', 'Bipolar Disorder', 'Clinical Depression', 'Major Depressive Disorder', 'Agoraphobia', 'ADHD', 'Anxiety Disorder', 'Others'
    ];

    public $past_medical_history = [
        'Mumps' => null, 'Heart Disorder' => null, 'Bleeding Problem' => null, 'Hepatitis' => null,
        'Chicken Pox' => null, 'Dengue' => null, 'Kidney Disease' => null, 'Covid-19' => null
    ];

    public $family_history = [
        'Bronchial Asthma' => null, 'Diabetes Mellitus' => null, 'Thyroid Disorder' => null, 
        'Cancer' => null, 'Hypertension' => null, 'Liver Disease' => null, 'Epilepsy' => null
    ];

    public $social_history = [
        'Vape' => null, 'Alcohol' => null, 'Medications' => null
    ];

    public $obgyne_history = [
        'LNMP' => null, 'OB Score' => null, 'Date of Last Delivery' => null
    ];

    public $immunizations = [
        'Hepa B' => null, 'HPV' => null, 'FLU VAC' => null, 'COVID-19 1st' => null, 'COVID-19 2nd' => null, 'Booster 1' => null, 'Booster 2' => null
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

        if (empty($this->diagnoses)) {
            $this->addDiagnosis();
        }

    }

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

    public function addDiagnosis()
    {
        $this->diagnoses[] = ['diagnosis' => '', 'notes' => ''];
    }

    public function removeDiagnosis($index)
    {
        unset($this->diagnoses[$index]);
        $this->diagnoses = array_values($this->diagnoses); // reindex
    }


    public function submit()
    {
        $this->validate([
            'email' => 'required',
            'apc_id_number' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'dob' => 'required|date',
            'age' => 'required',
            'gender' => 'required',
            'contact_number' => 'required',
            'street_number' => 'required',
            'barangay' => 'required',
            'city' => 'required',
            'province' => 'required',
            'zip_code' => 'required',
            'country' => 'required',
            'nationality' => 'required',
            'reason' => 'required',
            'description' => 'required',
            'diagnoses' => 'required|array|min:1',
            'diagnoses.*.diagnosis' => 'required|string',
            'prescription' => 'required',
        ]);

        $medicalRecord = MedicalRecord::create([
            'appointment_id' => $this->appointment_id,
            'apc_id_number' => $this->apc_id_number,
            'email' => $this->email,
            'first_name' => $this->first_name,
            'mi' => $this->mi,
            'last_name' => $this->last_name,
            'dob' => $this->dob,
            'age' => $this->age,
            'gender' => $this->gender,
            'contact_number' => $this->contact_number,
            'street_number' => $this->street_number,
            'barangay' => $this->barangay,
            'city' => $this->city,
            'province' => $this->province,
            'zip_code' => $this->zip_code,
            'country' => $this->country,
            'nationality' => $this->nationality,
            'reason' => $this->reason,
            'description' => $this->description,
            'allergies' => $this->allergies,
            'past_medical_history' => json_encode($this->past_medical_history),
            'family_history' => json_encode($this->family_history),
            'social_history' => json_encode($this->social_history),
            'obgyne_history' => json_encode($this->obgyne_history),
            'hospitalization' => $this->hospitalization,
            'operation' => $this->operation,
            'immunizations' => json_encode($this->immunizations),
            'last_visited' => now(),
            'prescription' => $this->prescription,
        ]);

        foreach ($this->diagnoses as $diag) {
            if (!empty($diag['diagnosis'])) {
                Diagnosis::create([
                    'medical_record_id' => $medicalRecord->id,
                    'diagnosis' => $diag['diagnosis'],
                    'diagnosis_notes' => $diag['notes'] ?? null,
                ]);
            }
        }


        foreach ($this->physical_examinations as $section => $data) {
            PhysicalExamination::create([
                'medical_record_id' => $medicalRecord->id,
                'section' => $section,
                'normal' => !empty($data['normal']),
                'findings' => $data['findings'] ?? null,
            ]);
        }

        if ($this->fromStaffCalendar && $this->appointment_id) {
            Appointment::where('id', $this->appointment_id)->update(['status' => 'completed']);
        }

        $this->reset();
        $this->physical_examinations = [];
        $this->diagnoses = [];
        $this->dispatch('recordAdded');

        return redirect()
            ->route('medical-records')
            ->with('toast', [
                'style' => 'success',
                'message' => 'Record saved successfully!'
            ]);
    }

    public function render()
    {
        return view('livewire.add-medical-record', [
            'buttonLabel' => $this->fromStaffCalendar ? 'Complete Appointment' : 'Submit',
        ]);
    }
}
