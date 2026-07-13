<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\Setting;

class StockSettings extends Component
{
    public $stock_system;

    public function mount()
    {
        // Load default values if not set
        $defaults = [
            'stock_system' => 'category_stock', // total_stock, category_stock, category_khamal_stock
        ];

        foreach ($defaults as $key => $val) {
            if (!Setting::where('key', $key)->exists()) {
                Setting::set($key, $val);
            }
        }

        $this->stock_system = Setting::get('stock_system');
    }

    public function save()
    {
        $this->validate([
            'stock_system' => 'required|in:total_stock,category_stock,category_khamal_stock',
        ]);

        Setting::set('stock_system', $this->stock_system);

        session()->flash('message', 'স্টক সেটিংস সফলভাবে আপডেট করা হয়েছে।');
    }

    public function render()
    {
        return view('livewire.settings.stock-settings');
    }
}
