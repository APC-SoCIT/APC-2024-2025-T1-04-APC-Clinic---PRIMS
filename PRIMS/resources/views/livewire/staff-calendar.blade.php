@php
    use Carbon\Carbon;
@endphp

<div class="flex gap-3 flex-col">
    <div wire:poll.5s="generateCalendar" class="flex gap-5 max-w-full">
        <!-- Left Side: Calendar & Pending Appointments -->
        <div class="w-3/5 bg-white shadow-lg rounded-lg p-4 mt-7">
            <div class="flex justify-center mb-4 pt-7">
                <h2 class="text-lg font-semibold">{{ $currentMonthYear }}</h2>
            </div>
            <div class="grid grid-cols-7 gap-1 text-center">
                <!-- Days of the Week -->
                @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                    <div class="font-bold text-gray-700">{{ $day }}</div>
                @endforeach
                
                <!-- Calendar Days -->
                @foreach($calendarDays as $day)
                    @php
                        $carbonDate = \Carbon\Carbon::parse($day['date']);
                        $isInCurrentMonth = $carbonDate->month == $currentDate->month;
                    @endphp

                    <div class="relative p-2 rounded-lg cursor-pointer hover:bg-gray-200 
                        {{ !$isInCurrentMonth ? 'text-gray-400' : '' }} 
                        {{ $day['isToday'] ? 'text-blue-600' : '' }} 
                        {{ $selectedDate === $day['date'] ? 'bg-prims-yellow-1' : '' }}"
                        wire:click="selectDate('{{ $day['date'] }}')">
                        {{ $day['day'] }}

                        <!-- Red dot for pending appointments -->
                        @if($day['hasPendingAppointments'])
                            <span class="absolute top-2 right-6 w-2 h-2 bg-red-500 rounded-full"></span>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="flex justify-center gap-3 mt-10 pb-3">
                <x-button wire:click="changeMonth(-1)" class="px-2 py-1 bg-gray-200 rounded">Previous</x-button>
                <x-button wire:click="changeMonth(1)" class="px-2 py-1 bg-gray-200 rounded">Next</x-button>
            </div>
        </div>

    <!-- Right Side: Approved Upcoming Appointments -->
    <div class="w-2/5 flex flex-col p-4 mt-7 overflow-y-auto">
        <h3 class="text-sm">Approved Appointments as of <span class="text-red-500">{{ \Carbon\Carbon::parse($selectedDate ?? now())->format('F j, Y') }}</span></h3>
        <!-- <div class="w-2/5 bg-white shadow-lg rounded-lg"> -->

            <div wire:poll.5s="loadAppointments" class="relative mt-2 flex flex-col items-center overflow-auto flex-grow min-h-0">
                @forelse($approvedAppointments as $index => $appointment)
                    <div 
                        class="w-4/5 p-3 border border-gray-200 rounded-lg bg-white shadow-md transition-all duration-150 transform 
                        mb-3 hover:scale-105"
                        wire:key="approved-{{ $appointment->id }}"
                    >
                        <p class="text-lg"><strong>Upcoming Appointment</strong></p>
                        <p class="text-3xl pb-3"><strong>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('h:i A') }}</strong></p>
                        <p>Patient: {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</p>
                        <p>Doctor: {{ $appointment->doctor->clinic_staff_fname }} {{ $appointment->doctor->clinic_staff_lname }}</p>

                        <x-prims-sub-button2 wire:click="startAppointment({{ $appointment->id }})" class="mt-3">Start Appointment</x-prims-sub-button2>
                        <x-prims-sub-button2 wire:click="confirmCancel({{ $appointment->id }})" class="mt-3 bg-white">Cancel Appointment</x-prims-sub-button2>
                    </div>
                @empty
                    <p class="text-gray-500 mt-3">No approved appointments yet.</p>
                @endforelse

                <div class="mt-2">
                    {{ $approvedAppointments->links() }}
                </div>

                @if($showCancelModal)
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center z-50">
                        <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm">
                            <h3 class="text-2xl font-bold pb-3 text-center">Cancel Appointment</h3>
                            <p class="text-center">Please enter a <span class="text-red-500"><strong>reason</strong></span> for cancelling this appointment.</p>
                            <textarea wire:model.defer="cancelReason" class="w-full p-2 border rounded mt-3" placeholder="Enter reason here..."></textarea>
                            
                            <div class="mt-4 flex justify-end gap-2">
                                <x-button 
                                wire:click="cancelAppointment"
                                x-bind:disabled="!$wire.cancelReason"
                                x-bind:class="!$wire.cancelReason ? 'opacity-50 cursor-not-allowed' : ''">
                                    Confirm
                                </x-button>
                                <button wire:click="$set('showCancelModal', false)" class="px-4 py-2 rounded">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                @endif

                @if($showCancelSuccessModal)
                <div class="fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center z-50">
                    <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm">
                        <h3 class="text-3xl font-bold pb-3 text-center">Appointment Cancelled</h3>
                        <p class="text-center">An <span class="text-red-500"><strong>email notification</strong></span> has been sent to the patient.</p>
                        <div class="mt-4 flex justify-end gap-2">
                            <x-button wire:click="$set('showCancelSuccessModal', false)">OK</x-button>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Display Selected Date Appointments -->
    @if($selectedDate && $appointments->isNotEmpty())
        <div class="mt-3 p-8 bg-white shadow-lg rounded-lg flex flex-row gap-5">
            <div class="w-full">
                <h2 class="text-lg font-semibold">Pending appointments for {{ \Carbon\Carbon::parse($selectedDate)->format('F j, Y') }}</h2>

                <!-- Loop through pending appointments -->
                @foreach($appointments as $appointment)
                    <div class="flex justify-between mt-3 p-2 border border-gray-200 rounded">
                        <div>
                            <!-- Display patient info if available -->
                            @if($appointment->patient)
                                <p><strong>Patient:</strong> {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</p>
                                <p><strong>Doctor:</strong> {{ $appointment->doctor->clinic_staff_fname }} {{ $appointment->doctor->clinic_staff_lname }}</p>
                                <p><strong>Reason:</strong> {{ $appointment->reason_for_visit }}</p>
                                <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('h:i A') }}</p>
                            @else
                                <p>Patient info unavailable</p>
                            @endif
                        </div>
                        <div class="flex flex-col gap-2">
                            <x-button wire:click="confirmApprove({{ $appointment->id }})" class="bg-green-500">Approve</x-button>
                            <x-button wire:click="confirmDecline({{ $appointment->id }})" class="bg-red-500">Decline</x-button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if($showApproveModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm">
                <h3 class="text-3xl font-bold pb-3 text-center">Appointment Approved</h3>
                <p class="text-center">An <span class="text-red-500"><strong>email notification</strong></span>  has been sent to the patient.</p>
                <div class="mt-4 flex justify-end gap-2">
                    <x-button wire:click="approveAppointment">OK</x-button>
                </div>
            </div>
        </div>
    @endif

    @if($showDeclineModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm">
                <h3 class="text-3xl font-bold pb-3 text-center">Decline Appointment</h3>
                <p class="text-center">Please enter a <span class="text-red-500"><strong>reason</strong></span> for declining this appointment.</p>
                <textarea wire:model.defer="declineReason" class="w-full p-2 border rounded mt-3" placeholder="Enter reason here..."></textarea>
                
                <div class="mt-4 flex justify-end gap-2">
                    <x-button 
                wire:click="declineAppointment"
                x-bind:disabled="!$wire.declineReason"
                x-bind:class="!$wire.declineReason ? 'opacity-50 cursor-not-allowed' : ''">Confirm</x-button>
                    <button wire:click="$set('showDeclineModal', false)" class="px-4 py-2 rounded">Cancel</button>
                </div>
            </div>
        </div>
    @endif

    @if($showDeclineSuccessModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm">
                <h3 class="text-3xl font-bold pb-3 text-center">Appointment Declined</h3>
                <p class="text-center">An <span class="text-red-500"><strong>email notification</strong></span> has been sent to the patient.</p>
                <div class="mt-4 flex justify-end gap-2">
                    <x-button wire:click="$set('showDeclineSuccessModal', false)">OK</x-button>
                </div>
            </div>
        </div>
    @endif

    <!-- for da management of the doctor's schedule -->
    <div class="mt-3 p-4 bg-white shadow-lg rounded-lg flex flex-row gap-5">
        <div class="w-2/5 flex flex-col p-4">
            <div class="flex flex-row justify-between items-center">
                <h2 class="text-lg font-semibold">Doctor's Schedule Management</h2>

                <!-- Edit Schedule Button -->
                <x-button wire:click="startEditingSchedule">
                    {{ $isEditingSchedule ? 'Save Schedule' : 'Edit Schedule' }}
                </x-button>
            </div>

            @if($isEditingSchedule && !$selectedDate)
                <p class="mt-3 text-sm text-red-500">Select a date to update the schedule.</p>
            @endif

            @if (session()->has('success'))
                <p class="mt-3 text-sm text-green-500">{{ session('success') }}</p>
            @endif

            <div class="mt-4">
                <label for="doctor" class="font-bold">Select Doctor:</label>
                <select wire:model="selectedDoctor" class="border rounded p-2 w-full {{ !$selectedDate || !$isEditingSchedule ? 'cursor-not-allowed' : '' }}" {{ !$selectedDate || !$isEditingSchedule ? 'disabled' : '' }}>
                    <option value="">-- Choose Doctor --</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}">{{ $doctor->clinic_staff_fname }} {{ $doctor->clinic_staff_lname }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="w-3/5 flex" @if(!$stopPolling) wire:poll.500ms="loadExistingSchedule" @endif>
            <!-- Time Slots -->
            <div class="mt-4">
                <h3 class="text-md font-semibold">Available Times</h3>
                <div class="grid grid-cols-7 gap-2 mt-2">
                    @foreach($timeSlots as $time)
                    <button wire:click="toggleTime('{{ $time }}')"
                        x-bind:class="{
                            'bg-prims-yellow-1 text-black': @json(in_array($time, (array) $selectedTimes)),
                            'bg-gray-200': @json(!in_array($time, (array) $selectedTimes)),
                            'cursor-not-allowed opacity-50': !($wire.selectedDoctor && $wire.selectedDate && $wire.isEditingSchedule),
                            'cursor-pointer hover:bg-white': $wire.selectedDoctor && $wire.selectedDate && $wire.isEditingSchedule
                        }"
                        class="p-2 rounded-lg">
                        {{ $time }}
                    </button>
                    @endforeach

                    @if($isEditingSchedule)
                        <button wire:click="cancelEditingSchedule" class="hover:bg-gray-300 text-red-500 px-4 py-2 rounded-lg">
                            Cancel
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
