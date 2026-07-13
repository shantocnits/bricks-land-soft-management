<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\Setting;

class SmsSettings extends Component
{
    public $sms_new_invoice = false;
    public $sms_update_invoice = false;
    public $sms_delete_invoice = false;
    public $sms_new_delivery = false;
    public $sms_due_collection = false;

    public function mount()
    {
        // Load default values if not set
        $defaults = [
            'sms_new_invoice' => '1',
            'sms_update_invoice' => '0',
            'sms_delete_invoice' => '0',
            'sms_new_delivery' => '1',
            'sms_due_collection' => '1',
        ];

        foreach ($defaults as $key => $val) {
            if (!Setting::where('key', $key)->exists()) {
                Setting::set($key, $val);
            }
        }

        $this->sms_new_invoice = Setting::get('sms_new_invoice') === '1';
        $this->sms_update_invoice = Setting::get('sms_update_invoice') === '1';
        $this->sms_delete_invoice = Setting::get('sms_delete_invoice') === '1';
        $this->sms_new_delivery = Setting::get('sms_new_delivery') === '1';
        $this->sms_due_collection = Setting::get('sms_due_collection') === '1';
    }

    public function save()
    {
        Setting::set('sms_new_invoice', $this->sms_new_invoice ? '1' : '0');
        Setting::set('sms_update_invoice', $this->sms_update_invoice ? '1' : '0');
        Setting::set('sms_delete_invoice', $this->sms_delete_invoice ? '1' : '0');
        Setting::set('sms_new_delivery', $this->sms_new_delivery ? '1' : '0');
        Setting::set('sms_due_collection', $this->sms_due_collection ? '1' : '0');

        session()->flash('message', 'এসএমএস সেটিংস সফলভাবে আপডেট করা হয়েছে।');
    }

    public function render()
    {
        return view('livewire.settings.sms-settings');
    }
}
