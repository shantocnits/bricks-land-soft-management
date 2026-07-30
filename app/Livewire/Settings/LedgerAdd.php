<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\Ledger;
use App\Models\Setting;

class LedgerAdd extends Component
{
    public $search = '';
    public $serial = '';
    public $name = '';
    public $group = '';
    public $rate = '';
    public $divisor = 1;

    // Modal and Edit Controls
    public $showModal = false;
    public $editingLedgerId = null;
    public $confirmingDeleteId = null;

    // Dynamic dropdown options management
    public $groupOptions = [];
    public $newGroupInput = '';

    public function rules()
    {
        return [
            'serial' => 'nullable',
            'name' => 'nullable|string|max:255',
            'group' => 'required|string|max:255',
            'rate' => 'nullable|numeric|min:0',
            'divisor' => 'required|integer|min:1',
        ];
    }

    protected $messages = [
        'group.required' => 'গ্রুপ আবশ্যক।',
        'group.in' => 'গ্রুপ নির্বাচন সঠিক নয়।',
        'divisor.required' => 'পরিমাণ ভাজক আবশ্যক।',
        'divisor.integer' => 'পরিমাণ ভাজক অবশ্যই একটি পূর্ণসংখ্যা হতে হবে।',
        'divisor.min' => 'পরিমাণ ভাজক কমপক্ষে ১ হতে হবে।',
    ];

    public $perPage = 10;
    public $page = 1;

    public function setPerPage($size)
    {
        $this->perPage = $size;
        $this->page = 1;
    }

    public function setPage($page)
    {
        $this->page = max(1, (int) $page);
    }

    public function syncGroupOptions()
    {
        $groupsJson = Setting::get('ledger_groups');
        $defaultGroups = ['কাস্টমার', 'সরবরাহকারী', 'লেবার', 'মাটি', 'স্টাফ', 'খরচ', 'আয়', 'অন্যান্য'];
        $savedGroups = $groupsJson ? (json_decode($groupsJson, true) ?: $defaultGroups) : $defaultGroups;
        
        $dbGroups = Ledger::whereNotNull('group')
            ->pluck('group')
            ->map(fn($g) => trim($g))
            ->filter(fn($g) => $g !== '')
            ->unique()
            ->values()
            ->toArray();

        $dbNames = Ledger::whereNotNull('name')
            ->pluck('name')
            ->map(fn($n) => trim($n))
            ->filter(fn($n) => $n !== '' && $n !== '—')
            ->unique()
            ->values()
            ->toArray();

        $merged = array_values(array_unique(array_merge($savedGroups, $dbGroups, $dbNames, $defaultGroups)));
        $this->groupOptions = array_values(array_filter($merged, fn($g) => trim($g) !== ''));
    }

