<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\Category;

class CategoryRate extends Component
{
    public $name = '';
    public $type = 'ইট';
    public $rate = '';

    public $editingCategoryId = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'type' => 'required|in:ইট,আধলা,অন্যান্য',
        'rate' => 'required|numeric|min:0',
    ];

    protected $messages = [
        'name.required' => 'শ্রেণির নাম আবশ্যক।',
        'type.required' => 'শ্রেণির ধরণ নির্বাচন করুন।',
        'type.in' => 'শ্রেণির ধরণ সঠিক নয়।',
        'rate.required' => 'রেট আবশ্যক।',
        'rate.numeric' => 'রেট একটি সংখ্যা হতে হবে।',
    ];

    public function mount()
    {
        // Preseed defaults if empty
        if (Category::count() === 0) {
            Category::create(['name' => '১ম শ্রেণি', 'type' => 'ইট', 'rate' => 9.00]);
            Category::create(['name' => '২য় শ্রেণি', 'type' => 'ইট', 'rate' => 8.00]);
            Category::create(['name' => '৩য় শ্রেণি', 'type' => 'ইট', 'rate' => 7.00]);
            Category::create(['name' => 'আধলা', 'type' => 'আধলা', 'rate' => 5.00]);
        }
    }

    public function save()
    {
        $this->validate();

        if ($this->editingCategoryId) {
            $category = Category::find($this->editingCategoryId);
            if ($category) {
                $category->update([
                    'name' => $this->name,
                    'type' => $this->type,
                    'rate' => $this->rate,
                ]);
                session()->flash('message', 'শ্রেণি সফলভাবে আপডেট করা হয়েছে।');
            }
        } else {
            Category::create([
                'name' => $this->name,
                'type' => $this->type,
                'rate' => $this->rate,
            ]);
            session()->flash('message', 'নতুন শ্রেণি সফলভাবে যুক্ত করা হয়েছে।');
        }

        $this->resetForm();
    }

    public function editCategory($id)
    {
        $category = Category::find($id);
        if ($category) {
            $this->editingCategoryId = $category->id;
            $this->name = $category->name;
            $this->type = $category->type;
            $this->rate = $category->rate;
        }
    }

    public function deleteCategory($id)
    {
        Category::destroy($id);
        session()->flash('message', 'শ্রেণি সফলভাবে মুছে ফেলা হয়েছে।');
        $this->resetForm();
    }

    public function cancelEdit()
    {
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['name', 'type', 'rate', 'editingCategoryId']);
    }

    public function render()
    {
        return view('livewire.settings.category-rate', [
            'categories' => Category::orderBy('id', 'asc')->get()
        ]);
    }
}
