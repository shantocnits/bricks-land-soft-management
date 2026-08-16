<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\AssetCategory;
use App\Models\Asset;
use App\Models\AssetIssue;
use App\Models\AssetHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MalamalStock extends Component
{
    use WithPagination, WithFileUploads;

    public string $activeTab = 'dashboard'; // dashboard, stock_list, issue_list, damaged_items, lost_items, history_log
    public string $search = '';
    public string $categoryFilter = 'all';
    public int $perPage = 10;

    // Add Asset Modal
    public bool $showAssetModal = false;
    public ?int $editingAssetId = null;
    public string $assetName = '';
    public string $assetCode = '';
    public ?int $assetCategoryId = null;
    public string $vendor = '';
    public string $unitPrice = '';
    public string $initialQty = '';
    public bool $hasWarranty = false;
    public string $warrantyExpiry = '';
    public $assetImage = null;
    public ?string $existingAssetImage = null;
    public string $assetNotes = '';

    // Delete Confirmation Modal
    public ?int $confirmDeleteAssetId = null;

    // Issue Modal
    public bool $showIssueModal = false;
    public ?int $selectedAssetId = null;
    public string $issuedTo = '';
    public string $issueLocation = '';
    public string $issueQty = '';
    public string $issueDate = '';
    public $issueImage = null;
    public string $issueNotes = '';

    // Detailed Return Issue Modal (Image 1 & 2)
    public bool $showReturnModal = false;
    public ?AssetIssue $selectedIssueForReturn = null;
    public string $returnEmployeeName = '';
    public string $returnGoodQty = '';
    public string $returnDamagedQty = '';
    public string $returnLostQty = '';
    public string $returnDate = '';
    public $returnImage = null;

    // Mark Damaged / Lost Modal
    public bool $showDamageModal = false;
    public string $damageType = 'damaged'; // damaged, lost
    public string $damageQty = '';
    public string $damageNotes = '';

    // Repair Modal for Damaged Items (Image 3)
    public bool $showRepairModal = false;
    public ?Asset $selectedAssetForRepair = null;
    public string $repairQty = '';
    public string $repairDate = '';
    public string $repairNotes = '';

    // Found Item Modal for Lost Items (Image 4)
    public bool $showFoundModal = false;
    public ?Asset $selectedAssetForFound = null;

    // Quick View Image Modal
    public bool $showQuickViewModal = false;
    public ?string $quickViewImageUrl = null;
    public ?string $quickViewTitle = null;

    // View Asset Details Modal
    public bool $showAssetViewModal = false;
    public ?Asset $selectedAssetForView = null;

    // Purchase Edit Modal (Image 4)
    public bool $showPurchaseEditModal = false;
    public ?int $editingPurchaseAssetId = null;
    public string $editVendor = '';
    public string $editUnitPrice = '0';
    public string $editWarrantyExpiry = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryFilter' => ['except' => 'all'],
    ];

    public function updatingSearch() { $this->resetPage(); }
    public function updatingCategoryFilter() { $this->resetPage(); }
    public function updatingPerPage() { $this->resetPage(); }
    public function updatingActiveTab() { $this->resetPage(); }

    // Mutual Auto-Reset for Return Modal (Rule 3.1)
    public function updatedReturnGoodQty($value)
    {
        if ((int)$value > 0) {
            $this->returnDamagedQty = '';
            $this->returnLostQty = '';
        }
    }

    public function updatedReturnDamagedQty($value)
    {
        if ((int)$value > 0) {
            $this->returnGoodQty = '';
            $this->returnLostQty = '';
        }
    }

    public function updatedReturnLostQty($value)
    {
        if ((int)$value > 0) {
            $this->returnGoodQty = '';
            $this->returnDamagedQty = '';
        }
    }

    public function selectCategoryFilter(string $catIdOrAll)
    {
        $this->categoryFilter = $catIdOrAll;
        $this->resetPage();
    }

    public function selectAssetCategory(int $catId)
    {
        $this->assetCategoryId = $catId;
    }

    public function selectPerPage(int $size)
    {
        $this->perPage = $size;
        $this->resetPage();
    }

    public function selectAssetForIssue(int $assetId)
    {
        $asset = Asset::find($assetId);
        if ($asset && $asset->current_qty > 0) {
            $this->selectedAssetId = $assetId;
        }
    }

    public function mount()
    {
        $this->issueDate = date('Y-m-d');
        $this->returnDate = date('Y-m-d');
        $this->repairDate = date('Y-m-d');
        
        $defaultCats = ['Tools', 'Machinery', 'Vehicles', 'Furniture', 'Electronics'];
        foreach ($defaultCats as $catName) {
            AssetCategory::firstOrCreate(['name' => $catName]);
        }
    }

    public function openQuickView(?string $url, ?string $title = '')
    {
        if (!$url) return;
        $this->quickViewImageUrl = $url;
        $this->quickViewTitle = $title;
        $this->showQuickViewModal = true;
    }

    public function openAssetModal(?int $id = null)
    {
        $this->resetValidation();
        $this->showAssetViewModal = false; // close detail view modal if open
        $this->editingAssetId = $id;

        if ($id) {
            $a = Asset::findOrFail($id);
            $this->assetName = $a->name;
            $this->assetCode = $a->code ?? '';
            $this->assetCategoryId = $a->category_id;
            $this->vendor = $a->vendor ?? '';
            $this->unitPrice = (string)$a->unit_price;
            $this->initialQty = (string)$a->total_qty;
            $this->hasWarranty = (bool)$a->has_warranty;
            $this->warrantyExpiry = $a->warranty_expiry ? $a->warranty_expiry->format('Y-m-d') : '';
            $this->existingAssetImage = $a->image;
            $this->assetNotes = $a->notes ?? '';
        } else {
            $this->assetName = '';
            $this->assetCode = '';
            $this->assetCategoryId = AssetCategory::first()?->id;
            $this->vendor = '';
            $this->unitPrice = '';
            $this->initialQty = '';
            $this->hasWarranty = false;
            $this->warrantyExpiry = '';
            $this->existingAssetImage = null;
            $this->assetNotes = '';
        }
        $this->assetImage = null;
        $this->showAssetModal = true;
    }

    public function openPurchaseEditModal(int $assetId)
    {
        $asset = Asset::findOrFail($assetId);
        $this->editingPurchaseAssetId = $asset->id;
        $this->editVendor = $asset->vendor ?: '';
        $this->editUnitPrice = (string)$asset->unit_price;
        $this->editWarrantyExpiry = $asset->warranty_expiry ? $asset->warranty_expiry->format('Y-m-d') : '';
        $this->showPurchaseEditModal = true;
    }

    public function savePurchaseEdit()
    {
        if (!$this->editingPurchaseAssetId) return;

        $asset = Asset::findOrFail($this->editingPurchaseAssetId);
        $asset->vendor = $this->editVendor;
        $asset->unit_price = (float)$this->editUnitPrice;
        if (!empty($this->editWarrantyExpiry)) {
            $asset->has_warranty = true;
            $asset->warranty_expiry = $this->editWarrantyExpiry;
        } else {
            $asset->has_warranty = false;
            $asset->warranty_expiry = null;
        }
        $asset->save();

        if ($this->selectedAssetForView && $this->selectedAssetForView->id === $asset->id) {
            $this->selectedAssetForView = $asset->fresh(['category']);
        }

        $this->showPurchaseEditModal = false;
        $msg = 'ক্রয়ের তথ্য সফলভাবে আপডেট হয়েছে!';
        session()->flash('message', $msg);
        $this->dispatch('show-toast', message: $msg, type: 'success');
    }

    public function saveAsset()
    {
        $this->validate([
            'assetName' => 'required|string|max:255',
            'unitPrice' => 'required|numeric|min:0',
            'initialQty' => 'required|integer|min:1',
            'warrantyExpiry' => 'nullable|required_if:hasWarranty,true|date',
        ], [
            'assetName.required' => 'প্রোডাক্টের নাম দেওয়া আবশ্যক',
            'unitPrice.required' => 'একক মূল্য দেওয়া আবশ্যক',
            'initialQty.required' => 'পরিমাণ দেওয়া আবশ্যক',
            'warrantyExpiry.required_if' => 'ওয়ারেন্টির মেয়াদ শেষ হওয়ার তারিখ নির্বাচন করুন',
        ]);

        $imagePath = null;
        if ($this->assetImage) {
            $imagePath = $this->assetImage->store('assets', 'public');
        }

        if ($this->editingAssetId) {
            $a = Asset::findOrFail($this->editingAssetId);
            $data = [
                'name' => trim($this->assetName),
                'code' => trim($this->assetCode),
                'category_id' => $this->assetCategoryId,
                'vendor' => trim($this->vendor),
                'unit_price' => (float)$this->unitPrice,
                'has_warranty' => $this->hasWarranty,
                'warranty_expiry' => ($this->hasWarranty && !empty($this->warrantyExpiry)) ? $this->warrantyExpiry : null,
                'notes' => trim($this->assetNotes),
            ];
            if ($imagePath) {
                if ($a->image && Storage::disk('public')->exists($a->image)) {
                    Storage::disk('public')->delete($a->image);
                }
                $data['image'] = $imagePath;
            }
            $a->update($data);
            $msg = 'অ্যাসেট পণ্য সফলভাবে আপডেট করা হয়েছে!';
        } else {
            $qty = (int)$this->initialQty;
            $asset = Asset::create([
                'name' => trim($this->assetName),
                'code' => trim($this->assetCode) ?: 'AST-'.str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT),
                'category_id' => $this->assetCategoryId,
                'vendor' => trim($this->vendor),
                'unit_price' => (float)$this->unitPrice,
                'total_qty' => $qty,
                'current_qty' => $qty,
                'has_warranty' => $this->hasWarranty,
                'warranty_expiry' => ($this->hasWarranty && !empty($this->warrantyExpiry)) ? $this->warrantyExpiry : null,
                'image' => $imagePath,
                'notes' => trim($this->assetNotes),
            ]);

            AssetHistory::create([
                'asset_id' => $asset->id,
                'action_type' => 'add_stock',
                'quantity' => $qty,
                'proof_image' => $imagePath,
                'notes' => 'নতুন স্টক যোগ করা হয়েছে',
            ]);
            $msg = 'নতুন মালামাল সফলভাবে স্টকে যুক্ত করা হয়েছে!';
        }

        $this->showAssetModal = false;
        session()->flash('message', $msg);
        $this->dispatch('show-toast', message: $msg, type: 'success');
    }

    public function viewAssetModal(int $id)
    {
        $this->selectedAssetForView = Asset::with('category')->findOrFail($id);
        $this->showAssetViewModal = true;
    }

    public function confirmDeleteAsset(int $id)
    {
        $this->confirmDeleteAssetId = $id;
    }

    public function cancelDeleteAsset()
    {
        $this->confirmDeleteAssetId = null;
    }

    public function executeDeleteAsset()
    {
        if ($this->confirmDeleteAssetId) {
            $this->deleteAsset($this->confirmDeleteAssetId);
            $this->confirmDeleteAssetId = null;
        }
    }

    public function deleteAsset(int $id)
    {
        $asset = Asset::findOrFail($id);
        if ($asset->image && Storage::disk('public')->exists($asset->image)) {
            Storage::disk('public')->delete($asset->image);
        }
        $asset->delete();

        $this->showAssetViewModal = false;
        $this->selectedAssetForView = null;

        $msg = 'অ্যাসেট সম্পূর্ণ মুছে ফেলা হয়েছে!';
        session()->flash('message', $msg);
        $this->dispatch('show-toast', message: $msg, type: 'success');
    }

    public function openIssueModal(?int $assetId = null)
    {
        $this->resetValidation();
        // Select first available asset with stock > 0
        $availableAsset = Asset::where('current_qty', '>', 0)->first();
        $this->selectedAssetId = $assetId ?: ($availableAsset ? $availableAsset->id : Asset::first()?->id);
        $this->issuedTo = '';
        $this->issueLocation = '';
        $this->issueQty = '';
        $this->issueDate = date('Y-m-d');
        $this->issueImage = null;
        $this->issueNotes = '';
        $this->showIssueModal = true;
    }

    public function saveIssue()
    {
        $this->validate([
            'selectedAssetId' => 'required|exists:assets,id',
            'issuedTo' => 'required|string|max:255',
            'issueQty' => 'required|integer|min:1',
            'issueDate' => 'required|date',
        ], [
            'selectedAssetId.required' => 'প্রোডাক্ট নির্বাচন করুন',
            'issuedTo.required' => 'যার নামে ইস্যু করা হচ্ছে তার নাম দেওয়া আবশ্যক',
            'issueQty.required' => 'ইস্যু পরিমাণ দেওয়া আবশ্যক',
        ]);

        $asset = Asset::findOrFail($this->selectedAssetId);
        $qty = (int)$this->issueQty;

        if ($asset->current_qty < $qty) {
            $this->addError('issueQty', "পর্যাপ্ত স্টক নেই! বর্তমান স্টক: {$asset->current_qty} টি");
            return;
        }

        $imgPath = null;
        if ($this->issueImage) {
            $imgPath = $this->issueImage->store('issues', 'public');
        }

        DB::transaction(function() use ($asset, $qty, $imgPath) {
            AssetIssue::create([
                'asset_id' => $asset->id,
                'issued_to' => trim($this->issuedTo),
                'location' => trim($this->issueLocation),
                'quantity' => $qty,
                'issue_date' => $this->issueDate,
                'status' => 'issued',
                'image' => $imgPath,
                'notes' => trim($this->issueNotes),
            ]);

            $asset->decrement('current_qty', $qty);
            $asset->increment('issued_qty', $qty);

            AssetHistory::create([
                'asset_id' => $asset->id,
                'action_type' => 'issue',
                'quantity' => $qty,
                'proof_image' => $imgPath,
                'notes' => "ইস্যু করা হলো: {$this->issuedTo} ({$this->issueLocation})",
            ]);
        });

        $this->showIssueModal = false;
        $msg = 'মালামাল সফলভাবে ইস্যু করা হয়েছে!';
        session()->flash('message', $msg);
        $this->dispatch('show-toast', message: $msg, type: 'success');
    }

    // Open Return Modal (Image 1 & 2)
    public function openReturnModal(int $issueId)
    {
        $this->resetValidation();
        $this->selectedIssueForReturn = AssetIssue::with('asset')->findOrFail($issueId);
        $this->returnEmployeeName = $this->selectedIssueForReturn->issued_to;
        $this->returnGoodQty = (string)$this->selectedIssueForReturn->quantity;
        $this->returnDamagedQty = '';
        $this->returnLostQty = '';
        $this->returnDate = date('Y-m-d');
        $this->returnImage = null;
        $this->showReturnModal = true;
    }

    // Save Return Issue (Image 1 & 2)
    public function saveReturn()
    {
        if (!$this->selectedIssueForReturn) return;

        $issue = $this->selectedIssueForReturn;
        $totalIssued = $issue->quantity;

        $good = (int)$this->returnGoodQty;
        $damaged = (int)$this->returnDamagedQty;
        $lost = (int)$this->returnLostQty;

        if (($good + $damaged + $lost) > $totalIssued) {
            $this->addError('returnGoodQty', "ফেরত পরিমাণের যোগফল মোট ইস্যু করা পরিমাণের ({$totalIssued}) চেয়ে বেশি হতে পারবে না!");
            return;
        }

        $proofPath = null;
        if ($this->returnImage) {
            $proofPath = $this->returnImage->store('returns', 'public');
        }

        DB::transaction(function() use ($issue, $good, $damaged, $lost, $proofPath) {
            $issue->update([
                'status' => 'returned',
                'return_date' => $this->returnDate ?: date('Y-m-d'),
            ]);

            $asset = $issue->asset;
            if ($asset) {
                if ($good > 0) $asset->increment('current_qty', $good);
                if ($damaged > 0) $asset->increment('damaged_qty', $damaged);
                if ($lost > 0) $asset->increment('lost_qty', $lost);

                $asset->decrement('issued_qty', min($asset->issued_qty, $issue->quantity));

                AssetHistory::create([
                    'asset_id' => $asset->id,
                    'action_type' => 'return',
                    'quantity' => $good + $damaged + $lost,
                    'good_qty' => $good,
                    'damaged_qty' => $damaged,
                    'lost_qty' => $lost,
                    'proof_image' => $proofPath ?: $issue->image,
                    'notes' => "ফেরত পাওয়া গেছে: {$this->returnEmployeeName} (ভালো: {$good}, নষ্ট: {$damaged}, হারানো: {$lost})",
                ]);
            }
        });

        $this->showReturnModal = false;
        $this->selectedIssueForReturn = null;
        $msg = 'ইস্যুকৃত পণ্য সফলভাবে ফেরত গ্রহণ করা হয়েছে!';
        session()->flash('message', $msg);
        $this->dispatch('show-toast', message: $msg, type: 'success');
    }

    // Repair Modal for Damaged Items
    public function openRepairModal(int $assetId)
    {
        $this->resetValidation();
        $this->selectedAssetForRepair = Asset::findOrFail($assetId);
        $this->repairQty = '';
        $this->repairDate = date('Y-m-d');
        $this->repairNotes = '';
        $this->showRepairModal = true;
    }

    public function saveRepair()
    {
        if (!$this->selectedAssetForRepair) return;

        $this->validate([
            'repairQty' => 'required|integer|min:1',
        ], [
            'repairQty.required' => 'মেরামত পরিমাণ দেওয়া আবশ্যক',
        ]);

        $asset = $this->selectedAssetForRepair;
        $qty = (int)$this->repairQty;

        if ($asset->damaged_qty < $qty) {
            $this->addError('repairQty', "নষ্ট স্টকের চেয়ে বেশি হতে পারবে না! বর্তমান নষ্ট স্টক: {$asset->damaged_qty} টি");
            return;
        }

        DB::transaction(function() use ($asset, $qty) {
            $asset->decrement('damaged_qty', $qty);
            $asset->increment('current_qty', $qty);

            AssetHistory::create([
                'asset_id' => $asset->id,
                'action_type' => 'repair',
                'quantity' => $qty,
                'notes' => "মেরামত সম্পন্ন করে স্টকে যুক্ত করা হলো: " . trim($this->repairNotes),
            ]);
        });

        $this->showRepairModal = false;
        $this->selectedAssetForRepair = null;
        $msg = 'মেরামত সম্পন্ন করে পণ্য স্টকে ফেরত যুক্ত করা হয়েছে!';
        session()->flash('message', $msg);
        $this->dispatch('show-toast', message: $msg, type: 'success');
    }

    // Found Item Modal for Lost Items
    public function openFoundModal(int $assetId)
    {
        $this->selectedAssetForFound = Asset::findOrFail($assetId);
        $this->showFoundModal = true;
    }

    public function confirmFound()
    {
        if (!$this->selectedAssetForFound) return;

        $asset = $this->selectedAssetForFound;
        $qty = min($asset->lost_qty, 1);

        if ($qty > 0) {
            DB::transaction(function() use ($asset, $qty) {
                $asset->decrement('lost_qty', $qty);
                $asset->increment('current_qty', $qty);

                AssetHistory::create([
                    'asset_id' => $asset->id,
                    'action_type' => 'found',
                    'quantity' => $qty,
                    'notes' => "হারানো পণ্য পাওয়া গেছে এবং স্টকে যোগ করা হয়েছে",
                ]);
            });
        }

        $this->showFoundModal = false;
        $this->selectedAssetForFound = null;
        $msg = 'হারানো পণ্য পুনরায় প্রধান স্টকে যুক্ত করা হয়েছে!';
        session()->flash('message', $msg);
        $this->dispatch('show-toast', message: $msg, type: 'success');
    }

    public function openDamageModal(int $assetId, string $type = 'damaged')
    {
        $this->resetValidation();
        $this->selectedAssetId = $assetId;
        $this->damageType = $type;
        $this->damageQty = '1';
        $this->damageNotes = '';
        $this->showDamageModal = true;
    }

    public function saveDamage()
    {
        $this->validate([
            'selectedAssetId' => 'required|exists:assets,id',
            'damageQty' => 'required|integer|min:1',
        ], [
            'damageQty.required' => 'পরিমাণ দেওয়া আবশ্যক',
        ]);

        $asset = Asset::findOrFail($this->selectedAssetId);
        $qty = (int)$this->damageQty;

        if ($asset->current_qty < $qty) {
            $this->addError('damageQty', "বর্তমান স্টকের চেয়ে বেশি হতে পারবে না! বর্তমান স্টক: {$asset->current_qty} টি");
            return;
        }

        DB::transaction(function() use ($asset, $qty) {
            $asset->decrement('current_qty', $qty);
            if ($this->damageType === 'damaged') {
                $asset->increment('damaged_qty', $qty);
            } else {
                $asset->increment('lost_qty', $qty);
            }

            AssetHistory::create([
                'asset_id' => $asset->id,
                'action_type' => $this->damageType,
                'quantity' => $qty,
                'notes' => ($this->damageType === 'damaged' ? 'নষ্ট আইটেম এন্ট্রি' : 'হারানো আইটেম এন্ট্রি') . ': ' . trim($this->damageNotes),
            ]);
        });

        $this->showDamageModal = false;
        $msg = 'রেকর্ড সফলভাবে আপডেট করা হয়েছে!';
        session()->flash('message', $msg);
        $this->dispatch('show-toast', message: $msg, type: 'success');
    }

    public function render()
    {
        $categories = AssetCategory::orderBy('name', 'asc')->get();

        // Summary Calculations & Money Values (Rule 1)
        $totalAssetCount = Asset::sum('total_qty');
        $currentStockCount = Asset::sum('current_qty');
        $damagedCount = Asset::sum('damaged_qty');
        $lostCount = Asset::sum('lost_qty');
        $totalAssetValue = Asset::select(DB::raw('SUM(unit_price * total_qty) as total_val'))->value('total_val') ?: 0;
        $currentStockValue = Asset::select(DB::raw('SUM(unit_price * current_qty) as total_val'))->value('total_val') ?: 0;
        $damagedValue = Asset::select(DB::raw('SUM(unit_price * damaged_qty) as total_val'))->value('total_val') ?: 0;
        $lostValue = Asset::select(DB::raw('SUM(unit_price * lost_qty) as total_val'))->value('total_val') ?: 0;

        // Dynamic Stock List Total Value calculation
        $stockListQuery = Asset::query();
        if ($this->categoryFilter !== 'all') {
            $stockListQuery->where('category_id', $this->categoryFilter);
        }
        if (!empty($this->search)) {
            $s = trim($this->search);
            $stockListQuery->where(function($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('code', 'like', "%{$s}%");
            });
        }
        $totalStockListValue = $stockListQuery->select(DB::raw('SUM(unit_price * total_qty) as total_val'))->value('total_val') ?: 0;

        // History Log Lifetime Cumulative Metrics (Rule 7: Non-decreasing)
        $returnedCount = AssetHistory::where('action_type', 'return')->sum('quantity');
        $goodCount = AssetHistory::where('action_type', 'return')->sum('good_qty');
        $damagedLogCount = AssetHistory::where('action_type', 'return')->sum('damaged_qty') + AssetHistory::where('action_type', 'damaged')->sum('quantity');
        $lostLogCount = AssetHistory::where('action_type', 'return')->sum('lost_qty') + AssetHistory::where('action_type', 'lost')->sum('quantity');

        // Data Query depending on active tab
        $query = null;

        if ($this->activeTab === 'stock_list' || $this->activeTab === 'damaged_items' || $this->activeTab === 'lost_items') {
            $query = Asset::with('category');

            if ($this->activeTab === 'stock_list' && $this->categoryFilter !== 'all') {
                $query->where('category_id', $this->categoryFilter);
            }

            if (!empty($this->search)) {
                $s = trim($this->search);
                $query->where(function($q) use ($s) {
                    $q->where('name', 'like', "%{$s}%")
                      ->orWhere('code', 'like', "%{$s}%");
                });
            }

            if ($this->activeTab === 'damaged_items') {
                $query->where('damaged_qty', '>', 0);
            } elseif ($this->activeTab === 'lost_items') {
                $query->where('lost_qty', '>', 0);
            }

            $records = $query->orderBy('name', 'asc')->paginate($this->perPage);

        } elseif ($this->activeTab === 'issue_list') {
            // Issue List ONLY fetches active issues where status = 'issued'
            $query = AssetIssue::with(['asset.category'])->where('status', 'issued');

            if (!empty($this->search)) {
                $s = trim($this->search);
                $query->where(function($q) use ($s) {
                    $q->where('issued_to', 'like', "%{$s}%")
                      ->orWhere('location', 'like', "%{$s}%")
                      ->orWhereHas('asset', function($aq) use ($s) {
                          $aq->where('name', 'like', "%{$s}%");
                      });
                });
            }

            $records = $query->orderBy('issue_date', 'desc')->orderBy('id', 'desc')->paginate($this->perPage);

        } elseif ($this->activeTab === 'history_log') {
            // Rule 6.1: History Log ONLY displays 'issue' and 'return' action types
            $query = AssetHistory::with(['asset.category'])
                ->whereIn('action_type', ['issue', 'return']);

            if (!empty($this->search)) {
                $s = trim($this->search);
                $query->whereHas('asset', function($aq) use ($s) {
                    $aq->where('name', 'like', "%{$s}%");
                });
            }

            $records = $query->orderBy('created_at', 'desc')->paginate($this->perPage);

        } elseif ($this->activeTab === 'dashboard') {
            $query = AssetHistory::with(['asset.category']);

            if (!empty($this->search)) {
                $s = trim($this->search);
                $query->whereHas('asset', function($aq) use ($s) {
                    $aq->where('name', 'like', "%{$s}%");
                });
            }

            $records = $query->orderBy('created_at', 'desc')->paginate($this->perPage);
        }

        $allAssets = Asset::orderBy('name', 'asc')->get();

        return view('livewire.malamal-stock', [
            'categories' => $categories,
            'allAssets' => $allAssets,
            'records' => $records,
            'totalAssetCount' => $totalAssetCount,
            'currentStockCount' => $currentStockCount,
            'damagedCount' => $damagedCount,
            'lostCount' => $lostCount,
            'totalAssetValue' => $totalAssetValue,
            'currentStockValue' => $currentStockValue,
            'damagedValue' => $damagedValue,
            'lostValue' => $lostValue,
            'totalStockListValue' => $totalStockListValue,
            'returnedCount' => $returnedCount,
            'goodCount' => $goodCount,
            'damagedLogCount' => $damagedLogCount,
            'lostLogCount' => $lostLogCount,
        ])->layout('layouts.app');
    }
}
