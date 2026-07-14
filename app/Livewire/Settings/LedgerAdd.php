<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\Ledger;
use App\Models\Setting;

class LedgerAdd extends Component
{
    public $search = '';
    public $name = '';
    public $group = 'কাস্টমার';
    public $rate = '';
    public $divisor = 1;

    // Modal and Edit Controls
    public $showModal = false;
    public $editingLedgerId = null;

    // Dynamic dropdown options management
    public $groupOptions = [];
    public $newGroupInput = '';

    public function rules()
    {
        $allowed = implode(',', $this->groupOptions ?: ['কাস্টমার', 'সরবরাহকারী', 'খরচ', 'আয়', 'অন্যান্য']);
        return [
            'name' => 'required|string|max:255',
            'group' => 'required|in:' . $allowed,
            'rate' => 'nullable|numeric|min:0',
            'divisor' => 'required|integer|min:1',
        ];
    }

    protected $messages = [
        'name.required' => 'খতিয়ানের নাম আবশ্যক।',
        'group.required' => 'গ্রুপ আবশ্যক।',
        'group.in' => 'গ্রুপ নির্বাচন সঠিক নয়।',
        'divisor.required' => 'পরিমাণ ভাজক আবশ্যক।',
        'divisor.integer' => 'পরিমাণ ভাজক অবশ্যই একটি পূর্ণসংখ্যা হতে হবে।',
        'divisor.min' => 'পরিমাণ ভাজক কমপক্ষে ১ হতে হবে।',
    ];

    public function mount()
    {
        // Load Ledger Groups from settings DB or set default list
        $groupsJson = Setting::get('ledger_groups');
        if (!$groupsJson) {
            $this->groupOptions = ['কাস্টমার', 'সরবরাহকারী', 'খরচ', 'আয়', 'অন্যান্য'];
            Setting::set('ledger_groups', json_encode($this->groupOptions));
        } else {
            $this->groupOptions = json_decode($groupsJson, true) ?: ['কাস্টমার', 'সরবরাহকারী', 'খরচ', 'আয়', 'অন্যান্য'];
        }

        if (count($this->groupOptions) > 0) {
            $this->group = $this->groupOptions[0];
        }

        // Preseed defaults if empty
        if (Ledger::count() === 0) {
            Ledger::create(['name' => 'কেএইচ-০১ (আব্দুল কুদ্দুস)', 'group' => 'কাস্টমার', 'rate' => 9.00, 'divisor' => 1]);
            Ledger::create(['name' => 'কেএইচ-০২ (করিম এন্টারপ্রাইজ)', 'group' => 'কাস্টমার', 'rate' => 8.50, 'divisor' => 1]);
            Ledger::create(['name' => 'এসটি-০৫ (মাটি সরবরাহকারী)', 'group' => 'সরবরাহকারী', 'rate' => 1500.00, 'divisor' => 100]);
            Ledger::create(['name' => 'এমজি-০৩ (কয়লা হিসাব)', 'group' => 'খরচ', 'rate' => 20000.00, 'divisor' => 1]);
        }
    }

    public function addGroup()
    {
        $newGroup = trim($this->newGroupInput);
        if ($newGroup !== '' && !in_array($newGroup, $this->groupOptions)) {
            $this->groupOptions[] = $newGroup;
            Setting::set('ledger_groups', json_encode($this->groupOptions));
            $this->group = $newGroup;
            $this->newGroupInput = '';
            session()->flash('group_message', 'নতুন গ্রুপ যুক্ত করা হয়েছে।');
        }
    }

    public function deleteGroup($groupToDelete)
    {
        if (count($this->groupOptions) <= 1) {
            session()->flash('group_error', 'কমপক্ষে একটি গ্রুপ থাকতে হবে।');
            return;
        }

        $this->groupOptions = array_values(array_diff($this->groupOptions, [$groupToDelete]));
        Setting::set('ledger_groups', json_encode($this->groupOptions));
        
        if ($this->group === $groupToDelete) {
            $this->group = $this->groupOptions[0];
        }
        session()->flash('group_message', 'গ্রুপ মুছে ফেলা হয়েছে।');
    }

    public function openAddModal()
    {
        $this->resetForm();
        if (count($this->groupOptions) > 0) {
            $this->group = $this->groupOptions[0];
        }
        $this->showModal = true;
    }

    public function openModal()
    {
        $this->openAddModal();
    }

    public function save()
    {
        // Block action if logged in as Demo
        if (auth()->user()->hasRole('demo')) {
            session()->flash('message', 'ডেমো মোডে খতিয়ান পরিবর্তন করা সম্ভব নয়।');
            $this->showModal = false;
            return;
        }

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

        $this->showModal = false;
        $this->resetForm();
    }

    public function editLedger($id)
    {
        // Block action if logged in as Demo
        if (auth()->user()->hasRole('demo')) {
            session()->flash('message', 'ডেমো মোডে খতিয়ান সংশোধন করা সম্ভব নয়।');
            return;
        }

        $ledger = Ledger::find($id);
        if ($ledger) {
            $this->editingLedgerId = $ledger->id;
            $this->name = $ledger->name;
            $this->group = $ledger->group;
            $this->rate = $ledger->rate;
            $this->divisor = $ledger->divisor;
            $this->showModal = true;
        }
    }

    public function deleteLedger($id)
    {
        // Block action if logged in as Demo
        if (auth()->user()->hasRole('demo')) {
            session()->flash('message', 'ডেমো মোডে খতিয়ান মুছে ফেলা সম্ভব নয়।');
            return;
        }

        Ledger::destroy($id);
        session()->flash('message', 'খতিয়ান সফলভাবে মুছে ফেলা হয়েছে।');
        $this->resetForm();
    }

    public function cancelEdit()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['name', 'rate', 'editingLedgerId']);
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
