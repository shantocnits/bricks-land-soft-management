<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PhoneContact;

class PhoneNumber extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 15;

    // Modal state
    public bool $showModal = false;
    public ?int $editingId = null;

    // Delete confirmation state
    public bool $showDeleteConfirmModal = false;
    public ?int $deletingId = null;

    // Form fields
    public string $name = '';
    public string $address = '';
    public string $profession = '';
    public string $phone = '';
    public string $notes = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->editingId = null;
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $contact = PhoneContact::findOrFail($id);
        $this->editingId = $id;
        $this->name = $contact->name;
        $this->address = $contact->address ?? '';
        $this->profession = $contact->profession ?? '';
        $this->phone = $contact->phone ?? '';
        $this->notes = $contact->notes ?? '';
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'name'       => 'required|string|max:255',
            'address'    => 'nullable|string|max:255',
            'profession' => 'nullable|string|max:255',
            'phone'      => 'nullable|string|max:20',
            'notes'      => 'nullable|string|max:1000',
        ]);

        if ($this->editingId) {
            $contact = PhoneContact::findOrFail($this->editingId);
            $contact->update([
                'name'       => $this->name,
                'address'    => $this->address,
                'profession' => $this->profession,
                'phone'      => $this->phone,
                'notes'      => $this->notes,
            ]);
            $msg = 'তথ্য সফলভাবে আপডেট করা হয়েছে!';
        } else {
            PhoneContact::create([
                'name'       => $this->name,
                'address'    => $this->address,
                'profession' => $this->profession,
                'phone'      => $this->phone,
                'notes'      => $this->notes,
            ]);
            $msg = 'নতুন নম্বর সফলভাবে যোগ করা হয়েছে!';
        }

        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('show-toast', message: $msg, type: 'success');
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->showDeleteConfirmModal = true;
    }

    public function delete(?int $id = null): void
    {
        $targetId = $id ?? $this->deletingId;
        if ($targetId) {
            PhoneContact::findOrFail($targetId)->delete();
            $this->dispatch('show-toast', message: 'নম্বরটি সফলভাবে মুছে ফেলা হয়েছে!', type: 'success');
        }
        $this->showDeleteConfirmModal = false;
        $this->deletingId = null;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->address = '';
        $this->profession = '';
        $this->phone = '';
        $this->notes = '';
    }

    public function render()
    {
        $contacts = PhoneContact::query()
            ->when($this->search, fn($q) => $q->where(function ($q2) {
                $q2->where('name', 'like', '%' . $this->search . '%')
                   ->orWhere('phone', 'like', '%' . $this->search . '%')
                   ->orWhere('address', 'like', '%' . $this->search . '%')
                   ->orWhere('profession', 'like', '%' . $this->search . '%');
            }))
            ->orderBy('name')
            ->paginate($this->perPage);

        return view('livewire.phone-number', [
            'contacts' => $contacts,
        ])->layout('layouts.app');
    }
}
