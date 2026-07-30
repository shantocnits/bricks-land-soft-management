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
    public float $quantity = 0;
    public float $rate = 0;
    public float $totalBill = 0;
    public float $advance = 0;
    public float $deduction = 0;
    public float $paymentAmount = 0;
    public float $purchaseReceive = 0;
    public $documentFile = null;

    // Custom List variables
    public array $ledgerGroups = ['লেবার', 'মাটি', 'স্টাফ', 'অন্যান্য'];
    public array $ledgerTypes = [];

    // Sub-items map for ledgers
    public array $subItemsMap = [];
    public array $explicitTypesMap = [];

    // Khotiyan Modal Search and Creation
    public string $khotiyanSearch = '';

    // New Khotiyan Form
    public string $newLedgerSerial = '';
    public string $newLedgerName = '';
    public string $newLedgerGroup = 'অন্যান্য';
    public string $newLedgerType = '';
    public ?string $editingLedgerOldName = null;
    public array $ledgerGroupsMap = [];

    public array $ledgers = [
        'মেল',
        'লোড মিস্ত্রি',
        'বেজা মাটি',
        '১ নং মেল',
        '২ নং মেল',
        '৩ নং মেল',
        'পোড়াই',
        'তেইলি লেবার',
        'রাবিশ ম্যান',
        'ক্লিন পরিষ্কার',
        'সাদা মাটি',
        'লাল মাটি',
        'অফিসিয়াল খরচ',
        'কারেন্ট বিল',
        'হাওয়ার তেল',
        'ভাটি স্টাফ',
        'স্টাফ খরচ',
        'মোটরসাইকেল',
        'বেকু',
        'মেসি',
        'জমির টাকা',
        'বালু',
        'খড়ির হিসাব',
        'ফর্মার হিসাব',
        'মালামাল',
        'মেরামত বিল',
        'অনুদান',
        'লেবার খরচ',
        'কাস্টমার কম দেওয়া',
        'জমা স্টক',
        'অন্যান্য'
    ];

    // Report tab state
    public string $reportTab = 'date'; // 'date' or 'all'

    // Payment History List
    public array $paymentsList = [];

    public function mount()
    {
        $this->dateFilter = now()->format('Y-m-d');
        $this->paymentDate = now()->format('Y-m-d');

        // Load ledger groups from Setting DB and DB ledgers table
        $groupsJson = Setting::get('ledger_groups');
        $dbGroups = Ledger::pluck('group')->unique()->toArray();
        $defaultGroups = ['কাস্টমার', 'সরবরাহকারী', 'লেবার', 'মাটি', 'স্টাফ', 'খরচ', 'আয়', 'অন্যান্য'];

        if ($groupsJson) {
            $allGroups = json_decode($groupsJson, true) ?: $defaultGroups;
            $this->ledgerGroups = array_values(array_unique(array_merge($allGroups, $dbGroups)));
        } else {
            $this->ledgerGroups = array_values(array_unique(array_merge($defaultGroups, $dbGroups)));
            Setting::set('ledger_groups', json_encode($this->ledgerGroups));
        }

        // Default first group
        $this->newLedgerGroup = count($this->ledgerGroups) > 0 ? $this->ledgerGroups[0] : '';

        // Seed default payments to database if empty
        if (Payment::count() === 0) {
            $seeds = [
                ['date' => '18/07/2026', 'ledger' => '১ নং মেল', 'desc' => 'কয়লা লোড করার বিল পেমেন্ট', 'qty' => 1500, 'rate' => 12, 'total' => 18000, 'advance' => 5000, 'deduction' => 1000, 'payment' => 12000, 'purchase_receive' => 18000, 'doc_url' => 'https://images.unsplash.com/photo-1554415707-6e8cfc93fe23?auto=format&fit=crop&w=800&q=80', 'has_doc' => true],
            ];
            foreach ($seeds as $p) {
                Payment::create($p);
            }
        }

        // Load payments list from database
        $this->paymentsList = Payment::all()->toArray();
    }

    public function getSubItemsMap()
    {
        $map = [];

        foreach ($this->ledgers as $ledger) {
            $matchedTypes = [];

            if (isset($this->explicitTypesMap[$ledger]) && trim($this->explicitTypesMap[$ledger]) !== '') {
                $matchedTypes[] = $this->explicitTypesMap[$ledger];
            }

            if (!empty($matchedTypes)) {
                $map[$ledger] = $matchedTypes;
            }
        }

        return $map;
    }

    public function updatedQuantity()
    {
        $this->calculateTotalBill();
    }

    public function updatedRate()
    {
        $this->calculateTotalBill();
    }

    public function calculateTotalBill()
    {
        $this->totalBill = (float) $this->quantity * (float) $this->rate;
    }

    public function selectLedger(string $ledger)
    {
        $this->selectedLedger = $ledger;
        $this->showKhotiyanModal = false;

        $dbLedger = Ledger::where('name', $ledger)->first();
        if ($dbLedger && $dbLedger->rate) {
            $this->rate = (float) $dbLedger->rate;
            $this->calculateTotalBill();
        }
    }

    public function addGroup(string $name)
    {
        $name = trim($name);
        if ($name !== '') {
            $this->ledgerGroups = array_values(array_diff($this->ledgerGroups, [$name]));
            array_unshift($this->ledgerGroups, $name);
            Setting::set('ledger_groups', json_encode($this->ledgerGroups));
            $this->newLedgerGroup = $name;
            $this->dispatch('show-toast', message: 'গ্রুপ যুক্ত করা হয়েছে।', type: 'success');
        }
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
        $dbLedgers = Ledger::all();
        $groupsJson = Setting::get('ledger_groups');
        $allGroups = $groupsJson ? (json_decode($groupsJson, true) ?: []) : [];
        $dbGroups = $dbLedgers->pluck('group')->filter()->unique()->toArray();
        $dbNames = $dbLedgers->pluck('name')->filter(fn($n) => $n !== '' && $n !== '—')->unique()->toArray();
        $this->ledgerGroups = array_values(array_unique(array_merge($allGroups, $dbGroups, $dbNames)));

        $this->editingLedgerOldName = null;
        $this->newLedgerName = '';
        $this->newLedgerGroup = $preselectedGroup ?: '';
        $this->newLedgerSerial = sprintf('%02d', Ledger::count() + 1);
        $this->showNewKhotiyanModal = true;
    }

    public function addLedger()
    {
        $this->validate([
            'newLedgerName' => 'required'
        ], [
            'newLedgerName.required' => 'খতিয়ানের নাম লিখুন'
        ]);

        $name = trim($this->newLedgerName);
        $group = $this->newLedgerGroup;

        $groupsJson = Setting::get('ledger_groups');
        $existingGroups = $groupsJson ? (json_decode($groupsJson, true) ?: []) : [];

        if (!in_array($group, $existingGroups)) {
            array_unshift($existingGroups, $group);
            Setting::set('ledger_groups', json_encode($existingGroups));
            $this->ledgerGroups = $existingGroups;
        }

        if ($this->editingLedgerOldName) {
            Ledger::where('name', $this->editingLedgerOldName)->update([
                'name' => $name,
                'group' => $group,
                'serial' => intval($this->newLedgerSerial),
            ]);

            Payment::where('ledger', $this->editingLedgerOldName)->update(['ledger' => $name]);
            $this->paymentsList = Payment::all()->toArray();

            if ($this->selectedLedger === $this->editingLedgerOldName) {
                $this->selectedLedger = $name;
            }
            $this->showNewKhotiyanModal = false;
            $this->dispatch('show-toast', message: 'খতিয়ান সফলভাবে আপডেট করা হয়েছে।', type: 'success');
        } else {
            Ledger::create([
                'serial' => intval($this->newLedgerSerial),
                'name' => $name,
                'group' => $group,
                'rate' => 0.00,
                'divisor' => 1,
            ]);
            $this->selectedLedger = $name;
            $this->showNewKhotiyanModal = false;
            $this->showKhotiyanModal = false;
            $this->dispatch('show-toast', message: 'খতিয়ান সফলভাবে যোগ করা হয়েছে।', type: 'success');
        }
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
            $this->quantity = (float) $payment['qty'];
            $this->rate = (float) $payment['rate'];
            $this->totalBill = (float) $payment['total'];
            $this->advance = (float) $payment['advance'];
            $this->deduction = (float) $payment['deduction'];
            $this->paymentAmount = (float) $payment['payment'];
            $this->purchaseReceive = (float) $payment['purchase_receive'];
            $this->paymentType = 'রেগুলার';

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
        $this->validate([
            'selectedLedger' => 'required',
            'paymentType' => 'required',
            'paymentAmount' => 'required|numeric'
        ], [
            'selectedLedger.required' => 'খতিয়ান নির্বাচন করুন',
            'paymentType.required' => 'পেমেন্টের ধরণ সিলেক্ট করুন',
            'paymentAmount.required' => 'পেমেন্ট পরিমাণ লিখুন'
        ]);

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

        if ($this->editingId) {
            $paymentModel = Payment::find($this->editingId);
            if ($paymentModel) {
                $paymentModel->update([
                    'date' => $formattedDate,
                    'ledger' => $this->selectedLedger,
                    'desc' => $this->paymentDesc,
                    'qty' => $this->quantity,
                    'rate' => $this->rate,
                    'total' => $this->totalBill,
                    'advance' => $this->advance,
                    'deduction' => $this->deduction,
                    'payment' => $this->paymentAmount,
                    'purchase_receive' => $this->purchaseReceive,
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
                'qty' => $this->quantity,
                'rate' => $this->rate,
                'total' => $this->totalBill,
                'advance' => $this->advance,
                'deduction' => $this->deduction,
                'payment' => $this->paymentAmount,
                'purchase_receive' => $this->purchaseReceive,
                'doc_url' => $docUrl,
                'has_doc' => $hasDoc
            ]);
            $this->dispatch('show-toast', message: 'পেমেন্ট সফলভাবে সংরক্ষণ করা হয়েছে।', type: 'success');
        }

        $this->paymentsList = Payment::all()->toArray();
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
            $this->paymentsList = Payment::all()->toArray();
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
            $payments = array_slice($payments, 0, 3);
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

        $groupsJson = Setting::get('ledger_groups');
        $savedGroups = $groupsJson ? (json_decode($groupsJson, true) ?: []) : [];
        $dbGroups = $dbLedgers->pluck('group')->filter()->unique()->toArray();
        $defaultGroups = ['কাস্টমার', 'সরবরাহকারী', 'লেবার', 'মাটি', 'স্টাফ', 'খরচ', 'আয়', 'অন্যান্য'];

        $this->ledgerGroups = array_values(array_filter(array_unique(array_merge($savedGroups, $dbGroups, $defaultGroups)), fn($g) => trim($g) !== ''));

        $groupedLedgers = [];
        foreach ($this->ledgerGroups as $grp) {
            $groupedLedgers[$grp] = [];
        }

        foreach ($dbLedgers as $ledger) {
            $g = trim($ledger->group) ?: 'অন্যান্য';
            if (!isset($groupedLedgers[$g])) {
                $groupedLedgers[$g] = [];
            }
            $groupedLedgers[$g][] = [
                'id' => $ledger->id,
                'name' => $ledger->name,
                'group' => $ledger->group,
                'rate' => $ledger->rate,
                'serial' => $ledger->serial,
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
            'payments' => $paginatedPayments,
            'totalPaymentsSum' => $totalPaymentsSum,
            'groupedLedgers' => $groupedLedgers
        ]);
    }
}