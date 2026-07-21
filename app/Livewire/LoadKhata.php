<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\LoadEntry;
use App\Models\LoadRound;
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
        // Set default round to first in DB
        $firstRound = LoadRound::orderBy('sort_order')->first();
        $this->round = $firstRound ? $firstRound->name : '';

        // Ensure target brick categories exist (same as unload page)
        $targetNames = ['১ নং', 'পিকেট', '২ নং (ক)', '২ নং (খ)', '৩ নং গরিয়া', '৩ নং ছালট', 'এলোট', '3 no it'];
        foreach ($targetNames as $name) {
            Category::firstOrCreate(
                ['name' => $name],
                ['type' => 'ইট', 'rate' => 0.00]
            );
        }

        $this->category = '১ নং';

        // Seed default entries if table is empty
        if (LoadEntry::count() === 0) {
            LoadEntry::create(['date' => '2026-07-09', 'round' => '-১ নম্বর রাউন্ড', 'description' => 'ইট থেকে লোড হয়েছে', 'quantity' => 5000]);
            LoadEntry::create(['date' => '2026-07-09', 'round' => '-১ নম্বর রাউন্ড', 'description' => 'পাকা ইট লোড হয়েছে (১ নং)', 'quantity' => 5000]);
            LoadEntry::create(['date' => '2026-07-08', 'round' => '-১ নম্বর রাউন্ড', 'description' => 'মাঠ থেকে লোড হয়েছে', 'quantity' => 5000]);
        }
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
        $this->date         = '';
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
            'date'        => $this->date,
            'round'       => $this->round,
            'description' => $this->description,
            'category'    => $categoryToSave,
            'quantity'    => intval($this->quantity),
        ];

        if ($this->editingId) {
            LoadEntry::findOrFail($this->editingId)->update($data);
            $msg = 'লোড হিসাব আপডেট হয়েছে।';
        } else {
            LoadEntry::create($data);
            $msg = 'নতুন লোড সংরক্ষিত হয়েছে।';
        }

        $this->closeModal();
        $this->dispatch('show-toast', message: $msg, type: 'success');
    }

    public function edit($id)
    {
        $entry = LoadEntry::findOrFail($id);
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

    public function delete($id)
    {
        LoadEntry::findOrFail($id)->delete();
        $this->dispatch('show-toast', message: 'লোড হিসাব মুছে ফেলা হয়েছে।', type: 'success');
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
        $rounds = LoadRound::orderBy('sort_order')->get();

        $query = LoadEntry::query();
        if ($this->dateFilter)  $query->whereDate('date', $this->dateFilter);
        if ($this->roundFilter) $query->where('round', $this->roundFilter);
        $entries = $query->orderBy('date', 'desc')->paginate($this->perPage);

        $totalQuantity = LoadEntry::sum('quantity');

        // Report: per-round breakdown (কাঁচা vs পাকা)
        $reportRows = LoadEntry::selectRaw('`round`, description, category, SUM(quantity) as total')
            ->groupBy('round', 'description', 'category')
            ->get()
            ->groupBy('round')
            ->map(function ($rows, $round) {
                // If description contains "পাকা", count as cooked. Otherwise raw.
                $raw    = $rows->filter(fn($r) => !str_contains($r->description, 'পাকা'))->sum('total');
                $cooked = $rows->filter(fn($r) => str_contains($r->description, 'পাকা'))->sum('total');
                return ['round' => $round, 'raw' => $raw, 'cooked' => $cooked, 'total' => $raw + $cooked];
            })->values();

        // Ordered brick category names — CASE WHEN works in both MySQL and SQLite
        $categoryNames = ['১ নং', 'পিকেট', '২ নং (ক)', '২ নং (খ)', '৩ নং গরিয়া', '৩ নং ছালট', 'এলোট', '3 no it'];
        $whenClauses = implode(' ', array_map(fn($i) => "WHEN name = ? THEN $i", array_keys($categoryNames)));
        $categories = Category::whereIn('name', $categoryNames)
            ->orderByRaw("CASE {$whenClauses} ELSE 999 END", array_values($categoryNames))
            ->get();

        return view('livewire.load-khata', [
            'entries'       => $entries,
            'rounds'        => $rounds,
            'totalQuantity' => $totalQuantity,
            'reportRows'    => $reportRows,
            'categories'    => $categories,
        ])->layout('layouts.app');
    }
}
