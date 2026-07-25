<?php

namespace App\Livewire;

use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class TaskManager extends Component
{
    use WithPagination;

    // Filter states
    public $filterPeriod = 'all'; // 'today', 'this_week', 'this_month', 'all'
    public $selectedDateFilter = null; // Specific date string (e.g. 2026-07-25)

    // Form modal state
    public $showTaskModal = false;
    public $editingTaskId = null;
    public $description = '';
    public $repeatPeriod = 'everyday';
    public $repeatDay = '';
    public $assignedTo = 'demo';
    public $dueDate = '';

    // List of users for assignment dropdown
    public $usersList = [];

    protected $paginationTheme = 'tailwind';

    protected function rules()
    {
        return [
            'description'  => 'required|string|min:2',
            'repeatPeriod' => 'required|string',
            'repeatDay'    => 'nullable|string',
            'assignedTo'   => 'nullable|string',
            'dueDate'      => 'required|date',
        ];
    }

    protected $messages = [
        'description.required'  => 'কাজের বিবরণ প্রদান করুন।',
        'description.min'       => 'কাজের বিবরণ অন্তত ২ অক্ষরের হতে হবে।',
        'repeatPeriod.required' => 'পুনরাবৃত্তি পিরিয়ড নির্বাচন করুন।',
        'dueDate.required'      => 'তারিখ নির্বাচন করুন।',
        'dueDate.date'          => 'সঠিক তারিখ প্রদান করুন।',
    ];

    public function mount()
    {
        $this->dueDate = Carbon::today()->format('Y-m-d');
        $this->usersList = User::pluck('name')->filter()->values()->toArray();
        if (empty($this->usersList)) {
            $this->usersList = ['demo', 'admin', 'shanto', 'niloy'];
        }
        $this->assignedTo = $this->usersList[0] ?? 'demo';
    }

    public function setFilterPeriod($period)
    {
        $this->filterPeriod = $period;
        $this->selectedDateFilter = null;
        $this->resetPage();
    }

    public function selectDateFilter($date)
    {
        $this->selectedDateFilter = $date;
        $this->filterPeriod = 'custom';
        $this->resetPage();
    }

    public function openTaskModal($taskId = null)
    {
        $this->resetValidation();
        if ($taskId) {
            $task = Task::findOrFail($taskId);
            $this->editingTaskId = $task->id;
            $this->description = $task->description;
            $this->repeatPeriod = $task->repeat_period;
            $this->repeatDay = $task->repeat_day ?: '';
            $this->assignedTo = $task->assigned_to ?: ($this->usersList[0] ?? 'demo');
            $this->dueDate = $task->due_date ? Carbon::parse($task->due_date)->format('Y-m-d') : Carbon::today()->format('Y-m-d');
        } else {
            $this->editingTaskId = null;
            $this->description = '';
            $this->repeatPeriod = 'everyday';
            $this->repeatDay = '';
            $this->assignedTo = $this->usersList[0] ?? 'demo';
            $this->dueDate = Carbon::today()->format('Y-m-d');
        }
        $this->showTaskModal = true;
    }

    public function saveTask()
    {
        $this->validate();

        Task::updateOrCreate(
            ['id' => $this->editingTaskId],
            [
                'description'   => $this->description,
                'repeat_period' => $this->repeatPeriod,
                'repeat_day'    => $this->repeatDay,
                'assigned_to'   => $this->assignedTo,
                'due_date'      => $this->dueDate,
            ]
        );

        $this->showTaskModal = false;
        session()->flash('message', $this->editingTaskId ? 'কাজ সফলভাবে আপডেট করা হয়েছে!' : 'নতুন কাজ সফলভাবে যুক্ত করা হয়েছে!');
        $this->reset(['editingTaskId', 'description', 'repeatDay']);
    }

    public function toggleTaskStatus($taskId)
    {
        $task = Task::findOrFail($taskId);
        $task->is_completed = !$task->is_completed;
        $task->completed_at = $task->is_completed ? now() : null;
        $task->save();

        session()->flash('message', $task->is_completed ? 'কাজটি সম্পূর্ণ হিসেবে মার্ক করা হয়েছে!' : 'কাজটি অসম্পূর্ণ তালিকায় ফেরত আনা হয়েছে!');
    }

    public function updateInline($taskId, $field, $value)
    {
        $task = Task::findOrFail($taskId);
        if (in_array($field, ['repeat_period', 'repeat_day', 'assigned_to', 'due_date'])) {
            $task->update([$field => $value]);
            session()->flash('message', 'কাজের তথ্য আপডেট করা হয়েছে!');
        }
    }

    public function deleteTask($taskId)
    {
        $task = Task::findOrFail($taskId);
        $task->delete();
        session()->flash('message', 'কাজটি মুছে ফেলা হয়েছে!');
    }

    // Per Page pagination states for both tables
    public $perPageIncomplete = 10;
    public $perPageCompleted = 10;

    public function selectPerPageIncomplete($size)
    {
        $this->perPageIncomplete = $size;
        $this->resetPage('inc_page');
    }

    public function selectPerPageCompleted($size)
    {
        $this->perPageCompleted = $size;
        $this->resetPage('comp_page');
    }

    public function render()
    {
        $today = Carbon::today();

        // Build base query according to period filter
        $applyFilter = function ($query) use ($today) {
            if ($this->selectedDateFilter) {
                $query->whereDate('due_date', $this->selectedDateFilter);
            } elseif ($this->filterPeriod === 'today') {
                $query->whereDate('due_date', $today);
            } elseif ($this->filterPeriod === 'this_week') {
                $query->whereBetween('due_date', [$today->copy()->startOfWeek(), $today->copy()->endOfWeek()]);
            } elseif ($this->filterPeriod === 'this_month') {
                $query->whereBetween('due_date', [$today->copy()->startOfMonth(), $today->copy()->endOfMonth()]);
            }
            return $query;
        };

        $incompleteTasksQuery = Task::where('is_completed', false);
        $incompleteTasksQuery = $applyFilter($incompleteTasksQuery);
        $incompleteTasks = $incompleteTasksQuery->orderBy('due_date', 'asc')->orderBy('id', 'desc')->paginate($this->perPageIncomplete, ['*'], 'inc_page');

        $completedTasksQuery = Task::where('is_completed', true);
        $completedTasksQuery = $applyFilter($completedTasksQuery);
        $completedTasks = $completedTasksQuery->orderBy('completed_at', 'desc')->orderBy('id', 'desc')->paginate($this->perPageCompleted, ['*'], 'comp_page');

        // Generate date numbers strip (e.g. current week / month days around today)
        $dateStrip = [];
        $startDate = $today->copy()->subDays(3);
        for ($i = 0; $i < 7; $i++) {
            $d = $startDate->copy()->addDays($i);
            $dateStrip[] = [
                'day_num'   => $d->format('j'),
                'date_str'  => $d->format('Y-m-d'),
                'is_today'  => $d->isToday(),
            ];
        }

        return view('livewire.task-manager', [
            'incompleteTasks' => $incompleteTasks,
            'completedTasks'  => $completedTasks,
            'dateStrip'       => $dateStrip,
        ])->layout('layouts.app');
    }
}
