<x-app-layout>

<div class="py-3">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <x-prims-sub-header>
            <a href="{{ url()->previous() }}" class="text-prims-yellow-1">
                [ < back ]
            </a>
            / Inventory Details
        </x-prims-sub-header>

        @livewire('inventory-details', ['id' => request()->id])

    </div>
</div>

</x-app-layout>