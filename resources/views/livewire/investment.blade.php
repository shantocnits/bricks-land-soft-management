@php
if (!function_exists('toBanglaNum')) {
    function toBanglaNum($num) {
        $eng = ['0','1','2','3','4','5','6','7','8','9',',','.'];
        $bn  = ['০','১','২','৩','৪','৫','৬','৭','৮','৯',',','.'];
        return str_replace($eng, $bn, $num);
    }
}
@endphp

<div class="min-h-screen bg-gray-50 dark:bg-slate-950 transition-colors duration-300 pb-12 font-sans relative">
    
    <!-- Top-Center Floating Toast Notification System (1.5 - 2 sec auto dismiss) -->
    <div x-data="{ show: false, message: '', type: 'success' }"
         x-init="
            window.addEventListener('show-toast', e => {
                message = e.detail.message;
                type = e.detail.type || 'success';
                show = true;
                setTimeout(() => show = false, 2000);
            });
         "
         x-show="show"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         x-cloak
         class="fixed top-5 left-1/2 -translate-x-1/2 z-[9999] px-5 py-3 rounded-2xl shadow-2xl flex items-center gap-2.5 font-sans font-bold text-xs border backdrop-blur-md"
         :class="type === 'success' ? 'bg-[#034C3C] text-white border-emerald-400/40' : 'bg-rose-600 text-white border-rose-400/40'">
        <span class="w-5 h-5 rounded-full flex items-center justify-center text-xs font-black"
              :class="type === 'success' ? 'bg-emerald-500/30 text-emerald-300' : 'bg-white/20 text-white'">
            <span x-text="type === 'success' ? '✓' : '✕'"></span>
        </span>
        <span x-text="message"></span>
    </div>

    <div class="w-full space-y-6">
        
        <!-- Summary Cards (2-cols on mobile, 4-cols on desktop) -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            <!-- Card 1: Total Investment -->
            <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold text-gray-500 dark:text-slate-400 uppercase">মোট ইনভেস্টমেন্ট</span>
                        <h3 class="text-xl sm:text-2xl font-black text-[#034C3C] dark:text-emerald-400 font-mono mt-1">
                            ৳{{ $this->formatMoney($totalInvested) }}
                        </h3>
                    </div>
                    <div class="p-3 bg-emerald-50 dark:bg-emerald-950/40 text-[#034C3C] dark:text-emerald-400 rounded-2xl border border-emerald-100 dark:border-emerald-900/50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Card 2: Total Repaid / Profit -->
            <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold text-gray-500 dark:text-slate-400 uppercase">পরিশোধিত লভ্যাংশ</span>
                        <h3 class="text-xl sm:text-2xl font-black text-emerald-600 dark:text-emerald-400 font-mono mt-1">
                            ৳{{ $this->formatMoney($totalRepaid) }}
                        </h3>
                    </div>
                    <div class="p-3 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 rounded-2xl border border-emerald-100 dark:border-emerald-900/50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Card 3: Remaining Balance -->
            <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold text-gray-500 dark:text-slate-400 uppercase">অবশিষ্ট ইনভেস্টমেন্ট</span>
                        <h3 class="text-xl sm:text-2xl font-black text-[#034C3C] dark:text-emerald-400 font-mono mt-1">
                            ৳{{ $this->formatMoney($netBalance) }}
                        </h3>
                    </div>
                    <div class="p-3 bg-emerald-50 dark:bg-emerald-950/40 text-[#034C3C] dark:text-emerald-400 rounded-2xl border border-emerald-100 dark:border-emerald-900/50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Card 4: Total Investors -->
            <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold text-gray-500 dark:text-slate-400 uppercase">মোট ইনভেস্টর সংখ্যা</span>
                        <h3 class="text-xl sm:text-2xl font-black text-sky-600 dark:text-sky-400 font-mono mt-1">
                            {{ toBanglaNum($totalInvestorsCount) }} জন
                        </h3>
                    </div>
                    <div class="p-3 bg-sky-50 dark:bg-sky-950/40 text-sky-600 dark:text-sky-400 rounded-2xl border border-sky-100 dark:border-sky-900/50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 1: Total Business Profit Card & Live State -->
        <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl p-5 shadow-sm space-y-3">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                <div class="flex items-center gap-2.5">
                    <div class="p-2.5 bg-emerald-50 dark:bg-emerald-950/50 text-[#034C3C] dark:text-emerald-400 rounded-2xl border border-emerald-100 dark:border-emerald-900/50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-extrabold text-gray-800 dark:text-white">ভাটার মোট লাভ (৳)</h4>
                        <p class="text-[11px] text-gray-500 dark:text-slate-400 font-semibold">ব্যবসার মোট অর্জিত প্রফিটের পরিমাণ ইনপুট দিন</p>
                    </div>
                </div>

                <!-- Input Box & Lock / Edit Toggle -->
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <div class="relative flex-1 sm:w-60">
                        <input type="number" step="0.01" min="0" wire:model.live="totalBusinessProfit"
                               @if($isTotalProfitLocked) disabled @endif
                               placeholder="0"
                               x-on:input="$el.value = $el.value.replace(/[^0-9.]/g, '')"
                               class="w-full px-3.5 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 font-mono font-bold text-xs disabled:opacity-75 disabled:bg-gray-100 dark:disabled:bg-slate-800">
                    </div>

                    @if($isTotalProfitLocked)
                        <button type="button" wire:click="editTotalProfit"
                                class="px-3.5 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-bold text-xs flex items-center gap-1.5 transition-all cursor-pointer shadow-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            <span>এডিট</span>
                        </button>
                    @else
                        <button type="button" wire:click="saveTotalProfit"
                                class="px-3.5 py-2 bg-[#034C3C] hover:bg-emerald-800 text-white rounded-xl font-bold text-xs flex items-center gap-1.5 transition-all cursor-pointer shadow-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span>সেভ / লক</span>
                        </button>
                    @endif
                </div>
            </div>

            <!-- Dynamic Remaining Profit Badge -->
            <div class="flex items-center justify-between pt-2 border-t border-gray-100 dark:border-slate-800 text-xs">
                <span class="font-bold text-gray-600 dark:text-slate-400">এই পর্যন্ত প্রদত্ত মোট লাভ বাদ দিয়ে:</span>
                <div class="flex items-center gap-2">
                    <span class="font-bold text-gray-500 dark:text-slate-400">অবশিষ্ট লাভ:</span>
                    <span class="font-black font-mono text-sm px-3 py-1 rounded-xl bg-emerald-100 dark:bg-emerald-950 text-[#034C3C] dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                        ৳{{ $this->formatMoney($this->runningRemainingProfit) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Filter & Tab Toolbar -->
        <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl p-4 sm:p-5 shadow-sm space-y-4">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                
                <!-- Tab Switcher -->
                <div class="flex items-center gap-1.5 bg-gray-100 dark:bg-slate-800 p-1 rounded-xl text-xs font-bold">
                    <button type="button" wire:click="$set('activeTab', 'investors')"
                            class="px-4 py-2 rounded-lg transition-all cursor-pointer {{ $activeTab === 'investors' ? 'bg-[#034C3C] text-white shadow-sm' : 'text-gray-600 dark:text-slate-300 hover:bg-gray-200 dark:hover:bg-slate-700' }}">
                        ইনভেস্টর তালিকা
                    </button>
                    <button type="button" wire:click="$set('activeTab', 'transactions')"
                            class="px-4 py-2 rounded-lg transition-all cursor-pointer {{ $activeTab === 'transactions' ? 'bg-[#034C3C] text-white shadow-sm' : 'text-gray-600 dark:text-slate-300 hover:bg-gray-200 dark:hover:bg-slate-700' }}">
                        লেনদেন ইতিহাস
                    </button>
                </div>

                <!-- Search & Action Buttons -->
                <div class="flex flex-wrap items-center gap-2.5 w-full sm:w-auto">
                    <div class="relative flex-grow sm:w-56">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="ইনভেস্টর নাম বা ফোন দিয়ে খুঁজুন..."
                               class="w-full pl-4 pr-9 py-2 text-xs font-semibold rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white placeholder-gray-400 focus:outline-none focus:border-emerald-500 transition-all">
                        <span class="absolute right-3 top-2.5 text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                    </div>

                    <button type="button" wire:click="openInvestorModal()"
                            class="px-3.5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold rounded-xl shadow-xs transition-all cursor-pointer flex items-center gap-1.5 whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>নতুন ইনভেস্টর</span>
                    </button>

                    <button type="button" wire:click="openTransactionModal()"
                            class="px-3.5 py-2 bg-[#034C3C] hover:bg-emerald-900 text-white text-xs font-bold rounded-xl shadow-xs transition-all cursor-pointer flex items-center gap-1.5 whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span>ইনভেস্টমেন্ট জমা / লভ্যাংশ</span>
                    </button>

                    <!-- Custom Root Dropdown for Transaction Types -->
                    @if($activeTab === 'transactions')
                        <div x-data="{ open: false }" class="relative text-xs">
                            <button @click="open = !open" type="button"
                                    class="flex items-center justify-between gap-2 px-3.5 py-2 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white font-bold rounded-xl border border-gray-200 dark:border-slate-700 cursor-pointer min-w-[130px]">
                                <span>
                                    @if($typeFilter === 'all') সকল লেনদেন
                                    @elseif($typeFilter === 'deposit') বিনিয়োগ জমা
                                    @elseif($typeFilter === 'profit_payout') লাভ প্রদান
                                    @elseif($typeFilter === 'capital_return') মূলধন ফেরত
                                    @endif
                                </span>
                                <svg class="w-3.5 h-3.5 transition-transform ml-1" :class="{'rotate-180': open}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" @click.outside="open = false" x-cloak
                                 class="absolute right-0 mt-1.5 z-[999] w-40 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl shadow-xl overflow-hidden py-1">
                                <button type="button" wire:click="selectTypeFilter('all')" @click="open = false" class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-200 font-bold flex items-center justify-between cursor-pointer">
                                    <span>সকল লেনদেন</span>
                                    @if($typeFilter === 'all') <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> @endif
                                </button>
                                <button type="button" wire:click="selectTypeFilter('deposit')" @click="open = false" class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-200 font-bold flex items-center justify-between cursor-pointer">
                                    <span>বিনিয়োগ জমা</span>
                                    @if($typeFilter === 'deposit') <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> @endif
                                </button>
                                <button type="button" wire:click="selectTypeFilter('profit_payout')" @click="open = false" class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-200 font-bold flex items-center justify-between cursor-pointer">
                                    <span>লাভ প্রদান</span>
                                    @if($typeFilter === 'profit_payout') <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> @endif
                                </button>
                                <button type="button" wire:click="selectTypeFilter('capital_return')" @click="open = false" class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-200 font-bold flex items-center justify-between cursor-pointer">
                                    <span>মূলধন ফেরত</span>
                                    @if($typeFilter === 'capital_return') <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> @endif
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Table View (Desktop) -->
        <div class="hidden md:block bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left border border-gray-200 dark:border-slate-800">
                    <thead>
                        <tr class="bg-[#034C3C] text-white text-[11px] font-bold uppercase">
                            @if($activeTab === 'investors')
                                <th class="py-3.5 px-4 text-center border-r border-white/20 w-12">#</th>
                                <th class="py-3.5 px-4 border-r border-white/20">ইনভেস্টরের নাম</th>
                                <th class="py-3.5 px-4 border-r border-white/20">ফোন</th>
                                <th class="py-3.5 px-4 border-r border-white/20">ঠিকানা</th>
                                <th class="py-3.5 px-4 text-center border-r border-white/20">লাভ %</th>
                                <th class="py-3.5 px-4 text-right border-r border-white/20">মোট বিনিয়োগ</th>
                                <th class="py-3.5 px-4 text-right border-r border-white/20">মোট লভ্যাংশ</th>
                                <th class="py-3.5 px-4 text-right border-r border-white/20">অবশিষ্ট মূলধন (পাওনা)</th>
                                <th class="py-3.5 px-4 text-center">অ্যাকশন</th>
                            @else
                                <th class="py-3.5 px-4 text-center border-r border-white/20 w-12">#</th>
                                <th class="py-3.5 px-4 border-r border-white/20">তারিখ</th>
                                <th class="py-3.5 px-4 border-r border-white/20">ইনভেস্টর</th>
                                <th class="py-3.5 px-4 text-center border-r border-white/20">লেনদেনের ধরণ</th>
                                <th class="py-3.5 px-4 text-center border-r border-white/20">পেমেন্ট মেথড</th>
                                <th class="py-3.5 px-4 text-right border-r border-white/20">পরিমাণ</th>
                                <th class="py-3.5 px-4 border-r border-white/20">নোট</th>
                                <th class="py-3.5 px-4 text-center">অ্যাকশন</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800 text-xs font-semibold">
                        @if($activeTab === 'investors')
                            @forelse($records as $index => $inv)
                                @php
                                    $activeSeason = \App\Models\Setting::get('season', '২৫-২৬');
                                    $invTotalInvest = \App\Models\InvestmentTransaction::where('investor_id', $inv->id)->where('season', $activeSeason)->where('transaction_type', 'deposit')->sum('amount');
                                    if ($invTotalInvest == 0 && $activeSeason === '২৫-২৬' && $inv->total_invested > 0) $invTotalInvest = $inv->total_invested;
                                    $invProfitPaid = \App\Models\InvestmentTransaction::where('investor_id', $inv->id)->where('season', $activeSeason)->where('transaction_type', 'profit_payout')->sum('amount');
                                    $invCapReturn = \App\Models\InvestmentTransaction::where('investor_id', $inv->id)->where('season', $activeSeason)->where('transaction_type', 'capital_return')->sum('amount');
                                    $rem = max(0, $invTotalInvest - $invCapReturn);
                                @endphp
                                <tr class="hover:bg-emerald-50/30 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="py-3.5 px-4 text-center text-gray-500 font-bold border-r border-gray-150 dark:border-slate-800">
                                        {{ toBanglaNum($records->firstItem() + $index) }}
                                    </td>
                                    <td class="py-3.5 px-4 font-extrabold text-gray-900 dark:text-white border-r border-gray-150 dark:border-slate-800">
                                        {{ $inv->name }}
                                    </td>
                                    <td class="py-3.5 px-4 font-mono text-gray-700 dark:text-slate-300 border-r border-gray-150 dark:border-slate-800">
                                        {{ toBanglaNum($inv->phone ?: '—') }}
                                    </td>
                                    <td class="py-3.5 px-4 text-gray-600 dark:text-slate-400 border-r border-gray-150 dark:border-slate-800">
                                        {{ $inv->address ?: '—' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center font-bold text-emerald-700 dark:text-emerald-400 border-r border-gray-150 dark:border-slate-800">
                                        {{ $inv->profit_percentage > 0 ? toBanglaNum($inv->profit_percentage) . '%' : '০%' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-right font-black text-[#034C3C] dark:text-emerald-400 font-mono border-r border-gray-150 dark:border-slate-800">
                                        ৳{{ $this->formatMoney($invTotalInvest) }}
                                    </td>
                                    <td class="py-3.5 px-4 text-right font-bold text-emerald-600 font-mono border-r border-gray-150 dark:border-slate-800">
                                        ৳{{ $this->formatMoney($invProfitPaid) }}
                                    </td>
                                    <td class="py-3.5 px-4 text-right font-black text-rose-500 font-mono border-r border-gray-150 dark:border-slate-800">
                                        ৳{{ $this->formatMoney($rem) }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <button type="button" wire:click="openInvestorModal({{ $inv->id }})" title="এডিট করুন"
                                                    class="p-1.5 rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-slate-200 hover:bg-emerald-600 hover:text-white dark:hover:bg-emerald-600 dark:hover:text-white transition-all cursor-pointer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </button>
                                            @if($this->isAdmin())
                                                <button type="button" wire:click="confirmDeleteInvestor({{ $inv->id }})" title="মুছে ফেলুন"
                                                        class="p-1.5 rounded-lg bg-rose-50 dark:bg-slate-800 text-rose-600 dark:text-rose-400 hover:bg-rose-600 hover:text-white dark:hover:bg-rose-600 dark:hover:text-white transition-all cursor-pointer">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="py-12 text-center text-gray-400 font-semibold text-xs">
                                        কোনো ইনভেস্টর তথ্য পাওয়া যায়নি।
                                    </td>
                                </tr>
                            @endforelse
                        @else
                            @forelse($records as $index => $t)
                                <tr class="hover:bg-emerald-50/30 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="py-3.5 px-4 text-center text-gray-500 font-bold border-r border-gray-150 dark:border-slate-800">
                                        {{ toBanglaNum($records->firstItem() + $index) }}
                                    </td>
                                    <td class="py-3.5 px-4 font-semibold text-gray-800 dark:text-slate-200 border-r border-gray-150 dark:border-slate-800 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($t->date)->format('d-m-Y') }}
                                    </td>
                                    <td class="py-3.5 px-4 font-extrabold text-gray-900 dark:text-white border-r border-gray-150 dark:border-slate-800">
                                        {{ $t->investor ? $t->investor->name : '—' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center border-r border-gray-150 dark:border-slate-800">
                                        <span class="inline-flex px-2 py-0.5 rounded-lg text-[10px] font-extrabold 
                                            {{ $t->transaction_type === 'deposit' ? 'bg-emerald-100 text-emerald-800' : ($t->transaction_type === 'profit_payout' ? 'bg-sky-100 text-sky-800' : 'bg-amber-100 text-amber-800') }}">
                                            {{ $t->transaction_type === 'deposit' ? 'বিনিয়োগ জমা' : ($t->transaction_type === 'profit_payout' ? 'লাভ প্রদান' : 'মূলধন ফেরত') }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-center text-gray-600 dark:text-slate-400 border-r border-gray-150 dark:border-slate-800 font-bold">
                                        {{ $t->payment_method }}
                                    </td>
                                    <td class="py-3.5 px-4 text-right font-black font-mono border-r border-gray-150 dark:border-slate-800 {{ $t->transaction_type === 'deposit' ? 'text-[#034C3C] dark:text-emerald-400' : 'text-rose-500' }}">
                                        ৳{{ $this->formatMoney($t->amount) }}
                                    </td>
                                    <td class="py-3.5 px-4 text-gray-500 dark:text-slate-400 border-r border-gray-150 dark:border-slate-800">
                                        {{ $t->notes ?: '—' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        <button type="button" wire:click="confirmDeleteTransaction({{ $t->id }})" title="মুছে ফেলুন"
                                                class="p-1.5 rounded-lg bg-rose-50 dark:bg-slate-800 text-rose-600 dark:text-rose-400 hover:bg-rose-600 hover:text-white dark:hover:bg-rose-600 dark:hover:text-white transition-all cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-12 text-center text-gray-400 font-semibold text-xs">
                                        কোনো লেনদেন রেকর্ড পাওয়া যায়নি।
                                    </td>
                                </tr>
                            @endforelse
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Bottom Toolbar & Pagination -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 px-5 py-4 border-t border-gray-150 dark:border-slate-800 bg-white dark:bg-slate-900">
                <div class="text-xs text-gray-500 dark:text-gray-400 font-semibold">
                    মোট রেকর্ড: <strong class="text-gray-800 dark:text-white">{{ toBanglaNum($records->total()) }} টি</strong>
                </div>

                <div class="flex items-center gap-4">
                    {{ $records->links() }}

                    <div x-data="{ open: false }" class="relative text-xs">
                        <button @click="open = !open" type="button"
                                class="flex items-center gap-1.5 px-3 py-1.5 bg-white dark:bg-slate-800 text-gray-800 dark:text-white font-bold rounded-lg border border-gray-200 dark:border-slate-700 cursor-pointer">
                            <span>{{ toBanglaNum($perPage) }} / পেজ</span>
                            <svg class="w-3.5 h-3.5 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" @click.outside="open = false" x-cloak
                             class="absolute bottom-full mb-1.5 right-0 z-[999] w-32 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl shadow-xl overflow-hidden py-1">
                            @foreach([5, 10, 15, 20, 50] as $size)
                                <button type="button" wire:click="$set('perPage', {{ $size }})" @click="open = false"
                                        class="w-full text-left px-3 py-2 text-xs font-bold text-gray-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-slate-800">
                                    {{ toBanglaNum($size) }} / পেজ
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Card View -->
        <div class="block md:hidden space-y-4">
            @if($activeTab === 'investors')
                @forelse($records as $inv)
                    @php
                        $invTotalInvest = \App\Models\InvestmentTransaction::where('investor_id', $inv->id)->where('transaction_type', 'deposit')->sum('amount');
                        if ($invTotalInvest == 0 && $inv->total_invested > 0) $invTotalInvest = $inv->total_invested;
                        $invProfitPaid = \App\Models\InvestmentTransaction::where('investor_id', $inv->id)->where('transaction_type', 'profit_payout')->sum('amount');
                        $invCapReturn = \App\Models\InvestmentTransaction::where('investor_id', $inv->id)->where('transaction_type', 'capital_return')->sum('amount');
                        $rem = max(0, $invTotalInvest - $invCapReturn);
                    @endphp
                    <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl p-5 shadow-sm space-y-3">
                        <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-800 pb-3">
                            <div>
                                <h4 class="font-extrabold text-gray-900 dark:text-white text-sm">{{ $inv->name }}</h4>
                                <span class="text-xs text-gray-500 dark:text-slate-400 font-mono">{{ toBanglaNum($inv->phone) }}</span>
                            </div>
                            <span class="text-xs font-bold px-2.5 py-0.5 rounded-lg bg-emerald-100 text-emerald-800">
                                লাভ: {{ toBanglaNum($inv->profit_percentage) }}%
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-xs font-mono">
                            <div>
                                <span class="text-gray-400 text-[10px] block">মোট বিনিয়োগ:</span>
                                <span class="font-black text-[#034C3C] dark:text-emerald-400">৳{{ $this->formatMoney($invTotalInvest) }}</span>
                            </div>
                            <div>
                                <span class="text-gray-400 text-[10px] block">মোট লভ্যাংশ:</span>
                                <span class="font-bold text-emerald-600">৳{{ $this->formatMoney($invProfitPaid) }}</span>
                            </div>
                        </div>
                        <div class="pt-2 border-t border-gray-100 dark:border-slate-800 flex items-center justify-between">
                            <div>
                                <span class="text-gray-400 text-[10px] block">অবশিষ্ট মূলধন</span>
                                <span class="font-black text-rose-500 font-mono text-sm">৳{{ $this->formatMoney($rem) }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-slate-900 border border-gray-150 rounded-3xl p-8 text-center text-gray-400 font-semibold text-xs">
                        কোনো ইনভেস্টর পাওয়া যায়নি।
                    </div>
                @endforelse
            @else
                @forelse($records as $t)
                    <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl p-5 shadow-sm space-y-3">
                        <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-800 pb-2">
                            <span class="font-extrabold text-gray-900 dark:text-white text-xs">{{ $t->investor ? $t->investor->name : '—' }}</span>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-slate-300">
                                {{ \Carbon\Carbon::parse($t->date)->format('d-m-Y') }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-xs font-mono">
                            <span class="font-bold text-gray-600 dark:text-slate-400">{{ $t->payment_method }}</span>
                            <span class="font-black text-sm {{ $t->transaction_type === 'deposit' ? 'text-[#034C3C] dark:text-emerald-400' : 'text-rose-500' }}">
                                ৳{{ $this->formatMoney($t->amount) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-slate-900 border border-gray-150 rounded-3xl p-8 text-center text-gray-400 font-semibold text-xs">
                        কোনো লেনদেন পাওয়া যায়নি।
                    </div>
                @endforelse
            @endif

            <div class="pt-2">
                {{ $records->links() }}
            </div>
        </div>

    </div>

    <!-- Investor Add/Edit Modal -->
    @if($showInvestorModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs"
             wire:click.self="$set('showInvestorModal', false)">
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-gray-150 dark:border-slate-800 w-full max-w-md p-6 space-y-4"
                 x-data="{ activeDropdown: null }"
                 wire:click.stop>
                <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-800 pb-3">
                    <h3 class="text-base font-extrabold text-gray-800 dark:text-white">
                        {{ $editingInvestorId ? 'ইনভেস্টর তথ্য এডিট' : 'নতুন ইনভেস্টর যুক্ত করুন' }}
                    </h3>
                    <button type="button" wire:click="$set('showInvestorModal', false)" class="text-gray-400 hover:text-gray-600 cursor-pointer">
                        ✕
                    </button>
                </div>

                <div class="space-y-3 text-xs">
                    <div>
                        <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">ইনভেস্টরের নাম *</label>
                        <input type="text" wire:model="investorName" placeholder="উদা: হাজী মোঃ আব্দুর রহিম"
                               class="w-full px-3.5 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 font-semibold">
                        @error('investorName') <span class="text-rose-500 text-[10px] font-bold mt-0.5 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">মোবাইল নম্বর</label>
                        <input type="text" inputmode="numeric" wire:model="investorPhone" placeholder="017xxxxxxxx"
                               x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '')"
                               class="w-full px-3.5 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 font-mono">
                        @error('investorPhone') <span class="text-rose-500 text-[10px] font-bold mt-0.5 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">ঠিকানা</label>
                        <input type="text" wire:model="investorAddress" placeholder="গ্রাম/জেলা..."
                               class="w-full px-3.5 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 font-semibold">
                        @error('investorAddress') <span class="text-rose-500 text-[10px] font-bold mt-0.5 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">মুনাফার হার (% / Profit Share)</label>
                        <div class="relative">
                            <input type="number" step="0.1" min="0" max="100" wire:model="profitPercentage" placeholder="0"
                                   x-on:input="$el.value = $el.value.replace(/[^0-9.]/g, ''); if(parseFloat($el.value) > 100) $el.value = 100;"
                                   class="w-full pr-8 px-3.5 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 font-mono">
                            <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 font-bold font-mono pointer-events-none">%</span>
                        </div>
                        @error('profitPercentage') <span class="text-rose-500 text-[10px] font-bold mt-0.5 block">{{ $message }}</span> @enderror
                    </div>

                    @if(!$editingInvestorId)
                        <div>
                            <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">প্রাথমিক বিনিয়োগ / মূলধন (৳)</label>
                            <input type="number" step="0.01" min="0" wire:model="initialInvestment" placeholder="0.00"
                                   x-on:input="$el.value = $el.value.replace(/[^0-9.]/g, '')"
                                   class="w-full px-3.5 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 font-mono font-bold">
                            @error('initialInvestment') <span class="text-rose-500 text-[10px] font-bold mt-0.5 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">পেমেন্ট মেথড</label>
                            <div class="relative text-xs">
                                <button @click="activeDropdown = (activeDropdown === 'inv_payment' ? null : 'inv_payment')" type="button"
                                        class="flex items-center justify-between w-full px-3.5 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white font-bold cursor-pointer transition-all hover:border-emerald-500">
                                    <span>{{ $investorPaymentMethod }}</span>
                                    <svg class="w-3.5 h-3.5 transition-transform ml-1" :class="{'rotate-180': activeDropdown === 'inv_payment'}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="activeDropdown === 'inv_payment'" @click.outside="if(activeDropdown === 'inv_payment') activeDropdown = null" x-cloak
                                     class="absolute left-0 right-0 mt-1 z-[999] bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl shadow-xl overflow-hidden py-1">
                                    @foreach(['নগদ', 'ব্যাংক', 'চেক'] as $method)
                                        <button type="button" wire:click="selectInvestorPaymentMethod('{{ $method }}')" @click="activeDropdown = null"
                                                class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-200 font-bold flex items-center justify-between cursor-pointer">
                                            <span>{{ $method }}</span>
                                            @if($investorPaymentMethod === $method)
                                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                            @endif
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <div>
                        <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">নোট / বিবরণ</label>
                        <textarea wire:model="investorNotes" rows="2" placeholder="অতিরিক্ত বিবরণ..."
                                  class="w-full px-3.5 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 font-semibold"></textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100 dark:border-slate-800">
                    <button type="button" wire:click="$set('showInvestorModal', false)" class="px-4 py-2 bg-gray-200 dark:bg-slate-800 hover:bg-gray-300 text-gray-700 dark:text-slate-200 rounded-xl font-bold text-xs cursor-pointer">
                        বাতিল
                    </button>
                    <button type="button" wire:click="saveInvestor()" wire:loading.attr="disabled" class="px-4 py-2 bg-[#034C3C] hover:bg-emerald-800 text-white rounded-xl font-bold text-xs cursor-pointer shadow-sm disabled:opacity-50">
                        <span wire:loading.remove wire:target="saveInvestor">সংরক্ষণ করুন</span>
                        <span wire:loading wire:target="saveInvestor">সংরক্ষণ হচ্ছে...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Transaction Modal (With Searchable Investor Dropdown & Read-Only Auto Profit Badges) -->
    @if($showTransactionModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs"
             wire:click.self="$set('showTransactionModal', false)">
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-gray-150 dark:border-slate-800 w-full max-w-md p-6 space-y-4"
                 x-data="{ activeDropdown: null }"
                 wire:click.stop>
                <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-800 pb-3">
                    <h3 class="text-base font-extrabold text-gray-800 dark:text-white">
                        ইনভেস্টমেন্ট লেনদেন ইনপুট
                    </h3>
                    <button type="button" wire:click="$set('showTransactionModal', false)" class="text-gray-400 hover:text-gray-600 cursor-pointer">
                        ✕
                    </button>
                </div>

                <div class="space-y-4 text-xs">
                    
                    <!-- Searchable Custom Dropdown 1: Investor Select -->
                    <div>
                        <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">ইনভেস্টর সিলেক্ট করুন *</label>
                        <div class="relative text-xs">
                            @php
                                $selInvestor = $allInvestors->firstWhere('id', $selectedInvestorId);
                            @endphp
                            <button @click="activeDropdown = (activeDropdown === 'investor' ? null : 'investor')" type="button"
                                    class="flex items-center justify-between w-full px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white font-bold cursor-pointer transition-all hover:border-emerald-500">
                                <span class="truncate">{{ $selInvestor ? $selInvestor->name . ($selInvestor->phone ? ' (' . $selInvestor->phone . ')' : '') : '-- ইনভেস্টর নির্বাচন করুন --' }}</span>
                                <svg class="w-3.5 h-3.5 transition-transform ml-2" :class="{'rotate-180': activeDropdown === 'investor'}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="activeDropdown === 'investor'" @click.outside="if(activeDropdown === 'investor') activeDropdown = null" x-cloak
                                 class="absolute left-0 right-0 mt-1 z-[999] bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl shadow-xl overflow-hidden py-1 max-h-56 flex flex-col">
                                <!-- Search Input Inside Dropdown -->
                                <div class="p-2 border-b border-gray-100 dark:border-slate-800 sticky top-0 bg-white dark:bg-slate-900 z-10">
                                    <input type="text" wire:model.live.debounce.150ms="investorSearch" placeholder="খুঁজুন (নাম/ফোন)..."
                                           class="w-full px-2.5 py-1.5 rounded-lg border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 font-semibold text-xs">
                                </div>
                                <div class="overflow-y-auto max-h-40 py-1">
                                    <button type="button" wire:click="selectInvestor(null)" @click="activeDropdown = null" class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-slate-800 text-gray-400 font-bold cursor-pointer">-- ইনভেস্টর নির্বাচন করুন --</button>
                                    @forelse($filteredInvestors as $inv)
                                        <button type="button" wire:click="selectInvestor({{ $inv->id }})" @click="activeDropdown = null"
                                                class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-200 font-bold flex items-center justify-between cursor-pointer">
                                            <span>{{ $inv->name }} ({{ $inv->phone ?: 'নম্বর নেই' }})</span>
                                            @if($selectedInvestorId == $inv->id)
                                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                            @endif
                                        </button>
                                    @empty
                                        <div class="px-3 py-2 text-gray-400 text-center font-semibold">কোনো ইনভেস্টর পাওয়া যায়নি</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        @error('selectedInvestorId') <span class="text-rose-500 text-[10px] font-bold mt-0.5 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Custom Root Dropdown 2: Transaction Type -->
                    <div>
                        <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">লেনদেনের ধরণ *</label>
                        <div class="relative text-xs">
                            <button @click="activeDropdown = (activeDropdown === 'type' ? null : 'type')" type="button"
                                    class="flex items-center justify-between w-full px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white font-bold cursor-pointer transition-all hover:border-emerald-500">
                                <span>
                                    @if($transactionType === 'deposit') বিনিয়োগ জমা (নতুন বিনিয়োগ)
                                    @elseif($transactionType === 'profit_payout') লাভ প্রদান (মুনাফা বিতরণ)
                                    @elseif($transactionType === 'capital_return') মূলধন ফেরত (মূল টাকা ফেরত)
                                    @endif
                                </span>
                                <svg class="w-3.5 h-3.5 transition-transform ml-2" :class="{'rotate-180': activeDropdown === 'type'}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="activeDropdown === 'type'" @click.outside="if(activeDropdown === 'type') activeDropdown = null" x-cloak
                                 class="absolute left-0 right-0 mt-1 z-[999] bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl shadow-xl overflow-hidden py-1">
                                <button type="button" wire:click="selectTransactionType('deposit')" @click="activeDropdown = null" class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-200 font-bold flex items-center justify-between cursor-pointer">
                                    <span>বিনিয়োগ জমা (নতুন বিনিয়োগ)</span>
                                    @if($transactionType === 'deposit') <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> @endif
                                </button>
                                <button type="button" wire:click="selectTransactionType('profit_payout')" @click="activeDropdown = null" class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-200 font-bold flex items-center justify-between cursor-pointer">
                                    <span>লাভ প্রদান (মুনাফা বিতরণ)</span>
                                    @if($transactionType === 'profit_payout') <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> @endif
                                </button>
                                <button type="button" wire:click="selectTransactionType('capital_return')" @click="activeDropdown = null" class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-200 font-bold flex items-center justify-between cursor-pointer">
                                    <span>মূলধন ফেরত (মূল টাকা ফেরত)</span>
                                    @if($transactionType === 'capital_return') <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> @endif
                                </button>
                            </div>
                        </div>
                    </div>

                    @if($transactionType === 'profit_payout')
                        <!-- Read-Only Auto-Profit Calculator Card -->
                        <div class="p-3.5 bg-emerald-50/70 dark:bg-slate-800/80 rounded-2xl border border-emerald-150 dark:border-slate-700/80 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-[11px] font-extrabold text-emerald-800 dark:text-emerald-400 uppercase flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    অটো প্রফিট ক্যালকুলেটর
                                </span>
                                <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-bold">মুনাফা তথ্য</span>
                            </div>

                            <div class="grid grid-cols-2 gap-2.5 text-xs">
                                <!-- Read-Only Badge 1: Business Profit -->
                                <div class="p-2.5 bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-slate-700">
                                    <span class="block text-[10px] font-bold text-gray-500 dark:text-slate-400 mb-0.5">অবশিষ্ট ব্যবসায়িক লাভ</span>
                                    <span class="font-extrabold font-mono text-sm text-gray-800 dark:text-white">
                                        ৳{{ $this->formatMoney($this->totalBusinessProfit) }}
                                    </span>
                                </div>

                                <!-- Read-Only Badge 2: Investor Share % -->
                                <div class="p-2.5 bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-slate-700">
                                    <span class="block text-[10px] font-bold text-gray-500 dark:text-slate-400 mb-0.5">ইনভেস্টর লভ্যাংশ (%)</span>
                                    <span class="font-extrabold font-mono text-sm text-emerald-600 dark:text-emerald-400">
                                        {{ $this->investorProfitShare ? toBanglaNum($this->investorProfitShare) : '০' }}%
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Amount Input -->
                    <div>
                        <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">টাকার পরিমাণ (৳) *</label>
                        <input type="number" step="0.01" min="0.01" wire:model="transactionAmount" placeholder="0.00"
                               x-on:input="$el.value = $el.value.replace(/[^0-9.]/g, '')"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 font-mono font-bold">
                        @error('transactionAmount') <span class="text-rose-500 text-[10px] font-bold mt-0.5 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">তারিখ *</label>
                            <input type="date" wire:model="transactionDate"
                                   class="w-full px-3.5 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none font-semibold">
                            @error('transactionDate') <span class="text-rose-500 text-[10px] font-bold mt-0.5 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Custom Root Dropdown 3: Payment Method -->
                        <div>
                            <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">পেমেন্ট মেথড</label>
                            <div class="relative text-xs">
                                <button @click="activeDropdown = (activeDropdown === 'payment' ? null : 'payment')" type="button"
                                        class="flex items-center justify-between w-full px-3.5 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white font-bold cursor-pointer transition-all hover:border-emerald-500">
                                    <span>{{ $paymentMethod }}</span>
                                    <svg class="w-3.5 h-3.5 transition-transform ml-1" :class="{'rotate-180': activeDropdown === 'payment'}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="activeDropdown === 'payment'" @click.outside="if(activeDropdown === 'payment') activeDropdown = null" x-cloak
                                     class="absolute left-0 right-0 mt-1 z-[999] bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl shadow-xl overflow-hidden py-1">
                                    @foreach(['নগদ', 'ব্যাংক', 'বিকাশ', 'নগদ অ্যাপ', 'চেক'] as $method)
                                        <button type="button" wire:click="selectPaymentMethod('{{ $method }}')" @click="activeDropdown = null"
                                                class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-200 font-bold flex items-center justify-between cursor-pointer">
                                            <span>{{ $method }}</span>
                                            @if($paymentMethod === $method)
                                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                            @endif
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">নোট / বিবরণ</label>
                        <textarea wire:model="transactionNotes" rows="2" placeholder="লেনদেনের বিবরণ..."
                                  class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 font-semibold"></textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100 dark:border-slate-800">
                    <button type="button" wire:click="$set('showTransactionModal', false)" class="px-4 py-2 bg-gray-200 dark:bg-slate-800 hover:bg-gray-300 text-gray-700 dark:text-slate-200 rounded-xl font-bold text-xs cursor-pointer">
                        বাতিল
                    </button>
                    <button type="button" wire:click="saveTransaction()" wire:loading.attr="disabled" class="px-4 py-2 bg-[#034C3C] hover:bg-emerald-800 text-white rounded-xl font-bold text-xs cursor-pointer shadow-sm disabled:opacity-50">
                        <span wire:loading.remove wire:target="saveTransaction">লেনদেন সংরক্ষণ করুন</span>
                        <span wire:loading wire:target="saveTransaction">সংরক্ষণ হচ্ছে...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    <div x-data
         x-show="$wire.confirmDeleteInvestorId !== null || $wire.confirmDeleteTransactionId !== null"
         @click.self="$wire.cancelDelete()"
         class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 backdrop-blur-xs p-4"
         x-cloak
         x-transition>
        <div class="bg-white dark:bg-slate-900 rounded-2xl border-2 border-red-200 dark:border-red-900/60 p-6 max-w-sm w-full shadow-2xl space-y-5">
            <div class="flex items-start gap-4">
                <div class="w-11 h-11 rounded-xl bg-red-100 dark:bg-red-950/40 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-gray-900 dark:text-white">নিশ্চিত মুছে ফেলবেন?</h3>
                    <p class="text-xs text-gray-500 dark:text-slate-400 font-semibold mt-1">
                        @if($confirmDeleteInvestorId)
                            আপনি কি নিশ্চিতভাবে এই ইনভেস্টর ও তাঁর সব লেনদেন রেকর্ড মুছে ফেলতে চান?
                        @else
                            আপনি কি নিশ্চিতভাবে এই লেনদেন রেকর্ডটি মুছে ফেলতে চান?
                        @endif
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3 justify-end">
                <button type="button" wire:click="cancelDelete()"
                        class="px-5 py-2.5 border border-gray-200 dark:border-slate-700 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-600 dark:text-slate-300 text-xs font-bold rounded-xl transition-all cursor-pointer">
                    না, বাতিল
                </button>
                <button type="button" wire:click="{{ $confirmDeleteInvestorId ? 'executeDeleteInvestor' : 'executeDeleteTransaction' }}"
                        class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl transition-all cursor-pointer shadow-md shadow-red-500/25 active:scale-95">
                    হ্যাঁ, মুছে দিন
                </button>
            </div>
        </div>
    </div>
</div>
