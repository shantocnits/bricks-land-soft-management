<?php

namespace App\Livewire\Delivery;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Delivery;
use App\Models\Challan;
use App\Models\ChallanItem;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class TodayDelivery extends Component
{
    use WithPagination;

    // Filter states
    public $search = '';
    public $dateFilter = ''; // Single date filter
    public $dateFrom = '';   // Range start
    public $dateTo = '';     // Range end
    
    // Sort states
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    
    // Pagination
    public $perPage = 10;
    
    // Modal states
    public $showDeliveryModal = false;
    public $showDeliveryDetailsModal = false;
    public $showReportModal = false;
    public $showPrintModal = false;
    public $printChallan = null;
    public $printDelivery = null;
    public $isDeliveryPrint = false;
    public $showDeleteConfirmModal = false;
    public $deletingDeliveryId = null;
    
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
    public $customerDue = 0;
    
    // Detail view state
    public $selectedDelivery = null;

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        $this->dateFilter = now()->toDateString();
        $this->deliveryDate = now()->toDateString();
    }

    public function updatedChallanNo($value)
    {
        $challan = Challan::with('items')->where('challan_no', $value)->first();
        if ($challan) {
            $this->deliveryChallanId = $challan->id;
            $this->customer_name = $challan->customer_name;
            $this->customer_phone = $challan->customer_phone;
            $this->customer_address = $challan->customer_address;
            $this->customerDue = $challan->customer_phone 
                ? Challan::where('customer_phone', $challan->customer_phone)->sum('due')
                : ($challan->due ?: 0);
            
            $this->challanItems = $challan->items;
            $firstItem = $challan->items->first();
            if ($firstItem) {
                $this->selectedChallanItemId = $firstItem->id;
                $this->deliveryItemCategory = $firstItem->category_name;
                $this->deliveryTotalQty = $firstItem->quantity;
                $this->deliveredQtySoFar = $firstItem->delivered_quantity;
                $this->todayDeliveryQty = max(0, $firstItem->quantity - $firstItem->delivered_quantity);
            } else {
                $this->resetItemFields();
            }
        } else {
            $this->resetCustomerFields();
        }
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

    protected function resetCustomerFields()
    {
        $this->deliveryChallanId = null;
        $this->customer_name = '';
        $this->customer_phone = '';
        $this->customer_address = '';
        $this->customerDue = 0;
        $this->challanItems = [];
        $this->resetItemFields();
    }

    protected function resetItemFields()
    {
        $this->selectedChallanItemId = null;
        $this->deliveryItemCategory = '';
        $this->deliveryTotalQty = 0;
        $this->deliveredQtySoFar = 0;
        $this->todayDeliveryQty = 0;
    }

    public function openNewDeliveryModal()
    {
        $this->resetExcept(['dateFilter', 'dateFrom', 'dateTo']);
        $this->deliveryNo = strval(Delivery::count() + 1);
        $this->deliveryDate = now()->toDateString();
        $this->showDeliveryModal = true;
    }

    public function saveDelivery($andPrint = false)
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
            $delivery = Delivery::create([
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
            $challan->update(['delivery_date' => $this->deliveryDate]);

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
            $this->dispatch('show-toast', message: 'ডেলিভারি তথ্য সফলভাবে সংরক্ষিত হয়েছে।');

            if ($andPrint) {
                $this->openPrintModal($challan->id, $delivery->id);
            }
        }
    }

    public function confirmDeleteDelivery($id)
    {
        $this->deletingDeliveryId = $id;
        $this->showDeleteConfirmModal = true;
    }

    public function cancelDeleteDelivery()
    {
        $this->deletingDeliveryId = null;
        $this->showDeleteConfirmModal = false;
    }

    public function deleteDeliveryConfirmed()
    {
        if ($this->deletingDeliveryId) {
            $this->deleteDelivery($this->deletingDeliveryId);
            $this->deletingDeliveryId = null;
        }
        $this->showDeleteConfirmModal = false;
    }

    public function deleteDelivery($id)
    {
        $delivery = Delivery::find($id);
        if ($delivery) {
            $item = ChallanItem::find($delivery->challan_item_id);
            if ($item) {
                $item->decrement('delivered_quantity', $delivery->quantity);
            }
            $delivery->delete();
            $this->dispatch('show-toast', message: 'ডেলিভারি তথ্য মুছে ফেলা হয়েছে।');
        }
    }

    public function openDeliveryDetails($id)
    {
        $this->selectedDelivery = Delivery::with(['challan.items'])->find($id);
        if ($this->selectedDelivery) {
            $this->showDeliveryDetailsModal = true;
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

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Delivery::with(['challan', 'challanItem']);

        // Filter by active season
        $activeSeason = Setting::get('season', '২৫-২৬');
        $query->whereHas('challan', function($sub) use ($activeSeason) {
            $sub->where(function($s) use ($activeSeason) {
                $s->where('season', $activeSeason)->orWhereNull('season');
            });
        });

        // Filter by search (customer details or challan number or delivery number)
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

        // Date filters logic
        if ($this->dateFilter) {
            $query->whereDate('delivery_date', $this->dateFilter);
        } else {
            $query->whereDate('delivery_date', now()->toDateString());
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
        $reportQuery->whereHas('challan', function($sub) use ($activeSeason) {
            $sub->where(function($s) use ($activeSeason) {
                $s->where('season', $activeSeason)->orWhereNull('season');
            });
        });

        if ($this->search) {
            $reportQuery->where(function($q) {
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
        if ($this->dateFilter) {
            $reportQuery->whereDate('delivery_date', $this->dateFilter);
        } else {
            $reportQuery->whereDate('delivery_date', now()->toDateString());
        }
        
        $reportData = $reportQuery->select('category_name', DB::raw('SUM(quantity) as total_qty'))
                                  ->groupBy('category_name')
                                  ->get();

        return view('livewire.delivery.today-delivery', [
            'deliveries' => $deliveries,
            'reportData' => $reportData,
            'totalQty' => $reportData->sum('total_qty'),
            'allDeliveriesForReport' => $deliveries->items(),
        ])->layout('layouts.app');
    }

    public function openPrintModal($challanId, $deliveryId = null)
    {
        $this->printChallan = Challan::with(['items', 'deliveries'])->find($challanId);
        if ($deliveryId) {
            $this->printDelivery = Delivery::find($deliveryId);
        } else {
            $this->printDelivery = null;
        }
        $this->isDeliveryPrint = true;
        $this->showPrintModal = true;
    }

    public function closePrintModal()
    {
        $this->showPrintModal = false;
        $this->isDeliveryPrint = false;
        $this->printChallan = null;
        $this->printDelivery = null;
    }
}
