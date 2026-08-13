<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Challan;
use App\Models\ChallanItem;
use App\Models\Payment;
use App\Models\LoadEntry;
use App\Models\UnloadItem;
use App\Models\UnloadEntry;
use App\Models\Delivery;
use App\Models\CashEntry;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public string $search = '';
    public string $filterPeriod = 'today'; // 'today', '7days', '15days', 'season', 'profit_loss'
    public string $dateFilter = '';

    public bool $showProfitLossModal = false;
    public string $modalSeason = '';

    public function setPeriod(string $period): void
    {
        $this->filterPeriod = $period;
        $this->dateFilter = '';
        if ($period === 'profit_loss') {
            $this->openProfitLossModal();
        }
    }

    public function openProfitLossModal(): void
    {
        if (empty($this->modalSeason)) {
            $this->modalSeason = \App\Models\Setting::get('season', '২৫-২৬');
        }
        $this->showProfitLossModal = true;
    }

    public function closeProfitLossModal(): void
    {
        $this->showProfitLossModal = false;
    }

    public function setModalSeason(string $season): void
    {
        $this->modalSeason = $season;
    }

    public function updatedDateFilter(): void
    {
        if ($this->dateFilter) {
            $this->filterPeriod = '';
        }
    }

    public function render()
    {
        $queryDate = null;
        $startDate = null;

        if ($this->dateFilter) {
            $queryDate = Carbon::parse($this->dateFilter);
        } else {
            if ($this->filterPeriod === 'today') {
                $queryDate = Carbon::today();
            } elseif ($this->filterPeriod === '7days') {
                $startDate = Carbon::today()->subDays(6);
            } elseif ($this->filterPeriod === '15days') {
                $startDate = Carbon::today()->subDays(14);
            }
            // 'season' & 'profit_loss' show full season data
        }

        $activeSeason = \App\Models\Setting::get('season', '২৫-২৬');

        // 1. Challan Summary Cards
        $challanQuery = Challan::query()->where(function ($q) use ($activeSeason) {
            $q->where('season', $activeSeason)->orWhereNull('season');
        });
        if ($queryDate) {
            $challanQuery->whereDate('date', $queryDate);
        } elseif ($startDate) {
            $challanQuery->whereDate('date', '>=', $startDate);
        }

        $totalSalesVat = $challanQuery->sum('grand_total');
        $cashSales = $challanQuery->sum('cash');
        $dueSales = $challanQuery->sum('due');
        $totalChallanValue = $challanQuery->sum('total_value');
        $totalDiscount = $challanQuery->sum('discount');
        $totalTransportRent = $challanQuery->sum('transport_rent');

        // 2. Payment Summary Card & Table
        $paymentQuery = Payment::query()->where(function ($q) use ($activeSeason) {
            $q->where('season', $activeSeason)->orWhereNull('season');
        });
        if ($queryDate) {
            $paymentQuery->whereDate('date', $queryDate);
        } elseif ($startDate) {
            $paymentQuery->whereDate('date', '>=', $startDate);
        }
        $totalPayment = $paymentQuery->sum('payment');

        // 3. Due Collection / Remaining Baki
        $dueDeposit = max(0, $dueSales - $totalPayment);

        // 4. Cash Summary
        $netCash = $cashSales + $totalPayment;

        // 5. Challan Category Table Data
        $challanItemsQuery = ChallanItem::query()
            ->join('challans', 'challan_items.challan_id', '=', 'challans.id')
            ->where(function ($q) use ($activeSeason) {
                $q->where('challans.season', $activeSeason)->orWhereNull('challans.season');
            })
            ->select(
                'challan_items.category_name',
                DB::raw('COUNT(DISTINCT challans.id) as total_challan'),
                DB::raw('SUM(challan_items.quantity) as total_qty'),
                DB::raw('SUM(challan_items.amount) as total_amount')
            );
        if ($queryDate) {
            $challanItemsQuery->whereDate('challans.date', $queryDate);
        } elseif ($startDate) {
            $challanItemsQuery->whereDate('challans.date', '>=', $startDate);
        }
        if ($this->search) {
            $challanItemsQuery->where('challan_items.category_name', 'like', '%' . $this->search . '%');
        }
        $challanCategories = $challanItemsQuery->groupBy('challan_items.category_name')->get();

        // 6. Payment Table Data
        $paymentsQueryList = Payment::query()
            ->select('ledger', 'desc', DB::raw('SUM(payment) as total_payment'))
            ->groupBy('ledger', 'desc');
        if ($queryDate) {
            $paymentsQueryList->whereDate('date', $queryDate);
        } elseif ($startDate) {
            $paymentsQueryList->whereDate('date', '>=', $startDate);
        }
        if ($this->search) {
            $paymentsQueryList->where(function ($q) {
                $q->where('ledger', 'like', '%' . $this->search . '%')
                    ->orWhere('desc', 'like', '%' . $this->search . '%');
            });
        }
        $paymentSummary = $paymentsQueryList->get();

        // 7. Production Table Data
        $totalChallanQty = $challanCategories->sum('total_qty');
        $productions = [
            ['mill' => '১ নং মেল', 'qty' => $totalChallanQty],
        ];

        // 8. Delivery Table Data
        $deliveryQuery = Delivery::query()
            ->select('category_name', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('category_name');
        if ($queryDate) {
            $deliveryQuery->whereDate('delivery_date', $queryDate);
        } elseif ($startDate) {
            $deliveryQuery->whereDate('delivery_date', '>=', $startDate);
        }
        $deliverySummary = $deliveryQuery->get();

        // 9. Load Table Data
        $loadQuery = LoadEntry::query()
            ->select('description', 'category', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('description', 'category');
        if ($queryDate) {
            $loadQuery->whereDate('date', $queryDate);
        } elseif ($startDate) {
            $loadQuery->whereDate('date', '>=', $startDate);
        }
        $loadSummary = $loadQuery->get();

        // 10. Unload Table Data
        $unloadQuery = UnloadItem::query()
            ->join('unload_entries', 'unload_items.unload_entry_id', '=', 'unload_entries.id')
            ->select('unload_items.category_name', DB::raw('SUM(unload_items.quantity) as total_qty'))
            ->groupBy('unload_items.category_name');
        if ($queryDate) {
            $unloadQuery->whereDate('unload_entries.date', $queryDate);
        } elseif ($startDate) {
            $unloadQuery->whereDate('unload_entries.date', '>=', $startDate);
        }
        $unloadSummary = $unloadQuery->get();

        // 11. Profit & Loss Modal Dataset for selected $modalSeason
        $mSeason = $this->modalSeason ?: $activeSeason;

        $mSales = Challan::where(function($q) use ($mSeason) {
            $q->where('season', $mSeason)->orWhereNull('season');
        })->sum('grand_total');

        $expenseLedgers = \App\Models\Ledger::where('group_type', 'expense')
            ->orWhere('group', 'LIKE', '%খরচ%')
            ->orWhere('name', 'LIKE', '%খরচ%')
            ->pluck('name')->toArray();

        $mExpenses = Payment::where(function($q) use ($mSeason) {
            $q->where('season', $mSeason)->orWhereNull('season');
        })
        ->where(function($q) use ($expenseLedgers) {
            if (!empty($expenseLedgers)) {
                $q->whereIn('ledger', $expenseLedgers);
            } else {
                $q->where('payment', '>', 0);
            }
        })
        ->sum('payment');

        $mOverpayment = Payment::where(function($q) use ($mSeason) {
            $q->where('season', $mSeason)->orWhereNull('season');
        })->where('purchase_receive', '<', 0)->sum('purchase_receive');

        $mNetProfitLoss = $mSales - ($mExpenses + abs($mOverpayment));

        $mDue = Challan::where(function($q) use ($mSeason) {
            $q->where('season', $mSeason)->orWhereNull('season');
        })->sum('due');

        $mOverallProfitLoss = $mNetProfitLoss - $mDue;

        $challanSeasons = Challan::whereNotNull('season')->select('season')->distinct()->pluck('season')->toArray();
        $loadSeasons = LoadEntry::whereNotNull('season')->select('season')->distinct()->pluck('season')->toArray();
        $availableSeasons = array_unique(array_merge(['২৫-২৬', '২৪-২৫', '২৩-২৪'], $challanSeasons, $loadSeasons));
        sort($availableSeasons);

        return view('livewire.dashboard', [
            'totalSalesVat' => $totalSalesVat,
            'cashSales' => $cashSales,
            'dueSales' => $dueSales,
            'totalPayment' => $totalPayment,
            'dueDeposit' => $dueDeposit,
            'netCash' => $netCash,
            'totalChallanValue' => $totalChallanValue,
            'totalDiscount' => $totalDiscount,
            'totalTransportRent' => $totalTransportRent,
            'challanCategories' => $challanCategories,
            'paymentSummary' => $paymentSummary,
            'productions' => $productions,
            'deliverySummary' => $deliverySummary,
            'loadSummary' => $loadSummary,
            'unloadSummary' => $unloadSummary,
            // Modal Data
            'modalSeason' => $mSeason,
            'mSales' => $mSales,
            'mExpenses' => $mExpenses,
            'mOverpayment' => $mOverpayment,
            'mNetProfitLoss' => $mNetProfitLoss,
            'mDue' => $mDue,
            'mOverallProfitLoss' => $mOverallProfitLoss,
            'availableSeasons' => array_reverse($availableSeasons),
        ])->layout('layouts.app');
    }
}
