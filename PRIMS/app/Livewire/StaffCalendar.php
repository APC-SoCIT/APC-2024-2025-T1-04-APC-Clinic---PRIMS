<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\ClinicStaff;
use App\Models\DoctorSchedule;
use Illuminate\Support\Facades\Log;

class StaffCalendar extends Component
{

    public $currentDate;
    public $calendarDays = [];
    public $appointments = [];
    public $selectedDate;
    public $approvedAppointments = [];
    public $showApproveModal = false;
    public $showDeclineModal = false;
    public $showCancelModal = false;
    public $showDeclineSuccessModal = false;
    public $showCancelSuccessModal = false;
    public $selectedAppointmentId;
    public $declineReason = '';
    public $cancelReason = '';
    public $doctors;
    public $selectedDoctor;
    public $timeSlots = [
        '7:30 AM', '8:00 AM', '8:30 AM', '9:00 AM', '9:30 AM',
        '10:00 AM', '10:30 AM', '11:00 AM','11:30 AM', '12:00 PM', 
        '12:30 PM', '1:00 PM', '1:30 PM', '2:00 PM', '2:30 PM',
        '3:00 PM', '3:30 PM', '4:00 PM', '4:30 PM', '5:00 PM',
    ];
    public $selectedTimes = [];
    public $isEditingSchedule = false;
    public $stopPolling = false;

    public function mount()
    {
        $this->currentDate = Carbon::now('Asia/Manila');
        $this->selectedDate = Carbon::now('Asia/Manila')->toDateString();
        $this->generateCalendar();
        $this->loadAppointments();
        $this->doctors = ClinicStaff::where('clinic_staff_role', 'doctor')->get();
    }

    public function changeMonth($offset)
    {
        $this->currentDate->addMonths($offset);
        $this->generateCalendar();
        $this->loadAppointments();
    }

    public function selectDate($date)
    {
        $this->selectedDate = $date;
        $this->loadAppointments();

        $this->loadExistingSchedule();
        }


    public function generateCalendar()
    {
        $startOfMonth = $this->currentDate->copy()->startOfMonth();
        $startOfWeek = $startOfMonth->copy()->locale('en')->startOfWeek(Carbon::SUNDAY); 
        $endOfMonth = $this->currentDate->copy()->endOfMonth();
        $endOfWeek = $endOfMonth->copy()->endOfWeek();

        $this->calendarDays = [];

        // Fetch all pending appointments for the current month
        $pendingAppointments = Appointment::whereBetween('appointment_date', [$startOfMonth, $endOfMonth])
                                        ->where('status', 'pending')
                                        ->get();

        // Prepare a list of dates with pending appointments
        $pendingDates = $pendingAppointments->map(function($appointment) {
            return \Carbon\Carbon::parse($appointment->appointment_date)->toDateString();
        })->toArray();

        for ($date = $startOfWeek->copy(); $date->lte($endOfWeek); $date->addDay()) {
            $this->calendarDays[] = [
                'day' => $date->day,
                'date' => $date->toDateString(),
                'isToday' => $date->isToday(),
                'isCurrentMonth' => $date->month === $this->currentDate->month,
                'hasPendingAppointments' => in_array($date->toDateString(), $pendingDates),
            ];
        }
    }

    public function loadAppointments()
    {
        // Eager load the 'patient' relationship to avoid the null error
        $this->appointments = Appointment::whereDate('appointment_date', $this->selectedDate ?? $this->currentDate->toDateString())
            ->where('status', 'pending')
            ->with('patient') // Make sure to eager load the patient relationship
            ->get();

        $this->approvedAppointments = Appointment::whereDate('appointment_date', $this->selectedDate)
            ->where('status', 'approved')
            ->orderBy('appointment_date', 'asc')
            ->get();
    }

    public function confirmApprove($appointmentId)
    {
        $this->selectedAppointmentId = $appointmentId;
        $this->showApproveModal = true;
    }

    public function approveAppointment()
    {
        $appointment = Appointment::find($this->selectedAppointmentId);
        if ($appointment) {

            $clinicStaffId = ClinicStaff::where('user_id', Auth::id())->value('id');

            $appointment->status = 'approved';
            $appointment->status_updated_by = $clinicStaffId;
            $appointment->save();

            $this->showApproveModal = false;

            $this->loadAppointments();
            $this->generateCalendar();

            session()->flash('success', 'Appointment approved. Email notification sent.');
        }
    }

