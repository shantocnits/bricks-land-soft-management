<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Challan;
use App\Models\ChallanItem;
use Illuminate\Support\Facades\DB;

class Customer extends Component
{
    use WithPagination;

    // Search and pagination parameters
    public string $search = '';
    public int $perPage = 10;

    // Modal display states
    public bool $showUpdateModal = false;
    public bool $showDateModal = false;

    // Targeted customer keys for updates
    public ?string $selectedPhone = null;
    public ?string $selectedName = null;

    // Info Update form inputs
    public string $updateId = '';
    public string $updateName = '';
    public string $updatePhone = '';
    public string $updateAddress = '';

    // Due Payment Date form inputs
    public string $newDueDate = '';
    public string $dueDateNotes = '';
    public float $selectedCustomerTotalDue = 0;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function selectPage($page)
    {
        $this->setPage($page);
    }

    public function openUpdateModal($phone, $name)
    {
        $this->selectedPhone = $phone;
        $this->selectedName = $name;

        // Query latest challan records for customer defaults
        $latest = Challan::where(function ($q) use ($phone, $name) {
            if ($phone) {
                $q->where('customer_phone', $phone);
            } else {
                $q->where('customer_name', $name);
            }
        })->latest()->first();

        if ($latest) {
            $this->updateId = strval($latest->id);
            $this->updateName = $latest->customer_name ?? '';
            $this->updatePhone = $latest->customer_phone ?? '';
            $this->updateAddress = $latest->customer_address ?? '';
        }

        $this->showUpdateModal = true;
    }

    public function clearUpdateForm()
    {
        $this->updateName = '';
        $this->updatePhone = '';
        $this->updateAddress = '';
    }

    public function saveCustomerInfo()
    {
        $this->validate([
            'updateName' => 'required|string|max:255',
            'updatePhone' => 'required|string|max:255',
            'updateAddress' => 'nullable|string|max:255',
        ]);

        $oldName = $this->selectedName;
        $oldPhone = $this->selectedPhone;

        // Sync all historical challan fields under this customer identifier
        Challan::where(function ($q) use ($oldPhone, $oldName) {
            if ($oldPhone) {
                $q->where('customer_phone', $oldPhone);
            } else {
                $q->where('customer_name', $oldName);
            }
        })->update([
            'customer_name' => $this->updateName,
            'customer_phone' => $this->updatePhone,
            'customer_address' => $this->updateAddress,
        ]);

        // Sync payments & ledgers if name updated
        if ($oldName && $oldName !== $this->updateName) {
            \App\Models\Payment::where('ledger', $oldName)->update(['ledger' => $this->updateName]);
            \App\Models\Ledger::where('name', $oldName)->update(['name' => $this->updateName]);
        }

        $this->showUpdateModal = false;
        $this->dispatch('show-toast', message: 'কাস্টমারের তথ্য সফলভাবে আপডেট করা হয়েছে।', type: 'success');
    }

    public function openDateModal($phone, $name)
    {
        $this->selectedPhone = $phone;
        $this->selectedName = $name;

        $challans = Challan::where(function ($q) use ($phone, $name) {
            if ($phone) {
                $q->where('customer_phone', $phone);
            } else {
                $q->where('customer_name', $name);
            }
        })->get();

        $this->selectedCustomerTotalDue = (float) $challans->sum('due');

        $latest = $challans->sortByDesc('created_at')->first();

        if ($latest) {
            $this->newDueDate = $latest->due_payment_date ? \Carbon\Carbon::parse($latest->due_payment_date)->toDateString() : '';
            $this->dueDateNotes = $latest->notes ?? '';
        }

        $this->showDateModal = true;
    }

    public function saveDueDate()
    {
        Challan::where(function ($q) {
            if ($this->selectedPhone) {
                $q->where('customer_phone', $this->selectedPhone);
            } else {
                $q->where('customer_name', $this->selectedName);
            }
        })->update([
            'due_payment_date' => $this->newDueDate ?: null,
            'notes' => $this->dueDateNotes ?: null,
        ]);

        $this->showDateModal = false;
        $this->dispatch('show-toast', message: 'পরিশোধের তারিখ সফলভাবে আপডেট করা হয়েছে।', type: 'success');
    }

    public function render()
    {
        // Unique customer grouping queries
        $query = Challan::select('customer_phone', 'customer_name', 'customer_address', DB::raw('MIN(id) as first_id'))
            ->groupBy('customer_phone', 'customer_name', 'customer_address');

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('customer_name', 'like', '%' . $this->search . '%')
                  ->orWhere('customer_phone', 'like', '%' . $this->search . '%')
                  ->orWhere('customer_address', 'like', '%' . $this->search . '%');
            });
        }

        $customersRaw = $query->get();

        $customersList = [];
        foreach ($customersRaw as $raw) {
            $phone = $raw->customer_phone;
            $name = $raw->customer_name;

            // Compute values by customer identifier group
            $challans = Challan::where(function ($q) use ($phone, $name) {
                if ($phone) {
                    $q->where('customer_phone', $phone);
                } else {
                    $q->where('customer_name', $name);
                }
            })->get();

            $challanIds = $challans->pluck('id');
            $totalPurchased = ChallanItem::whereIn('challan_id', $challanIds)->sum('quantity');
            $totalDelivered = ChallanItem::whereIn('challan_id', $challanIds)->sum('delivered_quantity');
            
            $totalValue = $challans->sum('grand_total');
            $totalPaid = $challans->sum('cash');
            $totalDue = $challans->sum('due');

            $latestChallan = $challans->sortByDesc('created_at')->first();
            $firstChallan = $challans->sortBy('id')->first();
            $dueDate = $latestChallan ? $latestChallan->due_payment_date : null;
            $notes = $latestChallan ? $latestChallan->notes : null;
            $primaryId = $firstChallan ? $firstChallan->id : $raw->first_id;

            $customersList[] = [
                'id' => $primaryId,
                'name' => $name,
                'phone' => $phone,
                'address' => $raw->customer_address,
                'total_purchased' => $totalPurchased,
                'total_delivered' => $totalDelivered,
                'delivery_due' => max(0, $totalPurchased - $totalDelivered),
                'total_value' => $totalValue,
                'total_paid' => $totalPaid,
                'total_due' => $totalDue,
                'due_date' => $dueDate,
                'notes' => $notes
            ];
        }

        // Custom memory pagination wrapping
        $total = count($customersList);
        $offset = ($this->getPage() - 1) * $this->perPage;
        $paginatedItems = array_slice($customersList, $offset, $this->perPage);

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedItems,
            $total,
            $this->perPage,
            $this->getPage(),
            ['path' => route('customer')]
        );

        return view('livewire.customer', [
            'customers' => $paginator,
            'count' => $total
        ])->layout('layouts.app');
    }
}
