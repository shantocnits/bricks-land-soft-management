<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Ledger;
use App\Models\Setting;
use App\Models\Payment;
use App\Models\StockAdjustment;
use App\Support\LedgerGroups;
use Illuminate\Support\Facades\File;

class PaymentKhata extends Component
{
    use WithFileUploads;

    // Search and Filters
    public string $search = '';
    public string $dateFilter = '';
    public int $perPage = 30;

    // Modal Visibility
    public bool $showPaymentModal = false;
    public bool $showReportModal = false;

    // Delete confirmation
    public ?int $confirmingDeleteId = null;

    // Form inputs (New Payment / Edit Payment)
    public ?int $editingId = null;
    public string $selectedLedger = '';
    public string $paymentType = '';
    public string $paymentDesc = '';
    public string $paymentDate = '';
    public $quantity = '';
    public $rate = '';
    public $divisor = 1;
    public $totalBill = '';
    public $advance = '';
    public $deduction = '';
    public $paymentAmount = '';
    public $purchaseReceive = '';
    public $documentFile = null;

    // Custom List variables
    public array $ledgerGroups = [];

    // Khotiyan Modal Search
    public string $khotiyanSearch = '';
    public bool $showKhotiyanModal = false;

    // Quick Add Ledger Modal
    public bool $showQuickAddLedgerModal = false;
    public string $quickLedgerSerial = '';
    public string $quickLedgerName = '';
    public string $quickLedgerGroup = '';
    public string $quickLedgerPaymentType = 'production';

    // Report tab state
    public string $reportTab = 'date'; // 'date' or 'all'

    // Payment History List
    public array $paymentsList = [];

    // Active Season
    public string $activeSeason = '';

    public function mount()
    {
        $this->activeSeason = Setting::get('season', '২৫-২৬');
        $this->dateFilter = now()->format('Y-m-d');
        $this->paymentDate = now()->format('Y-m-d');

        // Load ledger groups — only active groups
        $this->ledgerGroups = $this->syncLedgerGroups();

        // Load payments list from database filtered by active season
        $this->loadPaymentsList();
    }

    /**
     * Return only active groups for the payment form dropdown and khotiyan modal.
     */
    public function syncLedgerGroups(): array
    {
        return LedgerGroups::all(true, false);
    }

    public function openQuickAddLedgerModal()
    {
        $maxSerial = (int) (Ledger::max('serial') ?: Ledger::count());
        $this->quickLedgerSerial = (string) ($maxSerial + 1);
        $this->quickLedgerName = '';
        $this->quickLedgerGroup = '';
        $this->quickLedgerPaymentType = 'production';
        $this->showQuickAddLedgerModal = true;
    }

    public function resetQuickAddLedgerForm()
    {
        $maxSerial = (int) (Ledger::max('serial') ?: Ledger::count());
        $this->quickLedgerSerial = (string) ($maxSerial + 1);
        $this->quickLedgerName = '';
        $this->quickLedgerGroup = '';
        $this->quickLedgerPaymentType = 'production';
    }

    public function saveQuickLedger()
    {
        if (auth()->user()->hasRole('demo')) {
            $this->dispatch('show-toast', message: 'ডেমো মোডে খতিয়ান যোগ করা সম্ভব নয়।', type: 'danger');
            $this->showQuickAddLedgerModal = false;
            return;
        }

        $this->validate([
            'quickLedgerGroup' => 'required|string',
            'quickLedgerName'  => 'nullable|string|max:255',
        ], [
            'quickLedgerGroup.required' => 'খতিয়ানের গ্রুপ নির্বাচন করুন।',
        ]);

        $group = trim($this->quickLedgerGroup);
        $name = trim($this->quickLedgerName);

        if ($name !== '') {
            $existing = Ledger::where('name', $name)->where('group', $group)->first();
            if ($existing) {
                $existing->update([
                    'serial' => $this->quickLedgerSerial ? intval($this->quickLedgerSerial) : $existing->serial,
                    'is_active' => true,
                ]);
            } else {
                $maxSerial = (int) (Ledger::max('serial') ?: Ledger::count());
                $newSerial = $this->quickLedgerSerial ? intval($this->quickLedgerSerial) : ($maxSerial + 1);

                Ledger::create([
                    'serial' => $newSerial,
                    'name' => $name,
                    'group' => $group,
                    'group_type' => in_array($this->quickLedgerPaymentType, ['production', 'expense', 'income', 'other'])
                        ? $this->quickLedgerPaymentType
                        : 'other',
                    'divisor' => 1,
                    'is_active' => true,
                ]);
            }
            $this->dispatch('show-toast', message: 'নতুন খতিয়ান তৈরি করা হয়েছে।', type: 'success');
        } else {
            $this->dispatch('show-toast', message: 'খতিয়ান গ্রুপ নির্বাচন করা হয়েছে।', type: 'success');
        }

        $this->showQuickAddLedgerModal = false;
        $this->resetQuickAddLedgerForm();

        // Refresh ledger groups list
        $this->ledgerGroups = $this->syncLedgerGroups();
    }

