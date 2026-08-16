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
    public $assignedTo = 'admin';
    public $dueDate = '';

    // Custom Delete Confirmation Modal state (Rule 4)
    public $confirmDeleteTaskId = null;

    // Today Task Reminder Modal state (Rule 3)
    public $showReminderModal = false;
    public $hasDismissedReminder = false;

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

    public function checkIsAdmin(): bool
    {
        $user = auth()->user();
        if (!$user) {
            return true;
        }
        return ($user->role === 'admin' || strtolower($user->role ?? '') === 'admin' || $user->hasRole('admin') || strtolower($user->name ?? '') === 'admin');
    }

    public function canEditTask($task): bool
    {
        if ($this->checkIsAdmin()) {
            return true;
        }

        $user = auth()->user();
        $userName = $user ? strtolower(trim($user->name)) : '';

        // Staff can ONLY edit their own assigned incomplete task
        $isAssignedToMe = ($task && $task->assigned_to && strtolower(trim($task->assigned_to)) === $userName);
        $isCreatedByMe = ($task && property_exists($task, 'created_by') && $task->created_by && $user && $task->created_by == $user->id);

        return (!$task->is_completed) && ($isAssignedToMe || $isCreatedByMe);
    }

    public function canDeleteTask(): bool
    {
        return $this->checkIsAdmin();
    }

    public function mount()
    {
        $user = auth()->user();
        $this->dueDate = Carbon::today()->format('Y-m-d');
        $this->usersList = User::pluck('name')->filter()->values()->toArray();
        if (empty($this->usersList)) {
            $this->usersList = ['admin', 'shanto', 'niloy'];
        }

        if ($user && !$this->checkIsAdmin()) {
            $this->assignedTo = $user->name;
        } else {
            $this->assignedTo = $user ? $user->name : ($this->usersList[0] ?? 'admin');
        }

        // Auto trigger today task reminder check on page mount/reload
        $this->checkTodayReminder();
    }

    public function checkTodayReminder()
    {
        if ($this->hasDismissedReminder) {
            return;
        }

        $today = Carbon::today();
        $user = auth()->user();

        $query = Task::where('is_completed', false)
            ->whereDate('due_date', $today);

        if ($user && !$this->checkIsAdmin()) {
            $query->where('assigned_to', $user->name);
        }

        $count = $query->count();
        if ($count > 0) {
            $this->showReminderModal = true;
        }
    }

    public function dismissReminderModal()
    {
        $this->showReminderModal = false;
        $this->hasDismissedReminder = true;
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
        $user = auth()->user();
        $isAdmin = $this->checkIsAdmin();

        if ($taskId) {
            $task = Task::findOrFail($taskId);
            if (!$this->canEditTask($task)) {
                $msg = 'দুঃখিত! আপনার এই টাস্ক এডিট করার অনুমতি নেই।';
                session()->flash('message', $msg);
                $this->dispatch('show-toast', message: $msg, type: 'error');
                return;
            }
            $this->editingTaskId = $task->id;
            $this->description = $task->description;
            $this->repeatPeriod = $task->repeat_period;
            $this->repeatDay = $task->repeat_day ?: '';
            $this->assignedTo = $task->assigned_to ?: ($user ? $user->name : 'admin');
            $this->dueDate = $task->due_date ? Carbon::parse($task->due_date)->format('Y-m-d') : Carbon::today()->format('Y-m-d');
        } else {
            $this->editingTaskId = null;
            $this->description = '';
            $this->repeatPeriod = 'everyday';
            $this->repeatDay = '';
            if ($user && !$isAdmin) {
                $this->assignedTo = $user->name;
            } else {
                $this->assignedTo = $user ? $user->name : ($this->usersList[0] ?? 'admin');
            }
            $this->dueDate = Carbon::today()->format('Y-m-d');
        }
        $this->showTaskModal = true;
    }

    public function saveTask()
    {
        $user = auth()->user();
        $isAdmin = $this->checkIsAdmin();

        // Non-admin regular user CANNOT assign tasks to others - lock to their own name
        if ($user && !$isAdmin) {
            $this->assignedTo = $user->name;
        }

        $this->validate();

        if ($this->editingTaskId) {
            $existingTask = Task::find($this->editingTaskId);
            if ($existingTask && !$this->canEditTask($existingTask)) {
                session()->flash('message', 'আপনার এই টাস্ক এডিট করার অনুমতি নেই।');
                return;
            }
        }

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
        $msg = $this->editingTaskId ? 'কাজ সফলভাবে আপডেট করা হয়েছে!' : 'নতুন কাজ সফলভাবে যুক্ত করা হয়েছে!';
        session()->flash('message', $msg);
        $this->dispatch('show-toast', message: $msg, type: 'success');
        $this->reset(['editingTaskId', 'description', 'repeatDay']);

        // Refresh reminder state if today's task was modified
        $this->checkTodayReminder();
    }

    public function toggleTaskStatus($taskId)
    {
        $task = Task::findOrFail($taskId);
        $task->is_completed = !$task->is_completed;
        $task->completed_at = $task->is_completed ? now() : null;
        $task->save();

        $msg = $task->is_completed ? 'কাজটি সম্পূর্ণ হিসেবে মার্ক করা হয়েছে!' : 'কাজটি অসম্পূর্ণ তালিকায় ফেরত আনা হয়েছে!';
        session()->flash('message', $msg);
        $this->dispatch('show-toast', message: $msg, type: 'success');

        // If all today's tasks completed, check reminder state
        $this->checkTodayReminder();
    }

    public function updateInline($taskId, $field, $value)
    {
        $task = Task::findOrFail($taskId);
        if (!$this->canEditTask($task)) {
            session()->flash('message', 'আপনার এই টাস্ক আপডেট করার অনুমতি নেই।');
            return;
        }

        if (in_array($field, ['repeat_period', 'repeat_day', 'assigned_to', 'due_date'])) {
            $task->update([$field => $value]);
            session()->flash('message', 'কাজের তথ্য আপডেট করা হয়েছে!');
        }
    }

    public function confirmDeleteTask($taskId)
    {
        if (!$this->canDeleteTask()) {
            $msg = 'দুঃখিত! শুধুমাত্র অ্যাডমিন টাস্ক মুছে ফেলতে পারবেন।';
            session()->flash('message', $msg);
            $this->dispatch('show-toast', message: $msg, type: 'error');
            return;
        }
        $this->confirmDeleteTaskId = $taskId;
    }

    public function cancelDeleteTask()
    {
        $this->confirmDeleteTaskId = null;
    }

    public function executeDeleteTask()
    {
        if (!$this->canDeleteTask()) {
            $this->confirmDeleteTaskId = null;
            return;
        }

        if ($this->confirmDeleteTaskId) {
            $task = Task::find($this->confirmDeleteTaskId);
            if ($task) {
                $task->delete();
                $msg = 'কাজটি মুছে ফেলা হয়েছে!';
                session()->flash('message', $msg);
                $this->dispatch('show-toast', message: $msg, type: 'success');
            }
            $this->confirmDeleteTaskId = null;
        }
    }

    public function deleteTask($taskId)
    {
        $this->confirmDeleteTask($taskId);
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
        $user = auth()->user();
        $isAdmin = $this->checkIsAdmin();

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

        // Fetch Today's Incomplete Tasks for Reminder Modal
        $todayPendingTasksQuery = Task::where('is_completed', false)->whereDate('due_date', $today);
        if ($user && !$isAdmin) {
            $todayPendingTasksQuery->where('assigned_to', $user->name);
        }
        $todayPendingTasks = $todayPendingTasksQuery->orderBy('id', 'desc')->get();

        // Generate date numbers strip
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
            'incompleteTasks'   => $incompleteTasks,
            'completedTasks'    => $completedTasks,
            'todayPendingTasks' => $todayPendingTasks,
            'dateStrip'         => $dateStrip,
            'isAdmin'           => $isAdmin,
        ])->layout('layouts.app');
    }
}
