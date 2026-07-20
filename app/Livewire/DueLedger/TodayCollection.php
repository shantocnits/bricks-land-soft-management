<?php

namespace App\Livewire\DueLedger;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Challan;
use App\Models\Ledger;
use App\Models\ActivityLog;

class TodayCollection extends Component
{
    use WithPagination;

    public $search = '';
    public $date = '';
    public $showModal = false;
    public $editingId = null;
    public int $perPage = 10;

    // Form fields
    public $customer_id = '';
    public $customer_name = '';
    public $customer_phone = '';
    public $customer_address = '';
    public $season = '২৫-২৬';
    public $total_due = 0;
    public $cash = 0;
    public $new_due = 0;
    public $due_payment_date = '';
    public $send_sms = false;
    public $notes = '';

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        $this->date = now()->toDateString();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedDate()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function updatedCustomerId($value)
    {
        $value = trim($value);
        if ($value && is_numeric($value)) {
            $ledger = Ledger::find($value);
            if ($ledger) {
                $this->customer_name = $ledger->name;
                
                // Fetch details from latest challan
                $latestChallan = Challan::where('customer_name', $ledger->name)
                    ->latest()
                    ->first();
                if ($latestChallan) {
                    $this->customer_phone = $latestChallan->customer_phone;
                    $this->customer_address = $latestChallan->customer_address;
                } else {
                    $this->customer_phone = '';
                    $this->customer_address = '';
                }

                // Calculate total due (excluding current editing challan if any)
                $dueQuery = Challan::where(function($q) use ($ledger) {
                    $q->where('customer_name', $ledger->name);
                    if ($this->customer_phone) {
                        $q->orWhere('customer_phone', $this->customer_phone);
                    }
                });

                if ($this->editingId) {
                    $dueQuery->where('id', '!=', $this->editingId);
                }

                $this->total_due = $dueQuery->sum('due');
                $this->calculateNewDue();
                return;
            }
        }
        
        $this->customer_name = '';
        $this->customer_phone = '';
        $this->customer_address = '';
        $this->total_due = 0;
        $this->new_due = 0;
    }

    public function updatedCash($value)
    {
        $this->calculateNewDue();
    }

    public function calculateNewDue()
    {
        $this->new_due = floatval($this->total_due) - floatval($this->cash ?: 0);
    }

    public function openAddModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->customer_id = '';
        $this->customer_name = '';
        $this->customer_phone = '';
        $this->customer_address = '';
        $this->total_due = 0;
        $this->cash = 0;
        $this->new_due = 0;
        $this->due_payment_date = '';
        $this->send_sms = false;
        $this->notes = '';
        $this->resetValidation();
    }

    public function generateChallanNo()
    {
        $lastId = Challan::max('id') ?: 0;
        return (string)($lastId + 1);
    }

    public function save()
    {
        $this->validate([
            'customer_name' => 'required|string|max:255',
            'cash' => 'required|numeric|min:0.01',
            'due_payment_date' => 'nullable|date',
            'notes' => 'nullable|string'
        ], [
            'customer_name.required' => 'কাস্টমার আইডি দিয়ে কাস্টমার নির্বাচন করুন।',
            'cash.required' => 'জমার পরিমাণ আবশ্যক।',
            'cash.min' => 'জমার পরিমাণ শূন্যের বেশি হতে হবে।',
        ]);

        $due = 0 - floatval($this->cash);

        $challanData = [
            'customer_type' => 'old',
            'customer_phone' => $this->customer_phone,
            'customer_name' => $this->customer_name,
            'customer_address' => $this->customer_address,
            'challan_no' => $this->editingId ? Challan::find($this->editingId)->challan_no : $this->generateChallanNo(),
            'date' => $this->date ?: now()->toDateString(),
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

        if ($this->editingId) {
            $challan = Challan::findOrFail($this->editingId);
            $challan->update($challanData);
            session()->flash('message', 'জমা তথ্য সফলভাবে আপডেট করা হয়েছে।');
        } else {
            $challan = Challan::create($challanData);
            session()->flash('message', 'নতুন জমা তথ্য সফলভাবে সংরক্ষণ করা হয়েছে।');
        }

        // Auto-save Ledger just in case
        if ($this->customer_name) {
            Ledger::firstOrCreate(
                ['name' => trim($this->customer_name)],
                ['group' => 'চালান গ্রাহক', 'rate' => 0, 'divisor' => 1]
            );
        }

        ActivityLog::log(
            $this->editingId ? 'জমা আপডেট' : 'নতুন জমা',
            "গ্রাহকঃ {$this->customer_name}। পরিমাণঃ {$this->cash} টাকা।"
        );

        $this->closeModal();
    }

    public function edit($id)
    {
        $this->resetForm();
        $challan = Challan::findOrFail($id);
        $this->editingId = $id;
        $this->customer_name = $challan->customer_name;
        $this->customer_phone = $challan->customer_phone;
        $this->customer_address = $challan->customer_address;
        $this->cash = $challan->cash;
        $this->notes = $challan->notes;
        $this->send_sms = $challan->send_sms;
        $this->due_payment_date = $challan->due_payment_date ? \Carbon\Carbon::parse($challan->due_payment_date)->toDateString() : '';

        // Find customer Ledger ID
        $ledger = Ledger::where('name', $challan->customer_name)->first();
        if ($ledger) {
            $this->customer_id = $ledger->id;
        }

        // Previous due is sum of due of all other challans
        $this->total_due = Challan::where(fn($q) => $q->where('customer_name', $challan->customer_name)->orWhere('customer_phone', $challan->customer_phone))
            ->where('id', '!=', $challan->id)
            ->sum('due');

        $this->calculateNewDue();
        $this->showModal = true;
    }

    public function delete($id)
    {
        $challan = Challan::findOrFail($id);
        $name = $challan->customer_name;
        $cash = $challan->cash;
        $challan->delete();

        ActivityLog::log(
            'জমা মুছে ফেলা',
            "গ্রাহকঃ {$name}। পরিমাণঃ {$cash} টাকা।"
        );

        session()->flash('message', 'জমা তথ্য মুছে ফেলা হয়েছে।');
    }

    public function getPreviousDue($challan)
    {
        return Challan::where(function($q) use ($challan) {
                $q->where('customer_name', $challan->customer_name);
                if ($challan->customer_phone) {
                    $q->orWhere('customer_phone', $challan->customer_phone);
                }
            })
            ->where('id', '<', $challan->id)
            ->sum('due');
    }

    public function getLedgerId($customerName)
    {
        $ledger = Ledger::where('name', $customerName)->first();
        return $ledger ? $ledger->id : '—';
    }

    public function render()
    {
        $query = Challan::where('grand_total', 0)->where('cash', '>', 0);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('customer_name', 'like', '%' . $this->search . '%')
                  ->orWhere('customer_phone', 'like', '%' . $this->search . '%')
                  ->orWhere('notes', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->date) {
            $query->whereDate('date', $this->date);
        }

        $query->orderBy('id', 'desc');

        $collections = $query->paginate($this->editingId ? 100 : $this->perPage);
        $totalCollectionSum = (clone $query)->sum('cash');

        return view('livewire.due-ledger.today-collection', [
            'collections' => $collections,
            'totalCollectionSum' => $totalCollectionSum
        ])->layout('layouts.app');
    }
}
