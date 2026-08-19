<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\DeunaTransaction;
use App\Models\DeunaTransactionHistory;

class DeunaProfile extends Component
{
    use WithPagination;

    public int $transactionId;
    public ?DeunaTransaction $transaction = null;
    public int $historyPerPage = 10;

    // Payment Form (ঋণ পরিশোধ)
    public bool $showPayModal = false;
    public string $payAmount = '';
    public string $nextPayDate = '';
    public string $payNotes = '';

    // New Loan Form (নতুন লেনদেন)
    public bool $showNewLoanModal = false;
    public string $newLoanAmount = '';
    public string $newLoanDueDate = '';
    public string $newLoanDescription = '';

    public function mount(int $id): void
    {
        $this->transactionId = $id;
        $this->transaction = DeunaTransaction::findOrFail($id);
        $this->ensureInitialHistory();
    }

    private function isGivenType(?string $type): bool
    {
        if (!$type) return false;
        return in_array(trim($type), ['দেওয়া', 'দেওয়া']);
    }

    private function ensureInitialHistory(): void
    {
        if (!$this->transaction) return;

        $count = DeunaTransactionHistory::where('deuna_transaction_id', $this->transactionId)->count();
        $isGiven = $this->isGivenType($this->transaction->transaction_type);

        if ($count === 0) {
            DeunaTransactionHistory::create([
                'deuna_transaction_id' => $this->transaction->id,
                'type'                 => 'initial',
                'transaction_date'     => $this->transaction->transaction_date ?: ($this->transaction->created_at ?: now()),
                'description'          => $this->transaction->description ?: 'প্রাথমিক লেনদেন',
                'given_amount'         => $isGiven ? $this->transaction->amount : 0,
                'received_amount'      => !$isGiven ? $this->transaction->amount : 0,
                'paid_amount'          => 0,
                'balance'              => $this->transaction->amount,
            ]);
        }
    }

    public function openPayModal(): void
    {
        $this->payAmount = '';
        $this->nextPayDate = $this->transaction->due_date ? $this->transaction->due_date->toDateString() : '';
        $this->payNotes = '';
        $this->showPayModal = true;
    }

    public function savePay(): void
    {
        $this->validate([
            'payAmount' => 'required|numeric|min:0.01',
            'nextPayDate' => 'nullable|date',
            'payNotes' => 'nullable|string|max:1000',
        ]);

        $t = DeunaTransaction::findOrFail($this->transactionId);
        $payVal = (float) $this->payAmount;

        // Get latest historical balance snapshot
        $latestHistory = DeunaTransactionHistory::where('deuna_transaction_id', $t->id)
            ->orderBy('id', 'desc')
            ->first();

        $prevBalance = $latestHistory ? (float)$latestHistory->balance : max(0, (float)$t->amount - (float)$t->paid_amount);
        $newBalance = max(0, $prevBalance - $payVal);

        $t->paid_amount += $payVal;
        if ($this->nextPayDate) {
            $t->due_date = $this->nextPayDate;
        }
        $t->save();

        // Record immutable static snapshot history log
        DeunaTransactionHistory::create([
            'deuna_transaction_id' => $t->id,
            'type'                 => 'payment',
            'transaction_date'     => now(),
            'description'          => $this->payNotes ?: 'ঋণ পরিশোধ',
            'given_amount'         => 0,
            'received_amount'      => 0,
            'paid_amount'          => $payVal,
            'balance'              => $newBalance,
        ]);

        \App\Models\ActivityLog::log('ঋণ পরিশোধ', "গ্রাহক {$t->ledger_name} (আইডি: {$t->id}): ঋণ পরিশোধ ৳ {$payVal} • অবশিষ্ট বাকি: ৳ {$newBalance}");

        $this->transaction = $t;
        $this->showPayModal = false;
        $this->dispatch('show-toast', message: 'পরিশোধ সফলভাবে আপডেট করা হয়েছে!', type: 'success');
    }

    public function openNewLoanModal(): void
    {
        $this->newLoanAmount = '';
        $this->newLoanDueDate = $this->transaction->due_date ? $this->transaction->due_date->toDateString() : '';
        $this->newLoanDescription = '';
        $this->showNewLoanModal = true;
    }

    public function saveNewLoan(): void
    {
        $this->validate([
            'newLoanAmount' => 'required|numeric|min:0.01',
            'newLoanDueDate' => 'nullable|date',
            'newLoanDescription' => 'nullable|string|max:1000',
        ]);

        $t = DeunaTransaction::findOrFail($this->transactionId);
        $addedVal = (float) $this->newLoanAmount;

        // Get latest historical balance snapshot
        $latestHistory = DeunaTransactionHistory::where('deuna_transaction_id', $t->id)
            ->orderBy('id', 'desc')
            ->first();

        $prevBalance = $latestHistory ? (float)$latestHistory->balance : max(0, (float)$t->amount - (float)$t->paid_amount);
        $newBalance = $prevBalance + $addedVal;

        $t->amount += $addedVal;
        if ($this->newLoanDueDate) {
            $t->due_date = $this->newLoanDueDate;
        }
        $t->save();

        $isGiven = $this->isGivenType($t->transaction_type);

        // Record immutable static snapshot history log
        DeunaTransactionHistory::create([
            'deuna_transaction_id' => $t->id,
            'type'                 => 'new_loan',
            'transaction_date'     => now(),
            'description'          => $this->newLoanDescription ?: 'নতুন লেনদেন',
            'given_amount'         => $isGiven ? $addedVal : 0,
            'received_amount'      => !$isGiven ? $addedVal : 0,
            'paid_amount'          => 0,
            'balance'              => $newBalance,
        ]);

        \App\Models\ActivityLog::log('নতুন লেনদেন', "গ্রাহক {$t->ledger_name} (আইডি: {$t->id}): নতুন ঋণ/লেনদেন যোগ ৳ {$addedVal} • নতুন বাকি: ৳ {$newBalance}");

        $this->transaction = $t;
        $this->showNewLoanModal = false;
        $this->dispatch('show-toast', message: 'নতুন লেনদেন সফলভাবে যোগ করা হয়েছে!', type: 'success');
    }

    public function setHistoryPerPage(int $perPage): void
    {
        $this->historyPerPage = $perPage;
        $this->resetPage('historyPage');
    }

    public function render()
    {
        $perPage = $this->historyPerPage > 0 ? $this->historyPerPage : 999999;
        $histories = DeunaTransactionHistory::where('deuna_transaction_id', $this->transactionId)
            ->orderBy('id', 'desc')
            ->paginate($perPage, ['*'], 'historyPage');

        return view('livewire.deuna-profile', [
            'transaction' => $this->transaction,
            'histories'   => $histories,
        ])->layout('layouts.app');
    }
}
