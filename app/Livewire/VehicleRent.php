<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\VehicleRent as VehicleRentModel;

class VehicleRent extends Component
{
    use WithPagination;

    public $search = '';
    public int $perPage = 15;
    public string $sortField = 'id';
    public string $sortDirection = 'desc';

    public $showEditModal = false;
    public $editingId = null;
    public $address = '';
    public $area = '';
    public $fare = 0;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'desc';
        }
    }

    public function openEditModal($id)
    {
        $rent = VehicleRentModel::findOrFail($id);
        $this->editingId = $rent->id;
        $this->address = $rent->address;
        $this->area = $rent->area;
        $this->fare = rtrim(rtrim(number_format($rent->fare, 2, '.', ''), '0'), '.');
        $this->showEditModal = true;
    }

    public function closeModal()
    {
        $this->showEditModal = false;
        $this->reset(['editingId', 'address', 'area', 'fare']);
    }

    public function saveRent()
    {
        $this->validate([
            'address' => 'required|string|max:255',
            'fare' => 'required|numeric|min:0',
        ], [
            'address.required' => 'ঠিকানা আবশ্যক।',
            'fare.required' => 'ভাড়া আবশ্যক।',
        ]);

        if ($this->editingId) {
            $rent = VehicleRentModel::findOrFail($this->editingId);
            $rent->update([
                'address' => $this->address,
                'area' => $this->area ?: null,
                'fare' => $this->fare,
            ]);
            $this->dispatch('show-toast', ['message' => 'গাড়ি ভাড়ার তথ্য সফলভাবে আপডেট হয়েছে!']);
        }

        $this->closeModal();
    }

    public function render()
    {
        $query = VehicleRentModel::query();

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('address', 'like', '%' . $this->search . '%')
                  ->orWhere('area', 'like', '%' . $this->search . '%');
            });
        }

        $rents = $query->orderBy($this->sortField, $this->sortDirection)
                       ->paginate($this->perPage);

        return view('livewire.vehicle-rent', [
            'rents' => $rents,
        ]);
    }
}
