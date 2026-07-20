<?php

namespace App\Livewire\DueLedger;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Challan;
use App\Models\Ledger;
use App\Models\ActivityLog;

class DueToday extends Component
{
    use WithPagination;

    public $search = '';
    public $date = '';
    public $showModal = false;
    public $editingId = null;
    public int $perPage = 10;

    // Form fields for updating
    public $customer_id = '';
    public $customer_name = '';
    public $customer_phone = '';
    public $customer_address = '';
    public $season = '২৫-২৬';
    public $total_due = 0;
    public $cash = 0;
    public $new_due = 0;
    public $due_payment_date = '';
    public $notes = '';
    public $send_sms = false;

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

    public function closeModal()
    {
        $this->showModal = false;
        $this->editingId = null;
    }

    public function edit($id)
    {
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
        } else {
            $this->customer_id = '';
        }

        // Calculate total due (excluding this challan's current state)
        $this->total_due = Challan::where(fn($q) => $q->where('customer_name', $challan->customer_name)->orWhere('customer_phone', $challan->customer_phone))
            ->where('id', '!=', $challan->id)
            ->sum('due') + $challan->grand_total; // total due before cash is subtracted for this challan

        $this->new_due = $this->total_due - $this->cash;
        $this->showModal = true;
    }

    public function updatedCash($value)
    {
        $this->new_due = floatval($this->total_due) - floatval($value ?: 0);
    }

    public function save()
    {
        $this->validate([
            'cash' => 'required|numeric|min:0',
            'due_payment_date' => 'nullable|date',
            'notes' => 'nullable|string'
        ]);

        $challan = Challan::findOrFail($this->editingId);
        
        $oldCash = floatval($challan->cash);
        $newCash = floatval($this->cash);
        
        $challan->cash = $newCash;
        $challan->due = $challan->grand_total - $newCash;
        $challan->due_payment_date = $this->due_payment_date ?: null;
        $challan->notes = $this->notes;
        $challan->send_sms = $this->send_sms;
        $challan->save();

        if ($oldCash != $newCash) {
            ActivityLog::log(
                'পেমেন্ট আপডেট',
                "গ্রাহকঃ {$challan->customer_name} (চালান নং {$challan->challan_no}) • নগদঃ {$oldCash} -> {$newCash}"
            );
        } else {
            ActivityLog::log(
                'চালান তথ্য আপডেট',
                "গ্রাহকঃ {$challan->customer_name} (চালান নং {$challan->challan_no}) তথ্য আপডেট করা হয়েছে।"
            );
        }

        session()->flash('message', 'চালান তথ্য সফলভাবে আপডেট করা হয়েছে।');
        $this->closeModal();
    }

    public function delete($id)
    {
        $challan = Challan::findOrFail($id);
        $name = $challan->customer_name;
        $no = $challan->challan_no;
        $challan->delete();

        ActivityLog::log(
            'চালান মুছে ফেলা',
            "গ্রাহকঃ {$name} (চালান নং {$no}) মুছে ফেলা হয়েছে।"
        );

        session()->flash('message', 'চালান সফলভাবে মুছে ফেলা হয়েছে।');
    }

    public function getLedgerId($customerName)
    {
        $ledger = Ledger::where('name', $customerName)->first();
        return $ledger ? $ledger->id : '—';
    }

    public function render()
    {
        $query = Challan::with('items')->where('due', '>', 0);

        if ($this->date) {
            $query->whereDate('due_payment_date', $this->date);
        }

        if ($this->search) {
            $query->where(function($q) {
                $q->where('customer_name', 'like', '%' . $this->search . '%')
                  ->orWhere('customer_phone', 'like', '%' . $this->search . '%')
                  ->orWhere('notes', 'like', '%' . $this->search . '%')
                  ->orWhere('challan_no', 'like', '%' . $this->search . '%');
            });
        }

        $query->orderBy('due_payment_date', 'asc');

        $challans = $query->paginate($this->perPage);
        $totalDueSum = (clone $query)->sum('due');

        return view('livewire.due-ledger.due-today', [
            'challans' => $challans,
            'totalDueSum' => $totalDueSum
        ])->layout('layouts.app');
    }
}
