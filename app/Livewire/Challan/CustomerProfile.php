<?php

namespace App\Livewire\Challan;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Challan;
use App\Models\Category;
use App\Models\Setting;
use App\Models\Ledger;

class CustomerProfile extends Component
{
    use WithPagination;

    public $phone;
    public $customer_name = '';
    public $customer_phone = '';
    public $customer_address = '';

    public $search = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $activeTab = 'all_challan'; // all_challan, delivery_history, due_history

    // Form fields for editing/details if needed
    public $showDeliveryModal = false;
    public $showChallanDetailsModal = false;
    public $showDeliveryDetailsModal = false;
    public $deliveryChallanId = null;
    public $detailsChallan = null;

    // Delivery Modal fields
    public $deliveryNo = '৫';
    public $deliveryDate = '';
    public $nextDeliveryDate = '';
    public $deliveryNotes = '';
    public $driverName = '';
    public $driverPhone = '';
    public $vehicleNo = '';
    public $vehicleRent = 0;
    public $smsToCustomer = true;
    public $todayDeliveryQty = 0;
    public $deliveryItemCategory = '';
    public $deliveryTotalQty = 0;
    public $deliveryChallanDue = 0;
     public $selectedChallanItemId = null;
     public $deliveredQtySoFar = 0;
     public $challanItems = [];

     public function updatedSelectedChallanItemId($value)
     {
         $item = \App\Models\ChallanItem::find($value);
         if ($item) {
             $this->deliveryItemCategory = $item->category_name;
             $this->deliveryTotalQty = $item->quantity;
             $this->deliveredQtySoFar = $item->delivered_quantity;
             $this->todayDeliveryQty = max(0, $item->quantity - $item->delivered_quantity);
         }
     }
    protected $paginationTheme = 'tailwind';

    public function mount($phone)
    {
        $this->phone = $phone;
        $this->dateFrom = now()->startOfMonth()->toDateString();
        $this->dateTo   = now()->toDateString();

        // Get basic customer metadata from their latest challan
        $latestChallan = Challan::where('customer_phone', $phone)
            ->orWhere('customer_name', $phone)
            ->latest()
            ->first();

        if ($latestChallan) {
            $this->customer_name = $latestChallan->customer_name;
            $this->customer_phone = $latestChallan->customer_phone;
            $this->customer_address = $latestChallan->customer_address;
        } else {
            $this->customer_name = $phone;
            $this->customer_phone = $phone;
        }
    }

    public function openDeliveryModal($challanId)
    {
        $this->deliveryChallanId = $challanId;
        $challan = Challan::with('items')->find($challanId);
        if ($challan) {
            $this->customer_name = $challan->customer_name;
            $this->customer_phone = $challan->customer_phone;
            $this->customer_address = $challan->customer_address;
            $this->challan_no = $challan->challan_no;
            
            // Auto increment delivery no
            $this->deliveryNo = strval(\App\Models\Delivery::count() + 1);
            $this->deliveryDate = now()->toDateString();
            $this->nextDeliveryDate = '';
            $this->deliveryNotes = $challan->notes;
            $this->deliveryChallanDue = $challan->due;
            
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
            'deliveryDate' => 'required|date'
        ]);

        $item = \App\Models\ChallanItem::find($this->selectedChallanItemId);
        if ($item) {
            $challan = $item->challan;
            
            // Create delivery entry
            \App\Models\Delivery::create([
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
        }

        $this->showDeliveryModal = false;
        session()->flash('message', 'ডেলিভারি তথ্য সফলভাবে সংরক্ষিত হয়েছে।');
    }

    public $showPrintModal = false;
    public $printChallan = null;

    public function openPrintModal($challanId)
    {
        $this->printChallan = Challan::with('items')->find($challanId);
        if ($this->printChallan) {
            $this->showPrintModal = true;
        }
    }

    public function closePrintModal()
    {
        $this->showPrintModal = false;
        $this->printChallan = null;
    }

    public function openChallanDetailsModal($challanId)
    {
        $this->detailsChallan = Challan::with('items')->find($challanId);
        if ($this->detailsChallan) {
            $this->showChallanDetailsModal = true;
        }
    }

    public function openDeliveryDetailsModal($challanId)
    {
        $this->detailsChallan = Challan::with('items')->find($challanId);
        if ($this->detailsChallan) {
            $this->showDeliveryDetailsModal = true;
        }
    }

    public function delete($id)
    {
        Challan::destroy($id);
        session()->flash('message', 'চালান মুছে ফেলা হয়েছে।');
    }

    public function render()
    {
        // Base query for stats (unfiltered by date for customer totals)
        $allChallans = Challan::with('items')
            ->where(function($q) {
                $q->where('customer_phone', $this->phone)
                  ->orWhere('customer_name', $this->phone);
            })->get();

        // Calculate Stats
        $totalBricks = $allChallans->sum(fn($c) => $c->items->sum('quantity'));
        $deliveredBricks = $allChallans->sum(fn($c) => $c->items->sum('delivered_quantity'));
        $stats = [
            'total_bricks' => $totalBricks,
            'delivered'    => $deliveredBricks,
            'remaining'    => max(0, $totalBricks - $deliveredBricks),
            'total_value'  => $allChallans->sum('grand_total'),
            'paid'         => $allChallans->sum('cash'),
            'due'          => $allChallans->sum('due'),
        ];

        // Filtered query for the list
        $query = Challan::with('items')
            ->where(function($q) {
                $q->where('customer_phone', $this->phone)
                  ->orWhere('customer_name', $this->phone);
            });

        if ($this->dateFrom) {
            $query->whereDate('date', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->whereDate('date', '<=', $this->dateTo);
        }

        if ($this->search) {
            $query->where(function($q) {
                $q->where('challan_no', 'like', '%' . $this->search . '%')
                  ->orWhere('notes', 'like', '%' . $this->search . '%');
            });
        }

        $query->orderBy('id', 'desc');

        $printChallans = (clone $query)->get();

        // Calculate Print Totals for the view ($printTotal)
        $printTotal = [
            'quantity'  => $printChallans->sum(fn($c) => $c->items->sum('quantity')),
            'value'     => $printChallans->sum('value'),
            'transport' => $printChallans->sum('transport_rent'),
            'discount'  => $printChallans->sum('discount'),
            'grand'     => $printChallans->sum('grand_total'),
            'cash'      => $printChallans->sum('cash'),
            'due'       => $printChallans->sum('due'),
        ];

        return view('livewire.challan.customer-profile', [
            'challans'      => $query->paginate(10),
            'printChallans' => $printChallans,
            'printTotal'    => $printTotal, // Added to resolve undefined variable error
            'stats'         => $stats,
            'categories'    => Category::all(),
            'ledgers'       => Ledger::all()
        ])->layout('layouts.app');
    }
}