    public function mount()
    {
        // Preseed defaults if empty
        if (Ledger::count() === 0) {
            $defaultLedgers = [
                ['name' => 'কেএইচ-০১ (আব্দুল কুদ্দুস)', 'group' => 'কাস্টমার', 'rate' => 9.00, 'divisor' => 1],
                ['name' => 'কেএইচ-০২ (করিম এন্টারপ্রাইজ)', 'group' => 'কাস্টমার', 'rate' => 8.50, 'divisor' => 1],
                ['name' => 'এসটি-০৫ (মাটি সরবরাহকারী)', 'group' => 'সরবরাহকারী', 'rate' => 1500.00, 'divisor' => 100],
                ['name' => 'এমজি-০৩ (কয়লা হিসাব)', 'group' => 'খরচ', 'rate' => 20000.00, 'divisor' => 1],
                ['name' => 'মেল', 'group' => 'লেবার', 'rate' => null, 'divisor' => 1],
                ['name' => 'লোড মিস্ত্রি', 'group' => 'লেবার', 'rate' => null, 'divisor' => 1],
                ['name' => 'বেজা মাটি', 'group' => 'মাটি', 'rate' => null, 'divisor' => 1],
                ['name' => '১ নং মেল', 'group' => 'লেবার', 'rate' => null, 'divisor' => 1],
                ['name' => '২ নং মেল', 'group' => 'লেবার', 'rate' => null, 'divisor' => 1],
                ['name' => '৩ নং মেল', 'group' => 'লেবার', 'rate' => null, 'divisor' => 1],
                ['name' => 'পোড়াই', 'group' => 'লেবার', 'rate' => null, 'divisor' => 1],
                ['name' => 'তেইলি লেবার', 'group' => 'লেবার', 'rate' => null, 'divisor' => 1],
                ['name' => 'রাবিশ ম্যান', 'group' => 'লেবার', 'rate' => null, 'divisor' => 1],
                ['name' => 'ক্লিন পরিষ্কার', 'group' => 'লেবার', 'rate' => null, 'divisor' => 1],
                ['name' => 'সাদা মাটি', 'group' => 'মাটি', 'rate' => null, 'divisor' => 1],
                ['name' => 'লাল মাটি', 'group' => 'মাটি', 'rate' => null, 'divisor' => 1],
                ['name' => 'অফিসিয়াল খরচ', 'group' => 'খরচ', 'rate' => null, 'divisor' => 1],
                ['name' => 'কারেন্ট বিল', 'group' => 'খরচ', 'rate' => null, 'divisor' => 1],
                ['name' => 'হাওয়ার তেল', 'group' => 'খরচ', 'rate' => null, 'divisor' => 1],
                ['name' => 'ভাটি স্টাফ', 'group' => 'স্টাফ', 'rate' => null, 'divisor' => 1],
                ['name' => 'স্টাফ খরচ', 'group' => 'স্টাফ', 'rate' => null, 'divisor' => 1],
                ['name' => 'মোটরসাইকেল', 'group' => 'খরচ', 'rate' => null, 'divisor' => 1],
                ['name' => 'বেকু', 'group' => 'অন্যান্য', 'rate' => null, 'divisor' => 1],
                ['name' => 'মেসি', 'group' => 'অন্যান্য', 'rate' => null, 'divisor' => 1],
                ['name' => 'জমির টাকা', 'group' => 'অন্যান্য', 'rate' => null, 'divisor' => 1],
                ['name' => 'বালু', 'group' => 'অন্যান্য', 'rate' => null, 'divisor' => 1],
                ['name' => 'খড়ির হিসাব', 'group' => 'অন্যান্য', 'rate' => null, 'divisor' => 1],
                ['name' => 'ফর্মার হিসাব', 'group' => 'অন্যান্য', 'rate' => null, 'divisor' => 1],
                ['name' => 'মালামাল', 'group' => 'অন্যান্য', 'rate' => null, 'divisor' => 1],
                ['name' => 'মেরামত বিল', 'group' => 'খরচ', 'rate' => null, 'divisor' => 1],
                ['name' => 'অনুদান', 'group' => 'অন্যান্য', 'rate' => null, 'divisor' => 1],
                ['name' => 'লেবার খরচ', 'group' => 'লেবার', 'rate' => null, 'divisor' => 1],
                ['name' => 'কাস্টমার কম দেওয়া', 'group' => 'কাস্টমার', 'rate' => null, 'divisor' => 1],
                ['name' => 'জমা স্টক', 'group' => 'অন্যান্য', 'rate' => null, 'divisor' => 1],
                ['name' => 'অন্যান্য', 'group' => 'অন্যান্য', 'rate' => null, 'divisor' => 1],
            ];
            $i = 1;
            foreach ($defaultLedgers as $item) {
                Ledger::create([
                    'serial' => $i++,
                    'name' => $item['name'],
                    'group' => $item['group'],
                    'rate' => $item['rate'],
                    'divisor' => $item['divisor']
                ]);
            }
        }

        $this->syncGroupOptions();
        Setting::set('ledger_groups', json_encode($this->groupOptions));
    }

    public function addGroup($name = null)
    {
        $newGroup = trim($name !== null ? $name : $this->newGroupInput);
        if ($newGroup !== '') {
            $groupsJson = Setting::get('ledger_groups');
            $defaultGroups = ['কাস্টমার', 'সরবরাহকারী', 'লেবার', 'মাটি', 'স্টাফ', 'খরচ', 'আয়', 'অন্যান্য'];
            $savedGroups = $groupsJson ? (json_decode($groupsJson, true) ?: $defaultGroups) : $defaultGroups;

            // Remove $newGroup if it exists anywhere so unshifting puts it at the top
            $savedGroups = array_values(array_diff($savedGroups, [$newGroup]));
            array_unshift($savedGroups, $newGroup);
            Setting::set('ledger_groups', json_encode($savedGroups));

            $this->syncGroupOptions();

            $this->group = $newGroup;
            $this->newGroupInput = '';
            $this->dispatch('show-toast', message: 'নতুন গ্রুপ যুক্ত করা হয়েছে।', type: 'success');
        }
    }

    public function deleteGroup($groupToDelete)
    {
        if (count($this->groupOptions) <= 1) {
            $this->dispatch('show-toast', message: 'কমপক্ষে একটি গ্রুপ থাকতে হবে।', type: 'danger');
            return;
        }

        $this->groupOptions = array_values(array_diff($this->groupOptions, [$groupToDelete]));
        Setting::set('ledger_groups', json_encode($this->groupOptions));

        if ($this->group === $groupToDelete) {
            $this->group = '';
        }
        $this->dispatch('show-toast', message: 'গ্রুপ মুছে ফেলা হয়েছে।', type: 'success');
    }

    public function openAddModal()
    {
        $this->resetForm();
        $this->syncGroupOptions();
        $nextSerial = Ledger::count() + 1;
        $this->serial = sprintf('%02d', $nextSerial);
        $this->group = '';
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
            $this->dispatch('show-toast', message: 'ডেমো মোডে খতিয়ান পরিবর্তন করা সম্ভব নয়।', type: 'danger');
            $this->showModal = false;
            return;
        }

