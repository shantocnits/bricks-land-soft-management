<?php

namespace App\Livewire;

use App\Models\CashEntry;
use App\Models\Challan;
use App\Models\Payment;
use App\Models\Setting;
use Livewire\Component;
use Livewire\WithPagination;

class CashKhata extends Component
{
    use WithPagination;

    // Search and Filters
    public string $search = '';
    public string $dateFilter = '';
    public int $perPage = 20;

    // Modals visibility
    public bool $showModal = false;
    public bool $showInvestModal = false;
    public ?int $confirmDeleteId = null; // for delete confirmation

    // Form inputs
    public ?int $editingId = null;
    public string $entryType = 'in'; // 'in' = ক্যাশ ইন, 'out' = ক্যাশ আউট
    public string $description = '';
    public string $source = '';   // উৎস (cash in) / খাত (cash out)
    public string $amount = '';
    public string $time = '';
    public string $date = '';

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        $this->dateFilter = now()->format('Y-m-d');
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
        // Strict date logic: only today's date allows cash entry input
        if ($this->dateFilter !== now()->format('Y-m-d')) {
            $this->dispatch('cash-toast', message: 'দুঃখিত, আজকের তারিখ ব্যতীত অন্য তারিখে ক্যাশের হিসাব ইনপুট দেওয়ার অনুমতি নেই', type: 'error');
            return;
        }

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
        $this->entryType = 'in';
        $this->description = '';
        $this->source = '';
        $this->amount = '';
        $this->time = '';
        $this->date = '';
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'time' => 'required',
        ]);

        if (! $this->editingId && $this->date !== now()->format('Y-m-d')) {
            $this->dispatch('cash-toast', message: 'দুঃখিত, আজকের তারিখ ব্যতীত অন্য তারিখে ক্যাশের হিসাব ইনপুট দেওয়ার অনুমতি নেই', type: 'error');
            return;
        }

        $amount = floatval($this->amount);
        $data = [
            'description' => $this->description,
            'source'      => trim($this->source) !== '' ? $this->source : null,
            'cash_in' => $this->entryType === 'in' ? $amount : null,
            'cash_out' => $this->entryType === 'out' ? $amount : null,
            'date' => $this->date,
            'time' => $this->time,
            'season' => Setting::get('season', '২৫-২৬'),
        ];

        if ($this->editingId) {
            $entry = CashEntry::findOrFail($this->editingId);
            if ($entry->is_system) {
                $this->dispatch('cash-toast', message: 'সিস্টেম জেনারেটেড রেকর্ড — এটি এডিট করা যাবে না।', type: 'error');
                return;
            }
            $entry->update($data);
        } else {
            CashEntry::create($data);
        }

        $this->closeModal();
        $this->dispatch('cash-toast', message: 'ক্যাশ হিসাব সফলভাবে সংরক্ষিত হয়েছে।', type: 'success');
    }

    public function edit($id)
    {
        $entry = CashEntry::findOrFail($id);
        if ($entry->is_system) {
            $this->dispatch('cash-toast', message: 'সিস্টেম জেনারেটেড রেকর্ড — এটি এডিট করা যাবে না।', type: 'error');
            return;
        }
        $this->editingId = $entry->id;
        $this->entryType = $entry->cash_in !== null ? 'in' : 'out';
        $this->description = $entry->description;
        $this->source = $entry->source ?? '';
        $this->amount = $entry->cash_in !== null
            ? strval($entry->cash_in)
            : ($entry->cash_out !== null ? strval($entry->cash_out) : '');
        $this->date = $entry->date->format('Y-m-d');
        $this->time = $entry->time;
        $this->showModal = true;
    }

    public function confirmDelete($id)
    {
        $entry = CashEntry::findOrFail($id);
        if ($entry->is_system) {
            $this->dispatch('cash-toast', message: 'সিস্টেম জেনারেটেড রেকর্ড — এটি মুছে ফেলা যাবে না।', type: 'error');
            return;
        }
        $this->confirmDeleteId = $id;
    }

    public function cancelDelete()
    {
        $this->confirmDeleteId = null;
    }

    public function delete($id = null)
    {
        if ($id !== null) {
            $this->confirmDeleteId = $id;
        }
        if (! $this->confirmDeleteId) return;
        $entry = CashEntry::findOrFail($this->confirmDeleteId);
        if ($entry->is_system) {
            $this->dispatch('cash-toast', message: 'সিস্টেম জেনারেটেড রেকর্ড — এটি মুছে ফেলা যাবে না।', type: 'error');
            $this->confirmDeleteId = null;
            return;
        }
        $entry->delete();
        $this->confirmDeleteId = null;
        $this->dispatch('cash-toast', message: 'ক্যাশ হিসাব সফলভাবে মুছে ফেলা হয়েছে।', type: 'success');
    }

    public function render()
    {
        // Active season set from topbar header selector
        $activeSeason = Setting::get('season', '২৫-২৬');

        // System rows are auto-synced in real-time from চালান & বাকি খাতা modules.
        $dateScope = trim($this->dateFilter) !== '' ? $this->dateFilter : null;

        $saleCash = (float) Challan::query()
            ->where('grand_total', '>', 0)
            ->where(function ($q) use ($activeSeason) {
                $q->where('season', $activeSeason)->orWhereNull('season');
            })
            ->when($dateScope, fn ($q) => $q->whereDate('date', $dateScope))
            ->sum('cash');

        $collectionCash = (float) Challan::query()
            ->where('grand_total', 0)
            ->where(function ($q) use ($activeSeason) {
                $q->where('season', $activeSeason)->orWhereNull('season');
            })
            ->when($dateScope, fn ($q) => $q->whereDate('date', $dateScope))
            ->sum('cash');

        $paymentOut = (float) Payment::query()
            ->where(function ($q) use ($activeSeason) {
                $q->where('season', $activeSeason)->orWhereNull('season');
            })
            ->when($dateScope, function ($q) use ($dateScope) {
                $dmySlash = date('d/m/Y', strtotime($dateScope));
                $dmyDash  = date('d-m-Y', strtotime($dateScope));
                $ymdDash  = date('Y-m-d', strtotime($dateScope));
                $q->where(function ($sub) use ($dmySlash, $dmyDash, $ymdDash, $dateScope) {
                    $sub->where('date', $dmySlash)
                        ->orWhere('date', $dmyDash)
                        ->orWhere('date', $ymdDash)
                        ->orWhereDate('date', $dateScope)
                        ->orWhereDate('created_at', $dateScope);
                });
            })
            ->sum('payment');

        // The three system rows are always present in the table, dynamically computed.
        $systemRows = collect([
            (object) [
                'id' => null,
                'description' => 'নগদ ইট বিক্রি',
                'cash_in' => $saleCash,
                'cash_out' => null,
                'time' => null,
                'is_system' => true,
            ],
            (object) [
                'id' => null,
                'description' => 'বাকি কালেকশন',
                'cash_in' => $collectionCash,
                'cash_out' => null,
                'time' => null,
                'is_system' => true,
            ],
            (object) [
                'id' => null,
                'description' => 'মোট পেমেন্ট দেওয়া',
                'cash_in' => null,
                'cash_out' => $paymentOut,
                'time' => null,
                'is_system' => true,
            ],
        ]);

        // Manual (user-created) entries — editable/deletable, filtered by season
        $query = CashEntry::query()->where('is_system', false)
            ->where(function ($q) use ($activeSeason) {
                $q->where('season', $activeSeason)->orWhereNull('season');
            });

        if (trim($this->search) !== '') {
            $query->where('description', 'like', '%' . $this->search . '%');
        }

        if ($dateScope !== null) {
            $query->whereDate('date', $dateScope);
        }

        $entries = $query->orderBy('time', 'asc')->paginate($this->perPage);

        // Cash In/Out totals for the selected date (system + manual)
        $manualInForDate = (float) CashEntry::query()
            ->where('is_system', false)
            ->where(function ($q) use ($activeSeason) {
                $q->where('season', $activeSeason)->orWhereNull('season');
            })
            ->when($dateScope, fn ($q) => $q->whereDate('date', $dateScope))
            ->sum('cash_in');
        $manualOutForDate = (float) CashEntry::query()
            ->where('is_system', false)
            ->where(function ($q) use ($activeSeason) {
                $q->where('season', $activeSeason)->orWhereNull('season');
            })
            ->when($dateScope, fn ($q) => $q->whereDate('date', $dateScope))
            ->sum('cash_out');

        $todayCashIn = $saleCash + $collectionCash + $manualInForDate;
        $todayCashOut = $paymentOut + $manualOutForDate;

        // Cash Jer = Base + season sales + collections + manual in - season payments - manual out
        $baseCashJer = 6291941;
        $totalChallanCash = (float) Challan::query()
            ->where(function ($q) use ($activeSeason) {
                $q->where('season', $activeSeason)->orWhereNull('season');
            })->sum('cash');

        $totalPaymentOut = (float) Payment::query()
            ->where(function ($q) use ($activeSeason) {
                $q->where('season', $activeSeason)->orWhereNull('season');
            })->sum('payment');

        $totalDbCashIn = (float) CashEntry::where('is_system', false)
            ->where(function ($q) use ($activeSeason) {
                $q->where('season', $activeSeason)->orWhereNull('season');
            })->sum('cash_in');

        $totalDbCashOut = (float) CashEntry::where('is_system', false)
            ->where(function ($q) use ($activeSeason) {
                $q->where('season', $activeSeason)->orWhereNull('season');
            })->sum('cash_out');

        $cashJer = $baseCashJer + $totalChallanCash + $totalDbCashIn - $totalPaymentOut - $totalDbCashOut;

        // Breakdown for tooltip
        $cashJerBreakdown = [
            'base'           => $baseCashJer,
            'challanCash'    => $totalChallanCash,
            'manualIn'       => $totalDbCashIn,
            'paymentOut'     => $totalPaymentOut,
            'manualOut'      => $totalDbCashOut,
        ];

        // Invest report rows: system rows + manual entries for the selected date & active season
        $saleCashForDate = (float) Challan::query()->where('grand_total', '>', 0)
            ->where(function ($q) use ($activeSeason) {
                $q->where('season', $activeSeason)->orWhereNull('season');
            })
            ->when($dateScope, fn ($q) => $q->whereDate('date', $dateScope))->sum('cash');

        $collCashForDate = (float) Challan::query()->where('grand_total', 0)
            ->where(function ($q) use ($activeSeason) {
                $q->where('season', $activeSeason)->orWhereNull('season');
            })
            ->when($dateScope, fn ($q) => $q->whereDate('date', $dateScope))->sum('cash');

        $payOutForDate = (float) Payment::query()
            ->where(function ($q) use ($activeSeason) {
                $q->where('season', $activeSeason)->orWhereNull('season');
            })
            ->when($dateScope, function ($q) use ($dateScope) {
                $dmySlash = date('d/m/Y', strtotime($dateScope));
                $dmyDash  = date('d-m-Y', strtotime($dateScope));
                $ymdDash  = date('Y-m-d', strtotime($dateScope));
                $q->where(function ($sub) use ($dmySlash, $dmyDash, $ymdDash, $dateScope) {
                    $sub->where('date', $dmySlash)
                        ->orWhere('date', $dmyDash)
                        ->orWhere('date', $ymdDash)
                        ->orWhereDate('date', $dateScope)
                        ->orWhereDate('created_at', $dateScope);
                });
            })->sum('payment');

        $investReportSystemRows = collect([
            (object)['source' => 'চালান (নগদ বিক্রি)', 'cash_in' => $saleCashForDate, 'cash_out' => null],
            (object)['source' => 'বাকি কালেকশন',       'cash_in' => $collCashForDate,  'cash_out' => null],
            (object)['source' => 'মোট পেমেন্ট দেওয়া',  'cash_in' => null,              'cash_out' => $payOutForDate],
        ]);

        $manualForReport = CashEntry::query()->where('is_system', false)
            ->where(function ($q) use ($activeSeason) {
                $q->where('season', $activeSeason)->orWhereNull('season');
            })
            ->when($dateScope, fn ($q) => $q->whereDate('date', $dateScope))
            ->orderBy('time')->get();

        $investReportRows = $investReportSystemRows->concat($manualForReport->map(fn ($e) => (object)[
            'source'   => $e->source ?? '-',
            'cash_in'  => $e->cash_in,
            'cash_out' => $e->cash_out,
        ]));

        // Totals of the currently visible table rows (system + paginated manual)
        $viewTotalCashIn = $systemRows->sum('cash_in') + $entries->sum('cash_in');
        $viewTotalCashOut = $systemRows->sum('cash_out') + $entries->sum('cash_out');

        return view('livewire.cash-khata', [
            'systemRows'         => $systemRows,
            'entries'            => $entries,
            'todayCashIn'        => $todayCashIn,
            'todayCashOut'       => $todayCashOut,
            'cashJer'            => $cashJer,
            'cashJerBreakdown'   => $cashJerBreakdown,
            'viewTotalCashIn'    => $viewTotalCashIn,
            'viewTotalCashOut'   => $viewTotalCashOut,
            'investReportRows'   => $investReportRows,
            'activeSeason'       => $activeSeason,
        ])->layout('layouts.app');
    }
}
