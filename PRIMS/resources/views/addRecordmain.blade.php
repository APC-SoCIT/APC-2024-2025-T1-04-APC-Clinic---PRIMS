<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 my-6">
        <x-prims-sub-header>
            New Medical Record
        </x-prims-sub-header>
        <livewire:add-medical-record 
            :appointment_id="request()->query('appointment_id')"
            :fromStaffCalendar="(bool) request()->query('fromStaffCalendar', false)"
        />
    </div>
</x-app-layout>
