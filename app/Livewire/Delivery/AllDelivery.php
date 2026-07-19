<?php

namespace App\Livewire\Delivery;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Delivery;
use App\Models\Challan;
use App\Models\ChallanItem;
use Illuminate\Support\Facades\DB;

class AllDelivery extends Component
{
    use WithPagination;

    // Filter states
    public $search = '';
    public $dateFrom = '';
    public $dateTo = '';
    
    // Sort states
    public $sortField = 'delivery_date';
    public $sortDirection = 'desc';
    
    // Pagination
    public $perPage = 10;
    
    // Modal states
    public $showDeliveryModal = false;
    public $showReportModal = false;
    public $showChangeDateModal = false;
    
    // Change Date Form
    public $selectedDeliveryId = null;
    public $newDeliveryDate = '';
    public $changeOption = 'all';
    public $selectedChallanNo = '';
    public $currentDeliveryDate = '';
    public $changeDeliveries = [];

    // New Delivery Form properties (for "ডেলিভারি দিন")
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
        $this->dateFrom = now()->startOfMonth()->toDateString();
        $this->dateTo = now()->toDateString();
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

    public function openChangeDateModal($deliveryId)
    {
        $delivery = Delivery::with('challan')->find($deliveryId);
        if ($delivery) {
            $this->selectedDeliveryId = $delivery->id;
            $this->newDeliveryDate = $delivery->delivery_date->toDateString();
            $this->currentDeliveryDate = $delivery->delivery_date->format('d-m-Y');
            $this->selectedChallanNo = $delivery->challan->challan_no ?? '';
            $this->changeOption = 'all';
            
            // Get all deliveries of this Challan on this date to populate dropdown
            $this->changeDeliveries = Delivery::where('challan_id', $delivery->challan_id)
                ->whereDate('delivery_date', $delivery->delivery_date->toDateString())
                ->get();
                
            $this->showChangeDateModal = true;
        }
    }

    public function updatedSelectedDeliveryId($value)
    {
        $delivery = Delivery::find($value);
        if ($delivery) {
            $this->currentDeliveryDate = $delivery->delivery_date->format('d-m-Y');
            $this->newDeliveryDate = $delivery->delivery_date->toDateString();
        }
    }

    public function updateDeliveryDate()
    {
        $this->validate([
            'newDeliveryDate' => 'required|date'
        ]);

        $delivery = Delivery::find($this->selectedDeliveryId);
        if ($delivery) {
            $oldDate = $delivery->delivery_date->toDateString();
            
            if ($this->changeOption === 'all') {
                // Update all deliveries of this Challan on this date to the new date
                Delivery::where('challan_id', $delivery->challan_id)
                    ->whereDate('delivery_date', $oldDate)
                    ->update(['delivery_date' => $this->newDeliveryDate]);
                
                \App\Models\ActivityLog::log(
                    'ডেলিভারি তারিখ আপডেট (পুরো চালান)',
                    "চালান নং {$this->selectedChallanNo} এর সকল ডেলিভারির তারিখ পরিবর্তনঃ {$oldDate} -> {$this->newDeliveryDate}"
                );
            } else {
                // Update only this specific delivery category
                $delivery->update([
                    'delivery_date' => $this->newDeliveryDate
                ]);

                \App\Models\ActivityLog::log(
                    'ডেলিভারি তারিখ আপডেট (শ্রেণি অনুযায়ী)',
                    "ডেলিভারি নং {$delivery->delivery_no} এর তারিখ পরিবর্তনঃ {$oldDate} -> {$this->newDeliveryDate}"
                );
            }

            session()->flash('message', 'ডেলিভারি তারিখ সফলভাবে পরিবর্তন করা হয়েছে।');
        }
        $this->showChangeDateModal = false;
    }

    public function openDeliveryModal($challanId)
    {
        $challan = Challan::with('items')->find($challanId);
        if ($challan) {
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
            $firstItem = $challan->items->first();
            if ($firstItem) {
                $this->selectedChallanItemId = $firstItem->id;
                $this->deliveryItemCategory = $firstItem->category_name;
                $this->deliveryTotalQty = $firstItem->quantity;
                $this->deliveredQtySoFar = $firstItem->delivered_quantity;
                $this->todayDeliveryQty = max(0, $firstItem->quantity - $firstItem->delivered_quantity);
            } else {
                $this->selectedChallanItemId = null;
                $this->deliveryItemCategory = '';
                $this->deliveryTotalQty = 0;
                $this->deliveredQtySoFar = 0;
                $this->todayDeliveryQty = 0;
            }
            
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
        $query = Delivery::with(['challan', 'challanItem']);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('delivery_no', 'like', '%' . $this->search . '%')
                  ->orWhere('category_name', 'like', '%' . $this->search . '%')
                  ->orWhereHas('challan', function($sub) {
                      $sub->where('customer_name', 'like', '%' . $this->search . '%')
                          ->orWhere('customer_phone', 'like', '%' . $this->search . '%')
                          ->orWhere('customer_address', 'like', '%' . $this->search . '%')
                          ->orWhere('challan_no', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->dateFrom && $this->dateTo) {
            $query->whereBetween('delivery_date', [$this->dateFrom, $this->dateTo]);
        }

        // Apply custom sorting
        if ($this->sortField === 'challan_no') {
            $query->join('challans', 'deliveries.challan_id', '=', 'challans.id')
                  ->select('deliveries.*')
                  ->orderBy('challans.challan_no', $this->sortDirection);
        } elseif ($this->sortField === 'customer_name') {
            $query->join('challans', 'deliveries.challan_id', '=', 'challans.id')
                  ->select('deliveries.*')
                  ->orderBy('challans.customer_name', $this->sortDirection);
        } else {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        $deliveries = $query->paginate($this->perPage);

        // Fetch report summary
        $reportQuery = Delivery::query();
        if ($this->dateFrom && $this->dateTo) {
            $reportQuery->whereBetween('delivery_date', [$this->dateFrom, $this->dateTo]);
        }
        $reportData = $reportQuery->select('category_name', DB::raw('SUM(quantity) as total_qty'))
                                  ->groupBy('category_name')
                                  ->get();

        return view('livewire.delivery.all-delivery', [
            'deliveries' => $deliveries,
            'reportData' => $reportData,
            'totalQty' => $reportData->sum('total_qty'),
        ])->layout('layouts.app');
    }
}