    public function confirmDecline($appointmentId)
    {
        $this->selectedAppointmentId = $appointmentId;
        $this->showDeclineModal = true;
    }

    public function declineAppointment()
    {
        $appointment = Appointment::find($this->selectedAppointmentId);
        if ($appointment) {
            $clinicStaffId = ClinicStaff::where('user_id', Auth::id())->value('id');

            $appointment->status = 'declined';
            $appointment->declination_reason = $this->declineReason;
            $appointment->status_updated_by = $clinicStaffId;
            $appointment->save();

            // Reset values and close modal
            $this->showDeclineModal = false;
            $this->declineReason = '';
            $this->selectedAppointmentId = null;

            $this->showDeclineSuccessModal = true;

            // Refresh calendar
            $this->loadAppointments();
            $this->generateCalendar();
        }
    }

    public function confirmCancel($appointmentId)
    {
        $this->selectedAppointmentId = $appointmentId;
        $this->showCancelModal = true;
    }

    public function cancelAppointment()
    {
        $appointment = Appointment::find($this->selectedAppointmentId);
        if ($appointment) {
            $clinicStaffId = ClinicStaff::where('user_id', Auth::id())->value('id');

            $appointment->status = 'cancelled';
            $appointment->cancellation_reason = $this->cancelReason;
            $appointment->status_updated_by = $clinicStaffId;
            $appointment->save();

            // Reset values and close modal
            $this->showCancelModal = false;
            $this->cancelReason = '';
            $this->selectedAppointmentId = null;

            $this->showCancelSuccessModal = true;

            // Refresh calendar
            $this->loadAppointments();
            $this->generateCalendar();
        }
    }

    public function updateAppointmentStatus($appointmentId, $newStatus)
    {
        $clinicStaffId = ClinicStaff::where('user_id', Auth::id())->value('id');

        $appointment = Appointment::findOrFail($appointmentId);
        $appointment->status = $newStatus;
        $appointment->status_updated_by = $clinicStaffId;
        $appointment->save();
    }

    public function startEditingSchedule()
    {
        if ($this->isEditingSchedule) {
            $this->saveSchedule();
            return; 
        }
    
        // Load existing schedule when entering edit mode
        $existingSchedule = DoctorSchedule::where('doctor_id', $this->selectedDoctor)
            ->where('date', $this->selectedDate)
            ->first();
    
        $this->selectedTimes = $existingSchedule ? $existingSchedule->available_times : [];
    
        $this->isEditingSchedule = true;
    }
    

    public function toggleTime($time)
    {
        $this->stopPolling = true;

        if (in_array($time, $this->selectedTimes)) {
            $this->selectedTimes = array_diff($this->selectedTimes, [$time]);
        } else {
            $this->selectedTimes[] = $time;
        }
    }

    public function saveSchedule()
    {
        DoctorSchedule::updateOrCreate(
            ['doctor_id' => $this->selectedDoctor, 'date' => $this->selectedDate],
            ['available_times' => $this->selectedTimes]
        );

        $this->selectedDoctor = null;
        $this->selectedDate = null;
        $this->selectedTimes = [];

        $this->stopPolling = false;

        session()->flash('success', 'Schedule saved successfully.');
        $this->isEditingSchedule = false;
    }

    public function selectDoctor($doctorId)
    {
        $this->selectedDoctor = $doctorId;

        if ($this->selectedDate) {
            $this->loadExistingSchedule();
        }
    }

    public function loadExistingSchedule()
    {
        if ($this->selectedDoctor && $this->selectedDate) {
            $existingSchedule = DoctorSchedule::where('doctor_id', $this->selectedDoctor)
                ->where('date', $this->selectedDate)
                ->first();

            $this->selectedTimes = $existingSchedule ? $existingSchedule->available_times : [];
        }
    }

    public function stopPolling()
    {
        $this->stopPolling = true;

    }


    public function render()
    {
        return view('livewire.staff-calendar', [
            'currentMonthYear' => $this->currentDate->format('F Y'),
        ]);
    }
}
