<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\Setting;

class ProfileInfo extends Component
{
    public $company_name_bn;
    public $company_name_en;
    public $address;
    public $owner_name;
    public $owner_phone;
    public $invoice_phones;

    // Billing Info (Read-only for normal settings)
    public $client_id;
    public $monthly_fee;
    public $sms_rate;
    public $next_payment_date;

    public function mount()
    {
        // Set default settings if not exists
        $defaults = [
            'company_name_bn' => 'ডেমো ব্রিকস',
            'company_name_en' => 'DEMO',
            'address' => 'হিলালীপাড়া,কাটাবাড়ি,গোবিন্দগঞ্জ',
            'owner_name' => 'মোঃ মানিক মিয়া',
            'owner_phone' => '01918908070',
            'invoice_phones' => '01901349901,01901349906',
            'client_id' => '3',
            'monthly_fee' => '2000.00',
            'sms_rate' => '0.35',
            'next_payment_date' => '05 Jan, 2090',
        ];

        foreach ($defaults as $key => $val) {
            if (!Setting::where('key', $key)->exists()) {
                Setting::set($key, $val);
            }
        }

        $this->company_name_bn = Setting::get('company_name_bn');
        $this->company_name_en = Setting::get('company_name_en');
        $this->address = Setting::get('address');
        $this->owner_name = Setting::get('owner_name');
        $this->owner_phone = Setting::get('owner_phone');
        $this->invoice_phones = Setting::get('invoice_phones');

        $this->client_id = Setting::get('client_id');
        $this->monthly_fee = Setting::get('monthly_fee');
        $this->sms_rate = Setting::get('sms_rate');
        $this->next_payment_date = Setting::get('next_payment_date');
    }

    public function save()
    {
        $this->validate([
            'company_name_bn' => 'required|string|max:255',
            'company_name_en' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'owner_name' => 'required|string|max:255',
            'owner_phone' => 'required|string|max:20',
            'invoice_phones' => 'required|string|max:255',
        ], [
            'company_name_bn.required' => 'প্রতিষ্ঠানের নাম (বাংলা) আবশ্যক।',
            'company_name_en.required' => 'প্রতিষ্ঠানের নাম (ইংরেজি) আবশ্যক।',
            'address.required' => 'ঠিকানা আবশ্যক।',
            'owner_name.required' => 'মালিকের নাম আবশ্যক।',
            'owner_phone.required' => 'ব্যক্তিগত যোগাযোগের নম্বর আবশ্যক।',
            'invoice_phones.required' => 'ইনভয়েস ফোন নম্বর আবশ্যক।',
        ]);

        Setting::set('company_name_bn', $this->company_name_bn);
        Setting::set('company_name_en', $this->company_name_en);
        Setting::set('address', $this->address);
        Setting::set('owner_name', $this->owner_name);
        Setting::set('owner_phone', $this->owner_phone);
        Setting::set('invoice_phones', $this->invoice_phones);

        session()->flash('message', 'প্রতিষ্ঠানের তথ্য সফলভাবে আপডেট করা হয়েছে।');
    }

    public function render()
    {
        return view('livewire.settings.profile-info');
    }
}
