<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\Category;
use App\Models\Setting;

class CategoryRate extends Component
{
    public $name = '';
    public $type = 'ইট';
    public $rate = '';

    // Search and Modal Controls
    public $search = '';
    public $showModal = false;
    public $editingCategoryId = null;
    public $confirmingDeleteId = null;

    // Dynamic dropdown options management
    public $typeOptions = [];
    public $newTypeInput = '';

    public function rules()
    {
        $allowed = implode(',', $this->typeOptions ?: ['ইট', 'আধলা', 'অন্যান্য']);
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|in:' . $allowed,
            'rate' => 'required|numeric|min:0',
        ];
    }

    protected $messages = [
        'name.required' => 'শ্রেণির নাম আবশ্যক।',
        'type.required' => 'শ্রেণির ধরণ নির্বাচন করুন।',
        'type.in' => 'শ্রেণির ধরণ সঠিক নয়।',
        'rate.required' => 'রেট আবশ্যক।',
        'rate.numeric' => 'রেট একটি সংখ্যা হতে হবে।',
    ];

    public function mount()
    {
        // Load Category Types from settings DB or set default list
        $typesJson = Setting::get('category_types');
        if (!$typesJson) {
            $this->typeOptions = ['ইট', 'আধলা', 'অন্যান্য'];
            Setting::set('category_types', json_encode($this->typeOptions));
        } else {
            $this->typeOptions = json_decode($typesJson, true) ?: ['ইট', 'আধলা', 'অন্যান্য'];
        }

        if (count($this->typeOptions) > 0) {
            $this->type = $this->typeOptions[0];
        }

        // Preseed defaults if empty
        if (Category::count() === 0) {
            Category::create(['name' => '১ নং', 'type' => 'ইট', 'rate' => 8.10]);
            Category::create(['name' => 'পিকেট', 'type' => 'ইট', 'rate' => 9.00]);
            Category::create(['name' => '২ নং (ক)', 'type' => 'ইট', 'rate' => 8.50]);
            Category::create(['name' => '২ নং (খ)', 'type' => 'ইট', 'rate' => 7.50]);
            Category::create(['name' => '৩ নং ছালট', 'type' => 'ইট', 'rate' => 4.50]);
            Category::create(['name' => '৩ নং গরিয়া', 'type' => 'ইট', 'rate' => 6.00]);
            Category::create(['name' => 'এলোট', 'type' => 'ইট', 'rate' => 3.00]);
            Category::create(['name' => '১ নং আদলা', 'type' => 'আধলা', 'rate' => 4.50]);
            Category::create(['name' => '৩ নং আদলা', 'type' => 'আধলা', 'rate' => 1.50]);
            Category::create(['name' => 'রাবিশ', 'type' => 'অন্যান্য', 'rate' => 500.00]);
            Category::create(['name' => 'খোয়া', 'type' => 'অন্যান্য', 'rate' => 120.00]);
        }
    }

    public function addType()
    {
        $newType = trim($this->newTypeInput);
        if ($newType !== '') {
            if (!in_array($newType, $this->typeOptions)) {
                array_unshift($this->typeOptions, $newType);
                Setting::set('category_types', json_encode($this->typeOptions));
            }
            $this->type = $newType;
            $this->newTypeInput = '';
            $this->dispatch('show-toast', message: 'নতুন টাইপ যুক্ত করা হয়েছে।', type: 'success');
        }
    }

    public function deleteType($typeToDelete)
    {
        if (count($this->typeOptions) <= 1) {
            $this->dispatch('show-toast', message: 'কমপক্ষে একটি ধরন থাকতে হবে।', type: 'danger');
            return;
        }

        $this->typeOptions = array_values(array_diff($this->typeOptions, [$typeToDelete]));
        Setting::set('category_types', json_encode($this->typeOptions));
        
        if ($this->type === $typeToDelete) {
            $this->type = $this->typeOptions[0];
        }
        $this->dispatch('show-toast', message: 'ধরন মুছে ফেলা হয়েছে।', type: 'success');
    }

    public function openAddModal()
    {
        $this->resetForm();
        if (count($this->typeOptions) > 0) {
            $this->type = $this->typeOptions[0];
        }
        $this->showModal = true;
    }

    public function openModal()
    {
        $this->openAddModal();
    }

    public function save()
    {
        // Block action if logged in as Demo
        if (auth()->user()->hasRole('demo')) {
            $this->dispatch('show-toast', message: 'ডেমো মোডে কোনো শ্রেণি পরিবর্তন করা সম্ভব নয়।', type: 'danger');
            $this->showModal = false;
            return;
        }

        $this->validate();

        if ($this->editingCategoryId) {
            $category = Category::find($this->editingCategoryId);
            if ($category) {
                $category->update([
                    'name' => $this->name,
                    'type' => $this->type,
                    'rate' => $this->rate,
                ]);
                $this->dispatch('show-toast', message: 'শ্রেণি সফলভাবে আপডেট করা হয়েছে।', type: 'success');
            }
        } else {
            Category::create([
                'name' => $this->name,
                'type' => $this->type,
                'rate' => $this->rate,
            ]);
            $this->dispatch('show-toast', message: 'নতুন শ্রেণি সফলভাবে যুক্ত করা হয়েছে।', type: 'success');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function editCategory($id)
    {
        // Block action if logged in as Demo
        if (auth()->user()->hasRole('demo')) {
            $this->dispatch('show-toast', message: 'ডেমো মোডে শ্রেণি সংশোধন করা সম্ভব নয়।', type: 'danger');
            return;
        }

        $category = Category::find($id);
        if ($category) {
            $this->editingCategoryId = $category->id;
            $this->name = $category->name;
            $this->type = $category->type;
            $this->rate = $category->rate;
            $this->showModal = true;
        }
    }

    public function deleteCategory($id)
    {
        // Block action if logged in as Demo
        if (auth()->user()->hasRole('demo')) {
            $this->dispatch('show-toast', message: 'ডেমো মোডে শ্রেণি মুছে ফেলা সম্ভব নয়।', type: 'danger');
            return;
        }
        $this->confirmingDeleteId = $id;
    }

    public function cancelDelete()
    {
        $this->confirmingDeleteId = null;
    }

    public function deleteCategoryConfirmed()
    {
        // Block action if logged in as Demo
        if (auth()->user()->hasRole('demo')) {
            $this->dispatch('show-toast', message: 'ডেমো মোডে শ্রেণি মুছে ফেলা সম্ভব নয়।', type: 'danger');
            $this->confirmingDeleteId = null;
            return;
        }

        if ($this->confirmingDeleteId) {
            $category = Category::find($this->confirmingDeleteId);
            if ($category) {
                \App\Models\ActivityLog::log('শ্রেণি ডিলিট', "{$category->name} শ্রেণিটিকে ডিলিট করা হয়েছে যার মূল্য ছিল {$category->rate} এবং শ্রেণির ধরন ছিল {$category->type}");
                $category->delete();
            }
            $this->dispatch('show-toast', message: 'শ্রেণি সফলভাবে মুছে ফেলা হয়েছে।', type: 'success');
            $this->confirmingDeleteId = null;
        }
        $this->resetForm();
    }

    public function cancelEdit()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public $perPage = 10;
    public $page = 1;

    public function setPerPage($size)
    {
        $this->perPage = $size;
        $this->page = 1;
    }

    public function setPage($page)
    {
        $this->page = max(1, (int)$page);
    }

    public function resetForm()
    {
        $this->reset(['name', 'rate', 'editingCategoryId']);
    }

    public function render()
    {
        $query = Category::orderBy('id', 'asc');
        
        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('type', 'like', '%' . $this->search . '%');
        }

        $allCategories = $query->get();
        $totalCount = $allCategories->count();

        if ($this->perPage === 'all' || $this->perPage == 0) {
            $categories = $allCategories;
            $totalPages = 1;
            $this->page = 1;
        } else {
            $perPageInt = (int)$this->perPage > 0 ? (int)$this->perPage : 10;
            $totalPages = max(1, (int)ceil($totalCount / $perPageInt));
            if ($this->page > $totalPages) {
                $this->page = $totalPages;
            }
            $offset = ($this->page - 1) * $perPageInt;
            $categories = $allCategories->slice($offset, $perPageInt);
        }

        return view('livewire.settings.category-rate', [
            'categories' => $categories,
            'totalCount' => $totalCount,
            'totalPages' => $totalPages,
            'currentPage' => $this->page,
        ]);
    }
}
