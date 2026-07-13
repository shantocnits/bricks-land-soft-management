<?php

namespace App\Livewire;

use Livewire\Component;

class Settings extends Component
{
    public $activeTab = 'profile'; // Default tab

    protected $queryString = [
        'activeTab' => ['as' => 'tab', 'except' => 'profile']
    ];

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.settings')
            ->layout('layouts.app');
    }
}
