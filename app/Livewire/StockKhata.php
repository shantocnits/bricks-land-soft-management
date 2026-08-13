<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\ChallanItem;
use App\Models\Delivery;
use App\Models\Ledger;
use App\Models\LoadEntry;
use App\Models\Payment;
use App\Models\StockAdjustment;
use App\Models\Setting;
use App\Models\UnloadItem;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StockKhata extends Component
{
    use WithPagination;

    // View & Tab variables
    public string $view = 'stock'; // options: 'stock', 'update'
    public string $subTab = 'brick_calculation'; // options: 'brick_calculation', 'stock_value', 'update_history'
    public int $perPage = 15;

    // Edit Modal properties
    public bool $showEditModal = false;
    public ?int $editingId = null;
    public string $edit_description = '';
    public string $edit_category = '';
    public string $edit_stock_plus = '';
    public string $edit_stock_minus = '';

    // Form inputs for Stock Adjustment (Add)
    public string $date = '';
    public ?string $description = '';
    public string $category = '';
    public string $stock_plus = '';
    public string $stock_minus = '';

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        $this->date = now()->format('Y-m-d');
        
        $targetNames = ['১ নং', 'পিকেট', '২ নং (ক)', '২ নং (খ)', '৩ নং ছালট', '৩ নং গরিয়া', 'এলোট', '৩ নং ইট'];
        $whenClauses = implode(' ', array_map(fn($i) => "WHEN name = ? THEN $i", array_keys($targetNames)));
        $firstCategory = Category::whereIn('name', $targetNames)
            ->orderByRaw("CASE {$whenClauses} ELSE 999 END", array_values($targetNames))
            ->first();
            
        $this->category = $firstCategory ? $firstCategory->name : '১ নং';
    }

    public function resetForm()
    {
        $this->date = now()->format('Y-m-d');
        $this->description = '';
        $targetNames = ['১ নং', 'পিকেট', '২ নং (ক)', '২ নং (খ)', '৩ নং ছালট', '৩ নং গরিয়া', 'এলোট', '৩ নং ইট'];
        $whenClauses = implode(' ', array_map(fn($i) => "WHEN name = ? THEN $i", array_keys($targetNames)));
        $firstCategory = Category::whereIn('name', $targetNames)
            ->orderByRaw("CASE {$whenClauses} ELSE 999 END", array_values($targetNames))
            ->first();
        $this->category = $firstCategory ? $firstCategory->name : '১ নং';
        $this->stock_plus = '';
        $this->stock_minus = '';
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate([
            'date' => 'required|date',
            'description' => 'nullable|string|max:255',
            'category' => 'required|string',
            'stock_plus' => 'nullable|integer|min:0',
            'stock_minus' => 'nullable|integer|min:0',
        ]);

        $plus = $this->stock_plus !== '' ? (int)$this->stock_plus : 0;
        $minus = $this->stock_minus !== '' ? (int)$this->stock_minus : 0;

        if ($plus === 0 && $minus === 0) {
            $this->addError('stock_plus', 'স্টক ++ অথবা স্টক -- এর যেকোনো একটি পূরণ করতে হবে।');
            $this->addError('stock_minus', 'স্টক ++ অথবা স্টক -- এর যেকোনো একটি পূরণ করতে হবে।');
            return;
        }

        StockAdjustment::create([
            'date' => now()->format('Y-m-d H:i:s'),
            'description' => $this->description,
            'category_name' => $this->category,
            'stock_plus' => $plus,
            'stock_minus' => $minus,
            'user_id' => Auth::id(),
        ]);

        $this->resetForm();
        $this->dispatch('show-toast', message: 'স্টক আপডেট রেকর্ডটি সফলভাবে সংরক্ষণ করা হয়েছে।', type: 'success');
    }

    public function editAdjustment($id)
    {
        $adj = StockAdjustment::findOrFail($id);
        $adjDate = Carbon::parse($adj->date);
        if (!$adjDate->isToday()) {
            $this->dispatch('show-toast', message: 'পূর্বের দিনের রেকর্ড পরিবর্তন করা সম্ভব নয়।', type: 'error');
            return;
        }

        $this->editingId = $adj->id;
        $this->edit_description = $adj->description ?? '';
        $this->edit_category = $adj->category_name;
        $this->edit_stock_plus = $adj->stock_plus > 0 ? (string)$adj->stock_plus : '';
        $this->edit_stock_minus = $adj->stock_minus > 0 ? (string)$adj->stock_minus : '';
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->editingId = null;
        $this->edit_description = '';
        $this->edit_category = '';
        $this->edit_stock_plus = '';
        $this->edit_stock_minus = '';
        $this->resetValidation();
    }

    public function updateAdjustment()
    {
        if (!$this->editingId) return;

        $adj = StockAdjustment::findOrFail($this->editingId);
        $adjDate = Carbon::parse($adj->date);
        if (!$adjDate->isToday()) {
            $this->dispatch('show-toast', message: 'পূর্বের দিনের রেকর্ড পরিবর্তন করা সম্ভব নয়।', type: 'error');
            $this->closeEditModal();
            return;
        }

        $this->validate([
            'edit_category' => 'required|string',
            'edit_description' => 'nullable|string|max:255',
            'edit_stock_plus' => 'nullable|integer|min:0',
            'edit_stock_minus' => 'nullable|integer|min:0',
        ]);

        $plus = $this->edit_stock_plus !== '' ? (int)$this->edit_stock_plus : 0;
        $minus = $this->edit_stock_minus !== '' ? (int)$this->edit_stock_minus : 0;

        if ($plus === 0 && $minus === 0) {
            $this->addError('edit_stock_plus', 'স্টক ++ অথবা স্টক -- এর যেকোনো একটি পূরণ করতে হবে।');
            $this->addError('edit_stock_minus', 'স্টক ++ অথবা স্টক -- এর যেকোনো একটি পূরণ করতে হবে।');
            return;
        }

        $adj->update([
            'description' => $this->edit_description,
            'category_name' => $this->edit_category,
            'stock_plus' => $plus,
            'stock_minus' => $minus,
        ]);

        $this->closeEditModal();
        $this->dispatch('show-toast', message: 'স্টক আপডেট রেকর্ডটি সফলভাবে আপডেট করা হয়েছে।', type: 'success');
    }

    public function deleteAdjustment($id)
    {
        $adj = StockAdjustment::findOrFail($id);
        $adjDate = Carbon::parse($adj->date);
        if (!$adjDate->isToday()) {
            $this->dispatch('show-toast', message: 'পূর্বের দিনের রেকর্ড মোছা সম্ভব নয়।', type: 'error');
            return;
        }

        $adj->delete();
        if ($this->editingId === $id) {
            $this->closeEditModal();
        }
        $this->dispatch('show-toast', message: 'রেকর্ডটি সফলভাবে মুছে ফেলা হয়েছে।', type: 'success');
    }

    public function render()
    {
        $activeSeason = Setting::get('season', '২৫-২৬');

        // 1. Target 8 main brick categories
        $targetNames = ['১ নং', 'পিকেট', '২ নং (ক)', '২ নং (খ)', '৩ নং ছালট', '৩ নং গরিয়া', 'এলোট', '৩ নং ইট'];
        $whenClauses = implode(' ', array_map(fn($i) => "WHEN name = ? THEN $i", array_keys($targetNames)));
        $brickCategories = Category::whereIn('name', $targetNames)
            ->orderByRaw("CASE {$whenClauses} ELSE 999 END", array_values($targetNames))
            ->get();

        $brickStockData = [];
        $totalStockSum = 0;
        $brickDeliveryRemainingSum = 0;
        $stockPriceSum = 0;

        foreach ($brickCategories as $cat) {
            // Unloads from UnloadKhata for this category in active season
            $unloads = UnloadItem::where('category_name', $cat->name)
                ->where(function($q) use ($activeSeason) {
                    $q->whereHas('entry', fn($e) => $e->where('season', $activeSeason))
                      ->orWhereDoesntHave('entry');
                })
                ->sum('quantity');

            // Manual stock adjustments (স্টক ++ and স্টক --)
            $adjPlus = StockAdjustment::where('category_name', $cat->name)->sum('stock_plus');
            $adjMinus = StockAdjustment::where('category_name', $cat->name)->sum('stock_minus');

            // Rule 2.3: Paka Bricks Loaded for this category from LoadKhata
            $pakaLoaded = LoadEntry::where('category', $cat->name)
                ->where('description', 'LIKE', '%পাকা ইট%')
                ->where(function($q) use ($activeSeason) {
                    $q->where('season', $activeSeason)->orWhereNull('season');
                })
                ->sum('quantity');

            // Total Stock = (Unloads + Stock ++) - (Stock -- + Paka Loaded Bricks)
            $totalStock = ($unloads + $adjPlus) - ($adjMinus + $pakaLoaded);

            // Total Sold Bricks from ChallanItems
            $sold = ChallanItem::where('category_name', $cat->name)
                ->where(function($q) use ($activeSeason) {
                    $q->whereHas('challan', fn($c) => $c->where('season', $activeSeason))
                      ->orWhereDoesntHave('challan');
                })
                ->sum('quantity');

            // Total Delivered Bricks from ChallanItems
            $delivered = ChallanItem::where('category_name', $cat->name)
                ->where(function($q) use ($activeSeason) {
                    $q->whereHas('challan', fn($c) => $c->where('season', $activeSeason))
                      ->orWhereDoesntHave('challan');
                })
                ->sum('delivered_quantity');

            // 1. Delivery Pending (প্রকৃত ডেলিভারি বাকি) = চালানের মোট বিক্রি - ডেলিভারি হওয়া ইট
            $deliveryRemaining = max(0, $sold - $delivered);

            // 2. Real Stock (আসল স্টক) = মোট স্টক - ডেলিভারি বাকি
            $realStock = $totalStock - $deliveryRemaining;

            // 2. Stock Value (স্টক মূল্য) = (আসল স্টক > ০ ? আসল স্টক * দর : ০)
            $stockPrice = $realStock > 0 ? ($realStock * (float)$cat->rate) : 0;

            $brickStockData[$cat->name] = [
                'total_stock' => $totalStock,
                'delivery_remaining' => $deliveryRemaining,
                'real_stock' => $realStock,
                'stock_price' => $stockPrice,
                'rate' => $cat->rate
            ];

            $totalStockSum += $totalStock;
            $brickDeliveryRemainingSum += $deliveryRemaining;
            $stockPriceSum += $stockPrice;
        }

        // Calculate Delivery Remaining for ALL OTHER categories (non-brick: Adla, Rubbish, etc.)
        $otherSold = ChallanItem::whereNotIn('category_name', $targetNames)
            ->where(function($q) use ($activeSeason) {
                $q->whereHas('challan', fn($c) => $c->where('season', $activeSeason))
                  ->orWhereDoesntHave('challan');
            })
            ->sum('quantity');

        $otherDelivered = ChallanItem::whereNotIn('category_name', $targetNames)
            ->where(function($q) use ($activeSeason) {
                $q->whereHas('challan', fn($c) => $c->where('season', $activeSeason))
                  ->orWhereDoesntHave('challan');
            })
            ->sum('delivered_quantity');

        $otherDeliveryRemaining = max(0, $otherSold - $otherDelivered);

        // 2. Grand Total Delivery Remaining across ALL categories (main bricks + Adla + Rubbish + custom categories)
        // Matches the Delivery Modal / Delivery Khata Grand Total 100%!
        $deliveryRemainingSum = $brickDeliveryRemainingSum + $otherDeliveryRemaining;

        // Grand Total Real Stock = Total Main Stock - Grand Total Delivery Remaining
        $realStockSum = $totalStockSum - $deliveryRemainingSum;

        // 3. Get Adla & Other Categories Stock Data (Excludes paka brick loads)
        $totalLoadedBricks = LoadEntry::where(function($q) use ($activeSeason) {
            $q->where('season', $activeSeason)->orWhereNull('season');
        })
        ->where('description', 'NOT LIKE', '%পাকা%')
        ->sum('quantity');

        $totalUnloadedBricks = UnloadItem::whereIn('category_name', $brickCategories->pluck('name'))
            ->where(function($q) use ($activeSeason) {
                $q->whereHas('entry', fn($e) => $e->where('season', $activeSeason))
                  ->orWhereDoesntHave('entry');
            })->sum('quantity');

        // সর্বমোট আধলা = মোট কাঁচা ইট লোড - মোট আস্ত ইট আনলোড
        $adlaFromLoadUnload = max(0, $totalLoadedBricks - $totalUnloadedBricks);

        $adlaCategoryNames = Category::where('type', 'আধলা')->pluck('name')->toArray();
        $nonBrickCategoryNames = Category::whereNotIn('name', $targetNames)->pluck('name')->toArray();
        $allOtherCatNames = array_unique(array_merge($adlaCategoryNames, $nonBrickCategoryNames));

        $adlaAdjPlus = StockAdjustment::whereIn('category_name', $allOtherCatNames)->sum('stock_plus');
        $adlaAdjMinus = StockAdjustment::whereIn('category_name', $allOtherCatNames)->sum('stock_minus');

        $adlaDelivered = Delivery::whereIn('category_name', $allOtherCatNames)
            ->where(function($q) use ($activeSeason) {
                $q->whereHas('challan', fn($c) => $c->where('season', $activeSeason))
                  ->orWhereDoesntHave('challan');
            })
            ->sum('quantity');

        $adlaTotalStock = $adlaFromLoadUnload + $adlaAdjPlus - $adlaAdjMinus;
        // আধলা স্টক রয়েছে = সর্বমোট আধলা - আধলা ডেলিভারি
        $adlaRemainingStock = max(0, $adlaTotalStock - $adlaDelivered);

        // 4. Get Raw Brick Stock Data (কাঁচা ইট স্টক)
        // 4.1. Production Payment Entries: Find ONLY ledgers with group_type = 'production', 'উৎপাদন (কাঁচা ইট)', or 'উৎপাদন'
        $prodLedgerNames = Ledger::whereIn('group_type', ['production', 'উৎপাদন (কাঁচা ইট)', 'উৎপাদন'])
            ->orWhere('group', 'LIKE', '%উৎপাদন%')
            ->pluck('name')
            ->toArray();

        $legacyRawLedgerNames = ['কাঁচা ইট তৈরি', 'কাঁচা ইট', 'মাঠের ইট', '১ নং মেট', '১ নং মেল', 'মেট'];
        $allProdLedgerNames = array_unique(array_merge($prodLedgerNames, $legacyRawLedgerNames));

        // Rule 1.1 & 1.2: Strictly sum qty ONLY from payment entries belonging to 'উৎপাদন (কাঁচা ইট)' ledgers
        $totalRawBricksMade = 0;
        if (!empty($allProdLedgerNames)) {
            $totalRawBricksMade = Payment::where(function($q) use ($activeSeason) {
                    $q->where('season', $activeSeason)->orWhereNull('season');
                })
                ->where(function($q) use ($allProdLedgerNames) {
                    $q->whereIn('ledger', $allProdLedgerNames);
                    if (\Illuminate\Support\Facades\Schema::hasColumn('payments', 'khotian_name')) {
                        $q->orWhereIn('khotian_name', $allProdLedgerNames);
                    }
                })
                ->sum('qty');
        }

        // 4.2. Load entries breakdown based on 'লোডের ধরণ' (description)
        // 4.2. Load entries breakdown based on 'লোডের ধরণ' (description)
        // Rule 1: 'মাঠ থেকে লোড হয়েছে'
        $loadedFromField = LoadEntry::where(function($q) use ($activeSeason) {
                $q->where('season', $activeSeason)->orWhereNull('season');
            })
            ->where('description', 'LIKE', '%মাঠ%')
            ->sum('quantity');

        // Rule 2: 'স্টক থেকে লোড হয়েছে'
        $loadedFromStock = LoadEntry::where(function($q) use ($activeSeason) {
                $q->where('season', $activeSeason)->orWhereNull('season');
            })
            ->where('description', 'LIKE', '%স্টক থেকে%')
            ->sum('quantity');

        // Rule 4: 'স্টক লোড হয়েছে' (general raw stock load)
        $stockLoadedIn = LoadEntry::where(function($q) use ($activeSeason) {
                $q->where('season', $activeSeason)->orWhereNull('season');
            })
            ->where('description', 'LIKE', '%স্টক লোড%')
            ->where('description', 'NOT LIKE', '%থেকে%')
            ->where('description', 'NOT LIKE', '%পাকা%')
            ->sum('quantity');

        // Rule 1.2 & 2.1: 'মাঠে ইট রয়েছে' = মোট কাঁচা ইট উৎপাদন - মাঠ থেকে লোড হয়েছে
        $fieldBricksRemaining = max(0, (int)$totalRawBricksMade - (int)$loadedFromField);

        // Rule 2 & 4: 'স্টকে রয়েছে' = (স্টক লোড হয়েছে) - (স্টক থেকে লোড হয়েছে)
        $kilnBricksRemaining = (int)$stockLoadedIn - (int)$loadedFromStock;

        // Rule 3.1: 'মোট কাঁচা ইট রয়েছে' = 'মাঠে ইট রয়েছে' + 'স্টকে রয়েছে'
        $totalRawBricksRemaining = $fieldBricksRemaining + $kilnBricksRemaining;

        // 5. Paginated Adjustments List
        $adjustments = StockAdjustment::with('user')
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        // Ordered all categories for forms & modals
        $allCategories = Category::whereIn('name', $targetNames)
            ->orderByRaw("CASE {$whenClauses} ELSE 999 END", array_values($targetNames))
            ->get();

        return view('livewire.stock-khata', [
            'brickCategories' => $brickCategories,
            'brickStockData' => $brickStockData,
            'totalStockSum' => $totalStockSum,
            'deliveryRemainingSum' => $deliveryRemainingSum,
            'realStockSum' => $realStockSum,
            'stockPriceSum' => $stockPriceSum,
            
            'adlaTotalStock' => $adlaTotalStock,
            'adlaDelivered' => $adlaDelivered,
            'adlaRemainingStock' => $adlaRemainingStock,
            'otherDeliveryRemaining' => $otherDeliveryRemaining,

            'fieldBricksRemaining' => $fieldBricksRemaining,
            'kilnBricksRemaining' => $kilnBricksRemaining,
            'totalRawBricksRemaining' => $totalRawBricksRemaining,

            'adjustments' => $adjustments,
            'allCategories' => $allCategories,
            'activeSeason' => $activeSeason,
        ])->layout('layouts.app');
    }
}
