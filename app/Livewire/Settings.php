<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Settings extends Component
{
    public $activeTab = 'my_profile'; // Default tab

    protected $queryString = [
        'activeTab' => ['as' => 'tab', 'except' => 'my_profile']
    ];

    /**
     * Mount and validate tab access.
     */
    public function mount()
    {
        $allowedTabs = $this->getAllowedTabs();
        if (!in_array($this->activeTab, $allowedTabs)) {
            $this->activeTab = 'my_profile';
        }
    }

    /**
     * Handle tab changes.
     */
    public function setTab($tab)
    {
        $allowedTabs = $this->getAllowedTabs();
        if (in_array($tab, $allowedTabs)) {
            $this->activeTab = $tab;
        }
    }

    /**
     * Helper to get allowed tabs based on roles.
     */
    private function getAllowedTabs()
    {
        $user = Auth::user();
        if ($user && $user->isAdmin()) {
            return ['my_profile', 'profile', 'category', 'ledger', 'user', 'limit', 'printer', 'stock', 'sms'];
        }
        return ['my_profile'];
    }

    public function render()
    {
        return view('livewire.settings')
            ->layout('layouts.app');
    }
}
