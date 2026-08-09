<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Payment;
use App\Models\Ledger;
use App\Models\Setting;
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
        $activeSeason = Setting::get('season', '২৫-২৬');

        if ($this->selectedLedger) {
            // View 2: Ledger / Group Detail List
            $groupsJson = Setting::get('ledger_groups');
            $savedGroups = $groupsJson ? (json_decode($groupsJson, true) ?: []) : [];
            $dbGroups = Ledger::pluck('group')->filter()->unique()->toArray();
            $defaultGroups = ['কাস্টমার', 'সরবরাহকারী', 'লেবার', 'মাটি', 'স্টাফ', 'খরচ', 'আয়', 'অন্যান্য'];
            $allGroups = array_values(array_filter(array_unique(array_merge($savedGroups, $dbGroups, $defaultGroups)), fn($g) => trim($g) !== ''));

            $isGroup = in_array($this->selectedLedger, $allGroups);
            $targetNames = [$this->selectedLedger];

            if ($isGroup) {
                // Viewing Group fallback item: show ALL payments under this group (group name + all sub-khotiyans)
                $subNames = Ledger::where('group', $this->selectedLedger)->pluck('name')->toArray();
                $targetNames = array_values(array_unique(array_merge([$this->selectedLedger], $subNames)));
            } else {
                // Viewing a specific sub-khotiyan: STRICT filter — only exact ledger name matches
                $targetNames = [$this->selectedLedger];
            }

            $query = Payment::whereIn('ledger', $targetNames)
                ->where(function ($q) use ($activeSeason) {
                    $q->where('season', $activeSeason)
                      ->orWhereNull('season');
                });

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
            $totalDeduction = $allFilteredPayments->sum('deduction');

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
                'allPayments' => $allFilteredPayments,
                'totalAdvance' => $totalAdvance,
                'totalPayment' => $totalPayment,
                'totalQty' => $totalQty,
                'totalBill' => $totalBill,
                'totalDeduction' => $totalDeduction,
                'count' => $allFilteredPayments->count()
            ])->layout('layouts.app');
        } else {
            // View 1: Dynamic Group Cards Dashboard with Fallback Handling
            $dbLedgers = Ledger::all();
            $ledgerGroupMap = [];
            foreach ($dbLedgers as $l) {
                $ledgerGroupMap[mb_strtolower(trim($l->name))] = $l->group;
            }

            $groupsJson = Setting::get('ledger_groups');
            $savedGroups = $groupsJson ? (json_decode($groupsJson, true) ?: []) : [];
            $dbGroups = $dbLedgers->pluck('group')->filter()->unique()->toArray();
            $defaultGroups = ['কাস্টমার', 'সরবরাহকারী', 'লেবার', 'মাটি', 'স্টাফ', 'খরচ', 'আয়', 'অন্যান্য'];
            $allGroups = array_values(array_filter(array_unique(array_merge($savedGroups, $dbGroups, $defaultGroups)), fn($g) => trim($g) !== ''));

            $payments = Payment::where(function ($q) use ($activeSeason) {
                $q->where('season', $activeSeason)
                  ->orWhereNull('season');
            })->get();

            $groupedData = [];
            $initKhotiyan = fn() => ['payment' => 0, 'advance' => 0, 'bill' => 0, 'net' => 0];
            $initGroup = fn($g) => [
                'group_name' => $g,
                'total_payment' => 0,
                'total_advance' => 0,
                'total_bill' => 0,
                'total_net_balance' => 0,
                'khotiyans' => [$g => $initKhotiyan()],
                'direct_payment' => 0,
                'has_payments' => false
            ];

            // 1. Initialize defined groups — always seed group name itself as first (fallback) khotiyan item
            foreach ($allGroups as $g) {
                $groupedData[$g] = $initGroup($g);
            }

            foreach ($dbLedgers as $l) {
                $g = trim($l->group) ?: 'অন্যান্য';
                if (!isset($groupedData[$g])) {
                    $groupedData[$g] = $initGroup($g);
                }
                if (!isset($groupedData[$g]['khotiyans'][$l->name])) {
                    $groupedData[$g]['khotiyans'][$l->name] = $initKhotiyan();
                }
            }

            // 2. Map payments to Group and Sub-Khotiyans with Dynamic Fallback
            foreach ($payments as $p) {
                $name = trim($p->ledger);
                $lowerName = mb_strtolower($name);
                $payAmount = (float)$p->payment;
                $advanceAmount = (float)$p->advance;
                $billAmount = (float)$p->total;

                $group = $ledgerGroupMap[$lowerName] ?? (in_array($name, $allGroups) ? $name : 'অন্যান্য');

                if (!isset($groupedData[$group])) {
                    $groupedData[$group] = $initGroup($group);
                }

                $groupedData[$group]['has_payments'] = true;
                $groupedData[$group]['total_payment'] += $payAmount;
                $groupedData[$group]['total_advance'] += $advanceAmount;
                $groupedData[$group]['total_bill'] += $billAmount;

                // Match with sub-khotiyans
                $matchedKey = null;
                foreach (array_keys($groupedData[$group]['khotiyans']) as $kKey) {
                    if (mb_strtolower($kKey) === $lowerName) {
                        $matchedKey = $kKey;
                        break;
                    }
                }

                if ($matchedKey) {
                    $groupedData[$group]['khotiyans'][$matchedKey]['payment'] += $payAmount;
                    $groupedData[$group]['khotiyans'][$matchedKey]['advance'] += $advanceAmount;
                    $groupedData[$group]['khotiyans'][$matchedKey]['bill'] += $billAmount;
                } else {
                    $groupedData[$group]['direct_payment'] += $payAmount;
                    if (!empty($groupedData[$group]['khotiyans'])) {
                        if (!isset($groupedData[$group]['khotiyans'][$name])) {
                            $groupedData[$group]['khotiyans'][$name] = $initKhotiyan();
                        }
                        $groupedData[$group]['khotiyans'][$name]['payment'] += $payAmount;
                        $groupedData[$group]['khotiyans'][$name]['advance'] += $advanceAmount;
                        $groupedData[$group]['khotiyans'][$name]['bill'] += $billAmount;
                    } else {
                        $groupedData[$group]['khotiyans'][$name] = ['payment' => $payAmount, 'advance' => $advanceAmount, 'bill' => $billAmount, 'net' => 0];
                    }
                }
            }

            // 3. Compute net balance for each khotiyan and group: net = (payment + advance) - bill
            foreach ($groupedData as &$gData) {
                $groupNet = 0;
                $primaryName = null;
                foreach ($gData['khotiyans'] as $kName => &$kData) {
                    $kData['net'] = ($kData['payment'] + $kData['advance']) - $kData['bill'];
                    $groupNet += $kData['net'];
                    if ($primaryName === null && $kName !== $gData['group_name']) {
                        $primaryName = $kName;
                    }
                }
                unset($kData);
                $gData['total_net_balance'] = $groupNet;
                $gData['primary_name'] = $primaryName;
            }
            unset($gData);

            // Show only groups that have at least one payment/transaction in the active season
            $groupedData = array_filter($groupedData, fn($gData) => !empty($gData['has_payments']));

            // Filter out empty groups if search is performed
            if (!empty($this->search)) {
                $term = mb_strtolower(trim($this->search));
                $filteredGroups = [];
                foreach ($groupedData as $gName => $gData) {
                    $matchesGroup = str_contains(mb_strtolower($gName), $term);
                    $matchingKhotiyans = [];
                    foreach ($gData['khotiyans'] as $kName => $kData) {
                        if ($matchesGroup || str_contains(mb_strtolower($kName), $term)) {
                            $matchingKhotiyans[$kName] = $kData;
                        }
                    }
                    if ($matchesGroup || !empty($matchingKhotiyans)) {
                        $gData['khotiyans'] = $matchingKhotiyans;
                        $filteredGroups[$gName] = $gData;
                    }
                }
                $groupedData = $filteredGroups;
            }

            return view('livewire.khotian', [
                'isDetail' => false,
                'groupedData' => $groupedData,
                'count' => count($groupedData)
            ])->layout('layouts.app');
        }
    }
}
