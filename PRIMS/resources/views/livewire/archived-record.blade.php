<div>
    <h2 class="text-lg font-semibold mb-4">Archived Medical Records</h2>

    @if(session()->has('message'))
        <div class="bg-green-100 text-green-800 p-2 mb-4">
            {{ session('message') }}
        </div>
    @endif

    <table class="w-full border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-200">
                <th class="border border-gray-300 p-2">ID</th>
                <th class="border border-gray-300 p-2">Patient Name</th>
                <th class="border border-gray-300 p-2">Archived At</th>
                <th class="border border-gray-300 p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($archivedRecords as $record)
                <tr>
                    <td class="border border-gray-300 p-2">{{ $record->id }}</td>
                    <td class="border border-gray-300 p-2">{{ $record->patient_name }}</td>
                    <td class="border border-gray-300 p-2">{{ $record->archived_at }}</td>
                    <td class="border border-gray-300 p-2">
                        <button wire:click="restoreRecord({{ $record->id }})" class="bg-blue-500 text-white px-3 py-1 rounded">
                            Restore
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
