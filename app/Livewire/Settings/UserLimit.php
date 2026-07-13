<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\User;
use App\Models\UserLimit as UserLimitModel;

class UserLimit extends Component
{
    public $selectedUserId = '';
    public $limitType = 'daily_invoice_limit';
    public $amount = '';

    protected $rules = [
        'selectedUserId' => 'required|exists:users,id',
        'limitType' => 'required|in:daily_invoice_limit,max_discount_limit,daily_payment_limit',
        'amount' => 'required|numeric|min:0',
    ];

    protected $messages = [
        'selectedUserId.required' => 'ব্যবহারকারী নির্বাচন করুন।',
        'selectedUserId.exists' => 'নির্বাচিত ব্যবহারকারী সঠিক নয়।',
        'limitType.required' => 'লিমিটের ধরণ নির্বাচন করুন।',
        'amount.required' => 'পরিমাণ আবশ্যক।',
        'amount.numeric' => 'পরিমাণ অবশ্যই একটি সংখ্যা হতে হবে।',
    ];

    public function setLimit()
    {
        $this->validate();

        UserLimitModel::updateOrCreate(
            [
                'user_id' => $this->selectedUserId,
                'limit_type' => $this->limitType,
            ],
            [
                'amount' => $this->amount,
            ]
        );

        session()->flash('message', 'ইউজার লিমিট সফলভাবে সেট করা হয়েছে।');
        $this->reset(['selectedUserId', 'amount']);
    }

    public function deleteLimit($id)
    {
        UserLimitModel::destroy($id);
        session()->flash('message', 'ইউজার লিমিট সফলভাবে মুছে ফেলা হয়েছে।');
    }

    public function render()
    {
        return view('livewire.settings.user-limit', [
            'users' => User::orderBy('name', 'asc')->get(),
            'activeLimits' => UserLimitModel::with('user')->orderBy('id', 'desc')->get(),
        ]);
    }
}
