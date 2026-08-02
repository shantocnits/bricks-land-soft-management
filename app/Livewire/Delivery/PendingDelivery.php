<?php

namespace App\Livewire\Delivery;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Challan;
use App\Models\ChallanItem;
use App\Models\Delivery;
use App\Models\Setting;
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
    public $showChangeDateModal = false;
    public $showPrintModal = false;
    public $printChallan = null;
    public $printDelivery = null;
    public $isDeliveryPrint = false;

    // Change Date Form
    public $selectedDeliveryId = null;
    public $selectedChallanId = null;
    public $newDeliveryDate = '';
    public $changeOption = 'all';
    public $selectedChallanNo = '';
    public $currentDeliveryDate = '';
    public $changeDeliveries = [];
    
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
            $this->customerDue = $challan->customer_phone 
                ? Challan::where('customer_phone', $challan->customer_phone)->sum('due')
                : ($challan->due ?: 0);
            
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

    public function openChangeDateModal($challanId)
    {
        $challan = Challan::with('deliveries')->find($challanId);
        if ($challan) {
            $this->selectedChallanId = $challan->id;
            $this->selectedChallanNo = $challan->challan_no;
            $this->changeOption = 'all';
            
            // Get all unique categories for this challan that have deliveries
            $deliveries = Delivery::where('challan_id', $challan->id)->get();
            $this->changeDeliveries = $deliveries->map(function($d) {
                return [
                    'id' => $d->id,
                    'category_name' => $d->category_name,
                    'delivery_date' => $d->delivery_date ? $d->delivery_date->format('d-m-Y') : '',
                ];
            })->toArray();

            if (count($this->changeDeliveries) > 0) {
                $this->selectedDeliveryId = $this->changeDeliveries[0]['id'];
                $firstDel = $deliveries->first();
                $this->currentDeliveryDate = $firstDel && $firstDel->delivery_date ? $firstDel->delivery_date->format('d-m-Y') : now()->format('d-m-Y');
                $this->newDeliveryDate = $firstDel && $firstDel->delivery_date ? $firstDel->delivery_date->toDateString() : now()->toDateString();
            } else {
                $this->selectedDeliveryId = null;
                $date = $challan->date ?: now();
                $this->newDeliveryDate = $date->toDateString();
                $this->currentDeliveryDate = $date->format('d-m-Y');
            }
            
            $this->showChangeDateModal = true;
        }
    }

    public function updateDeliveryDate()
    {
        $this->validate([
            'newDeliveryDate' => 'required|date'
        ]);

        if ($this->selectedChallanId) {
            $challan = Challan::find($this->selectedChallanId);
            if ($challan) {
                $oldDate = $challan->date ? $challan->date->toDateString() : '';
                
                $challan->update(['date' => $this->newDeliveryDate]);

                if ($this->changeOption === 'all') {
                    Delivery::where('challan_id', $challan->id)
                        ->update(['delivery_date' => $this->newDeliveryDate]);
                } elseif ($this->selectedDeliveryId) {
                    Delivery::where('id', $this->selectedDeliveryId)
                        ->update(['delivery_date' => $this->newDeliveryDate]);
                }

                \App\Models\ActivityLog::log(
                    'ডেলিভারি তারিখ আপডেট',
                    "চালান নং {$this->selectedChallanNo} এর তারিখ পরিবর্তনঃ {$oldDate} -> {$this->newDeliveryDate}"
                );

                $this->dispatch('show-toast', message: 'ডেলিভারি তারিখ সফলভাবে পরিবর্তন করা হয়েছে।');
            }
        }
        $this->showChangeDateModal = false;
    }

    public function render()
    {
        $activeSeason = Setting::get('season', '২৫-২৬');

        // Select ChallanItems that are not fully delivered
        $query = ChallanItem::with(['challan'])
            ->whereRaw('quantity > delivered_quantity')
            ->whereHas('challan', function($sub) use ($activeSeason) {
                $sub->where(function($s) use ($activeSeason) {
                    $s->where('season', $activeSeason)->orWhereNull('season');
                });
            });

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
        $reportQuery = ChallanItem::with('challan')
            ->whereRaw('quantity > delivered_quantity')
            ->whereHas('challan', function($sub) use ($activeSeason) {
                $sub->where(function($s) use ($activeSeason) {
                    $s->where('season', $activeSeason)->orWhereNull('season');
                });
            });
        if ($this->search) {
            $reportQuery->where(function($q) {
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