    public function loadPaymentsList()
    {
        $this->activeSeason = Setting::get('season', '২৫-২৬');
        $payments = Payment::where(function ($query) {
            $query->where('season', $this->activeSeason)
                  ->orWhereNull('season');
        })->orderBy('id', 'desc')->get()->toArray();

        // Build ledger → group map from DB for fast lookup
        $ledgerGroupMap = Ledger::whereNotNull('name')
            ->whereNotNull('group')
            ->pluck('group', 'name')
            ->toArray();

        $allKnownGroups = LedgerGroups::all(true, true);
        $groupLowerMap = [];
        foreach ($allKnownGroups as $g) {
            $groupLowerMap[mb_strtolower(trim($g))] = trim($g);
        }

        // Inject group field and fix payment column mapping for advance/baki payments
        $this->paymentsList = array_map(function ($pay) use ($ledgerGroupMap, $groupLowerMap) {
            $ledgerName = trim($pay['ledger'] ?? '');
            $lowerLedger = mb_strtolower($ledgerName);

            if (!empty($ledgerGroupMap[$ledgerName])) {
                $pay['group'] = $ledgerGroupMap[$ledgerName];
            } elseif (isset($groupLowerMap[$lowerLedger])) {
                // If payment was made directly to a group name, display the group name
                $pay['group'] = $groupLowerMap[$lowerLedger];
            } else {
                $pay['group'] = '';
            }

            // Fix for baki paid entries: If total == 0, purchase_receive > 0, transfer to payment column and zero out purchase_receive
            if (floatval($pay['total'] ?? 0) == 0 && floatval($pay['purchase_receive'] ?? 0) > 0 && floatval($pay['qty'] ?? 0) == 0) {
                if (floatval($pay['payment'] ?? 0) == 0) {
                    $pay['payment'] = floatval($pay['purchase_receive']);
                }
                $pay['purchase_receive'] = 0;
            }

            // Fix for advance entries: Ensure payment column includes advance paid amount if payment is 0
            if (floatval($pay['payment'] ?? 0) == 0 && floatval($pay['advance'] ?? 0) > 0) {
                $pay['payment'] = floatval($pay['advance']);
            }

            return $pay;
        }, $payments);
    }

    // Listen for season changes from topbar
    public function updatedActiveSeason()
    {
        $this->loadPaymentsList();
    }

    public function updatedQuantity()
    {
        $this->calculateTotalBill();
    }

    public function updatedRate()
    {
        $this->calculateTotalBill();
    }

    public function updatedTotalBill()
    {
        $this->recalculatePaymentAndDiff(true);
    }

    public function updatedDeduction()
    {
        $this->recalculatePaymentAndDiff(true);
    }

    public function updatedPaymentAmount()
    {
        $this->recalculatePaymentAndDiff(false);
    }

    public function calculateTotalBill()
    {
        $q = floatval($this->quantity ?: 0);
        $r = floatval($this->rate ?: 0);
        $d = floatval($this->divisor ?: 1);
        if ($d <= 0) {
            $d = 1;
        }

        if ($q > 0 || $r > 0) {
            $calc = ($q / $d) * $r;
            $this->totalBill = $calc > 0 ? $calc : '';
        }

        $this->recalculatePaymentAndDiff(true);
    }

    public function recalculatePaymentAndDiff($autoPayment = true)
    {
        $total = floatval($this->totalBill ?: 0);
        $ded = floatval($this->deduction ?: 0);
        $payAmount = floatval($this->paymentAmount ?: 0);

        if ($total <= 0 && $ded <= 0 && $payAmount <= 0) {
            if ($autoPayment) {
                $this->paymentAmount = '';
            }
            $this->purchaseReceive = '';
            return;
        }

        if ($autoPayment) {
            $pay = $total - $ded;
            $this->paymentAmount = $pay > 0 ? $pay : ($total > 0 ? 0 : '');
        }

        $pay = floatval($this->paymentAmount ?: 0);
        $diff = ($total - $ded) - $pay;
        if ($ded > 0 && abs($diff) < 0.001) {
            $this->purchaseReceive = $ded;
        } else {
            $this->purchaseReceive = $diff != 0 ? abs($diff) : ($ded > 0 ? $ded : '');
        }
    }

