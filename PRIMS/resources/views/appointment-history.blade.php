@section('title', 'PRIMS · Appointment History')

<x-app-layout>
    
    <div class="py-3">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <x-prims-sub-header>Appointment History</x-prims-sub-header>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg mt-5">
                    <div class="p-6 lg:p-8 gap-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700 flex flex-wrap justify-start">
                        <!-- left - picture -->
                        <div>
                            <img src="img/appointment-history/temp-id-pic.jpg" class="max-h-44 inline-block align-middle">
                        </div>
                        <!-- center - personal details -->
                        <div class="flex flex-col w-2/5">
                            <!-- name -->
                            <div class="text-3xl pb-5 mt-2">
                            <strong>{{ $patient->first_name }} {{ $patient->middle_initial }}. {{ $patient->last_name }}</strong>
                            </div>
                            <div class="flex justify-start gap-5 flex-wrap">
                                <div class="flex flex-col gap-3">
                                    <!-- student number -->
                                    <div class="text-sm flex flex-row align-center gap-2">
                                        <img src="img/appointment-history/id-number-icon.svg" class="max-h-20">
                                        <span>{{ $patient->apc_id_number }}</span>
                                    </div>
                                    <!-- contact number -->
                                    <div class="text-sm flex flex-row align-center gap-2">
                                        <img src="img/appointment-history/contact-number-icon.svg" class="max-h-20">
                                        <span>{{ $patient->contact_number }}</span>
                                    </div>
                                    
                                </div>
                                <div class="flex flex-col gap-3 max-w-[70%]">
                                    <!-- email -->
                                    <div class="text-sm flex flex-row align-center gap-2 break-all">
                                        <img src="img/appointment-history/email-icon.svg" class="max-h-20">
                                        <span>{{ $patient->email }}</span>
                                    </div>
                                    <!-- address -->
                                    <div class="text-sm flex flex-row align-center gap-2 break-words">
                                        <img src="img/appointment-history/gender-icon.svg" class="max-h-20">
                                        <span>{{ $patient->gender }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- right - appointments -->
                        <div>
                            <div class="flex flex-col gap-3 mt-4">
                                <span><strong>Upcoming Appointment:</strong></span>

                                @if($hasUpcomingAppointment)
                                    <span class="text-sm">
                                        {{ \Carbon\Carbon::parse($hasUpcomingAppointment->appointment_date)->format('F j, Y - h:i A') }} 
                                    </span>
                                @else
                                    <span class="text-sm text-gray-500">None</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto mt-5 sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6 lg:p-8 gap-6 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
                        <div class="justify-between flex items-end">
                            <span>Total: {{ $appointmentHistory->count() }}</span>
                            <div>
                                <x-prims-sub-button2>Request Medical Record</x-prims-sub-button2>
                            </div>
                        </div>
                        <div class="overflow-x-auto py-4">
                            <table class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg">
                                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                    <tr>
                                        <th class="text-left px-6 py-3 text-sm font-medium uppercase border-b dark:border-gray-600">#</th>
                                        <th class="text-left px-6 py-3 text-sm font-medium uppercase border-b dark:border-gray-600">ID Number</th>
                                        <th class="text-left px-6 py-3 text-sm font-medium uppercase border-b dark:border-gray-600">Date</th>
                                        <th class="text-left px-6 py-3 text-sm font-medium uppercase border-b dark:border-gray-600">Time</th>
                                        <th class="text-left px-6 py-3 text-sm font-medium uppercase border-b dark:border-gray-600">Doctor</th>
                                        <th class="text-left px-6 py-3 text-sm font-medium uppercase border-b dark:border-gray-600">Status</th>
                                        <th class="text-left px-6 py-3 text-sm font-medium uppercase border-b dark:border-gray-600">Status Updated By</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-700 dark:text-gray-300">
                                @php 
                                    $sortedAppointments = $appointmentHistory->sortByDesc('created_at'); 
                                    $count = $sortedAppointments->count();
                                @endphp
                                    @forelse ($sortedAppointments as $appointment)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <td class="px-6 py-4 border-b dark:border-gray-600">{{ $count-- }}</td>
                                            <td class="px-6 py-4 border-b dark:border-gray-600">{{ $appointment->patient->apc_id_number }}</td>
                                            <td class="px-6 py-4 border-b dark:border-gray-600">
                                                {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F j, Y') }}
                                            </td>
                                            <td class="px-6 py-4 border-b dark:border-gray-600">
                                                {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('h:i A') }}
                                            </td>
                                            <td class="px-6 py-4 border-b dark:border-gray-600">Dr. {{ $appointment->doctor->clinic_staff_fname }} {{ $appointment->doctor->clinic_staff_lname }}</td>
                                            <td class="px-6 py-4 border-b dark:border-gray-600">
                                                <span class="px-3 py-1 text-xs font-semibold rounded-xl
                                                    {{ $appointment->status == 'pending' ? 'bg-yellow-200 text-yellow-700' : '' }}
                                                    {{ $appointment->status == 'approved' ? 'bg-green-200 text-green-700' : '' }}
                                                    {{ $appointment->status == 'declined' ? 'bg-red-200 text-red-700' : '' }}
                                                    {{ $appointment->status == 'cancelled' ? 'bg-gray-200 text-gray-700' : '' }}">
                                                    {{ ucfirst($appointment->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 border-b dark:border-gray-600">
                                                {{ $appointment->updatedBy->clinic_staff_role ?? ''}} 
                                                {{ $appointment->updatedBy->clinic_staff_fname ?? ''}} 
                                                {{ $appointment->updatedBy->clinic_staff_lname ?? ''}}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">No appointment history available.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
</x-app-layout>