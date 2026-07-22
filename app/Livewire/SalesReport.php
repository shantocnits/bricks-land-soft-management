<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Challan;
use App\Models\ChallanItem;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class SalesReport extends Component
{
    use WithPagination;

    public string $search = '';
    public string $dateFrom = '';
    public string $dateTo = '';
    public string $challanType = 'all'; // all, আজকের, অগ্রিম
    public string $categoryFilter = 'all';
    
    public int $perPage = 10;
    public string $chartPeriod = 'monthly'; // monthly, daily, category
    public array $chartData = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'challanType' => ['except' => 'all'],
        'categoryFilter' => ['except' => 'all'],
    ];

    public function updatingSearch() { $this->resetPage(); }
    public function updatingDateFrom() { $this->resetPage(); }
    public function updatingDateTo() { $this->resetPage(); }
    public function updatingChallanType() { $this->resetPage(); }
    public function updatingCategoryFilter() { $this->resetPage(); }
    public function updatingPerPage() { $this->resetPage(); }
    public function updatingChartPeriod() { $this->resetPage(); }

    public function resetFilters()
    {
        $this->search = '';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->challanType = 'all';
        $this->categoryFilter = 'all';
        $this->resetPage();
        $this->dispatch('reset-flatpickrs');
    }

    public function render()
    {
        // 1. Build Base Query
        $query = Challan::query();

        if (!empty($this->search)) {
            $search = trim($this->search);
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('customer_address', 'like', "%{$search}%")
                  ->orWhere('challan_no', 'like', "%{$search}%");
            });
        }

        if (!empty($this->dateFrom)) {
            $query->whereDate('date', '>=', $this->dateFrom);
        }
        if (!empty($this->dateTo)) {
            $query->whereDate('date', '<=', $this->dateTo);
        }

        if ($this->challanType !== 'all') {
            $query->where('challan_type', $this->challanType);
        }

        if ($this->categoryFilter !== 'all') {
            $query->whereHas('items', function ($q) {
                $q->where('category_name', $this->categoryFilter);
            });
        }

        // Summary Calculations for filtered set
        $summaryQuery = clone $query;
        $totalGrand = $summaryQuery->sum('grand_total');
        $totalPaid  = $summaryQuery->sum('cash');
        $totalDue   = $summaryQuery->sum('due');

        // Total brick quantity sold for filtered challans
        $filteredIds = (clone $query)->pluck('id');
        $totalQuantity = ChallanItem::whereIn('challan_id', $filteredIds)->sum('quantity');

        // Chart Data Generation using clean base query
        $this->chartData = $this->getChartData(clone $query);

        // Paginated results with eager loading
        $challans = (clone $query)->with('items')
                          ->orderBy('date', 'desc')
                          ->orderBy('id', 'desc')
                          ->paginate($this->perPage);

        // Brick Categories for filter
        $categoryNames = ['১ নং', 'পিকেট', '২ নং (ক)', '২ নং (খ)', '৩ নং গরিয়া', '৩ নং ছালট', 'এলোট', '3 no it'];
        $whenClauses = implode(' ', array_map(fn($i) => "WHEN name = ? THEN $i", array_keys($categoryNames)));
        $categories = Category::whereIn('name', $categoryNames)
            ->orderByRaw("CASE {$whenClauses} ELSE 999 END", array_values($categoryNames))
            ->get();

        return view('livewire.sales-report', [
            'challans'      => $challans,
            'totalGrand'    => $totalGrand,
            'totalPaid'     => $totalPaid,
            'totalDue'      => $totalDue,
            'totalQuantity' => $totalQuantity,
            'categories'    => $categories,
        ])->layout('layouts.app');
    }

    private function getChartData($baseQuery): array
    {
        $base = (clone $baseQuery)->reorder();

        if ($this->chartPeriod === 'category') {
            $challanIds = (clone $base)->pluck('id');
            $items = DB::table('challan_items')
                ->whereIn('challan_id', $challanIds)
                ->when($this->categoryFilter !== 'all', function($q) {
                    $q->where('category_name', $this->categoryFilter);
                })
                ->select('category_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(amount) as total_amount'))
                ->groupBy('category_name')
                ->get();

            return [
                'type'    => 'category',
                'labels'  => $items->pluck('category_name')->toArray(),
                'series'  => $items->pluck('total_qty')->map(fn($v) => (float)$v)->toArray(),
                'amounts' => $items->pluck('total_amount')->map(fn($v) => (float)$v)->toArray(),
            ];
        }

        if ($this->chartPeriod === 'daily') {
            if ($this->categoryFilter !== 'all') {
                $challanIds = (clone $base)->pluck('id');
                $daily = DB::table('challan_items')
                    ->join('challans', 'challan_items.challan_id', '=', 'challans.id')
                    ->whereIn('challan_items.challan_id', $challanIds)
                    ->where('challan_items.category_name', $this->categoryFilter)
                    ->select(
                        DB::raw('DATE(challans.date) as sale_date'),
                        DB::raw('SUM(challan_items.amount) as total_sales'),
                        DB::raw('SUM(challan_items.amount) as total_cash'),
                        DB::raw('0 as total_due')
                    )
                    ->groupBy('sale_date')
                    ->orderBy('sale_date', 'asc')
                    ->get();
            } else {
                $daily = (clone $base)
                    ->select(
                        DB::raw('DATE(date) as sale_date'),
                        DB::raw('SUM(grand_total) as total_sales'),
                        DB::raw('SUM(cash) as total_cash'),
                        DB::raw('SUM(due) as total_due')
                    )
                    ->groupBy('sale_date')
                    ->orderBy('sale_date', 'asc')
                    ->get();
            }

            return [
                'type'   => 'daily',
                'labels' => $daily->map(fn($d) => $d->sale_date ? \Carbon\Carbon::parse($d->sale_date)->format('d M') : '—')->toArray(),
                'series' => $daily->pluck('total_sales')->map(fn($v) => (float)$v)->toArray(),
                'cash'   => $daily->pluck('total_cash')->map(fn($v) => (float)$v)->toArray(),
                'due'    => $daily->pluck('total_due')->map(fn($v) => (float)$v)->toArray(),
            ];
        }

        // Default: Monthly sales
        $driver = DB::connection()->getDriverName();
        $dateFormat = ($driver === 'sqlite') ? "strftime('%Y-%m', date)" : "DATE_FORMAT(date, '%Y-%m')";

        if ($this->categoryFilter !== 'all') {
            $challanIds = (clone $base)->pluck('id');
            $dateFormatItem = ($driver === 'sqlite') ? "strftime('%Y-%m', challans.date)" : "DATE_FORMAT(challans.date, '%Y-%m')";

            $monthly = DB::table('challan_items')
                ->join('challans', 'challan_items.challan_id', '=', 'challans.id')
                ->whereIn('challan_items.challan_id', $challanIds)
                ->where('challan_items.category_name', $this->categoryFilter)
                ->select(
                    DB::raw("{$dateFormatItem} as month_year"),
                    DB::raw('SUM(challan_items.amount) as total_sales'),
                    DB::raw('SUM(challan_items.amount) as total_cash'),
                    DB::raw('0 as total_due')
                )
                ->groupBy('month_year')
                ->orderBy('month_year', 'asc')
                ->get();
        } else {
            $monthly = (clone $base)
                ->select(
                    DB::raw("{$dateFormat} as month_year"),
                    DB::raw('SUM(grand_total) as total_sales'),
                    DB::raw('SUM(cash) as total_cash'),
                    DB::raw('SUM(due) as total_due')
                )
                ->groupBy('month_year')
                ->orderBy('month_year', 'asc')
                ->get();
        }

        return [
            'type'   => 'monthly',
            'labels' => $monthly->map(function($m) {
                try {
                    return \Carbon\Carbon::createFromFormat('Y-m', $m->month_year)->format('M Y');
                } catch (\Exception $e) {
                    return $m->month_year ?: '—';
                }
            })->toArray(),
            'series' => $monthly->pluck('total_sales')->map(fn($v) => (float)$v)->toArray(),
            'cash'   => $monthly->pluck('total_cash')->map(fn($v) => (float)$v)->toArray(),
            'due'    => $monthly->pluck('total_due')->map(fn($v) => (float)$v)->toArray(),
        ];
    }
}