    public function selectLedger(string $ledger)
    {
        $this->selectedLedger = $ledger;
        $this->showKhotiyanModal = false;

        $dbLedger = Ledger::where('name', $ledger)->first();
        if ($dbLedger) {
            $this->rate = ($dbLedger->rate !== null && $dbLedger->rate !== '' && floatval($dbLedger->rate) > 0) ? (float) $dbLedger->rate : '';
            $this->divisor = ($dbLedger->divisor !== null && $dbLedger->divisor !== '' && floatval($dbLedger->divisor) > 0) ? floatval($dbLedger->divisor) : 1;
            $this->calculateTotalBill();
        } else {
            $this->rate = '';
            $this->divisor = 1;
            $this->calculateTotalBill();
        }
    }

    public function openAddModal()
    {
        $this->resetForm();
        $this->editingId = null;
        if (!empty($this->dateFilter)) {
            $this->paymentDate = date('Y-m-d', strtotime($this->dateFilter));
        } else {
            $this->paymentDate = now()->format('Y-m-d');
        }
        $this->showPaymentModal = true;
    }

    public function isAdminUser(): bool
    {
        if (!auth()->check()) {
            return false;
        }
        $user = auth()->user();
        if ($user->hasRole('admin') || $user->hasRole('owner') || $user->hasRole('super-admin')) {
            return true;
        }
        if (isset($user->role) && in_array($user->role, ['admin', 'owner', 'super-admin'])) {
            return true;
        }
        return false;
    }

    public function editPayment(int $id)
    {
        // Permission Check: Staff can only edit today's payments
        if (!$this->isAdminUser()) {
            $payment = collect($this->paymentsList)->firstWhere('id', $id);
            if ($payment) {
                $payDateStr = $payment['date'] ?? '';
                $today = now()->format('d/m/Y');
                $todayDash = now()->format('Y-m-d');
                if ($payDateStr !== $today && $payDateStr !== $todayDash) {
                    $this->dispatch('show-toast', message: 'আপনি শুধুমাত্র আজকের দিনে করা পেমেন্টগুলোই সম্পাদনা করতে পারবেন।', type: 'danger');
                    return;
                }
            }
        }

        $this->resetForm();
        $payment = collect($this->paymentsList)->firstWhere('id', $id);

        if ($payment) {
            $this->editingId = $id;
            $this->selectedLedger = $payment['ledger'];
            $this->paymentDesc = $payment['desc'];
            $this->quantity = floatval($payment['qty']) > 0 ? floatval($payment['qty']) : '';
            $this->rate = floatval($payment['rate']) > 0 ? floatval($payment['rate']) : '';
            $this->totalBill = floatval($payment['total']) > 0 ? floatval($payment['total']) : '';
            $this->advance = floatval($payment['advance']) > 0 ? floatval($payment['advance']) : '';
            $this->deduction = floatval($payment['deduction']) > 0 ? floatval($payment['deduction']) : '';
            $this->paymentAmount = floatval($payment['payment']) > 0 ? floatval($payment['payment']) : '';
            $this->purchaseReceive = floatval($payment['purchase_receive']) > 0 ? floatval($payment['purchase_receive']) : '';
            
            if (floatval($payment['advance']) > 0 && floatval($payment['payment']) == 0 && floatval($payment['qty']) == 0) {
                $this->paymentType = 'অগ্রিম';
            } elseif (floatval($payment['purchase_receive']) > 0 && floatval($payment['payment']) == 0 && floatval($payment['qty']) == 0) {
                $this->paymentType = 'বাকি';
            } else {
                $this->paymentType = 'রেগুলার';
            }

            if (!empty($payment['date'])) {
                $parts = explode('/', $payment['date']);
                if (count($parts) === 3) {
                    $this->paymentDate = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
                } else {
                    $this->paymentDate = now()->format('Y-m-d');
                }
            } else {
                $this->paymentDate = now()->format('Y-m-d');
            }

            $this->showPaymentModal = true;
        }
    }

    /**
     * Get ledger's group_type by ledger name.
     */
    protected function getLedgerGroupType(string $ledgerName): string
    {
        $ledger = Ledger::where('name', $ledgerName)->first();
        return $ledger ? ($ledger->group_type ?: 'other') : 'other';
    }

    /**
     * Get net balance (advance - due) for a ledger across all active season payments.
     * Positive = has advance; Negative = has due (baki).
     */
    protected function getLedgerNetBalance(string $ledgerName): float
    {
        $season = $this->activeSeason;
        $allPayments = Payment::where('ledger', $ledgerName)
            ->where(function ($q) use ($season) {
                $q->where('season', $season)->orWhereNull('season');
            })
            ->get();

        $totalPaid = $allPayments->sum('payment') + $allPayments->sum('advance');
        $totalBill = $allPayments->sum('total');
        return $totalPaid - $totalBill; // Positive = advance, Negative = due
    }

