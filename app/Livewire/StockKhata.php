<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\ChallanItem;
use App\Models\Delivery;
use App\Models\LoadEntry;
use App\Models\StockAdjustment;
use App\Models\Setting;
use App\Models\UnloadItem;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class StockKhata extends Component
{
    use WithPagination;

    // View & Tab variables
    public string $view = 'stock'; // options: 'stock', 'update'
    public string $subTab = 'brick_calculation'; // options: 'brick_calculation', 'stock_value', 'update_history'
    public int $perPage = 15;

    // Form inputs for Stock Adjustment
    public string $date = '';
    public ?string $description = '';
    public string $category = '';
    public string $stock_plus = '';
    public string $stock_minus = '';

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        $this->date = now()->format('Y-m-d');
        $firstCategory = Category::orderBy('id')->first();
        $this->category = $firstCategory ? $firstCategory->name : '';
    }

    public function resetForm()
    {
        $this->date = now()->format('Y-m-d');
        $this->description = '';
        $firstCategory = Category::orderBy('id')->first();
        $this->category = $firstCategory ? $firstCategory->name : '';
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
            'date' => $this->date,
            'description' => $this->description,
            'category_name' => $this->category,
            'stock_plus' => $plus,
            'stock_minus' => $minus,
            'user_id' => Auth::id(),
        ]);

        session()->flash('message', 'স্টক আপডেট রেকর্ডটি সফলভাবে সংরক্ষণ করা হয়েছে।');
        $this->resetForm();
    }

    public function deleteAdjustment($id)
    {
        $adj = StockAdjustment::findOrFail($id);
        $adj->delete();
        session()->flash('message', 'রেকর্ডটি সফলভাবে মুছে ফেলা হয়েছে।');
    }

    public function render()
    {
        // 1. Get Brick Category Stock Data (excluding khoya/খোয়া)
        $brickCategories = Category::where('type', 'ইট')
            ->where('name', '!=', 'khoya')
            ->where('name', '!=', 'খোয়া')
            ->orderBy('id')
            ->get();
        $brickStockData = [];
        
        $totalStockSum = 0;
        $deliveryRemainingSum = 0;
        $realStockSum = 0;
        $stockPriceSum = 0;

        foreach ($brickCategories as $cat) {
            $unloads = UnloadItem::where('category_name', $cat->name)->sum('quantity');
            $adjPlus = StockAdjustment::where('category_name', $cat->name)->sum('stock_plus');
            $adjMinus = StockAdjustment::where('category_name', $cat->name)->sum('stock_minus');
            $deliveries = Delivery::where('category_name', $cat->name)->sum('quantity');
            $sold = ChallanItem::where('category_name', $cat->name)->sum('quantity');

            $totalStock = $unloads + $adjPlus - $adjMinus - $deliveries;
            $deliveryRemaining = $sold - $deliveries;
            $realStock = $totalStock - $deliveryRemaining;
            $stockPrice = $realStock * $cat->rate;

            $brickStockData[$cat->name] = [
                'total_stock' => $totalStock,
                'delivery_remaining' => $deliveryRemaining,
                'real_stock' => $realStock,
                'stock_price' => $stockPrice,
                'rate' => $cat->rate
            ];

            $totalStockSum += $totalStock;
            $deliveryRemainingSum += $deliveryRemaining;
            $realStockSum += $realStock;
            $stockPriceSum += $stockPrice;
        }

        // 2. Get Adla Category Stock Data
        $adlaCategories = Category::where('type', 'আধলা')->pluck('name');
        $adlaUnloaded = UnloadItem::whereIn('category_name', $adlaCategories)->sum('quantity');
        $adlaAdjPlus = StockAdjustment::whereIn('category_name', $adlaCategories)->sum('stock_plus');
        $adlaAdjMinus = StockAdjustment::whereIn('category_name', $adlaCategories)->sum('stock_minus');
        $adlaDelivered = Delivery::whereIn('category_name', $adlaCategories)->sum('quantity');

        $adlaTotalStock = $adlaUnloaded + $adlaAdjPlus - $adlaAdjMinus;
        $adlaRemainingStock = $adlaTotalStock - $adlaDelivered;

        // 3. Get Raw Brick Stock Data
        $rawBricksMade = (int)Setting::get('raw_bricks_made', 39489);
        $loadedFromField = LoadEntry::where('description', 'মাঠ থেকে লোড হয়েছে')->sum('quantity');
        $brickCategoryNames = Category::where('type', 'ইট')
            ->where('name', '!=', 'khoya')
            ->where('name', '!=', 'খোয়া')
            ->pluck('name');
        $unloadedBricks = UnloadItem::whereIn('category_name', $brickCategoryNames)->sum('quantity');

        $fieldBricksRemaining = $rawBricksMade - $loadedFromField;
        $kilnBricksRemaining = $loadedFromField - $unloadedBricks;
        $totalRawBricksRemaining = $fieldBricksRemaining + $kilnBricksRemaining;

        // 4. Paginated Adjustments List
        $adjustments = StockAdjustment::with('user')
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

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

            'fieldBricksRemaining' => $fieldBricksRemaining,
            'kilnBricksRemaining' => $kilnBricksRemaining,
            'totalRawBricksRemaining' => $totalRawBricksRemaining,

            'adjustments' => $adjustments,
            'allCategories' => Category::where('name', '!=', 'khoya')->where('name', '!=', 'খোয়া')->orderBy('id')->get()
        ])->layout('layouts.app');
    }
}
