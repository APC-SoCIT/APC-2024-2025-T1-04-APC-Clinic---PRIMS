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
    
    public function generatePDF($appointmentId)
    {
        $appointment = Appointment::with(['patient', 'medicalRecord', 'feedback'])
            ->where('id', $appointmentId)
            ->where('patient_id', Auth::id())
            ->firstOrFail();

        $pdf = Pdf::loadView('pdf.appointment-summary', compact('appointment'));

        return $pdf->download('appointment_summary_' . $appointment->id . '.pdf');
    }
}
