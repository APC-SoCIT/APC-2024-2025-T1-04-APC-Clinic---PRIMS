<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Appointment;
use App\Models\Patient;
use Carbon\Carbon;

class PatientAppointmentNotif extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $selectedDate;
    public $selectedTime;
    public $reason;

    /**
     * Create a new message instance.
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
        $this->selectedDate = Carbon::parse($appointment->appointment_date)->format('F d, Y');
        $this->selectedTime = Carbon::parse($appointment->appointment_date)->format('h:i A');
        $this->reason = $appointment->reason_for_visit;
    }

    public function build()
    {
        return $this->subject('Appointment Notification')
        ->view('emails.patient-appointment-notif')
        ->with([
            'appointment'   => $this->appointment,
            'selectedDate'  => $this->selectedDate,
            'selectedTime'  => $this->selectedTime,
        ]);
    }
}