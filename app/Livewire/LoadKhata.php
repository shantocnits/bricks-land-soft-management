<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\LoadEntry;
use App\Models\LoadRound;
use App\Models\Setting;
use App\Models\UnloadEntry;
use Livewire\Component;
use Livewire\WithPagination;

class LoadKhata extends Component
{
    use WithPagination;

    // Search and Filters
    public string $dateFilter = '';
    public string $roundFilter = '';
    public int $perPage = 20;

    // Modals visibility
    public bool $showModal     = false;
    public bool $showReport    = false;

    // Form inputs
    public ?int  $editingId   = null;
    public string $date        = '';
    public string $round       = '';
    public string $description = 'মাঠ থেকে লোড হয়েছে';
    public string $category    = '';
    public string $quantity    = '';

    // Round management
    public string $newRoundName = '';
    public bool $showAddRound  = false;

    // Selector options
    public array $descriptions = [
        'মাঠ থেকে লোড হয়েছে',
        'স্টক থেকে লোড হয়েছে',
        'পাকা ইট লোড হয়েছে',
        'স্টক লোড হয়েছে'
    ];

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        // Default dateFilter to empty string so lifetime totals are shown initially
        $this->dateFilter = '';

        // Set default round to first in DB
        $firstRound = LoadRound::orderBy('sort_order')->first();
        $this->round = $firstRound ? $firstRound->name : '';
        $this->roundFilter = $firstRound ? $firstRound->name : '';

        // Ensure target brick categories exist (same as unload page)
        $targetNames = ['১ নং', 'পিকেট', '২ নং (ক)', '২ নং (খ)', '৩ নং গরিয়া', '৩ নং ছালট', 'এলোট', '৩ নং ইট'];
        foreach ($targetNames as $name) {
            Category::firstOrCreate(
                ['name' => $name],
                ['type' => 'ইট', 'rate' => 0.00]
            );
        }
        
        // Update legacy '3 no it' if present in database
        Category::where('name', '3 no it')->update(['name' => '৩ নং ইট']);

