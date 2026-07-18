<?php

namespace App\Livewire;

use Livewire\Component;

class FeePayment extends Component
{
    public string $method = 'bkash';
    public string $userId = '';
    public string $accountNumber = '';
    public string $transactionId = '';
    public string $amount = '';
    public bool $submitted = false;

    protected $rules = [
        'userId' => 'required',
        'accountNumber' => 'required',
        'transactionId' => 'required',
        'amount' => 'required|numeric|min:1',
    ];

    protected $messages = [
        'userId.required' => 'ইউজার আইডি দিন',
        'accountNumber.required' => 'অ্যাকাউন্ট নম্বর দিন',
        'transactionId.required' => 'ট্রানজেকশন আইডি দিন',
        'amount.required' => 'টাকার পরিমাণ দিন',
        'amount.numeric' => 'টাকার পরিমাণ সঠিক সংখ্যায় হতে হবে',
        'amount.min' => 'টাকার পরিমাণ কমপক্ষে ১ হতে হবে',
    ];

    public array $history = [
        [
            'date'    => 'January 27, 2026 10:50 AM',
            'method'  => 'বিকাশ',
            'account' => '01721661663',
            'trxid'   => '213132123',
            'amount'  => '৳৫,০০০',
            'status'  => 'Cancelled',
        ],
        [
            'date'    => 'March 05, 2026 03:14 PM',
            'method'  => 'নগদ',
            'account' => '01812345678',
            'trxid'   => '998877665',
            'amount'  => '৳৩,৫০০',
            'status'  => 'Completed',
        ],
    ];

    public function submit(): void
    {
        $this->validate();

        // Add to history mock
        $this->history[] = [
            'date'    => now()->format('F d, Y h:i A'),
            'method'  => $this->method === 'bkash' ? 'বিকাশ' : 'নগদ',
            'account' => $this->accountNumber,
            'trxid'   => $this->transactionId,
            'amount'  => '৳' . number_format((float)$this->amount, 0, '.', ','),
            'status'  => 'Pending',
        ];

        $this->dispatch('show-toast', message: 'পেমেন্ট সফলভাবে জমা দেওয়া হয়েছে।', type: 'success');
        $this->reset(['userId', 'accountNumber', 'transactionId', 'amount']);
        $this->submitted = true;
    }

    public function clearForm(): void
    {
        $this->reset(['userId', 'accountNumber', 'transactionId', 'amount']);
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.fee-payment');
    }
}
