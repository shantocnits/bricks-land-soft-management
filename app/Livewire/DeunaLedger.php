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

    // Delete confirmation modal state
    public bool $showDeleteConfirmModal = false;
    public ?int $deletingId = null;

    // Form fields
    public string $ledger_name = '';
    public string $transaction_type = 'নেওয়া';  // দেওয়া / নেওয়া
    public string $address = '';
    public string $phone = '';
    public string $amount = '';
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

    public function checkCanModify(DeunaTransaction $transaction): bool
    {
        $user = auth()->user();
        if ($user && $user->isAdmin()) {
            return true;
        }

        $trxDate = $transaction->transaction_date 
            ? $transaction->transaction_date->toDateString() 
            : ($transaction->created_at ? $transaction->created_at->toDateString() : null);

        return $trxDate === now()->toDateString();
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $t = DeunaTransaction::findOrFail($id);

        if (!$this->checkCanModify($t)) {
            $this->dispatch('show-toast', message: 'সাধারণ ইউজার শুধুমাত্র আজকের হিসাব সম্পাদনা করতে পারবেন!', type: 'error');
            return;
        }

        $this->editingId = $id;
        $this->ledger_name = $t->ledger_name;
        $this->transaction_type = $t->transaction_type;
        $this->address = $t->address ?? '';
        $this->phone = $t->phone ?? '';
        $this->amount = (string) $t->amount;
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
            'transaction_type' => 'required|in:দেওয়া,দেওয়া,নেওয়া,নেওয়া',
            'address'          => 'nullable|string|max:255',
            'phone'            => 'nullable|string|max:20',
            'amount'           => 'required|numeric|min:0',
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
            'transaction_date' => $this->transaction_date ?: null,
            'due_date'         => $this->due_date ?: null,
            'row1'             => $this->row1 ?: null,
            'row2'             => $this->row2 ?: null,
            'description'      => $this->description ?: null,
        ];

        if ($this->editingId) {
            $t = DeunaTransaction::findOrFail($this->editingId);
            if (!$this->checkCanModify($t)) {
                $this->dispatch('show-toast', message: 'সাধারণ ইউজার শুধুমাত্র আজকের হিসাব সম্পাদনা করতে পারবেন!', type: 'error');
                return;
            }

            // Amount cannot be changed on edit to maintain static snapshot history
            $data['amount'] = (float) $t->amount;

            $t->update($data);
            \App\Models\ActivityLog::log('দেনা-পাওনা আপডেট', "হিসাব আপডেট (আইডি: {$t->id}): {$t->ledger_name} • ধরণ: {$t->transaction_type} • টাকা: {$t->amount}");
            $msg = 'হিসাব সফলভাবে আপডেট করা হয়েছে!';
        } else {
            $t = DeunaTransaction::create($data);
            $isGiven = in_array(trim($t->transaction_type), ['দেওয়া', 'দেওয়া']);
            \App\Models\DeunaTransactionHistory::create([
                'deuna_transaction_id' => $t->id,
                'type'                 => 'initial',
                'transaction_date'     => $t->transaction_date ?: now(),
                'description'          => $t->description ?: 'প্রাথমিক লেনদেন',
                'given_amount'         => $isGiven ? $t->amount : 0,
                'received_amount'      => !$isGiven ? $t->amount : 0,
                'paid_amount'          => 0,
                'balance'              => $t->amount,
            ]);
            \App\Models\ActivityLog::log('দেনা-পাওনা তৈরি', "নতুন হিসাব তৈরি: {$t->ledger_name} • ধরণ: {$t->transaction_type} • টাকা: {$t->amount}");
            $msg = 'নতুন হিসাব সফলভাবে যোগ করা হয়েছে!';
        }

        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('show-toast', message: $msg, type: 'success');
    }

    public function confirmDelete(int $id): void
    {
        $t = DeunaTransaction::findOrFail($id);
        if (!$this->checkCanModify($t)) {
            $this->dispatch('show-toast', message: 'সাধারণ ইউজার শুধুমাত্র আজকের হিসাব মুছতে পারবেন!', type: 'error');
            return;
        }

        $hasHistory = \App\Models\DeunaTransactionHistory::where('deuna_transaction_id', $t->id)
            ->whereIn('type', ['payment', 'new_loan'])
            ->exists() || $t->paid_amount > 0;

        if ($hasHistory) {
            $this->dispatch('show-toast', message: 'এই ব্যক্তির লেনদেন রেকর্ড বিদ্যমান থাকায় ডিলিট করা সম্ভব নয়।', type: 'error');
            return;
        }

        $this->deletingId = $id;
        $this->showDeleteConfirmModal = true;
    }

    public function delete(?int $id = null): void
    {
        $targetId = $id ?? $this->deletingId;
        if ($targetId) {
            $t = DeunaTransaction::findOrFail($targetId);
            if (!$this->checkCanModify($t)) {
                $this->dispatch('show-toast', message: 'সাধারণ ইউজার শুধুমাত্র আজকের হিসাব মুছতে পারবেন!', type: 'error');
                $this->showDeleteConfirmModal = false;
                $this->deletingId = null;
                return;
            }

            $hasHistory = \App\Models\DeunaTransactionHistory::where('deuna_transaction_id', $t->id)
                ->whereIn('type', ['payment', 'new_loan'])
                ->exists() || $t->paid_amount > 0;

            if ($hasHistory) {
                $this->dispatch('show-toast', message: 'এই ব্যক্তির লেনদেন রেকর্ড বিদ্যমান থাকায় ডিলিট করা সম্ভব নয়।', type: 'error');
                $this->showDeleteConfirmModal = false;
                $this->deletingId = null;
                return;
            }

            $deletedName = $t->ledger_name;
            $deletedType = $t->transaction_type;
            $deletedAmount = $t->amount;

            \App\Models\DeunaTransactionHistory::where('deuna_transaction_id', $t->id)->delete();
            $t->delete();

            \App\Models\ActivityLog::log('দেনা-পাওনা ডিলিট', "হিসাব ডিলিট: {$deletedName} • ধরণ: {$deletedType} • টাকা: {$deletedAmount}");
            $this->dispatch('show-toast', message: 'হিসাবটি সফলভাবে মুছে ফেলা হয়েছে!', type: 'success');
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
        $this->ledger_name = '';
        $this->transaction_type = 'নেওয়া';
        $this->address = '';
        $this->phone = '';
        $this->amount = '';
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

        $givenTotal    = (clone $baseQuery)->where('transaction_type', 'দেওয়া')->get()->sum(fn($t) => max(0, $t->amount - $t->paid_amount));
        $receivedTotal = (clone $baseQuery)->where('transaction_type', 'নেওয়া')->get()->sum(fn($t) => max(0, $t->amount - $t->paid_amount));

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
