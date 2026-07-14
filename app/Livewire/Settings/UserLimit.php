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

    // Modal and Search control
    public $showModal = false;
    public $search = '';

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

    public function openAddModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function setLimit()
    {
        // Block action if logged in as Demo
        if (auth()->user()->hasRole('demo')) {
            session()->flash('message', 'ডেমো মোডে কোনো লিমিট পরিবর্তন করা সম্ভব নয়।');
            $this->showModal = false;
            return;
        }

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
        $this->showModal = false;
        $this->resetForm();
    }

    public function deleteLimit($id)
    {
        // Block action if logged in as Demo
        if (auth()->user()->hasRole('demo')) {
            session()->flash('message', 'ডেমো মোডে লিমিট মুছে ফেলা সম্ভব নয়।');
            return;
        }

        UserLimitModel::destroy($id);
        session()->flash('message', 'ইউজার লিমিট সফলভাবে মুছে ফেলা হয়েছে।');
    }

    public function cancelEdit()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['selectedUserId', 'amount']);
        $this->limitType = 'daily_invoice_limit';
    }

    public function render()
    {
        $query = UserLimitModel::with('user');

        if (!empty($this->search)) {
            $query->whereHas('user', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            })->orWhere('limit_type', 'like', '%' . $this->search . '%');
        }

        return view('livewire.settings.user-limit', [
            'users' => User::orderBy('name', 'asc')->get(),
            'activeLimits' => $query->orderBy('id', 'desc')->get(),
        ]);
    }
}
