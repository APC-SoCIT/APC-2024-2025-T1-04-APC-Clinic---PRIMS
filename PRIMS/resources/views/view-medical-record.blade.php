<x-app-layout>
    <div class="py-3">
        <div class="max-w-[90rem] mx-auto sm:px-6 lg:px-8">
            <x-prims-sub-header>
                <a href="{{ url()->previous() }}" class="text-prims-yellow-1">
                    [ < back ]
                </a>
                / Saved Medical Record
            </x-prims-sub-header>
            <livewire:view-medical-record :record="$record" />
        </div>
    </div>
</x-app-layout>
