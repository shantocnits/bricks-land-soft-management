<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class Khotian extends Component
{
    use WithPagination;

    // Search and Filters
    public string $search = '';
    public ?string $selectedLedger = null;
    public string $startDate = '';
    public string $endDate = '';
    public int $perPage = 15;

    protected $queryString = [
        'selectedLedger' => ['except' => null],
        'search' => ['except' => ''],
        'startDate' => ['except' => ''],
        'endDate' => ['except' => ''],
    ];

    public function selectLedger($ledger)
    {
        $this->selectedLedger = $ledger;
        $this->resetPage();
    }

    public function goBack()
    {
        $this->selectedLedger = null;
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['startDate', 'endDate', 'search']);
        $this->resetPage();
    }

    public function render()
    {
        $isSqlite = DB::connection()->getDriverName() === 'sqlite';

        if ($this->selectedLedger) {
            // View 2: Ledger Detail List
            $query = Payment::where('ledger', $this->selectedLedger);

            if (!empty($this->startDate)) {
                if ($isSqlite) {
                    $query->whereRaw("substr(date, 7, 4) || '-' || substr(date, 4, 2) || '-' || substr(date, 1, 2) >= ?", [$this->startDate]);
                } else {
                    $query->whereRaw("STR_TO_DATE(date, '%d/%m/%Y') >= ?", [$this->startDate]);
                }
            }
            if (!empty($this->endDate)) {
                if ($isSqlite) {
                    $query->whereRaw("substr(date, 7, 4) || '-' || substr(date, 4, 2) || '-' || substr(date, 1, 2) <= ?", [$this->endDate]);
                } else {
                    $query->whereRaw("STR_TO_DATE(date, '%d/%m/%Y') <= ?", [$this->endDate]);
                }
            }

            // Get sums for summary badges
            $allFilteredPayments = $query->get();
            $totalAdvance = $allFilteredPayments->sum('advance');
            $totalPayment = $allFilteredPayments->sum('payment');
            $totalQty = $allFilteredPayments->sum('qty');
            $totalBill = $allFilteredPayments->sum('total');

            // Paginated list
            if ($isSqlite) {
                $query->orderByRaw("substr(date, 7, 4) || '-' || substr(date, 4, 2) || '-' || substr(date, 1, 2) DESC");
            } else {
                $query->orderByRaw("STR_TO_DATE(date, '%d/%m/%Y') DESC");
            }

            $payments = $query->orderBy('created_at', 'desc')->paginate($this->perPage);

            return view('livewire.khotian', [
                'isDetail' => true,
                'payments' => $payments,
                'totalAdvance' => $totalAdvance,
                'totalPayment' => $totalPayment,
                'totalQty' => $totalQty,
                'totalBill' => $totalBill,
                'count' => $allFilteredPayments->count()
            ])->layout('layouts.app');
        } else {
            // View 1: Group Box Dashboard
            $query = Payment::select('ledger', DB::raw('SUM(payment) as total_payment'))
                ->groupBy('ledger');

            if (!empty($this->search)) {
                $query->where('ledger', 'like', '%' . $this->search . '%');
            }

            $ledgersData = $query->get();

            return view('livewire.khotian', [
                'isDetail' => false,
                'ledgersData' => $ledgersData,
                'count' => $ledgersData->count()
            ])->layout('layouts.app');
        }
    }
}
