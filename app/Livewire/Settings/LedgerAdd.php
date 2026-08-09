<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\Ledger;
use App\Models\Setting;
use App\Models\Payment;

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

    // Group Manager Modal
    public $showGroupManager = false;
    public $confirmingDeleteGroup = null;

    // Dynamic dropdown options management
    public $groupOptions = [];
    public $newGroupInput = '';

    public function rules()
    {
        return [
            'serial' => 'nullable',
            'name' => 'required|string|max:255',
            'group' => 'required|string|max:255',
            'rate' => 'nullable|numeric|min:0',
            'divisor' => 'nullable|numeric|min:1',
        ];
    }

    protected $messages = [
        'name.required' => 'খতিয়ানের নাম আবশ্যক।',
        'group.required' => 'গ্রুপ আবশ্যক।',
        'group.in' => 'গ্রুপ নির্বাচন সঠিক নয়।',
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
        $savedGroups = $groupsJson ? (json_decode($groupsJson, true) ?: []) : [];
        
        $dbGroups = Ledger::whereNotNull('group')
            ->pluck('group')
            ->map(fn($g) => trim($g))
            ->filter(fn($g) => $g !== '')
            ->unique()
            ->values()
            ->toArray();

        $merged = array_merge($savedGroups, $dbGroups);
        
        $seen = [];
        $unique = [];
        foreach ($merged as $g) {
            $trimmed = trim($g);
            if ($trimmed === '') continue;
            $lower = mb_strtolower($trimmed, 'UTF-8');
            if (!isset($seen[$lower])) {
                $seen[$lower] = true;
                $unique[] = $trimmed;
            }
        }

        $this->groupOptions = array_values($unique);
    }

    public function mount()
    {
        $this->syncGroupOptions();
    }

    public function addGroup($name = null)
    {
        $newGroup = trim($name !== null ? $name : $this->newGroupInput);
        if ($newGroup !== '') {
            $this->syncGroupOptions();

            // Check case-insensitively if group exists
            $existingGroup = null;
            foreach ($this->groupOptions as $opt) {
                if (mb_strtolower(trim($opt), 'UTF-8') === mb_strtolower($newGroup, 'UTF-8')) {
                    $existingGroup = $opt;
                    break;
                }
            }

            if ($existingGroup) {
                $this->group = $existingGroup;
                $this->newGroupInput = '';
                $this->dispatch('show-toast', message: '"' . $existingGroup . '" গ্রুপটি ইতিমধ্যেই বিদ্যমান।', type: 'warning');
                return;
            }

            $groupsJson = Setting::get('ledger_groups');
            $savedGroups = $groupsJson ? (json_decode($groupsJson, true) ?: []) : [];

            $savedGroups = array_values(array_filter($savedGroups, fn($g) => mb_strtolower(trim($g), 'UTF-8') !== mb_strtolower($newGroup, 'UTF-8')));
            array_unshift($savedGroups, $newGroup);
            Setting::set('ledger_groups', json_encode($savedGroups));

            $this->syncGroupOptions();

            $this->group = $newGroup;
            $this->newGroupInput = '';
            $this->dispatch('show-toast', message: 'নতুন গ্রুপ যুক্ত করা হয়েছে।', type: 'success');
        }
    }

    public function openGroupManager()
    {
        $this->syncGroupOptions();
        $this->confirmingDeleteGroup = null;
        $this->showGroupManager = true;
    }

    public function closeGroupManager()
    {
        $this->showGroupManager = false;
        $this->confirmingDeleteGroup = null;
    }

    public function askDeleteGroup($groupName)
    {
        $this->confirmingDeleteGroup = $groupName;
    }

    public function cancelDeleteGroup()
    {
        $this->confirmingDeleteGroup = null;
    }

    public function deleteGroupConfirmed()
    {
        $groupToDelete = $this->confirmingDeleteGroup;
        if (!$groupToDelete) return;

        if (count($this->groupOptions) <= 1) {
            $this->dispatch('show-toast', message: 'কমপক্ষে একটি গ্রুপ থাকতে হবে।', type: 'danger');
            $this->confirmingDeleteGroup = null;
            return;
        }

        $this->groupOptions = array_values(array_diff($this->groupOptions, [$groupToDelete]));
        Setting::set('ledger_groups', json_encode($this->groupOptions));

        if ($this->group === $groupToDelete) {
            $this->group = '';
        }

        $this->confirmingDeleteGroup = null;
        $this->dispatch('show-toast', message: '"' . $groupToDelete . '" গ্রুপ মুছে ফেলা হয়েছে।', type: 'success');
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
        $maxSerial = (int) (Ledger::max('serial') ?: Ledger::count());
        $this->serial = sprintf('%02d', $maxSerial + 1);
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

        $group = trim($this->group ?? '');
        $name = trim($this->name ?? '');

        if ($group !== '') {
            $groupsJson = Setting::get('ledger_groups');
            $savedGroups = $groupsJson ? (json_decode($groupsJson, true) ?: []) : [];

            $savedGroups = array_values(array_diff($savedGroups, [$group]));
            array_unshift($savedGroups, $group);
            Setting::set('ledger_groups', json_encode($savedGroups));
            $this->syncGroupOptions();
        }

        if ($this->editingLedgerId) {
            $ledger = Ledger::find($this->editingLedgerId);
            if ($ledger) {
                $oldName = $ledger->name;
                $ledger->update([
                    'serial' => $this->serial ? intval($this->serial) : $ledger->serial,
                    'name' => $name,
                    'group' => $group,
                    'rate' => $this->rate !== null && $this->rate !== '' ? floatval($this->rate) : null,
                    'divisor' => $this->divisor ? intval($this->divisor) : 1,
                ]);

                if ($oldName && $oldName !== $name) {
                    Payment::where('ledger', $oldName)->update(['ledger' => $name]);
                }

                $this->dispatch('ledger-updated');
                $this->dispatch('show-toast', message: 'খতিয়ান তথ্য আপডেট করা হয়েছে।', type: 'success');
            }
        } else {
            $maxSerial = (int) (Ledger::max('serial') ?: Ledger::count());
            $newSerial = $this->serial ? intval($this->serial) : ($maxSerial + 1);

            Ledger::create([
                'serial' => $newSerial,
                'name' => $name,
                'group' => $group,
                'rate' => $this->rate !== null && $this->rate !== '' ? floatval($this->rate) : null,
                'divisor' => $this->divisor ? intval($this->divisor) : 1,
            ]);

            $this->page = 1;
            $this->dispatch('ledger-added');
            $this->dispatch('show-toast', message: 'নতুন খতিয়ান তৈরি করা হয়েছে।', type: 'success');
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

    protected $listeners = [
        'ledger-added' => '$refresh',
        'ledger-updated' => '$refresh',
    ];

    public function render()
    {
        $query = Ledger::query();

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('group', 'like', '%' . $this->search . '%');
            });
        }

        $allLedgers = $query->orderBy('id', 'desc')->get();
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
