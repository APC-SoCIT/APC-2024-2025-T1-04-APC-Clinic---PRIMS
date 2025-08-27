<?php
namespace App\Services;

use App\Models\Appointment;
use Illuminate\Support\Facades\Mail;
use App\Mail\ClinicAppointmentNotif;
use App\Mail\PatientAppointmentNotif;

class AppointmentService
{
    public function createAppointment(array $data, $patient, $user)
    {
        $appointment = Appointment::create([
            'appointment_date' => $data['appointment_date'],
            'appointment_time' => $data['appointment_time'],
            'doctor_id' => $data['doctor_id'],
            'patient_id' => $patient->id,
        ]);

        // send mails
        Mail::to('primsapcclinic@gmail.com')
            ->send(new ClinicAppointmentNotif($appointment));

        Mail::to($user->email)
            ->send(new PatientAppointmentNotif($appointment));

        return $appointment;
    }
}
