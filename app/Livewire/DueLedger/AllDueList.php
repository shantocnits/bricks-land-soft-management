<?php

namespace App\Livewire\DueLedger;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Challan;
use App\Models\Ledger;
use App\Models\ActivityLog;

class AllDueList extends Component
{
    use WithPagination;

    public $search = '';
    public $seasonFilter = 'all';
    public $dateFrom = '';
    public $dateTo = '';
    public int $perPage = 10;

    // Modal visibilities
    public $showDateModal = false;
    public $showCollectionModal = false;
    public $showSmsModal = false;
    public $showPrintModal = false;
    public $printChallan = null;
    public $isDuePrint = false;
    public $selectedChallanId = null;

    public function openPrintModal($challanId)
    {
        $this->printChallan = Challan::with('items')->find($challanId);
        $this->isDuePrint = true;
        $this->showPrintModal = true;
    }

    public function closePrintModal()
    {
        $this->showPrintModal = false;
        $this->isDuePrint = false;
        $this->printChallan = null;
    }

    // Date Update variables
    public $new_payment_date = '';

    // Collection Modal variables
    public $customer_id = '';
    public $customer_name = '';
    public $customer_phone = '';
    public $customer_address = '';
    public $season = '২৫-২৬';
    public $total_due = '';
    public $cash = '';
    public $new_due = '';
    public $due_payment_date = '';
    public $notes = '';
    public $send_sms = false;

    // SMS Modal variables
    public $sms_text = '';
    public $sms_count = 1;
    public $sms_name = '';
    public $sms_phone = '';

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        $this->seasonFilter = \App\Models\Setting::get('season', '২৫-২৬');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedDateFrom()
    {
        $this->resetPage();
    }

    public function updatedDateTo()
    {
        $this->resetPage();
    }

    public function updatedSeasonFilter()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function setSeasonFilter($season)
    {
        $this->seasonFilter = $season;
        $this->resetPage();
    }

    // Dynamic season list (same as the topbar dropdown)
    private function seasonOptions()
    {
        $bnNum = function ($num) {
            $eng = ['0','1','2','3','4','5','6','7','8','9'];
            $bn = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
            return str_replace($eng, $bn, (string)$num);
        };

        $activeSeason = \App\Models\Setting::get('season', '২৫-২৬');
        $currentYearTwoDigit = (int)date('y');
        $seasons = [];

        for ($i = -3; $i <= -1; $i++) {
            $y1 = sprintf('%02d', $currentYearTwoDigit + $i);
            $y2 = sprintf('%02d', $currentYearTwoDigit + $i + 1);
            $seasons[] = $bnNum($y1) . '-' . $bnNum($y2);
        }
        if (!in_array($activeSeason, $seasons)) {
            array_unshift($seasons, $activeSeason);
        }

        return $seasons;
    }

    // Modal Close logic
    public function closeModal()
    {
        $this->showDateModal = false;
        $this->showCollectionModal = false;
        $this->showSmsModal = false;
        $this->selectedChallanId = null;
        $this->resetValidation();
    }

    // Action 1: Date Update Modal
    public function openDateModal($id)
    {
        $challan = Challan::findOrFail($id);
        $this->selectedChallanId = $id;
        $this->new_payment_date = $challan->due_payment_date ? \Carbon\Carbon::parse($challan->due_payment_date)->toDateString() : '';
        $this->notes = $challan->notes;
        $this->showDateModal = true;
    }

