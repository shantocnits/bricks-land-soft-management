@php
if (!function_exists('toBanglaNum')) {
    function toBanglaNum($num) {
        $numStr = (string)$num;
        if (str_contains($numStr, '.')) {
            $numStr = rtrim(rtrim($numStr, '0'), '.');
        }
        $en = ['0','1','2','3','4','5','6','7','8','9'];
        $bn = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
        return str_replace($en, $bn, $numStr);
    }
}
@endphp

<div class="space-y-6 pb-12">
    <!-- Toast Notification (Top Center Fixed - Repeatable 2s Toast) -->
    <div x-data="{ show: false, message: '', timer: null }"
         x-init="
            @if(session()->has('message'))
                message = @js(session('message'));
                show = true;
                timer = setTimeout(() => show = false, 2000);
            @endif
         "
         @show-toast.window="
            message = $event.detail.message;
            show = false;
            if (timer) clearTimeout(timer);
            $nextTick(() => {
                show = true;
                timer = setTimeout(() => show = false, 2000);
            });
         "
         x-show="show"
         x-transition:enter="transition ease-out duration-200 transform"
         x-transition:enter-start="-translate-y-10 opacity-0 scale-95"
         x-transition:enter-end="translate-y-0 opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150 transform"
         x-transition:leave-start="translate-y-0 opacity-100 scale-100"
         x-transition:leave-end="-translate-y-10 opacity-0 scale-95"
         x-cloak
         class="fixed top-5 left-1/2 -translate-x-1/2 z-[99999] px-5 py-3 bg-[#034C3C] text-white rounded-2xl shadow-2xl flex items-center gap-3 font-bold text-xs border border-emerald-400/30">
        <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        <span x-text="message"></span>
        <button @click="show = false" class="text-white/70 hover:text-white ml-2 cursor-pointer">✕</button>
    </div>

    <!-- Show Top Filter Bar and 6 KPI Cards ONLY when on main dashboard (!selectedVehicleId) -->
    @if(!$selectedVehicleId)
        <!-- Top Action & Filter Toolbar -->
        <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl p-4 sm:p-5 shadow-xs flex flex-col lg:flex-row items-center justify-between gap-4">
            <!-- Left: Filter Period Buttons -->
            <div class="flex items-center gap-1.5 p-1 bg-gray-100 dark:bg-slate-800 rounded-2xl overflow-x-auto w-full lg:w-auto text-xs font-bold">
                <button type="button" wire:click="setFilterPeriod('today')"
                        class="px-4 py-2 rounded-xl transition-all cursor-pointer whitespace-nowrap {{ $filterPeriod === 'today' ? 'bg-[#034C3C] text-white shadow-xs' : 'text-gray-600 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white' }}">
                    আজকের হিসাব
                </button>
                <button type="button" wire:click="setFilterPeriod('7_days')"
                        class="px-4 py-2 rounded-xl transition-all cursor-pointer whitespace-nowrap {{ $filterPeriod === '7_days' ? 'bg-[#034C3C] text-white shadow-xs' : 'text-gray-600 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white' }}">
                    গত ৭ দিনের হিসাব
                </button>
                <button type="button" wire:click="setFilterPeriod('15_days')"
                        class="px-4 py-2 rounded-xl transition-all cursor-pointer whitespace-nowrap {{ $filterPeriod === '15_days' ? 'bg-[#034C3C] text-white shadow-xs' : 'text-gray-600 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white' }}">
                    গত ১৫ দিনের হিসাব
                </button>
                <button type="button" wire:click="setFilterPeriod('all')"
                        class="px-4 py-2 rounded-xl transition-all cursor-pointer whitespace-nowrap {{ $filterPeriod === 'all' && !$filterMonth && !$filterDate ? 'bg-[#034C3C] text-white shadow-xs' : 'text-gray-600 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white' }}">
                    সর্বমোট হিসাব
                </button>
            </div>

            <!-- Right: Flatpickr Date & Settings Icon -->
            <div class="flex items-center gap-2 w-full lg:w-auto justify-end text-xs">
                <!-- Date Picker (placeholder dd/mm/yy) -->
                <div x-data="{
                    fp: null,
                    init() {
                        this.fp = flatpickr($refs.dateInput, {
                            locale: fpLocale,
                            dateFormat: 'Y-m-d',
                            altInput: true,
                            altFormat: 'd-m-Y',
                            allowInput: false,
                            disableMobile: true,
                            defaultDate: $wire.filterDate || '',
                            onChange: (dates, str) => {
                                $wire.set('filterDate', str);
                            }
                        });
                        $wire.watch('filterDate', (val) => {
                            if (!val) {
                                this.fp.clear();
                            } else {
                                this.fp.setDate(val, false);
                            }
                        });
                    }
                }" class="relative w-36 sm:w-40" wire:ignore>
                    <input x-ref="dateInput" type="text" placeholder="dd/mm/yy" readonly
                           class="w-full pl-3 pr-8 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white font-mono font-bold text-xs cursor-pointer focus:outline-none">
                    <span class="absolute right-2.5 top-2.5 text-gray-400 pointer-events-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </span>
                </div>

                <!-- Vehicle Settings Button -->
                <button type="button" wire:click="openEditVehicleModal()" title="গাড়ি সেটিংস"
                        class="p-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl shadow-xs transition-all cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </button>
            </div>
        </div>

        <!-- 6 KPI Summary Cards Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
            <div class="bg-blue-600 rounded-2xl p-4 text-white shadow-md space-y-1">
                <span class="text-xs font-semibold block opacity-90">মোট আয়</span>
                <div class="text-xl sm:text-2xl font-black font-mono">৳ {{ toBanglaNum(number_format($totalIncome, 0)) }}</div>
            </div>

            <div class="bg-amber-600 rounded-2xl p-4 text-white shadow-md space-y-1">
                <span class="text-xs font-semibold block opacity-90">মোট ব্যয়</span>
                <div class="text-xl sm:text-2xl font-black font-mono">৳ {{ toBanglaNum(number_format($totalExpense, 0)) }}</div>
            </div>

            <div class="bg-purple-600 rounded-2xl p-4 text-white shadow-md space-y-1">
                <span class="text-xs font-semibold block opacity-90">মোট ক্যাশ</span>
                <div class="text-xl sm:text-2xl font-black font-mono">৳ {{ toBanglaNum(number_format($totalCash, 0)) }}</div>
            </div>

            <div class="bg-emerald-600 rounded-2xl p-4 text-white shadow-md space-y-1">
                <span class="text-xs font-semibold block opacity-90">মহাজন নেওয়া</span>
                <div class="text-xl sm:text-2xl font-black font-mono">৳ {{ toBanglaNum(number_format($mahajanTaken, 0)) }}</div>
            </div>

            <div class="bg-rose-600 rounded-2xl p-4 text-white shadow-md space-y-1">
                <span class="text-xs font-semibold block opacity-90">মহাজন দেওয়া</span>
                <div class="text-xl sm:text-2xl font-black font-mono">৳ {{ toBanglaNum(number_format($mahajanGiven, 0)) }}</div>
            </div>

            <div class="bg-indigo-600 rounded-2xl p-4 text-white shadow-md space-y-1">
                <span class="text-xs font-semibold block opacity-90">ক্যাশ জের</span>
                <div class="text-xl sm:text-2xl font-black font-mono">৳ {{ toBanglaNum(number_format($cashJer, 0)) }}</div>
            </div>
        </div>
    @endif

    @if(!$selectedVehicleId)
        <!-- VIEW 1: Main Dashboard (Vehicles Grid + Income Report Table) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Left: Vehicles Cards Grid (3 Columns on Desktop, 2 on Mobile) -->
            <div class="lg:col-span-7 space-y-4">
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    @foreach($vehicles as $v)
                        <button type="button" wire:click="selectVehicle({{ $v->id }})"
                                class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 hover:border-emerald-500 dark:hover:border-emerald-500 rounded-3xl p-6 shadow-xs transition-all hover:shadow-lg cursor-pointer flex flex-col items-center justify-center space-y-3 group text-center min-h-[140px]">
                            <div class="p-3 bg-emerald-50 dark:bg-emerald-950/60 rounded-2xl text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition-transform">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-1.1 0-2 .9-2 2v7c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><circle cx="17" cy="17" r="2"/></svg>
                            </div>
                            <span class="text-base font-extrabold text-gray-800 dark:text-white font-mono group-hover:text-emerald-600 transition-colors">{{ $v->name }}</span>
                        </button>
                    @endforeach

                    <!-- Add Vehicle Button Card -->
                    <button type="button" wire:click="openAddVehicleModal()"
                            class="bg-white dark:bg-slate-900 border-2 border-dashed border-gray-200 dark:border-slate-700 hover:border-emerald-500 dark:hover:border-emerald-500 rounded-3xl p-6 transition-all hover:shadow-lg cursor-pointer flex flex-col items-center justify-center space-y-2 group text-center min-h-[140px]">
                        <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 flex items-center justify-center text-2xl font-black group-hover:scale-110 transition-transform">+</div>
                        <span class="text-sm font-extrabold text-gray-700 dark:text-slate-200 group-hover:text-emerald-600 transition-colors">+ অ্যাড গাড়ি</span>
                    </button>
                </div>
            </div>

            <!-- Right: Income Report Table -->
            <div class="lg:col-span-5 bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl shadow-xs overflow-hidden h-fit">
                <div class="bg-[#034C3C] text-white py-3.5 px-5 font-black text-sm text-center">
                    ইনকাম রিপোর্ট
                </div>

                <!-- Desktop Table View -->
                <div class="hidden sm:block overflow-x-auto">
                    <table class="w-full border-collapse text-left text-xs">
                        <thead>
                            <tr class="bg-emerald-50 dark:bg-slate-800/80 text-emerald-800 dark:text-emerald-300 font-bold border-b border-gray-150 dark:border-slate-800">
                                <th class="py-3 px-4">গাড়ির নাম</th>
                                <th class="py-3 px-4 text-emerald-600 dark:text-emerald-400 text-right">আয়</th>
                                <th class="py-3 px-4 text-rose-500 text-right">ব্যয়</th>
                                <th class="py-3 px-4 text-emerald-700 dark:text-emerald-300 text-right font-black">ইনকাম</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-150 dark:divide-slate-800">
                            @forelse($incomeReport as $item)
                                <tr class="hover:bg-emerald-50/40 dark:hover:bg-slate-800/60 transition-colors">
                                    <td class="py-3 px-4 font-extrabold text-gray-800 dark:text-white font-mono">{{ $item['vehicle']->name }}</td>
                                    <td class="py-3 px-4 font-mono font-bold text-emerald-600 text-right">৳ {{ toBanglaNum(number_format($item['income'], 0)) }}</td>
                                    <td class="py-3 px-4 font-mono font-bold text-rose-500 text-right">৳ {{ toBanglaNum(number_format($item['expense'], 0)) }}</td>
                                    <td class="py-3 px-4 font-mono font-black text-emerald-700 dark:text-emerald-400 text-right">৳ {{ toBanglaNum(number_format($item['net'], 0)) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="py-6 text-center text-gray-400 font-semibold">কোনো গাড়ির তথ্য নেই</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Box-Type Card View -->
                <div class="block sm:hidden p-4 space-y-3">
                    @forelse($incomeReport as $item)
                        <div class="p-3.5 bg-gray-50 dark:bg-slate-800/60 rounded-2xl border border-gray-200 dark:border-slate-700 space-y-2 text-xs">
                            <div class="flex items-center justify-between border-b border-gray-200 dark:border-slate-700 pb-1.5">
                                <span class="font-extrabold text-gray-900 dark:text-white font-mono text-sm">{{ $item['vehicle']->name }}</span>
                                <span class="font-black text-emerald-600 dark:text-emerald-400 font-mono">ইনকাম: ৳ {{ toBanglaNum(number_format($item['net'], 0)) }}</span>
                            </div>
                            <div class="flex items-center justify-between font-mono font-bold text-[11px]">
                                <span class="text-emerald-600">আয়: ৳ {{ toBanglaNum(number_format($item['income'], 0)) }}</span>
                                <span class="text-rose-500">ব্যয়: ৳ {{ toBanglaNum(number_format($item['expense'], 0)) }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="py-4 text-center text-gray-400 font-semibold text-xs">কোনো গাড়ির তথ্য নেই</div>
                    @endforelse
                </div>
            </div>
        </div>

    @else

        <!-- VIEW 2: Specific Vehicle Details View -->
        <div class="space-y-4">
            <!-- Dynamic Breadcrumb Navigation in Top Bar -->
            <div class="flex items-center justify-between bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-2xl px-5 py-3 shadow-xs">
                <div class="flex items-center gap-2 text-sm font-extrabold text-gray-800 dark:text-white">
                    <button type="button" wire:click="selectVehicle(null)" class="text-emerald-600 hover:underline cursor-pointer">গাড়ি</button>
                    <span class="text-gray-400">/</span>
                    <span class="font-mono text-emerald-700 dark:text-emerald-400">{{ $selectedVehicle->name }}</span>
                </div>
                <!-- Back Button -->
                <button type="button" wire:click="selectVehicle(null)"
                        class="px-4 py-2 bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-slate-200 hover:bg-[#034C3C] hover:text-white dark:hover:bg-[#034C3C] dark:hover:text-white rounded-xl font-bold text-xs cursor-pointer transition-all shadow-xs">
                    ← ব্যাকে যান
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Left Tabs Navigation -->
                <div class="lg:col-span-3 space-y-2">
                    <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl p-3 shadow-xs space-y-2">
                        <!-- Tab 1: আয় -->
                        <button type="button" wire:click="setTab('income')"
                                class="w-full text-left px-4 py-3 rounded-2xl font-black text-xs transition-all cursor-pointer flex items-center gap-2.5 {{ $activeTab === 'income' ? 'bg-[#034C3C] text-white shadow-xs' : 'bg-gray-50 dark:bg-slate-800/60 text-gray-700 dark:text-slate-300 hover:bg-emerald-50 dark:hover:bg-slate-800' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span>আয়</span>
                        </button>

                        <!-- Tab 2: ব্যয় -->
                        <button type="button" wire:click="setTab('expense')"
                                class="w-full text-left px-4 py-3 rounded-2xl font-black text-xs transition-all cursor-pointer flex items-center gap-2.5 {{ $activeTab === 'expense' ? 'bg-[#034C3C] text-white shadow-xs' : 'bg-gray-50 dark:bg-slate-800/60 text-gray-700 dark:text-slate-300 hover:bg-emerald-50 dark:hover:bg-slate-800' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>ব্যয়</span>
                        </button>

                        <!-- Tab 3: ক্যাশ -->
                        <button type="button" wire:click="setTab('cash')"
                                class="w-full text-left px-4 py-3 rounded-2xl font-black text-xs transition-all cursor-pointer flex items-center gap-2.5 {{ $activeTab === 'cash' ? 'bg-[#034C3C] text-white shadow-xs' : 'bg-gray-50 dark:bg-slate-800/60 text-gray-700 dark:text-slate-300 hover:bg-emerald-50 dark:hover:bg-slate-800' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span>ক্যাশ</span>
                        </button>

                        <!-- Tab 4: বাকি -->
                        <button type="button" wire:click="setTab('due')"
                                class="w-full text-left px-4 py-3 rounded-2xl font-black text-xs transition-all cursor-pointer flex items-center gap-2.5 {{ $activeTab === 'due' ? 'bg-[#034C3C] text-white shadow-xs' : 'bg-gray-50 dark:bg-slate-800/60 text-gray-700 dark:text-slate-300 hover:bg-emerald-50 dark:hover:bg-slate-800' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>বাকি</span>
                        </button>

                        <!-- Tab 5: হিস্ট্রি -->
                        <button type="button" wire:click="setTab('history')"
                                class="w-full text-left px-4 py-3 rounded-2xl font-black text-xs transition-all cursor-pointer flex items-center gap-2.5 {{ $activeTab === 'history' ? 'bg-[#034C3C] text-white shadow-xs' : 'bg-gray-50 dark:bg-slate-800/60 text-gray-700 dark:text-slate-300 hover:bg-emerald-50 dark:hover:bg-slate-800' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>হিস্ট্রি</span>
                        </button>

                        <!-- Tab 6: খতিয়ান -->
                        <button type="button" wire:click="setTab('ledger')"
                                class="w-full text-left px-4 py-3 rounded-2xl font-black text-xs transition-all cursor-pointer flex items-center gap-2.5 {{ $activeTab === 'ledger' ? 'bg-[#034C3C] text-white shadow-xs' : 'bg-gray-50 dark:bg-slate-800/60 text-gray-700 dark:text-slate-300 hover:bg-emerald-50 dark:hover:bg-slate-800' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            <span>খতিয়ান</span>
                        </button>
                    </div>
                </div>

                <!-- Right Table / View Content -->
                <div class="lg:col-span-9 space-y-4">
                    @if($activeTab === 'ledger')
                        <!-- KHOTIAN TAB VIEW -->
                        <div class="space-y-4">
                            <!-- Top Bar: Total Khotian Badge & Professional Search Icon -->
                            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl p-4 shadow-xs">
                                <div class="px-4 py-2 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 font-extrabold text-xs rounded-xl border border-emerald-200 dark:border-emerald-900/50">
                                    মোট খতিয়ান: <span class="font-mono">{{ toBanglaNum($khotianCards->count()) }} টি</span>
                                </div>
                                <div class="relative w-full sm:w-72">
                                    <input type="text" wire:model.live="searchKhotian" placeholder="সার্চ করুন..."
                                           class="w-full pl-4 pr-10 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white font-semibold text-xs focus:outline-none focus:border-emerald-500">
                                    <button type="button" class="absolute right-3 top-2.5 text-gray-400 hover:text-emerald-600 cursor-pointer transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Khotian Grid Boxes -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                @forelse($khotianCards as $kc)
                                    <button type="button" wire:click="openKhotianDetailModal('{{ $kc->kname }}')"
                                            class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 hover:border-emerald-500 rounded-3xl p-4 shadow-xs transition-all hover:shadow-md cursor-pointer flex items-center gap-3 group text-left">
                                        <div class="p-3 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 rounded-2xl group-hover:scale-110 transition-transform">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                        </div>
                                        <div>
                                            <h4 class="font-extrabold text-xs text-gray-900 dark:text-white group-hover:text-emerald-600 transition-colors">{{ $kc->kname }}</h4>
                                            <span class="font-mono text-xs font-bold text-emerald-600 dark:text-emerald-400 block">৳ {{ toBanglaNum(number_format($kc->total_bill ?? $kc->total_amount, 0)) }}</span>
                                            <span class="text-[10px] text-gray-400 font-semibold">মোট রেকর্ড: {{ toBanglaNum($kc->total_count) }} টি</span>
                                        </div>
                                    </button>
                                @empty
                                    <div class="col-span-full py-12 text-center text-gray-400 font-semibold text-xs">
                                        কোনো খতিয়ান পাওয়া যায়নি।
                                    </div>
                                @endforelse
                            </div>
                        </div>

                    @else
                        <!-- Action Bar for Tabs -->
                        <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl p-3.5 shadow-xs space-y-3">
                            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
                                <!-- Col 1: Left Button OR Tab Heading Title -->
                                <div>
                                    @if(in_array($activeTab, ['income', 'expense', 'cash']))
                                        <button type="button" wire:click="openTransactionModal()"
                                                class="w-full sm:w-auto px-4 py-2 bg-[#034C3C] hover:bg-emerald-800 text-white font-extrabold text-xs rounded-xl shadow-xs transition-all cursor-pointer flex items-center justify-center gap-2">
                                            <span class="text-base">+</span>
                                            <span>নতুন {{ $activeTab === 'income' ? 'আয়' : ($activeTab === 'expense' ? 'ব্যয়' : 'ক্যাশ') }}</span>
                                        </button>
                                    @elseif($activeTab === 'due')
                                        <h3 class="font-extrabold text-sm text-gray-800 dark:text-white py-1">বাকি / দেনা-পাওনা</h3>
                                    @elseif($activeTab === 'history')
                                        <h3 class="font-extrabold text-sm text-gray-800 dark:text-white py-1">হিস্ট্রি রেকর্ড</h3>
                                    @else
                                        <div></div>
                                    @endif
                                </div>

                                <!-- Col 2 & 3: Dynamic Tab Badges & Date Field -->
                                <div class="flex flex-wrap items-center justify-end gap-3">
                                    @if($activeTab === 'income')
                                        <div class="px-3.5 py-1.5 bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 font-extrabold rounded-xl border border-emerald-200 dark:border-emerald-900/50">
                                            মোট আয়: <span class="font-mono text-emerald-900 dark:text-emerald-200">৳ {{ toBanglaNum(number_format($vehicleTotalIncome, 0)) }}</span>
                                        </div>
                                    @elseif($activeTab === 'expense')
                                        <div class="px-3.5 py-1.5 bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 font-extrabold rounded-xl border border-amber-200 dark:border-amber-900/50">
                                            মোট ব্যয়: <span class="font-mono text-amber-900 dark:text-amber-200">৳ {{ toBanglaNum(number_format($vehicleTotalExpense, 0)) }}</span>
                                        </div>
                                    @elseif($activeTab === 'cash')
                                        <div class="flex items-center gap-1.5">
                                            <div class="px-3 py-1.5 bg-emerald-100 dark:bg-emerald-950/80 text-emerald-800 dark:text-emerald-300 font-extrabold rounded-xl border border-emerald-300/50 shadow-xs text-xs">
                                                ক্যাশ: <span class="font-mono">৳ {{ toBanglaNum(number_format($vehicleCash, 0)) }}</span>
                                            </div>
                                            <div class="px-3 py-1.5 bg-rose-100 dark:bg-rose-950/80 text-rose-800 dark:text-rose-300 font-extrabold rounded-xl border border-rose-300/50 shadow-xs text-xs">
                                                জের: <span class="font-mono">৳ {{ toBanglaNum(number_format($vehicleCashJer, 0)) }}</span>
                                            </div>
                                        </div>
                                    @elseif($activeTab === 'due')
                                        <div class="flex items-center gap-1.5">
                                            <div class="px-2.5 py-1.5 bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 font-extrabold rounded-xl border border-emerald-200 dark:border-emerald-900/50">
                                                মোট পাবো: <span class="font-mono">৳ {{ toBanglaNum(number_format($vehicleDueGet, 0)) }}</span>
                                            </div>
                                            <div class="px-2.5 py-1.5 bg-rose-100 dark:bg-rose-950 text-rose-800 dark:text-rose-300 font-extrabold rounded-xl border border-rose-200 dark:border-rose-900/50">
                                                মোট পাবে: <span class="font-mono">৳ {{ toBanglaNum(number_format($vehicleDuePay, 0)) }}</span>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Date Picker (placeholder dd/mm/yy) -->
                                    <div x-data="{
                                         fp: null,
                                         init() {
                                             this.fp = flatpickr($refs.tabDateInput, {
                                                 locale: fpLocale,
                                                 dateFormat: 'Y-m-d',
                                                 altInput: true,
                                                 altFormat: 'd-m-Y',
                                                 allowInput: false,
                                                 disableMobile: true,
                                                 defaultDate: $wire.filterDate || '',
                                                 onChange: (dates, str) => {
                                                     $wire.set('filterDate', str);
                                                 }
                                             });
                                             $wire.watch('filterDate', (val) => {
                                                 if (!val) {
                                                     this.fp.clear();
                                                 } else {
                                                     this.fp.setDate(val, false);
                                                 }
                                             });
                                         }
                                     }" class="relative w-36 sm:w-40" wire:ignore>
                                        <input x-ref="tabDateInput" type="text" placeholder="dd/mm/yy" readonly
                                               class="w-full pl-3 pr-8 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white font-mono font-bold text-xs cursor-pointer focus:outline-none">
                                        <span class="absolute right-2.5 top-2.5 text-gray-400 pointer-events-none">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Table with Specific Headings per Tab -->
                        <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl shadow-xs overflow-hidden">
                            <!-- Desktop Table View -->
                            <div class="hidden sm:block overflow-x-auto">
                                <table class="w-full border-collapse text-left text-xs">
                                    <thead>
                                        @if($activeTab === 'income')
                                            <tr class="bg-emerald-50 dark:bg-slate-800/80 text-emerald-800 dark:text-emerald-300 font-bold border-b border-gray-150 dark:border-slate-800">
                                                <th class="py-3 px-4">তারিখ</th>
                                                <th class="py-3 px-4">গাড়ি</th>
                                                <th class="py-3 px-4">বিবরণ</th>
                                                <th class="py-3 px-4 text-right">পরিমাণ</th>
                                                <th class="py-3 px-4 text-right">ভাড়া</th>
                                                <th class="py-3 px-4 text-emerald-600 text-right">পেয়েছি</th>
                                                <th class="py-3 px-4 text-rose-500 text-right">বাকি রইল</th>
                                                <th class="py-3 px-4 text-center">বাটন</th>
                                            </tr>
                                        @elseif($activeTab === 'expense')
                                            <tr class="bg-amber-50 dark:bg-slate-800/80 text-amber-800 dark:text-amber-300 font-bold border-b border-gray-150 dark:border-slate-800">
                                                <th class="py-3 px-4">তারিখ</th>
                                                <th class="py-3 px-4">খতিয়ান</th>
                                                <th class="py-3 px-4">বিবরণ</th>
                                                <th class="py-3 px-4 text-right">পরিমাণ</th>
                                                <th class="py-3 px-4 text-right">বিল</th>
                                                <th class="py-3 px-4 text-emerald-600 text-right">পেমেন্ট</th>
                                                <th class="py-3 px-4 text-rose-500 text-right">বাকি রইল</th>
                                                <th class="py-3 px-4 text-center">বাটন</th>
                                            </tr>
                                        @elseif($activeTab === 'cash')
                                            <tr class="bg-[#009669] text-white font-bold">
                                                <th class="py-3 px-4 text-center w-12">#</th>
                                                <th class="py-3 px-4">ক্যাশের বিবরণ</th>
                                                <th class="py-3 px-4 text-right">ক্যাশ ++</th>
                                                <th class="py-3 px-4 text-right">ক্যাশ --</th>
                                                <th class="py-3 px-4 text-center w-24">বাটন</th>
                                            </tr>
                                        @elseif($activeTab === 'due')
                                            <tr class="bg-rose-50 dark:bg-slate-800/80 text-rose-800 dark:text-rose-300 font-bold border-b border-gray-150 dark:border-slate-800">
                                                <th class="py-3 px-4">তারিখ</th>
                                                <th class="py-3 px-4">বিবরণ</th>
                                                <th class="py-3 px-4 text-emerald-600 text-right">ভাড়া পাবো</th>
                                                <th class="py-3 px-4 text-rose-500 text-right">পেমেন্ট পাবে</th>
                                                <th class="py-3 px-4 text-center">অ্যাকশন</th>
                                            </tr>
                                        @elseif($activeTab === 'history')
                                            <tr class="bg-gray-50 dark:bg-slate-800/80 text-gray-700 dark:text-slate-300 font-bold border-b border-gray-150 dark:border-slate-800">
                                                <th class="py-3 px-4">আইডি</th>
                                                <th class="py-3 px-4">হিসাবের তারিখ</th>
                                                <th class="py-3 px-4">আপডেটের তারিখ</th>
                                                <th class="py-3 px-4">বিবরণ</th>
                                                <th class="py-3 px-4 text-emerald-600 text-right">পেয়েছি</th>
                                                <th class="py-3 px-4 text-rose-500 text-right">দিয়েছি</th>
                                                <th class="py-3 px-4 text-center">বাটন</th>
                                            </tr>
                                        @endif
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                                        @if($activeTab === 'cash')
                                            <!-- Top Fixed Summary Row 1: মোট আয় -->
                                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/40 transition-colors">
                                                <td class="py-2.5 px-4 font-mono font-bold text-center text-gray-500">1</td>
                                                <td class="py-2.5 px-4 font-bold text-[#009669] dark:text-emerald-400">মোট আয়</td>
                                                <td class="py-2.5 px-4 font-mono font-bold text-emerald-600 text-right">৳ {{ toBanglaNum(number_format($vehicleTotalIncome, 0)) }}</td>
                                                <td class="py-2.5 px-4 font-mono text-gray-400 text-right">0</td>
                                                 <td class="py-2.5 px-4 text-center text-gray-400 dark:text-slate-600 select-none">&mdash;</td>
                                            </tr>
                                            <!-- Top Fixed Summary Row 2: মোট ব্যয় -->
                                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/40 transition-colors">
                                                <td class="py-2.5 px-4 font-mono font-bold text-center text-rose-500">2</td>
                                                <td class="py-2.5 px-4 font-bold text-rose-600 dark:text-rose-400">মোট ব্যয়</td>
                                                <td class="py-2.5 px-4 font-mono text-gray-400 text-right">0</td>
                                                <td class="py-2.5 px-4 font-mono font-bold text-rose-500 text-right">৳ {{ toBanglaNum(number_format($vehicleTotalExpense, 0)) }}</td>
                                                 <td class="py-2.5 px-4 text-center text-gray-400 dark:text-slate-600 select-none">&mdash;</td>
                                            </tr>
                                            <!-- Top Fixed Summary Row 3: মোট কালেকশন -->
                                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/40 transition-colors">
                                                <td class="py-2.5 px-4 font-mono font-bold text-center text-emerald-600">3</td>
                                                <td class="py-2.5 px-4 font-bold text-[#009669] dark:text-emerald-400">মোট কালেকশন</td>
                                                <td class="py-2.5 px-4 font-mono font-bold text-emerald-600 text-right">৳ {{ toBanglaNum(number_format($vehicleTotalIncome, 0)) }}</td>
                                                <td class="py-2.5 px-4 font-mono text-gray-400 text-right">0</td>
                                                 <td class="py-2.5 px-4 text-center text-gray-400 dark:text-slate-600 select-none">&mdash;</td>
                                            </tr>
                                            <!-- Top Fixed Summary Row 4: বাকি পেমেন্ট -->
                                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/40 transition-colors">
                                                <td class="py-2.5 px-4 font-mono font-bold text-center text-rose-500">4</td>
                                                <td class="py-2.5 px-4 font-bold text-rose-600 dark:text-rose-400">বাকি পেমেন্ট</td>
                                                <td class="py-2.5 px-4 font-mono text-gray-400 text-right">0</td>
                                                <td class="py-2.5 px-4 font-mono font-bold text-rose-500 text-right">৳ {{ toBanglaNum(number_format($vehicleDuePay, 0)) }}</td>
                                                 <td class="py-2.5 px-4 text-center text-gray-400 dark:text-slate-600 select-none">&mdash;</td>
                                            </tr>

                                            <!-- User Added Dynamic Cash Transactions (Rows 5+) -->
                                            @foreach($vehicleTransactions as $cIndex => $tx)
                                                <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/40 transition-colors bg-sky-950/10">
                                                    <td class="py-2.5 px-4 font-mono font-bold text-center text-sky-400">{{ toBanglaNum(5 + $cIndex) }}</td>
                                                    <td class="py-2.5 px-4 font-bold text-gray-800 dark:text-white">{{ $tx->description ?: ($tx->khotian_name ?: '—') }}</td>
                                                    <td class="py-2.5 px-4 font-mono font-bold text-emerald-600 text-right">{{ $tx->received > 0 ? '৳ ' . toBanglaNum(number_format($tx->received, 0)) : '0' }}</td>
                                                    <td class="py-2.5 px-4 font-mono font-bold text-rose-500 text-right">{{ $tx->rent > 0 ? '৳ ' . toBanglaNum(number_format($tx->rent, 0)) : ($tx->amount > 0 && $tx->received == 0 ? '৳ ' . toBanglaNum(number_format($tx->amount, 0)) : '0') }}</td>
                                                    <td class="py-2.5 px-4 text-center">
                                                        <div class="flex items-center justify-center gap-2">
                                                            <button type="button" wire:click="editTransaction({{ $tx->id }})" class="p-1.5 rounded-lg bg-sky-500/20 text-sky-400 hover:bg-sky-500/30 hover:text-sky-300 cursor-pointer transition-all shadow-xs" title="সম্পাদনা">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                                            </button>
                                                            <button type="button" wire:click="confirmDeleteTransaction({{ $tx->id }})" class="p-1.5 rounded-lg bg-rose-500/20 text-rose-400 hover:bg-rose-500/30 hover:text-rose-300 cursor-pointer transition-all shadow-xs" title="ডিলেট">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            @forelse($vehicleTransactions as $tx)
                                                @php
                                                    $txIsToday = $tx->date ? \Carbon\Carbon::parse($tx->date)->startOfDay()->equalTo(\Carbon\Carbon::today()->startOfDay()) : true;
                                                    $canManageTx = auth()->user()?->isAdmin() || $txIsToday;
                                                @endphp
                                                <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/40 transition-colors">
                                                    @if($activeTab === 'income')
                                                        <td class="py-2.5 px-4 font-mono text-gray-600 dark:text-slate-400 whitespace-nowrap">
                                                            {{ \Carbon\Carbon::parse($tx->created_at ?: $tx->date)->setTimezone('Asia/Dhaka')->format('d-m-Y') }}
                                                            <span class="text-[10px] text-gray-400 font-semibold block">({{ \Carbon\Carbon::parse($tx->created_at ?: $tx->date)->setTimezone('Asia/Dhaka')->format('h:i A') }})</span>
                                                        </td>
                                                        <td class="py-2.5 px-4 font-bold text-emerald-700 dark:text-emerald-400 font-mono">{{ $selectedVehicle ? $selectedVehicle->name : '—' }}</td>
                                                        <td class="py-2.5 px-4 font-bold text-gray-800 dark:text-white">{{ $tx->description ?: ($tx->khotian_name ?: '—') }}</td>
                                                        <td class="py-2.5 px-4 font-mono text-gray-700 dark:text-slate-300 text-right">{{ $tx->quantity ? toBanglaNum($tx->quantity) : '—' }}</td>
                                                        <td class="py-2.5 px-4 font-mono font-bold text-amber-600 dark:text-amber-400 text-right">৳ {{ toBanglaNum(number_format($tx->rent, 0)) }}</td>
                                                        <td class="py-2.5 px-4 font-mono font-bold text-emerald-600 text-right">৳ {{ toBanglaNum(number_format($tx->received, 0)) }}</td>
                                                         <td class="py-2.5 px-4 font-mono font-bold text-rose-500 text-right">{{ $tx->due_amount < 0 ? '- ৳ ' . toBanglaNum(number_format(abs($tx->due_amount), 0)) : '৳ ' . toBanglaNum(number_format($tx->due_amount, 0)) }}</td>
                                                        <td class="py-2.5 px-4 text-center">
                                                            <div class="flex items-center justify-center gap-1.5">
                                                                @if($canManageTx)
                                                                    <button type="button" wire:click="editTransaction({{ $tx->id }})" class="p-1.5 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/60 cursor-pointer transition-all shadow-xs" title="সম্পাদনা">
                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                                                    </button>
                                                                    <button type="button" wire:click="confirmDeleteTransaction({{ $tx->id }})" class="p-1.5 rounded-lg bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900/60 cursor-pointer transition-all shadow-xs" title="ডিলেট">
                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                                    </button>
                                                                @else
                                                                    <button type="button" disabled class="p-1.5 rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-400 dark:text-slate-600 opacity-40 cursor-not-allowed transition-all shadow-xs" title="পেছনের তারিখের হিসাব পরিবর্তন করার পারমিশন নেই">
                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                                                    </button>
                                                                    <button type="button" disabled class="p-1.5 rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-400 dark:text-slate-600 opacity-40 cursor-not-allowed transition-all shadow-xs" title="পেছনের তারিখের হিসাব ডিলেট করার পারমিশন নেই">
                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    @elseif($activeTab === 'expense')
                                                        <td class="py-2.5 px-4 font-mono text-gray-600 dark:text-slate-400 whitespace-nowrap">
                                                            {{ \Carbon\Carbon::parse($tx->created_at ?: $tx->date)->setTimezone('Asia/Dhaka')->format('d-m-Y') }}
                                                            <span class="text-[10px] text-gray-400 font-semibold block">({{ \Carbon\Carbon::parse($tx->created_at ?: $tx->date)->setTimezone('Asia/Dhaka')->format('h:i A') }})</span>
                                                        </td>
                                                        <td class="py-2.5 px-4 font-bold text-amber-700 dark:text-amber-400">{{ $tx->khotian_name ?: '—' }}</td>
                                                        <td class="py-2.5 px-4 font-bold text-gray-800 dark:text-white">{{ $tx->description ?: '—' }}</td>
                                                        <td class="py-2.5 px-4 font-mono text-gray-700 dark:text-slate-300 text-right">{{ $tx->quantity ? toBanglaNum($tx->quantity) : '—' }}</td>
                                                        <td class="py-2.5 px-4 font-mono font-bold text-amber-600 dark:text-amber-400 text-right">৳ {{ toBanglaNum(number_format($tx->rent, 0)) }}</td>
                                                        <td class="py-2.5 px-4 font-mono font-bold text-emerald-600 text-right">৳ {{ toBanglaNum(number_format($tx->received, 0)) }}</td>
                                                         <td class="py-2.5 px-4 font-mono font-bold text-rose-500 text-right">{{ $tx->due_amount < 0 ? '- ৳ ' . toBanglaNum(number_format(abs($tx->due_amount), 0)) : '৳ ' . toBanglaNum(number_format($tx->due_amount, 0)) }}</td>
                                                        <td class="py-2.5 px-4 text-center">
                                                            <div class="flex items-center justify-center gap-1.5">
                                                                @if($canManageTx)
                                                                    <button type="button" wire:click="editTransaction({{ $tx->id }})" class="p-1.5 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/60 cursor-pointer transition-all shadow-xs" title="সম্পাদনা">
                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                                                    </button>
                                                                    <button type="button" wire:click="confirmDeleteTransaction({{ $tx->id }})" class="p-1.5 rounded-lg bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900/60 cursor-pointer transition-all shadow-xs" title="ডিলেট">
                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                                    </button>
                                                                @else
                                                                    <button type="button" disabled class="p-1.5 rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-400 dark:text-slate-600 opacity-40 cursor-not-allowed transition-all shadow-xs" title="পেছনের তারিখের হিসাব পরিবর্তন করার পারমিশন নেই">
                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                                                    </button>
                                                                    <button type="button" disabled class="p-1.5 rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-400 dark:text-slate-600 opacity-40 cursor-not-allowed transition-all shadow-xs" title="পেছনের তারিখের হিসাব ডিলেট করার পারমিশন নেই">
                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    @elseif($activeTab === 'due')
                                                        <td class="py-2.5 px-4 font-mono text-gray-600 dark:text-slate-400 whitespace-nowrap">
                                                            {{ \Carbon\Carbon::parse($tx->created_at ?: $tx->date)->setTimezone('Asia/Dhaka')->format('d-m-Y') }}
                                                            <span class="text-[10px] text-gray-400 font-semibold block">({{ \Carbon\Carbon::parse($tx->created_at ?: $tx->date)->setTimezone('Asia/Dhaka')->format('h:i A') }})</span>
                                                        </td>
                                                        <td class="py-2.5 px-4 font-bold text-gray-800 dark:text-white">{{ $tx->description ?: ($tx->khotian_name ?: '—') }}</td>
                                                         <td class="py-2.5 px-4 font-mono font-bold text-emerald-600 text-right">{{ $tx->type === 'income' ? ($tx->due_amount < 0 ? '- ৳ ' . toBanglaNum(number_format(abs($tx->due_amount), 0)) : '৳ ' . toBanglaNum(number_format($tx->due_amount, 0))) : '—' }}</td>
                                                         <td class="py-2.5 px-4 font-mono font-bold text-rose-500 text-right">{{ $tx->type === 'expense' ? ($tx->due_amount < 0 ? '- ৳ ' . toBanglaNum(number_format(abs($tx->due_amount), 0)) : '৳ ' . toBanglaNum(number_format($tx->due_amount, 0))) : '—' }}</td>
                                                        <td class="py-2.5 px-4 text-center">
                                                            <div class="flex items-center justify-center gap-1.5">
                                                                @if($canManageTx)
                                                                    <button type="button" wire:click="editTransaction({{ $tx->id }})" class="p-1.5 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/60 cursor-pointer transition-all shadow-xs" title="সম্পাদনা">
                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                                                    </button>
                                                                    <button type="button" wire:click="confirmDeleteTransaction({{ $tx->id }})" class="p-1.5 rounded-lg bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900/60 cursor-pointer transition-all shadow-xs" title="ডিলেট">
                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                                    </button>
                                                                @else
                                                                    <button type="button" disabled class="p-1.5 rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-400 dark:text-slate-600 opacity-40 cursor-not-allowed transition-all shadow-xs" title="পেছনের তারিখের হিসাব পরিবর্তন করার পারমিশন নেই">
                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                                                    </button>
                                                                    <button type="button" disabled class="p-1.5 rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-400 dark:text-slate-600 opacity-40 cursor-not-allowed transition-all shadow-xs" title="পেছনের তারিখের হিসাব ডিলেট করার পারমিশন নেই">
                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    @elseif($activeTab === 'history')
                                                        <td class="py-2.5 px-4 font-mono font-bold text-gray-500 dark:text-slate-400 whitespace-nowrap">#{{ toBanglaNum($tx->id) }}</td>
                                                        <td class="py-2.5 px-4 font-mono text-gray-600 dark:text-slate-400 whitespace-nowrap">
                                                            {{ \Carbon\Carbon::parse($tx->created_at ?: $tx->date)->setTimezone('Asia/Dhaka')->format('d-m-Y') }}
                                                            <span class="text-[10px] text-gray-400 font-semibold block">({{ \Carbon\Carbon::parse($tx->created_at ?: $tx->date)->setTimezone('Asia/Dhaka')->format('h:i A') }})</span>
                                                        </td>
                                                        <td class="py-2.5 px-4 font-mono text-gray-500 dark:text-slate-400 whitespace-nowrap text-[11px]">{{ \Carbon\Carbon::parse($tx->updated_at)->setTimezone('Asia/Dhaka')->format('d-m-Y h:i A') }}</td>
                                                        <td class="py-2.5 px-4 font-bold text-gray-800 dark:text-white">{{ $tx->description ?: ($tx->khotian_name ?: '—') }}</td>
                                                        <td class="py-2.5 px-4 font-mono font-bold text-emerald-600 text-right">{{ $tx->type !== 'expense' && ($tx->received || $tx->amount) ? '৳ ' . toBanglaNum(number_format($tx->received ?: $tx->amount, 0)) : '—' }}</td>
                                                        <td class="py-2.5 px-4 font-mono font-bold text-rose-500 text-right">{{ $tx->type === 'expense' ? '৳ ' . toBanglaNum(number_format($tx->rent ?: $tx->amount, 0)) : '—' }}</td>
                                                        <td class="py-2.5 px-4 text-center">
                                                             <div class="flex items-center justify-center gap-1.5">
                                                                 @if($canManageTx)
                                                                     <button type="button" wire:click="confirmDeleteTransaction({{ $tx->id }})" class="p-1.5 rounded-lg bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900/60 cursor-pointer transition-all shadow-xs" title="ডিলেট">
                                                                         <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                                     </button>
                                                                 @else
                                                                     <button type="button" disabled class="p-1.5 rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-400 dark:text-slate-600 opacity-40 cursor-not-allowed transition-all shadow-xs" title="পেছনের তারিখের হিসাব ডিলেট করার পারমিশন নেই (শুধুমাত্র অ্যাডমিন করতে পারবে)">
                                                                         <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                                     </button>
                                                                 @endif
                                                             </div>
                                                         </td>
                                                    @endif
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="py-12 text-center text-gray-400 font-semibold">কোনো তথ্য পাওয়া যায়নি।</td>
                                                </tr>
                                            @endforelse
                                        @endif
                                    </tbody>
                                    <!-- Summary Row (Bottom Total Row) -->
                                    <tfoot class="bg-gray-100 dark:bg-slate-800/90 font-black text-gray-900 dark:text-white border-t-2 border-gray-200 dark:border-slate-700">
                                        @if($activeTab === 'income')
                                            <tr>
                                                <td class="py-3 px-4">সর্বমোট:</td>
                                                <td class="py-3 px-4">—</td>
                                                <td class="py-3 px-4">—</td>
                                                <td class="py-3 px-4 text-right font-mono">{{ toBanglaNum($sumQuantity) }}</td>
                                                <td class="py-3 px-4 text-right font-mono text-amber-600">৳ {{ toBanglaNum(number_format($sumRent, 0)) }}</td>
                                                <td class="py-3 px-4 text-right font-mono text-emerald-600">৳ {{ toBanglaNum(number_format($sumReceived, 0)) }}</td>
                                                <td class="py-3 px-4 text-right font-mono text-rose-500">৳ {{ toBanglaNum(number_format($sumDue, 0)) }}</td>
                                                <td class="py-3 px-4">—</td>
                                            </tr>
                                        @elseif($activeTab === 'expense')
                                            <tr>
                                                <td class="py-3 px-4">সর্বমোট:</td>
                                                <td class="py-3 px-4">—</td>
                                                <td class="py-3 px-4">—</td>
                                                <td class="py-3 px-4 text-right font-mono">{{ toBanglaNum($sumQuantity) }}</td>
                                                <td class="py-3 px-4 text-right font-mono text-amber-600">৳ {{ toBanglaNum(number_format($sumRent, 0)) }}</td>
                                                <td class="py-3 px-4 text-right font-mono text-emerald-600">৳ {{ toBanglaNum(number_format($sumReceived, 0)) }}</td>
                                                <td class="py-3 px-4 text-right font-mono text-rose-500">৳ {{ toBanglaNum(number_format($sumDue, 0)) }}</td>
                                                <td class="py-3 px-4">—</td>
                                            </tr>
                                        @elseif($activeTab === 'cash')
                                            <tr>
                                                <td class="py-3 px-4">সর্বমোট:</td>
                                                <td class="py-3 px-4">—</td>
                                                <td class="py-3 px-4 text-right font-mono text-emerald-600">৳ {{ toBanglaNum(number_format($sumCashIn, 0)) }}</td>
                                                <td class="py-3 px-4 text-right font-mono text-rose-500">৳ {{ toBanglaNum(number_format($sumCashOut, 0)) }}</td>
                                                <td class="py-3 px-4">—</td>
                                            </tr>
                                        @elseif($activeTab === 'due')
                                            <tr>
                                                <td class="py-3 px-4">সর্বমোট:</td>
                                                <td class="py-3 px-4">—</td>
                                                <td class="py-3 px-4 text-right font-mono text-emerald-600">৳ {{ toBanglaNum(number_format($sumDueGet, 0)) }}</td>
                                                <td class="py-3 px-4 text-right font-mono text-rose-500">৳ {{ toBanglaNum(number_format($sumDuePay, 0)) }}</td>
                                                <td class="py-3 px-4">—</td>
                                            </tr>
                                        @elseif($activeTab === 'history')
                                            <tr>
                                                <td class="py-3 px-4">সর্বমোট:</td>
                                                <td class="py-3 px-4">—</td>
                                                <td class="py-3 px-4">—</td>
                                                <td class="py-3 px-4">—</td>
                                                <td class="py-3 px-4 text-right font-mono text-emerald-600">৳ {{ toBanglaNum(number_format($sumReceived, 0)) }}</td>
                                                <td class="py-3 px-4 text-right font-mono text-rose-500">৳ {{ toBanglaNum(number_format($sumExpenseAmount, 0)) }}</td>
                                                <td class="py-3 px-4">—</td>
                                            </tr>
                                        @endif
                                    </tfoot>
                                </table>
                            </div>

                            <!-- Mobile Box View (Full Data Cards) -->
                            <div class="block sm:hidden p-4 space-y-3">
                                @forelse($vehicleTransactions as $tx)
                                    @php
                                        $txIsToday = $tx->date ? \Carbon\Carbon::parse($tx->date)->startOfDay()->equalTo(\Carbon\Carbon::today()->startOfDay()) : true;
                                        $canManageTx = auth()->user()?->isAdmin() || $txIsToday;
                                    @endphp
                                    <div class="p-4 bg-gray-50 dark:bg-slate-800/60 rounded-2xl border border-gray-200 dark:border-slate-700 space-y-2 text-xs">
                                        <div class="flex items-center justify-between border-b border-gray-200 dark:border-slate-700 pb-2">
                                            <div>
                                                <span class="font-mono font-bold text-gray-700 dark:text-slate-300 text-xs">
                                                    {{ \Carbon\Carbon::parse($tx->created_at ?: $tx->date)->setTimezone('Asia/Dhaka')->format('d-m-Y') }}
                                                </span>
                                                <span class="text-[10px] text-gray-400 font-semibold block">
                                                    ({{ \Carbon\Carbon::parse($tx->created_at ?: $tx->date)->setTimezone('Asia/Dhaka')->format('h:i A') }})
                                                </span>
                                            </div>
                                            <div class="flex gap-1.5">
                                                @if($activeTab === 'cash')
                                                    <button type="button" wire:click="notifyCashRestriction()" @click="$dispatch('show-toast', { message: 'এই হিসাব ক্যাশ খাতা থেকে পরিবর্তন করা যাবে না' })" class="p-1.5 rounded-lg bg-blue-100 dark:bg-blue-950 text-blue-600 cursor-pointer">✏️</button>
                                                    <button type="button" wire:click="notifyCashRestriction()" @click="$dispatch('show-toast', { message: 'এই হিসাব ক্যাশ খাতা থেকে পরিবর্তন করা যাবে না' })" class="p-1.5 rounded-lg bg-rose-100 dark:bg-rose-950 text-rose-600 cursor-pointer">🗑️</button>
                                                @elseif($activeTab === 'history')
                                                    <button type="button" wire:click="confirmDeleteTransaction({{ $tx->id }})" class="p-1.5 rounded-lg bg-rose-100 dark:bg-rose-950 text-rose-600 cursor-pointer">🗑️</button>
                                                @else
                                                    <button type="button" wire:click="editTransaction({{ $tx->id }})" class="p-1.5 rounded-lg bg-blue-100 dark:bg-blue-950 text-blue-600 cursor-pointer">✏️</button>
                                                    <button type="button" wire:click="confirmDeleteTransaction({{ $tx->id }})" class="p-1.5 rounded-lg bg-rose-100 dark:bg-rose-950 text-rose-600 cursor-pointer">🗑️</button>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="space-y-1">
                                            <div class="font-black text-gray-900 dark:text-white text-xs">
                                                {{ $tx->description ?: ($tx->khotian_name ?: '—') }}
                                            </div>
                                            @if($tx->khotian_name && $tx->khotian_name !== $tx->description)
                                                <div class="text-[11px] font-bold text-amber-600 dark:text-amber-400">
                                                    খতিয়ান: {{ $tx->khotian_name }}
                                                </div>
                                            @endif
                                        </div>

                                        @if($activeTab === 'income')
                                            <div class="grid grid-cols-2 gap-2 pt-1 font-mono text-xs border-t border-gray-150 dark:border-slate-700/60">
                                                <div><span class="text-gray-500">পরিমাণ:</span> <span class="font-bold">{{ $tx->quantity ? toBanglaNum($tx->quantity) : '—' }}</span></div>
                                                <div><span class="text-amber-600 font-bold">ভাড়া:</span> <span class="font-bold text-amber-600">৳{{ toBanglaNum(number_format($tx->rent, 0)) }}</span></div>
                                                <div><span class="text-emerald-600 font-bold">জমা:</span> <span class="font-bold text-emerald-600">৳{{ toBanglaNum(number_format($tx->received, 0)) }}</span></div>
                                                <div><span class="text-rose-500 font-bold">বাকি:</span> <span class="font-bold text-rose-500">৳{{ toBanglaNum(number_format($tx->due_amount, 0)) }}</span></div>
                                            </div>
                                        @elseif($activeTab === 'expense')
                                            <div class="grid grid-cols-2 gap-2 pt-1 font-mono text-xs border-t border-gray-150 dark:border-slate-700/60">
                                                <div><span class="text-gray-500">পরিমাণ:</span> <span class="font-bold">{{ $tx->quantity ? toBanglaNum($tx->quantity) : '—' }}</span></div>
                                                <div><span class="text-amber-600 font-bold">বিল:</span> <span class="font-bold text-amber-600">৳{{ toBanglaNum(number_format($tx->rent, 0)) }}</span></div>
                                                <div><span class="text-emerald-600 font-bold">পেমেন্ট:</span> <span class="font-bold text-emerald-600">৳{{ toBanglaNum(number_format($tx->received, 0)) }}</span></div>
                                                <div><span class="text-rose-500 font-bold">বাকি:</span> <span class="font-bold text-rose-500">৳{{ toBanglaNum(number_format($tx->due_amount, 0)) }}</span></div>
                                            </div>
                                        @elseif($activeTab === 'cash')
                                            <div class="flex items-center justify-between pt-1 font-mono text-xs border-t border-gray-150 dark:border-slate-700/60 font-bold">
                                                <span class="text-emerald-600">ক্যাশ ++: {{ $tx->type === 'income' || $tx->received > 0 ? '৳ ' . toBanglaNum(number_format($tx->received ?: $tx->amount, 0)) : '—' }}</span>
                                                <span class="text-rose-500">ক্যাশ --: {{ $tx->type === 'expense' || ($tx->amount > 0 && $tx->type !== 'income' && $tx->received == 0) ? '৳ ' . toBanglaNum(number_format($tx->amount ?: $tx->rent, 0)) : '—' }}</span>
                                            </div>
                                        @elseif($activeTab === 'due')
                                            <div class="flex items-center justify-between pt-1 font-mono text-xs border-t border-gray-150 dark:border-slate-700/60 font-bold">
                                                <span class="text-emerald-600">ভাড়া পাবো: {{ $tx->type === 'income' ? '৳ ' . toBanglaNum(number_format($tx->due_amount, 0)) : '—' }}</span>
                                                <span class="text-rose-500">পেমেন্ট পাবে: {{ $tx->type === 'expense' ? '৳ ' . toBanglaNum(number_format($tx->due_amount, 0)) : '—' }}</span>
                                            </div>
                                        @elseif($activeTab === 'history')
                                            <div class="flex items-center justify-between pt-1 font-mono text-xs border-t border-gray-150 dark:border-slate-700/60 font-bold">
                                                <span class="text-emerald-600">পেয়েছি: {{ $tx->received ? '৳ ' . toBanglaNum(number_format($tx->received, 0)) : '—' }}</span>
                                                <span class="text-rose-500">দিয়েছি: {{ $tx->type === 'expense' || $tx->amount ? '৳ ' . toBanglaNum(number_format($tx->amount ?: $tx->rent, 0)) : '—' }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="py-8 text-center text-gray-400 font-semibold">কোনো তথ্য পাওয়া যায়নি।</div>
                                @endforelse
                            </div>

                            <!-- Pagination -->
                            @if($vehicleTransactions instanceof \Illuminate\Pagination\LengthAwarePaginator && $vehicleTransactions->hasPages())
                                <div class="px-4 py-3 border-t border-gray-100 dark:border-slate-800">
                                    {{ $vehicleTransactions->links() }}
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- ============================================================ -->
    <!-- MODALS SECTION -->
    <!-- ============================================================ -->

    <!-- Add / Edit Vehicle Modal -->
    @if($showVehicleModal)
        <div class="fixed inset-0 z-[9998] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" wire:click.self="$set('showVehicleModal', false)">
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl w-full max-w-sm p-6 space-y-4 border border-gray-150 dark:border-slate-800">
                <div class="flex items-start justify-between pb-2 border-b border-gray-100 dark:border-slate-800">
                    <h3 class="text-lg font-black text-gray-900 dark:text-white">{{ $editingVehicleId ? 'গাড়ি সম্পাদনা' : 'নতুন গাড়ি যোগ' }}</h3>
                    <button type="button" wire:click="$set('showVehicleModal', false)" class="text-gray-400 hover:text-gray-700 dark:hover:text-white cursor-pointer p-1 rounded-full">✕</button>
                </div>
                <div class="space-y-3 text-xs">
                    <div>
                        <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">গাড়ির নাম</label>
                        <input type="text" wire:model="vehicleName" placeholder="যেমন: car-1, ঢাকা মেট্রো ক-১১-১২৩৪"
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white font-bold focus:outline-none focus:border-emerald-500">
                        @error('vehicleName') <span class="text-rose-500 text-[10px] mt-0.5 block">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <button type="button" wire:click="$set('showVehicleModal', false)"
                            class="flex-1 py-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 hover:bg-gray-100 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-200 dark:hover:text-white font-bold rounded-xl text-xs transition-all cursor-pointer">
                        বাতিল
                    </button>
                    <button type="button" wire:click="saveVehicle()"
                            class="flex-1 py-2.5 bg-[#034C3C] hover:bg-emerald-800 text-white font-bold rounded-xl text-xs transition-all cursor-pointer">
                        সেভ করুন
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Transaction Modal (Income / Expense / Cash / Due) -->
    @if($showTransactionModal)
        <div class="fixed inset-0 z-[9998] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" wire:click.self="$set('showTransactionModal', false)">
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl w-full max-w-md p-6 border border-gray-150 dark:border-slate-800 max-h-[90vh] overflow-y-auto">

                @if($activeTab === 'income')
                    <!-- MODAL TYPE 1: আয়ের হিসাব -->
                    <div class="flex items-start justify-between pb-2 border-b border-gray-100 dark:border-slate-800">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 flex items-center justify-center text-2xl shadow-xs">💰</div>
                            <div>
                                <h3 class="text-lg font-black text-gray-900 dark:text-white">আয়ের হিসাব</h3>
                                <p class="text-xs text-gray-400 font-bold">নতুন আয় বা ভাড়ার তথ্য যোগ করুন</p>
                            </div>
                        </div>
                        <button type="button" wire:click="$set('showTransactionModal', false)" class="text-gray-400 hover:text-gray-700 dark:hover:text-white cursor-pointer p-2 rounded-full bg-gray-100 dark:bg-slate-800 transition-all">✕</button>
                    </div>

                    <div class="space-y-4 text-xs mt-4">
                        <div>
                            <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">গাড়ির হিসাবের বিবরণ</label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-3 text-gray-400">📄</span>
                                <input type="text" wire:model="txDescription" placeholder="বিবরণ লিখুন"
                                       class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white font-bold focus:outline-none focus:border-emerald-500">
                            </div>
                        </div>

                        <div class="p-4 bg-gray-50/70 dark:bg-slate-950/60 border border-gray-150 dark:border-slate-800 rounded-2xl space-y-3">
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block font-bold text-gray-600 dark:text-slate-400 mb-1">পরিমাণ</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-2.5 text-gray-400 font-mono">#</span>
                                        <input type="number" wire:model.live="txQuantity" placeholder="0"
                                               class="w-full pl-7 pr-3 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-gray-800 dark:text-white font-mono font-bold text-sm">
                                    </div>
                                </div>

                                <div>
                                    <label class="block font-bold text-amber-600 dark:text-amber-400 mb-1">মোট ভাড়া</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-2.5 text-amber-600 font-bold">৳</span>
                                        <input type="number" wire:model.live="txRent" placeholder="0"
                                               class="w-full pl-7 pr-3 py-2 rounded-xl border border-amber-200 dark:border-amber-900/40 bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 font-mono font-bold text-sm">
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block font-bold text-emerald-600 dark:text-emerald-400 mb-1">জমা / পেমেন্ট</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-2.5 text-emerald-600 font-bold">৳</span>
                                        <input type="number" wire:model.live="txReceived" placeholder="0"
                                               class="w-full pl-7 pr-3 py-2 rounded-xl border border-emerald-200 dark:border-emerald-900/40 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 font-mono font-bold text-sm">
                                    </div>
                                </div>

                                <div>
                                    <label class="block font-bold text-rose-600 dark:text-rose-400 mb-1">বাকি রইল</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-2.5 text-rose-600 font-bold">৳</span>
                                        @php
                                            $calculatedDue = floatval($txRent) - floatval($txReceived);
                                            $dueValue = $calculatedDue < 0 ? '-' . toBanglaNum(number_format(abs($calculatedDue), 0)) : toBanglaNum(number_format($calculatedDue, 0));
                                        @endphp
                                        <input type="text" value="{{ $dueValue }}" readonly placeholder="0"
                                               class="w-full pl-7 pr-3 py-2 rounded-xl border border-rose-200 dark:border-rose-900/40 bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 font-mono font-bold text-sm">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-4">
                        <button type="button" wire:click="clearTransactionForm()"
                                class="flex-1 py-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 hover:bg-gray-100 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-200 dark:hover:text-white font-bold rounded-xl text-xs transition-all cursor-pointer shadow-xs">
                            ক্লিয়ার
                        </button>
                        <button type="button" wire:click="saveTransaction()"
                                class="flex-1 py-2.5 bg-[#034C3C] hover:bg-emerald-800 text-white font-bold rounded-xl text-xs transition-all cursor-pointer shadow-xs">
                            সেভ করুন
                        </button>
                    </div>

                @elseif($activeTab === 'expense')
                    <!-- MODAL TYPE 2: ব্যয়ের হিসাব -->
                    <div class="flex items-start justify-between pb-2 border-b border-gray-100 dark:border-slate-800">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-amber-100 dark:bg-amber-950/60 text-amber-600 flex items-center justify-center text-2xl shadow-xs">🧮</div>
                            <div>
                                <h3 class="text-lg font-black text-gray-900 dark:text-white">ব্যয়ের হিসাব</h3>
                                <p class="text-xs text-gray-400 font-bold">নতুন খরচ বা বিল যুক্ত করুন</p>
                            </div>
                        </div>
                        <button type="button" wire:click="$set('showTransactionModal', false)" class="text-gray-400 hover:text-gray-700 dark:hover:text-white cursor-pointer p-2 rounded-full bg-gray-100 dark:bg-slate-800 transition-all">✕</button>
                    </div>

                    <div class="space-y-4 text-xs mt-4">
                        <!-- Dynamic Khotian Dropdown with live client-side filtering -->
                        <div>
                            <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">খতিয়ান সিলেক্ট করুন</label>
                            <div
                                x-data="{
                                    open: false,
                                    allItems: @js($khotianList->values()->all()),
                                    get filteredItems() {
                                        const q = ($wire.txKhotianName || '').trim().toLowerCase();
                                        if (!q) return this.allItems;
                                        return this.allItems.filter(n => n.toLowerCase().includes(q));
                                    }
                                }"
                                @click.outside="open = false"
                                class="relative">
                                <div class="relative">
                                    <span class="absolute left-3.5 top-3 text-gray-400 pointer-events-none">👤</span>
                                    <input
                                        type="text"
                                        wire:model.live="txKhotianName"
                                        @focus="open = true"
                                        @click.stop="open = true"
                                        @input="open = true"
                                        placeholder="খতিয়ান নির্বাচন করুন বা নতুন টাইপ করুন..."
                                        class="w-full pl-9 pr-9 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white font-bold focus:outline-none focus:border-emerald-500 cursor-text text-xs">
                                    <button type="button" @click.stop="open = !open" class="absolute right-3 top-3 text-gray-400 hover:text-gray-600 transition-colors">
                                        <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                </div>
                                <div
                                    x-show="open && filteredItems.length > 0"
                                    x-cloak
                                    class="absolute left-0 right-0 mt-1 z-[9999] max-h-48 overflow-y-auto bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl shadow-2xl py-1">
                                    <template x-for="kName in filteredItems" :key="kName">
                                        <button
                                            type="button"
                                            @mousedown.prevent
                                            @click="$wire.set('txKhotianName', kName); open = false;"
                                            class="w-full text-left px-4 py-2 text-xs font-bold hover:bg-emerald-50 dark:hover:bg-slate-800 cursor-pointer text-gray-700 dark:text-slate-200 flex items-center justify-between">
                                            <span x-text="kName"></span>
                                            <span x-show="$wire.txKhotianName === kName" class="text-emerald-600 font-extrabold">✓</span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">গাড়ির হিসাবের বিবরণ</label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-3 text-gray-400">📄</span>
                                <input type="text" wire:model="txDescription" placeholder="বিবরণ লিখুন"
                                       class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white font-bold focus:outline-none focus:border-emerald-500">
                            </div>
                        </div>

                        <div class="p-4 bg-gray-50/70 dark:bg-slate-950/60 border border-gray-150 dark:border-slate-800 rounded-2xl space-y-3">
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block font-bold text-gray-600 dark:text-slate-400 mb-1">পরিমাণ</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-2.5 text-gray-400 font-mono">#</span>
                                        <input type="number" wire:model.live="txQuantity" placeholder="0"
                                               class="w-full pl-7 pr-3 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-gray-800 dark:text-white font-mono font-bold text-sm">
                                    </div>
                                </div>

                                <div>
                                    <label class="block font-bold text-amber-600 dark:text-amber-400 mb-1">মোট বিল</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-2.5 text-amber-600 font-bold">৳</span>
                                        <input type="number" wire:model.live="txRent" placeholder="0"
                                               class="w-full pl-7 pr-3 py-2 rounded-xl border border-amber-200 dark:border-amber-900/40 bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 font-mono font-bold text-sm">
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block font-bold text-emerald-600 dark:text-emerald-400 mb-1">জমা / পেমেন্ট</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-2.5 text-emerald-600 font-bold">৳</span>
                                        <input type="number" wire:model.live="txReceived" placeholder="0"
                                               class="w-full pl-7 pr-3 py-2 rounded-xl border border-emerald-200 dark:border-emerald-900/40 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 font-mono font-bold text-sm">
                                    </div>
                                </div>

                                <div>
                                    <label class="block font-bold text-rose-600 dark:text-rose-400 mb-1">বাকি আছে</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-2.5 text-rose-600 font-bold">৳</span>
                                        @php
                                            $expDue = floatval($txRent) - floatval($txReceived);
                                            $expDueValue = $expDue < 0 ? '-' . toBanglaNum(number_format(abs($expDue), 0)) : toBanglaNum(number_format($expDue, 0));
                                        @endphp
                                        <input type="text" value="{{ $expDueValue }}" readonly placeholder="0"
                                               class="w-full pl-7 pr-3 py-2 rounded-xl border border-rose-200 dark:border-rose-900/40 bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 font-mono font-bold text-sm">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-4">
                        <button type="button" wire:click="clearTransactionForm()"
                                class="flex-1 py-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 hover:bg-gray-100 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-200 dark:hover:text-white font-bold rounded-xl text-xs transition-all cursor-pointer shadow-xs">
                            ক্লিয়ার
                        </button>
                        <button type="button" wire:click="saveTransaction()"
                                class="flex-1 py-2.5 bg-[#034C3C] hover:bg-emerald-800 text-white font-bold rounded-xl text-xs transition-all cursor-pointer shadow-xs">
                            সেভ করুন
                        </button>
                    </div>

                @elseif($activeTab === 'cash')
                    <!-- MODAL TYPE 3: ক্যাশ হিসাব (Image 1 Match) -->
                    <div class="flex items-start justify-between pb-2 border-b border-gray-100 dark:border-slate-800">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-950/80 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl font-bold flex-shrink-0">💼</div>
                            <div>
                                <h3 class="text-base sm:text-lg font-black text-gray-900 dark:text-white">ক্যাশের হিসাব</h3>
                                <p class="text-xs text-gray-400 font-bold">মালিকের লেনদেন রেকর্ড</p>
                            </div>
                        </div>
                        <button type="button" wire:click="$set('showTransactionModal', false)" class="text-gray-400 hover:text-gray-700 dark:hover:text-white cursor-pointer p-2 rounded-full bg-gray-100 dark:bg-slate-800 transition-all text-xs font-bold">✕</button>
                    </div>

                    <div class="space-y-4 text-xs mt-4">
                        <div>
                            <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">হিসাবের বিবরণ</label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-3 text-gray-400">📄</span>
                                <input type="text" wire:model="txDescription" placeholder="বিবরণ লিখুন (যেমন: ব্যাংক জমা)"
                                       class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white font-bold focus:outline-none focus:border-emerald-500">
                            </div>
                        </div>

                        <div class="p-4 bg-gray-50/70 dark:bg-slate-950/60 border border-gray-150 dark:border-slate-800 rounded-2xl">
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block font-bold text-emerald-600 dark:text-emerald-400 mb-1">ক্যাশ জমা (++)</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-2.5 text-emerald-600 font-bold">৳</span>
                                        <input type="number" wire:model.live="txReceived" placeholder="0"
                                               class="w-full pl-7 pr-3 py-2.5 rounded-xl border border-emerald-200 dark:border-emerald-900/40 bg-white dark:bg-slate-900 text-emerald-700 dark:text-emerald-400 font-mono font-bold text-sm focus:outline-none focus:border-emerald-500">
                                    </div>
                                </div>

                                <div>
                                    <label class="block font-bold text-rose-500 dark:text-rose-400 mb-1">ক্যাশ খরচ (--)</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-2.5 text-rose-500 font-bold">৳</span>
                                        <input type="number" wire:model.live="txRent" placeholder="0"
                                               class="w-full pl-7 pr-3 py-2.5 rounded-xl border border-rose-200 dark:border-rose-900/40 bg-white dark:bg-slate-900 text-rose-600 dark:text-rose-400 font-mono font-bold text-sm focus:outline-none focus:border-rose-500">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-4">
                        <button type="button" wire:click="clearTransactionForm()"
                                class="flex-1 py-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 hover:bg-gray-100 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-200 dark:hover:text-white font-bold rounded-xl text-xs transition-all cursor-pointer shadow-xs">
                            ক্লিয়ার
                        </button>
                        <button type="button" wire:click="saveTransaction()"
                                class="flex-1 py-2.5 bg-[#009669] hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition-all cursor-pointer shadow-xs">
                            সেভ করুন
                        </button>
                    </div>

                @elseif($activeTab === 'due')
                    <!-- MODAL TYPE 4: বাকি হিসাব (Images 2 & 3 Match) -->
                    <div class="flex items-start justify-between pb-2 border-b border-gray-100 dark:border-slate-800">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-950/80 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl font-bold flex-shrink-0">💵</div>
                            <div>
                                <h3 class="text-base sm:text-lg font-black text-gray-900 dark:text-white">বাকির হিসাব</h3>
                                <p class="text-xs text-gray-400 font-bold">বাকি বা দেনা-পাওনা আপডেট</p>
                            </div>
                        </div>
                        <button type="button" wire:click="$set('showTransactionModal', false)" class="text-gray-400 hover:text-gray-700 dark:hover:text-white cursor-pointer p-2 rounded-full bg-gray-100 dark:bg-slate-800 transition-all text-xs font-bold">✕</button>
                    </div>

                    <div class="space-y-4 text-xs mt-4">
                        <div>
                            <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">হিসাবের বিবরণ</label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-3 text-gray-400">📄</span>
                                <input type="text" wire:model="txDescription" placeholder="বিবরণ লিখুন"
                                       class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white font-bold focus:outline-none focus:border-emerald-500">
                            </div>
                        </div>

                        <div class="p-4 bg-gray-50/70 dark:bg-slate-950/60 border border-gray-150 dark:border-slate-800 rounded-2xl space-y-3">
                            @if($txDueType === 'income')
                                <!-- Image 2 Layout: ভাড়া পাবো (মোট পাবো vs পেলাম) -->
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block font-bold text-amber-600 dark:text-amber-400 mb-1">মোট পাবো</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-2.5 text-amber-600 font-bold">৳</span>
                                            <input type="number" wire:model.live="txRent" placeholder="0" readonly
                                                   class="w-full pl-7 pr-3 py-2.5 rounded-xl border border-amber-200 dark:border-amber-900/40 bg-amber-50/50 text-amber-700 dark:text-amber-400 font-mono font-bold text-sm">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block font-bold text-emerald-600 dark:text-emerald-400 mb-1">পেলাম (জমা)</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-2.5 text-emerald-600 font-bold">৳</span>
                                            <input type="number" wire:model.live="txReceived" placeholder="0"
                                                   class="w-full pl-7 pr-3 py-2.5 rounded-xl border border-emerald-200 dark:border-emerald-900/40 bg-white dark:bg-slate-900 text-emerald-700 dark:text-emerald-400 font-mono font-bold text-sm focus:outline-none focus:border-emerald-500">
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block font-bold text-rose-600 dark:text-rose-400 mb-1">এখনও বাকি আছে</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-2.5 text-rose-600 font-bold">৳</span>
                                        @php
                                            $remDue = floatval($txRent) - floatval($txReceived);
                                            if ($remDue < 0) $remDue = 0;
                                        @endphp
                                        <input type="text" value="{{ toBanglaNum(number_format($remDue, 0)) }}" readonly
                                               class="w-full pl-7 pr-3 py-2.5 rounded-xl border border-rose-200 dark:border-rose-900/40 bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 font-mono font-bold text-sm">
                                    </div>
                                </div>
                            @else
                                <!-- Image 3 Layout: পেমেন্ট পাবে (মোট পাবে vs দিলাম) -->
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block font-bold text-amber-600 dark:text-amber-400 mb-1">মোট পাবে</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-2.5 text-amber-600 font-bold">৳</span>
                                            <input type="number" wire:model.live="txRent" placeholder="0" readonly
                                                   class="w-full pl-7 pr-3 py-2.5 rounded-xl border border-amber-200 dark:border-amber-900/40 bg-amber-50/50 text-amber-700 dark:text-amber-400 font-mono font-bold text-sm">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block font-bold text-emerald-600 dark:text-emerald-400 mb-1">দিলাম (পরিশোধ)</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-2.5 text-emerald-600 font-bold">৳</span>
                                            <input type="number" wire:model.live="txReceived" placeholder="0"
                                                   class="w-full pl-7 pr-3 py-2.5 rounded-xl border border-emerald-200 dark:border-emerald-900/40 bg-white dark:bg-slate-900 text-emerald-700 dark:text-emerald-400 font-mono font-bold text-sm focus:outline-none focus:border-emerald-500">
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block font-bold text-rose-600 dark:text-rose-400 mb-1">এখনও বাকি আছে</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-2.5 text-rose-600 font-bold">৳</span>
                                        @php
                                            $remDue = floatval($txRent) - floatval($txReceived);
                                            if ($remDue < 0) $remDue = 0;
                                        @endphp
                                        <input type="text" value="{{ toBanglaNum(number_format($remDue, 0)) }}" readonly
                                               class="w-full pl-7 pr-3 py-2.5 rounded-xl border border-rose-200 dark:border-rose-900/40 bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 font-mono font-bold text-sm">
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-4">
                        <button type="button" wire:click="$set('showTransactionModal', false)"
                                class="flex-1 py-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 hover:bg-gray-100 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-200 dark:hover:text-white font-bold rounded-xl text-xs transition-all cursor-pointer shadow-xs">
                            বাতিল
                        </button>
                        <button type="button" wire:click="saveTransaction()"
                                class="flex-1 py-2.5 bg-[#009669] hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition-all cursor-pointer shadow-xs">
                            আপডেট করুন
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteConfirmModal)
        <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" wire:click.self="$set('showDeleteConfirmModal', false)">
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl w-full max-w-sm p-6 space-y-4 border border-gray-150 dark:border-slate-800 text-center">
                <div class="w-12 h-12 rounded-2xl bg-rose-100 dark:bg-rose-950/60 text-rose-600 flex items-center justify-center text-2xl mx-auto">⚠️</div>
                <h3 class="text-base font-black text-gray-900 dark:text-white">হিসাব ডিলেট নিশ্চিতকরণ</h3>
                <p class="text-xs text-gray-500 dark:text-slate-400 font-bold">আপনি কি নিশ্চিত এই হিসাবটি মুছে ফেলতে চান?</p>
                <div class="flex items-center gap-3 pt-2">
                    <button type="button" wire:click="$set('showDeleteConfirmModal', false)"
                            class="flex-1 py-2.5 bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-slate-200 font-bold rounded-xl text-xs hover:bg-gray-200 dark:hover:bg-slate-700 transition-all cursor-pointer">
                        না
                    </button>
                    <button type="button" wire:click="deleteTransaction()"
                            class="flex-1 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl text-xs transition-all cursor-pointer">
                        হ্যাঁ
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Khotian Detail Modal (Redesigned to match image) -->
    @if($showKhotianDetailModal && $selectedKhotianName)
        <div class="fixed inset-0 z-[9998] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" wire:click.self="$set('showKhotianDetailModal', false)">
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl w-full max-w-3xl border border-gray-150 dark:border-slate-800 max-h-[90vh] flex flex-col overflow-hidden">
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-4 sm:p-5 border-b border-gray-100 dark:border-slate-800 flex-shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-950/80 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl font-bold flex-shrink-0">
                            🐷
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-black text-gray-900 dark:text-white">
                                {{ $selectedKhotianName }} @ ({{ $selectedVehicle ? $selectedVehicle->name : '' }})
                            </h3>
                            <p class="text-xs text-gray-400 font-bold">খতিয়ানের বিস্তারিত হিসাব-নিকাশ</p>
                        </div>
                    </div>
                    <button type="button" wire:click="$set('showKhotianDetailModal', false)" class="text-gray-400 hover:text-gray-600 dark:hover:text-white cursor-pointer p-2 rounded-full bg-slate-100 dark:bg-slate-800 transition-all text-xs font-bold">✕</button>
                </div>

                <!-- KPI Summary & Date Filter Row -->
                <div class="p-4 border-b border-gray-100 dark:border-slate-800 flex-shrink-0 space-y-3">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                        <div class="bg-emerald-100/70 dark:bg-emerald-950/50 text-emerald-800 dark:text-emerald-300 font-bold rounded-2xl px-4 py-2.5 text-center border border-emerald-200/50 flex items-center justify-center">
                            পেমেন্ট বাকি: ৳ {{ toBanglaNum(number_format($khotianNetDue > 0 ? $khotianNetDue : 0, 0)) }}
                        </div>
                        <div class="bg-rose-100/70 dark:bg-rose-950/50 text-rose-800 dark:text-rose-300 font-bold rounded-2xl px-4 py-2.5 text-center border border-rose-200/50 flex items-center justify-center">
                            বেশি পেমেন্ট: ৳ {{ toBanglaNum(number_format($khotianNetDue < 0 ? abs($khotianNetDue) : 0, 0)) }}
                        </div>
                        <div x-data="{
                            fpStart: null, fpEnd: null,
                            init() {
                                this.fpStart = flatpickr($refs.kStartDate, {
                                    locale: fpLocale, dateFormat: 'Y-m-d', altInput: true, altFormat: 'd-m-Y',
                                    allowInput: false, disableMobile: true,
                                    onChange: (d, s) => $wire.set('khotianStartDate', s)
                                });
                                this.fpEnd = flatpickr($refs.kEndDate, {
                                    locale: fpLocale, dateFormat: 'Y-m-d', altInput: true, altFormat: 'd-m-Y',
                                    allowInput: false, disableMobile: true,
                                    onChange: (d, s) => $wire.set('khotianEndDate', s)
                                });
                            }
                        }" class="flex items-center gap-2 border border-gray-200 dark:border-slate-700 rounded-2xl px-3 py-1.5 bg-gray-50 dark:bg-slate-950 text-xs" wire:ignore>
                            <input x-ref="kStartDate" type="text" placeholder="শুরু তারিখ" readonly class="bg-transparent text-gray-800 dark:text-white font-mono text-xs cursor-pointer focus:outline-none w-20 text-center">
                            <span class="text-gray-400">➔</span>
                            <input x-ref="kEndDate" type="text" placeholder="শেষ তারিখ" readonly class="bg-transparent text-gray-800 dark:text-white font-mono text-xs cursor-pointer focus:outline-none w-20 text-center">
                            <span class="text-gray-400 ml-auto">📅</span>
                        </div>
                    </div>
                </div>

                <!-- Desktop Table View inside Modal -->
                <div class="hidden sm:block p-4 sm:p-5 overflow-y-auto flex-1 max-h-[60vh]">
                    <div class="border border-gray-200 dark:border-slate-800 rounded-2xl overflow-hidden">
                        <table class="w-full border-collapse text-left text-xs">
                            <thead class="sticky top-0">
                                <tr class="bg-[#009669] text-white font-bold">
                                    <th class="py-3 px-4">তারিখ</th>
                                    <th class="py-3 px-4">খতিয়ান</th>
                                    <th class="py-3 px-4">বিবরণ</th>
                                    <th class="py-3 px-4 text-right">বিল</th>
                                    <th class="py-3 px-4 text-right">পেমেন্ট</th>
                                    <th class="py-3 px-4 text-right">বাকি রইল</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                                @forelse($khotianDetailTransactions as $ktx)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/40 transition-colors">
                                        <td class="py-2.5 px-4 font-mono text-gray-700 dark:text-slate-300 whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($ktx->created_at ?: $ktx->date)->format('d-m-Y') }}
                                            <span class="text-[10px] text-gray-400 font-semibold block">({{ \Carbon\Carbon::parse($ktx->created_at ?: $ktx->date)->format('h:i A') }})</span>
                                        </td>
                                        <td class="py-2.5 px-4 font-bold text-gray-800 dark:text-white">{{ $ktx->khotian_name ?: $selectedKhotianName }}</td>
                                        <td class="py-2.5 px-4 font-bold text-gray-800 dark:text-white">{{ $ktx->description ?: '—' }}</td>
                                        <td class="py-2.5 px-4 font-mono font-bold text-rose-500 text-right">৳ {{ toBanglaNum(number_format($ktx->rent, 0)) }}</td>
                                        <td class="py-2.5 px-4 font-mono font-bold text-emerald-600 text-right">৳ {{ toBanglaNum(number_format($ktx->received, 0)) }}</td>
                                        <td class="py-2.5 px-4 font-mono font-bold text-rose-500 text-right">৳ {{ toBanglaNum(number_format($ktx->due_amount, 0)) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-10 text-center text-gray-400 font-semibold">কোনো লেনদেন পাওয়া যায়নি।</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Mobile Box View inside Modal -->
                <div class="block sm:hidden p-4 space-y-3 overflow-y-auto flex-1 max-h-[60vh]">
                    @forelse($khotianDetailTransactions as $ktx)
                        <div class="p-3.5 bg-gray-50 dark:bg-slate-800/60 rounded-2xl border border-gray-200 dark:border-slate-700 space-y-2 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="font-mono text-gray-500 dark:text-slate-400 text-[11px]">
                                    {{ \Carbon\Carbon::parse($ktx->created_at ?: $ktx->date)->format('d-m-Y (h:i A)') }}
                                </span>
                                <span class="font-bold text-emerald-700 dark:text-emerald-400">{{ $ktx->khotian_name ?: $selectedKhotianName }}</span>
                            </div>
                            <div class="font-bold text-gray-900 dark:text-white">{{ $ktx->description ?: '—' }}</div>
                            <div class="flex justify-between font-mono font-bold">
                                <span class="text-amber-600">বিল: ৳{{ toBanglaNum(number_format($ktx->rent, 0)) }}</span>
                                <span class="text-emerald-600">পেমেন্ট: ৳{{ toBanglaNum(number_format($ktx->received, 0)) }}</span>
                                <span class="text-rose-500">বাকি: ৳{{ toBanglaNum(number_format($ktx->due_amount, 0)) }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-gray-400 font-semibold">কোনো লেনদেন পাওয়া যায়নি।</div>
                    @endforelse
                </div>

                <!-- Modal Footer -->
                <div class="p-4 border-t border-gray-100 dark:border-slate-800 flex-shrink-0 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs font-bold text-gray-700 dark:text-slate-300">
                    <div>
                        পরিমাণ {{ toBanglaNum($khotianTotalQty) }} | বিল ৳ {{ toBanglaNum(number_format($khotianTotalBill, 0)) }} | পেমেন্ট ৳ {{ toBanglaNum(number_format($khotianTotalPayment, 0)) }}
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1.5" x-data="{ openSort: false }" @click.outside="openSort = false">
                            <span class="text-gray-500 font-bold text-xs">প্রতি পেজে:</span>
                            <div class="relative">
                                <button type="button" @click="openSort = !openSort"
                                        class="px-3 py-1.5 rounded-xl border border-emerald-500/40 bg-emerald-950/40 text-emerald-300 font-bold text-xs flex items-center gap-2 shadow-xs cursor-pointer hover:border-emerald-400 transition-all">
                                    <span>{{ toBanglaNum($khotianPerPage) }} / পেজ</span>
                                    <svg class="w-3.5 h-3.5 text-emerald-400 transition-transform" :class="{'rotate-180': openSort}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="openSort" x-cloak
                                     class="absolute right-0 bottom-full mb-1 z-[9999] w-28 bg-[#0B1528] dark:bg-slate-900 border border-slate-700/80 rounded-xl shadow-2xl overflow-hidden py-1">
                                    @foreach([5, 10, 20, 40] as $opt)
                                        <button type="button"
                                                @click="$wire.set('khotianPerPage', {{ $opt }}); openSort = false;"
                                                class="w-full text-left px-3 py-2 text-xs font-bold transition-all flex items-center justify-between cursor-pointer {{ $khotianPerPage == $opt ? 'bg-blue-600 text-white' : 'text-slate-200 hover:bg-slate-800' }}">
                                            <span>{{ toBanglaNum($opt) }} / পেজ</span>
                                            @if($khotianPerPage == $opt)
                                                <span class="text-xs">✓</span>
                                            @endif
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @if($khotianDetailTransactions instanceof \Illuminate\Pagination\LengthAwarePaginator && $khotianDetailTransactions->hasPages())
                            <div>
                                {{ $khotianDetailTransactions->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ===== গাড়ি সেটিংস মোডাল ===== --}}
    <template x-teleport="body">
    <div x-data="{ open: @entangle('showVehicleModal') }"
         x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click.self="open = false; $wire.set('showVehicleModal', false)"
         class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/30 backdrop-blur-xs"
         x-cloak>

        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-gray-100 dark:border-slate-700 w-full max-w-sm overflow-visible"
             x-show="open"
             x-transition:enter="transition ease-out duration-200 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150 transform"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-2">

            {{-- Header Tabs --}}
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-700 px-5 pt-4 pb-0 rounded-t-2xl">
                <div class="flex items-center gap-1">
                    <button type="button"
                            wire:click="$set('activeVehicleModalTab', 'rename')"
                            class="px-4 py-2.5 text-xs font-bold border-b-2 transition-all cursor-pointer
                                {{ $activeVehicleModalTab === 'rename'
                                    ? 'border-emerald-600 text-emerald-700 dark:text-emerald-400'
                                    : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">
                        নাম পরিবর্তন
                    </button>
                    <button type="button"
                            wire:click="$set('activeVehicleModalTab', 'delete')"
                            class="px-4 py-2.5 text-xs font-bold border-b-2 transition-all cursor-pointer
                                {{ $activeVehicleModalTab === 'delete'
                                    ? 'border-red-500 text-red-600 dark:text-red-400'
                                    : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">
                        ডিলেট
                    </button>
                </div>
                <button type="button"
                        @click="open = false; $wire.set('showVehicleModal', false)"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors cursor-pointer p-1 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800 mb-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="p-5 space-y-4" x-data="{ confirmOpen: false }">

                @if($activeVehicleModalTab === 'rename')
                    {{-- গাড়ি ড্রপডাউন --}}
                    <div class="relative" x-data="{ vDropOpen: false, selectedName: '{{ $editVehicleId ? (\App\Models\Vehicle::find($editVehicleId)?->name ?? 'গাড়ি নির্বাচন করুন') : 'গাড়ি নির্বাচন করুন' }}' }">
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">গাড়ি</label>
                        <button type="button" @click="vDropOpen = !vDropOpen"
                                class="w-full flex items-center justify-between py-2.5 px-3 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs font-semibold text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-250/20 cursor-pointer text-left transition-all">
                            <span x-text="selectedName"></span>
                            <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': vDropOpen }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="vDropOpen" @click.away="vDropOpen = false" x-transition
                             class="absolute left-0 right-0 mt-1.5 bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-gray-150 dark:border-slate-800 p-1.5 z-[99999] text-xs flex flex-col"
                             x-cloak>
                            @foreach($vehicles as $v)
                                <button type="button"
                                        @click="selectedName = '{{ $v->name }}'; vDropOpen = false; $wire.call('selectEditVehicle', {{ $v->id }})"
                                        class="w-full text-left px-4 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-lg cursor-pointer
                                            {{ $editVehicleId == $v->id ? 'text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/10' : 'text-gray-700 dark:text-gray-200' }}">
                                    {{ $v->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- নতুন নাম --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">নতুন নাম</label>
                        <input type="text"
                               wire:model="renameVehicleName"
                               placeholder="গাড়ির নতুন নাম লিখুন"
                               class="w-full py-2.5 px-3 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-250/20 transition-all font-semibold">
                    </div>

                    {{-- Buttons --}}
                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100 dark:border-slate-800">
                        <button type="button"
                                @click="open = false; $wire.set('showVehicleModal', false)"
                                class="px-4 py-2 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-200 text-xs font-semibold rounded-xl cursor-pointer transition-all">
                            বাতিল
                        </button>
                        <button type="button"
                                wire:click="updateVehicleName"
                                class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md active:scale-95 cursor-pointer transition-all">
                            নাম পরিবর্তন
                        </button>
                    </div>
                @endif

                @if($activeVehicleModalTab === 'delete')
                    {{-- গাড়ি ড্রপডাউন --}}
                    <div class="relative" x-data="{ vDropOpen: false, selectedName: '{{ $editVehicleId ? (\App\Models\Vehicle::find($editVehicleId)?->name ?? 'গাড়ি নির্বাচন করুন') : 'গাড়ি নির্বাচন করুন' }}' }">
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">গাড়ি</label>
                        <button type="button" @click="vDropOpen = !vDropOpen"
                                class="w-full flex items-center justify-between py-2.5 px-3 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs font-semibold text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-red-400/20 cursor-pointer text-left transition-all">
                            <span x-text="selectedName"></span>
                            <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': vDropOpen }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="vDropOpen" @click.away="vDropOpen = false" x-transition
                             class="absolute left-0 right-0 mt-1.5 bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-gray-150 dark:border-slate-800 p-1.5 z-[99999] text-xs flex flex-col"
                             x-cloak>
                            @foreach($vehicles as $v)
                                <button type="button"
                                        @click="selectedName = '{{ $v->name }}'; vDropOpen = false; $wire.call('selectEditVehicle', {{ $v->id }})"
                                        class="w-full text-left px-4 py-2 hover:bg-red-50 dark:hover:bg-red-950/20 hover:text-red-600 dark:hover:text-red-400 transition-all font-semibold rounded-lg cursor-pointer
                                            {{ $editVehicleId == $v->id ? 'text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-950/10' : 'text-gray-700 dark:text-gray-200' }}">
                                    {{ $v->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    @if($editVehicleId)
                        <div class="p-3 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-900/50 rounded-xl text-xs text-red-700 dark:text-red-400 font-semibold">
                            ⚠️ এই গাড়িটি ডিলেট করলে তার সকল হিসাব স্থায়ীভাবে মুছে যাবে।
                        </div>
                    @endif

                    {{-- Buttons --}}
                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100 dark:border-slate-800">
                        <button type="button"
                                @click="open = false; $wire.set('showVehicleModal', false)"
                                class="px-4 py-2 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-200 text-xs font-semibold rounded-xl cursor-pointer transition-all">
                            বাতিল
                        </button>
                        <button type="button"
                                @click="confirmOpen = true"
                                {{ !$editVehicleId ? 'disabled' : '' }}
                                class="px-5 py-2 bg-red-600 hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed text-white text-xs font-bold rounded-xl shadow-md active:scale-95 cursor-pointer transition-all">
                            ডিলেট করুন
                        </button>
                    </div>

                    {{-- হ্যাঁ/না Confirm Overlay Popup --}}
                    <template x-teleport="body">
                        <div x-show="confirmOpen"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="fixed inset-0 bg-black/30 backdrop-blur-xs flex items-center justify-center p-4 z-[10000]"
                             x-cloak>
                            <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-700 rounded-2xl p-5 w-full max-w-xs flex flex-col items-center gap-3 text-center shadow-2xl">
                                <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-950/30 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800 dark:text-white">নিশ্চিত করুন</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">এই গাড়ির সকল হিসাব মুছে যাবে!</p>
                                </div>
                                <div class="flex gap-2.5 w-full justify-center mt-1">
                                    <button type="button" @click="confirmOpen = false"
                                            class="px-5 py-2 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-700 dark:text-gray-200 text-xs font-bold rounded-xl cursor-pointer transition-all">
                                        না
                                    </button>
                                    <button type="button"
                                            @click="confirmOpen = false; $wire.call('deleteVehicle')"
                                            class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl shadow-sm cursor-pointer transition-all active:scale-95">
                                        হ্যাঁ, ডিলেট করুন
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                @endif

            </div>
        </div>
    </div>
    </template>

    {{-- ===== হ্যাঁ/না ডিলেট কনফার্ম মোডাল (হিসাব ডিলেট) ===== --}}
    <template x-teleport="body">
    <div x-data="{ open: @entangle('showDeleteConfirmModal') }"
         x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click.self="open = false; $wire.set('showDeleteConfirmModal', false)"
         class="fixed inset-0 z-[9998] flex items-center justify-center p-4 bg-black/30 backdrop-blur-xs"
         x-cloak>
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-gray-100 dark:border-slate-700 w-full max-w-xs p-5 flex flex-col items-center gap-3 text-center"
             x-show="open"
             x-transition:enter="transition ease-out duration-200 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150 transform"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-2">
            <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-950/30 flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-800 dark:text-white">হিসাব ডিলেট করবেন?</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">এই হিসাবটি স্থায়ীভাবে মুছে যাবে।</p>
            </div>
            <div class="flex gap-2.5 w-full justify-center mt-1">
                <button type="button"
                        @click="open = false; $wire.set('showDeleteConfirmModal', false)"
                        class="px-5 py-2 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-700 dark:text-gray-200 text-xs font-bold rounded-xl cursor-pointer transition-all">
                    না
                </button>
                <button type="button"
                        wire:click="deleteTransaction"
                        class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl shadow-sm cursor-pointer transition-all active:scale-95">
                    হ্যাঁ, ডিলেট করুন
                </button>
            </div>
        </div>
    </div>
    </template>
</div>
