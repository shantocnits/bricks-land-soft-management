<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\DeunaTransaction;

class DeunaLedger extends Component
{
    use WithPagination;

    public string $search = '';
    public int $givenPerPage = 10;
    public int $receivedPerPage = 10;

    // Modal state
    public bool $showModal = false;
    public ?int $editingId = null;

    // Form fields
    public string $ledger_name = '';
    public string $transaction_type = 'নেওয়া';  // দেওয়া / নেওয়া
    public string $address = '';
    public string $phone = '';
    public string $amount = '';
    public string $start_date = '';
    public string $transaction_date = '';
    public string $due_date = '';
    public string $row1 = '';
    public string $row2 = '';
    public string $description = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage('givenPage');
        $this->resetPage('receivedPage');
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $t = DeunaTransaction::findOrFail($id);
        $this->editingId = $id;
        $this->ledger_name = $t->ledger_name;
        $this->transaction_type = $t->transaction_type;
        $this->address = $t->address ?? '';
        $this->phone = $t->phone ?? '';
        $this->amount = (string) $t->amount;
        $this->start_date = $t->start_date ? $t->start_date->toDateString() : '';
        $this->transaction_date = $t->transaction_date ? $t->transaction_date->toDateString() : '';
        $this->due_date = $t->due_date ? $t->due_date->toDateString() : '';
        $this->row1 = $t->row1 ?? '';
        $this->row2 = $t->row2 ?? '';
        $this->description = $t->description ?? '';
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'ledger_name'      => 'required|string|max:255',
            'transaction_type' => 'required|in:দেওয়া,নেওয়া',
            'address'          => 'nullable|string|max:255',
            'phone'            => 'nullable|string|max:20',
            'amount'           => 'required|numeric|min:0',
            'start_date'       => 'nullable|date',
            'transaction_date' => 'nullable|date',
            'due_date'         => 'nullable|date',
            'row1'             => 'nullable|string|max:255',
            'row2'             => 'nullable|string|max:255',
            'description'      => 'nullable|string|max:2000',
        ]);

        $data = [
            'ledger_name'      => $this->ledger_name,
            'transaction_type' => $this->transaction_type,
            'address'          => $this->address ?: null,
            'phone'            => $this->phone ?: null,
            'amount'           => (float) $this->amount,
            'start_date'       => $this->start_date ?: null,
            'transaction_date' => $this->transaction_date ?: null,
            'due_date'         => $this->due_date ?: null,
            'row1'             => $this->row1 ?: null,
            'row2'             => $this->row2 ?: null,
            'description'      => $this->description ?: null,
        ];

        if ($this->editingId) {
            DeunaTransaction::findOrFail($this->editingId)->update($data);
            $msg = 'হিসাব সফলভাবে আপডেট করা হয়েছে!';
        } else {
            $t = DeunaTransaction::create($data);
            \App\Models\DeunaTransactionHistory::create([
                'deuna_transaction_id' => $t->id,
                'type'                 => 'initial',
                'transaction_date'     => $t->transaction_date ?: now(),
                'description'          => $t->description ?: 'প্রাথমিক লেনদেন',
                'given_amount'         => $t->transaction_type === 'দেওয়া' ? $t->amount : 0,
                'received_amount'      => $t->transaction_type === 'নেওয়া' ? $t->amount : 0,
                'paid_amount'          => 0,
                'balance'              => $t->amount,
            ]);
            $msg = 'নতুন হিসাব সফলভাবে যোগ করা হয়েছে!';
        }

        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('show-toast', message: $msg, type: 'success');
    }

    public function delete(int $id): void
    {
        DeunaTransaction::findOrFail($id)->delete();
        $this->dispatch('show-toast', message: 'হিসাবটি সফলভাবে মুছে ফেলা হয়েছে!', type: 'success');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->ledger_name = '';
        $this->transaction_type = 'নেওয়া';
        $this->address = '';
        $this->phone = '';
        $this->amount = '';
        $this->start_date = '';
        $this->transaction_date = '';
        $this->due_date = '';
        $this->row1 = '';
        $this->row2 = '';
        $this->description = '';
    }

    public function render()
    {
        $baseQuery = DeunaTransaction::query()
            ->when($this->search, fn($q) => $q->where(function ($q2) {
                $q2->where('ledger_name', 'like', '%' . $this->search . '%')
                   ->orWhere('address', 'like', '%' . $this->search . '%')
                   ->orWhere('phone', 'like', '%' . $this->search . '%');
            }));

        $givenTotal    = (clone $baseQuery)->where('transaction_type', 'দেওয়া')->sum('amount');
        $receivedTotal = (clone $baseQuery)->where('transaction_type', 'নেওয়া')->sum('amount');

        $givenList    = (clone $baseQuery)->where('transaction_type', 'দেওয়া')->orderBy('id', 'desc')->paginate($this->givenPerPage, ['*'], 'givenPage');
        $receivedList = (clone $baseQuery)->where('transaction_type', 'নেওয়া')->orderBy('id', 'desc')->paginate($this->receivedPerPage, ['*'], 'receivedPage');

        return view('livewire.deuna-pauna', [
            'givenList'     => $givenList,
            'receivedList'  => $receivedList,
            'givenTotal'    => $givenTotal,
            'receivedTotal' => $receivedTotal,
        ])->layout('layouts.app');
    }
}
