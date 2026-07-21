<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\LoadEntry;
use App\Models\LoadRound;
use App\Models\UnloadEntry;
use App\Models\UnloadItem;
use Livewire\Component;
use Livewire\WithPagination;

class UnloadKhata extends Component
{
    use WithPagination;

    // Search and Filters
    public string $dateFilter = '';
    public string $roundFilter = '';
    public int $perPage = 20;

    // Modals visibility
    public bool $showModal  = false;
    public bool $showReport = false;

    // Report active tab
    public string $activeTab = 'quantity'; // options: 'quantity', 'percentage', 'bricks_adla'

    // Form inputs
    public ?int  $editingId = null;
    public string $date     = '';
    public string $round    = '';
    public string $category = '';
    public string $quantity = '';

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        // Set default values for modal inputs
        $this->date = now()->format('Y-m-d');
        $firstRound = LoadRound::orderBy('sort_order')->first();
        $this->round = $firstRound ? $firstRound->name : '';

        // Ensure target brick categories exist
        $targetNames = ['১ নং', 'পিকেট', '২ নং (ক)', '২ নং (খ)', '৩ নং ছালট', '৩ নং গরিয়া', 'এলোট', '3 no it'];
        foreach ($targetNames as $name) {
            Category::firstOrCreate(
                ['name' => $name],
                ['type' => 'ইট', 'rate' => 0.00]
            );
        }

