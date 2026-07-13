<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\Setting;

class PrinterSettings extends Component
{
    public $printer_type;
    public $print_copies;
    public $thermal_padding_top;
    public $thermal_padding_bottom;

    public function mount()
    {
        // Load default values if not set
        $defaults = [
            'printer_type' => 'thermal', // thermal, desktop
            'print_copies' => '2', // Customer + Office Copy
            'thermal_padding_top' => '10', // mm
            'thermal_padding_bottom' => '15', // mm
        ];

        foreach ($defaults as $key => $val) {
            if (!Setting::where('key', $key)->exists()) {
                Setting::set($key, $val);
            }
        }

        $this->printer_type = Setting::get('printer_type');
        $this->print_copies = Setting::get('print_copies');
        $this->thermal_padding_top = Setting::get('thermal_padding_top');
        $this->thermal_padding_bottom = Setting::get('thermal_padding_bottom');
    }

    public function save()
    {
        $this->validate([
            'printer_type' => 'required|in:thermal,desktop',
            'print_copies' => 'required|integer|min:1|max:5',
            'thermal_padding_top' => 'required|integer|min:0',
            'thermal_padding_bottom' => 'required|integer|min:0',
        ], [
            'printer_type.required' => 'প্রিন্টার টাইপ নির্বাচন করুন।',
            'print_copies.required' => 'প্রিন্ট কপির সংখ্যা দিন।',
            'thermal_padding_top.required' => 'টপ প্যাডিং দিন।',
            'thermal_padding_bottom.required' => 'বটম প্যাডিং দিন।',
        ]);

        Setting::set('printer_type', $this->printer_type);
        Setting::set('print_copies', $this->print_copies);
        Setting::set('thermal_padding_top', $this->thermal_padding_top);
        Setting::set('thermal_padding_bottom', $this->thermal_padding_bottom);

        session()->flash('message', 'প্রিন্টার সেটিংস সফলভাবে আপডেট করা হয়েছে।');
    }

    public function render()
    {
        return view('livewire.settings.printer-settings');
    }
}