        $this->validate();

        $ledgerName = $this->name !== null && trim($this->name) !== '' ? trim($this->name) : '—';

        if ($this->group && trim($this->group) !== '') {
            $grp = trim($this->group);
            $groupsJson = Setting::get('ledger_groups');
            $defaultGroups = ['কাস্টমার', 'সরবরাহকারী', 'লেবার', 'মাটি', 'স্টাফ', 'খরচ', 'আয়', 'অন্যান্য'];
            $savedGroups = $groupsJson ? (json_decode($groupsJson, true) ?: $defaultGroups) : $defaultGroups;

            $savedGroups = array_values(array_diff($savedGroups, [$grp]));
            array_unshift($savedGroups, $grp);
            Setting::set('ledger_groups', json_encode($savedGroups));
            $this->syncGroupOptions();
        }

        if ($this->editingLedgerId) {
            $ledger = Ledger::find($this->editingLedgerId);
            if ($ledger) {
                $ledger->update([
                    'serial' => $this->serial ? intval($this->serial) : null,
                    'name' => $ledgerName,
                    'group' => $this->group,
                    'rate' => $this->rate ?: null,
                    'divisor' => $this->divisor,
                ]);
                $this->dispatch('show-toast', message: 'খতিয়ান সফলভাবে আপডেট করা হয়েছে।', type: 'success');
            }
        } else {
            Ledger::create([
                'serial' => $this->serial ? intval($this->serial) : null,
                'name' => $ledgerName,
                'group' => $this->group,
                'rate' => $this->rate ?: null,
                'divisor' => $this->divisor,
            ]);
            $this->dispatch('show-toast', message: 'নতুন খতিয়ান সফলভাবে যুক্ত করা হয়েছে।', type: 'success');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function editLedger($id)
    {
        // Block action if logged in as Demo
        if (auth()->user()->hasRole('demo')) {
            $this->dispatch('show-toast', message: 'ডেমো মোডে খতিয়ান সংশোধন করা সম্ভব নয়।', type: 'danger');
            return;
        }

        $this->syncGroupOptions();
        $ledger = Ledger::find($id);
        if ($ledger) {
            $this->editingLedgerId = $ledger->id;
            $this->serial = $ledger->serial ? sprintf('%02d', $ledger->serial) : '';
            $this->name = $ledger->name;
            $this->group = $ledger->group;
            $this->rate = $ledger->rate;
            $this->divisor = $ledger->divisor;
            $this->showModal = true;
        }
    }

    public function confirmDelete($id)
    {
        if (auth()->user()->hasRole('demo')) {
            $this->dispatch('show-toast', message: 'ডেমো মোডে খতিয়ান মুছে ফেলা সম্ভব নয়।', type: 'danger');
            return;
        }
        $this->confirmingDeleteId = $id;
    }

    public function cancelDelete()
    {
        $this->confirmingDeleteId = null;
    }

    public function deleteLedgerConfirmed()
    {
        if (auth()->user()->hasRole('demo')) {
            $this->dispatch('show-toast', message: 'ডেমো মোডে খতিয়ান মুছে ফেলা সম্ভব নয়।', type: 'danger');
            $this->confirmingDeleteId = null;
            return;
        }

        if ($this->confirmingDeleteId) {
            Ledger::destroy($this->confirmingDeleteId);
            $this->dispatch('show-toast', message: 'খতিয়ান সফলভাবে মুছে ফেলা হয়েছে।', type: 'success');
            $this->confirmingDeleteId = null;
        }
        $this->resetForm();
    }

    public function cancelEdit()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['serial', 'name', 'rate', 'editingLedgerId', 'group']);
        $this->divisor = 1;
    }

    public function render()
    {
        $query = Ledger::query();

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('group', 'like', '%' . $this->search . '%');
            });
        }

        $allLedgers = $query->orderByRaw('CASE WHEN serial IS NULL THEN 1 ELSE 0 END, serial ASC, id ASC')->get();
        $totalCount = $allLedgers->count();

        if ($this->perPage === 'all' || $this->perPage == 0) {
            $ledgers = $allLedgers;
            $totalPages = 1;
            $this->page = 1;
        } else {
            $perPageInt = (int) $this->perPage > 0 ? (int) $this->perPage : 10;
            $totalPages = max(1, (int) ceil($totalCount / $perPageInt));
            if ($this->page > $totalPages) {
                $this->page = $totalPages;
            }
            $offset = ($this->page - 1) * $perPageInt;
            $ledgers = $allLedgers->slice($offset, $perPageInt);
        }

        $this->syncGroupOptions();

        return view('livewire.settings.ledger-add', [
            'ledgers' => $ledgers,
            'totalCount' => $totalCount,
            'totalPages' => $totalPages,
            'currentPage' => $this->page,
        ]);
    }
}
