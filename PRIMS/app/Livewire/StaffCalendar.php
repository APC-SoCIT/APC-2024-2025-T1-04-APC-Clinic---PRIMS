<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\ClinicStaff;
use App\Models\DoctorSchedule;
use Illuminate\Support\Facades\Log;
use Livewire\WithPagination;
use App\Mail\ApprovedAppointment;
use App\Mail\DeclinedAppointment;
use App\Mail\CancelledAppointment;
use Illuminate\Support\Facades\Mail;
use App\Models\MedicalRecord;
use App\Models\User;

class StaffCalendar extends Component
{
    use WithPagination;

    public $currentDate;
    public $calendarDays = [];
    public $appointments = [];
    public $selectedDate;
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

    protected $paginationTheme = 'tailwind';

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

            $appointment->status = 'approved';
            $appointment->status_updated_by = Auth::id();
            $appointment->save();

            $schedule = DoctorSchedule::where('doctor_id', $appointment->clinic_staff_id)
                ->where('date', Carbon::parse($appointment->appointment_date)->format('Y-m-d'))
                ->first();

            if ($schedule) {
                $availableTimes = is_array($schedule->available_times)
                    ? $schedule->available_times
                    : json_decode($schedule->available_times, true) ?? [];

                // Remove the approved time
                $timeToRemove = Carbon::parse($appointment->appointment_date)->format('g:i A');
                $updatedTimes = array_values(array_diff($availableTimes, [$timeToRemove]));

                // Save the updated available times
                $schedule->update(['available_times' => json_encode($updatedTimes)]);
            }

            $this->showApproveModal = false;

            $this->loadAppointments();
            $this->generateCalendar();

            session()->flash('success', 'Appointment approved. Email notification sent.');

            Mail::to($appointment->patient->email)->send(new ApprovedAppointment($appointment));
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

            $appointment->status = 'declined';
            $appointment->declination_reason = $this->declineReason;
            $appointment->status_updated_by = Auth::id();
            $appointment->save();

            // Reset values and close modal
            $this->showDeclineModal = false;
            $this->declineReason = '';
            $this->selectedAppointmentId = null;

            $this->showDeclineSuccessModal = true;

            // Refresh calendar
            $this->loadAppointments();
            $this->generateCalendar();

            Mail::to($appointment->patient->email)->send(new DeclinedAppointment($appointment));
        }
    }

    public function startAppointment($appointmentId)
    {
        $appointment = Appointment::find($appointmentId);
    
        if (!$appointment) {
            session()->flash('error', 'Appointment not found.');
            return;
        }
    
        return redirect()->route('addRecordmain', [
            'appointment_id' => $appointment->id,
            'fromStaffCalendar' => true
        ]);
    }

    public function reapproveAppointment($appointmentId)
    {
        $appointment = Appointment::find($appointmentId);
    
        if ($appointment && $appointment->status !== 'approved') {
            $appointment->status = 'approved';
            $appointment->status_updated_by = Auth::id();
            $appointment->save();
    
            // Refresh calendar and appointments
            $this->loadAppointments();
            $this->generateCalendar();
    
            session()->flash('success', 'Appointment re-approved successfully.');
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

            $appointment->status = 'cancelled';
            $appointment->cancellation_reason = $this->cancelReason;
            $appointment->status_updated_by = Auth::id();
            $appointment->save();

            $schedule = DoctorSchedule::where('doctor_id', $appointment->clinic_staff_id)
                ->where('date', Carbon::parse($appointment->appointment_date)->format('Y-m-d'))
                ->first();

            if ($schedule) {
                $availableTimes = json_decode($schedule->available_times, true) ?? [];

                // Add the canceled time back if it's not already there
                $newTime = Carbon::parse($appointment->appointment_date)->format('g:i A');
                if (!in_array($newTime, $availableTimes)) {
                    $availableTimes[] = $newTime;
                }

                // Save the updated available times
                $schedule->update(['available_times' => json_encode($availableTimes)]);
            }

            Mail::to($appointment->patient->email)->send(new CancelledAppointment($appointment));

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

        $appointment = Appointment::findOrFail($appointmentId);
        $appointment->status = $newStatus;
        $appointment->status_updated_by = Auth::id();
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

    public function cancelEditingSchedule()
    {
        $this->isEditingSchedule = false; 
        $this->selectedDoctor = null; 
        $this->selectedTimes = []; 
        $this->stopPolling = false; 
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
        // Validation
        if (empty($this->selectedDoctor) || empty($this->selectedDate) || empty($this->selectedTimes)) {
            $this->dispatch('notify', style: 'danger', message: 'Please select a doctor, date, and at least one available time.');
            return;
        }


        
        DoctorSchedule::updateOrCreate(
            ['doctor_id' => $this->selectedDoctor, 'date' => $this->selectedDate],
            ['available_times' => $this->selectedTimes]
        );

        $this->selectedDoctor = null;
        $this->selectedDate = null;
        $this->selectedTimes = [];

        $this->stopPolling = false;

        $this->dispatch('notify', style: 'success', message: 'Schedule saved successfully.');

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

            $this->selectedTimes = $existingSchedule 
                ? (is_array($existingSchedule->available_times) 
                    ? $existingSchedule->available_times 
                    : json_decode($existingSchedule->available_times, true) ?? []) 
                : [];        
        }
    }

    public function stopPolling()
    {
        $this->stopPolling = true;

    }

    public function updatingSelectedDate()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.staff-calendar', [
            'currentMonthYear' => $this->currentDate->format('F Y'),
            'approvedAppointments' => Appointment::whereDate('appointment_date', $this->selectedDate)
                ->where('status', 'approved')
                ->orderBy('appointment_date', 'asc')
                ->paginate(2)
        ]);
    }

    // private function getApprovedAppointments()
    // {
    //     return Appointment::whereDate('appointment_date', $this->selectedDate)
    //         ->where('status', 'approved')
    //         ->orderBy('appointment_date', 'asc')
    //         ->paginate(2);
    // }
}