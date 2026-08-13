<?php

namespace App\Livewire\Delivery;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Delivery;
use App\Models\Challan;
use App\Models\ChallanItem;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class AllDelivery extends Component
{
    use WithPagination;

    // Filter states
    public $search = '';
    public $dateFrom = '';
    public $dateTo = '';
    
    // Sort states
    public $sortField = 'id';
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
    public $showDeleteConfirmModal = false;
    public $deletingDeliveryId = null;
    
    // Change Date Form
    public $selectedChallanId = null;
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
    public $customerDue = 0;

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
            }
        }
    }

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        $this->dateFrom = null;
        $this->dateTo = null;
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

    public function openChangeDateModal($challanId)
    {
        $challan = Challan::with('deliveries')->find($challanId);
        if ($challan) {
            $this->selectedChallanId = $challan->id;
            $this->selectedChallanNo = $challan->challan_no;
            $this->changeOption = 'all';

            $delDateVal = $challan->delivery_date;
            if (!$delDateVal) {
                $firstDel = Delivery::where('challan_id', $challan->id)->latest('id')->first();
                $delDateVal = $firstDel ? $firstDel->delivery_date : $challan->date;
            }

            $dateObj = $delDateVal ? \Carbon\Carbon::parse($delDateVal) : now();
            $this->currentDeliveryDate = $dateObj->format('d-m-Y');
            $this->newDeliveryDate = $dateObj->toDateString();
            
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
            } else {
                $this->selectedDeliveryId = null;
            }
            
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

        if ($this->selectedChallanId) {
            $challan = Challan::find($this->selectedChallanId);
            if ($challan) {
                $oldDate = $challan->date ? $challan->date->toDateString() : '';
                
                $challan->update([
                    'date' => $this->newDeliveryDate,
                    'delivery_date' => $this->newDeliveryDate,
                ]);

                if ($this->changeOption === 'all' || !$this->selectedDeliveryId) {
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

    public function openDeliveryModal($challanId)
    {
        $challan = Challan::with('items')->find($challanId);
        if ($challan) {
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
        }
        $this->deletingDeliveryId = null;
        $this->showDeleteConfirmModal = false;
    }

    public function deleteDelivery($id)
    {
        $delivery = Delivery::find($id);
        if ($delivery) {
            if ($delivery->challan_item_id) {
                $item = ChallanItem::find($delivery->challan_item_id);
                if ($item) {
                    $item->delivered_quantity = max(0, $item->delivered_quantity - $delivery->quantity);
                    $item->save();
                }
            }

            \App\Models\ActivityLog::log(
                'ডেলিভারি ডিলিট',
                "ডেলিভারি নং {$delivery->delivery_no} (পরিমাণ: {$delivery->quantity}) মুছে ফেলা হয়েছে।"
            );

            $delivery->delete();
            $this->dispatch('show-toast', message: 'ডেলিভারি সফলভাবে মুছে ফেলা হয়েছে।');
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

    public function updatingDateFrom()
    {
        $this->resetPage();
    }

    public function updatingDateTo()
    {
        $this->resetPage();
    }

    public function render()
    {
        $activeSeason = Setting::get('season', '২৫-২৬');

        // Select ChallanItems that are not fully delivered (pending delivery list)
        $query = ChallanItem::with(['challan.deliveries'])
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

        if ($this->dateFrom && $this->dateTo) {
            $query->whereHas('challan', function($sub) {
                $sub->whereBetween('date', [$this->dateFrom, $this->dateTo]);
            });
        } elseif ($this->dateFrom) {
            $query->whereHas('challan', function($sub) {
                $sub->whereDate('date', '>=', $this->dateFrom);
            });
        } elseif ($this->dateTo) {
            $query->whereHas('challan', function($sub) {
                $sub->whereDate('date', '<=', $this->dateTo);
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
        } elseif ($this->sortField === 'delivery_date' || $this->sortField === 'date') {
            $query->join('challans', 'challan_items.challan_id', '=', 'challans.id')
                  ->select('challan_items.*')
                  ->orderBy('challans.date', $this->sortDirection);
        } else {
            $query->orderBy('challan_items.' . ($this->sortField ?: 'id'), $this->sortDirection);
        }

        $deliveries = $query->paginate($this->perPage);

        // Fetch report summary
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
        if ($this->dateFrom && $this->dateTo) {
            $reportQuery->whereHas('challan', function($sub) {
                $sub->whereBetween('date', [$this->dateFrom, $this->dateTo]);
            });
        } elseif ($this->dateFrom) {
            $reportQuery->whereHas('challan', function($sub) {
                $sub->whereDate('date', '>=', $this->dateFrom);
            });
        } elseif ($this->dateTo) {
            $reportQuery->whereHas('challan', function($sub) {
                $sub->whereDate('date', '<=', $this->dateTo);
            });
        }

        $reportData = $reportQuery->select('category_name', DB::raw('SUM(quantity - delivered_quantity) as total_qty'))
                                  ->groupBy('category_name')
                                  ->get();

        return view('livewire.delivery.all-delivery', [
            'deliveries' => $deliveries,
            'reportData' => $reportData,
            'totalQty' => $reportData->sum('total_qty'),
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
