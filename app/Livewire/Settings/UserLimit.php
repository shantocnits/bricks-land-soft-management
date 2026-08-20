<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\User;
use App\Models\UserLimit as UserLimitModel;

class UserLimit extends Component
{
    public $selectedUserId = '';
    public $limitType = 'discount_limit';
    public $amount = '';
    public $editingLimitId = null;

    // Modal and Search control
    public $showModal = false;
    public $search = '';

    protected $rules = [
        'selectedUserId' => 'required|exists:users,id',
        'limitType' => 'required|in:discount_limit,due_limit,delivery_limit,daily_invoice_limit,max_discount_limit,daily_payment_limit',
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

    public function editLimit($id)
    {
        $limit = UserLimitModel::find($id);
        if ($limit) {
            $this->editingLimitId = $limit->id;
            $this->selectedUserId = $limit->user_id;
            $this->limitType      = $limit->limit_type;
            $this->amount         = $limit->amount;
            $this->showModal      = true;
        }
    }

    public function setLimit()
    {
        // Block action if logged in as Demo
        if (auth()->user()->hasRole('demo')) {
            $this->dispatch('show-toast', message: 'ডেমো মোডে কোনো লিমিট পরিবর্তন করা সম্ভব নয়।', type: 'danger');
            $this->showModal = false;
            return;
        }

        $this->validate();

        if ($this->editingLimitId) {
            $limit = UserLimitModel::find($this->editingLimitId);
            if ($limit) {
                $limit->update([
                    'user_id'    => $this->selectedUserId,
                    'limit_type' => $this->limitType,
                    'amount'     => $this->amount,
                ]);
            }
            $msg = 'ইউজার লিমিট সফলভাবে আপডেট করা হয়েছে।';
        } else {
            UserLimitModel::updateOrCreate(
                [
                    'user_id'    => $this->selectedUserId,
                    'limit_type' => $this->limitType,
                ],
                [
                    'amount'     => $this->amount,
                ]
            );
            $msg = 'ইউজার লিমিট সফলভাবে সেট করা হয়েছে।';
        }

        $this->dispatch('show-toast', message: $msg, type: 'success');
        $this->showModal = false;
        $this->resetForm();
    }

    public $confirmDeleteId = null;

    public function confirmDelete($id)
    {
        $this->confirmDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        if ($this->confirmDeleteId) {
            $this->deleteLimit($this->confirmDeleteId);
            $this->confirmDeleteId = null;
        }
    }

    public function deleteLimit($id)
    {
        // Block action if logged in as Demo
        if (auth()->user()->hasRole('demo')) {
            $this->dispatch('show-toast', message: 'ডেমো মোডে লিমিট মুছে ফেলা সম্ভব নয়।', type: 'danger');
            return;
        }

        UserLimitModel::destroy($id);
        $this->dispatch('show-toast', message: 'ইউজার লিমিট সফলভাবে মুছে ফেলা হয়েছে।', type: 'success');
    }

    public function cancelEdit()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['selectedUserId', 'amount', 'editingLimitId']);
        $this->limitType = 'discount_limit';
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
