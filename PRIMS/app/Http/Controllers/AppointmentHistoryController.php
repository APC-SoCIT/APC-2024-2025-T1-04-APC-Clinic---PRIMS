<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;


class AppointmentHistoryController extends Controller
{
    public function showAppointmentHistory()
    {
        // Fetch appointment history for logged-in patient
        $appointmentHistory = Appointment::where('patient_id', Auth::id())
            ->orderBy('appointment_date', 'desc')
            ->get();

        $user = Auth::user();
        if (!$user || !$user->hasRole('patient')) {
            abort(403); // Forbidden
        }

        // Fetch next upcoming approved appointment
        $hasUpcomingAppointment = Appointment::where('patient_id', Auth::id())
            ->where('appointment_date', '>=', Carbon::now())
            ->where('status', 'approved')
            ->orderBy('appointment_date', 'asc')
            ->first();

        return view('appointment-history', compact('appointmentHistory', 'hasUpcomingAppointment'));
    }

public function printMedicalRecord($appointmentId)
{
    // Find the appointment by ID
    $appointment = Appointment::with(['patient', 'medicalRecords'])->findOrFail($appointmentId);

    // Fetch the associated medical records (you can adjust this based on how you structure the records)
    $medicalRecords = $appointment->medicalRecords;

    // Retrieve the patient details
    $patient = $appointment->patient;

    // Generate PDF using DomPDF
    $pdf = \PDF::loadView('pdf.medical-record-pdf', [
        'patient' => $patient,
        'medicalRecords' => $medicalRecords,
        'appointment' => $appointment,
    ]);

    // Return the generated PDF file for download
    return $pdf->download('medical_record_' . $appointment->id . '.pdf');
}
}
