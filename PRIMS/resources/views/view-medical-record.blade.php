<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 my-6">
        <x-prims-sub-header>
            Saved Medical Record
        </x-prims-sub-header>
        <livewire:view-medical-record :record="$record" />
    </div>
</x-app-layout>
