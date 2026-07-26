<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SmsLog;
use App\Models\SmsRecharge;
use App\Models\Setting;

class SmsPage extends Component
{
    use WithPagination;

    public $search = '';
    public int $perPage = 10;

    public $showRechargeModal = false;
    public $modalTab = 'payment'; // 'payment', 'history'

    public $remainingSms = '৩৪';
    public $sentSms = '৯৯';

    // Payment Numbers
    public $bkashNumber = '01797-926335';
    public $nagadNumber = '01797-926335';
    public $rocketNumber = '01797-926335';
    public $editingNumbers = false;

    // Payment Form
    public $paymentMethod = 'বিকাশ';
    public $senderPhone = '01797-926335';
    public $trxId = '655666';
    public $amount = 500;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function mount()
    {
        $this->remainingSms = Setting::get('sms_remaining_count', '৩৪');
        $this->sentSms = Setting::get('sms_sent_count', '৯৯');

        $this->bkashNumber = Setting::get('sms_bkash_number', '01797-926335');
        $this->nagadNumber = Setting::get('sms_nagad_number', '01797-926335');
        $this->rocketNumber = Setting::get('sms_rocket_number', '01797-926335');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function openRechargeModal()
    {
        $this->showRechargeModal = true;
        $this->modalTab = 'payment';
    }

    public function closeRechargeModal()
    {
        $this->showRechargeModal = false;
        $this->editingNumbers = false;
    }

    public function savePaymentNumbers()
    {
        Setting::set('sms_bkash_number', $this->bkashNumber);
        Setting::set('sms_nagad_number', $this->nagadNumber);
        Setting::set('sms_rocket_number', $this->rocketNumber);

        $this->editingNumbers = false;
        $this->dispatch('show-toast', ['message' => 'পেমেন্ট নম্বরসমূহ আপডেট হয়েছে!']);
    }

    public function confirmPayment()
    {
        $this->validate([
            'senderPhone' => 'required|string',
            'trxId' => 'required|string',
            'amount' => 'required|numeric|min:1',
        ], [
            'senderPhone.required' => 'প্রেরকের নম্বর আবশ্যক।',
            'trxId.required' => 'Transaction ID আবশ্যক।',
            'amount.required' => 'টাকার পরিমাণ আবশ্যক।',
        ]);

        $smsRate = floatval(Setting::get('sms_rate', '0.52'));
        $smsCount = $smsRate > 0 ? intval(floor($this->amount / $smsRate)) : intval($this->amount);

        SmsRecharge::create([
            'payment_method' => $this->paymentMethod,
            'sender_phone' => $this->senderPhone,
            'trx_id' => $this->trxId,
            'amount' => $this->amount,
            'sms_count' => $smsCount,
            'status' => 'Pending',
        ]);

        $this->dispatch('show-toast', ['message' => 'পেমেন্ট রিকোয়েস্ট সফলভাবে জমা হয়েছে!']);
        $this->modalTab = 'history';
    }

    public function render()
    {
        $logQuery = SmsLog::query();

        if (!empty($this->search)) {
            $logQuery->where(function($q) {
                $q->where('phone', 'like', '%' . $this->search . '%')
                  ->orWhere('message', 'like', '%' . $this->search . '%');
            });
        }

        $logs = $logQuery->orderBy('id', 'desc')->paginate($this->perPage);

        $recharges = SmsRecharge::orderBy('id', 'desc')->get();

        return view('livewire.sms-page', [
            'logs' => $logs,
            'recharges' => $recharges,
        ]);
    }
}
