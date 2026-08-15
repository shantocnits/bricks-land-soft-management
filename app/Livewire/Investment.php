<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Investor;
use App\Models\InvestmentTransaction;
use App\Models\Setting;
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
    public string $profitPercentage = '';
    public string $initialInvestment = '';
    public string $investorPaymentMethod = 'নগদ';
    public string $investorNotes = '';

    // Transaction Modal Properties
    public bool $showTransactionModal = false;
    public ?int $selectedInvestorId = null;
    public string $transactionType = 'deposit'; // deposit, profit_payout, capital_return
    public string $transactionAmount = '';
    public string $transactionDate = '';
    public string $paymentMethod = 'নগদ';
    public string $transactionNotes = '';

    // Profit Calculator & Card Properties
    public string $totalBusinessProfit = '';
    public bool $isTotalProfitLocked = false;

    // Delete Confirmation Modal Properties
    public ?int $confirmDeleteInvestorId = null;
    public ?int $confirmDeleteTransactionId = null;

    public function confirmDeleteInvestor(int $id)
    {
        $this->confirmDeleteInvestorId = $id;
    }

    public function confirmDeleteTransaction(int $id)
    {
        $this->confirmDeleteTransactionId = $id;
    }

    public function cancelDelete()
    {
        $this->confirmDeleteInvestorId = null;
        $this->confirmDeleteTransactionId = null;
    }

    public function executeDeleteInvestor()
    {
        if ($this->confirmDeleteInvestorId) {
            $this->deleteInvestor($this->confirmDeleteInvestorId);
            $this->confirmDeleteInvestorId = null;
        }
    }

    public function executeDeleteTransaction()
    {
        if ($this->confirmDeleteTransactionId) {
            $this->deleteTransaction($this->confirmDeleteTransactionId);
            $this->confirmDeleteTransactionId = null;
        }
    }

    protected $queryString = [
        'search' => ['except' => ''],
        'typeFilter' => ['except' => 'all'],
    ];

    public function updatingSearch() { $this->resetPage(); }
    public function updatingTypeFilter() { $this->resetPage(); }
    public function updatingPerPage() { $this->resetPage(); }
    public function updatingActiveTab() { $this->resetPage(); }
    public function updatingInvestorSearch() { }

    public function mount()
    {
        $activeSeason = Setting::get('season', '২৫-২৬');
        $this->transactionDate = date('Y-m-d');
        $this->totalBusinessProfit = (string)Setting::get('total_business_profit_' . $activeSeason, session('total_business_profit', ''));
        $this->isTotalProfitLocked = (bool)Setting::get('is_total_profit_locked_' . $activeSeason, session('is_total_profit_locked', false));
    }

    public function saveTotalProfit()
    {
        $activeSeason = Setting::get('season', '২৫-২৬');
        $this->isTotalProfitLocked = true;
        Setting::set('total_business_profit_' . $activeSeason, $this->totalBusinessProfit);
        Setting::set('is_total_profit_locked_' . $activeSeason, true);
        session(['total_business_profit' => $this->totalBusinessProfit, 'is_total_profit_locked' => true]);
        $this->calculateProfitAmount();
    }

    public function editTotalProfit()
    {
        $activeSeason = Setting::get('season', '২৫-২৬');
        $this->isTotalProfitLocked = false;
        Setting::set('is_total_profit_locked_' . $activeSeason, false);
        session(['is_total_profit_locked' => false]);
    }

    public function getRunningRemainingProfitProperty(): float
    {
        $activeSeason = Setting::get('season', '২৫-২৬');
        $profit = is_numeric($this->totalBusinessProfit) ? (float)$this->totalBusinessProfit : 0;
        $totalPaid = InvestmentTransaction::where('transaction_type', 'profit_payout')
            ->where('season', $activeSeason)
            ->sum('amount');
        return max(0, $profit - $totalPaid);
    }

    public function formatMoney($val): string
    {
        if ($val === null || $val === '' || !is_numeric($val)) {
            return '০';
        }
        $num = (float)$val;
        if ($num == (int)$num) {
            $formatted = number_format($num, 0, '.', ',');
        } else {
            $formatted = number_format($num, 2, '.', ',');
            $formatted = rtrim(rtrim($formatted, '0'), '.');
        }
        return toBanglaNum($formatted);
    }

    public function isAdmin(): bool
    {
        $user = auth()->user();
        if (!$user) return false;
        return $user->role === 'admin' || (method_exists($user, 'hasRole') && $user->hasRole('admin'));
    }

    public function selectInvestor(?int $id)
    {
        $this->selectedInvestorId = $id;
        $this->investorSearch = '';
        if ($id) {
            $inv = Investor::find($id);
            if ($inv && $inv->profit_percentage > 0) {
                $this->investorProfitShare = (string)$inv->profit_percentage;
            } else {
                $this->investorProfitShare = '';
            }
            $this->calculateProfitAmount();
        }
    }

    public function selectTransactionType(string $type)
    {
        $this->transactionType = $type;
        if ($type === 'profit_payout') {
            if ($this->selectedInvestorId) {
                $inv = Investor::find($this->selectedInvestorId);
                if ($inv && $inv->profit_percentage > 0) {
                    $this->investorProfitShare = (string)$inv->profit_percentage;
                }
            }
            $this->calculateProfitAmount();
        }
    }

    public function updatedTotalBusinessProfit()
    {
        session(['total_business_profit' => $this->totalBusinessProfit]);
        $this->calculateProfitAmount();
    }

    public function updatedInvestorProfitShare()
    {
        $this->calculateProfitAmount();
    }

    public function updatedTransactionAmount()
    {
        if ($this->transactionType === 'profit_payout') {
            $payout = is_numeric($this->transactionAmount) ? (float)$this->transactionAmount : 0;
            $myProfit = max(0, $this->runningRemainingProfit - $payout);
            $this->myRemainingProfit = (string)round($myProfit, 2);
        }
    }

    public function calculateProfitAmount()
    {
        if ($this->transactionType !== 'profit_payout') {
            return;
        }

        $baseProfit = is_numeric($this->totalBusinessProfit) ? (float)$this->totalBusinessProfit : 0;
        $share = is_numeric($this->investorProfitShare) ? (float)$this->investorProfitShare : 0;

        if ($baseProfit > 0 && $share > 0) {
            $payout = ($baseProfit * $share) / 100;
            $this->transactionAmount = (string)round($payout, 2);
        }
    }

    public function selectPaymentMethod(string $method)
    {
        $this->paymentMethod = $method;
    }

    public function selectInvestorPaymentMethod(string $method)
    {
        $this->investorPaymentMethod = $method;
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
            $this->profitPercentage = $inv->profit_percentage > 0 ? (string)$inv->profit_percentage : '';
            $this->initialInvestment = '';
            $this->investorPaymentMethod = 'নগদ';
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
        $this->profitPercentage = '';
        $this->initialInvestment = '';
        $this->investorPaymentMethod = 'নগদ';
        $this->investorNotes = '';
    }

    public function saveInvestor()
    {
        $this->validate([
            'investorName' => 'required|string|max:255',
            'profitPercentage' => 'nullable|numeric|min:0|max:100',
            'initialInvestment' => 'nullable|numeric|min:0',
        ], [
            'investorName.required' => 'ইনভেস্টরের নাম দেওয়া আবশ্যক',
            'profitPercentage.numeric' => 'মুনাফার হার সঠিক সংখ্যা হতে হবে',
            'profitPercentage.min' => 'মুনাফার হার ০ বা তার বেশি হতে হবে',
            'profitPercentage.max' => 'মুনাফার হার ১০০% এর বেশি হতে পারবে না',
            'initialInvestment.numeric' => 'প্রাথমিক বিনিয়োগের পরিমাণ সঠিক সংখ্যা হতে হবে',
            'initialInvestment.min' => 'প্রাথমিক বিনিয়োগ ০ বা তার বেশি হতে হবে',
        ]);

        $profitVal = is_numeric($this->profitPercentage) ? min(100, max(0, (float)$this->profitPercentage)) : 0;
        $initialAmount = is_numeric($this->initialInvestment) ? (float)$this->initialInvestment : 0;

        DB::transaction(function() use ($profitVal, $initialAmount) {
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
                $inv = Investor::create([
                    'name' => trim($this->investorName),
                    'phone' => trim($this->investorPhone),
                    'address' => trim($this->investorAddress),
                    'profit_percentage' => $profitVal,
                    'notes' => trim($this->investorNotes),
                    'total_invested' => $initialAmount,
                    'total_repaid' => 0,
                ]);

                if ($initialAmount > 0) {
                    InvestmentTransaction::create([
                        'investor_id' => $inv->id,
                        'transaction_type' => 'deposit',
                        'amount' => $initialAmount,
                        'date' => date('Y-m-d'),
                        'payment_method' => $this->investorPaymentMethod,
                        'notes' => 'প্রাথমিক মূলধন জমা',
                    ]);
                }
                $msg = 'নতুন ইনভেস্টর সফলভাবে যুক্ত হয়েছে!';
            }
            $this->dispatch('show-toast', message: $msg, type: 'success');
        });

        $this->resetInvestorFields();
        $this->showInvestorModal = false;
        $this->activeTab = 'investors';
        $this->resetPage();
    }

    public function deleteInvestor(int $id)
    {
        if (!$this->isAdmin()) {
            $msg = 'ইনভেস্টর মোছার অনুমতি শুধুমাত্র অ্যাডমিনের আছে!';
            $this->dispatch('show-toast', message: $msg, type: 'danger');
            return;
        }

        DB::transaction(function() use ($id) {
            InvestmentTransaction::where('investor_id', $id)->delete();
            Investor::where('id', $id)->delete();
        });

        $this->dispatch('show-toast', message: 'ইনভেস্টর ও তাঁর লেনদেন রেকর্ড মুছে ফেলা হয়েছে!', type: 'success');
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
        $this->investorProfitShare = '';
        $this->myRemainingProfit = '';
        $this->investorSearch = '';

        if (empty($this->totalBusinessProfit)) {
            $this->totalBusinessProfit = (string)session('total_business_profit', '');
        }

        if ($investorId) {
            $inv = Investor::find($investorId);
            if ($inv && $inv->profit_percentage > 0) {
                $this->investorProfitShare = (string)$inv->profit_percentage;
            }
        }

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
        $this->investorProfitShare = '';
        $this->myRemainingProfit = '';
        $this->investorSearch = '';
        if (empty($this->totalBusinessProfit)) {
            $this->totalBusinessProfit = (string)session('total_business_profit', '');
        }
    }

    public function saveTransaction()
    {
        $this->validate([
            'selectedInvestorId' => 'required|exists:investors,id',
            'transactionType' => 'required|in:deposit,profit_payout,capital_return',
            'transactionAmount' => 'required|numeric|min:0.01',
            'transactionDate' => 'required|date',
            'totalBusinessProfit' => 'nullable|numeric|min:0',
            'investorProfitShare' => 'nullable|numeric|min:0|max:100',
        ], [
            'selectedInvestorId.required' => 'ইনভেস্টর নির্বাচন করা আবশ্যক',
            'selectedInvestorId.exists' => 'সঠিক ইনভেস্টর সিলেক্ট করুন',
            'transactionAmount.required' => 'টাকার পরিমাণ দেওয়া আবশ্যক',
            'transactionAmount.min' => 'টাকার পরিমাণ ০ এর বেশি হতে হবে',
            'transactionDate.required' => 'তারিখ দেওয়া আবশ্যক',
        ]);

        $investor = Investor::findOrFail($this->selectedInvestorId);
        $amount = (float)$this->transactionAmount;

        if ($this->transactionType === 'profit_payout' && !empty($this->totalBusinessProfit) && is_numeric($this->totalBusinessProfit)) {
            $availProfit = (float)$this->totalBusinessProfit;
            if ($amount > $availProfit) {
                $this->addError('transactionAmount', 'প্রদেয় লভ্যাংশের পরিমাণ অবশিষ্ট ব্যবসায়িক লাভের (৳' . number_format($availProfit, 2) . ') চেয়ে বেশি হতে পারবে না।');
                return;
            }

            // Deduct from total business profit tracking
            $remainingAfter = max(0, $availProfit - $amount);
            session(['last_total_business_profit' => $remainingAfter]);
            session(['last_profit_session_start' => now()]);
        }

        DB::transaction(function() use ($investor, $amount) {
            InvestmentTransaction::create([
                'investor_id' => $investor->id,
                'transaction_type' => $this->transactionType,
                'amount' => $amount,
                'date' => $this->transactionDate,
                'payment_method' => $this->paymentMethod,
                'notes' => trim($this->transactionNotes),
                'season' => Setting::get('season', '২৫-২৬'),
            ]);

            if ($this->transactionType === 'deposit') {
                $investor->increment('total_invested', $amount);
            } elseif (in_array($this->transactionType, ['profit_payout', 'capital_return'])) {
                $investor->increment('total_repaid', $amount);
            }
        });

        $this->resetTransactionFields();
        $this->showTransactionModal = false;
        $this->resetPage();

        $msg = 'ইনভেস্টমেন্ট লেনদেন সফলভাবে রেকর্ড করা হয়েছে!';
        $this->dispatch('show-toast', message: $msg, type: 'success');
    }

    public function deleteTransaction(int $id)
    {
        if (!$this->isAdmin()) {
            $msg = 'লেনদেন মোছার অনুমতি শুধুমাত্র অ্যাডমিনের আছে!';
            $this->dispatch('show-toast', message: $msg, type: 'danger');
            return;
        }

        DB::transaction(function() use ($id) {
            $tx = InvestmentTransaction::findOrFail($id);
            $investor = $tx->investor;

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
        $this->dispatch('show-toast', message: $msg, type: 'success');
    }

    public function render()
    {
        $activeSeason = Setting::get('season', '২৫-২৬');

        $txSeasonQuery = InvestmentTransaction::where('season', $activeSeason);

        // 3.1 Total Investment = SUM(All 'বিনিয়োগ জমা')
        $txDepositSum = (clone $txSeasonQuery)->where('transaction_type', 'deposit')->sum('amount');
        $invDepositBaseline = ($activeSeason === '২৫-২৬' && $txDepositSum == 0) ? Investor::sum('total_invested') : 0;
        $totalInvested = max($txDepositSum, $invDepositBaseline);

        // 3.2 Total Profit Paid = SUM(All 'লাভ প্রদান')
        $txProfitSum = (clone $txSeasonQuery)->where('transaction_type', 'profit_payout')->sum('amount');
        $invRepaidBaseline = ($activeSeason === '২৫-২৬' && $txProfitSum == 0) ? Investor::sum('total_repaid') : 0;
        $totalRepaid = max($txProfitSum, $invRepaidBaseline);

        // 3.3 Remaining Investment = Total Investment - SUM(All 'মূলধন ফেরত')
        $txCapitalReturnSum = (clone $txSeasonQuery)->where('transaction_type', 'capital_return')->sum('amount');
        $netBalance = max(0, $totalInvested - $txCapitalReturnSum);

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
            $query = InvestmentTransaction::with('investor')
                ->where('season', $activeSeason);

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

        $filteredInvestors = Investor::query()
            ->when(!empty($this->investorSearch), function($q) {
                $s = trim($this->investorSearch);
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%");
            })
            ->orderBy('name', 'asc')
            ->get();

        return view('livewire.investment', [
            'records' => $records,
            'totalInvested' => $totalInvested,
            'totalRepaid' => $totalRepaid,
            'netBalance' => $netBalance,
            'totalInvestorsCount' => $totalInvestorsCount,
            'allInvestors' => $allInvestors,
            'filteredInvestors' => $filteredInvestors,
            'isTotalProfitLocked' => $this->isTotalProfitLocked,
        ])->layout('layouts.app');
    }
}
