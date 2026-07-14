<?php
 
 namespace App\Livewire\Challan;
 
 use Livewire\Component;
 use Livewire\WithPagination;
 use App\Models\Challan;
 use App\Models\ChallanItem;
 use App\Models\Category;
 use App\Models\Ledger;
 
 class PendingChallan extends Component
 {
     use WithPagination;
 
     public $search = '';
     public $showModal = false;
     public $showReport = false;
     public $editingId = null;
 
     // Form fields
     public $customer_type = 'new'; // new, old
     public $customer_phone = '';
     public $customer_name = '';
     public $customer_address = '';
     public $ledger_id = '';
     public $challan_no = '';
     public $notes = '';
     public $date = '';
     
     public $rent = 0;
     public $transport_rent = 0;
     public $discount = 0;
     public $cash = 0;
     public $send_sms = false;
 
     // Items array
     public $items = [];
 
     // Calculated values
     public $value = 0;
     public $grand_total = 0;
     public $due = 0;
 
     protected $paginationTheme = 'tailwind';
 
     public function mount()
     {
         $this->date = now()->toDateString();
     }
 
     public function openAddModal()
     {
         $this->resetForm();
         $this->editingId = null;
         $this->challan_no = $this->generateChallanNo();
         $this->addItem();
         $this->showModal = true;
     }
 
     public function generateChallanNo()
     {
         $lastId = Challan::max('id') ?: 0;
         return (string)($lastId + 1);
     }
 
     public function closeModal()
     {
         $this->showModal = false;
         $this->resetForm();
     }

     public function openReport()
     {
         $this->showReport = true;
     }

     public function closeReport()
     {
         $this->showReport = false;
     }

     public function getReportDataProperty()
     {
         $challans = Challan::with('items')
             ->where('challan_type', 'অগ্রিম')
             ->get();

         $byCategory = [];
         foreach ($challans as $challan) {
             foreach ($challan->items as $item) {
                 $key = $item->category_name;
                 if (!isset($byCategory[$key])) {
                     $byCategory[$key] = ['category' => $key, 'challan_count' => 0, 'quantity' => 0];
                 }
                 $byCategory[$key]['challan_count']++;
                 $byCategory[$key]['quantity'] += floatval($item->quantity);
             }
         }

         return [
             'rows'            => array_values($byCategory),
             'total_value'     => $challans->sum('value'),
             'total_discount'  => $challans->sum('discount'),
             'total_transport' => $challans->sum('transport_rent'),
             'total_grand'     => $challans->sum('grand_total'),
             'total_cash'      => $challans->sum('cash'),
             'total_due'       => $challans->sum('due'),
             'total_qty'       => $challans->sum(fn($c) => $c->items->sum('quantity')),
             'total_challans'  => $challans->count(),
         ];
     }
 
     public function resetForm()
     {
         $this->customer_type = 'new';
         $this->customer_phone = '';
         $this->customer_name = '';
         $this->customer_address = '';
         $this->ledger_id = '';
         $this->notes = '';
         $this->rent = 0;
         $this->transport_rent = 0;
         $this->discount = 0;
         $this->cash = 0;
         $this->send_sms = false;
         $this->items = [];
         $this->value = 0;
         $this->grand_total = 0;
         $this->due = 0;
         $this->editingId = null;
         $this->resetValidation();
     }
 
     public function addItem()
     {
         $this->items[] = [
             'category_name' => '',
             'rate' => 0,
             'quantity' => 0,
             'amount' => 0
         ];
         $this->calculateTotals();
     }
 
     public function removeItem($index)
     {
         unset($this->items[$index]);
         $this->items = array_values($this->items);
         $this->calculateTotals();
     }
 
     public function selectCategory($index, $categoryName)
     {
         $this->items[$index]['category_name'] = $categoryName;
         $this->updatedItems($categoryName, $index . '.category_name');
     }
 
     public function updatedItems($value, $key)
     {
         $parts = explode('.', $key);
         if (count($parts) === 2) {
             $index = $parts[0];
             $property = $parts[1];
 
             if ($property === 'category_name') {
                 $category = Category::where('name', $value)->first();
                 if ($category) {
                     $this->items[$index]['rate'] = $category->rate;
                 }
             }
 
             $rate = floatval($this->items[$index]['rate'] ?? 0);
             $qty = floatval($this->items[$index]['quantity'] ?? 0);
             $this->items[$index]['amount'] = $rate * $qty;
         }
 
         $this->calculateTotals();
     }
 
     public function updatedLedgerId($value)
     {
         if ($this->customer_type === 'old' && $value) {
             $ledger = Ledger::find($value);
             if ($ledger) {
                 $this->customer_name = $ledger->name;
                 $this->customer_address = 'খতিয়ান গ্রাহক';
             }
         }
     }
 
     public function updatedCustomerType($value)
     {
         if ($value === 'new') {
             $this->ledger_id = '';
             $this->customer_name = '';
             $this->customer_address = '';
         }
     }
 
     public function updated($propertyName)
     {
         if (in_array($propertyName, ['rent', 'transport_rent', 'discount', 'cash'])) {
             $this->calculateTotals();
         }
     }
 
     public $newCategoryInput = '';
 
     public function addCategoryOption()
     {
         $name = trim($this->newCategoryInput);
         if ($name !== '') {
             Category::updateOrCreate(['name' => $name], ['type' => 'ইট', 'rate' => 0.00]);
             $this->newCategoryInput = '';
             session()->flash('category_success', 'নতুন শ্রেণি যুক্ত হয়েছে।');
         }
     }
 
     public function deleteCategoryOption($id)
     {
         Category::destroy($id);
         session()->flash('category_success', 'শ্রেণিটি মুছে ফেলা হয়েছে।');
     }
 
     public function calculateTotals()
     {
         $subtotal = 0;
         foreach ($this->items as $item) {
             $subtotal += floatval($item['amount'] ?? 0);
         }
         $this->value = $subtotal;
         
         $transRentVal = floatval($this->transport_rent ?: 0);
         $discountVal = floatval($this->discount ?: 0);
         $cashVal = floatval($this->cash ?: 0);
 
         $this->rent = 0; // Rent is removed, transport_rent is used
         $this->grand_total = $subtotal + $transRentVal - $discountVal;
         $this->due = $this->grand_total - $cashVal;
     }
 
     public function save()
     {
         $this->validate([
             'customer_name' => 'required|string|max:255',
             'items' => 'required|array|min:1',
             'items.*.category_name' => 'required|string',
             'items.*.quantity' => 'required|numeric|min:1',
             'items.*.rate' => 'required|numeric|min:0',
         ], [
             'customer_name.required' => 'কাস্টমারের নাম আবশ্যক।',
             'items.required' => 'কমপক্ষে একটি আইটেম যুক্ত করুন।',
             'items.*.category_name.required' => 'শ্রেণি আবশ্যক।',
             'items.*.quantity.required' => 'পরিমাণ আবশ্যক।',
         ]);
 
         $challanData = [
             'customer_type' => $this->customer_type,
             'customer_phone' => $this->customer_phone,
             'customer_name' => $this->customer_name,
             'customer_address' => $this->customer_address,
             'challan_no' => $this->challan_no ?: $this->generateChallanNo(),
             'date' => $this->date ?: now()->toDateString(),
             'challan_type' => 'অগ্রিম',
             'notes' => $this->notes,
             'value' => $this->value,
             'total_value' => $this->value,
             'rent' => $this->rent ?: 0,
             'transport_rent' => $this->transport_rent ?: 0,
             'discount' => $this->discount ?: 0,
             'grand_total' => $this->grand_total,
             'cash' => $this->cash ?: 0,
             'due' => $this->due,
             'send_sms' => $this->send_sms,
         ];
 
         if ($this->editingId) {
             $challan = Challan::findOrFail($this->editingId);
             $challan->update($challanData);
             $challan->items()->delete();
         } else {
             $challan = Challan::create($challanData);
         }
 
         foreach ($this->items as $item) {
             ChallanItem::create([
                 'challan_id' => $challan->id,
                 'category_name' => $item['category_name'],
                 'rate' => $item['rate'],
                 'quantity' => $item['quantity'],
                 'amount' => $item['amount'],
             ]);
         }
 
         session()->flash('message', $this->editingId ? 'অগ্রিম চালান সফলভাবে আপডেট করা হয়েছে।' : 'অগ্রিম চালান সফলভাবে সংরক্ষিত হয়েছে।');
         $this->closeModal();
     }
 
     public function edit($id)
     {
         $challan = Challan::with('items')->findOrFail($id);
         $this->editingId = $id;
         $this->customer_type = $challan->customer_type;
         $this->customer_phone = $challan->customer_phone;
         $this->customer_name = $challan->customer_name;
         $this->customer_address = $challan->customer_address;
         $this->challan_no = $challan->challan_no;
         $this->date = $challan->date ? $challan->date->toDateString() : now()->toDateString();
         $this->notes = $challan->notes;
         $this->rent = $challan->rent;
         $this->transport_rent = $challan->transport_rent;
         $this->discount = $challan->discount;
         $this->cash = $challan->cash;
         $this->send_sms = $challan->send_sms;
 
         $this->items = [];
         foreach ($challan->items as $item) {
             $this->items[] = [
                 'category_name' => $item->category_name,
                 'rate' => $item->rate,
                 'quantity' => $item->quantity,
                 'amount' => $item->amount,
             ];
         }
 
         $this->calculateTotals();
         $this->showModal = true;
     }
 
     public function delete($id)
     {
         Challan::destroy($id);
         session()->flash('message', 'চালান মুছে ফেলা হয়েছে।');
     }
 
     public function render()
     {
         $query = Challan::with('items')->where('challan_type', 'অগ্রিম');
 
         if ($this->search) {
             $query->where('customer_name', 'like', '%' . $this->search . '%')
                   ->orWhere('customer_phone', 'like', '%' . $this->search . '%')
                   ->orWhere('challan_no', 'like', '%' . $this->search . '%');
         }
 
         return view('livewire.challan.pending-challan', [
             'challans' => $query->paginate(10),
             'categories' => Category::all(),
             'ledgers' => Ledger::all()
         ])->layout('layouts.app');
     }
 }
 