        $this->category = '১ নং';
    }

    public function updatingDateFilter()  { $this->resetPage(); }
    public function updatingRoundFilter() { $this->resetPage(); }

    // ── Quantity Dynamic Auto-load ──────────────────────────────────────────
    public function updatedDate()     { $this->loadExistingQuantity(); }
    public function updatedRound()    { $this->loadExistingQuantity(); }
    public function updatedCategory() { $this->loadExistingQuantity(); }

    public function loadExistingQuantity()
    {
        if ($this->date && $this->round && $this->category) {
            $entry = UnloadEntry::whereDate('date', $this->date)
                ->where('round', $this->round)
                ->first();
            if ($entry) {
                $item = $entry->items()->where('category_name', $this->category)->first();
                $this->quantity = $item ? strval($item->quantity) : '';
            } else {
                $this->quantity = '';
            }
        } else {
            $this->quantity = '';
        }
    }

    // ── Modal ───────────────────────────────────────────────────────────────
    public function openModal()
    {
        $this->resetForm();
        $this->date = now()->format('Y-m-d');
        $firstRound = LoadRound::orderBy('sort_order')->first();
        $this->round = $firstRound ? $firstRound->name : '';
        $firstCategory = Category::whereIn('type', ['ইট', 'আধলা'])->first();
        $this->category = $firstCategory ? $firstCategory->name : '';
        
        $this->loadExistingQuantity();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->date      = '';
        $this->round     = '';
        $this->category  = '';
        $this->quantity  = '';
        $this->resetErrorBag();
    }

    // ── CRUD ────────────────────────────────────────────────────────────────
    public function save()
    {
        $this->validate([
            'date'     => 'required|date',
            'round'    => 'required|string',
            'category' => 'required|string',
            'quantity' => 'nullable|integer|min:0',
        ]);

        // Find or create unload entry by unique date and round
        $entry = UnloadEntry::whereDate('date', $this->date)
            ->where('round', $this->round)
            ->first();

        if (!$entry) {
            $entry = UnloadEntry::create([
                'date'  => $this->date,
                'round' => $this->round,
            ]);
        }

        $qtyVal = $this->quantity === '' || $this->quantity === null ? 0 : intval($this->quantity);

        if ($qtyVal === 0) {
            // Delete item if quantity is set to 0 or cleared
            $entry->items()->where('category_name', $this->category)->delete();
        } else {
            $entry->items()->updateOrCreate(
                ['category_name' => $this->category],
                ['quantity'      => $qtyVal]
            );
        }

        // Clean up entry if there are no items left
        if ($entry->items()->count() === 0) {
            $entry->delete();
            $msg = 'আনলোড তথ্য সংরক্ষণ (মুছে ফেলা) হয়েছে।';
        } else {
            $msg = $this->editingId ? 'আনলোড হিসাব আপডেট হয়েছে।' : 'নতুন আনলোড সংরক্ষিত হয়েছে।';
        }

        $this->closeModal();
        $this->dispatch('show-toast', message: $msg, type: 'success');
    }

    public function edit($id)
    {
        $entry = UnloadEntry::findOrFail($id);
        $this->editingId = $entry->id;
        $this->date      = $entry->date->format('Y-m-d');
        $this->round     = $entry->round;

        // Load first item or default to first category option
        $firstItem = $entry->items()->first();
        $this->category = $firstItem ? $firstItem->category_name : '';
        $this->quantity = $firstItem ? strval($firstItem->quantity) : '';

        if (!$this->category) {
            $firstCategory = Category::whereIn('type', ['ইট', 'আধলা'])->first();
            $this->category = $firstCategory ? $firstCategory->name : '';
        }

        $this->showModal = true;
    }

    public function delete($id)
    {
        UnloadEntry::findOrFail($id)->delete();
        $this->dispatch('show-toast', message: 'আনলোড হিসাব মুছে ফেলা হয়েছে।', type: 'success');
    }

    // ── Render ───────────────────────────────────────────────────────────────
    public function render()
    {
        // Dynamic category names matching requested target categories and database variations
        $goriyaName = Category::whereIn('name', ['৩ নং গরিয়া', '৩ নং গরিয়া'])->first()?->name ?? '৩ নং গরিয়া';
        $elotName = Category::whereIn('name', ['এলোট', 'এলোটি'])->first()?->name ?? 'এলোট';

        $targetNames = ['১ নং', 'পিকেট', '২ নং (ক)', '২ নং (খ)', $goriyaName, '৩ নং ছালট', $elotName, '3 no it'];

        // 1. Get Categories of type 'ইট' for main table columns — CASE WHEN works in both MySQL and SQLite
        $whenClauses = implode(' ', array_map(fn($i) => "WHEN name = ? THEN $i", array_keys($targetNames)));
        $brickCategories = Category::whereIn('name', $targetNames)
            ->orderByRaw("CASE {$whenClauses} ELSE 999 END", array_values($targetNames))
            ->get();

        // Get all categories for modal dropdown select (exactly matching headers)
        $allCategories = $brickCategories;

        // 2. Fetch rounds options
        $rounds = LoadRound::orderBy('sort_order')->get();

        // 3. Paginated entries
        $query = UnloadEntry::query()->with('items');
        if ($this->dateFilter) {
            $query->whereDate('date', $this->dateFilter);
        }
        if ($this->roundFilter) {
            $query->where('round', $this->roundFilter);
        }
        $entries = $query->orderBy('date', 'desc')->paginate($this->perPage);

        // Calculate total bricks for dynamic calculations in main table footer
        $totalQuantitySum = UnloadItem::whereHas('entry', function ($q) {
            if ($this->dateFilter)  $q->whereDate('date', $this->dateFilter);
            if ($this->roundFilter) $q->where('round', $this->roundFilter);
        })
        ->whereIn('category_name', $brickCategories->pluck('name'))
        ->sum('quantity');

        // 4. Report datasets
        // Tab 1 & Tab 2 dataset (Quantity & Percentage)
        $qtyReport = UnloadEntry::selectRaw('unload_entries.round, unload_items.category_name, SUM(unload_items.quantity) as total_qty')
            ->join('unload_items', 'unload_entries.id', '=', 'unload_items.unload_entry_id')
            ->groupBy('unload_entries.round', 'unload_items.category_name')
            ->get();

        $reportRows = $qtyReport->groupBy('round')->map(function ($items, $round) use ($brickCategories) {
            $row = ['round' => $round];
            $total = 0;
            foreach ($brickCategories as $cat) {
                $qty = $items->where('category_name', $cat->name)->first()?->total_qty ?? 0;
                $row[$cat->name] = $qty;
                $total += $qty;
            }
            $row['total'] = $total;
            return $row;
        })->values();

        // Tab 3 dataset (Bricks and Adla Comparison with Load Khata)
        $unloadReport = UnloadItem::selectRaw('unload_entries.round, categories.type, SUM(unload_items.quantity) as total_qty')
            ->join('unload_entries', 'unload_entries.id', '=', 'unload_items.unload_entry_id')
            ->join('categories', 'unload_items.category_name', '=', 'categories.name')
            ->groupBy('unload_entries.round', 'categories.type')
            ->get()
            ->groupBy('round');

        $loadReport = LoadEntry::selectRaw('round, SUM(quantity) as total_qty')
            ->groupBy('round')
            ->pluck('total_qty', 'round')
            ->toArray();

        $allReportRounds = array_unique(array_merge(
            array_keys($loadReport),
            $unloadReport->keys()->toArray()
        ));

        $compareRows = collect();
        foreach ($allReportRounds as $rnd) {
            $loadQty = $loadReport[$rnd] ?? 0;
            $roundUnloads = $unloadReport->get($rnd) ?? collect();
            
            $brickQty = $roundUnloads->where('type', 'ইট')->sum('total_qty');
            $adlaQty = $roundUnloads->where('type', 'আধলা')->sum('total_qty');

            $compareRows->push([
                'round' => $rnd,
                'load'  => $loadQty,
                'brick' => $brickQty,
                'adla'  => $adlaQty,
            ]);
        }

        return view('livewire.unload-khata', [
            'entries'          => $entries,
            'brickCategories'  => $brickCategories,
            'allCategories'    => $allCategories,
            'rounds'           => $rounds,
            'totalQuantitySum' => $totalQuantitySum,
            'reportRows'       => $reportRows,
            'compareRows'      => $compareRows,
        ])->layout('layouts.app');
    }
}
