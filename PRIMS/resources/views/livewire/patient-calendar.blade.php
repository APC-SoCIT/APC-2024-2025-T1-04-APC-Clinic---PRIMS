<div>
    <div class="grid grid-cols-7 grid-rows-4 gap-4 justify-center mt-5">
        
        <!-- Doctor Information Section -->
        <div class="col-span-2 row-span-12 bg-white rounded-md shadow-md text-center">
            <div class="flex justify-center mt-6 bg-prims-yellow-1">
                <h1 class="text-xl font-bold">Choose a Doctor</h1>
            </div>

            @if(count($doctors) > 0)
                <div class="mt-3">
                    @foreach($doctors as $doctor)
                        @php
                            $isAvailable = empty($selectedDate) || in_array($doctor->id, $availableDoctors);
                        @endphp
                        <div class="flex items-center justify-center p-2 rounded 
                            {{ $isAvailable ? 'cursor-pointer hover:scale-105' : 'opacity-50 cursor-not-allowed' }}" 
                            @if($isAvailable) wire:click="selectDoctor({{ $doctor->id }})" @endif>
                            
                            <div class="w-4/5 border rounded shadow-sm p-3 transition-all duration-150 transform mb-3 
                                {{ $selectedDoctor && $selectedDoctor->id == $doctor->id ? 'bg-prims-azure-100 border-2 border-prims-yellow-5 text-white' : ''}}">
                                
                                <img src="{{ asset($doctor->clinic_staff_image) }}" alt="Profile Picture" class="rounded-full w-18 h-18 mx-auto">
                                <p class="font-semibold pt-3">Dr. {{ $doctor->clinic_staff_fname }} {{ $doctor->clinic_staff_lname }}</p>
                                <p class="text-sm pb-3">{{ $doctor->email }}</p>
                                <hr class="w-3/4 mx-auto">
                                <p class="text-sm pt-3">{{ $doctor->clinic_staff_desc}}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center mt-3">No doctors available.</p>
            @endif
        </div>

        <!-- Calendar Section -->
        <div class="col-span-5 row-span-12 bg-white rounded-md shadow-md">
            <div class="flex justify-center mt-6 bg-prims-yellow-1">
                <h1 class="text-xl font-bold">Choose a Date</h1>
            </div>
            <div class="flex gap-4 justify-center mx-1 my-4 font-bold text-sub-header-1">
                <select wire:model="month" wire:change="generateCalendar" class="border border-gray-300 rounded-lg px-7 py-2">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}">{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                    @endforeach
                </select>
                <select wire:model="year" wire:change="generateCalendar" class="border border-gray-300 rounded-lg px-7 py-2">
                    @foreach(range(now()->year - 5, now()->year + 5) as $y)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Days of the Week -->
            <div class="grid grid-cols-7 gap-4 text-center font-bold text-gray-700 mb-4 mx-3">
                <div class="text-red-500">Sun</div>
                <div>Mon</div>
                <div>Tues</div>
                <div>Wed</div>
                <div>Thu</div>
                <div>Fri</div>
                <div>Sat</div>
            </div>

            <!-- Calendar Days -->
            <div class="grid grid-cols-7 gap-2 text-center mb-4 mx-3">
            @foreach($daysInMonth as $day)
                @if($day)
                    @php
                        $isPastDate = \Carbon\Carbon::parse($day['date'])->lt(\Carbon\Carbon::today('Asia/Manila'));
                        $isSunday = \Carbon\Carbon::parse($day['date'])->isSunday();
                        $isAvailable = $day['isAvailable'] ?? false;
                        $isFullyBooked = $day['isFullyBooked'] ?? false;
                    @endphp

                    <div class="p-2 rounded-lg 
                        {{ $day['isToday'] ? 'text-blue-600' : '' }} 
                        {{ $selectedDate === $day['date'] ? 'border-2 border-prims-yellow-5 bg-prims-azure-100 text-white' : '' }} 
                        {{ ($isPastDate || $isSunday || !$isAvailable) ? 'text-gray-400 cursor-not-allowed' : 'cursor-pointer hover:bg-prims-yellow-1' }}
                        {{ $isFullyBooked && !$isPastDate ? 'bg-[#ff8a8a] text-black' : ($isAvailable && !$isPastDate ? 'bg-[#8aff8a] text-black' : '') }} "
                        @if($isAvailable) wire:click="selectDate({{ $day['day'] }})" @endif>
                        {{ $day['day'] }}
                    </div>
                @else
                    <div></div>
                @endif
            @endforeach
            </div>

        <!-- Choose a Time -->
        <div class="col-span-5 row-span-4 bg-white shadow-md">
            <div class="flex justify-center mt-6 bg-prims-yellow-1">
                <h1 class="text-xl font-bold">Choose a Time</h1>
            </div>
            <div class="grid grid-cols-5 gap-4 px-4 py-4 font-bold text-center">
            @foreach($allTimes as $time)
                @php
                    $isSelectionMade = $selectedDoctor && $selectedDate;
                    $slot = collect($availableTimes)->firstWhere('time', $time);
                    $isAvailable = $slot && $slot['isAvailable'];
                @endphp

                <button 
                    class="p-2 rounded-lg transition-all duration-150
                    @if(!$isSelectionMade) 
                        text-gray-400 cursor-not-allowed
                    @elseif($isAvailable) 
                        {{ $selectedTime === $time ? 'border-2 border-prims-yellow-5 bg-prims-azure-100 text-white' : 'text-black hover:bg-prims-yellow-1' }}
                    @else 
                        text-gray-400 cursor-not-allowed
                    @endif" 
                    @if($isSelectionMade && $isAvailable) wire:click="selectTime('{{ $time }}')" @endif>
                    {{ $time }}
                </button>
            @endforeach
            </div>
        </div>


        <!-- Submit -->
        <div class="col-span-5 row-span-4 bg-prims-yellow-1 shadow-md p-6">
            <h1 class="text-xl font-bold text-center mb-4">Enter Reason for Visit</h1>
            <textarea wire:model="reasonForVisit" id="reason" placeholder="Enter reason for visit" class="border border-gray-300 rounded-lg w-full p-2 mt-2"></textarea>

            <div class="mt-4 flex justify-center gap-4">
                <x-prims-sub-button1 wire:click="confirmAppointment">Submit</x-prims-sub-button1>
            </div>
        </div>

        @if($isConfirming)
            <div class="fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center z-50">
                <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm">
                    <h3 class="text-3xl font-bold pb-3 text-center">Are you sure?</h3>
                    <p class="text-center">Before submitting, is <strong><span class="text-red-500">{{ \Carbon\Carbon::parse($selectedDate)->format('M. d, Y') }}</span></strong> at <strong><span class="text-red-500">{{ $selectedTime }}</span></strong> the correct date and time for your appointment with <strong><span class="text-red-500">Dr. {{ $selectedDoctor->clinic_staff_fname }} {{ $selectedDoctor->clinic_staff_lname }}</span></strong>?</p>
                    <div class="mt-4 flex justify-center pb-3 gap-2">
                    @if($hasUpcomingAppointment)
                        <x-prims-sub-button1 class="opacity-50 cursor-not-allowed" disabled>
                            Yes
                        </x-prims-sub-button1>
                    @else
                        <x-prims-sub-button1 wire:click="submitAppointment">
                            Yes
                        </x-prims-sub-button1>
                    @endif
                        <button wire:click="resetSelection" class="px-4 text-black hover:text-prims-yellow-1">No</button>
                    </div>
                    @if($hasUpcomingAppointment)
                        <span class="text-sm text-red-500 text-center inline-block">You already have an <strong>upcoming</strong> or <strong>pending</strong> appointment.</span>
                    @endif
                </div>
            </div>
        @endif

        @if($showSuccessModal)
            <div class="fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center z-50">
                <div class="bg-white p-4 rounded-lg shadow-lg max-w-sm">
                    <h3 class="text-3xl font-bold pb-3 text-center">Thank you!</h3>
                    <p class="text-center">@php echo $successMessage; @endphp</p>
                    <div class="mt-4 flex justify-center">
                        <x-prims-sub-button1 wire:click="closeSuccessModal">OK</x-prims-sub-button1>
                    </div>
                </div>
            </div>
        @endif

        @if($showErrorModal)
            <div class="fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center z-50">
                <div class="bg-white p-4 rounded-lg shadow-lg max-w-sm">
                    <h3 class="text-3xl font-semibold pb-3 text-red-600 text-center">Oops...</h3>
                    <p>@php echo $errorMessage; @endphp</p>
                    <div class="mt-4 flex justify-center">
                        <x-prims-sub-button1 wire:click="closeErrorModal" >OK</x-prims-sub-button1>
                    </div>
                </div>
            </div>
        @endif

        @if($showBookingFeedbackModal)
            <div class="fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center z-50">
                <div class="bg-white p-4 rounded-lg shadow-lg max-w-sm">
                    <h3 class="text-xl font-semibold pb-3 text-black text-center">How satisfied are you with the booking process?</h3>
                    <div class="flex justify-center gap-2">
                        <button 
                            wire:click="$set('selectedEmoji', '{{ $selectedEmoji === 'sad' ? null : 'sad' }}')" 
                            class="px-4 py-2 rounded-lg hover:bg-gray-200 active:bg-gray-300 {{ $selectedEmoji === 'sad' ? 'bg-red-200' : '' }}">
                            <img src="{{ asset('img/sad.png') }}" class="w-12 h-12">
                        </button>

                        <button 
                            wire:click="$set('selectedEmoji', '{{ $selectedEmoji === 'flat' ? null : 'flat' }}')" 
                            class="px-4 py-2 rounded-lg hover:bg-gray-200 active:bg-gray-300 {{ $selectedEmoji === 'flat' ? 'bg-gray-200' : '' }}">
                            <img src="{{ asset('img/flat.png') }}" class="w-12 h-12">
                        </button>

                        <button 
                            wire:click="$set('selectedEmoji', '{{ $selectedEmoji === 'happy' ? null : 'happy' }}')" 
                            class="px-4 py-2 rounded-lg hover:bg-gray-200 active:bg-gray-300 {{ $selectedEmoji === 'happy' ? 'bg-green-200' : '' }}">
                            <img src="{{ asset('img/happy.png') }}" class="w-12 h-12">
                        </button>

                    </div>
                    @if($selectedEmoji)
                        <div>
                            <p class="text-sm text-gray-500 text-center mt-2">Add a quick comment to help us improve!</p>
                            <textarea wire:model="bookingFeedback" id="bookingFeedback" placeholder="Your feedback..." class=" text-sm border border-gray-300 rounded-lg w-full p-2 mt-2"></textarea>
                            <input id="anonymous" type="checkbox" name="anonymous" class="col-start-1 row-start-1 appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto" />
                            <label for="anonymous" class="text-sm text-gray-500">Keep me anonymous</label>
                        </div>
                    @endif
                    <div class="mt-4 flex justify-center gap-2">
                        <x-prims-sub-button1 wire:click="cancelBookingFeedback" class="bg-gray-200">Cancel</x-prims-sub-button1>
                        <x-prims-sub-button1 wire:click="submitBookingFeedback">Submit</x-prims-sub-button1>
                    </div>
                </div>
            </div>
        @endif
        
    </div>
</div>