        $this->category = '১ নং';
    }

    public function updatingDateFilter()  { $this->resetPage(); }
    public function updatingRoundFilter() { $this->resetPage(); }

    // ── Modal ───────────────────────────────────────────────────────────────
    public function openModal()
    {
        $this->resetForm();
        $this->date = now()->format('Y-m-d');
        $firstRound = LoadRound::orderBy('sort_order')->first();
        $this->round = $firstRound ? $firstRound->name : '';
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->editingId    = null;
        $this->date         = now()->format('Y-m-d');
        $this->round        = '';
        $this->description  = 'মাঠ থেকে লোড হয়েছে';
        $this->category     = '';
        $this->quantity     = '';
        $this->newRoundName = '';
        $this->showAddRound = false;
        $this->resetErrorBag();
    }

    // ── CRUD ────────────────────────────────────────────────────────────────
    public function save()
    {
        $rules = [
            'date'        => 'required|date',
            'round'       => 'required|string',
            'description' => 'required|string',
            'category'    => 'nullable|string',
            'quantity'    => 'required|integer|min:1',
        ];

        $isPakaIt = ($this->description === 'পাকা ইট লোড হয়েছে' || $this->description === 'পাকা ইট লোড হয়েছে');

        $this->validate($rules);

        $categoryToSave = $isPakaIt ? $this->category : '';

        $data = [
            'date'        => $this->editingId ? $this->date : now()->format('Y-m-d'),
            'season'      => Setting::get('season', '২৫-২৬'),
            'round'       => $this->round,
            'description' => $this->description,
            'category'    => $categoryToSave,
            'quantity'    => intval($this->quantity),
        ];

        if ($this->editingId) {
            $entry = LoadEntry::findOrFail($this->editingId);
            if (!$entry->date || !$entry->date->isToday()) {
                $this->dispatch('show-toast', message: 'পূর্বের দিনের লোড হিসাব আপডেট করা যাবে না।', type: 'error');
                return;
            }
            $entry->update($data);
            $msg = 'লোড হিসাব আপডেট হয়েছে।';
        } else {
            // Only today's load can be added
            $data['date'] = now()->format('Y-m-d');
            LoadEntry::create($data);
            $msg = 'নতুন লোড সংরক্ষিত হয়েছে।';
        }

        $this->closeModal();
        $this->dispatch('show-toast', message: $msg, type: 'success');
    }

    public function edit($id)
    {
        $entry = LoadEntry::findOrFail($id);

        if (!$entry->date || !$entry->date->isToday()) {
            $this->dispatch('show-toast', message: 'পূর্বের দিনের লোড হিসাব পরিবর্তন করা যাবে না।', type: 'error');
            return;
        }

        $this->editingId   = $entry->id;
        $this->date        = $entry->date->format('Y-m-d');
        $this->round       = $entry->round;
        $this->description = $entry->description;
        $this->quantity    = strval($entry->quantity);
        $this->category    = $entry->category ?? '';

        // Handle legacy description formats
        if ($this->description === 'পাকা ইট লোড হয়েছে (১ নং)' || $this->description === 'পাকা ইট লোড হয়েছে' || $this->description === 'পাকা ইট লোড হয়েছে') {
            $this->description = 'পাকা ইট লোড হয়েছে';
            if (!$this->category && str_contains($entry->description, '(১ নং)')) {
                $this->category = '১ নং';
            }
        }

        $this->showModal   = true;
    }

    public $confirmDeleteId = null;

    public function confirmDelete($id)
    {
        $this->confirmDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        if ($this->confirmDeleteId) {
            $this->delete($this->confirmDeleteId);
            $this->confirmDeleteId = null;
        }
    }

    public function delete($id)
    {
        $entry = LoadEntry::findOrFail($id);

        if (!$entry->date || !$entry->date->isToday()) {
            $this->dispatch('show-toast', message: 'পূর্বের দিনের লোড হিসাব মুছে ফেলা যাবে না।', type: 'error');
            return;
        }

        $roundName = $entry->round;
        $entryDate = $entry->date->format('Y-m-d');
        $entry->delete();

        // If no more load entries remain for this round on this date,
        // remove the linked unload_entry (and its items via cascade)
        $remainingForDate = LoadEntry::where('round', $roundName)
            ->whereDate('date', $entryDate)
            ->count();
        if ($remainingForDate === 0) {
            UnloadEntry::where('round', $roundName)
                ->whereDate('date', $entryDate)
                ->delete();
        }

        // When all load entries for this round are gone (any date), delete the round itself
        if (LoadEntry::where('round', $roundName)->count() === 0) {
            LoadRound::where('name', $roundName)->delete();
        }

        $this->dispatch('show-toast', message: 'লোড হিসাব মুছে ফেলা হয়েছে এবং সংশ্লিষ্ট আনলোড ডেটাও মুছে ফেলা হয়েছে।', type: 'success');
    }

    // ── Round Management ────────────────────────────────────────────────────
    public function addRound()
    {
        $input = trim($this->newRoundName);
        if ($input === '') {
            $this->validate(['newRoundName' => 'required']);
            return;
        }

        // Convert English numbers to Bangla numbers
        $eng = ['0','1','2','3','4','5','6','7','8','9'];
        $bg = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
        $converted = str_replace($eng, $bg, $input);

        // Append " নম্বর রাউন্ড" suffix
        if (!str_contains($converted, 'নম্বর রাউন্ড')) {
            $converted = $converted . ' নম্বর রাউন্ড';
        }

        $this->newRoundName = $converted;

        $this->validate([
            'newRoundName' => 'required|string|min:2|max:60|unique:load_rounds,name'
        ]);

        $created = LoadRound::create(['name' => $converted]);

        // Auto-select the newly added round in the modal
        $this->round        = $created->name;
        $this->newRoundName = '';
        $this->showAddRound = false;

        $this->dispatch('show-toast', message: 'নতুন রাউন্ড যোগ হয়েছে।', type: 'success');
    }

    public function deleteRound($id)
    {
        $round = LoadRound::findOrFail($id);
        $round->delete();
        // If deleted round was selected, reset
        if ($this->round === $round->name)  $this->round = '';
        if ($this->roundFilter === $round->name) $this->roundFilter = '';
        $this->dispatch('show-toast', message: 'রাউন্ড মুছে ফেলা হয়েছে।', type: 'success');
    }

    // ── Render ───────────────────────────────────────────────────────────────
    public function render()
    {
        // Active season from global topbar selector
        $activeSeason = Setting::get('season', '২৫-২৬');

        // Permanent round list from LoadRound model
        $rounds = LoadRound::orderBy('sort_order')->get();
        $allRounds = $rounds;

        // Main table query — filtered by season
        $query = LoadEntry::query()->where('season', $activeSeason);
        if ($this->dateFilter)  $query->whereDate('date', $this->dateFilter);
        if ($this->roundFilter) $query->where('round', $this->roundFilter);
        $entries = $query->orderBy('date', 'desc')->paginate($this->perPage);

        $totalQuantity = LoadEntry::where('season', $activeSeason)->sum('quantity');

        // Report: per-round breakdown (কাঁচা vs পাকা) — season & round filtered
        $reportQuery = LoadEntry::selectRaw('`round`, description, category, SUM(quantity) as total')
            ->where('season', $activeSeason);
        if ($this->dateFilter) {
            $reportQuery->whereDate('date', $this->dateFilter);
        }
        if ($this->roundFilter) {
            $reportQuery->where('round', $this->roundFilter);
        }
        $reportRows = $reportQuery->groupBy('round', 'description', 'category')
            ->get()
            ->groupBy('round')
            ->map(function ($rows, $round) {
                $raw    = $rows->filter(fn($r) => !str_contains($r->description, 'পাকা'))->sum('total');
                $cooked = $rows->filter(fn($r) =>  str_contains($r->description, 'পাকা'))->sum('total');
                return ['round' => $round, 'raw' => $raw, 'cooked' => $cooked, 'total' => $raw + $cooked];
            })->values();

        // Ordered brick category names
        $categoryNames = ['১ নং', 'পিকেট', '২ নং (ক)', '২ নং (খ)', '৩ নং গরিয়া', '৩ নং ছালট', 'এলোট', '৩ নং ইট'];
        $whenClauses = implode(' ', array_map(fn($i) => "WHEN name = ? THEN $i", array_keys($categoryNames)));
        $categories = Category::whereIn('name', $categoryNames)
            ->orderByRaw("CASE {$whenClauses} ELSE 999 END", array_values($categoryNames))
            ->get();

        return view('livewire.load-khata', [
            'entries'       => $entries,
            'rounds'        => $rounds,
            'allRounds'     => $allRounds,
            'totalQuantity' => $totalQuantity,
            'reportRows'    => $reportRows,
            'categories'    => $categories,
            'activeSeason'  => $activeSeason,
        ])->layout('layouts.app');
    }
}
