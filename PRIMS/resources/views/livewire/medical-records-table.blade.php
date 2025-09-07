<div class="p-6 bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg mt-5">
    <!-- Search bar -->
    <div class="flex gap-6 pb-5 justify-end">
        <input type="text" id="searchInput" placeholder="Search records..." class="px-4 py-2 border rounded-lg w-1/3">

        <a href="/staff/add-record">
            <button id="addRecordButton" class="px-4 py-2 bg-prims-azure-500 text-white rounded-lg hover:bg-prims-azure-100">
                Add Record
            </button>
        </a>
    </div>

    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table id="recordsTable" class="w-full min-w-full divide-y divide-gray-200">
            <thead style="background-color: #F4BF4F;">
                <tr>
                    <th class="px-6 py-3 text-left text-sm uppercase tracking-wider">
                        APC ID Number
                    </th>
                    <th class="px-6 py-3 text-left text-sm uppercase tracking-wider">
                        Last Name
                    </th>
                    <th class="px-6 py-3 text-left text-sm uppercase tracking-wider">
                        First Name
                    </th>
                    <th class="px-6 py-3 text-center text-sm uppercase tracking-wider">
                        Middle Initial
                    </th>
                    <th class="px-6 py-3 text-left text-sm uppercase tracking-wider">
                        Email
                    </th>
                    <th class="px-6 py-3 text-left text-sm uppercase tracking-wider">
                        Last Visited
                    </th>
                    <th class="px-6 py-3 text-left text-sm uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($records as $record)
                    <tr>
                        <td class="px-6 py-3 text-left text-md">{{ $record->apc_id_number }}</td>
                        <td class="px-6 py-3 text-left text-md">{{ $record->last_name }}</td>
                        <td class="px-6 py-3 text-left text-md">{{ $record->first_name }}</td>
                        <td class="px-6 py-3 text-left text-md text-center">{{ $record->mi }}</td>
                        <td class="px-6 py-3 text-left text-md">{{ $record->email }}</td>
                        <td class="px-6 py-3 text-left text-md">{{ $record->last_visited }}</td>
                        <td>
                            <button wire:click="toggleExpand('{{ $record->apc_id_number }}')"
                                    class="px-4 py-1 bg-blue-500 text-white rounded">
                                View Records
                            </button>
                        </td>
                    </tr>

                    @if ($expandedPatient === $record->apc_id_number)
                        <tr>
                            <td colspan="7">
                                <div class="flex justify-center mt-4 mb-2">
                                    <h3 class="text-lg font-semibold">Medical Records for {{ $record->first_name }} {{ $record->mi }}. {{ $record->last_name }} ({{ $record->apc_id_number }})</h3>
                                </div>
                                <div class="flex justify-center">
                                    <table class="table-auto w-[80%] rounded mb-4 border">
                                        <thead>
                                            <tr class="border border-gray-300 bg-gray-200">
                                                <th></th>
                                                <th>Date</th>
                                                <th>Reason</th>
                                                <th>Diagnosis</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($this->getPatientRecords($record->apc_id_number) as $med)
                                                <tr>
                                                    <td class="text-center">
                                                        <input type="checkbox" disabled {{ $med->appointment_id ? 'checked' : '' }}>
                                                    </td>
                                                    <td class="text-center">{{ \Carbon\Carbon::parse($med->last_visited)->format('M j, Y') }}</td>
                                                    <td class="text-center">{{ $med->reason }}</td>
                                                    <td class="text-center">{{ $med->diagnoses->first()->diagnosis ?? '-' }}</td>
                                                    <td class="text-center">
                                                        <a href="{{ route('view-medical-record', $med->id) }}" class="text-blue-600">View</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    document.getElementById("searchInput").addEventListener("keyup", function () {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll(".record-row");

        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? "" : "none";
        });
    });
</script>
