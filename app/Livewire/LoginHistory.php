<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\LoginLog;

class LoginHistory extends Component
{
    use WithPagination;

    public $perPage = 15;
    public $selectedLogs = [];
    public $selectAll = false;

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        if (LoginLog::count() === 0) {
            $mockLogs = [
                ['type' => 'Login', 'user_name' => 'Demo', 'device' => 'Windows', 'ip' => '103.81.29.28', 'time' => now()->subMinutes(5)],
                ['type' => 'Logout', 'user_name' => 'Demo', 'device' => 'Windows', 'ip' => '103.81.29.28', 'time' => now()->subMinutes(10)],
                ['type' => 'Login', 'user_name' => 'Demo', 'device' => 'Windows', 'ip' => '103.31.178.254', 'time' => now()->subHours(2)],
                ['type' => 'Login', 'user_name' => 'Demo', 'device' => 'Windows', 'ip' => '103.31.178.116', 'time' => now()->subHours(5)],
                ['type' => 'Logout', 'user_name' => 'Demo', 'device' => 'Windows', 'ip' => '103.31.178.116', 'time' => now()->subHours(6)],
                ['type' => 'Login', 'user_name' => 'Demo', 'device' => 'Windows', 'ip' => '103.31.178.116', 'time' => now()->subHours(7)],
                ['type' => 'Logout', 'user_name' => 'Demo', 'device' => 'Windows', 'ip' => '103.31.178.116', 'time' => now()->subHours(8)],
                ['type' => 'Login', 'user_name' => 'Demo', 'device' => 'Windows', 'ip' => '103.118.78.128', 'time' => now()->subDays(1)],
                ['type' => 'Login', 'user_name' => 'Demo', 'device' => 'Windows', 'ip' => '103.31.178.116', 'time' => now()->subDays(2)],
                ['type' => 'Login', 'user_name' => 'Demo', 'device' => 'Windows', 'ip' => '103.81.29.27', 'time' => now()->subDays(3)],
                ['type' => 'Login', 'user_name' => 'Demo', 'device' => 'Windows', 'ip' => '103.54.39.106', 'time' => now()->subDays(4)],
                ['type' => 'Logout', 'user_name' => 'Demo', 'device' => 'Unknown', 'ip' => '182.48.83.104', 'time' => now()->subDays(5)],
                ['type' => 'Login', 'user_name' => 'Demo', 'device' => 'Android', 'ip' => '59.152.5.125', 'time' => now()->subDays(6)],
                ['type' => 'Login', 'user_name' => 'Demo', 'device' => 'Android', 'ip' => '118.179.3.227', 'time' => now()->subDays(7)],
                ['type' => 'Login', 'user_name' => 'Demo', 'device' => 'Android', 'ip' => '118.179.3.227', 'time' => now()->subDays(8)],
                ['type' => 'Logout', 'user_name' => 'Demo', 'device' => 'Android', 'ip' => '118.179.3.227', 'time' => now()->subDays(9)],
                ['type' => 'Login', 'user_name' => 'Demo', 'device' => 'Windows', 'ip' => '103.81.29.28', 'time' => now()->subDays(10)],
                ['type' => 'Logout', 'user_name' => 'Demo', 'device' => 'Windows', 'ip' => '103.81.29.28', 'time' => now()->subDays(11)],
                ['type' => 'Login', 'user_name' => 'Demo', 'device' => 'MacOS', 'ip' => '103.31.178.200', 'time' => now()->subDays(12)],
                ['type' => 'Login', 'user_name' => 'Demo', 'device' => 'MacOS', 'ip' => '103.31.178.200', 'time' => now()->subDays(13)],
                ['type' => 'Logout', 'user_name' => 'Demo', 'device' => 'MacOS', 'ip' => '103.31.178.200', 'time' => now()->subDays(14)],
                ['type' => 'Login', 'user_name' => 'Demo', 'device' => 'Windows', 'ip' => '103.31.178.116', 'time' => now()->subDays(15)],
                ['type' => 'Logout', 'user_name' => 'Demo', 'device' => 'Windows', 'ip' => '103.31.178.116', 'time' => now()->subDays(16)],
                ['type' => 'Login', 'user_name' => 'Demo', 'device' => 'Windows', 'ip' => '103.81.29.28', 'time' => now()->subDays(17)],
                ['type' => 'Login', 'user_name' => 'Demo', 'device' => 'iOS', 'ip' => '118.179.3.100', 'time' => now()->subDays(18)],
                ['type' => 'Logout', 'user_name' => 'Demo', 'device' => 'iOS', 'ip' => '118.179.3.100', 'time' => now()->subDays(19)],
                ['type' => 'Login', 'user_name' => 'Demo', 'device' => 'Android', 'ip' => '118.179.3.50', 'time' => now()->subDays(20)],
                ['type' => 'Logout', 'user_name' => 'Demo', 'device' => 'Android', 'ip' => '118.179.3.50', 'time' => now()->subDays(21)],
                ['type' => 'Login', 'user_name' => 'Demo', 'device' => 'Windows', 'ip' => '103.81.29.28', 'time' => now()->subDays(22)],
                ['type' => 'Login', 'user_name' => 'Demo', 'device' => 'Windows', 'ip' => '103.81.29.28', 'time' => now()->subDays(23)],
            ];

            foreach ($mockLogs as $log) {
                LoginLog::create([
                    'type' => $log['type'],
                    'user_name' => $log['user_name'],
                    'device' => $log['device'],
                    'ip' => $log['ip'],
                    'time' => $log['time'],
                    'created_at' => $log['time'],
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
            $paginatedIds = LoginLog::orderBy('id', 'desc')
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
            LoginLog::whereIn('id', $this->selectedLogs)->delete();
            $this->selectedLogs = [];
            $this->selectAll = false;
            $this->dispatch('show-toast', message: 'নির্বাচিত লগ রেকর্ড সফলভাবে মুছে ফেলা হয়েছে।', type: 'success');
        }
    }

    public function render()
    {
        $logs = LoginLog::orderBy('id', 'desc')->paginate($this->perPage);

        return view('livewire.login-history', [
            'logs' => $logs,
            'totalCount' => LoginLog::count(),
        ])->layout('layouts.app');
    }
}
