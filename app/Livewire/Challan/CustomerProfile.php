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
            $this->deliveryNo = '৫';
            $this->deliveryDate = now()->toDateString();
            $this->nextDeliveryDate = '';
            $this->deliveryNotes = $challan->notes;
            
            $firstItem = $challan->items->first();
            if ($firstItem) {
                $this->deliveryItemCategory = $firstItem->category_name;
                $this->deliveryTotalQty = $firstItem->quantity;
                $this->todayDeliveryQty = $firstItem->quantity;
            } else {
                $this->deliveryItemCategory = '';
                $this->deliveryTotalQty = 0;
                $this->todayDeliveryQty = 0;
            }
            
            $this->driverName = '';
            $this->driverPhone = '';
            $this->vehicleNo = '';
            $this->vehicleRent = $challan->transport_rent ?: 0;
            $this->smsToCustomer = true;
            
            $this->showDeliveryModal = true;
        }
    }

    public function saveDelivery()
    {
        $this->showDeliveryModal = false;
        session()->flash('message', 'ডেলিভারি তথ্য সফলভাবে সংরক্ষিত হয়েছে।');
    }

    public function openChallanDetailsModal($challanId)
    {
        $this->detailsChallan = Challan::with('items')->find($challanId);
        if ($this->detailsChallan) {
            $this->showChallanDetailsModal = true;
        }
    }

    public function delete($id)
    {
        Challan::destroy($id);
        session()->flash('message', 'চালান মুছে ফেলা হয়েছে।');
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
        $stats = [
            'total_bricks' => $allChallans->sum(fn($c) => $c->items->sum('quantity')),
            'delivered'    => $allChallans->sum(fn($c) => $c->items->sum('quantity')),
            'remaining'    => 0,
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

        $printChallans = (clone $query)->get();

        return view('livewire.challan.customer-profile', [
            'challans'      => $query->paginate(10),
            'printChallans' => $printChallans,
            'stats'         => $stats,
            'categories'    => Category::all(),
            'ledgers'       => Ledger::all()
        ])->layout('layouts.app');
    }
}
