<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Challan;
use App\Models\ChallanItem;
use App\Models\Payment;
use App\Models\LoadEntry;
use App\Models\UnloadItem;
use App\Models\Delivery;
use App\Models\CashEntry;
use App\Models\Setting;
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
            $this->modalSeason = Setting::get('season', '২৫-২৬');
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
                $startDate = Carbon::today()->subDays(6); // last 7 days inclusive
            } elseif ($this->filterPeriod === '15days') {
                $startDate = Carbon::today()->subDays(14); // last 15 days inclusive
            }
            // 'season' & 'profit_loss' show full season data without date boundaries
        }

        $activeSeason = Setting::get('season', '২৫-২৬');

        // --- 1. Challan Sales Query (grand_total > 0) ---
        $salesQuery = Challan::query()
            ->where('grand_total', '>', 0)
            ->where(function ($q) use ($activeSeason) {
                $q->where('season', $activeSeason)->orWhereNull('season');
            });

        if ($queryDate) {
            $salesQuery->whereDate('date', $queryDate);
        } elseif ($startDate) {
            $salesQuery->whereDate('date', '>=', $startDate);
        }

        $totalSalesVat      = (float) (clone $salesQuery)->sum('grand_total');
        $cashSales          = (float) (clone $salesQuery)->sum('cash');
        $dueSales           = (float) (clone $salesQuery)->sum('due');
        $totalChallanValue  = (float) (clone $salesQuery)->sum('total_value');
        $totalDiscount      = (float) (clone $salesQuery)->sum('discount');
        $totalTransportRent = (float) (clone $salesQuery)->sum('transport_rent');

        // --- 2. Due Collection Query (grand_total == 0) ---
        $dueCollectionQuery = Challan::query()
            ->where('grand_total', 0)
            ->where(function ($q) use ($activeSeason) {
                $q->where('season', $activeSeason)->orWhereNull('season');
            });

        if ($queryDate) {
            $dueCollectionQuery->whereDate('date', $queryDate);
        } elseif ($startDate) {
            $dueCollectionQuery->whereDate('date', '>=', $startDate);
        }

        $dueDeposit = (float) $dueCollectionQuery->sum('cash');

        // --- 3. Payment Query (Total Expenses) ---
        $paymentQuery = Payment::query()
            ->where(function ($q) use ($activeSeason) {
                $q->where('season', $activeSeason)->orWhereNull('season');
            });

        if ($queryDate) {
            $dmySlash = $queryDate->format('d/m/Y');
            $dmyDash  = $queryDate->format('d-m-Y');
            $ymdDash  = $queryDate->format('Y-m-d');
            $paymentQuery->where(function ($sub) use ($dmySlash, $dmyDash, $ymdDash, $queryDate) {
                $sub->where('date', $dmySlash)
                    ->orWhere('date', $dmyDash)
                    ->orWhere('date', $ymdDash)
                    ->orWhereDate('date', $queryDate)
                    ->orWhereDate('created_at', $queryDate);
            });
        } elseif ($startDate) {
            $paymentQuery->whereDate('date', '>=', $startDate);
        }

        $totalPayment = (float) (clone $paymentQuery)->sum('payment');

        // --- 4. Manual Cash Entries (CashEntry) ---
        $manualInQuery = CashEntry::query()
            ->where('is_system', false)
            ->where(function ($q) use ($activeSeason) {
                $q->where('season', $activeSeason)->orWhereNull('season');
            });

        $manualOutQuery = CashEntry::query()
            ->where('is_system', false)
            ->where(function ($q) use ($activeSeason) {
                $q->where('season', $activeSeason)->orWhereNull('season');
            });

        if ($queryDate) {
            $manualInQuery->whereDate('date', $queryDate);
            $manualOutQuery->whereDate('date', $queryDate);
        } elseif ($startDate) {
            $manualInQuery->whereDate('date', '>=', $startDate);
            $manualOutQuery->whereDate('date', '>=', $startDate);
        }

        $manualCashIn  = (float) $manualInQuery->sum('cash_in');
        $manualCashOut = (float) $manualOutQuery->sum('cash_out');

        // --- 5. Total Net Cash ---
        $netCash = ($cashSales + $dueDeposit + $manualCashIn) - ($totalPayment + $manualCashOut);

        // --- 6. Challan Category Table Data ---
        $challanItemsQuery = ChallanItem::query()
            ->join('challans', 'challan_items.challan_id', '=', 'challans.id')
            ->where('challans.grand_total', '>', 0)
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

        // --- 7. Payment Summary Table Data ---
        $paymentsQueryList = Payment::query()
            ->where(function ($q) use ($activeSeason) {
                $q->where('season', $activeSeason)->orWhereNull('season');
            })
            ->select('ledger', DB::raw('SUM(payment) as total_payment'))
            ->groupBy('ledger');

        if ($queryDate) {
            $dmySlash = $queryDate->format('d/m/Y');
            $dmyDash  = $queryDate->format('d-m-Y');
            $ymdDash  = $queryDate->format('Y-m-d');
            $paymentsQueryList->where(function ($sub) use ($dmySlash, $dmyDash, $ymdDash, $queryDate) {
                $sub->where('date', $dmySlash)
                    ->orWhere('date', $dmyDash)
                    ->orWhere('date', $ymdDash)
                    ->orWhereDate('date', $queryDate)
                    ->orWhereDate('created_at', $queryDate);
            });
        } elseif ($startDate) {
            $paymentsQueryList->whereDate('date', '>=', $startDate);
        }
        if ($this->search) {
            $paymentsQueryList->where('ledger', 'like', '%' . $this->search . '%');
        }
        $paymentSummary = $paymentsQueryList->get();

        // --- 8. Production Summary Table Data ---
        $totalLoadQty = (float) LoadEntry::query()
            ->where(function ($q) use ($activeSeason) {
                $q->where('season', $activeSeason)->orWhereNull('season');
            })
            ->when($queryDate, fn($q) => $q->whereDate('date', $queryDate))
            ->when($startDate, fn($q) => $q->whereDate('date', '>=', $startDate))
            ->sum('quantity');

        $productions = [
            ['mill' => '১ নং মেল', 'qty' => $totalLoadQty > 0 ? $totalLoadQty : $challanCategories->sum('total_qty')],
        ];

        // --- 9. Delivery Summary Table Data ---
        $deliveryQuery = Delivery::query()
            ->where(function ($q) use ($activeSeason) {
                $q->where('season', $activeSeason)->orWhereNull('season');
            })
            ->select('category_name', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('category_name');

        if ($queryDate) {
            $deliveryQuery->whereDate('delivery_date', $queryDate);
        } elseif ($startDate) {
            $deliveryQuery->whereDate('delivery_date', '>=', $startDate);
        }
        if ($this->search) {
            $deliveryQuery->where('category_name', 'like', '%' . $this->search . '%');
        }
        $deliverySummary = $deliveryQuery->get();

        // --- 10. Load Summary Table Data ---
        $loadQuery = LoadEntry::query()
            ->where(function ($q) use ($activeSeason) {
                $q->where('season', $activeSeason)->orWhereNull('season');
            })
            ->select(DB::raw("COALESCE(NULLIF(description, ''), category, 'লোডিং') as load_desc"), DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('load_desc');

        if ($queryDate) {
            $loadQuery->whereDate('date', $queryDate);
        } elseif ($startDate) {
            $loadQuery->whereDate('date', '>=', $startDate);
        }
        if ($this->search) {
            $loadQuery->where('description', 'like', '%' . $this->search . '%');
        }
        $loadSummary = $loadQuery->get();

        // --- 11. Unload Summary Table Data ---
        $unloadQuery = UnloadItem::query()
            ->join('unload_entries', 'unload_items.unload_entry_id', '=', 'unload_entries.id')
            ->where(function ($q) use ($activeSeason) {
                $q->where('unload_entries.season', $activeSeason)->orWhereNull('unload_entries.season');
            })
            ->select('unload_items.category_name', DB::raw('SUM(unload_items.quantity) as total_qty'))
            ->groupBy('unload_items.category_name');

        if ($queryDate) {
            $unloadQuery->whereDate('unload_entries.date', $queryDate);
        } elseif ($startDate) {
            $unloadQuery->whereDate('unload_entries.date', '>=', $startDate);
        }
        if ($this->search) {
            $unloadQuery->where('unload_items.category_name', 'like', '%' . $this->search . '%');
        }
        $unloadSummary = $unloadQuery->get();

        // --- 12. Profit & Loss Modal Dataset ---
        $mSeason = $this->modalSeason ?: $activeSeason;

        $mSales = (float) Challan::where('grand_total', '>', 0)
            ->where(function($q) use ($mSeason) {
                $q->where('season', $mSeason)->orWhereNull('season');
            })->sum('grand_total');

        $expenseLedgers = \App\Models\Ledger::where('group_type', 'expense')
            ->orWhere('group', 'LIKE', '%খরচ%')
            ->orWhere('name', 'LIKE', '%খরচ%')
            ->pluck('name')->toArray();

        $mExpenses = (float) Payment::where(function($q) use ($mSeason) {
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

        $mOverpayment = (float) Payment::where(function($q) use ($mSeason) {
            $q->where('season', $mSeason)->orWhereNull('season');
        })->where('purchase_receive', '<', 0)->sum('purchase_receive');

        $mNetProfitLoss = $mSales - ($mExpenses + abs($mOverpayment));

        $mDue = (float) Challan::where('grand_total', '>', 0)
            ->where(function($q) use ($mSeason) {
                $q->where('season', $mSeason)->orWhereNull('season');
            })->sum('due');

        $mOverallProfitLoss = $mNetProfitLoss - $mDue;

        $challanSeasons = Challan::whereNotNull('season')->select('season')->distinct()->pluck('season')->toArray();
        $loadSeasons    = LoadEntry::whereNotNull('season')->select('season')->distinct()->pluck('season')->toArray();
        $availableSeasons = array_unique(array_merge(['২৫-২৬', '২৪-২৫', '২৩-২৪'], $challanSeasons, $loadSeasons));
        sort($availableSeasons);

        return view('livewire.dashboard', [
            'totalSalesVat'      => $totalSalesVat,
            'cashSales'          => $cashSales,
            'dueSales'           => $dueSales,
            'totalPayment'       => $totalPayment,
            'dueDeposit'         => $dueDeposit,
            'netCash'            => $netCash,
            'totalChallanValue'  => $totalChallanValue,
            'totalDiscount'      => $totalDiscount,
            'totalTransportRent' => $totalTransportRent,
            'challanCategories'  => $challanCategories,
            'paymentSummary'     => $paymentSummary,
            'productions'        => $productions,
            'deliverySummary'    => $deliverySummary,
            'loadSummary'        => $loadSummary,
            'unloadSummary'      => $unloadSummary,
            // Modal Data
            'modalSeason'        => $mSeason,
            'mSales'             => $mSales,
            'mExpenses'          => $mExpenses,
            'mOverpayment'       => $mOverpayment,
            'mNetProfitLoss'     => $mNetProfitLoss,
            'mDue'               => $mDue,
            'mOverallProfitLoss' => $mOverallProfitLoss,
            'availableSeasons'   => array_reverse($availableSeasons),
        ])->layout('layouts.app');
    }
}
