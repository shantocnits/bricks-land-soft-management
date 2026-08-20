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
    public $showDeleteConfirmModal = false;

    protected $paginationTheme = 'tailwind';

    public function updatedPerPage()
    {
        $this->resetPage();
        $this->selectedLogs = [];
        $this->selectAll = false;
    }

    public function updatedSelectAll($value)
    {
        if (auth()->check() && auth()->user()->role !== 'admin') {
            $this->selectAll = false;
            $this->selectedLogs = [];
            return;
        }

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

    public function confirmDeleteSelected()
    {
        if (auth()->check() && auth()->user()->role !== 'admin') {
            $this->dispatch('show-toast', message: 'সাধারণ ইউজার অথবা স্টাফ আপডেট হিস্ট্রি রেকর্ড মুছে ফেলতে পারবে না!', type: 'danger');
            return;
        }

        if (count($this->selectedLogs) > 0) {
            $this->showDeleteConfirmModal = true;
        } else {
            $this->dispatch('show-toast', message: 'অনুগ্রহ করে অন্তত একটি হিস্ট্রি রেকর্ড সিলেক্ট করুন।', type: 'danger');
        }
    }

    public function deleteSelected()
    {
        if (auth()->check() && auth()->user()->role !== 'admin') {
            $this->dispatch('show-toast', message: 'সাধারণ ইউজার অথবা স্টাফ আপডেট হিস্ট্রি রেকর্ড মুছে ফেলতে পারবে না!', type: 'danger');
            $this->showDeleteConfirmModal = false;
            return;
        }

        if (count($this->selectedLogs) > 0) {
            ActivityLog::whereIn('id', $this->selectedLogs)->delete();
            $this->selectedLogs = [];
            $this->selectAll = false;
            $this->showDeleteConfirmModal = false;
            $this->dispatch('show-toast', message: 'নির্বাচিত ইতিহাস সফলভাবে মুছে ফেলা হয়েছে।', type: 'success');
        }
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
