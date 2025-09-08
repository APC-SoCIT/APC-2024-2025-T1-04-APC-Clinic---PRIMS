<div class="pb-5">
    <div class="max-w-7xl mx-auto bg-white rounded-md shadow-md mt-5 p-6">

        <!-- Personal Information -->
        <div class="bg-prims-yellow-1 rounded-lg p-4">
            <h2 class="text-lg font-semibold">Personal Information</h2>
        </div>
        <form wire:submit.prevent="submit">
        <div class="grid grid-cols-4 gap-4 my-4">
            <div>
                <label class="text-lg">ID Number</label>
                <input type="text" wire:model.lazy="apc_id_number" wire:change="searchPatient" class="border p-2 rounded w-full" placeholder="Enter an ID number">
            </div>
            <div>
                <label class="text-lg">First Name</label>
                <input type="text" wire:model="first_name" class="border p-2 rounded w-full bg-gray-200" readonly>
                <input type="hidden" wire:model="first_name">
            </div>
            <div>
                <label class="text-lg">Middle Initial</label>
                <input type="text" wire:model="mi" class="border p-2 rounded w-full bg-gray-200" readonly>
                <input type="hidden" wire:model="mi">
            </div>
            <div>
                <label class="text-lg">Last Name</label>
                <input type="text" wire:model="last_name" class="border p-2 rounded w-full bg-gray-200" readonly>
                <input type="hidden" wire:model="last_name">
            </div>
            <div>
                <label class="text-lg">Gender</label>
                <input type="text" wire:model="gender" class="border p-2 rounded w-full col-span-2 bg-gray-200" readonly>
                <input type="hidden" wire:model="gender"> 
            </div>
            <div>
                <label class="text-lg">Age</label>
                <input type="text" wire:model="age" class="border p-2 rounded w-full col-span-2 bg-gray-200" readonly>
                <input type="hidden" wire:model="age">
            </div>
            <div>
                <label class="text-lg">Date of Birth</label>
                <input type="text" wire:model="dob" class="border p-2 rounded w-full col-span-2 bg-gray-200" readonly>
                <input type="hidden" wire:model="dob"> 
            </div>
            <div>
                <label class="text-lg">Email</label>
                <input type="text" wire:model="email" class="border p-2 rounded w-full col-span-2 bg-gray-200" readonly>
                <input type="hidden" wire:model="email">
            </div>
            <div>
                <label class="text-lg">House/Unit No. & Street</label>
                <input type="text" wire:model="street_number" class="border p-2 rounded w-full col-span-2 bg-gray-200" readonly>
                <input type="hidden" wire:model="first_name"> 
            </div>
            <div>
                <label class="text-lg">Barangay</label>
                <input type="text" wire:model="barangay" class="border p-2 rounded w-full col-span-2 bg-gray-200" readonly>
                <input type="hidden" wire:model="first_name"> 
            </div>
            <div>
                <label class="text-lg">City/Municipality</label>
                <input type="text" wire:model="city" class="border p-2 rounded w-full col-span-2 bg-gray-200" readonly>
                <input type="hidden" wire:model="first_name">
            </div>        
            <div>
                <label class="text-lg">Province</label>
                <input type="text" wire:model="province" class="border p-2 rounded w-full col-span-2 bg-gray-200" readonly>
                <input type="hidden" wire:model="first_name">
            </div>
            <div>
                <label class="text-lg">ZIP Code</label>
                <input type="text" wire:model="zip_code" class="border p-2 rounded w-full col-span-2 bg-gray-200" readonly>
                <input type="hidden" wire:model="first_name">
            </div>
            <div>
                <label class="text-lg">Country</label>
                <input type="text" wire:model="country" class="border p-2 rounded w-full col-span-2 bg-gray-200" readonly>
                <input type="hidden" wire:model="first_name">
            </div>
            <div>
                <label class="text-lg">Contact Number</label>
                <input type="text" wire:model="contact_number" class="border p-2 rounded w-full col-span-2 bg-gray-200" readonly>
                <input type="hidden" wire:model="contact_number">
            </div>
            <div>
                <label class="text-lg">Nationality</label>
                <input type="text" wire:model="nationality" class="border p-2 rounded w-full col-span-2 bg-gray-200" readonly>
                <input type="hidden" wire:model="nationality">
            </div>
        </div>
        <!-- Dental Examination -->
        <div class="bg-prims-yellow-1 rounded-lg p-4 mt-6">
            <h2 class="text-lg font-semibold">Dental Examination</h2>
        </div>

        <!-- Tooth Number Chart -->
        <div class="mt-4">
            <h3 class="text-md font-semibold mb-2">Select Tooth Numbers</h3>

            <!-- Upper Teeth -->
            <div class="flex justify-center space-x-3">
                @foreach (['8','7','6','5','4','3','2','1'] as $tooth)
                    <button type="button" wire:click="openModal('{{ $tooth }}', 'upper')"
                        class="w-12 h-10 rounded-full border border-gray-400 transition-colors duration-300
                        {{ isset($teeth['upper'][$tooth]) ? 'bg-blue-600 text-white' : 'bg-white hover:bg-gray-200' }}">
                        {{ $tooth }}
                    </button>
                @endforeach

                <span class="mx-3 font-bold">|</span>

                @foreach (['1','2','3','4','5','6','7','8'] as $tooth)
                    <button type="button" wire:click="openModal('{{ $tooth }}', 'upper')"
                        class="w-10 h-10 rounded-full border border-gray-400 transition-colors duration-300
                        {{ isset($teeth['upper'][$tooth]) ? 'bg-blue-600 text-white' : 'bg-white hover:bg-gray-200' }}">
                        {{ $tooth }}
                    </button>
                @endforeach
            </div>

            <!-- Lower Teeth -->
            <div class="flex justify-center space-x-3 mt-3">
                @foreach (['8','7','6','5','4','3','2','1'] as $tooth)
                    <button type="button" wire:click="openModal('{{ $tooth }}', 'lower')"
                        class="w-10 h-10 rounded-full border border-gray-400 transition-colors duration-300
                        {{ isset($teeth['lower'][$tooth]) ? 'bg-green-600 text-white' : 'bg-white hover:bg-gray-200' }}">
                        {{ $tooth }}
                    </button>
                @endforeach

                <span class="mx-3 font-bold">|</span>

                @foreach (['1','2','3','4','5','6','7','8'] as $tooth)
                    <button type="button" wire:click="openModal('{{ $tooth }}', 'lower')"
                        class="w-10 h-10 rounded-full border border-gray-400 transition-colors duration-300
                        {{ isset($teeth['lower'][$tooth]) ? 'bg-green-600 text-white' : 'bg-white hover:bg-gray-200' }}">
                        {{ $tooth }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Pop-up Modal -->
        @if($showModal)
            <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 transition-opacity duration-300">
                <div class="bg-white rounded-lg shadow-lg p-6 w-96 transform transition-transform duration-300 scale-95 hover:scale-100">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">
                        Tooth <span class="text-blue-600">{{ $selectedTooth }}</span> ({{ ucfirst($selectedJaw) }})
                    </h3>

                    <div class="grid grid-cols-3 gap-3">
                        @foreach(['C', 'M', 'E', 'LC', 'CR', 'UE'] as $status)
                            @php
                                $colors = [
                                    'C' => 'bg-red-500 hover:bg-red-600',
                                    'M' => 'bg-gray-500 hover:bg-gray-600',
                                    'E' => 'bg-yellow-500 hover:bg-yellow-600',
                                    'LC' => 'bg-orange-500 hover:bg-orange-600',
                                    'CR' => 'bg-purple-500 hover:bg-purple-600',
                                    'UE' => 'bg-blue-500 hover:bg-blue-600'
                                ];
                            @endphp
                            <button wire:click="selectToothCondition('{{ $status }}')"
                                class="py-2 px-3 rounded text-white transition-all duration-200 shadow-sm
                                {{ isset($teeth[$selectedJaw][$selectedTooth]) && $teeth[$selectedJaw][$selectedTooth] === $status
                                    ? $colors[$status].' scale-105'
                                    : 'bg-gray-200 text-gray-800 hover:scale-105 '.$colors[$status] }}">
                                {{ $status }}
                            </button>
                        @endforeach
                    </div>

                    <div class="flex justify-end mt-5">
                        <button wire:click="closeModal"
                            class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 transition-colors duration-300">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Submit -->
        <div class="mt-6 flex justify-end">
            <button type="submit"
                class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition-colors duration-300">
                Submit Dental Record
            </button>
        </div>
    </div>
</div>