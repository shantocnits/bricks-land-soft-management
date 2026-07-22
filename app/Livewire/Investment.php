<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Investor;
use App\Models\InvestmentTransaction;
use Illuminate\Support\Facades\DB;

class Investment extends Component
{
    use WithPagination;

    public string $search = '';
    public string $typeFilter = 'all';
    public int $perPage = 10;
    public string $activeTab = 'investors'; // investors, transactions

    // Investor Modal Properties
    public bool $showInvestorModal = false;
    public ?int $editingInvestorId = null;
    public string $investorName = '';
    public string $investorPhone = '';
    public string $investorAddress = '';
    public string $profitPercentage = '0';
    public string $investorNotes = '';

    // Transaction Modal Properties
    public bool $showTransactionModal = false;
    public ?int $selectedInvestorId = null;
    public string $transactionType = 'deposit'; // deposit, profit_payout, capital_return
    public string $transactionAmount = '';
    public string $transactionDate = '';
    public string $paymentMethod = 'নগদ';
    public string $transactionNotes = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'typeFilter' => ['except' => 'all'],
    ];

    public function updatingSearch() { $this->resetPage(); }
    public function updatingTypeFilter() { $this->resetPage(); }
    public function updatingPerPage() { $this->resetPage(); }
    public function updatingActiveTab() { $this->resetPage(); }

    public function mount()
    {
        $this->transactionDate = date('Y-m-d');
    }

    public function selectInvestor(?int $id)
    {
        $this->selectedInvestorId = $id;
    }

    public function selectTransactionType(string $type)
    {
        $this->transactionType = $type;
    }

    public function selectPaymentMethod(string $method)
    {
        $this->paymentMethod = $method;
    }

    public function selectTypeFilter(string $filter)
    {
        $this->typeFilter = $filter;
        $this->resetPage();
    }

    public function openInvestorModal(?int $id = null)
    {
        $this->resetValidation();
        $this->editingInvestorId = $id;

        if ($id) {
            $inv = Investor::findOrFail($id);
            $this->investorName = $inv->name;
            $this->investorPhone = $inv->phone ?? '';
            $this->investorAddress = $inv->address ?? '';
            $this->profitPercentage = (string)$inv->profit_percentage;
            $this->investorNotes = $inv->notes ?? '';
        } else {
            $this->resetInvestorFields();
        }

        $this->showInvestorModal = true;
    }

    public function resetInvestorFields()
    {
        $this->editingInvestorId = null;
        $this->investorName = '';
        $this->investorPhone = '';
        $this->investorAddress = '';
        $this->profitPercentage = '0';
        $this->investorNotes = '';
    }

    public function saveInvestor()
    {
        $this->validate([
            'investorName' => 'required|string|max:255',
            'profitPercentage' => 'nullable|numeric|min:0|max:100000000',
        ], [
            'investorName.required' => 'ইনভেস্টরের নাম দেওয়া আবশ্যক',
            'profitPercentage.numeric' => 'মুনাফার হার সঠিক সংখ্যা হতে হবে',
            'profitPercentage.max' => 'মুনাফার হার অতিরিক্ত বড় হতে পারবে না',
        ]);

        $profitVal = is_numeric($this->profitPercentage) ? (float)$this->profitPercentage : 0;

        if ($this->editingInvestorId) {
            $inv = Investor::findOrFail($this->editingInvestorId);
            $inv->update([
                'name' => trim($this->investorName),
                'phone' => trim($this->investorPhone),
                'address' => trim($this->investorAddress),
                'profit_percentage' => $profitVal,
                'notes' => trim($this->investorNotes),
            ]);
            $msg = 'ইনভেস্টর তথ্য সফলভাবে আপডেট করা হয়েছে!';
        } else {
            Investor::create([
                'name' => trim($this->investorName),
                'phone' => trim($this->investorPhone),
                'address' => trim($this->investorAddress),
                'profit_percentage' => $profitVal,
                'notes' => trim($this->investorNotes),
                'total_invested' => 0,
                'total_repaid' => 0,
            ]);
            $msg = 'নতুন ইনভেস্টর সফলভাবে যুক্ত হয়েছে!';
        }

        $this->resetInvestorFields();
        $this->showInvestorModal = false;
        $this->activeTab = 'investors';
        $this->resetPage();

        session()->flash('message', $msg);
        $this->dispatch('show-toast', message: $msg, type: 'success');
    }

    public function deleteInvestor(int $id)
    {
        DB::transaction(function() use ($id) {
            InvestmentTransaction::where('investor_id', $id)->delete();
            Investor::where('id', $id)->delete();
        });

        session()->flash('message', 'ইনভেস্টর ও তাঁর লেনদেন রেকর্ড মুছে ফেলা হয়েছে!');
        $this->dispatch('show-toast', message: 'ইনভেস্টর ও লেনদেন মোছা হয়েছে!', type: 'success');
    }

    public function openTransactionModal(?int $investorId = null)
    {
        $this->resetValidation();
        $this->selectedInvestorId = $investorId;
        $this->transactionAmount = '';
        $this->transactionType = 'deposit';
        $this->transactionDate = date('Y-m-d');
        $this->paymentMethod = 'নগদ';
        $this->transactionNotes = '';
        $this->showTransactionModal = true;
    }

    public function resetTransactionFields()
    {
        $this->selectedInvestorId = null;
        $this->transactionAmount = '';
        $this->transactionType = 'deposit';
        $this->transactionDate = date('Y-m-d');
        $this->paymentMethod = 'নগদ';
        $this->transactionNotes = '';
    }

    public function saveTransaction()
    {
        $this->validate([
            'selectedInvestorId' => 'required|exists:investors,id',
            'transactionType' => 'required|in:deposit,profit_payout,capital_return',
            'transactionAmount' => 'required|numeric|min:0.01',
            'transactionDate' => 'required|date',
        ], [
            'selectedInvestorId.required' => 'ইনভেস্টর নির্বাচন করা আবশ্যক',
            'selectedInvestorId.exists' => 'সঠিক ইনভেস্টর সিলেক্ট করুন',
            'transactionAmount.required' => 'টাকার পরিমাণ দেওয়া আবশ্যক',
            'transactionAmount.min' => 'টাকার পরিমাণ ০ এর বেশি হতে হবে',
        ]);

        $investor = Investor::findOrFail($this->selectedInvestorId);
        $amount = (float)$this->transactionAmount;

        DB::transaction(function() use ($investor, $amount) {
            InvestmentTransaction::create([
                'investor_id' => $investor->id,
                'transaction_type' => $this->transactionType,
                'amount' => $amount,
                'date' => $this->transactionDate,
                'payment_method' => $this->paymentMethod,
                'notes' => trim($this->transactionNotes),
            ]);

            if ($this->transactionType === 'deposit') {
                $investor->increment('total_invested', $amount);
            } elseif ($this->transactionType === 'capital_return') {
                $investor->increment('total_repaid', $amount);
            } elseif ($this->transactionType === 'profit_payout') {
                $investor->increment('total_repaid', $amount);
            }
        });

        $this->resetTransactionFields();
        $this->showTransactionModal = false;
        $this->activeTab = 'transactions';
        $this->resetPage();

        $msg = 'ইনভেস্টমেন্ট লেনদেন সফলভাবে রেকর্ড করা হয়েছে!';
        session()->flash('message', $msg);
        $this->dispatch('show-toast', message: $msg, type: 'success');
    }

    public function deleteTransaction(int $id)
    {
        $tx = InvestmentTransaction::findOrFail($id);
        $investor = $tx->investor;

        DB::transaction(function() use ($tx, $investor) {
            if ($investor) {
                if ($tx->transaction_type === 'deposit') {
                    $investor->decrement('total_invested', min($investor->total_invested, $tx->amount));
                } elseif (in_array($tx->transaction_type, ['profit_payout', 'capital_return'])) {
                    $investor->decrement('total_repaid', min($investor->total_repaid, $tx->amount));
                }
            }
            $tx->delete();
        });

        $msg = 'লেনদেন রেকর্ড মুছে ফেলা হয়েছে!';
        session()->flash('message', $msg);
        $this->dispatch('show-toast', message: $msg, type: 'success');
    }

    public function render()
    {
        // Dynamic Summary Metrics calculated directly from DB
        $txDepositSum = InvestmentTransaction::where('transaction_type', 'deposit')->sum('amount');
        $invDepositSum = Investor::sum('total_invested');
        $totalInvested = max($txDepositSum, $invDepositSum);

        $txRepaidSum = InvestmentTransaction::whereIn('transaction_type', ['profit_payout', 'capital_return'])->sum('amount');
        $invRepaidSum = Investor::sum('total_repaid');
        $totalRepaid = max($txRepaidSum, $invRepaidSum);

        $netBalance = max(0, $totalInvested - $totalRepaid);
        $totalInvestorsCount = Investor::count();

        // Data Query
        if ($this->activeTab === 'investors') {
            $query = Investor::query();

            if (!empty($this->search)) {
                $s = trim($this->search);
                $query->where(function($q) use ($s) {
                    $q->where('name', 'like', "%{$s}%")
                      ->orWhere('phone', 'like', "%{$s}%")
                      ->orWhere('address', 'like', "%{$s}%");
                });
            }
            $records = $query->orderBy('id', 'desc')->paginate($this->perPage);
        } else {
            $query = InvestmentTransaction::with('investor');

            if (!empty($this->search)) {
                $s = trim($this->search);
                $query->whereHas('investor', function($q) use ($s) {
                    $q->where('name', 'like', "%{$s}%")
                      ->orWhere('phone', 'like', "%{$s}%");
                });
            }

            if ($this->typeFilter !== 'all') {
                $query->where('transaction_type', $this->typeFilter);
            }

            $records = $query->orderBy('date', 'desc')->orderBy('id', 'desc')->paginate($this->perPage);
        }

        $allInvestors = Investor::orderBy('name', 'asc')->get();

        return view('livewire.investment', [
            'records' => $records,
            'totalInvested' => $totalInvested,
            'totalRepaid' => $totalRepaid,
            'netBalance' => $netBalance,
            'totalInvestorsCount' => $totalInvestorsCount,
            'allInvestors' => $allInvestors,
        ])->layout('layouts.app');
    }
}
