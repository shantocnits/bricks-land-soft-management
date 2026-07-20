<?php

namespace App\Livewire;

use App\Models\CashEntry;
use Livewire\Component;
use Livewire\WithPagination;

class CashKhata extends Component
{
    use WithPagination;

    // Search and Filters
    public string $search = '';
    public string $dateFilter = '2026-07-20';
    public int $perPage = 20;

    // Modals visibility
    public bool $showModal = false;
    public bool $showInvestModal = false;

    // Form inputs
    public ?int $editingId = null;
    public string $description = '';
    public string $cashIn = '';
    public string $cashOut = '';
    public string $time = '';
    public string $date = '';

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        // Seed default records if table is empty
        if (CashEntry::count() === 0) {
            CashEntry::create([
                'description' => 'নগদ ইট বিক্রি',
                'cash_in' => null,
                'cash_out' => null,
                'date' => '2026-07-20',
                'time' => '12:00:00',
            ]);
            CashEntry::create([
                'description' => 'বাকি কালেকশন',
                'cash_in' => 667.00,
                'cash_out' => null,
                'date' => '2026-07-20',
                'time' => '12:30:00',
            ]);
            CashEntry::create([
                'description' => 'মোট পেমেন্ট দেওয়া',
                'cash_in' => null,
                'cash_out' => null,
                'date' => '2026-07-20',
                'time' => '13:00:00',
            ]);
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingDateFilter()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->date = $this->dateFilter;
        $this->time = now()->format('H:i:s');
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function openInvestModal()
    {
        $this->showInvestModal = true;
    }

    public function closeInvestModal()
    {
        $this->showInvestModal = false;
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->description = '';
        $this->cashIn = '';
        $this->cashOut = '';
        $this->time = '';
        $this->date = '';
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate([
            'description' => 'required|string|max:255',
            'cashIn' => 'nullable|numeric|min:0',
            'cashOut' => 'nullable|numeric|min:0',
            'date' => 'required|date',
            'time' => 'required',
        ]);

        $data = [
            'description' => $this->description,
            'cash_in' => $this->cashIn !== '' ? floatval($this->cashIn) : null,
            'cash_out' => $this->cashOut !== '' ? floatval($this->cashOut) : null,
            'date' => $this->date,
            'time' => $this->time,
        ];

        if ($this->editingId) {
            $entry = CashEntry::findOrFail($this->editingId);
            $entry->update($data);
        } else {
            CashEntry::create($data);
        }

        $this->closeModal();
        $this->dispatch('notify', ['message' => 'ক্যাশ হিসাব সফলভাবে সংরক্ষিত হয়েছে।']);
    }

    public function edit($id)
    {
        $entry = CashEntry::findOrFail($id);
        $this->editingId = $entry->id;
        $this->description = $entry->description;
        $this->cashIn = $entry->cash_in !== null ? strval($entry->cash_in) : '';
        $this->cashOut = $entry->cash_out !== null ? strval($entry->cash_out) : '';
        $this->date = $entry->date->format('Y-m-d');
        $this->time = $entry->time;
        $this->showModal = true;
    }

    public function delete($id)
    {
        $entry = CashEntry::findOrFail($id);
        $entry->delete();
        $this->dispatch('notify', ['message' => 'ক্যাশ হিসাব সফলভাবে মুছে ফেলা হয়েছে।']);
    }

    public function render()
    {
        // Query builder
        $query = CashEntry::query();

        if (trim($this->search) !== '') {
            $query->where('description', 'like', '%' . $this->search . '%');
        }

        if (trim($this->dateFilter) !== '') {
            $query->whereDate('date', $this->dateFilter);
        }

        $entries = $query->orderBy('time', 'asc')->paginate($this->perPage);

        // Calculate Totals for dynamic dashboard indicators
        // Cash In for current dateFilter
        $todayCashIn = CashEntry::whereDate('date', $this->dateFilter)->sum('cash_in');
        $todayCashOut = CashEntry::whereDate('date', $this->dateFilter)->sum('cash_out');

        // Cash Jer (Cash Balance) = Base of 6,291,941 + total database cash_in - total database cash_out
        $baseCashJer = 6291941;
        $totalDbCashIn = CashEntry::sum('cash_in');
        $totalDbCashOut = CashEntry::sum('cash_out');
        $cashJer = $baseCashJer + $totalDbCashIn - $totalDbCashOut;

        // Totals of the current paginated view/query
        $viewTotalCashIn = $entries->sum('cash_in');
        $viewTotalCashOut = $entries->sum('cash_out');

        return view('livewire.cash-khata', [
            'entries' => $entries,
            'todayCashIn' => $todayCashIn,
            'todayCashOut' => $todayCashOut,
            'cashJer' => $cashJer,
            'viewTotalCashIn' => $viewTotalCashIn,
            'viewTotalCashOut' => $viewTotalCashOut,
        ])->layout('layouts.app');
    }
}
