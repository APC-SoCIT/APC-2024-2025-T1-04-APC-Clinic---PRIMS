<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicalRecord;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\ClinicStaff;
use Carbon\Carbon;

class MedicalRecordController extends Controller
{
    public $archiveRecord, $appointmentId, $appointment;

    public function view($id)
    {
        $record = MedicalRecord::findOrFail($id);
        return view('view-medical-record', compact('record'));
    }

    public function printMedicalRecord($appointmentId)
    {
        // Find the appointment by ID
        $appointment = Appointment::with(['patient', 'medicalRecords'])->findOrFail($appointmentId);
    
        // Fetch the associated medical records (you can adjust this based on how you structure the records)
        $medicalRecords = $appointment->medicalRecords;
    
        // Retrieve the patient details
        $patient = $appointment->patient;
    
        // Ensure that the medical record data includes diagnosis, treatment, and notes
        // You might need to modify the medical records model if necessary to include these attributes
    
        // Generate PDF using DomPDF
        $pdf = \PDF::loadView('pdf.medical-record-pdf', [
            'patient' => $patient,
            'medicalRecords' => $medicalRecords,
            'appointment' => $appointment,
        ]);
    
        // Return the generated PDF file for download
        return $pdf->download('medical_record_' . $appointment->id . '.pdf');
    }

    public function create(Request $request)
    {
        $appointmentId = $request->appointmentId;
        $appointment = Appointment::with('patient')->find($appointmentId);

        return view('livewire.add-medical-record', 
        [
            'patient' => $appointment->patient,
            'appointmentId' => $appointment->id,
            'email' => $appointment->patient->email,
            'apc_id_number' => $appointment->patient->apc_id_number,
            'first_name' => $appointment->patient->first_name,
            'mi' => $appointment->patient->middle_initial,
            'last_name' => $appointment->patient->last_name,
            'dob' => $appointment->patient->date_of_birth,
            'gender' => $appointment->patient->gender,
            'street_number' => $appointment->patient->street_number,
            'barangay' => $appointment->patient->barangay,
            'city' => $appointment->patient->city,
            'province' => $appointment->patient->province,
            'zip_code' => $appointment->patient->zip_code,
            'country' => $appointment->patient->country,
            'contact_number' => $appointment->patient->contact_number,
            'nationality' => $appointment->patient->nationality,
            'age' => Carbon::parse($appointment->patient->date_of_birth)->age,
        ]);
    }

}
