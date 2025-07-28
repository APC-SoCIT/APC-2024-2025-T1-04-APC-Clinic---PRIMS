<div>
    <table class="table-auto w-full border-collapse">
        <thead>
            <tr>
                <th class="px-4 py-2 text-left border-b">ID Number</th>
                <th class="px-4 py-2 text-left border-b">Last Name</th>
                <th class="px-4 py-2 text-left border-b">First Name</th>
                <th class="px-4 py-2 text-left border-b">Last Visited</th>
                <th class="px-4 py-2 text-left border-b">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $record)
                <tr>
                    <td class="px-4 py-2 border-b">{{ $record->apc_id_number }}</td>
                    <td class="px-4 py-2 border-b">{{ $record->last_name }}</td>
                    <td class="px-4 py-2 border-b">{{ $record->first_name }}</td>
                    <td class="px-4 py-2 border-b">{{ $record->last_visited }}</td>
                    <td class="px-4 py-2 border-b">
                    <a href="{{ route('view-medical-record', ['id' => $record->id]) }}">View</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-2 text-center">No records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
