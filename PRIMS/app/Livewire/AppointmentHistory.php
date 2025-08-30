<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\DoctorSchedule;
use App\Models\Feedback;
use Barryvdh\DomPDF\Facade\Pdf;

class AppointmentHistory extends Component
{
    public $patient;
    public $appointmentHistory;
    public $hasUpcomingAppointment;
    public $showCancelModal = false;
    public $cancelAppointmentId;
    public $cancelReason;
    public $showCancelSuccessModal = false;
    public $expandedRow = null;
    public $showFeedbackModal = false;
    public $feedbackAppointmentId;
    public $feedbackText;
    public $rating = 0;
    public $anonymous = false;
    public $consultationFeedback;
    public $appointmentId;

    public function mount()
    {
        $this->patient = Auth::user()->patient;
        $this->loadAppointments();
    }

    public function loadAppointments()
    {
        $this->appointmentHistory = Appointment::with(['feedback', 'medicalRecord'])
            ->where('patient_id', $this->patient->id)
            ->orderBy('appointment_date', 'desc')
            ->get();

        $this->hasUpcomingAppointment = Appointment::where('patient_id', $this->patient->id)
            ->where('appointment_date', '>=', now())
            ->whereIn('status', ['approved'])
            ->first();
    }

    public function confirmCancel($appointmentId)
    {
        $this->cancelAppointmentId = $appointmentId;
        $this->showCancelModal = true;
    }

    public function cancelAppointment()
    {
        $appointment = Appointment::find($this->cancelAppointmentId);

        if ($appointment) {
            $appointment->status = 'cancelled';
            $appointment->cancellation_reason = $this->cancelReason;
            $appointment->status_updated_by = Auth::id();
            $appointment->save();

            $schedule = DoctorSchedule::where('doctor_id', $appointment->clinic_staff_id)
                ->where('date', Carbon::parse($appointment->appointment_date)->format('Y-m-d'))
                ->first();

            if ($schedule) {
                $availableTimes = is_array($schedule->available_times) 
                    ? $schedule->available_times 
                    : json_decode($schedule->available_times, true) ?? [];

                $newTime = Carbon::parse($appointment->appointment_date)->format('g:i A');
                if (!in_array($newTime, $availableTimes)) {
                    $availableTimes[] = $newTime;
                }

                $schedule->update(['available_times' => $availableTimes]);
            }


            // Refresh appointments
            $this->loadAppointments();

            // Reset variables
            $this->cancelAppointmentId = null;
            $this->cancelReason = null;
            $this->showCancelModal = false;
            $this->showCancelSuccessModal = true;
        }
    }

    public function toggleExpand($appointmentId)
    {
        $this->expandedRow = $this->expandedRow === $appointmentId ? null : $appointmentId;
    }

    public function openFeedbackModal($appointmentId)
    {
        $this->feedbackAppointmentId = $appointmentId;
        $this->appointmentId = $appointmentId;
        $this->showFeedbackModal = true;
    }

    public function feedback()
    {
        return $this->hasOne(Feedback::class, 'appointment_id');
    }

    public function setRating($value)
    {
        $this->rating = $value;
    }

    public function submitConsultationFeedback()
    {
        $this->validate([
            'rating' => 'required|integer|min:1|max:5'
        ]);

        Feedback::create([
            'user_id' => Auth::id(),
            'appointment_id' => $this->feedbackAppointmentId,
            'type' => 'consultation',
            'emoji' => null,
            'rating' => $this->rating,
            'comment' => $this->consultationFeedback,
            'anonymous' => $this->anonymous,
        ]);

        $this->reset(['rating', 'consultationFeedback', 'anonymous', 'showFeedbackModal', 'feedbackAppointmentId']);
    }

    public function closeFeedbackModal()
    {
        $this->showFeedbackModal = false;
        $this->feedbackAppointmentId = null;
        $this->consultationFeedback = null;
        $this->rating = 0;
    }

    public function downloadMedicalRecord($appointmentId)
    {
        $appointment = Appointment::with(['patient', 'medicalRecord'])->find($appointmentId);

        $medicalRecord = $appointment->medicalRecord;
        $patient = $appointment->patient;

        $data = [
            'patient' => $patient,
            'medicalRecord' => $medicalRecord,
            'appointment' => $appointment,
            'dateGenerated' => Carbon::now()->format('F j, Y, g:i A'),
        ];

        $pdf = Pdf::loadView('pdf.medical-record-pdf', $data);
        return response()->streamDownload(function() use ($pdf) {
            echo $pdf->output();
        }, 'Medical_Record_' . $patient->first_name . '_' . $patient->last_name . '_' . Carbon::now()->format('Ymd_His') . '.pdf');
    }


    public function render()
    {
        return view('livewire.appointment-history', [
            'appointmentHistory' => $this->appointmentHistory,
            'hasUpcomingAppointment' => $this->hasUpcomingAppointment,
        ]);
    }
}
