<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ActivityLog;

class UpdateHistory extends Component
{
    use WithPagination;

    public $perPage = 15;
    public $selectedLogs = [];
    public $selectAll = false;

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        if (ActivityLog::count() === 0) {
            $mockLogs = [
                ['field' => 'শ্রেণি ডিলিট', 'description' => '1 no 2 শ্রেণিটিকে ডিলিট করা হয়েছে যার মূল্য ছিল 8.5 এবং শ্রেণির ধরন ছিল ইট', 'created_at' => now()->subDays(30)],
                ['field' => 'শ্রেণি ডিলিট', 'description' => '1 no 3 শ্রেণিটিকে ডিলিট করা হয়েছে যার মূল্য ছিল 8.8 এবং শ্রেণির ধরন ছিল ইট', 'created_at' => now()->subDays(29)],
                ['field' => 'শ্রেণি ডিলিট', 'description' => '1 no 4 শ্রেণিটিকে ডিলিট করা হয়েছে যার মূল্য ছিল 9 এবং শ্রেণির ধরন ছিল ইট', 'created_at' => now()->subDays(28)],
                ['field' => 'আনলোড আপডেট', 'description' => 'শ্রেণি ১ নং। পূর্বের আনলোডের পরিমাণঃ ১০০। নতুন আনলোডের পরিমাণঃ ১,০০০', 'created_at' => now()->subDays(27)],
                ['field' => 'পেমেন্ট আপডেট', 'description' => 'পেমেন্ট আপডেট (আইডি: 6) • রেট: 0 -> 0', 'created_at' => now()->subDays(26)],
                ['field' => 'আনলোড আপডেট', 'description' => 'শ্রেণি ১ নং। পূর্বের আনলোডের পরিমাণঃ ২,০০০। নতুন আনলোডের পরিমাণঃ ১,০০০', 'created_at' => now()->subDays(25)],
                ['field' => 'আনলোড আপডেট', 'description' => 'শ্রেণি পিকেট। পূর্বের আনলোডের পরিমাণঃ ৪০০। নতুন আনলোডের পরিমাণঃ ২০০', 'created_at' => now()->subDays(24)],
                ['field' => 'শ্রেণি ডিলিট', 'description' => 'শ্রেণি ২ নং (ক) শ্রেণিটিকে ডিলিট করা হয়েছে যার মূল্য ছিল 8.5 এবং শ্রেণির ধরন ছিল ইট', 'created_at' => now()->subDays(23)],
                ['field' => 'শ্রেণি ডিলিট', 'description' => 'শ্রেণি ৩ নং ছালট শ্রেণিটিকে ডিলিট করা হয়েছে যার মূল্য ছিল 4.5 এবং শ্রেণির ধরন ছিল ইট', 'created_at' => now()->subDays(22)],
                ['field' => 'শ্রেণি ডিলিট', 'description' => 'শ্রেণি খোয়া শ্রেণিটিকে ডিলিট করা হয়েছে যার মূল্য ছিল 120.00 এবং শ্রেণির ধরন ছিল অন্যান্য', 'created_at' => now()->subDays(21)],
                ['field' => 'আনলোড আপডেট', 'description' => 'শ্রেণি পিকটি। পূর্বের আনলোডের পরিমাণঃ ৫০০। নতুন আনলোডের পরিমাণঃ ১৫০০', 'created_at' => now()->subDays(20)],
                ['field' => 'পেমেন্ট আপডেট', 'description' => 'পেমেন্ট আপডেট (আইডি: 12) • রেট: 5000 -> 8000', 'created_at' => now()->subDays(19)],
                ['field' => 'আনলোড আপডেট', 'description' => 'শ্রেণি ১ নং আদলা। পূর্বের আনলোডের পরিমাণঃ ৩০০। নতুন আনলোডের পরিমাণঃ ৬০০', 'created_at' => now()->subDays(18)],
                ['field' => 'শ্রেণি ডিলিট', 'description' => 'শ্রেণি ৩ নং আদলা শ্রেণিটিকে ডিলিট করা হয়েছে যার মূল্য ছিল 1.50 এবং শ্রেণির ধরন ছিল আধলা', 'created_at' => now()->subDays(17)],
                ['field' => 'আনলোড আপডেট', 'description' => 'শ্রেণি রাবিশ। পূর্বের আনলোডের পরিমাণঃ ২০। নতুন আনলোডের পরিমাণঃ ৫০', 'created_at' => now()->subDays(16)],
                ['field' => 'পেমেন্ট আপডেট', 'description' => 'পেমেন্ট আপডেট (আইডি: 20) • রেট: 2000 -> 0', 'created_at' => now()->subDays(15)],
                ['field' => 'আনলোড আপডেট', 'description' => 'শ্রেণি ১ নং। পূর্বের আনলোডের পরিমাণঃ ৪,০০০। নতুন আনলোডের পরিমাণঃ ৮,০০০', 'created_at' => now()->subDays(14)],
                ['field' => 'শ্রেণি ডিলিট', 'description' => 'শ্রেণি ২ নং (খ) শ্রেণিটিকে ডিলিট করা হয়েছে যার মূল্য ছিল 7.50 এবং শ্রেণির ধরন ছিল ইট', 'created_at' => now()->subDays(13)],
                ['field' => 'শ্রেণি ডিলিট', 'description' => 'শ্রেণি ৩ নং গরিয়া শ্রেণিটিকে ডিলিট করা হয়েছে যার মূল্য ছিল 6.00 এবং শ্রেণির ধরন ছিল ইট', 'created_at' => now()->subDays(12)],
                ['field' => 'আনলোড আপডেট', 'description' => 'শ্রেণি এলোট। পূর্বের আনলোডের পরিমাণঃ ১০০। নতুন আনলোডের পরিমাণঃ ৫০', 'created_at' => now()->subDays(11)],
                ['field' => 'পেমেন্ট আপডেট', 'description' => 'পেমেন্ট আপডেট (আইডি: 15) • রেট: 10000 -> 9000', 'created_at' => now()->subDays(10)],
                ['field' => 'শ্রেণি ডিলিট', 'description' => 'শ্রেণি ১ নং আদলা শ্রেণিটিকে ডিলিট করা হয়েছে যার মূল্য ছিল 4.50 এবং শ্রেণির ধরন ছিল আধলা', 'created_at' => now()->subDays(9)],
                ['field' => 'আনলোড আপডেট', 'description' => 'শ্রেণি পিকটি। পূর্বের আনলোডের পরিমাণঃ ৮০০। নতুন আনলোডের পরিমাণঃ ১,০০০', 'created_at' => now()->subDays(8)],
                ['field' => 'আনলোড আপডেট', 'description' => 'শ্রেণি খোয়া। পূর্বের আনলোডের পরিমাণঃ ১০। নতুন আনলোডের পরিমাণঃ ১৫', 'created_at' => now()->subDays(7)],
                ['field' => 'পেমেন্ট আপডেট', 'description' => 'পেমেন্ট আপডেট (আইডি: 3) • রেট: 0 -> 1500', 'created_at' => now()->subDays(6)],
                ['field' => 'আনলোড আপডেট', 'description' => 'শ্রেণি ৩ নং ছালট। পূর্বের আনলোডের পরিমাণঃ ৩০০। নতুন আনলোডের পরিমাণঃ ৪০০', 'created_at' => now()->subDays(5)],
                ['field' => 'আনলোড আপডেট', 'description' => 'শ্রেণি ৩ নং গরিয়া। পূর্বের আনলোডের পরিমাণঃ ২০০। নতুন আনলোডের পরিমাণঃ ৩৫০', 'created_at' => now()->subDays(4)],
                ['field' => 'শ্রেণি ডিলিট', 'description' => 'শ্রেণি এলোট শ্রেণিটিকে ডিলিট করা হয়েছে যার মূল্য ছিল 3.00 এবং শ্রেণির ধরন ছিল ইট', 'created_at' => now()->subDays(3)],
                ['field' => 'শ্রেণি ডিলিট', 'description' => 'শ্রেণি রাবিশ শ্রেণিটিকে ডিলিট করা হয়েছে যার মূল্য ছিল 500.00 এবং শ্রেণির ধরন ছিল অন্যান্য', 'created_at' => now()->subDays(2)],
                ['field' => 'আনলোড আপডেট', 'description' => 'শ্রেণি ১ নং। পূর্বের আনলোডের পরিমাণঃ ৫০০। নতুন আনলোডের পরিমাণঃ ১,৫০০', 'created_at' => now()->subDays(1)],
            ];

            foreach ($mockLogs as $log) {
                ActivityLog::create([
                    'field' => $log['field'],
                    'description' => $log['description'],
                    'user_name' => 'Demo',
                    'status' => false,
                    'created_at' => $log['created_at'],
                ]);
            }
        }
    }

    public function updatedPerPage()
    {
        $this->resetPage();
        $this->selectedLogs = [];
        $this->selectAll = false;
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $paginatedIds = ActivityLog::orderBy('id', 'desc')
                ->paginate($this->perPage)
                ->pluck('id')
                ->map(fn($id) => (string)$id)
                ->toArray();
            $this->selectedLogs = $paginatedIds;
        } else {
            $this->selectedLogs = [];
        }
    }

    public function deleteSelected()
    {
        if (count($this->selectedLogs) > 0) {
            ActivityLog::whereIn('id', $this->selectedLogs)->delete();
            $this->selectedLogs = [];
            $this->selectAll = false;
            $this->dispatch('show-toast', message: 'নির্বাচিত ইতিহাস সফলভাবে মুছে ফেলা হয়েছে।', type: 'success');
        }
    }

    public function checkGhornala()
    {
        session()->flash('message', 'ঘরনালা চেক করা হয়েছে।');
    }

    public function render()
    {
        $logs = ActivityLog::orderBy('id', 'desc')->paginate($this->perPage);

        return view('livewire.update-history', [
            'logs' => $logs,
            'totalCount' => ActivityLog::count(),
        ])->layout('layouts.app');
    }
}