    /**
     * Computed property: Get net due amount (পেমেন্ট বাকি) for the currently selected ledger.
     * Formula: মোট বিল - (মোট নগদ পেমেন্ট + মোট কর্তন)
     */
    public function getSelectedLedgerDueProperty(): float
    {
        if (empty($this->selectedLedger)) {
            return 0;
        }
        $season = $this->activeSeason ?: Setting::get('season', '২৫-২৬');
        $query = Payment::where('ledger', $this->selectedLedger)
            ->where(function ($q) use ($season) {
                $q->where('season', $season)->orWhereNull('season');
            });

        if ($this->editingId) {
            $query->where('id', '!=', $this->editingId);
        }

        $allPayments = $query->get();

        $regBill = 0;
        $regDeduction = 0;
        $regCashPayment = 0;
        $bakiPaidTotal = 0;

        foreach ($allPayments as $p) {
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
                $bakiPaidTotal += max($payment, $rec);
            } elseif ($isAdvanceType) {
                // Advance payment entry: does not reduce due balance
            } else {
                $regBill += $total;
                $regDeduction += $deduction;
                $regCashPayment += $payment;
            }
        }

        $regDue = $regBill - ($regDeduction + $regCashPayment);
        $pastNetDue = $regDue - $bakiPaidTotal;

        // Real-time deduction from current modal form inputs as user types
        $currPay = floatval($this->paymentAmount ?: 0);
        $currAdv = floatval($this->advance ?: 0);
        $currRec = floatval($this->purchaseReceive ?: 0);
        $currDed = floatval($this->deduction ?: 0);
        $currBill = floatval($this->totalBill ?: 0);

        if (str_contains($this->paymentType, 'বাকি')) {
            $currBakiEffect = max($currRec, $currPay);
            $netDue = $pastNetDue - $currBakiEffect;
        } elseif (str_contains($this->paymentType, 'অগ্ৰিম') || str_contains($this->paymentType, 'অগ্রিম')) {
            $netDue = $pastNetDue;
        } else {
            $currPaidEffect = $currPay + $currDed;
            $netDue = ($pastNetDue + $currBill) - $currPaidEffect;
        }

