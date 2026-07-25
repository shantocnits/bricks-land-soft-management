@php
if (!function_exists('toBanglaNum')) {
    function toBanglaNum($num) {
        $en = ['0','1','2','3','4','5','6','7','8','9'];
        $bn = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
        return str_replace($en, $bn, (string)$num);
    }
}
@endphp

<div class="space-y-6 pb-12">
    <!-- Req 2: Status Toast Notification (Top Center Fixed) -->
    @if(session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="-translate-y-10 opacity-0 scale-95"
             x-transition:enter-end="translate-y-0 opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-y-0 opacity-100 scale-100"
             x-transition:leave-end="-translate-y-10 opacity-0 scale-95"
             class="fixed top-5 left-1/2 -translate-x-1/2 z-[99999] px-5 py-3 bg-[#034C3C] text-white rounded-2xl shadow-2xl flex items-center gap-3 font-bold text-xs border border-emerald-400/30">
            <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('message') }}</span>
            <button @click="show = false" class="text-white/70 hover:text-white ml-2 cursor-pointer">✕</button>
        </div>
    @endif

    <!-- Top Action Toolbar & Date Strip -->
    <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl p-4 sm:p-5 shadow-xs flex flex-col lg:flex-row items-center justify-between gap-4">
        <!-- Left: Add Task Button -->
        <div class="w-full lg:w-auto flex items-center justify-between lg:justify-start gap-3">
            <button type="button" wire:click="openTaskModal()"
                    class="px-4 py-2.5 bg-[#034C3C] hover:bg-emerald-800 text-white font-bold text-xs rounded-xl shadow-xs transition-all cursor-pointer flex items-center gap-2 whitespace-nowrap">
                <span class="text-base font-extrabold">+</span>
                <span>অ্যাড কাজ</span>
            </button>
        </div>

        <!-- Middle: Filter Period Tabs -->
        <div class="flex items-center gap-1.5 p-1 bg-gray-100 dark:bg-slate-800 rounded-2xl overflow-x-auto w-full lg:w-auto text-xs font-bold">
            <button type="button" wire:click="setFilterPeriod('today')"
                    class="px-4 py-2 rounded-xl transition-all cursor-pointer whitespace-nowrap {{ $filterPeriod === 'today' ? 'bg-[#034C3C] text-white shadow-xs' : 'text-gray-600 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white' }}">
                আজ
            </button>
            <button type="button" wire:click="setFilterPeriod('this_week')"
                    class="px-4 py-2 rounded-xl transition-all cursor-pointer whitespace-nowrap {{ $filterPeriod === 'this_week' ? 'bg-[#034C3C] text-white shadow-xs' : 'text-gray-600 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white' }}">
                এই সপ্তাহ
            </button>
            <button type="button" wire:click="setFilterPeriod('this_month')"
                    class="px-4 py-2 rounded-xl transition-all cursor-pointer whitespace-nowrap {{ $filterPeriod === 'this_month' ? 'bg-[#034C3C] text-white shadow-xs' : 'text-gray-600 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white' }}">
                এই মাস
            </button>
            <button type="button" wire:click="setFilterPeriod('all')"
                    class="px-4 py-2 rounded-xl transition-all cursor-pointer whitespace-nowrap {{ $filterPeriod === 'all' && !$selectedDateFilter ? 'bg-[#034C3C] text-white shadow-xs' : 'text-gray-600 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white' }}">
                সব কাজ
            </button>
        </div>

        <!-- Right: Horizontal Date Strip -->
        <div class="flex items-center gap-1 overflow-x-auto w-full lg:w-auto justify-center lg:justify-end text-xs font-mono font-bold">
            @foreach($dateStrip as $ds)
                <button type="button" wire:click="selectDateFilter('{{ $ds['date_str'] }}')"
                        class="w-8 h-8 flex items-center justify-center rounded-xl border transition-all cursor-pointer {{ $selectedDateFilter === $ds['date_str'] ? 'bg-emerald-600 text-white border-emerald-600 shadow-xs' : ($ds['is_today'] ? 'border-emerald-500 text-emerald-600 dark:text-emerald-400 bg-emerald-50/50 dark:bg-emerald-950/40' : 'border-gray-150 dark:border-slate-800 text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800') }}">
                    {{ toBanglaNum($ds['day_num']) }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- Section 1: অসম্পূর্ণ কাজ (Incomplete Tasks) -->
    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <h3 class="text-base sm:text-lg font-black text-gray-900 dark:text-white flex items-center gap-2">
                <span>অসম্পূর্ণ কাজ</span>
                <span class="px-2.5 py-0.5 rounded-full bg-amber-100 dark:bg-amber-950 text-amber-700 dark:text-amber-300 text-xs font-mono font-bold">
                    {{ toBanglaNum($incompleteTasks->total()) }}
                </span>
            </h3>
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl shadow-xs">
            <div class="overflow-x-visible">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#034C3C] text-white text-[11px] font-bold uppercase rounded-t-3xl">
                            <th class="py-3.5 px-4 rounded-tl-3xl border-r border-white/20">কাজের বিবরণ</th>
                            <th class="py-3.5 px-4 border-r border-white/20 w-48">পুনরাবৃত্তি পিরিয়ড</th>
                            <th class="py-3.5 px-4 border-r border-white/20 w-44">ব্যক্তি</th>
                            <th class="py-3.5 px-4 border-r border-white/20 w-48">তারিখ</th>
                            <th class="py-3.5 px-4 text-center rounded-tr-3xl w-28">বাটন</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-150 dark:divide-slate-800 text-xs">
                        @forelse($incompleteTasks as $task)
                            <!-- Req 3: Same hover row color across both tables (light & dark) -->
                            <tr class="hover:bg-emerald-50/40 dark:hover:bg-slate-800/60 transition-colors">
                                <!-- Checkbox + Description -->
                                <td class="py-3.5 px-4 font-semibold text-gray-900 dark:text-white border-r border-gray-150 dark:border-slate-800">
                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input type="checkbox" wire:click="toggleTaskStatus({{ $task->id }})"
                                               class="w-4 h-4 rounded text-emerald-600 border-gray-300 focus:ring-emerald-500 cursor-pointer">
                                        <span class="text-xs font-bold leading-normal">{{ $task->description }}</span>
                                    </label>
                                </td>

                                <!-- Req 1: Repeat Period as Dynamic Name (NO dropdown in table) -->
                                <td class="py-3.5 px-4 border-r border-gray-150 dark:border-slate-800">
                                    <span class="px-2.5 py-1 rounded-xl bg-emerald-50 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 font-bold text-xs inline-block border border-emerald-200/60 dark:border-emerald-900/50">
                                        {{ $task->repeat_period }} {{ $task->repeat_day ? '('.$task->repeat_day.')' : '' }}
                                    </span>
                                </td>

                                <!-- Assignee Person Dropdown (Dynamic Users List) -->
                                <td class="py-3.5 px-4 border-r border-gray-150 dark:border-slate-800">
                                    <div x-data="{ open: false }" class="relative text-xs">
                                        <button @click="open = !open" type="button"
                                                class="w-full flex items-center justify-between px-3 py-2 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white font-bold rounded-xl border border-gray-200 dark:border-slate-700 cursor-pointer shadow-xs hover:border-emerald-500 transition-colors">
                                            <span>{{ $task->assigned_to ?: '—' }}</span>
                                            <svg class="w-3.5 h-3.5 text-gray-400 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                        </button>
                                        <div x-show="open" @click.outside="open = false" x-cloak
                                             class="absolute bottom-full mb-1 left-0 right-0 z-[9999] max-h-44 overflow-y-auto bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl shadow-2xl py-1">
                                            @foreach($usersList as $u)
                                                <button type="button" wire:click="updateInline({{ $task->id }}, 'assigned_to', '{{ $u }}')" @click="open = false"
                                                        class="w-full text-left px-3 py-1.5 text-xs font-bold hover:bg-emerald-50 dark:hover:bg-slate-800 cursor-pointer flex items-center justify-between {{ $task->assigned_to === $u ? 'text-emerald-600 bg-emerald-50/50 dark:bg-emerald-950/60 dark:text-emerald-300' : 'text-gray-700 dark:text-slate-200' }}">
                                                    <span>{{ $u }}</span>
                                                    @if($task->assigned_to === $u) <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> @endif
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                </td>

                                <!-- Date (Flatpickr Integration with d-m-Y display) -->
                                <td class="py-3.5 px-4 border-r border-gray-150 dark:border-slate-800">
                                    <div class="relative" wire:ignore>
                                        <input type="text" data-flatpickr
                                               data-wire-prop="due_date"
                                               data-default="{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : '' }}"
                                               x-data x-init="
                                                   flatpickr($el, {
                                                       dateFormat: 'Y-m-d',
                                                       altInput: true,
                                                       altFormat: 'd-m-Y',
                                                       defaultDate: '{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : '' }}',
                                                       onChange: function(dates, str) {
                                                           $wire.updateInline({{ $task->id }}, 'due_date', str);
                                                       }
                                                   });
                                               "
                                               placeholder="dd-mm-yyyy" readonly
                                               class="w-full pl-3 pr-8 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white font-mono font-bold text-xs focus:outline-none cursor-pointer">
                                        <span class="absolute right-2.5 top-2.5 text-emerald-500 pointer-events-none">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                        </span>
                                    </div>
                                </td>

                                <!-- Professional Action Buttons -->
                                <td class="py-3.5 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" wire:click="openTaskModal({{ $task->id }})"
                                                class="p-2 bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white dark:bg-emerald-950/60 dark:text-emerald-400 font-bold rounded-xl border border-emerald-200 dark:border-emerald-900/50 transition-all cursor-pointer shadow-xs" title="এডিট">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <button type="button" wire:confirm="আপনি কি নিশ্চিতভাবে এই কাজ মুছে ফেলতে চান?" wire:click="deleteTask({{ $task->id }})"
                                                class="p-2 bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white dark:bg-rose-950/60 dark:text-rose-400 font-bold rounded-xl border border-rose-200 dark:border-rose-900/50 transition-all cursor-pointer shadow-xs" title="ডিলিট">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-10 text-center text-gray-400">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <div class="p-3 bg-gray-100 dark:bg-slate-800 rounded-2xl">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                        </div>
                                        <span class="text-xs font-semibold">কোনো অসম্পূর্ণ কাজ নেই।</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Incomplete Tasks Sort & Pagination Toolbar -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 px-5 py-4 border-t border-gray-150 dark:border-slate-800 bg-white dark:bg-slate-900 rounded-b-3xl">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div x-data="{ open: false }" class="relative text-xs">
                        <button @click="open = !open" type="button"
                                class="flex items-center gap-1.5 px-3 py-1.5 bg-white dark:bg-slate-800 text-gray-800 dark:text-white font-bold rounded-lg border border-gray-200 dark:border-slate-700 cursor-pointer shadow-xs hover:border-emerald-500 transition-colors">
                            <span>{{ toBanglaNum($perPageIncomplete) }} / পেজ</span>
                            <svg class="w-3.5 h-3.5 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" @click.outside="open = false" x-cloak
                             class="absolute bottom-full mb-1.5 left-0 z-[9999] w-32 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl shadow-xl overflow-hidden py-1">
                            @foreach([5, 10, 15, 20, 50] as $size)
                                <button type="button" wire:click="selectPerPageIncomplete({{ $size }})" @click="open = false"
                                        class="w-full text-left px-3 py-2 text-xs font-bold text-gray-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-slate-800 cursor-pointer transition-colors">
                                    {{ toBanglaNum($size) }} / পেজ
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="text-xs text-gray-500 dark:text-gray-400 font-semibold whitespace-nowrap">
                        মোট রেকর্ড: <strong class="text-gray-800 dark:text-white font-bold">{{ toBanglaNum($incompleteTasks->total()) }} টি</strong>
                    </div>
                </div>

                <div class="flex items-center [&_p]:mr-4 sm:[&_p]:mr-6 [&_p]:font-semibold">
                    {{ $incompleteTasks->links() }}
                </div>
            </div>
        </div>

        <!-- Mobile Box-Type Card View (Incomplete Tasks) -->
        <div class="block md:hidden space-y-3">
            @forelse($incompleteTasks as $task)
                <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-2xl p-4 shadow-xs space-y-3">
                    <div class="flex items-start justify-between gap-3 border-b border-gray-100 dark:border-slate-800 pb-2">
                        <label class="flex items-start gap-2.5 cursor-pointer">
                            <input type="checkbox" wire:click="toggleTaskStatus({{ $task->id }})"
                                   class="w-4 h-4 mt-0.5 rounded text-emerald-600 border-gray-300 focus:ring-emerald-500 cursor-pointer">
                            <span class="text-xs font-extrabold text-gray-900 dark:text-white leading-snug">{{ $task->description }}</span>
                        </label>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div>
                            <span class="text-gray-400 text-[10px] block mb-1">পুনরাবৃত্তি পিরিয়ড:</span>
                            <span class="px-2 py-0.5 rounded-lg bg-sky-50 dark:bg-sky-950 text-sky-600 dark:text-sky-300 font-bold text-[10px] inline-block">
                                {{ $task->repeat_period }} {{ $task->repeat_day ? '('.$task->repeat_day.')' : '' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-400 text-[10px] block mb-1">ব্যক্তি:</span>
                            <span class="font-bold text-gray-800 dark:text-slate-200 block">{{ $task->assigned_to ?: '—' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 text-[10px] block mb-1">তারিখ:</span>
                            <span class="font-mono font-semibold text-gray-700 dark:text-slate-300 block">{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d-m-Y') : '—' }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 pt-2 border-t border-gray-100 dark:border-slate-800">
                        <button type="button" wire:click="openTaskModal({{ $task->id }})"
                                class="flex-1 py-1.5 bg-emerald-50 dark:bg-slate-800 text-emerald-700 dark:text-emerald-300 font-bold rounded-xl text-xs hover:bg-emerald-600 hover:text-white transition-all flex items-center justify-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            <span>এডিট</span>
                        </button>
                        <button type="button" wire:confirm="আপনি কি নিশ্চিতভাবে এই কাজ মুছে ফেলতে চান?" wire:click="deleteTask({{ $task->id }})"
                                class="py-1.5 px-3 bg-rose-50 dark:bg-slate-800 text-rose-600 dark:text-rose-400 font-bold rounded-xl text-xs hover:bg-rose-600 hover:text-white transition-all flex items-center justify-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            <span>ডিলিট</span>
                        </button>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-2xl p-6 text-center text-gray-400 font-semibold text-xs">
                    কোনো অসম্পূর্ণ কাজ নেই।
                </div>
            @endforelse

            <div class="pt-2 flex items-center justify-end [&_p]:mr-4 sm:[&_p]:mr-6">
                {{ $incompleteTasks->links() }}
            </div>
        </div>
    </div>

    <!-- Section 2: সম্পূর্ণ কাজ (Completed Tasks) -->
    <div class="space-y-3 pt-4">
        <div class="flex items-center justify-between">
            <h3 class="text-base sm:text-lg font-black text-gray-900 dark:text-white flex items-center gap-2">
                <span>সম্পূর্ণ কাজ</span>
                <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 text-xs font-mono font-bold">
                    {{ toBanglaNum($completedTasks->total()) }}
                </span>
            </h3>
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl shadow-xs">
            <div class="overflow-x-visible">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#034C3C] text-white text-[11px] font-bold uppercase rounded-t-3xl">
                            <th class="py-3.5 px-4 rounded-tl-3xl border-r border-white/20">কাজের বিবরণ</th>
                            <th class="py-3.5 px-4 border-r border-white/20 w-48">পুনরাবৃত্তি পিরিয়ড</th>
                            <th class="py-3.5 px-4 border-r border-white/20 w-44">ব্যক্তি</th>
                            <th class="py-3.5 px-4 border-r border-white/20 w-48">তারিখ</th>
                            <th class="py-3.5 px-4 text-center rounded-tr-3xl w-28">বাটন</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-150 dark:divide-slate-800 text-xs">
                        @forelse($completedTasks as $task)
                            <!-- Req 3: Same row hover color for completed tasks -->
                            <tr class="bg-emerald-50/20 dark:bg-slate-900/60 hover:bg-emerald-50/40 dark:hover:bg-slate-800/60 transition-colors">
                                <!-- Checkbox + Description -->
                                <td class="py-3.5 px-4 font-semibold text-gray-800 dark:text-slate-200 border-r border-gray-150 dark:border-slate-800">
                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input type="checkbox" checked wire:click="toggleTaskStatus({{ $task->id }})"
                                               class="w-4 h-4 rounded text-emerald-600 border-gray-300 focus:ring-emerald-500 cursor-pointer">
                                        <span class="text-xs font-bold line-through text-gray-500 dark:text-slate-400 leading-normal">{{ $task->description }}</span>
                                    </label>
                                </td>

                                <!-- Repeat Period -->
                                <td class="py-3.5 px-4 border-r border-gray-150 dark:border-slate-800">
                                    <span class="px-2.5 py-1 rounded-xl bg-emerald-50 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 font-bold text-xs inline-block border border-emerald-200/60 dark:border-emerald-900/50">
                                        {{ $task->repeat_period }} {{ $task->repeat_day ? '('.$task->repeat_day.')' : '' }}
                                    </span>
                                </td>

                                <!-- Person -->
                                <td class="py-3.5 px-4 font-bold text-gray-700 dark:text-slate-300 border-r border-gray-150 dark:border-slate-800">
                                    {{ $task->assigned_to ?: '—' }}
                                </td>

                                <!-- Date -->
                                <td class="py-3.5 px-4 font-mono font-semibold text-gray-600 dark:text-slate-400 border-r border-gray-150 dark:border-slate-800">
                                    {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d-m-Y') : '—' }}
                                </td>

                                <!-- Buttons -->
                                <td class="py-3.5 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" wire:click="openTaskModal({{ $task->id }})"
                                                class="p-2 bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white dark:bg-emerald-950/60 dark:text-emerald-400 font-bold rounded-xl border border-emerald-200 dark:border-emerald-900/50 transition-all cursor-pointer shadow-xs" title="এডিট">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <button type="button" wire:confirm="আপনি কি নিশ্চিতভাবে এই কাজ মুছে ফেলতে চান?" wire:click="deleteTask({{ $task->id }})"
                                                class="p-2 bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white dark:bg-rose-950/60 dark:text-rose-400 font-bold rounded-xl border border-rose-200 dark:border-rose-900/50 transition-all cursor-pointer shadow-xs" title="ডিলিট">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-gray-400">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <div class="p-4 bg-gray-100 dark:bg-slate-800 rounded-3xl">
                                            <svg class="w-10 h-10 stroke-1.5 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                        </div>
                                        <span class="text-xs font-semibold text-gray-400">No data</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Completed Tasks Sort & Pagination Toolbar -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 px-5 py-4 border-t border-gray-150 dark:border-slate-800 bg-white dark:bg-slate-900 rounded-b-3xl">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div x-data="{ open: false }" class="relative text-xs">
                        <button @click="open = !open" type="button"
                                class="flex items-center gap-1.5 px-3 py-1.5 bg-white dark:bg-slate-800 text-gray-800 dark:text-white font-bold rounded-lg border border-gray-200 dark:border-slate-700 cursor-pointer shadow-xs hover:border-emerald-500 transition-colors">
                            <span>{{ toBanglaNum($perPageCompleted) }} / পেজ</span>
                            <svg class="w-3.5 h-3.5 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" @click.outside="open = false" x-cloak
                             class="absolute bottom-full mb-1.5 left-0 z-[9999] w-32 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl shadow-xl overflow-hidden py-1">
                            @foreach([5, 10, 15, 20, 50] as $size)
                                <button type="button" wire:click="selectPerPageCompleted({{ $size }})" @click="open = false"
                                        class="w-full text-left px-3 py-2 text-xs font-bold text-gray-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-slate-800 cursor-pointer transition-colors">
                                    {{ toBanglaNum($size) }} / পেজ
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="text-xs text-gray-500 dark:text-gray-400 font-semibold whitespace-nowrap">
                        মোট রেকর্ড: <strong class="text-gray-800 dark:text-white font-bold">{{ toBanglaNum($completedTasks->total()) }} টি</strong>
                    </div>
                </div>

                <div class="flex items-center [&_p]:mr-4 sm:[&_p]:mr-6 [&_p]:font-semibold">
                    {{ $completedTasks->links() }}
                </div>
            </div>
        </div>

        <!-- Mobile Box-Type Card View (Completed Tasks) -->
        <div class="block md:hidden space-y-3">
            @forelse($completedTasks as $task)
                <div class="bg-emerald-50/30 dark:bg-slate-900 border border-emerald-200 dark:border-slate-800 rounded-2xl p-4 shadow-xs space-y-3">
                    <div class="flex items-start justify-between gap-3 border-b border-emerald-100 dark:border-slate-800 pb-2">
                        <label class="flex items-start gap-2.5 cursor-pointer">
                            <input type="checkbox" checked wire:click="toggleTaskStatus({{ $task->id }})"
                                   class="w-4 h-4 mt-0.5 rounded text-emerald-600 border-gray-300 focus:ring-emerald-500 cursor-pointer">
                            <span class="text-xs font-extrabold line-through text-gray-500 dark:text-slate-400 leading-snug">{{ $task->description }}</span>
                        </label>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div>
                            <span class="text-gray-400 text-[10px] block mb-1">পুনরাবৃত্তি পিরিয়ড:</span>
                            <span class="px-2 py-0.5 rounded-lg bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 font-bold text-[10px] inline-block">
                                {{ $task->repeat_period }} {{ $task->repeat_day ? '('.$task->repeat_day.')' : '' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-400 text-[10px] block mb-1">ব্যক্তি:</span>
                            <span class="font-bold text-gray-800 dark:text-slate-200 block">{{ $task->assigned_to ?: '—' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 text-[10px] block mb-1">তারিখ:</span>
                            <span class="font-mono font-semibold text-gray-700 dark:text-slate-300 block">{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d-m-Y') : '—' }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 pt-2 border-t border-emerald-100 dark:border-slate-800">
                        <button type="button" wire:click="openTaskModal({{ $task->id }})"
                                class="flex-1 py-1.5 bg-emerald-100 dark:bg-slate-800 text-emerald-800 dark:text-emerald-300 font-bold rounded-xl text-xs hover:bg-emerald-600 hover:text-white transition-all flex items-center justify-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            <span>এডিট</span>
                        </button>
                        <button type="button" wire:confirm="আপনি কি নিশ্চিতভাবে এই কাজ মুছে ফেলতে চান?" wire:click="deleteTask({{ $task->id }})"
                                class="py-1.5 px-3 bg-rose-50 dark:bg-slate-800 text-rose-600 dark:text-rose-400 font-bold rounded-xl text-xs hover:bg-rose-600 hover:text-white transition-all flex items-center justify-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            <span>ডিলিট</span>
                        </button>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-2xl p-6 text-center text-gray-400 font-semibold text-xs">
                    কোনো সম্পূর্ণ কাজ নেই।
                </div>
            @endforelse

            <div class="pt-2 flex items-center justify-end [&_p]:mr-4 sm:[&_p]:mr-6">
                {{ $completedTasks->links() }}
            </div>
        </div>
    </div>

    <!-- Modal: নতুন কাজ সংযোজন / এডিট (Add / Edit Task Modal) -->
    <!-- Req 4: Exclusive dropdown state (activeDropdown) + Outside click handler -->
    @if($showTaskModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs"
             wire:click.self="$set('showTaskModal', false)">
            <div x-data="{ activeDropdown: null }" @click.outside="activeDropdown = null"
                 class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-gray-150 dark:border-slate-800 w-full max-w-lg p-6 space-y-4"
                 wire:click.stop>
                <!-- Modal Header -->
                <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-800 pb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="p-2 bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400 rounded-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-gray-900 dark:text-white">
                                {{ $editingTaskId ? 'কাজের তথ্য এডিট করুন' : 'নতুন কাজ সংযোজন' }}
                            </h3>
                            <p class="text-[11px] text-gray-400 font-semibold">কাজের বিবরণ ও মেয়াদ নির্ধারণ করুন</p>
                        </div>
                    </div>
                    <!-- Rounded Close Button -->
                    <button type="button" wire:click="$set('showTaskModal', false)"
                            class="text-gray-400 hover:text-gray-700 dark:hover:text-white cursor-pointer p-2 rounded-full bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 transition-all">✕</button>
                </div>

                <!-- Form Fields -->
                <div class="space-y-4 text-xs">
                    <!-- Task Description -->
                    <div>
                        <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">কাজের বিবরণ *</label>
                        <textarea wire:model="description" rows="3" placeholder="কাজের বিষয় ও বিস্তারিত লিখুন..."
                                  class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 font-semibold"></textarea>
                        @error('description') <span class="text-rose-500 text-[10px] font-bold mt-0.5 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Repeat Period & Assignee Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <!-- Repeat Period Dropdown (Single activeDropdown handler) -->
                        <div>
                            <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">পুনরাবৃত্তি পিরিয়ড *</label>
                            <div class="relative">
                                <button @click="activeDropdown = (activeDropdown === 'period' ? null : 'period')" type="button"
                                        class="w-full flex items-center justify-between px-3.5 py-2.5 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white font-bold rounded-xl border border-gray-200 dark:border-slate-700 cursor-pointer shadow-xs hover:border-emerald-500 transition-colors">
                                    <span>{{ $repeatPeriod }}</span>
                                    <svg class="w-4 h-4 text-gray-400 transition-transform" :class="{'rotate-180': activeDropdown === 'period'}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="activeDropdown === 'period'" x-cloak
                                     class="absolute left-0 right-0 mt-1 z-[9999] bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl shadow-2xl py-1">
                                    @foreach(['everyday', 'weekly', 'monthly', 'once'] as $period)
                                        <button type="button" wire:click="$set('repeatPeriod', '{{ $period }}')" @click="activeDropdown = null"
                                                class="w-full text-left px-4 py-2 text-xs font-bold hover:bg-emerald-50 dark:hover:bg-slate-800 cursor-pointer flex items-center justify-between {{ $repeatPeriod === $period ? 'text-emerald-600 bg-emerald-50/50 dark:bg-emerald-950/60 dark:text-emerald-300' : 'text-gray-700 dark:text-slate-200' }}">
                                            <span>{{ $period }}</span>
                                            @if($repeatPeriod === $period) <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> @endif
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                            @error('repeatPeriod') <span class="text-rose-500 text-[10px] font-bold mt-0.5 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Assignee Person Dropdown (Single activeDropdown handler) -->
                        <div>
                            <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">ব্যক্তি</label>
                            <div class="relative">
                                <button @click="activeDropdown = (activeDropdown === 'assignee' ? null : 'assignee')" type="button"
                                        class="w-full flex items-center justify-between px-3.5 py-2.5 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white font-bold rounded-xl border border-gray-200 dark:border-slate-700 cursor-pointer shadow-xs hover:border-emerald-500 transition-colors">
                                    <span>{{ $assignedTo ?: 'নির্বাচন করুন' }}</span>
                                    <svg class="w-4 h-4 text-gray-400 transition-transform" :class="{'rotate-180': activeDropdown === 'assignee'}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="activeDropdown === 'assignee'" x-cloak
                                     class="absolute left-0 right-0 mt-1 z-[9999] max-h-48 overflow-y-auto bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl shadow-2xl py-1">
                                    @foreach($usersList as $u)
                                        <button type="button" wire:click="$set('assignedTo', '{{ $u }}')" @click="activeDropdown = null"
                                                class="w-full text-left px-4 py-2 text-xs font-bold hover:bg-emerald-50 dark:hover:bg-slate-800 cursor-pointer flex items-center justify-between {{ $assignedTo === $u ? 'text-emerald-600 bg-emerald-50/50 dark:bg-emerald-950/60 dark:text-emerald-300' : 'text-gray-700 dark:text-slate-200' }}">
                                            <span>{{ $u }}</span>
                                            @if($assignedTo === $u) <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> @endif
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic Sub-Field for Weekly or Monthly (Single activeDropdown handler) -->
                    @if($repeatPeriod === 'weekly')
                        <div x-transition class="p-3 bg-emerald-50/50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-900/50 rounded-2xl space-y-1.5">
                            <label class="block font-bold text-emerald-800 dark:text-emerald-300 text-xs">📅 সপ্তাহের কোনো দিনে অনুষ্ঠিত হবে? (Select Day of Week)</label>
                            <div class="relative">
                                <button @click="activeDropdown = (activeDropdown === 'day' ? null : 'day')" type="button"
                                        class="w-full flex items-center justify-between px-3.5 py-2 bg-white dark:bg-slate-950 text-gray-800 dark:text-white font-bold rounded-xl border border-gray-200 dark:border-slate-700 cursor-pointer shadow-xs">
                                    <span>{{ $repeatDay ?: 'Select a day of the week' }}</span>
                                    <svg class="w-4 h-4 text-emerald-500 transition-transform" :class="{'rotate-180': activeDropdown === 'day'}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="activeDropdown === 'day'" x-cloak
                                     class="absolute left-0 right-0 mt-1 z-[9999] max-h-48 overflow-y-auto bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl shadow-2xl py-1">
                                    @foreach(['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                                        <button type="button" wire:click="$set('repeatDay', '{{ $day }}')" @click="activeDropdown = null"
                                                class="w-full text-left px-4 py-2 text-xs font-semibold hover:bg-emerald-50 dark:hover:bg-slate-800 cursor-pointer flex items-center justify-between {{ $repeatDay === $day ? 'text-emerald-600 bg-emerald-50/50 dark:bg-emerald-950/60 dark:text-emerald-300 font-bold' : 'text-gray-700 dark:text-slate-200' }}">
                                            <span>{{ $day }}</span>
                                            @if($repeatDay === $day) <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> @endif
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @elseif($repeatPeriod === 'monthly')
                        <div x-transition class="p-3 bg-emerald-50/50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-900/50 rounded-2xl space-y-1.5">
                            <label class="block font-bold text-emerald-800 dark:text-emerald-300 text-xs">📆 মাসের কত তারিখে অনুষ্ঠিত হবে? (Select Date of Month)</label>
                            <div class="relative">
                                <button @click="activeDropdown = (activeDropdown === 'day' ? null : 'day')" type="button"
                                        class="w-full flex items-center justify-between px-3.5 py-2 bg-white dark:bg-slate-950 text-gray-800 dark:text-white font-bold rounded-xl border border-gray-200 dark:border-slate-700 cursor-pointer shadow-xs">
                                    <span>{{ $repeatDay ? toBanglaNum($repeatDay) . ' তারিখ' : 'তারিখ নির্বাচন করুন (১-৩১)' }}</span>
                                    <svg class="w-4 h-4 text-emerald-500 transition-transform" :class="{'rotate-180': activeDropdown === 'day'}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="activeDropdown === 'day'" x-cloak
                                     class="absolute left-0 right-0 mt-1 z-[9999] max-h-48 overflow-y-auto bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl shadow-2xl py-1">
                                    @for($m = 1; $m <= 31; $m++)
                                        <button type="button" wire:click="$set('repeatDay', '{{ $m }}')" @click="activeDropdown = null"
                                                class="w-full text-left px-4 py-2 text-xs font-mono font-semibold hover:bg-emerald-50 dark:hover:bg-slate-800 cursor-pointer flex items-center justify-between {{ $repeatDay == $m ? 'text-emerald-600 bg-emerald-50/50 dark:bg-emerald-950/60 dark:text-emerald-300 font-bold' : 'text-gray-700 dark:text-slate-200' }}">
                                            <span>{{ toBanglaNum($m) }} তারিখ</span>
                                            @if($repeatDay == $m) <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> @endif
                                        </button>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Date (Flatpickr Datepicker) -->
                    <div>
                        <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">তারিখ *</label>
                        <div class="relative">
                            <input type="text" data-flatpickr data-wire-prop="dueDate"
                                   data-default="{{ $dueDate }}"
                                   placeholder="dd/mm/yy" readonly
                                   class="w-full pl-3.5 pr-10 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 font-semibold cursor-pointer">
                            <span class="absolute right-3 top-3 text-emerald-500 pointer-events-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            </span>
                        </div>
                        @error('dueDate') <span class="text-rose-500 text-[10px] font-bold mt-0.5 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Footer Action Buttons -->
                <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100 dark:border-slate-800">
                    <button type="button" wire:click="$set('showTaskModal', false)"
                            class="px-4 py-2 bg-gray-200 dark:bg-slate-800 text-gray-700 dark:text-slate-200 rounded-xl font-bold text-xs cursor-pointer">
                        বাতিল
                    </button>
                    <button type="button" wire:click="saveTask()" wire:loading.attr="disabled"
                            class="px-5 py-2 bg-[#034C3C] hover:bg-emerald-800 text-white rounded-xl font-bold text-xs cursor-pointer transition-all shadow-xs flex items-center gap-1">
                        <span wire:loading.remove wire:target="saveTask">সংরক্ষণ করুন</span>
                        <span wire:loading wire:target="saveTask">সেভ হচ্ছে...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
