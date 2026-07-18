<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

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
        $this->dateFilter = '2026-07-18';
        $this->paymentDate = '2026-07-18';
        $this->newLedgerSerial = count($this->ledgers) + 1;

        // Initialize ledger groups map
        foreach ($this->ledgers as $ledger) {
            if (in_array($ledger, ['মেল', 'লোড মিস্ত্রি', 'তেইলি লেবার', 'লেবার খরচ'])) {
                $this->ledgerGroupsMap[$ledger] = 'লেবার';
            } elseif (in_array($ledger, ['বেজা মাটি', 'সাদা মাটি', 'লাল মাটি'])) {
                $this->ledgerGroupsMap[$ledger] = 'মাটি';
            } elseif (in_array($ledger, ['ভাটি স্টাফ', 'স্টাফ খরচ'])) {
                $this->ledgerGroupsMap[$ledger] = 'স্টাফ';
            } else {
                $this->ledgerGroupsMap[$ledger] = 'অন্যান্য';
            }
        }

        // ডিফল্ট কোনো টাইপ ম্যাপ করা নেই
        $this->explicitTypesMap = [];
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
        $this->dispatch('show-toast', message: 'খতিয়ান নির্বাচন করা হয়েছে।', type: 'success');
    }

    // Dynamic Lists Actions (Add/Delete)
    public function addGroup(string $name)
    {
        $name = trim($name);
        if ($name !== '' && !in_array($name, $this->ledgerGroups)) {
            $this->ledgerGroups[] = $name;
            $this->newLedgerGroup = $name;
            $this->dispatch('show-toast', message: 'গ্রুপ যুক্ত করা হয়েছে।', type: 'success');
        }
    }

    public function deleteGroup(string $name)
    {
        $this->ledgerGroups = array_values(array_filter($this->ledgerGroups, fn($g) => $g !== $name));
        if ($this->newLedgerGroup === $name) {
            $this->newLedgerGroup = 'অন্যান্য';
        }
        $this->dispatch('show-toast', message: 'গ্রুপ মুছে ফেলা হয়েছে।', type: 'success');
    }

    public function addType(string $name)
    {
        $name = trim($name);
        if ($name !== '') {
            if (!in_array($name, $this->ledgerTypes)) {
                $this->ledgerTypes[] = $name;
            }
            $this->newLedgerType = $name;
            
            if ($this->newLedgerName !== '') {
                $this->explicitTypesMap[trim($this->newLedgerName)] = $name;
            }
            
            $this->dispatch('show-toast', message: 'টাইপ যুক্ত করা হয়েছে এবং সিলেক্ট করা হয়েছে।', type: 'success');
        }
    }

    public function deleteType(string $name)
    {
        $this->ledgerTypes = array_values(array_filter($this->ledgerTypes, fn($t) => $t !== $name));
        if ($this->newLedgerType === $name) {
            $this->newLedgerType = '';
        }
        
        foreach ($this->explicitTypesMap as $ledger => $type) {
            if ($type === $name) {
                unset($this->explicitTypesMap[$ledger]);
            }
        }
        
        $this->dispatch('show-toast', message: 'টাইপ মুছে ফেলা হয়েছে।', type: 'success');
    }

    public function openNewKhotiyanModal()
    {
        $this->editingLedgerOldName = null;
        $this->newLedgerName = '';
        $this->newLedgerGroup = 'অন্যান্য';
        $this->newLedgerType = '';
        $this->newLedgerSerial = count($this->ledgers) + 1;
        $this->showNewKhotiyanModal = true;
    }

    public function openEditLedgerModal(string $ledgerName)
    {
        $this->editingLedgerOldName = $ledgerName;
        $this->newLedgerName = $ledgerName;
        $this->newLedgerGroup = isset($this->ledgerGroupsMap[$ledgerName]) ? $this->ledgerGroupsMap[$ledgerName] : 'অন্যান্য';
        
        $index = array_search($ledgerName, $this->ledgers);
        $this->newLedgerSerial = $index !== false ? $index + 1 : count($this->ledgers) + 1;
        
        $this->newLedgerType = isset($this->explicitTypesMap[$ledgerName]) ? $this->explicitTypesMap[$ledgerName] : '';
        
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
        $type = trim($this->newLedgerType);

        if ($this->editingLedgerOldName) {
            $oldName = $this->editingLedgerOldName;
            
            // Update ledgers list
            foreach ($this->ledgers as $key => $lName) {
                if ($lName === $oldName) {
                    $this->ledgers[$key] = $name;
                    break;
                }
            }

            // Update active inputs
            if ($this->selectedLedger === $oldName) {
                $this->selectedLedger = $name;
            }

            // Update payments list
            foreach ($this->paymentsList as &$pay) {
                if ($pay['ledger'] === $oldName) {
                    $pay['ledger'] = $name;
                }
            }

            // Update groups map
            unset($this->ledgerGroupsMap[$oldName]);
            $this->ledgerGroupsMap[$name] = $group;

            // Update explicitTypesMap link
            unset($this->explicitTypesMap[$oldName]);
            if ($type !== '') {
                $this->explicitTypesMap[$name] = $type;
                if (!in_array($type, $this->ledgerTypes)) {
                    $this->ledgerTypes[] = $type;
                }
            }

            $this->showNewKhotiyanModal = false;
            $this->dispatch('show-toast', message: 'খতিয়ান সফলভাবে আপডেট করা হয়েছে।', type: 'success');
        } else {
            if (!in_array($name, $this->ledgers)) {
                $this->ledgers[] = $name;
            }
            $this->ledgerGroupsMap[$name] = $group;
            
            if ($type !== '') {
                $this->explicitTypesMap[$name] = $type;
                if (!in_array($type, $this->ledgerTypes)) {
                    $this->ledgerTypes[] = $type;
                }
            }
            
            $this->selectedLedger = $name;
            $this->showNewKhotiyanModal = false;
            $this->dispatch('show-toast', message: 'খতিয়ান সফলভাবে যোগ করা হয়েছে।', type: 'success');
        }
    }

    public function deleteLedger(string $ledgerName)
    {
        $this->ledgers = array_values(array_filter($this->ledgers, function ($name) use ($ledgerName) {
            return $name !== $ledgerName;
        }));

        if ($this->selectedLedger === $ledgerName) {
            $this->selectedLedger = '';
        }

        if (isset($this->explicitTypesMap[$ledgerName])) {
            unset($this->explicitTypesMap[$ledgerName]);
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
            $this->dispatch('show-toast', message: 'পেমেন্ট সম্পাদনা মোড চালু হয়েছে।', type: 'success');
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
            foreach ($this->paymentsList as &$item) {
                if ($item['id'] === $this->editingId) {
                    $item['date'] = $formattedDate;
                    $item['ledger'] = $this->selectedLedger;
                    $item['desc'] = $this->paymentDesc;
                    $item['qty'] = $this->quantity;
                    $item['rate'] = $this->rate;
                    $item['total'] = $this->totalBill;
                    $item['advance'] = $this->advance;
                    $item['deduction'] = $this->deduction;
                    $item['payment'] = $this->paymentAmount;
                    $item['purchase_receive'] = $this->purchaseReceive;
                    if ($this->documentFile) {
                        $item['has_doc'] = $hasDoc;
                        $item['doc_url'] = $docUrl;
                    }
                    break;
                }
            }
            $this->dispatch('show-toast', message: 'পেমেন্ট সফলভাবে আপডেট করা হয়েছে।', type: 'success');
        } else {
            $this->paymentsList[] = [
                'id' => count($this->paymentsList) + 1,
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
            ];
            $this->dispatch('show-toast', message: 'পেমেন্ট সফলভাবে সংরক্ষণ করা হয়েছে।', type: 'success');
        }

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
        $this->paymentDate = '2026-07-18';
    }

    public function deletePayment(int $id)
    {
        $this->paymentsList = array_values(array_filter($this->paymentsList, function ($item) use ($id) {
            return $item['id'] !== $id;
        }));
        $this->dispatch('show-toast', message: 'পেমেন্ট ডিলিট করা হয়েছে।', type: 'success');
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
        $this->subItemsMap = $this->getSubItemsMap();

        // Filter payments list based on search
        $filteredPayments = array_filter($this->paymentsList, function ($payment) {
            $matchesSearch = true;
            if ($this->search !== '') {
                $term = strtolower($this->search);
                $matchesSearch = str_contains(strtolower($payment['ledger']), $term) ||
                                str_contains(strtolower($payment['desc']), $term);
            }
            return $matchesSearch;
        });

        // Calculate total payments sum
        $totalPaymentsSum = array_reduce($filteredPayments, function ($carry, $item) {
            return $carry + $item['payment'];
        }, 0);

        // Filter ledgers search
        $filteredLedgers = array_filter($this->ledgers, function ($ledger) {
            if ($this->khotiyanSearch === '') return true;
            return str_contains(strtolower($ledger), strtolower($this->khotiyanSearch));
        });

        // Paginate payments (Dynamic rows based on perPage)
        $paginatedPayments = array_slice($filteredPayments, 0, $this->perPage);

        return view('livewire.payment-khata', [
            'payments' => $paginatedPayments,
            'totalPaymentsSum' => $totalPaymentsSum,
            'filteredLedgers' => $filteredLedgers
        ]);
    }
}