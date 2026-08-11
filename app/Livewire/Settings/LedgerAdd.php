<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\Ledger;
use App\Models\Payment;
use App\Support\LedgerGroups;

class LedgerAdd extends Component
{
    public $search = '';
    public $serial = '';
    public $name = '';
    public $group = '';
    public $group_type = 'other'; // production, expense, income, other
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
    public $newGroupType = 'other';

    // Locally added groups (not yet saved to DB — saved when ledger form is submitted)
    public $pendingGroups = [];

    public function rules()
    {
        return [
            'serial' => 'nullable',
            'name' => 'nullable|string|max:255',
            'group' => 'required|string|max:255',
            'group_type' => 'nullable|string|in:production,expense,income,other',
            'rate' => 'nullable|numeric|min:0',
            'divisor' => 'nullable|numeric|min:1',
        ];
    }

    protected $messages = [
        'group.required' => 'গ্রুপ আবশ্যক।',
        'group.in' => 'গ্রুপ নির্বাচন সঠিক নয়।',
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

    public function syncGroupOptions(bool $includeInactive = false)
    {
        $base = LedgerGroups::all(false, $includeInactive || $this->showGroupManager);
        // Bring pending groups to the top of dropdown list
        if (!$this->showGroupManager && !empty($this->pendingGroups)) {
            foreach (array_reverse($this->pendingGroups) as $pg) {
                $base = array_values(array_filter($base, fn($opt) => mb_strtolower($opt) !== mb_strtolower($pg)));
                array_unshift($base, $pg);
            }
        }
        $this->groupOptions = array_values($base);
    }

    /**
     * Add a new group to the local dropdown only (no DB save).
     * DB save happens when the ledger form is submitted.
     */
    public function addGroup(string $name): void
    {
        $name = trim($name);
        if ($name === '') {
            return;
        }

        // Filter out if already in pendingGroups or groupOptions
        $this->pendingGroups = array_values(array_filter($this->pendingGroups, fn($opt) => mb_strtolower($opt) !== mb_strtolower($name)));
        $this->groupOptions = array_values(array_filter($this->groupOptions, fn($opt) => mb_strtolower($opt) !== mb_strtolower($name)));

        // Unshift to absolute top (front of array)
        array_unshift($this->pendingGroups, $name);
        array_unshift($this->groupOptions, $name);
        $this->groupOptions = array_values($this->groupOptions);
        $this->group = $name;
        $this->newGroupInput = '';
    }

    /**
     * Save the group_type for a group in settings.
     */
    protected function saveGroupType(string $group, string $groupType): void
    {
        // group_type is stored on the ledger record itself, no separate setting needed
        // This method exists to satisfy legacy calls and is intentionally a no-op here.
    }

    public function mount()
    {
        $this->syncGroupOptions();
    }

    public function openGroupManager()
    {
        $this->syncGroupOptions(true);
        $this->confirmingDeleteGroup = null;
        $this->showGroupManager = true;
    }

    public function closeGroupManager()
    {
        $this->showGroupManager = false;
        $this->confirmingDeleteGroup = null;
        $this->syncGroupOptions(false);
    }

    public function askDeleteGroup($groupName)
    {
        $this->confirmingDeleteGroup = $groupName;
    }

    public function cancelDeleteGroup()
    {
        $this->confirmingDeleteGroup = null;
    }

    /**
     * Soft / Hard Delete Logic for Group:
     * - Has payments → soft delete (mark group as inactive, mark ledgers inactive, hide from dropdowns)
     * - No payments → hard delete
     */
    public function deleteGroupConfirmed()
    {
        $groupToDelete = $this->confirmingDeleteGroup;
        if (!$groupToDelete) return;

        if (count($this->groupOptions) <= 1) {
            $this->dispatch('show-toast', message: 'কমপক্ষে একটি গ্রুপ থাকতে হবে।', type: 'danger');
            $this->confirmingDeleteGroup = null;
            return;
        }

        // Check if any payment exists for this group's ledgers
        $groupLedgerNames = Ledger::where('group', $groupToDelete)->pluck('name')->toArray();
        $groupLedgerNames[] = $groupToDelete; // include group name itself as a ledger

        $hasPayments = Payment::whereIn('ledger', $groupLedgerNames)->exists();

        if ($hasPayments) {
            // Soft Delete — mark group inactive & mark all ledgers in this group as inactive
            Ledger::where('group', $groupToDelete)->update(['is_active' => false]);
            LedgerGroups::markInactive($groupToDelete);
            LedgerGroups::remove($groupToDelete);
            $this->dispatch('show-toast', message: '"' . $groupToDelete . '" গ্রুপে পেমেন্ট থাকায় নিষ্ক্রিয় করা হয়েছে। পেমেন্ট ইতিহাস সুরক্ষিত আছে।', type: 'warning');
        } else {
            // Hard Delete — remove from settings and DB completely
            LedgerGroups::remove($groupToDelete);
            LedgerGroups::markActive($groupToDelete);
            Ledger::where('group', $groupToDelete)->delete();
            $this->dispatch('show-toast', message: '"' . $groupToDelete . '" গ্রুপ সফলভাবে মুছে ফেলা হয়েছে।', type: 'success');
        }

        if ($this->group === $groupToDelete) {
            $this->group = '';
        }

        $this->confirmingDeleteGroup = null;
        $this->syncGroupOptions(true);
    }

    /**
     * Reactivate an inactive group:
     * - Soft-deleted ledgers (is_active=false, has payments) → restore to active
     * - Hard-deleted ledgers (no DB record) → never restored
     */
    public function reactivateGroup($groupName)
    {
        if (auth()->user()->hasRole('demo')) {
            $this->dispatch('show-toast', message: 'ডেমো মোডে এই কাজ সম্ভব নয়।', type: 'danger');
            return;
        }

        // Restore only soft-deleted (is_active=false) ledgers — hard-deleted ones have no DB record
        Ledger::where('group', $groupName)->where('is_active', false)->update(['is_active' => true]);

        LedgerGroups::markActive($groupName);
        LedgerGroups::add($groupName);
        $this->syncGroupOptions(true);
        $this->dispatch('show-toast', message: '"' . $groupName . '" গ্রুপ পুনরায় সক্রিয় করা হয়েছে।', type: 'success');
    }

    public function deleteGroup($groupToDelete)
    {
        if (count($this->groupOptions) <= 1) {
            $this->dispatch('show-toast', message: 'কমপক্ষে একটি গ্রুপ থাকতে হবে।', type: 'danger');
            return;
        }

        $this->groupOptions = array_values(array_diff($this->groupOptions, [$groupToDelete]));
        LedgerGroups::save($this->groupOptions);

        if ($this->group === $groupToDelete) {
            $this->group = '';
        }
        $this->dispatch('show-toast', message: 'গ্রুপ মুছে ফেলা হয়েছে।', type: 'success');
    }

    public function openAddModal()
    {
        $this->resetForm();
        $this->syncGroupOptions();
        $maxSerial = (int) (Ledger::max('serial') ?: Ledger::count());
        $this->serial = sprintf('%02d', $maxSerial + 1);
        $this->group = '';
        $this->group_type = 'other';
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
            $this->dispatch('show-toast', message: 'ডেমো মোডে খতিয়ান পরিবর্তন করা সম্ভব নয়।', type: 'danger');
            $this->showModal = false;
            return;
        }

        $this->validate();

        $group = trim($this->group ?? '');
        $name = trim($this->name ?? '');
        $groupType = $this->group_type ?: 'other';

        if ($group !== '') {
            // Now persist to DB (including any pending/newly-created group)
            LedgerGroups::add($group);
            $this->pendingGroups = []; // clear pending since it's now saved
            $this->syncGroupOptions();
        }

        if ($this->editingLedgerId && $name !== '') {
            $ledger = Ledger::find($this->editingLedgerId);
            if ($ledger) {
                $oldName = $ledger->name;
                $ledger->update([
                    'serial' => $this->serial ? intval($this->serial) : $ledger->serial,
                    'name' => $name,
                    'group' => $group,
                    'group_type' => $groupType,
                    'rate' => $this->rate !== null && $this->rate !== '' ? floatval($this->rate) : null,
                    'divisor' => $this->divisor ? intval($this->divisor) : 1,
                    'is_active' => true,
                ]);

                if ($oldName && $oldName !== $name) {
                    Payment::where('ledger', $oldName)->update(['ledger' => $name]);
                }

                $this->dispatch('ledger-updated');
                $this->dispatch('show-toast', message: 'খতিয়ান তথ্য আপডেট করা হয়েছে।', type: 'success');
            }
        } elseif ($name !== '') {
            // Check if existing record with same name and group exists
            $existing = Ledger::where('name', $name)->where('group', $group)->first();
            if ($existing) {
                $existing->update([
                    'serial' => $this->serial ? intval($this->serial) : $existing->serial,
                    'group_type' => $groupType,
                    'rate' => $this->rate !== null && $this->rate !== '' ? floatval($this->rate) : null,
                    'divisor' => $this->divisor ? intval($this->divisor) : 1,
                    'is_active' => true,
                ]);
            } else {
                $maxSerial = (int) (Ledger::max('serial') ?: Ledger::count());
                $newSerial = $this->serial ? intval($this->serial) : ($maxSerial + 1);

                Ledger::create([
                    'serial' => $newSerial,
                    'name' => $name,
                    'group' => $group,
                    'group_type' => $groupType,
                    'rate' => $this->rate !== null && $this->rate !== '' ? floatval($this->rate) : null,
                    'divisor' => $this->divisor ? intval($this->divisor) : 1,
                    'is_active' => true,
                ]);
            }

            $this->page = 1;
            $this->dispatch('ledger-added');
            $this->dispatch('show-toast', message: 'নতুন খতিয়ান তৈরি করা হয়েছে।', type: 'success');
        } else {
            // Only group added
            $this->dispatch('show-toast', message: 'নতুন গ্রুপ সফলভাবে যুক্ত করা হয়েছে।', type: 'success');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function editLedger($id)
    {
        // Block action if logged in as Demo
        if (auth()->user()->hasRole('demo')) {
            $this->dispatch('show-toast', message: 'ডেমো মোডে খতিয়ান সংশোধন করা সম্ভব নয়।', type: 'danger');
            return;
        }

        $this->syncGroupOptions();
        $ledger = Ledger::find($id);
        if ($ledger) {
            $this->editingLedgerId = $ledger->id;
            $this->serial = $ledger->serial ? sprintf('%02d', $ledger->serial) : '';
            $this->name = $ledger->name;
            $this->group = $ledger->group;
            $this->group_type = $ledger->group_type ?: $this->getGroupType($ledger->group);
            $this->rate = $ledger->rate;
            $this->divisor = $ledger->divisor;
            $this->showModal = true;
        }
    }

    public function confirmDelete($id)
    {
        if (auth()->user()->hasRole('demo')) {
            $this->dispatch('show-toast', message: 'ডেমো মোডে খতিয়ান মুছে ফেলা সম্ভব নয়।', type: 'danger');
            return;
        }
        $this->confirmingDeleteId = $id;
    }

    public function cancelDelete()
    {
        $this->confirmingDeleteId = null;
    }

    /**
     * Soft Delete Logic for Ledger:
     * - Has payments → soft delete (is_active = false)
     * - No payments → hard delete
     */
    public function deleteLedgerConfirmed()
    {
        if (auth()->user()->hasRole('demo')) {
            $this->dispatch('show-toast', message: 'ডেমো মোডে খতিয়ান মুছে ফেলা সম্ভব নয়।', type: 'danger');
            $this->confirmingDeleteId = null;
            return;
        }

        if ($this->confirmingDeleteId) {
            $ledger = Ledger::find($this->confirmingDeleteId);
            if ($ledger) {
                $hasPayments = Payment::where('ledger', $ledger->name)->exists();
                if ($hasPayments) {
                    // Soft Delete — keep payment history intact
                    $ledger->update(['is_active' => false]);
                    $this->dispatch('show-toast', message: '"' . $ledger->name . '" খতিয়ানে পেমেন্ট থাকায় নিষ্ক্রিয় করা হয়েছে। পেমেন্ট ইতিহাস সুরক্ষিত আছে।', type: 'warning');
                } else {
                    // Hard Delete — no payment records
                    $ledger->delete();
                    $this->dispatch('show-toast', message: 'খতিয়ান সফলভাবে মুছে ফেলা হয়েছে।', type: 'success');
                }
                // NOTE: Do NOT markInactive the group here.
                // Group dropdown visibility is controlled ONLY via "গ্রুপ বাদ দিন" modal actions.
                // Deleting a single ledger must not remove the group from the dropdown.
            }
            $this->confirmingDeleteId = null;
        }
        $this->resetForm();
    }

    /**
     * Reactivate a soft-deleted ledger.
     */
    public function reactivateLedger($id)
    {
        if (auth()->user()->hasRole('demo')) {
            $this->dispatch('show-toast', message: 'ডেমো মোডে এই কাজ সম্ভব নয়।', type: 'danger');
            return;
        }
        $ledger = Ledger::find($id);
        if ($ledger) {
            $ledger->update(['is_active' => true]);
            $this->dispatch('show-toast', message: '"' . $ledger->name . '" খতিয়ান পুনরায় সক্রিয় করা হয়েছে।', type: 'success');
        }
    }

    public function cancelEdit()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['serial', 'name', 'rate', 'editingLedgerId', 'group', 'newGroupInput']);
        $this->pendingGroups = [];
        $this->divisor = 1;
        $this->group_type = 'other';
    }

    protected $listeners = [
        'ledger-added' => '$refresh',
        'ledger-updated' => '$refresh',
    ];

    public function render()
    {
        $query = Ledger::active();

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

        $this->syncGroupOptions($this->showGroupManager);

        return view('livewire.settings.ledger-add', [
            'ledgers' => $ledgers,
            'totalCount' => $totalCount,
            'totalPages' => $totalPages,
            'currentPage' => $this->page,
        ]);
    }
}
