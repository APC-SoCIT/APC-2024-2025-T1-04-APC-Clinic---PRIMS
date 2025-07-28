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

class AppointmentController extends Controller
{
    // for patients to see their own appointments
    public function index()
    {
        $patient = Auth::user()->patient;

        if (!$patient) {
            abort(403, 'Unauthorized action.');
        }

        $appointments = $patient->appointments; // Use the relationship

        return view('appointments.index', compact('appointments'));
    }

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

    // for patients booking their own appointments
    public function store(Request $request)
    {
        $request->validate([
            'appointment_date' => 'required|date',
            'appointment_time' => 'required', 
            'doctor_id' => 'required|exists:doctors,id', 
        ]);

        $patient = Auth::user()->patient; // Get the logged-in patient

        if (!$patient) {
            abort(403, 'Unauthorized action.'); 
        }

        $appointment = new Appointment();
        $appointment->appointment_date = $request->input('appointment_date');
        $appointment->appointment_time = $request->input('appointment_time');
        $appointment->doctor_id = $request->input('doctor_id'); // Assign the doctor
        $appointment->patient_id = $patient->id; // Use the relationship

        $appointment->save();
        
        Mail::to('primsapcclinic@gmail.com')->send(new ClinicAppointmentNotif($appointment, $selectedDate, $selectedTime));

        Mail::to(Auth::user()->email)->send(new PatientAppointmentNotif($appointment, $selectedDate, $selectedTime));

        return redirect()->route('appointments.index')->with('success', 'Appointment created successfully.');
    }
}