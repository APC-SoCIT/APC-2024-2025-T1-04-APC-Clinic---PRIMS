<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Appointment;
use App\Models\Patient;
use Carbon\Carbon;

class CancelledAppointment extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $selectedDate;
    public $selectedTime;
    public $reason;

    public function __construct($appointment)
    {
        $this->appointment = $appointment;
        $this->selectedDate = Carbon::parse($appointment->appointment_date)->format('F d, Y');
        $this->selectedTime = Carbon::parse($appointment->appointment_date)->format('h:i A');
        $this->reason = $appointment->cancellation_reason;
    }

    public function build()
    {
        return $this->subject('Appointment Status')
        ->view('emails.cancelled-appointment')
        ->with([
            'appointment'   => $this->appointment,
            'selectedDate'  => $this->selectedDate,
            'selectedTime'  => $this->selectedTime,
        ]);
    }
}