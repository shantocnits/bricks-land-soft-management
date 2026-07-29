<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Ledger;
use App\Models\Setting;

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

    // Custom List variables for খতিয়ানের গ্রুপ and টাইপ
    public array $ledgerGroups = ['লেবার', 'মাটি', 'স্টাফ', 'অন্যান্য'];
    public array $ledgerTypes = []; // ডিফল্ট সম্পূর্ণ ফাঁকা, আপনি যা অ্যাড করবেন সেটাই থাকবে

    // Sub-items map for ledgers (mapped parent to list of children)
    public array $subItemsMap = [];
    public array $explicitTypesMap = [];

    // শুধুমাত্র ঐ নির্দিষ্ট খতিয়ানের সাথে লিংক থাকা ডাইনামিক টাইপটিই হোভার ড্রপডাউনে দেখাবে
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
        'মেল', 'লোড মিস্ত্রি', 'বেজা মাটি',
        '১ নং মেল', '২ নং মেল', '৩ নং মেল', 'পোড়াই',
        'তেইলি লেবার', 'রাবিশ ম্যান', 'ক্লিন পরিষ্কার', 'সাদা মাটি',
        'লাল মাটি', 'অফিসিয়াল খরচ', 'কারেন্ট বিল', 'হাওয়ার তেল',
        'ভাটি স্টাফ', 'স্টাফ খরচ', 'মোটরসাইকেল', 'বেকু',
        'মেসি', 'জমির টাকা', 'বালু', 'খড়ির হিসাব',
        'ফর্মার হিসাব', 'মালামাল', 'মেরামত বিল', 'অনুদান',
        'লেবার খরচ', 'কাস্টমার কম দেওয়া', 'জমা স্টক', 'অন্যান্য'
    ];

    // Report tab state
    public string $reportTab = 'date'; // 'date' or 'all'

    // Payment History List (Mock Data)
    public array $paymentsList = [
        [
            'id' => 1,
            'date' => '18/07/2026',
            'ledger' => '১ নং মেল',
            'desc' => 'কয়লা লোড করার বিল পেমেন্ট',
            'qty' => 1500,
            'rate' => 12,
            'total' => 18000,
            'advance' => 5000,
            'deduction' => 1000,
            'payment' => 12000,
            'purchase_receive' => 18000,
            'doc_url' => 'https://images.unsplash.com/photo-1554415707-6e8cfc93fe23?auto=format&fit=crop&w=800&q=80',
            'has_doc' => true
        ],
        [
            'id' => 2,
            'date' => '18/07/2026',
            'ledger' => 'তেইলি লেবার',
            'desc' => 'সাপ্তাহিক মজুরি বিতরণ',
            'qty' => 45,
            'rate' => 500,
            'total' => 22500,
            'advance' => 0,
            'deduction' => 500,
            'payment' => 22000,
            'purchase_receive' => 22500,
            'doc_url' => '#',
            'has_doc' => false
        ],
        [
            'id' => 3,
            'date' => '18/07/2026',
            'ledger' => 'লোড মিস্ত্রি',
            'desc' => 'ভাটি লোডিং লেবার পেমেন্ট',
            'qty' => 120,
            'rate' => 300,
            'total' => 36000,
            'advance' => 10000,
            'deduction' => 0,
            'payment' => 26000,
            'purchase_receive' => 36000,
            'doc_url' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
            'has_doc' => true
        ],
        [
            'id' => 4,
            'date' => '18/07/2026',
            'ledger' => 'কারেন্ট বিল',
            'desc' => 'জুন ২০২৬ মাসের বিদ্যুৎ বিল',
            'qty' => 1,
            'rate' => 14500,
            'total' => 14500,
            'advance' => 0,
            'deduction' => 0,
            'payment' => 14500,
            'purchase_receive' => 14500,
            'doc_url' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=800&q=80',
            'has_doc' => true
        ],
        [
            'id' => 5,
            'date' => '18/07/2026',
            'ledger' => 'অফিসিয়াল খরচ',
            'desc' => 'স্টেশনারি ও অফিস চা খরচ',
            'qty' => 1,
            'rate' => 2450,
            'total' => 2450,
            'advance' => 0,
            'deduction' => 0,
            'payment' => 2450,
            'purchase_receive' => 2450,
            'doc_url' => '#',
            'has_doc' => false
        ]
    ];

    public function mount()
    {
        $this->dateFilter = now()->format('Y-m-d');
        $this->paymentDate = now()->format('Y-m-d');

        // Load ledger groups from Setting DB (same source as LedgerAdd settings tab)
        $groupsJson = Setting::get('ledger_groups');
        if ($groupsJson) {
            $this->ledgerGroups = json_decode($groupsJson, true) ?: ['লেবার', 'মাটি', 'স্টাফ', 'খরচ', 'কাস্টমার', 'অন্যান্য'];
        } else {
            $this->ledgerGroups = ['লেবার', 'মাটি', 'স্টাফ', 'খরচ', 'কাস্টমার', 'অন্যান্য'];
        }

        // Default first group
        $this->newLedgerGroup = count($this->ledgerGroups) > 0 ? $this->ledgerGroups[0] : '';

        // Seed default payments to database if empty
        if (\App\Models\Payment::count() === 0) {
            $seeds = [
                ['date' => '18/07/2026', 'ledger' => '১ নং মেল', 'desc' => 'কয়লা লোড করার বিল পেমেন্ট', 'qty' => 1500, 'rate' => 12, 'total' => 18000, 'advance' => 5000, 'deduction' => 1000, 'payment' => 12000, 'purchase_receive' => 18000, 'doc_url' => 'https://images.unsplash.com/photo-1554415707-6e8cfc93fe23?auto=format&fit=crop&w=800&q=80', 'has_doc' => true],
            ];
            foreach ($seeds as $p) {
                \App\Models\Payment::create($p);
            }
        }

        // Load payments list from database
        $this->paymentsList = \App\Models\Payment::all()->toArray();
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
        $this->totalBill = (float)$this->quantity * (float)$this->rate;
    }

    public function selectLedger(string $ledger)
    {
        $this->selectedLedger = $ledger;
        $this->showKhotiyanModal = false;

        $dbLedger = Ledger::where('name', $ledger)->first();
        if ($dbLedger && $dbLedger->rate) {
            $this->rate = (float)$dbLedger->rate;
            $this->calculateTotalBill();
        }
    }

    // Dynamic Group Actions (synced to Setting DB)
    public function addGroup(string $name)
    {
        $name = trim($name);
        if ($name !== '' && !in_array($name, $this->ledgerGroups)) {
            array_unshift($this->ledgerGroups, $name);
            Setting::set('ledger_groups', json_encode($this->ledgerGroups));
            $this->newLedgerGroup = $name;
            $this->dispatch('show-toast', message: 'গ্রুপ যুক্ত করা হয়েছে।', type: 'success');
        }
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
        $this->editingLedgerOldName = null;
        $this->newLedgerName = '';
        $this->newLedgerGroup = $preselectedGroup ?: (count($this->ledgerGroups) > 0 ? $this->ledgerGroups[0] : '');
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

        if ($this->editingLedgerOldName) {
            // Update in database
            Ledger::where('name', $this->editingLedgerOldName)->update([
                'name' => $name,
                'group' => $group,
                'serial' => intval($this->newLedgerSerial),
            ]);
            // Also update payment records
            \App\Models\Payment::where('ledger', $this->editingLedgerOldName)->update(['ledger' => $name]);
            $this->paymentsList = \App\Models\Payment::all()->toArray();
            if ($this->selectedLedger === $this->editingLedgerOldName) {
                $this->selectedLedger = $name;
            }
            $this->showNewKhotiyanModal = false;
            $this->dispatch('show-toast', message: 'খতিয়ান সফলভাবে আপডেট করা হয়েছে।', type: 'success');
        } else {
            // Create in database
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
        $payment = null;
        foreach ($this->paymentsList as $item) {
            if ($item['id'] === $id) {
                $payment = $item;
                break;
            }
        }

        if ($payment) {
            $this->editingId = $id;
            $this->selectedLedger = $payment['ledger'];
            $this->paymentDesc = $payment['desc'];
            $this->quantity = $payment['qty'];
            $this->rate = $payment['rate'];
            $this->totalBill = $payment['total'];
            $this->advance = $payment['advance'];
            $this->deduction = $payment['deduction'];
            $this->paymentAmount = $payment['payment'];
            $this->purchaseReceive = $payment['purchase_receive'];
            $this->paymentType = 'রেগুলার';
            
            if (isset($payment['date'])) {
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
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
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
            $paymentModel = \App\Models\Payment::find($this->editingId);
            if ($paymentModel) {
                $paymentModel->date = $formattedDate;
                $paymentModel->ledger = $this->selectedLedger;
                $paymentModel->desc = $this->paymentDesc;
                $paymentModel->qty = $this->quantity;
                $paymentModel->rate = $this->rate;
                $paymentModel->total = $this->totalBill;
                $paymentModel->advance = $this->advance;
                $paymentModel->deduction = $this->deduction;
                $paymentModel->payment = $this->paymentAmount;
                $paymentModel->purchase_receive = $this->purchaseReceive;
                if ($this->documentFile) {
                    $paymentModel->has_doc = $hasDoc;
                    $paymentModel->doc_url = $docUrl;
                }
                $paymentModel->save();
            }
            $this->dispatch('show-toast', message: 'পেমেন্ট সফলভাবে আপডেট করা হয়েছে।', type: 'success');
        } else {
            \App\Models\Payment::create([
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

        // Reload paymentsList from database
        $this->paymentsList = \App\Models\Payment::all()->toArray();

        $this->resetForm();
        $this->showPaymentModal = false;
    }

    public function resetForm()
    {
        $this->reset([
            'selectedLedger', 'paymentType', 'paymentDesc',
            'quantity', 'rate', 'totalBill', 'advance',
            'deduction', 'paymentAmount', 'purchaseReceive', 'documentFile', 'editingId'
        ]);
        $this->paymentDate = now()->format('Y-m-d');
    }

    public function confirmDelete(int $id)
    {
        if (auth()->check() && auth()->user()->hasRole('demo')) {
            $this->dispatch('show-toast', message: 'ডেমো মোডে পেমেন্ট মুছে ফেলা সম্ভব নয়।', type: 'danger');
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
            $this->dispatch('show-toast', message: 'ডেমো মোডে পেমেন্ট মুছে ফেলা সম্ভব নয়।', type: 'danger');
            $this->confirmingDeleteId = null;
            return;
        }

        if ($this->confirmingDeleteId) {
            \App\Models\Payment::destroy($this->confirmingDeleteId);
            $this->paymentsList = \App\Models\Payment::all()->toArray();
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
        // Filter payments list based on search and date filter
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

        // Calculate total payments sum
        $totalPaymentsSum = array_reduce($filteredPayments, function ($carry, $item) {
            return $carry + $item['payment'];
        }, 0);

        // Load ledgers from DB
        $dbLedgers = Ledger::orderByRaw('CASE WHEN serial IS NULL THEN 1 ELSE 0 END, serial ASC, id ASC')->get();

        // Get groups from settings or DB
        $groupsJson = Setting::get('ledger_groups');
        $allGroups = $groupsJson ? (json_decode($groupsJson, true) ?: []) : [];
        $dbGroups = $dbLedgers->pluck('group')->unique()->toArray();
        $mergedGroups = array_values(array_unique(array_merge($allGroups, $dbGroups)));

        $groupedLedgers = [];
        foreach ($mergedGroups as $grp) {
            if (trim($grp) !== '') {
                $groupedLedgers[$grp] = [];
            }
        }

        foreach ($dbLedgers as $ledger) {
            $g = $ledger->group ?: 'অন্যান্য';
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

        // Search filter if khotiyanSearch is filled
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

        // Paginate payments
        $paginatedPayments = array_slice($filteredPayments, 0, $this->perPage);

        return view('livewire.payment-khata', [
            'payments' => $paginatedPayments,
            'totalPaymentsSum' => $totalPaymentsSum,
            'groupedLedgers' => $groupedLedgers
        ]);
    }
}