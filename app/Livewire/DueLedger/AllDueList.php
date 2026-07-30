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
    public $seasonFilter = 'all'; // all, 25-26, 23-24
    public $dateFrom = '';
    public $dateTo = '';
    public int $perPage = 10;

    // Modal visibilities
    public $showDateModal = false;
    public $showCollectionModal = false;
    public $showSmsModal = false;

    // Selected state
    public $selectedChallanId = null;

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

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function setSeasonFilter($season)
    {
        $this->seasonFilter = $season;
        $this->resetPage();
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

        session()->flash('message', 'পরিশোধের তারিখ সফলভাবে আপডেট করা হয়েছে।');
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
        $this->cash = 0;
        $this->notes = '';
        $this->send_sms = false;
        $this->due_payment_date = '';

        // Find customer Ledger ID
        $ledger = Ledger::where('name', $challan->customer_name)->first();
        if ($ledger) {
            $this->customer_id = $ledger->id;
        } else {
            $this->customer_id = '';
        }

        // Calculate total due (sum of all challan dues)
        $this->total_due = Challan::where(fn($q) => $q->where('customer_name', $challan->customer_name)->orWhere('customer_phone', $challan->customer_phone))
            ->sum('due');

        $this->new_due = $this->total_due;
        $this->showCollectionModal = true;
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

        $due = 0 - floatval($this->cash);

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
            'due' => $due,
            'send_sms' => $this->send_sms,
            'due_payment_date' => $this->due_payment_date ?: null,
        ];

        Challan::create($challanData);

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

        session()->flash('message', 'নতুন জমা তথ্য সফলভাবে সংরক্ষণ করা হয়েছে।');
        $this->closeModal();
    }

    public function generateChallanNo()
    {
        $lastId = Challan::max('id') ?: 0;
        return (string)($lastId + 1);
    }

    // Action 3: SMS Modal
    public function openSmsModal($id)
    {
        $challan = Challan::findOrFail($id);
        $this->selectedChallanId = $id;
        $this->sms_name = $challan->customer_name;
        $this->sms_phone = $challan->customer_phone ?: '—';
        $this->sms_text = "প্রিয় {$challan->customer_name}, ডেমো ব্রিকস-এ আপনার চালান নং {$challan->challan_no} এর বকেয়া বাকি ৳" . number_format($challan->due) . " টাকা। অনুগ্রহ করে দ্রুত পরিশোধ করুন। ধন্যবাদ।";
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

        session()->flash('message', 'গ্রাহককে সফলভাবে বকেয়ার এসএমএস পাঠানো হয়েছে।');
        $this->closeModal();
    }

    public function getLedgerId($customerName)
    {
        $ledger = Ledger::where('name', $customerName)->first();
        return $ledger ? $ledger->id : '—';
    }

    public function render()
    {
        $query = Challan::with('items')->where('due', '>', 0);

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

        // Season filter dummy logic (since we just show season ২৫-২৬ always)
        // If they select 25-26, it lists them. If they select others, it returns empty/others.
        if ($this->seasonFilter !== 'all') {
            if ($this->seasonFilter !== '25-26') {
                $query->where('id', '<', 0); // empty list for other seasons
            }
        }

        $query->orderBy('id', 'desc');

        $challans = $query->paginate($this->perPage);
        $totalDueSum = (clone $query)->sum('due');

        return view('livewire.due-ledger.all-due-list', [
            'challans' => $challans,
            'totalDueSum' => $totalDueSum
        ])->layout('layouts.app');
    }
}