    public function saveDate()
    {
        $this->validate([
            'new_payment_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        $challan = Challan::findOrFail($this->selectedChallanId);
        $oldDate = $challan->due_payment_date ? \Carbon\Carbon::parse($challan->due_payment_date)->format('d-m-Y') : 'কোনো তারিখ নেই';
        $newDateFormatted = \Carbon\Carbon::parse($this->new_payment_date)->format('d-m-Y');

        $challan->due_payment_date = $this->new_payment_date;
        $challan->notes = $this->notes;
        $challan->save();

        ActivityLog::log(
            'পরিশোধের তারিখ আপডেট',
            "গ্রাহকঃ {$challan->customer_name} (চালান নং {$challan->challan_no}) • পরিশোধের তারিখ পরিবর্তনঃ {$oldDate} -> {$newDateFormatted} • নোটঃ {$this->notes}"
        );

        $this->dispatch('show-toast', message: 'পরিশোধের তারিখ সফলভাবে আপডেট করা হয়েছে।', type: 'success');
        $this->closeModal();
    }

    // Action 2: Collection Modal
    public function openCollectionModal($id)
    {
        $challan = Challan::findOrFail($id);
        $this->selectedChallanId = $id;
        $this->customer_name = $challan->customer_name;
        $this->customer_phone = $challan->customer_phone;
        $this->customer_address = $challan->customer_address;
        $this->cash = '';
        $this->notes = '';
        $this->send_sms = false;
        $this->due_payment_date = '';

        // Show the same stable customer ID (earliest challan of this customer)
        $this->customer_id = $this->customerId($challan->customer_name, $challan->customer_phone);

        // Calculate total due (net sum of all challan dues for this customer)
        $this->total_due = $this->customerNetDue($challan->customer_name, $challan->customer_phone);

        $this->new_due = $this->total_due;
        $this->showCollectionModal = true;
    }

    // Net total due for a customer (name OR phone identity, same as the collection modal)
    private function customerNetDue($name, $phone): float
    {
        $customerScope = function ($q) use ($name, $phone) {
            $q->where('customer_name', $name);
            if ($phone) {
                $q->orWhere('customer_phone', $phone);
            }
        };

        $totalSalesDue = (float) Challan::where($customerScope)->where('grand_total', '>', 0)->sum('due');
        $totalCollections = (float) Challan::where($customerScope)->where('grand_total', 0)->sum('cash');

        return max(0, $totalSalesDue - $totalCollections);
    }

    // Stable customer ID = earliest challan id of this customer (name OR phone identity)
    private function customerId($name, $phone)
    {
        return (string) Challan::where(function ($q) use ($name, $phone) {
            $q->where('customer_name', $name);
            if ($phone) {
                $q->orWhere('customer_phone', $phone);
            }
        })->min('id');
    }

    public function updatedCash($value)
    {
        $this->new_due = floatval($this->total_due) - floatval($value ?: 0);
    }

    public function saveCollection()
    {
        $this->validate([
            'cash' => 'required|numeric|min:0.01',
            'due_payment_date' => 'nullable|date',
            'notes' => 'nullable|string'
        ]);

        $challanData = [
            'customer_type' => 'old',
            'customer_phone' => $this->customer_phone,
            'customer_name' => $this->customer_name,
            'customer_address' => $this->customer_address,
            'challan_no' => $this->generateChallanNo(),
            'date' => now()->toDateString(),
            'challan_type' => 'আজকের',
            'notes' => $this->notes,
            'value' => 0,
            'total_value' => 0,
            'rent' => 0,
            'transport_rent' => 0,
            'discount' => 0,
            'grand_total' => 0,
            'cash' => $this->cash,
            'due' => 0,
            'send_sms' => $this->send_sms,
            'due_payment_date' => $this->due_payment_date ?: null,
            'season' => $this->season ?: \App\Models\Setting::get('season', '২৫-২৬'),
        ];

        Challan::create($challanData);

        if ($this->due_payment_date) {
            Challan::where(function($q) {
                $q->where('customer_name', $this->customer_name);
                if ($this->customer_phone) {
                    $q->orWhere('customer_phone', $this->customer_phone);
                }
            })->where('grand_total', '>', 0)->update(['due_payment_date' => $this->due_payment_date]);
        }

        // Auto-save Ledger just in case
        if ($this->customer_name) {
            Ledger::firstOrCreate(
                ['name' => trim($this->customer_name)],
                ['group' => 'চালান গ্রাহক', 'rate' => 0, 'divisor' => 1]
            );
        }

        ActivityLog::log(
            'নতুন জমা',
            "গ্রাহকঃ {$this->customer_name}। পরিমাণঃ {$this->cash} টাকা।"
        );

        $this->dispatch('show-toast', message: 'নতুন জমা তথ্য সফলভাবে সংরক্ষণ করা হয়েছে।', type: 'success');
        $this->closeModal();
    }

    public function generateChallanNo()
    {
        $lastId = Challan::max('id') ?: 0;
        return (string)($lastId + 1);
    }

    // Settle the customer's outstanding dues with this collection.
    // Returns the excess amount that goes as advance credit (if any).
    private function settleDuesFromCollection()
    {
        return 0.0;
    }

    // Action 3: SMS Modal
    public function openSmsModal($id)
    {
        $challan = Challan::findOrFail($id);
        $this->selectedChallanId = $id;
        $this->sms_name = $challan->customer_name;
        $this->sms_phone = $challan->customer_phone ?: '—';
        $payDate = $challan->due_payment_date
            ? \Carbon\Carbon::parse($challan->due_payment_date)->format('d-m-Y')
            : ($challan->date ? $challan->date->format('d-m-Y') : '');
        $this->sms_text = "প্রিয় {$challan->customer_name}, ডেমো ব্রিকস-এ আপনার চালান নং {$challan->challan_no} এর বকেয়া বাকি ৳" . number_format($challan->due) . " টাকা। অনুগ্রহ করে {$payDate} tarikher moddhe দ্রুত পরিশোধ করুন, ধন্যবাদ।";
        $this->sms_count = 1;
        $this->showSmsModal = true;
    }

    public function updatedSmsText($value)
    {
        // Simple logic: 1 SMS is up to 160 chars in English, or 70 in unicode (Bengali)
        $len = mb_strlen($value);
        if ($len <= 70) {
            $this->sms_count = 1;
        } elseif ($len <= 134) {
            $this->sms_count = 2;
        } else {
            $this->sms_count = 3;
        }
    }

    public function sendSms()
    {
        $challan = Challan::findOrFail($this->selectedChallanId);
        
        // Log to activity as sending SMS
        ActivityLog::log(
            'এসএমএস প্রেরণ',
            "গ্রাহকঃ {$challan->customer_name} ({$challan->customer_phone}) কে এসএমএস পাঠানো হয়েছে। বার্তাঃ \"{$this->sms_text}\""
        );

        $this->dispatch('show-toast', message: 'গ্রাহককে সফলভাবে বকেয়ার এসএমএস পাঠানো হয়েছে।', type: 'success');
        $this->closeModal();
    }

    public function render()
    {
        $query = Challan::with('items')
            ->where('due', '>', 0)
            ->whereIn('customer_name', function ($q) {
                $q->select('customer_name')
                    ->from('challans')
                    ->whereNotNull('customer_name')
                    ->where('customer_name', '!=', '')
                    ->groupBy('customer_name')
                    ->havingRaw('SUM(due) > 0');
            });

        if ($this->search) {
            $query->where(function($q) {
                $q->where('customer_name', 'like', '%' . $this->search . '%')
                  ->orWhere('customer_phone', 'like', '%' . $this->search . '%')
                  ->orWhere('notes', 'like', '%' . $this->search . '%')
                  ->orWhere('challan_no', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->dateFrom) {
            $query->whereDate('date', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('date', '<=', $this->dateTo);
        }

        // Season filter (active season from topbar, same as other pages)
        if ($this->seasonFilter !== 'all') {
            $query->where(function ($q) {
                $q->where('season', $this->seasonFilter)->orWhereNull('season');
            });
        }

        $query->orderBy('id', 'desc');

        $challans = $query->paginate($this->perPage);

        // Net due per customer (same computation as the collection modal) for row display + badge
        $netDueMap = [];
        $customerIdMap = [];
        $totalDueSum = 0;
        $allRows = (clone $query)->get(['customer_name', 'customer_phone']);
        foreach ($allRows as $row) {
            $key = $row->customer_name . '|' . ($row->customer_phone ?: '');
            if (isset($netDueMap[$key])) {
                continue;
            }
            $netDueMap[$key] = $this->customerNetDue($row->customer_name, $row->customer_phone);
            $customerIdMap[$key] = $this->customerId($row->customer_name, $row->customer_phone);
            $totalDueSum += $netDueMap[$key];
        }

        // Dynamic season list (same as the topbar dropdown)
        $seasons = collect($this->seasonOptions());

        return view('livewire.due-ledger.all-due-list', [
            'challans' => $challans,
            'totalDueSum' => $totalDueSum,
            'netDueMap' => $netDueMap,
            'customerIdMap' => $customerIdMap,
            'seasons' => $seasons
        ])->layout('layouts.app');
    }
}
