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

        <!-- Medical Concern -->
        <div class="mt-6 bg-prims-yellow-1 rounded-lg p-4">
            <h2 class="text-lg font-semibold">Medical Concern</h2>
        </div>

        <div class="mt-6">
            <label class="block text-lg font-medium">Reason for Visit</label>
            <select wire:model="reason" class="w-full p-2 border rounded-md mb-6">
                <option value="">Select a reason</option>
                <option value="Consultation">Consultation</option>
                <option value="Fever">Fever</option>
                <option value="Emergency">Headache</option>
                <option value="Injury">Injury</option>
                <option value="Other">Other</option>
            </select>

            <label class="font-medium text-lg">Description of Symptoms</label>
            <textarea wire:model="description" class="w-full border p-2 rounded mb-5" placeholder="Description of symptoms..."></textarea>
        </div>

        <!-- Medical History -->
        <div class="mb-6 bg-prims-yellow-1 rounded-lg p-4">
            <h3 class="text-lg font-semibold">Medical History</h3>
        </div>

        <!-- Past Medical History -->
        <div class="mb-3 bg-gray-300 rounded-lg p-1 flex justify-center">
            <h3 class="text-md font-semibold">A. Past Medical History</h3>
        </div>

        <label class="font-medium text-lg mt-2">Allergies</label>
        <textarea wire:model="allergies" class="w-full border p-2 rounded" placeholder="Please specify..."></textarea>

        <div class="text-md grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach ($past_medical_history as $key => $value)
                @if ($key === 'Hepatitis')
                    <div class="flex flex-col my-2">
                        <span class="font-bold text-lg">{{ $key }}</span>
                        <div class="flex flex-wrap gap-4 mt-1">
                            @foreach (['A', 'B', 'C', 'D', 'None'] as $type)
                                <label class="flex items-center space-x-1">
                                    <input type="radio" wire:model="past_medical_history.Hepatitis" value="{{ $type }}" class="accent-black">
                                    <span>{{ $type }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="flex flex-col my-2">
                        <span class="font-medium text-lg">{{ $key }}</span>
                        <div class="flex gap-4 mt-1">
                            <label class="flex items-center space-x-1">
                                <input type="radio" wire:model="past_medical_history.{{ $key }}" value="Yes" class="accent-black">
                                <span>Yes</span>
                            </label>
                            <label class="flex items-center space-x-1">
                                <input type="radio" wire:model="past_medical_history.{{ $key }}" value="No" class="accent-black">
                                <span>No</span>
                            </label>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Family History -->
        <div class="my-3 bg-gray-300 rounded-lg p-1 flex justify-center">
            <h3 class="text-md font-semibold">B. Family History</h3>
        </div>

        <div class="text-md grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach ($family_history as $key => $value)
                <div class="flex flex-col my-2">
                    <span class="font-medium text-lg">{{ $key }}</span>
                    <div class="flex gap-4 mt-1">
                        <label class="flex items-center space-x-1">
                            <input type="radio" wire:model="family_history.{{ $key }}" value="Yes" class="accent-black">
                            <span>Yes</span>
                        </label>
                        <label class="flex items-center space-x-1">
                            <input type="radio" wire:model="family_history.{{ $key }}" value="No" class="accent-black">
                            <span>No</span>
                        </label>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Personal and Social History -->
        <div class="my-3 bg-gray-300 rounded-lg p-1 flex justify-center">
            <h3 class="text-md font-semibold">C. Personal & Social History</h3>
        </div>

        <div class="text-md grid grid-cols-2 md:grid-cols-4 gap-4">
            <!-- Smoke Input -->
            <div class="col-span-2 flex flex-col">
                <label class="text-lg font-medium">Smoke</label>
                <div class="flex items-center gap-4">
                    <label class="text-md">Sticks/day:</label>
                    <input type="number" wire:model="social_history.sticks_per_day" class="border rounded p-1 w-20" min="0">
                    <label class="text-md">Packs/year:</label>
                    <input type="number" wire:model="social_history.packs_per_year" class="border rounded p-1 w-20" min="0">
                </div>
            </div>

            <!-- Alcohol Consumption -->
            <div class="flex flex-col">
                <label class="font-medium text-lg">Alcohol Consumption</label>
                <select wire:model="social_history.alcohol" class="border rounded p-1 w-full">
                    <option value="">Select bottles per week</option>
                    <option value="N/A">N/A</option>
                    @for ($i = 1; $i <= 20; $i++)
                        <option value="{{ $i }}">{{ $i }} bottle(s) per week</option>
                    @endfor
                </select>
            </div>

            <!-- Medications -->
            <div class="col-span-2 flex flex-col">
                <label class="text-lg font-medium">Medications</label>
                <input type="text" wire:model="social_history.medications" class="border rounded-md p-1 w-[23.15rem]">
            </div>

            <!-- Loop Through Other Fields (Vape, etc.) -->
            @foreach ($social_history as $key => $value)
                @if (!in_array($key, ['Smoker', 'Alcohol', 'Medications', 'sticks_per_day', 'packs_per_year']))
                    <div class="flex flex-col my-2">
                        <span class="font-medium text-lg">{{ $key }}</span>
                        <div class="flex gap-4 mt-1">
                            <label class="flex items-center space-x-1">
                                <input type="radio" wire:model="social_history.{{ $key }}" value="Yes" class="accent-black">
                                <span>Yes</span>
                            </label>
                            <label class="flex items-center space-x-1">
                                <input type="radio" wire:model="social_history.{{ $key }}" value="No" class="accent-black">
                                <span>No</span>
                            </label>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- OB-GYNE History -->
        <div class="my-3 bg-gray-300 rounded-lg p-1 flex justify-center">
            <h3 class="text-md font-semibold">D. OB-GYNE History</h3>
        </div>

        <div class="text-md grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach ($obgyne_history as $key => $value)
                <div class="flex flex-col my-2">
                    <span class="font-medium text-lg">{{ $key }}</span>
                    <input type="text" wire:model="obgyne_history.{{ $key }}" class="border rounded-md p-1 w-[18rem]">
                </div>
            @endforeach
        </div>

        <!-- Hospitalization -->
        <div class="my-3 bg-gray-300 rounded-lg p-1 flex justify-center">
            <h3 class="text-md font-semibold">E. Hospitalization</h3>
        </div>

        <label class="font-medium text-lg mt-2">Hospitalization</label>
        <textarea wire:model="hospitalization" class="w-full border p-2 rounded" placeholder="Please specify..."></textarea>

        <!-- Operation -->
        <div class="my-3 bg-gray-300 rounded-lg p-1 flex justify-center">
            <h3 class="text-md font-semibold">F. Operation</h3>
        </div>

        <label class="font-medium text-lg mt-2">Operation</label>
        <textarea wire:model="operation" class="w-full border p-2 rounded" placeholder="Please specify..."></textarea>

        <!-- Immunizations -->
        <div class="my-3 bg-gray-300 rounded-lg p-1 flex justify-center">
            <h3 class="text-md font-semibold">G. Immunizations</h3>
        </div>

        <div class="text-md grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach ($immunizations as $key => $value)
                <div class="flex flex-col my-2">
                    <span class="font-medium text-lg">{{ $key }}</span>

                    @if (in_array($key, ['COVID-19 1st', 'COVID-19 2nd', 'Booster 1', 'Booster 2']))
                        <input type="text" 
                            wire:model="immunizations.{{ $key }}" 
                            placeholder="Enter date/details" 
                            class="border rounded px-2 py-1 mt-1 w-full" />
                    @else
                        <div class="flex gap-4 mt-1">
                            <label class="flex items-center space-x-1">
                                <input type="radio" wire:model="immunizations.{{ $key }}" value="Yes" class="accent-black">
                                <span>Yes</span>
                            </label>
                            <label class="flex items-center space-x-1">
                                <input type="radio" wire:model="immunizations.{{ $key }}" value="No" class="accent-black">
                                <span>No</span>
                            </label>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        
        <!-- Physical Examination -->
        <div class="my-6 bg-prims-yellow-1 rounded-lg p-4">
            <h3 class="text-lg font-semibold">Physical Examination</h3>
        </div>

        <div class="flex justify-center mb-6">
            <table class="table-auto w-[80%] border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border px-4 py-1">Section</th>
                        <th class="border px-4 py-1">Normal</th>
                        <th class="border px-4 py-1">Findings</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- General Appearance -->
                    <tr>
                        <td class="border px-4 py-1">General Appearance</td>
                        <td class="border px-4 py-1 text-center">
                            <input type="checkbox" name="general_appearance_normal">
                        </td>
                        <td class="border px-4 py-1">
                            <input type="text" name="general_appearance_findings" class="w-full border rounded px-2 py-1">
                        </td>
                    </tr>
                    <!-- Skin -->
                    <tr>
                        <td class="border px-4 py-1">Skin</td>
                        <td class="border px-4 py-1 text-center">
                            <input type="checkbox" name="skin_normal">
                        </td>
                        <td class="border px-4 py-1">
                            <input type="text" name="skin_findings" class="w-full border rounded px-2 py-1">
                        </td>
                    </tr>
                    <!-- Head and Scalp -->
                    <tr>
                        <td class="border px-4 py-1">Head and Scalp</td>
                        <td class="border px-4 py-1 text-center">
                            <input type="checkbox" name="head_and_scalp_normal">
                        </td>
                        <td class="border px-4 py-1">
                            <input type="text" name="head_and_scalp_findings" class="w-full border rounded px-2 py-1">
                        </td>
                    </tr>
                    <!-- Eyes (OD) -->
                    <tr>
                        <td class="border px-4 py-1">Eyes (OD)</td>
                        <td class="border px-4 py-1 text-center">
                            <input type="checkbox" name="eyes_od_normal">
                        </td>
                        <td class="border px-4 py-1">
                            <input type="text" name="eyes_od_findings" class="w-full border rounded px-2 py-1">
                        </td>
                    </tr>
                    <!-- Eyes (OS) -->
                    <tr>
                        <td class="border px-4 py-1">Eyes (OS)</td>
                        <td class="border px-4 py-1 text-center">
                            <input type="checkbox" name="eyes_os_normal">
                        </td>
                        <td class="border px-4 py-1">
                            <input type="text" name="eyes_os_findings" class="w-full border rounded px-2 py-1">
                        </td>
                    </tr>
                    <!-- Corrected (OD) -->
                    <tr>
                        <td class="border px-4 py-1">Corrected (OD)</td>
                        <td class="border px-4 py-1 text-center">
                            <input type="checkbox" name="corrected_od_normal">
                        </td>
                        <td class="border px-4 py-1">
                            <input type="text" name="corrected_od_findings" class="w-full border rounded px-2 py-1">
                        </td>
                    </tr>
                    <!-- Corrected (OS) -->
                    <tr>
                        <td class="border px-4 py-1">Corrected (OS)</td>
                        <td class="border px-4 py-1 text-center">
                            <input type="checkbox" name="corrected_os_normal">
                        </td>
                        <td class="border px-4 py-1">
                            <input type="text" name="corrected_os_findings" class="w-full border rounded px-2 py-1">
                        </td>
                    </tr>
                    <!-- Pupils -->
                    <tr>
                        <td class="border px-4 py-1">Pupils</td>
                        <td class="border px-4 py-1 text-center">
                            <input type="checkbox" name="pupils_normal">
                        </td>
                        <td class="border px-4 py-1">
                            <input type="text" name="pupils_findings" class="w-full border rounded px-2 py-1">
                        </td>
                    </tr>
                    <!-- Ears, Eardrums -->
                    <tr>
                        <td class="border px-4 py-1">Ears, Eardrums</td>
                        <td class="border px-4 py-1 text-center">
                            <input type="checkbox" name="ears_eardrums_normal">
                        </td>
                        <td class="border px-4 py-1">
                            <input type="text" name="ears_eardrums_findings" class="w-full border rounded px-2 py-1">
                        </td>
                    </tr>
                    <!-- Nose, Sinuses -->
                    <tr>
                        <td class="border px-4 py-1">Nose, Sinuses</td>
                        <td class="border px-4 py-1 text-center">
                            <input type="checkbox" name="nose_sinuses_normal">
                        </td>
                        <td class="border px-4 py-1">
                            <input type="text" name="nose_sinuses_findings" class="w-full border rounded px-2 py-1">
                        </td>
                    </tr>
                    <!-- Mouth, Throat -->
                    <tr>
                        <td class="border px-4 py-1">Mouth, Throat</td>
                        <td class="border px-4 py-1 text-center">
                            <input type="checkbox" name="mouth_throat_normal">
                        </td>
                        <td class="border px-4 py-1">
                            <input type="text" name="mouth_throat_findings" class="w-full border rounded px-2 py-1">
                        </td>
                    </tr>
                    <!-- Neck, Thyroid -->
                    <tr>
                        <td class="border px-4 py-1">Neck, Thyroid</td>
                        <td class="border px-4 py-1 text-center">
                            <input type="checkbox" name="neck_thyroid_normal">
                        </td>
                        <td class="border px-4 py-1">
                            <input type="text" name="neck_thyroid_findings" class="w-full border rounded px-2 py-1">
                        </td>
                    </tr>
                    <!-- Chest, Breast, Axilla -->
                    <tr>
                        <td class="border px-4 py-1">Chest, Breast, Axilla</td>
                        <td class="border px-4 py-1 text-center">
                            <input type="checkbox" name="chest_breast_axilla_normal">
                        </td>
                        <td class="border px-4 py-1">
                            <input type="text" name="chest_breast_axilla_findings" class="w-full border rounded px-2 py-1">
                        </td>
                    </tr>
                    <!-- Heart- Cardiovascular -->
                    <tr>
                        <td class="border px-4 py-1">Heart- Cardiovascular</td>
                        <td class="border px-4 py-1 text-center">
                            <input type="checkbox" name="heart_cardiovascular_normal">
                        </td>
                        <td class="border px-4 py-1">
                            <input type="text" name="heart_cardiovascular_findings" class="w-full border rounded px-2 py-1">
                        </td>
                    </tr>
                    <!-- Lungs- Respiratory -->
                    <tr>
                        <td class="border px-4 py-1">Lungs- Respiratory</td>
                        <td class="border px-4 py-1 text-center">
                            <input type="checkbox" name="lungs_respiratory_normal">
                        </td>
                        <td class="border px-4 py-1">
                            <input type="text" name="lungs_respiratory_findings" class="w-full border rounded px-2 py-1">
                        </td>
                    </tr>
                    <!-- Abdomen -->
                    <tr>
                        <td class="border px-4 py-1">Abdomen</td>
                        <td class="border px-4 py-1 text-center">
                            <input type="checkbox" name="abdomen_normal">
                        </td>
                        <td class="border px-4 py-1">
                            <input type="text" name="abdomen_findings" class="w-full border rounded px-2 py-1">
                        </td>
                    </tr>
                    <!-- Back, Flanks -->
                    <tr>
                        <td class="border px-4 py-1">Back, Flanks</td>
                        <td class="border px-4 py-1 text-center">
                            <input type="checkbox" name="back_flanks_normal">
                        </td>
                        <td class="border px-4 py-1">
                            <input type="text" name="back_flanks_findings" class="w-full border rounded px-2 py-1">
                        </td>
                    </tr>
                    <!-- Mosculoskeletal -->
                    <tr>
                        <td class="border px-4 py-1">Mosculoskeletal</td>
                        <td class="border px-4 py-1 text-center">
                            <input type="checkbox" name="mosculoskeletal_normal">
                        </td>
                        <td class="border px-4 py-1">
                            <input type="text" name="mosculoskeletal_findings" class="w-full border rounded px-2 py-1">
                        </td>
                    </tr>
                    <!-- Musculo-skeletal -->
                    <tr>
                        <td class="border px-4 py-1">Musculo-skeletal</td>
                        <td class="border px-4 py-1 text-center">
                            <input type="checkbox" name="musculo_skeletal_normal">
                        </td>
                        <td class="border px-4 py-1">
                            <input type="text" name="musculo_skeletal_findings" class="w-full border rounded px-2 py-1">
                        </td>
                    </tr>
                    <!-- Extremities -->
                    <tr>
                        <td class="border px-4 py-1">Extremities</td>
                        <td class="border px-4 py-1 text-center">
                            <input type="checkbox" name="extremities_normal">
                        </td>
                        <td class="border px-4 py-1">
                            <input type="text" name="extremities_findings" class="w-full border rounded px-2 py-1">
                        </td>
                    </tr>
                    <!-- Reflexes -->
                    <tr>
                        <td class="border px-4 py-1">Reflexes</td>
                        <td class="border px-4 py-1 text-center">
                            <input type="checkbox" name="reflexes_normal">
                        </td>
                        <td class="border px-4 py-1">
                            <input type="text" name="reflexes_findings" class="w-full border rounded px-2 py-1">
                        </td>
                    </tr>
                    <!-- Neurological -->
                    <tr>
                        <td class="border px-4 py-1">Neurological</td>
                        <td class="border px-4 py-1 text-center">
                            <input type="checkbox" name="neurological_normal">
                        </td>
                        <td class="border px-4 py-1">
                            <input type="text" name="neurological_findings" class="w-full border rounded px-2 py-1">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Diagnosis -->
        <div class="my-6 bg-prims-yellow-1 rounded-lg p-4">
            <h3 class="text-lg font-semibold">Diagnosis</h3>
        </div>

        <div class="mb-6 flex flex-row gap-4">
            <div class="w-1/3">
                <label class="block text-lg font-medium">Diagnosis</label>
                <input list="diagnoses"
                    wire:model.defer="diagnosis"
                    wire:keydown.enter.prevent="applyAutocomplete"
                    id="diagnosisInput"
                    class="w-full p-2 border rounded-md mb-4"
                    placeholder="Type diagnosis...">

                <datalist id="diagnoses">
                    @foreach ($diagnosisOptions as $option)
                        <option value="{{ $option }}">
                    @endforeach
                </datalist>
            </div>
            <div class="w-2/3">
                <label class="block text-lg font-medium">Diagnosis Notes:</label>
                <textarea wire:model="diagnosis_notes" class="w-full border p-2 rounded mb-6" placeholder="Additional notes..."></textarea>
            </div>
        </div>

        <!-- <label class="font-medium text-lg mt-4">Prescription</label>
        <textarea wire:model="prescription" class="w-full border p-2 rounded mb-1" placeholder="Prescription"></textarea> -->

        <div class="mt-6 flex justify-end">
            <a href="/staff/medical-records" class="text-prims-azure-500 text-md m-2 mx-6 underline hover:text-prims-yellow-1 transition ease-in-out duration-150"> Back </a>
            <button 
            id="addRecordButton" 
            class="px-4 py-2 bg-prims-azure-500 text-white rounded-lg hover:bg-prims-azure-100"
            wire:click="submit">
            {{ $fromStaffCalendar ? 'Complete Appointment' : 'Submit' }}
            </button>
        </div>
        </form>
    </div>
</div>