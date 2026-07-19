<?php

namespace App\Livewire\Delivery;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Challan;
use App\Models\ChallanItem;
use App\Models\Delivery;
use Illuminate\Support\Facades\DB;

class PendingDelivery extends Component
{
    use WithPagination;

    // Filter states
    public $search = '';
    public $dateFilter = ''; // Single date filter for challan date
    
    // Sort states
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    
    // Pagination
    public $perPage = 10;
    
    // Modal states
    public $showDeliveryModal = false;
    public $showReportModal = false;
    
    // New Delivery Form properties
    public $deliveryNo = '';
    public $challan_no = '';
    public $deliveryChallanId = null;
    public $customer_name = '';
    public $customer_phone = '';
    public $customer_address = '';
    
    public $deliveryDate = '';
    public $nextDeliveryDate = '';
    public $deliveryNotes = '';
    
    public $challanItems = [];
    public $selectedChallanItemId = null;
    public $deliveryItemCategory = '';
    public $deliveryTotalQty = 0;
    public $deliveredQtySoFar = 0;
    public $todayDeliveryQty = 0;
    
    public $driverName = '';
    public $driverPhone = '';
    public $vehicleNo = '';
    public $vehicleRent = 0;
    public $smsToCustomer = true;

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        $this->dateFilter = now()->toDateString();
        $this->deliveryDate = now()->toDateString();
    }

    public function updatedSelectedChallanItemId($value)
    {
        $item = ChallanItem::find($value);
        if ($item) {
            $this->deliveryItemCategory = $item->category_name;
            $this->deliveryTotalQty = $item->quantity;
            $this->deliveredQtySoFar = $item->delivered_quantity;
            $this->todayDeliveryQty = max(0, $item->quantity - $item->delivered_quantity);
        }
    }

    public function openDeliveryModal($challanItemId)
    {
        $item = ChallanItem::with('challan.items')->find($challanItemId);
        if ($item) {
            $challan = $item->challan;
            $this->deliveryChallanId = $challan->id;
            $this->challan_no = $challan->challan_no;
            $this->customer_name = $challan->customer_name;
            $this->customer_phone = $challan->customer_phone;
            $this->customer_address = $challan->customer_address;
            
            $this->deliveryNo = strval(Delivery::count() + 1);
            $this->deliveryDate = now()->toDateString();
            $this->nextDeliveryDate = '';
            $this->deliveryNotes = $challan->notes;
            
            $this->challanItems = $challan->items;
            $this->selectedChallanItemId = $item->id;
            $this->deliveryItemCategory = $item->category_name;
            $this->deliveryTotalQty = $item->quantity;
            $this->deliveredQtySoFar = $item->delivered_quantity;
            $this->todayDeliveryQty = max(0, $item->quantity - $item->delivered_quantity);
            
            $this->driverName = '';
            $this->driverPhone = '';
            $this->vehicleNo = '';
            $this->vehicleRent = 0;
            $this->smsToCustomer = true;
            
            $this->showDeliveryModal = true;
        }
    }

    public function saveDelivery()
    {
        $this->validate([
            'todayDeliveryQty' => 'required|integer|min:1',
            'deliveryNo' => 'required',
            'deliveryDate' => 'required|date',
            'selectedChallanItemId' => 'required'
        ]);

        $item = ChallanItem::find($this->selectedChallanItemId);
        if ($item) {
            $challan = $item->challan;
            
            // Create delivery entry
            Delivery::create([
                'delivery_no' => $this->deliveryNo,
                'challan_id' => $challan->id,
                'challan_item_id' => $item->id,
                'category_name' => $item->category_name,
                'quantity' => intval($this->todayDeliveryQty),
                'delivery_date' => $this->deliveryDate,
                'next_delivery_date' => $this->nextDeliveryDate ?: null,
                'notes' => $this->deliveryNotes,
                'driver_name' => $this->driverName,
                'driver_phone' => $this->driverPhone,
                'vehicle_no' => $this->vehicleNo,
                'vehicle_rent' => floatval($this->vehicleRent),
                'sms_sent' => $this->smsToCustomer,
            ]);

            // Increment delivered quantity
            $item->increment('delivered_quantity', intval($this->todayDeliveryQty));

            // Log activity
            $qtyStrBn = str_replace(
                ['0','1','2','3','4','5','6','7','8','9'],
                ['০','১','২','৩','৪','৫','৬','৭','৮','৯'],
                number_format($this->todayDeliveryQty)
            );
            \App\Models\ActivityLog::log(
                'নতুন ডেলিভারি',
                "চালান নং {$challan->challan_no}। শ্রেণি {$item->category_name}। ডেলিভারি পরিমাণঃ {$qtyStrBn}"
            );
            
            $this->showDeliveryModal = false;
            session()->flash('message', 'ডেলিভারি তথ্য সফলভাবে সংরক্ষিত হয়েছে।');
        }
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Select ChallanItems that are not fully delivered
        $query = ChallanItem::with(['challan'])
            ->whereRaw('quantity > delivered_quantity');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('category_name', 'like', '%' . $this->search . '%')
                  ->orWhereHas('challan', function($sub) {
                      $sub->where('customer_name', 'like', '%' . $this->search . '%')
                          ->orWhere('customer_phone', 'like', '%' . $this->search . '%')
                          ->orWhere('customer_address', 'like', '%' . $this->search . '%')
                          ->orWhere('challan_no', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->dateFilter) {
            $query->whereHas('challan', function($sub) {
                $sub->whereDate('date', $this->dateFilter);
            });
        }

        // Apply custom sorting
        if ($this->sortField === 'challan_no') {
            $query->join('challans', 'challan_items.challan_id', '=', 'challans.id')
                  ->select('challan_items.*')
                  ->orderBy('challans.challan_no', $this->sortDirection);
        } elseif ($this->sortField === 'customer_name') {
            $query->join('challans', 'challan_items.challan_id', '=', 'challans.id')
                  ->select('challan_items.*')
                  ->orderBy('challans.customer_name', $this->sortDirection);
        } else {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        $items = $query->paginate($this->perPage);

        // Fetch report summary for pending deliveries
        $reportQuery = ChallanItem::with('challan')->whereRaw('quantity > delivered_quantity');
        if ($this->dateFilter) {
            $reportQuery->whereHas('challan', function($sub) {
                $sub->whereDate('date', $this->dateFilter);
            });
        }
        $reportData = $reportQuery->select('category_name', DB::raw('SUM(quantity - delivered_quantity) as pending_qty'))
                                  ->groupBy('category_name')
                                  ->get();

        return view('livewire.delivery.pending-delivery', [
            'items' => $items,
            'reportData' => $reportData,
            'totalPendingQty' => $reportData->sum('pending_qty'),
        ])->layout('layouts.app');
    }
}
