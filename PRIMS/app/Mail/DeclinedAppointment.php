<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Appointment;
use App\Models\Patient;

class DeclinedAppointment extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $selectedDate;
    public $selectedTime;
    public $reason;

    public function __construct($appointment)
    {
        $this->appointment = $appointment;
        $this->selectedDate = $appointment->appointment_date;
        $this->selectedTime = $appointment->appointment_time;
        $this->reason = $appointment->declination_reason;
    }

    public function build()
    {
        return $this->subject('Appointment Status')
                    ->view('emails.declined-appointment');
    }
}