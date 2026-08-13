<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Payment;
use App\Models\Ledger;
use App\Models\Setting;
use App\Support\LedgerGroups;
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
            // View 2: Ledger / Group Detail List - STRICT filter by exact selected ledger name
            $targetNames = [$this->selectedLedger];

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

            // Get sums for summary badges according to Rules 1.1 - 1.4
            $allFilteredPayments = $query->get();

            $regBill = 0;
            $regDeduction = 0;
            $regCashPayment = 0;
            $duePaymentEntries = 0;

            foreach ($allFilteredPayments as $p) {
                $pType = trim($p->payment_type ?? '');
                $qty = floatval($p->qty);
                $total = floatval($p->total);
                $advance = floatval($p->advance);
                $deduction = floatval($p->deduction);
                $payment = floatval($p->payment);
                $rec = floatval($p->purchase_receive);

                $isAdvanceType = str_contains($pType, 'অগ্ৰিম') || str_contains($pType, 'অগ্রিম') || ($total == 0 && $advance > 0);
                $isBakiType = str_contains($pType, 'বাকি') || (!$isAdvanceType && $total == 0 && ($rec > 0 || ($payment > 0 && $qty == 0)));

                if ($isBakiType) {
                    $duePaymentEntries += max($payment, $rec);
                } elseif ($isAdvanceType) {
                    // Advance payment entry: does not reduce payment baki
                } else {
                    // Regular entry
                    $regBill += $total;
                    $regDeduction += $deduction;
                    $regCashPayment += $payment;
                }
            }

            // Rule 1.1: SUM(Advance_Amount) from pure advance entries only
            $totalAdvance = 0;
            foreach ($allFilteredPayments as $p) {
                $pType = trim($p->payment_type ?? '');
                $tot = floatval($p->total);
                $adv = floatval($p->advance);
                $isAdv = str_contains($pType, 'অগ্ৰিম') || str_contains($pType, 'অগ্রিম') || ($tot == 0 && $adv > 0);
                if ($isAdv) {
                    $totalAdvance += $adv;
                }
            }

            // Rule 1.2: SUM(Deduction_Amount)
            $totalDeduction = $allFilteredPayments->sum('deduction');
            $totalPorishodh = $totalDeduction;

            // Rule 1.3: Total_Advance - Total_Deduction
            $advanceRemaining = $totalAdvance - $totalDeduction;
            if ($advanceRemaining < 0) {
                $advanceRemaining = 0;
            }

            // Rule 1.4: (SUM(Regular_Bill) - (SUM(Regular_Deduction) + SUM(Regular_Cash_Payment))) - SUM(Due_Payment_Entries)
            $regDue = $regBill - ($regDeduction + $regCashPayment);
            $rawPaymentBaki = $regDue - $duePaymentEntries;
            $paymentBaki = $rawPaymentBaki;

            $totalPayment = 0;
            foreach ($allFilteredPayments as $p) {
                $pPay = floatval($p->payment);
                $pAdv = floatval($p->advance);
                $pRec = floatval($p->purchase_receive);
                $tot = floatval($p->total);

                if ($pPay > 0) {
                    $totalPayment += $pPay;
                } elseif ($tot == 0 && $pAdv > 0) {
                    $totalPayment += $pAdv;
                } elseif ($tot == 0 && $pRec > 0) {
                    $totalPayment += $pRec;
                }
            }
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
                'totalPorishodh' => $totalPorishodh,
                'advanceRemaining' => $advanceRemaining,
                'paymentBaki' => $paymentBaki,
                'rawPaymentBaki' => $rawPaymentBaki,
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
            // Track every ledger name that appears in a payment record (even if amounts are 0)
            $paymentKhotiyans = []; // ledger_name => group_name

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

                // Record that this ledger name appeared in a payment
                $paymentKhotiyans[$name] = $group;

                $groupedData[$group]['has_payments'] = true;
                $groupedData[$group]['total_payment'] += $payAmount;
                $groupedData[$group]['total_advance'] += $advanceAmount;
                $groupedData[$group]['total_bill'] += $billAmount;

                // Ensure this khotiyan name is seeded in the group
                if (!isset($groupedData[$group]['khotiyans'][$name])) {
                    $groupedData[$group]['khotiyans'][$name] = $initKhotiyan();
                }

                // Match with sub-khotiyans (case-insensitive)
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
                    if (!isset($groupedData[$group]['khotiyans'][$name])) {
                        $groupedData[$group]['khotiyans'][$name] = $initKhotiyan();
                    }
                    $groupedData[$group]['khotiyans'][$name]['payment'] += $payAmount;
                    $groupedData[$group]['khotiyans'][$name]['advance'] += $advanceAmount;
                    $groupedData[$group]['khotiyans'][$name]['bill'] += $billAmount;
                }
            }

            // 3. Compute net balance and total payment sum for each khotiyan and group
            foreach ($groupedData as &$gData) {
                $groupNet = 0;
                $groupTotalPayment = 0;
                $filteredKhotiyans = [];

                foreach ($gData['khotiyans'] as $kName => &$kData) {
                    $paymentSum = $kData['payment'] + $kData['advance'];
                    // A khotiyan shows if: it has ANY payment record (even amounts=0), or has financial activity
                    $hasPaymentRecord = array_key_exists($kName, $paymentKhotiyans);
                    $hasActivity = ($paymentSum != 0 || $kData['bill'] != 0);

                    if ($hasPaymentRecord || $hasActivity) {
                        $kData['net'] = ($kData['payment'] + $kData['advance']) - $kData['bill'];
                        $kData['payment_sum'] = $paymentSum;
                        $groupNet += $kData['net'];
                        $groupTotalPayment += $paymentSum;
                        $filteredKhotiyans[$kName] = $kData;
                    }
                }
                unset($kData);

                $gData['khotiyans'] = $filteredKhotiyans;
                $gData['total_net_balance'] = $groupNet;
                $gData['total_payment_sum'] = $groupTotalPayment;

                // Determine primary click ledger (prefer non-group-name item)
                $primaryName = null;
                foreach (array_keys($gData['khotiyans']) as $kName) {
                    if ($kName !== $gData['group_name']) {
                        $primaryName = $kName;
                        break;
                    }
                }
                $gData['primary_name'] = $primaryName ?? $gData['group_name'];
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
