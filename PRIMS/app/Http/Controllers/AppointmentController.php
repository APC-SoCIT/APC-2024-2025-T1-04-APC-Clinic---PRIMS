<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Appointment;
use App\Models\Patient;
use App\Notifications\AppointmentBooked;
use App\Mail\ClinicAppointmentNotif;
use App\Mail\PatientAppointmentNotif;
use App\Http\Requests\StoreAppointmentRequest;

class AppointmentController extends Controller
{
    // Function for patients to see their own appointments
    public function index()
    {
        $patient = Auth::user()->patient;

        if (!$patient) {
            abort(403, 'Unauthorized action.');
        }

        $appointments = $patient->appointments; // Use the relationship

        return view('appointments.index', compact('appointments'));
    }

    // Function for patients to see their appointment history
    public function showAppointmentHistory()
    {
        $patient = Auth::user()->patient;

        $appointmentHistory = Appointment::where('patient_id', Auth::id())
            ->with(['doctor', 'updatedBy'])
            ->orderBy('appointment_date', 'desc')
            ->get();

        $hasUpcomingAppointment = Appointment::where('patient_id', Auth::id())
            ->where('appointment_date', '>=', now())
            ->whereIn('status', ['approved'])
            ->orderBy('appointment_date', 'asc')
            ->first();

        return view('appointment-history', compact('patient', 'appointmentHistory', 'hasUpcomingAppointment'));
    }

    /*** Function for patients to book an appointment
     * Request is handled by StoreAppointmentRequest for validation
     * Service is handled by AppointmentService
    */
    public function store(StoreAppointmentRequest $request)
    {
        $patient = Auth::user()->patient; // Get the logged-in patient

        if (!$patient) {
            abort(403, 'Unauthorized action.'); 
        }

        $appointment = $appointmentService->createAppointment(
            $request->validated(),
            $patient,
            Auth::user()
        );

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment created successfully.');
    }
}