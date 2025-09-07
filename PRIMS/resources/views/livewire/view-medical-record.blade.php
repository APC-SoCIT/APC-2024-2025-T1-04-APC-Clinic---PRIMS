<div>
    <div class="bg-white rounded-md shadow-md mt-5 p-6">

        <!-- Personal Information -->
        <div class="bg-prims-yellow-1 rounded-lg p-4">
            <h2 class="text-lg font-semibold">Personal Information</h2>
        </div>

        <div class="grid grid-cols-4 gap-4 mt-4 items-center">
            <div>
                <label class="font-bold text-lg">ID Number</label>
                <input type="text" value="{{ $record->apc_id_number }}" class="border p-2 rounded w-full bg-gray-100" readonly>
            </div>
            <div>
                <label class="font-bold text-lg">First Name</label>
                <input type="text" value="{{ $record->first_name }}" class="border p-2 rounded w-full bg-gray-100" readonly>
            </div>
            <div>
                <label class="font-bold text-lg">Middle Initial</label>
                <input type="text" value="{{ $record->mi }}" class="border p-2 rounded w-full bg-gray-100" readonly>
            </div>
            <div>
                <label class="font-bold text-lg">Last Name</label>
                <input type="text" value="{{ $record->last_name }}" class="border p-2 rounded w-full bg-gray-100" readonly>
            </div>
            <div>
                <label class="font-bold text-lg">Gender</label>
                <input type="text" value="{{ $record->gender }}" class="border p-2 rounded w-full bg-gray-100" readonly>
            </div>
            <div>
                <label class="font-bold text-lg">Age</label>
                <input type="text" value="{{ $record->age }}" class="border p-2 rounded w-full bg-gray-100" readonly>
            </div>
            <div>
                <label class="font-bold text-lg">Date of Birth</label>
                <input type="date" value="{{ $record->dob }}" class="border p-2 rounded w-full bg-gray-100" readonly>
            </div>
            <div>
                <label class="font-bold text-lg">Email</label>
                <input type="text" value="{{ $record->email }}" class="border p-2 rounded w-full bg-gray-100" readonly>
            </div>
            <div>
                <label class="font-bold text-lg">House/Unit No. & Street</label>
                <input type="text" value="{{ $record->street_number }}" class="border p-2 rounded w-full bg-gray-100" readonly>
            </div>
            <div>
                <label class="font-bold text-lg">Barangay</label>
                <input type="text" value="{{ $record->barangay }}" class="border p-2 rounded w-full bg-gray-100" readonly>
            </div>
            <div>
                <label class="font-bold text-lg">City/Municipality</label>
                <input type="text" value="{{ $record->city }}" class="border p-2 rounded w-full bg-gray-100" readonly>
            </div>
            <div>
                <label class="font-bold text-lg">Province</label>
                <input type="text" value="{{ $record->province }}" class="border p-2 rounded w-full bg-gray-100" readonly>
            </div>
            <div>
                <label class="font-bold text-lg">ZIP Code</label>
                <input type="text" value="{{ $record->zip_code }}" class="border p-2 rounded w-full bg-gray-100" readonly>
            </div>
            <div>
                <label class="font-bold text-lg">Country</label>
                <input type="text" value="{{ $record->country }}" class="border p-2 rounded w-full bg-gray-100" readonly>
            </div>
            <div>
                <label class="font-bold text-lg">Contact Number</label>
                <input type="text" value="{{ $record->contact_number }}" class="border p-2 rounded w-full bg-gray-100" readonly>
            </div>
            <div>
                <label class="font-bold text-lg">Nationality</label>
                <input type="text" value="{{ $record->nationality }}" class="border p-2 rounded w-full bg-gray-100" readonly>
            </div>
        </div>

        <!-- Medical Concern -->
        <div class="mt-6 bg-prims-yellow-1 rounded-lg p-4">
            <h2 class="text-lg font-semibold">Medical Concern</h2>
        </div>

        <div class="mt-4">
            <label class="text-lg font-medium">Reason for visit</label>
            <textarea class="w-full border p-2 rounded mb-5 bg-gray-100" readonly>{{ $record->reason }}</textarea>

            <label class="text-lg font-medium">Description of symptoms</label>
            <textarea class="w-full border p-2 rounded mb-5 bg-gray-100" readonly>{{ $record->description }}</textarea>
        </div>

        <!-- Medical History -->
        <div class="mb-6 bg-prims-yellow-1 rounded-lg p-4">
            <h3 class="text-lg font-semibold">Medical History</h3>
        </div>

        <!-- Past Medical History -->
        <div class="mb-3 bg-gray-300 rounded-lg p-1 flex justify-center">
            <h3 class="text-md font-semibold">A. Past Medical History</h3>
        </div>

        <label class="text-lg font-medium">Allergies</label>
            <textarea class="w-full border p-2 rounded bg-gray-100" readonly>{{ $record->allergies }}</textarea>

        <div class="m-4 text-md grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($past_medical_history as $key => $value)
                <div class="flex flex-col my-2 justify-between">
                    <label class="text-lg font-semibold">{{ $key }}</label>

                    @if ($key === 'Hepatitis')
                        <div class="flex space-x-3">
                            @foreach(['A', 'B', 'C', 'D', 'None'] as $type)
                                <label class="flex items-center">
                                    <input type="radio" name="past_medical_history[{{ $key }}]" value="{{ $type }}" 
                                        {{ $value === $type ? 'checked' : '' }} disabled>
                                    <span class="ml-2">{{ $type }}</span>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <div class="flex space-x-2">
                            <label class="flex items-center">
                                <input type="radio" name="past_medical_history[{{ $key }}]" value="Yes" 
                                    {{ $value === 'Yes' ? 'checked' : '' }} disabled>
                                <span class="ml-2">Yes</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="past_medical_history[{{ $key }}]" value="No" 
                                    {{ $value === 'No' ? 'checked' : '' }} disabled>
                                <span class="ml-2">No</span>
                            </label>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Family History -->
        <div class="mb-3 bg-gray-300 rounded-lg p-1 flex justify-center">
            <h3 class="text-md font-semibold">B. Family History</h3>
        </div>

        <div class="m-4 grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($family_history as $key => $value)
                <div class="flex flex-col my-2 justify-between">
                    <label class="text-lg font-semibold">{{ $key }}</label>
                    <div class="flex space-x-4">
                        <label class="flex items-center">
                            <input type="radio" name="past_medical_history[{{ $key }}]" value="Yes" 
                                {{ $value === 'Yes' ? 'checked' : '' }} disabled>
                            <span class="ml-2">Yes</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="past_medical_history[{{ $key }}]" value="No" 
                                {{ $value === 'No' ? 'checked' : '' }} disabled>
                            <span class="ml-2">No</span>
                        </label>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Personal & Social History -->
        <div class="mb-3 bg-gray-300 rounded-lg p-1 flex justify-center">
            <h3 class="text-md font-semibold">C. Personal & Social History</h3>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 m-4">
            <!-- Smoke -->
            <div class="col-span-2 flex flex-col">
                <label class="text-lg font-semibold">Smoke</label>
                <div class="flex items-center gap-4 text-md">
                    <span>Sticks/day: <strong>{{ $social_history['sticks_per_day'] ?? 'N/A' }}</strong></span>
                    <span>Packs/year: <strong>{{ $social_history['packs_per_year'] ?? 'N/A' }}</strong></span>
                </div>
            </div>

            <!-- Alcohol Consumption -->
            <div class="flex flex-col">
                <label class="text-lg font-semibold">Alcohol Consumption</label>
                <p class="border border-gray-500 rounded p-1 w-[22rem] bg-gray-100">
                    {{ $social_history['alcohol'] ?? 'N/A' }}
                </p>
            </div>

            <!-- Medications -->
            <div class="col-span-2 flex flex-col">
                <label class="text-lg font-semibold">Medications</label>
                <p class="border border-gray-500 rounded-md p-1 w-[22rem] bg-gray-100">
                    {{ $social_history['medications'] ?? 'None' }}
                </p>
            </div>

            <!-- Other Social History Fields (e.g., Vape) -->
            @foreach ($social_history as $key => $value)
                @if (in_array($key, ['Vape']))
                    <div class="flex flex-col">
                        <span class="text-lg font-bold">{{ ucfirst($key) }}</span>
                        <div class="flex gap-4 mt-1">
                            <label class="flex items-center space-x-1">
                                <input type="radio" value="Yes" {{ $value === 'Yes' ? 'checked' : '' }} disabled>
                                <span>Yes</span>
                            </label>
                            <label class="flex items-center space-x-1">
                                <input type="radio" value="No" {{ $value === 'No' ? 'checked' : '' }} disabled>
                                <span>No</span>
                            </label>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- OB-GYNE History -->
        <div class="mb-3 bg-gray-300 rounded-lg p-1 flex justify-center">
            <h3 class="text-md font-semibold">D. OB-GYNE History</h3>
        </div>

        <div class="text-md grid grid-cols-3 gap-x-4 gap-y-3">
            @foreach ($obgyne_history as $key => $value)
                <div class="flex flex-col my-2">
                    <label class="text-lg font-semibold">{{ $key }}</label>
                    <input type="text" value="{{ $value }}" class="border rounded-md p-1 w-full" readonly>
                </div>
            @endforeach
        </div>

        <!-- Hospitalization -->
        <div class="mb-3 bg-gray-300 rounded-lg p-1 flex justify-center">
            <h3 class="text-md font-semibold">E. Hospitalization</h3>
        </div>

        <div class="mt-4">
            <label class="text-lg font-medium">Hospitalization</label>
            <textarea class="w-full border p-2 rounded mb-5 bg-gray-100" readonly>{{ $record->hospitalization }}</textarea>
        </div>

        <!-- Operation -->
        <div class="mb-3 bg-gray-300 rounded-lg p-1 flex justify-center">
            <h3 class="text-md font-semibold">F. Operation</h3>
        </div>

        <div class="mt-4">
            <label class="text-lg font-medium">Operation</label>
            <textarea class="w-full border p-2 rounded mb-5 bg-gray-100" readonly>{{ $record->operation }}</textarea>
        </div>

        <!-- Immunizations -->
        <div class="mb-3 bg-gray-300 rounded-lg p-1 flex justify-center">
            <h3 class="text-md font-semibold">G. Immunizations</h3>
        </div>

        <div class="m-4 text-md grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach ($immunizations as $key => $value)
                <div class="flex flex-col my-2">
                    <span class="font-medium text-lg">{{ $key }}</span>

                    @if (in_array($key, ['COVID-19 1st', 'COVID-19 2nd', 'Booster 1', 'Booster 2']))
                        <input type="text" 
                            value="{{ $value }}" 
                            class="border rounded px-2 py-1 mt-1 w-full bg-gray-100" 
                            readonly />
                    @else
                        <div class="flex gap-4 mt-1">
                            <label class="flex items-center space-x-1">
                                <input type="radio" 
                                    value="Yes" 
                                    {{ $value === 'Yes' ? 'checked' : '' }} 
                                    disabled>
                                <span>Yes</span>
                            </label>
                            <label class="flex items-center space-x-1">
                                <input type="radio" 
                                    value="No" 
                                    {{ $value === 'No' ? 'checked' : '' }} 
                                    disabled>
                                <span>No</span>
                            </label>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Physical Examination -->
        <div class="mt-6 bg-prims-yellow-1 rounded-lg p-4">
            <h3 class="text-lg font-semibold">Physical Examination</h3>
        </div>

        <div class="flex justify-center mb-6 mt-4">
            <table class="table-auto w-[80%] border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border px-4 py-2">Section</th>
                        <th class="border px-4 py-2">Normal</th>
                        <th class="border px-4 py-2">Findings</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($physical_exams as $exam)
                        <tr>
                            <td class="border px-4 py-2 font-medium">{{ $exam->section }}</td>
                            <td class="border px-4 py-2 text-center">
                                <input type="checkbox" {{ $exam->normal ? 'checked' : '' }} disabled>
                            </td>
                            <td class="border px-4 py-2">
                                <input type="text" 
                                    value="{{ $exam->findings ?? 'N/A' }}" 
                                    class="w-full border rounded px-2 py-1 h-10 bg-gray-100" 
                                    readonly>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="border px-4 py-2 text-center text-gray-500">
                                No physical examination data available
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>


        <!-- Diagnosis -->
        <div class="mt-6 bg-prims-yellow-1 rounded-lg p-4">
            <h3 class="text-lg font-semibold">Diagnosis</h3>
        </div>

        <div>
            @forelse ($diagnoses as $diag)
                <div class="my-4">
                    <label class="text-lg font-medium">Diagnosis</label>
                    <input type="text" 
                        value="{{ $diag->diagnosis }}" 
                        class="border p-2 rounded w-full bg-gray-100 mb-2" 
                        readonly>

                    <label class="text-lg font-medium">Notes</label>
                    <textarea class="w-full border p-2 rounded bg-gray-100" readonly>{{ $diag->diagnosis_notes ?? 'No additional notes' }}</textarea>
                </div>
            @empty
                <p class="text-gray-500 italic">No diagnosis data available</p>
            @endforelse
        </div>

        <!-- Prescription -->
        <div class="bg-prims-yellow-1 rounded-lg p-4">
            <h3 class="text-lg font-semibold">Prescription</h3>
        </div>

        <div class="mt-4">
            <label class="text-lg font-medium">Prescription</label>
            <textarea class="w-full border p-2 rounded bg-gray-100" readonly>{{ $record->prescription ?? 'No prescription available' }}</textarea>
        </div>

        <div class="mt-6 flex justify-end">
            <a href="{{ route('medical-records') }}">
                <button class="px-4 py-2 bg-prims-azure-500 text-white rounded-lg hover:bg-prims-azure-100">
                    Back
                </button>
            </a>
        </div>
    </div>
</div>