        return $netDue > 0 ? $netDue : 0;
    }

    public function submitPayment()
    {
        if (str_contains($this->paymentType, 'অগ্ৰিম') || str_contains($this->paymentType, 'অগ্রিম')) {
            $this->validate([
                'selectedLedger' => 'required',
                'paymentType' => 'required',
                'advance' => 'required|numeric'
            ], [
                'selectedLedger.required' => 'খতিয়ান নির্বাচন করুন',
                'paymentType.required' => 'পেমেন্টের ধরণ সিলেক্ট করুন',
                'advance.required' => 'অগ্রিম টাকা লিখুন'
            ]);
        } elseif (str_contains($this->paymentType, 'বাকি')) {
            $this->validate([
                'selectedLedger' => 'required',
                'paymentType' => 'required',
                'purchaseReceive' => 'required|numeric'
            ], [
                'selectedLedger.required' => 'খতিয়ান নির্বাচন করুন',
                'paymentType.required' => 'পেমেন্টের ধরণ সিলেক্ট করুন',
                'purchaseReceive.required' => 'বাকি টাকা লিখুন'
            ]);
        } else {
            $this->validate([
                'selectedLedger' => 'required',
                'paymentType' => 'required',
                'paymentAmount' => 'required|numeric'
            ], [
                'selectedLedger.required' => 'খতিয়ান নির্বাচন করুন',
                'paymentType.required' => 'পেমেন্টের ধরণ সিলেক্ট করুন',
                'paymentAmount.required' => 'পেমেন্ট পরিমাণ লিখুন'
            ]);
        }

        $docUrl = '#';
        $hasDoc = false;

        if ($this->documentFile) {
            $hasDoc = true;
            try {
                $filename = time() . '_' . $this->documentFile->getClientOriginalName();
                $destinationPath = public_path('uploads');

                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0777, true);
                }

                copy($this->documentFile->getRealPath(), $destinationPath . '/' . $filename);
                $docUrl = asset('uploads/' . $filename);
            } catch (\Throwable $e) {
                $docUrl = 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf';
            }
        }

        $formattedDate = now()->format('d/m/Y');
        if ($this->paymentDate) {
            if (str_contains($this->paymentDate, '-')) {
                $parts = explode('-', $this->paymentDate);
                if (count($parts) === 3) {
                    $formattedDate = sprintf('%02d/%02d/%04d', $parts[2], $parts[1], $parts[0]);
                }
            } elseif (str_contains($this->paymentDate, '/')) {
                $formattedDate = $this->paymentDate;
            }
        }

        // Permission Check 1.1: Staff can ONLY enter payments for TODAY'S date
        if (!$this->isAdminUser()) {
            $todaySlash = now()->format('d/m/Y');
            if (!$this->editingId && $formattedDate !== $todaySlash) {
                $this->dispatch('show-toast', message: 'সাধারণ ইউজার বা স্টাফ শুধুমাত্র আজকের তারিখে পেমেন্ট এন্ট্রি করতে পারবেন। পেছনের তারিখ নির্বাচন করার অধিকার নেই।', type: 'danger');
                return;
            }
        }

        $qty = floatval($this->quantity ?: 0);
        $rate = floatval($this->rate ?: 0);
        $total = floatval($this->totalBill ?: ($qty * $rate));
        $deduction = floatval($this->deduction ?: 0);
        $payment = floatval($this->paymentAmount ?: 0);
        $advance = floatval($this->advance ?: 0);
        $purchaseReceive = floatval($this->purchaseReceive ?: 0);

        // Advance payment logic: Advance means money paid out, so populate payment column if 0
        if ((str_contains($this->paymentType, 'অগ্ৰিম') || str_contains($this->paymentType, 'অগ্রিম') || $advance > 0) && $payment == 0) {
            $payment = $advance;
        }

        // Baki payment logic: Paying off due means money paid out, so populate payment column if 0
        if ((str_contains($this->paymentType, 'বাকি') || $purchaseReceive > 0) && $payment == 0) {
            $payment = $purchaseReceive;
        }

        // Validation 2.1: Block saving entries with no financial transaction (all amounts 0)
        if ($total == 0 && $payment == 0 && $advance == 0 && $deduction == 0 && $purchaseReceive == 0 && $qty == 0) {
            $this->dispatch('show-toast', message: 'পেমেন্ট মোডালে কোনো লেনদেন ছাড়া (বিল, পেমেন্ট, অগ্রিম বা কর্তন সব ফিল্ড ০ থাকলে) এন্ট্রি সেভ করা সম্ভব নয়।', type: 'danger');
            return;
        }

        // =====================================================================
        // Smart Payment Hisab Logic (3.3 - 3.5)
        // =====================================================================
        // Get the existing net balance for this ledger (excluding the current editing entry)
        $existingNetBalance = $this->getLedgerNetBalance($this->selectedLedger);
        if ($this->editingId) {
            // Exclude current payment from net balance calculation
            $currentPayment = Payment::find($this->editingId);
            if ($currentPayment) {
                $existingNetBalance -= (floatval($currentPayment->payment) + floatval($currentPayment->advance) - floatval($currentPayment->total));
            }
        }

        // Calculate the "net effect" of this entry
        // effective_payment = payment - total - deduction
        // If total=0 and advance>0, it's a pure advance entry.
        // If total>0:
        //   payment+advance >= total => surplus = (payment+advance) - total
        //   payment+advance < total  => deficit = total - (payment+advance)

        $finalAdvance = $advance;
        $finalPurchaseReceive = $purchaseReceive;

        if (str_contains($this->paymentType, 'বাকি') || ($purchaseReceive > 0 && $total == 0)) {
            // Pure Baki settlement payment: strictly goes into payment column, NOT into kom/beshi column
            $finalPurchaseReceive = 0;
            $finalAdvance = 0;
        } elseif (str_contains($this->paymentType, 'অগ্ৰিম') || str_contains($this->paymentType, 'অগ্রিম')) {
            $finalAdvance = $advance;
            $finalPurchaseReceive = 0;
        } elseif ($total > 0 || $payment > 0) {
            $netEntry = ($payment + $advance) - ($total - $deduction);

            if ($netEntry > 0) {
                // Surplus (overpayment) e.g. paid 2000 for 1000 bill -> surplus = 1000, stored as -1000
                $finalAdvance = 0;
                $finalPurchaseReceive = -$netEntry;
            } elseif ($netEntry < 0) {
                // Deficit (baki/due) e.g. paid 2000 for 5000 bill (deduction 1000) -> due = 2000, stored as 2000
                $finalAdvance = 0;
                $finalPurchaseReceive = abs($netEntry);
            } else {
                // Exact match
                $finalAdvance = 0;
                $finalPurchaseReceive = 0;
            }
        }

        $season = Setting::get('season', '২৫-২৬');

        if ($this->editingId) {
            $paymentModel = Payment::find($this->editingId);
            if ($paymentModel) {
                // Reverse old production stock if applicable
                $oldGroupType = $this->getLedgerGroupType($paymentModel->ledger);
                if ($oldGroupType === 'production' && floatval($paymentModel->qty) > 0) {
                    $this->reverseProductionStock($paymentModel);
                }

                $paymentModel->update([
                    'date' => $formattedDate,
                    'ledger' => $this->selectedLedger,
                    'desc' => $this->paymentDesc,
                    'payment_type' => $this->paymentType ?: 'রেগুলার',
                    'qty' => $qty,
                    'rate' => $rate,
                    'total' => $total,
                    'advance' => $finalAdvance,
                    'deduction' => $deduction,
                    'payment' => $payment,
                    'purchase_receive' => $finalPurchaseReceive,
                    'has_doc' => $this->documentFile ? $hasDoc : $paymentModel->has_doc,
                    'doc_url' => $this->documentFile ? $docUrl : $paymentModel->doc_url,
                ]);

                // Add new production stock if applicable
                $newGroupType = $this->getLedgerGroupType($this->selectedLedger);
                if ($newGroupType === 'production' && $qty > 0) {
                    $this->addProductionStock($this->selectedLedger, $qty, $formattedDate, $paymentModel->id);
                }
            }
            $this->dispatch('show-toast', message: 'পেমেন্ট সফলভাবে আপডেট করা হয়েছে।', type: 'success');
        } else {
            $newPayment = Payment::create([
                'date' => $formattedDate,
                'ledger' => $this->selectedLedger,
                'desc' => $this->paymentDesc,
                'payment_type' => $this->paymentType ?: 'রেগুলার',
                'qty' => $qty,
                'rate' => $rate,
                'total' => $total,
                'advance' => $finalAdvance,
                'deduction' => $deduction,
                'payment' => $payment,
                'purchase_receive' => $finalPurchaseReceive,
                'doc_url' => $docUrl,
                'has_doc' => $hasDoc,
                'season' => $season,
            ]);

            // Production Stock Integration (4.1, 4.2)
            $groupType = $this->getLedgerGroupType($this->selectedLedger);
            if ($groupType === 'production' && $qty > 0) {
                $this->addProductionStock($this->selectedLedger, $qty, $formattedDate, $newPayment->id);
            }

            $this->dispatch('show-toast', message: 'পেমেন্ট সফলভাবে সংরক্ষণ করা হয়েছে।', type: 'success');
        }

        if ($this->paymentDate) {
            if (str_contains($this->paymentDate, '-')) {
                $this->dateFilter = $this->paymentDate;
            } elseif (str_contains($this->paymentDate, '/')) {
                $parts = explode('/', $this->paymentDate);
                if (count($parts) === 3) {
                    $this->dateFilter = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
                }
            }
        }
        $this->loadPaymentsList();
        $this->resetForm();
        $this->showPaymentModal = false;
    }

    /**
     * Add production stock when payment with production group is saved.
     */
    protected function addProductionStock(string $ledgerName, float $qty, string $date, int $paymentId): void
    {
        try {
            // Convert date from dd/mm/yyyy to yyyy-mm-dd for StockAdjustment
            $dateForStock = now()->format('Y-m-d');
            if (str_contains($date, '/')) {
                $parts = explode('/', $date);
                if (count($parts) === 3) {
                    $dateForStock = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
                }
            }

            StockAdjustment::create([
                'date' => $dateForStock,
                'description' => 'পেমেন্ট খাতা থেকে: ' . $ledgerName . ' (ID:' . $paymentId . ')',
                'category_name' => $ledgerName,
                'stock_plus' => (int) round($qty),
                'stock_minus' => 0,
                'user_id' => auth()->id(),
            ]);
        } catch (\Throwable $e) {
            // Silently fail — don't block payment save
        }
    }

    /**
     * Reverse production stock when payment is deleted or edited.
     */
    protected function reverseProductionStock(Payment $payment): void
    {
        try {
            // Find matching StockAdjustment by description containing payment ID
            StockAdjustment::where('description', 'like', '%(ID:' . $payment->id . ')%')->delete();
        } catch (\Throwable $e) {
            // Silently fail
        }
    }

    public function resetForm()
    {
        $this->reset([
            'selectedLedger',
            'paymentType',
            'paymentDesc',
            'quantity',
            'rate',
            'totalBill',
            'advance',
            'deduction',
            'paymentAmount',
            'purchaseReceive',
            'documentFile',
            'editingId',
            'khotiyanSearch',
        ]);
        if (!empty($this->dateFilter)) {
            $this->paymentDate = date('Y-m-d', strtotime($this->dateFilter));
        } else {
            $this->paymentDate = now()->format('Y-m-d');
        }
        $this->divisor = 1;
    }

    public function confirmDelete(int $id)
    {
        if (auth()->check() && auth()->user()->hasRole('demo')) {
            $this->dispatch('show-toast', message: 'ডেমো মোডে পেমেন্ট মুছে ফেলা সম্ভব নয়।', type: 'danger');
            return;
        }

        // Permission Check: Staff can only delete today's payments
        if (!$this->isAdminUser()) {
            $payment = collect($this->paymentsList)->firstWhere('id', $id);
            if ($payment) {
                $payDateStr = $payment['date'] ?? '';
                $today = now()->format('d/m/Y');
                $todayDash = now()->format('Y-m-d');
                if ($payDateStr !== $today && $payDateStr !== $todayDash) {
                    $this->dispatch('show-toast', message: 'আপনি শুধুমাত্র আজকের পেমেন্ট মুছে ফেলতে পারবেন।', type: 'danger');
                    return;
                }
            }
        }

        $this->confirmingDeleteId = $id;
    }

    public function cancelDelete()
    {
        $this->confirmingDeleteId = null;
    }

    public function deletePaymentConfirmed()
    {
        if (auth()->check() && auth()->user()->hasRole('demo')) {
            $this->dispatch('show-toast', message: 'ডেমো মোডে পেমেন্ট মুছে ফেলা সম্ভব নয়।', type: 'danger');
            $this->confirmingDeleteId = null;
            return;
        }

        if ($this->confirmingDeleteId) {
            $paymentModel = Payment::find($this->confirmingDeleteId);
            if ($paymentModel) {
                $ledgerName = $paymentModel->ledger;
                $ledgerObj = Ledger::where('name', $ledgerName)->first();
                $groupName = $ledgerObj ? $ledgerObj->group : $ledgerName;

                // Reverse production stock if applicable (3.6)
                $groupType = $this->getLedgerGroupType($paymentModel->ledger);
                if ($groupType === 'production' && floatval($paymentModel->qty) > 0) {
                    $this->reverseProductionStock($paymentModel);
                }
                $paymentModel->delete();

                // If group is inactive and now has NO active ledgers AND NO payments remaining in DB, clean it up permanently
                if ($groupName && LedgerGroups::isInactive($groupName)) {
                    $hasActiveLedgers = Ledger::active()->where('group', $groupName)->exists();
                    $allGroupLedgers = Ledger::where('group', $groupName)->pluck('name')->toArray();
                    $allGroupLedgers[] = $groupName;
                    $hasPaymentsLeft = Payment::whereIn('ledger', $allGroupLedgers)->exists();

                    if (!$hasActiveLedgers && !$hasPaymentsLeft) {
                        LedgerGroups::remove($groupName);
                        LedgerGroups::markActive($groupName);
                        Ledger::where('group', $groupName)->delete();
                    }
                }
            }
            $this->loadPaymentsList();
            $this->dispatch('show-toast', message: 'পেমেন্ট সফলভাবে মুছে ফেলা হয়েছে।', type: 'success');
            $this->confirmingDeleteId = null;
        }
    }

    public function deletePayment(int $id)
    {
        $this->confirmDelete($id);
    }

    public function getReportDataProperty()
    {
        $payments = $this->paymentsList;
        if ($this->reportTab === 'date') {
            if (!empty($this->dateFilter)) {
                $targetSlash = date('d/m/Y', strtotime($this->dateFilter));
                $targetDash = date('Y-m-d', strtotime($this->dateFilter));
            } else {
                $targetSlash = now()->format('d/m/Y');
                $targetDash = now()->format('Y-m-d');
            }

            $payments = array_values(array_filter($payments, function ($pay) use ($targetSlash, $targetDash) {
                if (!empty($pay['date'])) {
                    if ($pay['date'] === $targetSlash || $pay['date'] === $targetDash) {
                        return true;
                    }
                }
                if (!empty($pay['created_at'])) {
                    return date('Y-m-d', strtotime($pay['created_at'])) === $targetDash;
                }
                return false;
            }));
        }

        $byLedger = [];
        $totalQty = 0;
        $totalBillSum = 0;
        $totalAdvanceSum = 0;
        $totalDeductionSum = 0;
        $totalPaymentSum = 0;
        $totalPurchaseRecSum = 0;

        foreach ($payments as $pay) {
            $key = $pay['ledger'];
            if (!isset($byLedger[$key])) {
                $byLedger[$key] = ['ledger' => $key, 'count' => 0, 'quantity' => 0, 'payment' => 0];
            }
            $byLedger[$key]['count']++;
            $byLedger[$key]['quantity'] += floatval($pay['qty']);
            $byLedger[$key]['payment'] += floatval($pay['payment']);

            $totalQty += floatval($pay['qty']);
            $totalBillSum += floatval($pay['total']);
            $totalAdvanceSum += floatval($pay['advance']);
            $totalDeductionSum += floatval($pay['deduction']);
            $totalPaymentSum += floatval($pay['payment']);
            $totalPurchaseRecSum += floatval($pay['purchase_receive']);
        }

        return [
            'rows' => array_values($byLedger),
            'total_qty' => $totalQty,
            'total_bill' => $totalBillSum,
            'total_advance' => $totalAdvanceSum,
            'total_deduction' => $totalDeductionSum,
            'total_payment' => $totalPaymentSum,
            'total_purchase_rec' => $totalPurchaseRecSum,
            'count' => count($payments)
        ];
    }

    public function render()
    {
        $filteredPayments = array_filter($this->paymentsList, function ($payment) {
            $matchesSearch = true;
            if ($this->search !== '') {
                $term = strtolower($this->search);
                $matchesSearch = str_contains(strtolower($payment['ledger']), $term) ||
                    str_contains(strtolower($payment['desc'] ?? ''), $term);
            }
            $matchesDate = true;
            if (!empty($this->dateFilter)) {
                $formattedFilter = date('d/m/Y', strtotime($this->dateFilter));
                $matchesDate = ($payment['date'] === $formattedFilter || $payment['date'] === $this->dateFilter);
            }
            return $matchesSearch && $matchesDate;
        });

        $totalPaymentsSum = array_reduce($filteredPayments, function ($carry, $item) {
            return $carry + (float) $item['payment'];
        }, 0);

        // Only active ledgers for the selection modal
        $dbLedgers = Ledger::active()
            ->orderByRaw('CASE WHEN serial IS NULL THEN 1 ELSE 0 END, serial ASC, id ASC')
            ->get();

        $this->ledgerGroups = $this->syncLedgerGroups();

        // Compute per-ledger net balance from all payments (active season)
        $allSeasonPayments = Payment::where(function ($q) {
            $q->where('season', $this->activeSeason)->orWhereNull('season');
        })->get();

        $ledgerBalances = [];
        foreach ($allSeasonPayments as $p) {
            $name = trim($p->ledger);
            if (!isset($ledgerBalances[$name])) {
                $ledgerBalances[$name] = ['payment' => 0, 'advance' => 0, 'bill' => 0, 'net' => 0];
            }
            $ledgerBalances[$name]['payment'] += (float)$p->payment;
            $ledgerBalances[$name]['advance'] += (float)$p->advance;
            $ledgerBalances[$name]['bill']    += (float)$p->total;
        }
        foreach ($ledgerBalances as $name => &$bal) {
            $bal['net'] = ($bal['payment'] + $bal['advance']) - $bal['bill'];
        }
        unset($bal);

        $groupedLedgers = [];
        foreach ($this->ledgerGroups as $grp) {
            $grpBal = $ledgerBalances[$grp] ?? ['payment' => 0, 'advance' => 0, 'bill' => 0, 'net' => 0];
            $groupedLedgers[$grp] = [
                ['id' => null, 'name' => $grp, 'group' => $grp, 'rate' => 0, 'serial' => 0, 'is_group_fallback' => true, 'balance' => $grpBal['net']],
            ];
        }

        foreach ($dbLedgers as $ledger) {
            $g = trim($ledger->group) ?: 'অন্যান্য';
            if (!isset($groupedLedgers[$g])) {
                $grpBal = $ledgerBalances[$g] ?? ['payment' => 0, 'advance' => 0, 'bill' => 0, 'net' => 0];
                $groupedLedgers[$g] = [
                    ['id' => null, 'name' => $g, 'group' => $g, 'rate' => 0, 'serial' => 0, 'is_group_fallback' => true, 'balance' => $grpBal['net']],
                ];
            }
            $lBal = $ledgerBalances[$ledger->name] ?? ['net' => 0];
            $groupedLedgers[$g][] = [
                'id'              => $ledger->id,
                'name'            => $ledger->name,
                'group'           => $ledger->group,
                'rate'            => $ledger->rate,
                'serial'          => $ledger->serial,
                'is_group_fallback' => false,
                'balance'         => $lBal['net'],
            ];
        }

        // Remove inactive groups completely from groupedLedgers
        foreach (array_keys($groupedLedgers) as $gName) {
            if (LedgerGroups::isInactive($gName)) {
                unset($groupedLedgers[$gName]);
            }
        }

        if (!empty($this->khotiyanSearch)) {
            $term = strtolower(trim($this->khotiyanSearch));
            foreach ($groupedLedgers as $gName => &$items) {
                $matchesGroup = str_contains(strtolower($gName), $term);
                if (!$matchesGroup) {
                    $items = array_values(array_filter($items, function ($item) use ($term) {
                        return str_contains(strtolower($item['name']), $term);
                    }));
                }
            }
        }

        $paginatedPayments = array_slice($filteredPayments, 0, $this->perPage);

        return view('livewire.payment-khata', [
            'payments'         => $paginatedPayments,
            'totalPaymentsSum' => $totalPaymentsSum,
            'groupedLedgers'   => $groupedLedgers,
        ]);
    }
}