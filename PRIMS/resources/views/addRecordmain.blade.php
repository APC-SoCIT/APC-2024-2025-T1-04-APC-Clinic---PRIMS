<x-app-layout>
    <div class="py-3">
        <div class="max-w-[90rem] mx-auto sm:px-6 lg:px-8">
            <x-prims-sub-header>
                <a href="{{ url()->previous() }}" class="text-prims-yellow-1">
                    [ < back ]
                </a>
                / New Medical Record
            </x-prims-sub-header>
            <livewire:add-medical-record 
                :appointment_id="request()->query('appointment_id')"
                :fromStaffCalendar="(bool) request()->query('fromStaffCalendar', false)"
            />
        </div>
    </div>
</x-app-layout>
