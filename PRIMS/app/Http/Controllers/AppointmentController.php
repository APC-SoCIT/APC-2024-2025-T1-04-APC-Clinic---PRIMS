<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Appointment;
use App\Models\Patient;

class AppointmentController extends Controller
{
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
}