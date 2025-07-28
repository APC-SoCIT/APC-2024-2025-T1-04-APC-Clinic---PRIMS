@php
    // Sort all inventory by expiration date
    $allBatches = $inventory->supply->inventory->sortBy('expiration_date');

    // Get the current batch as the one with the earliest expiration date
    $currentBatch = $allBatches->first();

    if ($currentBatch) {
        // Calculate remaining stock for the current batch
        $remainingStock = $currentBatch->quantity_received - $currentBatch->dispensed->sum('quantity_dispensed');

        // If stock is zero or less, delete the batch and move to the next one
        if ($remainingStock <= 0) {
            $currentBatch->delete();
            $currentBatch = $allBatches->where('id', '!=', $currentBatch->id)->first();
        }
    }

    // Get remaining other batches
    $otherBatches = $allBatches->where('id', '!=', optional($currentBatch)->id);
@endphp

<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg mt-5">
    <div class="p-6 lg:p-8 gap-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700 flex flex-wrap justify-start">
        <div class="grid grid-cols-2 grid-rows-2 gap-2 w-full">
            <div>
                <p class="font-bold">Generic Name:</p>
                <p>:: {{ $inventory->supply->name }}</p>
            </div>
            <div>
                <p class="font-bold">Category:</p>
                <p>:: {{ $inventory->supply->category }}</p>
            </div>
            <div>
                <p class="font-bold">Brand Name:</p>
                <p>:: {{ $inventory->supply->brand }}</p>
            </div>
            <div>
                <p class="font-bold">Dosage Form & Strength:</p>
                <p>:: {{ $inventory->supply->dosage_form }} {{ $inventory->supply->dosage_strength }}</p>
            </div>
        </div>
    </div>

    <!-- CURRENT BATCH IN USE -->
    <div class="p-6 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700 flex flex-wrap justify-start">
        <div class="mx-auto">    
            <span class="text-lg font-bold uppercase">Current Batch In Use</span>
        </div>
        <table class="w-full bg-white border border-gray-200 rounded-lg shadow-lg">
            <thead class="bg-prims-yellow-1 text-prims-blue-500 ">
                <tr>                    
                    <th class="px-4 py-2">Generic Name</th>
                    <th class="px-4 py-2">Brand</th>
                    <th class="px-4 py-2">Category</th>
                    <th class="px-4 py-2">Dosage Form</th>
                    <th class="px-4 py-2">Strength</th>
                    <th class="px-4 py-2">Date Supplied</th>
                    <th class="px-4 py-2">Expiration Date</th>
                    <th class="px-4 py-2">Quantity Received</th>
                    <th class="px-4 py-2">Remaining Stock</th>
                </tr>
            </thead>
            <tbody>
                <tr class="text-center align-middle">
                    <td class="px-4 py-2">{{ $currentBatch->supply->name }}</td>
                    <td class="px-4 py-2">{{ $currentBatch->supply->brand }}</td>
                    <td class="px-4 py-2">{{ $currentBatch->supply->category }}</td>
                    <td class="px-4 py-2">{{ $currentBatch->supply->dosage_form }}</td>
                    <td class="px-4 py-2">{{ $currentBatch->supply->dosage_strength }}</td>
                    <td class="px-4 py-2">{{ $currentBatch->date_supplied }}</td>
                    <td class="relative group px-4 py-2 
                                    {{ now()->diffInDays($currentBatch->expiration_date, false) <= 14 && now()->diffInDays($currentBatch->expiration_date, false) >= 0 ? 'text-yellow-500 font-semibold' : '' }} 
                                    {{ \Carbon\Carbon::parse($currentBatch->expiration_date)->isPast() ? 'text-red-500 font-bold' : '' }}">

                                    {{ $currentBatch->expiration_date ?? 'N/A' }}

                                    @if (\Carbon\Carbon::parse($currentBatch->expiration_date)->isPast())
                                            <div class="absolute bottom-full mb-1 left-1/2 transform -translate-x-1/2 hidden group-hover:flex 
                                                        bg-black text-red-300 text-xs rounded px-2 py-1 whitespace-nowrap z-50">
                                                Expired - Need to dispose
                                            </div>
                                    @elseif (now()->diffInDays($currentBatch->expiration_date, false) <= 14 && now()->diffInDays($currentBatch->expiration_date, false) >= 0)
                                            <div class="absolute bottom-full mb-1 left-1/2 transform -translate-x-1/2 hidden group-hover:flex 
                                                        bg-black text-yellow-300 text-xs rounded px-2 py-1 whitespace-nowrap z-50">
                                                Near Expiry ({{ round(now()->diffInDays($currentBatch->expiration_date)) }} days left)
                                            </div>
                                    @endif
                                </td>
                    <td class="px-4 py-2">{{ $currentBatch->quantity_received }}</td>
                    <td class="px-4 py-2 font-semibold relative group
                    @if ($currentBatch->quantity_received - $currentBatch->dispensed->sum('quantity_dispensed') <= 25) text-red-500
                    @elseif ($currentBatch->quantity_received - $currentBatch->dispensed->sum('quantity_dispensed') <= 50) text-yellow-500
                    @endif">
                    {{ $currentBatch->quantity_received - $currentBatch->dispensed->sum('quantity_dispensed') }}
                </tr>
            </tbody>
        </table>
        <div class="pt-6 pr-6 flex justify-end space-x-4 w-full">
            <div>
                <!-- Dispose Button -->
                <x-button wire:click="openDisposeModal" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                    Dispose
                </x-button>

                @if ($showDisposeModal)
                    <div class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
                        <div class="bg-white p-6 rounded shadow-lg">
                            <h2 class="text-lg font-bold mb-4">Confirm Disposal</h2>
                            <p>Are you sure you want to dispose of this medicine? This action cannot be undone.</p>

                            <div class="mt-4 flex justify-end space-x-2">
                                <button wire:click="$set('showDisposeModal', false)" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                                <button wire:click="confirmDispose" class="bg-blue-600 text-white px-4 py-2 rounded">Confirm</button>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Dispense Button -->
                <x-button wire:click="openDispenseModal" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Dispense to Patient
                </x-button>

                @if ($showDispenseModal)
                    <div class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
                        <div class="bg-white p-6 rounded shadow-lg w-[25%]">
                            <h2 class="text-lg font-bold mb-4">Dispense Medicine</h2>

                            <label class="block mb-2">APC ID Number</label>
                            <input type="text" wire:model.live="patientId" class="w-full border p-2 rounded">
                            
                            <!-- Display patient details dynamically -->
                            <div class="mt-2">
                                <em>
                                @if ($selectedPatient)
                                    <p class="text-prims-azure-500 font-semibold">{{ $selectedPatient->first_name }} {{ $selectedPatient->middle_initial}}. {{ $selectedPatient->last_name }}</p>
                                    <p class="text-prims-azure-500">{{ $selectedPatient->email }}</p>
                                @else
                                    <p class="text-sm text-red-500">No patient with that ID</p>
                                @endif
                                </em>
                            </div>

                            <label class="block mb-2 mt-4">Amount to Dispense</label>
                            <input type="number" wire:model="amountDispensed" class="w-full border p-2 rounded">

                            <div class="mt-4 flex justify-end space-x-2">
                                <button wire:click="closeDispenseModal" class="bg-gray-500 text-white px-4 py-2 rounded">
                                    Cancel
                                </button>
                                <button wire:click="dispense" class="bg-blue-600 text-white px-4 py-2 rounded" 
                                    @if (!$selectedPatient) disabled @endif>
                                    Confirm
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- OTHER BATCH/ES IN STOCK -->
    <div class="p-6 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700 flex flex-wrap justify-start">
        <div class="mx-auto">    
            <span class="text-lg font-bold uppercase">Batch/es in Stock</span>
        </div>
        <table class="w-full bg-white border border-gray-200 rounded-lg shadow-lg">
            <thead class="bg-gray-200 text-prims-blue-500 ">
                <tr>                    
                    <th class="px-4 py-2">Generic Name</th>
                    <th class="px-4 py-2">Brand</th>
                    <th class="px-4 py-2">Category</th>
                    <th class="px-4 py-2">Dosage Form</th>
                    <th class="px-4 py-2">Strength</th>
                    <th class="px-4 py-2">Date Supplied</th>
                    <th class="px-4 py-2">Expiration Date</th>
                    <th class="px-4 py-2">Quantity Received</th>
                    <th class="px-4 py-2">Remaining Stock</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($otherBatches as $batch)
                    <tr class="text-center align-middle">
                        <td class="px-4 py-2">{{ $batch->supply->name }}</td>
                        <td class="px-4 py-2">{{ $batch->supply->brand }}</td>
                        <td class="px-4 py-2">{{ $batch->supply->category }}</td>
                        <td class="px-4 py-2">{{ $batch->supply->dosage_form }}</td>
                        <td class="px-4 py-2">{{ $batch->supply->dosage_strength }}</td>
                        <td class="px-4 py-2">{{ $batch->date_supplied }}</td>
                        <td class="px-4 py-2">{{ $batch->expiration_date }}</td>
                        <td class="px-4 py-2">{{ $batch->quantity_received }}</td>
                        <td class="px-4 py-2">{{ $batch->quantity_received - $batch->dispensed->sum('quantity_dispensed') }}</td>
                    </tr>
                @endforeach
                @if ($otherBatches->isEmpty())
                    <tr>
                        <td class="px-4 py-2 text-center" colspan="9"><em>No other batches in stock</em></td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>


@if (session('reload'))
    <script>
        window.location.reload();
    </script>
@endif
