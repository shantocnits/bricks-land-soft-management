<?php

namespace App\Livewire;

use App\Models\Vehicle;
use App\Models\VehicleTransaction;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class VehicleAccount extends Component
{
    use WithPagination;

    // View & Filter states
    public $selectedVehicleId = null;
    public $activeTab = 'income'; // 'income', 'expense', 'cash', 'due', 'history', 'ledger'
    public $filterPeriod = 'today'; // 'today', '7_days', '15_days', 'all'
    public $filterMonth = null;
    public $filterDate = null;

    // Vehicle Modal states
    public $showVehicleModal = false;
    public $editingVehicleId = null;
    public $vehicleName = '';
    public $showAddVehicleModal = false;
    public $showEditVehicleModal = false;
    public $activeVehicleModalTab = 'rename'; // 'rename', 'delete'
    public $newVehicleName = '';
    public $editVehicleId = null;
    public $renameVehicleName = '';

    // Transaction Modal states
    public $showTransactionModal = false;
    public $editingTransactionId = null;
    public $txDate = '';
    public $txDescription = '';
    public $txKhotianName = '';
    public $txQuantity = null;
    public $txRent = null;
    public $txReceived = null;
    public $txDue = null;
    public $txAmount = null;
    public $txDueAmount = null;
    public $txDueType = 'income';

    // Khotian Modal & Search states
    public $searchKhotian = '';
    public $showKhotianDetailModal = false;
    public $selectedKhotianName = null;
    public $khotianStartDate = null;
    public $khotianEndDate = null;
    public $khotianPerPage = 10;

    // Delete Confirmation Modal
    public $showDeleteConfirmModal = false;
    public $deletingTransactionId = null;
    public $deletingVehicleId = null;

    // Pagination
    public $perPage = 15;
    protected $paginationTheme = 'tailwind';

    protected function rules()
    {
        return [
            'newVehicleName' => 'required|string|min:1',
        ];
    }

    public function mount()
    {
        $this->txDate = Carbon::today()->format('Y-m-d');
        
        // Seed default vehicles if none exist
        if (Vehicle::count() === 0) {
            foreach (['car-1', 'car-2', 'car-3', 'car-4'] as $name) {
                Vehicle::create(['name' => $name]);
            }
        }
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->filterMonth = null;
        $this->filterDate = null;
        $this->resetPage();
    }

    // Vehicle Management
    public function selectVehicle($id = null)
    {
        $this->selectedVehicleId = $id;
        $this->activeTab = 'income';
        $this->filterMonth = null;
        $this->filterDate = null;
        $this->resetPage();
    }

    public function openAddVehicleModal()
    {
        $this->newVehicleName = '';
        $this->vehicleName = '';
        $this->editingVehicleId = null;
        $this->showAddVehicleModal = true;
        $this->showVehicleModal = true;
    }

    public function saveVehicle()
    {
        $name = trim($this->vehicleName ?: $this->newVehicleName);
        if (empty($name)) return;

        if ($this->editingVehicleId) {
            $v = Vehicle::find($this->editingVehicleId);
            if ($v) {
                $v->update(['name' => $name]);
                session()->flash('message', 'গাড়ির নাম পরিবর্তন করা হয়েছে!');
            }
        } else {
            Vehicle::create(['name' => $name]);
            session()->flash('message', 'নতুন গাড়ি সফলভাবে যুক্ত করা হয়েছে!');
        }
        $this->showVehicleModal = false;
        $this->showAddVehicleModal = false;
        $this->showEditVehicleModal = false;
        $this->vehicleName = '';
        $this->newVehicleName = '';
        $this->editingVehicleId = null;
    }

    public function saveNewVehicle()
    {
        $this->vehicleName = $this->newVehicleName;
        $this->saveVehicle();
    }

    public function openEditVehicleModal($vehicleId = null)
    {
        $v = $vehicleId ? Vehicle::find($vehicleId) : ($this->selectedVehicleId ? Vehicle::find($this->selectedVehicleId) : Vehicle::first());
        if ($v) {
            $this->editVehicleId = $v->id;
            $this->editingVehicleId = $v->id;
            $this->renameVehicleName = $v->name;
            $this->vehicleName = $v->name;
        }
        $this->activeVehicleModalTab = 'rename';
        $this->showEditVehicleModal = true;
        $this->showVehicleModal = true;
    }

    public function selectEditVehicle($id)
    {
        $this->editVehicleId = $id;
        $this->editingVehicleId = $id;
        $v = Vehicle::find($id);
        if ($v) {
            $this->renameVehicleName = $v->name;
            $this->vehicleName = $v->name;
        }
    }

    public function updateVehicleName()
    {
        $this->vehicleName = $this->renameVehicleName;
        $this->saveVehicle();
    }

    public function deleteVehicle($vehicleId = null)
    {
        $id = $vehicleId ?: $this->editVehicleId ?: $this->editingVehicleId;
        if ($id) {
            $v = Vehicle::find($id);
            if ($v) {
                VehicleTransaction::where('vehicle_id', $v->id)->delete();
                $v->delete();
                if ($this->selectedVehicleId == $id) {
                    $this->selectedVehicleId = null;
                }
                session()->flash('message', 'যে গাড়িটি ডিলেট করেছেন তার সকল হিসাব মুছে দেওয়া হয়েছে!');
            }
        }
        $this->showVehicleModal = false;
        $this->showEditVehicleModal = false;
        $this->showDeleteConfirmModal = false;
    }

    // Transaction Management
    public function openTransactionModal($txId = null)
    {
        if ($txId) {
            $tx = VehicleTransaction::findOrFail($txId);
            $this->editingTransactionId = $tx->id;
            $this->txDate = $tx->date ? $tx->date->format('Y-m-d') : Carbon::today()->format('Y-m-d');
            $this->txDescription = $tx->description;
            $this->txKhotianName = $tx->khotian_name;
            $this->txQuantity = $tx->quantity;
            $this->txDueType = $tx->type ?: 'income';
            $this->txDue = $tx->due_amount;
            $this->txDueAmount = $tx->due_amount;
            $this->txAmount = $tx->amount ?: $tx->received;

            if ($this->activeTab === 'due') {
                $this->txRent = $tx->due_amount > 0 ? $tx->due_amount : $tx->rent;
                $this->txReceived = null;
            } else {
                $this->txRent = $tx->rent;
                $this->txReceived = $tx->received;
            }
        } else {
            $this->editingTransactionId = null;
            $this->txDate = Carbon::today()->format('Y-m-d');
            $this->txDescription = '';
            $this->txKhotianName = '';
            $this->txQuantity = null;
            $this->txRent = null;
            $this->txReceived = null;
            $this->txDue = null;
            $this->txDueAmount = null;
            $this->txDueType = 'income';
            $this->txAmount = null;
        }
        $this->showTransactionModal = true;
    }

    public function editTransaction($txId)
    {
        $this->openTransactionModal($txId);
    }

    public function clearTransactionForm()
    {
        $this->txDescription = '';
        $this->txKhotianName = '';
        $this->txQuantity = null;
        $this->txRent = null;
        $this->txReceived = null;
        $this->txDue = null;
        $this->txDueAmount = null;
        $this->txAmount = null;
    }

    public function saveTransaction()
    {
        if (!$this->selectedVehicleId) return;

        $due = 0;
        $amount = 0;

        if ($this->activeTab === 'income') {
            $due = floatval($this->txRent) - floatval($this->txReceived);
            if ($due < 0) $due = 0;
            $amount = floatval($this->txReceived);
        } elseif ($this->activeTab === 'expense') {
            $due = floatval($this->txRent) - floatval($this->txReceived);
            if ($due < 0) $due = 0;
            $amount = floatval($this->txReceived);
        } elseif ($this->activeTab === 'cash') {
            if (floatval($this->txRent) > 0) {
                $amount = floatval($this->txRent);
            } else {
                $amount = floatval($this->txReceived);
            }
        } elseif ($this->activeTab === 'due') {
            $due = floatval($this->txRent) - floatval($this->txReceived);
            if ($due < 0) $due = 0;
            $amount = floatval($this->txReceived);
        }

        $typeToSave = $this->activeTab ?: 'income';
        if ($this->editingTransactionId && $this->activeTab === 'due') {
            $typeToSave = $this->txDueType ?: 'income';
        }

        VehicleTransaction::updateOrCreate(
            ['id' => $this->editingTransactionId],
            [
                'vehicle_id'   => $this->selectedVehicleId,
                'type'         => $typeToSave,
                'date'         => $this->txDate ?: Carbon::today()->format('Y-m-d'),
                'description'  => $this->txDescription,
                'khotian_name' => $this->txKhotianName ?: $this->txDescription,
                'quantity'     => floatval($this->txQuantity),
                'rent'         => floatval($this->txRent),
                'received'     => floatval($this->txReceived),
                'due_amount'   => $due,
                'amount'       => $amount,
            ]
        );

        $this->showTransactionModal = false;
        session()->flash('message', $this->editingTransactionId ? 'হিসাব আপডেট করা হয়েছে!' : 'নতুন হিসাব যুক্ত করা হয়েছে!');
    }

    public function confirmDeleteTransaction($txId)
    {
        $this->deletingTransactionId = $txId;
        $this->showDeleteConfirmModal = true;
    }

    public function notifyCashRestriction()
    {
        $this->dispatch('show-toast', message: 'এই হিসাব ক্যাশ খাতা থেকে পরিবর্তন করা যাবে না');
        session()->flash('message', 'এই হিসাব ক্যাশ খাতা থেকে পরিবর্তন করা যাবে না');
    }

    public function deleteTransaction($txId = null)
    {
        $id = $txId ?: $this->deletingTransactionId;
        if ($id) {
            $tx = VehicleTransaction::find($id);
            if ($tx) {
                $tx->delete();
                session()->flash('message', 'গাড়ির হিসাব ডিলেট করা হয়েছে!');
            }
        }
        $this->showDeleteConfirmModal = false;
        $this->deletingTransactionId = null;
    }

    // Khotian Detail Modal
    public function openKhotianDetailModal($name)
    {
        $this->selectedKhotianName = $name;
        $this->khotianStartDate = null;
        $this->khotianEndDate = null;
        $this->showKhotianDetailModal = true;
    }

    public function setFilterPeriod($period)
    {
        $this->filterPeriod = $period;
        $this->filterMonth = null;
        $this->filterDate = null;
        $this->resetPage();
    }

    public function selectPerPage($size)
    {
        $this->perPage = $size;
        $this->resetPage();
    }

    public function render()
    {
        $vehicles = Vehicle::orderBy('id', 'asc')->get();
        $selectedVehicle = $this->selectedVehicleId ? Vehicle::find($this->selectedVehicleId) : null;

        // Base Transaction Query with Date Filters
        $txQuery = VehicleTransaction::query();

        if ($this->filterDate) {
            $txQuery->whereDate('date', $this->filterDate);
        } elseif ($this->filterMonth) {
            $txQuery->whereMonth('date', Carbon::parse($this->filterMonth)->month)
                    ->whereYear('date', Carbon::parse($this->filterMonth)->year);
        } elseif ($this->filterPeriod === 'today') {
            $txQuery->whereDate('date', Carbon::today());
        } elseif ($this->filterPeriod === '7_days') {
            $txQuery->whereBetween('date', [Carbon::today()->subDays(7), Carbon::today()]);
        } elseif ($this->filterPeriod === '15_days') {
            $txQuery->whereBetween('date', [Carbon::today()->subDays(15), Carbon::today()]);
        }

        // Summary Calculations for Top KPI Cards
        $totalIncome  = (clone $txQuery)->where('type', 'income')->sum('received');
        $totalExpense = (clone $txQuery)->where('type', 'expense')->sum('amount');
        $totalCash    = $totalIncome - $totalExpense;
        $mahajanTaken = (clone $txQuery)->where('type', 'cash')->sum('amount');
        $mahajanGiven = (clone $txQuery)->where('type', 'due')->sum('due_amount');
        $cashJer      = $totalCash + $mahajanTaken - $mahajanGiven;

        // Income Report table data for main dashboard view
        $incomeReport = [];
        foreach ($vehicles as $v) {
            $vInc = VehicleTransaction::where('vehicle_id', $v->id)->where('type', 'income')->sum('received');
            $vExp = VehicleTransaction::where('vehicle_id', $v->id)->where('type', 'expense')->sum('amount');
            $incomeReport[] = [
                'vehicle' => $v,
                'income'  => $vInc,
                'expense' => $vExp,
                'net'     => $vInc - $vExp,
            ];
        }

        // Specific Vehicle Transactions Query & Tab Badge Calculations
        $vehicleTransactions = collect();
        $totalReceivedForVehicle = 0;
        $vehicleTotalIncome = 0;
        $vehicleTotalExpense = 0;
        $vehicleCash = 0;
        $vehicleCashJer = 0;
        $vehicleDueGet = 0;
        $vehicleDuePay = 0;
        $khotianCards = collect();
        $khotianDetailTransactions = collect();

        $sumQuantity = 0;
        $sumRent = 0;
        $sumReceived = 0;
        $sumDue = 0;
        $sumCashIn = 0;
        $sumCashOut = 0;
        $sumDueGet = 0;
        $sumDuePay = 0;
        $sumExpenseAmount = 0;

        if ($this->selectedVehicleId) {
            // Vehicle specific calculations for tab top-right badges
            $vehicleTotalIncome  = VehicleTransaction::where('vehicle_id', $this->selectedVehicleId)->where('type', 'income')->sum('received');
            $vehicleTotalExpense = VehicleTransaction::where('vehicle_id', $this->selectedVehicleId)->where('type', 'expense')->sum('amount');
            $vehicleCash        = $vehicleTotalIncome - $vehicleTotalExpense;
            $vehicleCashJer     = $vehicleCash;
            $vehicleDueGet      = VehicleTransaction::where('vehicle_id', $this->selectedVehicleId)->where('type', 'income')->sum('due_amount');
            $vehicleDuePay      = VehicleTransaction::where('vehicle_id', $this->selectedVehicleId)->where('type', 'expense')->sum('due_amount');

            $vTxQuery = VehicleTransaction::where('vehicle_id', $this->selectedVehicleId);
            
            if ($this->activeTab === 'ledger') {
                // Group transactions for Khotian Cards from expense table entries
                $khotianGroupQuery = VehicleTransaction::where('vehicle_id', $this->selectedVehicleId)
                    ->where('type', 'expense');
                if (!empty($this->searchKhotian)) {
                    $khotianGroupQuery->where(function($q) {
                        $q->where('khotian_name', 'like', '%'.$this->searchKhotian.'%')
                          ->orWhere('description', 'like', '%'.$this->searchKhotian.'%');
                    });
                }
                $khotianCards = $khotianGroupQuery->selectRaw('COALESCE(NULLIF(khotian_name, ""), description) as kname, SUM(rent) as total_bill, SUM(received) as total_payment, SUM(due_amount) as total_due, SUM(amount) as total_amount, COUNT(id) as total_count')
                    ->groupBy('kname')
                    ->get();
            } else {
                if (in_array($this->activeTab, ['income', 'expense', 'cash', 'due', 'history'])) {
                    if ($this->activeTab === 'income') {
                        $vTxQuery->where('type', 'income');
                    } elseif ($this->activeTab === 'expense') {
                        $vTxQuery->where('type', 'expense');
                    } elseif ($this->activeTab === 'cash') {
                        $vTxQuery->where('type', 'cash');
                    } elseif ($this->activeTab === 'due') {
                        $vTxQuery->where(function($q) {
                            $q->where('due_amount', '>', 0)->orWhere('type', 'due');
                        });
                    }
                }
                $totalReceivedForVehicle = (clone $vTxQuery)->sum('received');

                // Compute sums for footer summary row
                $sumQuantity = (clone $vTxQuery)->sum('quantity');
                $sumRent     = (clone $vTxQuery)->sum('rent');
                $sumReceived = (clone $vTxQuery)->sum('received');
                $sumDue      = (clone $vTxQuery)->sum('due_amount');

                // Dynamic Cash In and Cash Out sum calculations
                $userCashRows = VehicleTransaction::where('vehicle_id', $this->selectedVehicleId)->where('type', 'cash')->get();
                $userCashIn  = $userCashRows->sum(function($tx) { return $tx->received ?: 0; });
                $userCashOut = $userCashRows->sum(function($tx) { return $tx->rent ?: ($tx->amount ?: 0); });

                $sumCashIn  = $vehicleTotalIncome + $userCashIn;
                $sumCashOut = $vehicleTotalExpense + $vehicleDuePay + $userCashOut;

                $sumDueGet   = (clone $vTxQuery)->where('type', 'income')->sum('due_amount');
                $sumDuePay   = (clone $vTxQuery)->where('type', 'expense')->sum('due_amount');
                $sumExpenseAmount = (clone $vTxQuery)->where('type', 'expense')->sum('amount');

                $vehicleTransactions = $vTxQuery->orderBy('updated_at', 'desc')->orderBy('id', 'desc')->paginate($this->perPage);
            }

            // If Khotian detail modal is open
            if ($this->showKhotianDetailModal && $this->selectedKhotianName) {
                $kDetailQuery = VehicleTransaction::where('vehicle_id', $this->selectedVehicleId)
                    ->where('type', 'expense')
                    ->where(function($q) {
                        $q->where('khotian_name', $this->selectedKhotianName)
                          ->orWhere('description', $this->selectedKhotianName);
                    });
                if ($this->khotianStartDate && $this->khotianEndDate) {
                    $kDetailQuery->whereBetween('date', [$this->khotianStartDate, $this->khotianEndDate]);
                }

                $khotianTotalQty     = (clone $kDetailQuery)->sum('quantity');
                $khotianTotalBill    = (clone $kDetailQuery)->sum('rent');
                $khotianTotalPayment = (clone $kDetailQuery)->sum('received');
                $khotianNetDue       = (clone $kDetailQuery)->sum('due_amount');

                $khotianDetailTransactions = $kDetailQuery->orderBy('date', 'desc')->orderBy('id', 'desc')->paginate($this->khotianPerPage, ['*'], 'khotianPage');
            }
        }

        $khotianList = collect();
        if ($this->selectedVehicleId) {
            $khotianList = VehicleTransaction::where('vehicle_id', $this->selectedVehicleId)
                ->where('type', 'expense')
                ->whereNotNull('khotian_name')
                ->where('khotian_name', '!=', '')
                ->pluck('khotian_name')
                ->unique()
                ->values();
        }

        return view('livewire.vehicle-account', [
            'vehicles'                  => $vehicles,
            'selectedVehicle'           => $selectedVehicle,
            'totalIncome'               => $totalIncome,
            'totalExpense'              => $totalExpense,
            'totalCash'                 => $totalCash,
            'mahajanTaken'              => $mahajanTaken,
            'mahajanGiven'              => $mahajanGiven,
            'cashJer'                   => $cashJer,
            'incomeReport'              => $incomeReport,
            'vehicleTransactions'       => $vehicleTransactions,
            'totalReceivedForVehicle'   => $totalReceivedForVehicle,
            'vehicleTotalIncome'        => $vehicleTotalIncome,
            'vehicleTotalExpense'       => $vehicleTotalExpense,
            'vehicleCash'               => $vehicleCash,
            'vehicleCashJer'            => $vehicleCashJer,
            'vehicleDueGet'             => $vehicleDueGet,
            'vehicleDuePay'             => $vehicleDuePay,
            'khotianCards'              => $khotianCards,
            'khotianDetailTransactions' => $khotianDetailTransactions,
            'khotianTotalQty'           => $khotianTotalQty ?? 0,
            'khotianTotalBill'          => $khotianTotalBill ?? 0,
            'khotianTotalPayment'       => $khotianTotalPayment ?? 0,
            'khotianNetDue'             => $khotianNetDue ?? 0,
            'khotianList'               => $khotianList,
            'sumQuantity'               => $sumQuantity,
            'sumRent'                   => $sumRent,
            'sumReceived'               => $sumReceived,
            'sumDue'                    => $sumDue,
            'sumCashIn'                 => $sumCashIn,
            'sumCashOut'                => $sumCashOut,
            'sumDueGet'                 => $sumDueGet,
            'sumDuePay'                 => $sumDuePay,
            'sumExpenseAmount'          => $sumExpenseAmount,
        ])->layout('layouts.app');
    }
}
