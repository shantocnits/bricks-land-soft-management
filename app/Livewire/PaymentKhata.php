<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Ledger;
use App\Models\Setting;
use App\Models\Payment;
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
    public bool $showKhotiyanModal = false;
    public bool $showNewKhotiyanModal = false;
    public bool $showReportModal = false;

    // Delete confirmation
    public ?int $confirmingDeleteId = null;
    public $groupToDelete = null;
    public bool $showDeleteConfirmModal = false;

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

    // Khotiyan Modal Search and Creation
    public string $khotiyanSearch = '';

    // New Khotiyan Form
    public string $newLedgerSerial = '';
    public string $newLedgerName = '';
    public string $newLedgerGroup = 'অন্যান্য';
    public $newLedgerRate = '';
    public $newLedgerDivisor = 1;
    public ?string $editingLedgerOldName = null;

    // Report tab state
    public string $reportTab = 'date'; // 'date' or 'all'

    // Payment History List
    public array $paymentsList = [];

    public function mount()
    {
        $this->dateFilter = now()->format('Y-m-d');
        $this->paymentDate = now()->format('Y-m-d');

        // Load ledger groups dynamically from Setting state and DB ledgers table
        $this->ledgerGroups = $this->syncLedgerGroups();

        // Default first group
        $this->newLedgerGroup = count($this->ledgerGroups) > 0 ? $this->ledgerGroups[0] : '';

        // Load payments list from database filtered by active season
        $this->loadPaymentsList();
    }

    public function syncLedgerGroups(): array
    {
        $groupsJson = Setting::get('ledger_groups');
        $savedGroups = $groupsJson ? (json_decode($groupsJson, true) ?: []) : [];
        $dbGroups = Ledger::pluck('group')->filter()->unique()->toArray();

        return array_values(array_filter(array_unique(array_merge($savedGroups, $dbGroups)), fn($g) => trim($g) !== ''));
    }

    public function loadPaymentsList()
    {
        $activeSeason = Setting::get('season', '২৫-২৬');
        $this->paymentsList = Payment::where(function ($query) use ($activeSeason) {
            $query->where('season', $activeSeason)
                  ->orWhereNull('season');
        })->get()->toArray();
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

    public function addGroup(string $name)
    {
        $name = trim($name);
        if ($name === '') {
            return;
        }

        // Check for duplicate group name case-insensitively
        $exists = false;
        foreach ($this->ledgerGroups as $g) {
            if (mb_strtolower(trim($g), 'UTF-8') === mb_strtolower($name, 'UTF-8')) {
                $exists = true;
                $name = $g;
                break;
            }
        }

        if (!$exists) {
            // Add to memory list ONLY (do NOT save to DB Setting yet)
            array_unshift($this->ledgerGroups, $name);
        }

        // Automatically set as selected group in modal
        $this->newLedgerGroup = $name;
        $this->dispatch('show-toast', message: "'{$name}' গ্রুপ সিলেক্ট করা হয়েছে।", type: 'success');
    }

    public function confirmDeleteGroup($groupName)
    {
        $this->groupToDelete = $groupName;
        $this->showDeleteConfirmModal = true;
    }

    public function deleteGroupConfirmed()
    {
        if ($this->groupToDelete) {
            $name = $this->groupToDelete;
            $groupsJson = Setting::get('ledger_groups');
            $allGroups = $groupsJson ? (json_decode($groupsJson, true) ?: []) : [];
            $allGroups = array_values(array_filter($allGroups, fn($g) => $g !== $name));
            Setting::set('ledger_groups', json_encode($allGroups));

            Ledger::where('group', $name)->update(['group' => 'অন্যান্য']);

            $this->dispatch('show-toast', message: "'{$name}' গ্রুপটি সফলভাবে মুছে ফেলা হয়েছে।", type: 'success');
            $this->groupToDelete = null;
            $this->showDeleteConfirmModal = false;
        }
    }

    public function cancelDeleteGroup()
    {
        $this->groupToDelete = null;
        $this->showDeleteConfirmModal = false;
    }

    public function deleteGroup(string $name)
    {
        $this->ledgerGroups = array_values(array_filter($this->ledgerGroups, fn($g) => $g !== $name));
        Setting::set('ledger_groups', json_encode($this->ledgerGroups));
        if ($this->newLedgerGroup === $name) {
            $this->newLedgerGroup = count($this->ledgerGroups) > 0 ? $this->ledgerGroups[0] : '';
        }
        $this->dispatch('show-toast', message: 'গ্রুপ মুছে ফেলা হয়েছে।', type: 'success');
    }

    public function openNewKhotiyanModal(string $preselectedGroup = '')
    {
        $this->ledgerGroups = $this->syncLedgerGroups();

        $this->editingLedgerOldName = null;
        $this->newLedgerName = '';
        $this->newLedgerGroup = $preselectedGroup ?: '';
        $this->newLedgerSerial = sprintf('%02d', Ledger::count() + 1);
        $this->newLedgerRate = '';
        $this->newLedgerDivisor = 1;
        $this->showNewKhotiyanModal = true;
    }

    public function addLedger()
    {
        $group = trim($this->newLedgerGroup);
        $name = trim($this->newLedgerName);

        if ($group === '') {
            $this->dispatch('show-toast', message: 'খতিয়ানের গ্রুপ আবশ্যক।', type: 'danger');
            return;
        }

        // Rule: Khotiyan Name is optional. If empty, default to Group Name.
        if ($name === '') {
            $name = $group;
        }

        $rateVal = ($this->newLedgerRate !== '' && $this->newLedgerRate !== null) ? floatval($this->newLedgerRate) : 0;
        $divisorVal = ($this->newLedgerDivisor !== '' && $this->newLedgerDivisor !== null && floatval($this->newLedgerDivisor) > 0) ? floatval($this->newLedgerDivisor) : 1;

        // Save group into DB settings upon main button submit
        $groupsJson = Setting::get('ledger_groups');
        $existingGroups = $groupsJson ? (json_decode($groupsJson, true) ?: []) : [];

        $matchedExistingGroup = null;
        foreach ($existingGroups as $eg) {
            if (mb_strtolower(trim($eg), 'UTF-8') === mb_strtolower($group, 'UTF-8')) {
                $matchedExistingGroup = $eg;
                break;
            }
        }

        if ($matchedExistingGroup) {
            $group = $matchedExistingGroup;
        } else {
            array_unshift($existingGroups, $group);
            Setting::set('ledger_groups', json_encode($existingGroups));
        }
        $this->ledgerGroups = $this->syncLedgerGroups();

        if ($this->editingLedgerOldName) {
            $oldName = $this->editingLedgerOldName;
            Ledger::where('name', $oldName)->update([
                'name' => $name,
                'group' => $group,
                'serial' => intval($this->newLedgerSerial),
                'rate' => $rateVal,
                'divisor' => $divisorVal,
            ]);

            Payment::where('ledger', $oldName)->update(['ledger' => $name]);
            $this->loadPaymentsList();

            if ($this->selectedLedger === $oldName) {
                $this->selectedLedger = $name;
                $this->rate = $rateVal !== 0 ? $rateVal : '';
                $this->divisor = ($divisorVal !== null && $divisorVal > 0) ? $divisorVal : 1;
                $this->calculateTotalBill();
            }
            $this->showNewKhotiyanModal = false;
            $this->dispatch('ledger-added');
            $this->dispatch('show-toast', message: 'খতিয়ান সফলভাবে আপডেট করা হয়েছে।', type: 'success');
        } else {
            // Rule: Default Khotiyan Creation
            // If adding a sub-khotiyan (name != group), ensure a default group khotiyan (name == group) exists in DB
            if (mb_strtolower($name, 'UTF-8') !== mb_strtolower($group, 'UTF-8')) {
                $defaultGroupLedger = Ledger::where('group', $group)
                    ->whereRaw('LOWER(name) = ?', [mb_strtolower($group, 'UTF-8')])
                    ->first();
                if (!$defaultGroupLedger) {
                    $maxSerial = (int) (Ledger::max('serial') ?: Ledger::count());
                    Ledger::create([
                        'serial' => $maxSerial + 1,
                        'name' => $group,
                        'group' => $group,
                        'rate' => 0,
                        'divisor' => 1,
                    ]);
                }
            }

            $maxSerial = (int) (Ledger::max('serial') ?: Ledger::count());
            $newSerial = $this->newLedgerSerial ? intval($this->newLedgerSerial) : ($maxSerial + 1);

            Ledger::create([
                'serial' => $newSerial,
                'name' => $name,
                'group' => $group,
                'rate' => $rateVal,
                'divisor' => $divisorVal,
            ]);
            $this->selectedLedger = $name;
            $this->rate = $rateVal !== 0 ? $rateVal : '';
            $this->divisor = ($divisorVal !== null && $divisorVal > 0) ? $divisorVal : 1;
            $this->calculateTotalBill();

            $this->dispatch('ledger-added');
            $this->dispatch('show-toast', message: 'খতিয়ান সফলভাবে যোগ করা হয়েছে।', type: 'success');

            $this->showNewKhotiyanModal = false;
            $this->showKhotiyanModal = false;
        }

        $this->newLedgerName = '';
        $this->newLedgerGroup = '';
        $this->newLedgerRate = '';
        $this->newLedgerDivisor = 1;
        $this->editingLedgerOldName = null;
    }

    public function deleteLedger($id)
    {
        $ledger = is_numeric($id) ? Ledger::find($id) : Ledger::where('name', $id)->first();
        if ($ledger) {
            if ($this->selectedLedger === $ledger->name) {
                $this->selectedLedger = '';
            }
            $ledger->delete();
        }
        $this->dispatch('show-toast', message: 'খতিয়ান মুছে ফেলা হয়েছে।', type: 'success');
    }

    public function openAddModal()
    {
        $this->resetForm();
        $this->editingId = null;
        $this->showPaymentModal = true;
    }

    public function editPayment(int $id)
    {
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
                    $this->paymentDate = '2026-07-18';
                }
            } else {
                $this->paymentDate = '2026-07-18';
            }

            $this->showPaymentModal = true;
        }
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

        $formattedDate = '18/07/2026';
        if ($this->paymentDate) {
            $parts = explode('-', $this->paymentDate);
            if (count($parts) === 3) {
                $formattedDate = $parts[2] . '/' . $parts[1] . '/' . $parts[0];
            }
        }

        $qty = floatval($this->quantity ?: 0);
        $rate = floatval($this->rate ?: 0);
        $total = floatval($this->totalBill ?: ($qty * $rate));
        $advance = floatval($this->advance ?: 0);
        $deduction = floatval($this->deduction ?: 0);
        $payment = floatval($this->paymentAmount ?: 0);
        $purchaseReceive = floatval($this->purchaseReceive ?: 0);

        if ($this->editingId) {
            $paymentModel = Payment::find($this->editingId);
            if ($paymentModel) {
                $paymentModel->update([
                    'date' => $formattedDate,
                    'ledger' => $this->selectedLedger,
                    'desc' => $this->paymentDesc,
                    'qty' => $qty,
                    'rate' => $rate,
                    'total' => $total,
                    'advance' => $advance,
                    'deduction' => $deduction,
                    'payment' => $payment,
                    'purchase_receive' => $purchaseReceive,
                    'has_doc' => $this->documentFile ? $hasDoc : $paymentModel->has_doc,
                    'doc_url' => $this->documentFile ? $docUrl : $paymentModel->doc_url,
                ]);
            }
            $this->dispatch('show-toast', message: 'পেমেন্ট সফলভাবে আপডেট করা হয়েছে।', type: 'success');
        } else {
            Payment::create([
                'date' => $formattedDate,
                'ledger' => $this->selectedLedger,
                'desc' => $this->paymentDesc,
                'qty' => $qty,
                'rate' => $rate,
                'total' => $total,
                'advance' => $advance,
                'deduction' => $deduction,
                'payment' => $payment,
                'purchase_receive' => $purchaseReceive,
                'doc_url' => $docUrl,
                'has_doc' => $hasDoc,
                'season' => Setting::get('season', '২৫-২৬')
            ]);
            $this->dispatch('show-toast', message: 'পেমেন্ট সফলভাবে সংরক্ষণ করা হয়েছে।', type: 'success');
        }

        $this->loadPaymentsList();
        $this->resetForm();
        $this->showPaymentModal = false;
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
            'editingId'
        ]);
        $this->paymentDate = now()->format('Y-m-d');
    }

    public function confirmDelete(int $id)
    {
        if (auth()->check() && auth()->user()->hasRole('demo')) {
            $this->dispatch('show-toast', message: 'ডেমো মোডে পেমেন্ট মুছে ফেলা সম্ভব নয়।', type: 'danger');
            return;
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
            Payment::destroy($this->confirmingDeleteId);
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
            $todayFormatted = now()->format('d/m/Y');
            $todayDash = now()->format('Y-m-d');
            $payments = array_values(array_filter($payments, function ($pay) use ($todayFormatted, $todayDash) {
                if (!empty($pay['date'])) {
                    if ($pay['date'] === $todayFormatted || $pay['date'] === $todayDash) {
                        return true;
                    }
                }
                if (!empty($pay['created_at'])) {
                    return date('Y-m-d', strtotime($pay['created_at'])) === $todayDash;
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
                    str_contains(strtolower($payment['desc']), $term);
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

        $dbLedgers = Ledger::orderByRaw('CASE WHEN serial IS NULL THEN 1 ELSE 0 END, serial ASC, id ASC')->get();

        $this->ledgerGroups = $this->syncLedgerGroups();

        // Compute per-ledger net balance from all payments (active season)
        $activeSeason = Setting::get('season', '২৫-২৬');
        $allSeasonPayments = Payment::where(function ($q) use ($activeSeason) {
            $q->where('season', $activeSeason)->orWhereNull('season');
        })->get();

        $ledgerBalances = []; // ledger name => ['payment' => 0, 'advance' => 0, 'bill' => 0, 'net' => 0]
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
            // Always seed each group with the group name itself as the first (fallback) item
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