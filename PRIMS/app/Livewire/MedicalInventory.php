<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Inventory;
use Livewire\WithPagination;


class MedicalInventory extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'supplies.name';
    public $sortDirection = 'asc';
    public $itemIdToDispose;
    public $fullyDeletedSupplies;
    protected $listeners = ['disposeItem', 'show-confirm-modal'];

    // public function mount()
    // {
    //     $this->fullyDeletedSupplies = Inventory::withTrashed()
    //         ->select('supply_id')
    //         ->groupBy('supply_id')
    //         ->havingRaw('COUNT(*) = SUM(CASE WHEN deleted_at IS NOT NULL THEN 1 ELSE 0 END)')
    //         ->pluck('supply_id');
    // }

    public function updatedSearch()
    {
        $this->render();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function confirmDisposal($itemId)
    {
        $this->itemIdToDispose = $itemId;
        $this->dispatch('show-confirm-modal'); 
    }

    public function disposeItem()
    {
        if ($this->itemIdToDispose) {
            Inventory::find($this->itemIdToDispose)?->delete();
            $this->itemIdToDispose = null;
            session()->flash('message', 'Item disposed successfully.');
        }
    }
    
    public function render()
    {
        $inventory = Inventory::with('supply')
            ->leftjoin('supplies', 'inventory.supply_id', '=', 'supplies.id')
            ->whereNull('inventory.deleted_at')
            ->orderBy($this->sortField, $this->sortDirection) // Sort alphabetically by name
            ->select('inventory.*', 'supplies.name as supply_name', 'supplies.brand', 'supplies.category', 'supplies.dosage_strength', 'supplies.dosage_form')
            ->when(strlen($this->search) > 0, function ($query) { 
                $query->where(function ($q) {
                    $q->where('supplies.name', 'like', '%' . trim($this->search) . '%')
                      ->orWhere('supplies.brand', 'like', '%' . trim($this->search) . '%')
                      ->orWhere('supplies.category', 'like', '%' . trim($this->search) . '%');
                });
            })
            ->get();

        $inventoryWithTrashed = Inventory::withTrashed()
            ->join('supplies', 'inventory.supply_id', '=', 'supplies.id')
            ->selectRaw('
                inventory.supply_id, 
                MIN(supplies.name) as supply_name, 
                MIN(supplies.brand) as brand, 
                MIN(supplies.category) as category, 
                MIN(supplies.dosage_strength) as dosage_strength, 
                MIN(supplies.dosage_form) as dosage_form
            ')
            ->groupBy('inventory.supply_id')
            ->havingRaw('COUNT(*) = SUM(CASE WHEN inventory.deleted_at IS NOT NULL THEN 1 ELSE 0 END)')
            ->get();

        return view('livewire.medical-inventory', compact('inventory', 'inventoryWithTrashed'));
    }
}
