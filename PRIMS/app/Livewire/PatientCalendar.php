<?php
namespace App\Livewire;

use Livewire\Component;
use Carbon\Carbon;
use App\Models\Appointment;
use App\Models\ClinicStaff;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ClinicAppointmentNotif;
use App\Mail\PatientAppointmentNotif;


class PatientCalendar extends Component
{
    public $patient;
    public $selectedDate;
    public $selectedTime;
    public $month;
    public $year;
    public $daysInMonth = [];
    public $availableTimes = [
        '7:30 AM', '8:00 AM', '8:30 AM', '9:00 AM', '9:30 AM',
        '10:00 AM', '10:30 AM', '11:00 AM','11:30 AM', '12:00 PM', 
        '12:30 PM', '1:00 PM', '1:30 PM', '2:00 PM', '2:30 PM',
        '3:00 PM', '3:30 PM', '4:00 PM', '4:30 PM', '5:00 PM',
    ];
    public $reasonForVisit;
    public $doctors = [];
    public $selectedDoctor;
    public $isConfirming = false; 
    public $hasUpcomingAppointment = false;
    public $showErrorModal = false;
    public $errorMessage = '';
    public $showSuccessModal = false;
    public $successMessage = '';
    public $appointmentHistory = [];
    public $existingAppointment = false;

    public function mount()
    {
        if (!Auth::check()) {
            abort(403, 'Unauthorized');
        }

        $this->patient = Auth::user()->patient;

        $this->month = Carbon::now()->month;
        $this->year = Carbon::now()->year;
        $this->generateCalendar();

        $this->appointmentHistory = Appointment::where('patient_id', Auth::id())
            ->whereIn('status', ['pending', 'approved', 'declined', 'completed', 'cancelled'])
            ->with(['doctor', 'updatedBy']) // Load doctor and updater details
            ->orderBy('created_at', 'desc')
            ->get();

        // Check if the patient has an APPROVED or PENDING appointment
        $this->hasUpcomingAppointment = Appointment::where('patient_id', Auth::id())
        ->where(function ($query) {
            $query->where('status', 'pending')
                    ->orWhere('status', 'approved')
                    ->where('appointment_date', '>=', Carbon::now());
            })
        ->orderBy('appointment_date', 'asc')
        ->first();
        

        $this->doctors = ClinicStaff::where('clinic_staff_role', 'doctor')->get();
    }
    

    public function generateCalendar()
    {
        $this->daysInMonth = [];
        $today = Carbon::today('Asia/Manila');
        $firstDay = Carbon::createFromDate($this->year, $this->month, 1)->dayOfWeek;
        $totalDays = Carbon::createFromDate($this->year, $this->month, 1)->daysInMonth;

        for ($i = 0; $i < $firstDay; $i++) {
            $this->daysInMonth[] = null;
        }

        for ($day = 1; $day <= $totalDays; $day++) {
            $date = Carbon::createFromDate($this->year, $this->month, $day)->toDateString();
            $this->daysInMonth[] = [
                'day' => $day,
                'date' => $date,
                'isToday' => $date === $today->toDateString()
            ];
        }
    }

    public function selectDate($date)
    {
        if (!is_numeric($date)) {
            return;
        }

        $this->selectedDate = Carbon::createFromDate($this->year, $this->month, (int) $date)->toDateString();
    }

    public function selectTime($time)
    {
        $this->selectedTime = $time;
    }

    public function selectDoctor($doctorId)
    {
        $this->selectedDoctor = ClinicStaff::findOrFail($doctorId);
        $doctorId = $this->selectedDoctor->id;
    }

    public function confirmAppointment()
    {
        // Ensure all required fields are set
        if (!$this->selectedDate || !$this->selectedTime || !$this->reasonForVisit || !$this->selectedDoctor) {
            $this->errorMessage = 'Please select a <strong>date</strong>, <strong>time</strong>, <strong>doctor</strong>, and provide a <strong>reason</strong> to appoint a check up.';
            $this->showErrorModal = true; // Show the error modal
            return;
        }

        $this->isConfirming = true; // Show the confirmation modal
    }

    public function closeErrorModal()
    {
        $this->showErrorModal = false;
    }

    public function closeSuccessModal()
    {
        $this->showSuccessModal = false;
    }

    public function submitAppointment()
    {
        // Combine date and time into a valid DateTime format
        $appointmentDate = Carbon::createFromFormat('Y-m-d h:i A', $this->selectedDate . ' ' . $this->selectedTime);

        // Check if there is already an upcoming or pending appointment
        $existingAppointment = Appointment::where('patient_id', Auth::id())
        ->where(function ($query) {
            $query->whereIn('status', ['pending', 'approved']) 
                  ->where('appointment_date', '>=', Carbon::now()); 
        })
            ->exists();

        if ($existingAppointment) {
            session()->flash('error', 'You already have an upcoming or pending appointment.');
            return;
        }

        // Store the appointment if no existing appointment
        $appointment = Appointment::create([
            'appointment_date' => $appointmentDate,
            'status' => 'pending',
            'reason_for_visit' => $this->reasonForVisit,
            'patient_id' => Auth::id(), 
            'clinic_staff_id' => $this->selectedDoctor->id,
        ]);

        

        // Reset form fields
        $this->selectedDate = null;
        $this->selectedTime = null;
        $this->selectedDoctor = null;
        $this->reasonForVisit = null;
        $this->isConfirming = false;  
        $this->hasUpcomingAppointment = true;  
        $this->showSuccessModal = true;
        $this->successMessage = '<strong>Your appointment request has been received.</strong> An <span class="text-red-500"><strong>email notification</strong></span> has been sent to you, please wait for the clinic staff to approve your appointment.';
        session()->flash('success', 'Appointment successfully submitted!');

        Mail::to('prims.apc@gmail.com')->send(new ClinicAppointmentNotif($appointment));

        Mail::to(Auth::user()->email)->send(new PatientAppointmentNotif($appointment));
    }


    // Reset appointment selection if the patient cancels
    public function resetSelection()
    {
        $this->isConfirming = false;  // Hide the confirmation modal
        $this->selectedDate = null;
        $this->selectedTime = null;
        $this->selectedDoctor = null;
        $this->reasonForVisit = null;
    }

    public function render()
    {
        return view('livewire.patient-calendar', [
            'monthName' => Carbon::create()->month((int) $this->month)->format('F'),
            'currentYear' => $this->year,
            'doctors' => $this->doctors,
        ]);
    }
}
