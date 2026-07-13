<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\Ledger;

class LedgerAdd extends Component
{
    public $search = '';
    public $name = '';
    public $group = 'কাস্টমার';
    public $rate = '';
    public $divisor = 1;

    public $editingLedgerId = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'group' => 'required|string|max:255',
        'rate' => 'nullable|numeric|min:0',
        'divisor' => 'required|integer|min:1',
    ];

    protected $messages = [
        'name.required' => 'খতিয়ানের নাম আবশ্যক।',
        'group.required' => 'গ্রুপ আবশ্যক।',
        'divisor.required' => 'পরিমাণ ভাজক আবশ্যক।',
        'divisor.integer' => 'পরিমাণ ভাজক অবশ্যই একটি পূর্ণসংখ্যা হতে হবে।',
        'divisor.min' => 'পরিমাণ ভাজক কমপক্ষে ১ হতে হবে।',
    ];

    public function mount()
    {
        // Preseed defaults if empty
        if (Ledger::count() === 0) {
            Ledger::create(['name' => 'কেএইচ-০১ (আব্দুল কুদ্দুস)', 'group' => 'কাস্টমার', 'rate' => 9.00, 'divisor' => 1]);
            Ledger::create(['name' => 'কেএইচ-০২ (করিম এন্টারপ্রাইজ)', 'group' => 'কাস্টমার', 'rate' => 8.50, 'divisor' => 1]);
            Ledger::create(['name' => 'এসটি-০৫ (মাটি সরবরাহকারী)', 'group' => 'সরবরাহকারী', 'rate' => 1500.00, 'divisor' => 100]);
            Ledger::create(['name' => 'এমজি-০৩ (কয়লা হিসাব)', 'group' => 'খরচ', 'rate' => 20000.00, 'divisor' => 1]);
        }
    }

    public function save()
    {
        $this->validate();

        if ($this->editingLedgerId) {
            $ledger = Ledger::find($this->editingLedgerId);
            if ($ledger) {
                $ledger->update([
                    'name' => $this->name,
                    'group' => $this->group,
                    'rate' => $this->rate ?: null,
                    'divisor' => $this->divisor,
                ]);
                session()->flash('message', 'খতিয়ান সফলভাবে আপডেট করা হয়েছে।');
            }
        } else {
            Ledger::create([
                'name' => $this->name,
                'group' => $this->group,
                'rate' => $this->rate ?: null,
                'divisor' => $this->divisor,
            ]);
            session()->flash('message', 'নতুন খতিয়ান সফলভাবে যুক্ত করা হয়েছে।');
        }

        $this->resetForm();
    }

    public function editLedger($id)
    {
        $ledger = Ledger::find($id);
        if ($ledger) {
            $this->editingLedgerId = $ledger->id;
            $this->name = $ledger->name;
            $this->group = $ledger->group;
            $this->rate = $ledger->rate;
            $this->divisor = $ledger->divisor;
        }
    }

    public function deleteLedger($id)
    {
        Ledger::destroy($id);
        session()->flash('message', 'খতিয়ান সফলভাবে মুছে ফেলা হয়েছে।');
        $this->resetForm();
    }

    public function cancelEdit()
    {
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['name', 'rate', 'editingLedgerId']);
        $this->group   = 'কাস্টমার';
        $this->divisor = 1;
    }

    public function render()
    {
        $query = Ledger::query();

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('group', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.settings.ledger-add', [
            'ledgers' => $query->orderBy('id', 'desc')->get()
        ]);
    }
}
