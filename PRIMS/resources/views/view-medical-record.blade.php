<x-app-layout>
    <div class="py-3">
        <div class="max-w-[90rem] mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center gap-1">
            <x-prims-sub-header>
                <a href="{{ url()->previous() }}" class="pr-3">
                    <
                </a>
            </x-prims-sub-header>
            <div class="flex-1">
                <x-prims-sub-header>
                    Saved Medical Record
                </x-prims-sub-header>
            </div>
        </div>
            <livewire:view-medical-record :record="$record" />
        </div>
    </div>
</x-app-layout>
