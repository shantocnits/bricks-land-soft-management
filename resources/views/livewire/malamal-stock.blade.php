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
            @if(session()->has('message'))
                message = '{{ session('message') }}';
                show = true;
                setTimeout(() => show = false, 2000);
            @endif
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
        
        <!-- Header Info Banner -->
        <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl p-5 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <div class="p-2 bg-[#034C3C] text-white rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-extrabold text-gray-800 dark:text-white">অ্যাসেট ম্যানেজমেন্ট</h2>
                </div>
                <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">
                    কারখানার সকল মালামাল, ইকুইপমেন্ট, নতুন স্টক ও ইস্যুর যাবতীয় হিসাব তালিকা।
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2">
                <button type="button" wire:click="openIssueModal()"
                        class="px-4 py-2 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-800 dark:text-slate-200 text-xs font-bold rounded-xl border border-gray-200 dark:border-slate-700 transition-all cursor-pointer flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    ইস্যু করুন
                </button>
                <button type="button" wire:click="openAssetModal()"
                        class="px-4 py-2 bg-[#034C3C] hover:bg-emerald-900 text-white text-xs font-bold rounded-xl shadow-sm transition-all cursor-pointer flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    নতুন স্টক
                </button>
            </div>
        </div>

        <!-- Navigation Tabs Bar -->
        <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl p-3 shadow-sm flex items-center gap-1 overflow-x-auto text-xs font-bold">
            <button type="button" wire:click="$set('activeTab', 'dashboard')"
                    class="px-4 py-2.5 rounded-2xl transition-all cursor-pointer flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'dashboard' ? 'bg-[#034C3C] text-white shadow-sm' : 'text-gray-600 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                ড্যাশবোর্ড
            </button>
            <button type="button" wire:click="$set('activeTab', 'stock_list')"
                    class="px-4 py-2.5 rounded-2xl transition-all cursor-pointer flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'stock_list' ? 'bg-[#034C3C] text-white shadow-sm' : 'text-gray-600 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                স্টক তালিকা
            </button>
            <button type="button" wire:click="$set('activeTab', 'issue_list')"
                    class="px-4 py-2.5 rounded-2xl transition-all cursor-pointer flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'issue_list' ? 'bg-[#034C3C] text-white shadow-sm' : 'text-gray-600 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                ইস্যু লিস্ট
            </button>
            <button type="button" wire:click="$set('activeTab', 'damaged_items')"
                    class="px-4 py-2.5 rounded-2xl transition-all cursor-pointer flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'damaged_items' ? 'bg-[#034C3C] text-white shadow-sm' : 'text-gray-600 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800' }}">
                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                নষ্ট আইটেম
            </button>
            <button type="button" wire:click="$set('activeTab', 'lost_items')"
                    class="px-4 py-2.5 rounded-2xl transition-all cursor-pointer flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'lost_items' ? 'bg-[#034C3C] text-white shadow-sm' : 'text-gray-600 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800' }}">
                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                হারানো আইটেম
            </button>
            <button type="button" wire:click="$set('activeTab', 'history_log')"
                    class="px-4 py-2.5 rounded-2xl transition-all cursor-pointer flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'history_log' ? 'bg-[#034C3C] text-white shadow-sm' : 'text-gray-600 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                হিস্ট্রি লগ
            </button>
        </div>

        <!-- Dashboard Summary Cards -->
        @if($activeTab === 'dashboard')
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl p-5 shadow-sm cursor-pointer hover:border-emerald-500 transition-all"
                     wire:click="$set('activeTab', 'stock_list')">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-[11px] font-bold text-gray-500 uppercase">মোট অ্যাসেট (স্টক তালিকা)</span>
                            <h3 class="text-xl sm:text-2xl font-black text-[#034C3C] dark:text-emerald-400 font-mono mt-1">
                                {{ toBanglaNum($totalAssetCount) }}
                            </h3>
                            <span class="text-[10px] text-gray-400 font-bold block mt-1">পরিমাণ: {{ toBanglaNum($totalAssetCount) }} টি</span>
                        </div>
                        <div class="p-3 bg-sky-50 dark:bg-sky-950/40 text-sky-600 rounded-2xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl p-5 shadow-sm cursor-pointer hover:border-emerald-500 transition-all"
                     wire:click="$set('activeTab', 'stock_list')">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-[11px] font-bold text-gray-500 uppercase">বর্তমান রেডি স্টক</span>
                            <h3 class="text-xl sm:text-2xl font-black text-emerald-600 dark:text-emerald-400 font-mono mt-1">
                                {{ toBanglaNum($currentStockCount) }}
                            </h3>
                            <span class="text-[10px] text-gray-400 font-bold block mt-1">পরিমাণ: {{ toBanglaNum($currentStockCount) }} টি</span>
                        </div>
                        <div class="p-3 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 rounded-2xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl p-5 shadow-sm cursor-pointer hover:border-amber-500 transition-all"
                     wire:click="$set('activeTab', 'damaged_items')">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-[11px] font-bold text-gray-500 uppercase">নষ্ট আইটেম</span>
                            <h3 class="text-xl sm:text-2xl font-black text-amber-600 dark:text-amber-400 font-mono mt-1">
                                {{ toBanglaNum($damagedCount) }}
                            </h3>
                            <span class="text-[10px] text-gray-400 font-bold block mt-1">পরিমাণ: {{ toBanglaNum($damagedCount) }} টি</span>
                        </div>
                        <div class="p-3 bg-amber-50 dark:bg-amber-950/40 text-amber-600 rounded-2xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl p-5 shadow-sm cursor-pointer hover:border-rose-500 transition-all"
                     wire:click="$set('activeTab', 'lost_items')">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-[11px] font-bold text-gray-500 uppercase">হারানো আইটেম</span>
                            <h3 class="text-xl sm:text-2xl font-black text-rose-500 dark:text-rose-400 font-mono mt-1">
                                {{ toBanglaNum($lostCount) }}
                            </h3>
                            <span class="text-[10px] text-gray-400 font-bold block mt-1">পরিমাণ: {{ toBanglaNum($lostCount) }} টি</span>
                        </div>
                        <div class="p-3 bg-rose-50 dark:bg-rose-950/40 text-rose-500 rounded-2xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- History Log Summary Cards -->
        @if($activeTab === 'history_log')
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-sky-50/60 dark:bg-sky-950/30 border border-sky-150 dark:border-sky-900/50 rounded-2xl p-4">
                    <span class="text-xs font-bold text-sky-700 dark:text-sky-400 block mb-1">ফেরত এসেছে</span>
                    <h3 class="text-2xl font-black text-sky-800 dark:text-sky-300 font-mono">{{ toBanglaNum($returnedCount) }}</h3>
                </div>

                <div class="bg-emerald-50/60 dark:bg-emerald-950/30 border border-emerald-150 dark:border-emerald-900/50 rounded-2xl p-4">
                    <span class="text-xs font-bold text-emerald-700 dark:text-emerald-400 block mb-1">ভালো</span>
                    <h3 class="text-2xl font-black text-emerald-800 dark:text-emerald-300 font-mono">{{ toBanglaNum($goodCount) }}</h3>
                </div>

                <div class="bg-amber-50/60 dark:bg-amber-950/30 border border-amber-150 dark:border-amber-900/50 rounded-2xl p-4">
                    <span class="text-xs font-bold text-amber-700 dark:text-amber-400 block mb-1">নষ্ট</span>
                    <h3 class="text-2xl font-black text-amber-800 dark:text-amber-300 font-mono">{{ toBanglaNum($damagedLogCount) }}</h3>
                </div>

                <div class="bg-rose-50/60 dark:bg-rose-950/30 border border-rose-150 dark:border-rose-900/50 rounded-2xl p-4">
                    <span class="text-xs font-bold text-rose-700 dark:text-rose-400 block mb-1">হারানো</span>
                    <h3 class="text-2xl font-black text-rose-800 dark:text-rose-300 font-mono">{{ toBanglaNum($lostLogCount) }}</h3>
                </div>
            </div>
        @endif

        <!-- Filter & Search Toolbar -->
        @if(in_array($activeTab, ['stock_list', 'issue_list', 'damaged_items', 'lost_items']))
            <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl p-4 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3 w-full sm:w-auto flex-grow">
                    <div class="relative flex-grow sm:w-72">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="আইটেম নাম দিয়ে খুঁজুন..."
                               class="w-full pl-4 pr-9 py-2 text-xs font-semibold rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white placeholder-gray-400 focus:outline-none focus:border-emerald-500">
                        <span class="absolute right-3 top-2.5 text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                    </div>

                    <!-- Category Dropdown ONLY for stock_list tab -->
                    @if($activeTab === 'stock_list')
                        <div x-data="{ open: false }" class="relative text-xs w-full sm:w-auto">
                            @php
                                $selectedCat = $categories->firstWhere('id', $categoryFilter);
                            @endphp
                            <button @click="open = !open" type="button"
                                    class="flex items-center justify-between gap-2 px-3.5 py-2 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white font-bold rounded-xl border border-emerald-600/70 dark:border-emerald-500/70 cursor-pointer min-w-[160px] shadow-xs">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                    </svg>
                                    <span>{{ $categoryFilter === 'all' ? 'সকল ক্যাটাগরি' : ($selectedCat ? $selectedCat->name : 'সকল ক্যাটাগরি') }}</span>
                                </div>
                                <svg class="w-3.5 h-3.5 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" @click.outside="open = false" x-cloak
                                 class="absolute right-0 mt-1.5 z-[999] w-48 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-2xl shadow-xl overflow-hidden py-1">
                                <button type="button" wire:click="selectCategoryFilter('all')" @click="open = false"
                                        class="w-full text-left px-3 py-2 text-xs font-bold transition-colors cursor-pointer flex items-center justify-between {{ $categoryFilter === 'all' ? 'bg-emerald-100/80 dark:bg-emerald-900/60 text-emerald-900 dark:text-emerald-200' : 'text-gray-700 dark:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-800' }}">
                                    <span>সকল ক্যাটাগরি</span>
                                    @if($categoryFilter === 'all') <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> @endif
                                </button>
                                @foreach($categories as $cat)
                                    <button type="button" wire:click="selectCategoryFilter('{{ $cat->id }}')" @click="open = false"
                                            class="w-full text-left px-3 py-2 text-xs font-bold transition-colors cursor-pointer flex items-center justify-between {{ $categoryFilter == $cat->id ? 'bg-emerald-100/80 dark:bg-emerald-900/60 text-emerald-900 dark:text-emerald-200' : 'text-gray-700 dark:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-800' }}">
                                        <span>{{ $cat->name }}</span>
                                        @if($categoryFilter == $cat->id) <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                @if($activeTab === 'stock_list')
                    <div class="px-4 py-2 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900/50 rounded-2xl text-xs font-bold text-emerald-800 dark:text-emerald-300 flex items-center gap-1.5 whitespace-nowrap shadow-xs">
                        <span class="text-gray-500 dark:text-slate-400 font-semibold">মোট মূল্য:</span>
                        <span class="font-mono text-sm font-black text-[#034C3C] dark:text-emerald-400">৳{{ toBanglaNum(number_format($totalStockListValue, 0)) }}</span>
                    </div>
                @endif
            </div>
        @endif

        <!-- Main Data Tables (Desktop Table View) -->
        <div class="hidden md:block bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left border border-gray-200 dark:border-slate-800">
                    <thead>
                        <tr class="bg-[#034C3C] text-white text-[11px] font-bold uppercase">
                            @if($activeTab === 'dashboard')
                                <th class="py-3.5 px-4 text-center border-r border-white/20 w-12">SL</th>
                                <th class="py-3.5 px-4 text-center border-r border-white/20">টাইপ</th>
                                <th class="py-3.5 px-4 border-r border-white/20">তারিখ</th>
                                <th class="py-3.5 px-4 border-r border-white/20">প্রোডাক্ট</th>
                                <th class="py-3.5 px-4 text-center border-r border-white/20">পরিমাণ</th>
                                <th class="py-3.5 px-4 text-center">স্টেটাস</th>

                            @elseif($activeTab === 'stock_list')
                                <th class="py-3.5 px-4 text-center border-r border-white/20 w-12">SL</th>
                                <th class="py-3.5 px-4 text-center border-r border-white/20">ছবি</th>
                                <th class="py-3.5 px-4 border-r border-white/20">প্রোডাক্ট</th>
                                <th class="py-3.5 px-4 border-r border-white/20">ক্যাটাগরি</th>
                                <th class="py-3.5 px-4 text-center border-r border-white/20">মোট</th>
                                <th class="py-3.5 px-4 text-center border-r border-white/20">বর্তমান</th>
                                <th class="py-3.5 px-4 text-center border-r border-white/20">ইস্যু</th>
                                <th class="py-3.5 px-4 text-center border-r border-white/20">নষ্ট</th>
                                <th class="py-3.5 px-4 text-center border-r border-white/20">হারা</th>
                                <th class="py-3.5 px-4 text-right border-r border-white/20">একক মূল্য</th>
                                <th class="py-3.5 px-4 text-right border-r border-white/20">মোট মূল্য</th>
                                <th class="py-3.5 px-4 text-center">অ্যাকশন</th>

                            @elseif($activeTab === 'issue_list')
                                <th class="py-3.5 px-4 text-center border-r border-white/20 w-12">SL</th>
                                <th class="py-3.5 px-4 text-center border-r border-white/20">ছবি</th>
                                <th class="py-3.5 px-4 border-r border-white/20">প্রোডাক্ট</th>
                                <th class="py-3.5 px-4 border-r border-white/20">ক্যাটাগরি</th>
                                <th class="py-3.5 px-4 border-r border-white/20">কার কাছে</th>
                                <th class="py-3.5 px-4 border-r border-white/20">লোকেশন</th>
                                <th class="py-3.5 px-4 text-center border-r border-white/20">পরিমাণ</th>
                                <th class="py-3.5 px-4 text-center border-r border-white/20">ইস্যু তারিখ</th>
                                <th class="py-3.5 px-4 text-center">অ্যাকশন</th>

                            @elseif($activeTab === 'damaged_items')
                                <th class="py-3.5 px-4 text-center border-r border-white/20 w-12">SL</th>
                                <th class="py-3.5 px-4 border-r border-white/20">প্রোডাক্ট</th>
                                <th class="py-3.5 px-4 text-center border-r border-white/20">নষ্ট পরিমাণ</th>
                                <th class="py-3.5 px-4 text-right border-r border-white/20">ক্ষতি (টাকা)</th>
                                <th class="py-3.5 px-4 text-center">অ্যাকশন</th>

                            @elseif($activeTab === 'lost_items')
                                <th class="py-3.5 px-4 text-center border-r border-white/20 w-12">SL</th>
                                <th class="py-3.5 px-4 border-r border-white/20">প্রোডাক্ট</th>
                                <th class="py-3.5 px-4 text-center border-r border-white/20">হারানো পরিমাণ</th>
                                <th class="py-3.5 px-4 text-right border-r border-white/20">ক্ষতি (টাকা)</th>
                                <th class="py-3.5 px-4 text-center">অ্যাকশন</th>

                            @elseif($activeTab === 'history_log')
                                <th class="py-3.5 px-4 text-center border-r border-white/20 w-12">SL</th>
                                <th class="py-3.5 px-4 text-center border-r border-white/20">তারিখ</th>
                                <th class="py-3.5 px-4 text-center border-r border-white/20">টাইপ</th>
                                <th class="py-3.5 px-4 border-r border-white/20">প্রোডাক্ট</th>
                                <th class="py-3.5 px-4 border-r border-white/20">বিবরণ</th>
                                <th class="py-3.5 px-4 text-center border-r border-white/20">মোট</th>
                                <th class="py-3.5 px-4 text-center border-r border-white/20">অবস্থা</th>
                                <th class="py-3.5 px-4 text-center">প্রমাণ</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800 text-xs font-semibold">
                        @if($activeTab === 'dashboard')
                            @forelse($records as $index => $log)
                                <tr class="hover:bg-emerald-50/30 dark:hover:bg-slate-800/50">
                                    <td class="py-3.5 px-4 text-center text-gray-500 font-bold border-r border-gray-150 dark:border-slate-800">
                                        {{ toBanglaNum($records->firstItem() + $index) }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center border-r border-gray-150 dark:border-slate-800">
                                        @if($log->action_type === 'add_stock')
                                            <span class="inline-flex px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 text-[10px] font-extrabold">স্টক ইন</span>
                                        @elseif($log->action_type === 'issue')
                                            <span class="inline-flex px-2 py-0.5 rounded bg-sky-100 text-sky-800 text-[10px] font-extrabold">ইস্যু</span>
                                        @elseif($log->action_type === 'return')
                                            <span class="inline-flex px-2 py-0.5 rounded bg-indigo-100 text-indigo-800 text-[10px] font-extrabold">ফেরত</span>
                                        @elseif($log->action_type === 'damaged')
                                            <span class="inline-flex px-2 py-0.5 rounded bg-amber-100 text-amber-800 text-[10px] font-extrabold">নষ্ট</span>
                                        @elseif($log->action_type === 'lost')
                                            <span class="inline-flex px-2 py-0.5 rounded bg-rose-100 text-rose-800 text-[10px] font-extrabold">হারানো</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 font-semibold text-gray-800 dark:text-slate-200 border-r border-gray-150 dark:border-slate-800 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($log->created_at)->format('d-m-Y') }}
                                    </td>
                                    <td class="py-3.5 px-4 font-extrabold text-gray-900 dark:text-white border-r border-gray-150 dark:border-slate-800">
                                        {{ $log->asset ? $log->asset->name : '—' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center font-black font-mono text-gray-900 dark:text-white border-r border-gray-150 dark:border-slate-800">
                                        {{ toBanglaNum($log->quantity) }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        <span class="px-2 py-0.5 rounded-lg bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 font-bold text-[10px]">সম্পন্ন</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center text-gray-400 font-semibold text-xs">
                                        কোনো তথ্য পাওয়া যায়নি।
                                    </td>
                                </tr>
                            @endforelse

                        @elseif($activeTab === 'stock_list')
                            @forelse($records as $index => $a)
                                <tr class="hover:bg-emerald-50/30 dark:hover:bg-slate-800/50">
                                    <td class="py-3.5 px-4 text-center text-gray-500 font-bold border-r border-gray-150 dark:border-slate-800">
                                        {{ toBanglaNum($records->firstItem() + $index) }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center border-r border-gray-150 dark:border-slate-800">
                                        @if($a->image)
                                            <div class="relative group w-9 h-9 mx-auto rounded-xl overflow-hidden cursor-pointer"
                                                 wire:click="openQuickView('{{ Storage::url($a->image) }}', '{{ $a->name }}')">
                                                <img src="{{ Storage::url($a->image) }}" alt="{{ $a->name }}" class="w-9 h-9 object-cover rounded-xl border border-gray-200 dark:border-slate-700">
                                                <div class="absolute inset-0 bg-slate-950/60 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white transition-opacity duration-200">
                                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                        @else
                                            <div class="w-9 h-9 bg-gray-100 dark:bg-slate-800 rounded-xl flex items-center justify-center text-gray-400 mx-auto">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 font-extrabold text-gray-900 dark:text-white border-r border-gray-150 dark:border-slate-800">
                                        {{ $a->name }}
                                        <span class="text-[10px] text-gray-400 font-mono block">{{ $a->code }}</span>
                                    </td>
                                    <td class="py-3.5 px-4 text-gray-600 dark:text-slate-400 border-r border-gray-150 dark:border-slate-800">
                                        {{ $a->category ? $a->category->name : '—' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center font-bold font-mono text-gray-800 dark:text-slate-200 border-r border-gray-150 dark:border-slate-800">
                                        {{ toBanglaNum($a->total_qty) }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center font-black font-mono text-[#034C3C] dark:text-emerald-400 border-r border-gray-150 dark:border-slate-800">
                                        {{ toBanglaNum($a->current_qty) }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center font-bold font-mono text-sky-600 border-r border-gray-150 dark:border-slate-800">
                                        {{ toBanglaNum($a->issued_qty) }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center font-bold font-mono text-amber-600 border-r border-gray-150 dark:border-slate-800">
                                        {{ toBanglaNum($a->damaged_qty) }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center font-bold font-mono text-rose-500 border-r border-gray-150 dark:border-slate-800">
                                        {{ toBanglaNum($a->lost_qty) }}
                                    </td>
                                    <td class="py-3.5 px-4 text-right font-black font-mono text-emerald-700 dark:text-emerald-400 border-r border-gray-150 dark:border-slate-800">
                                        ৳{{ toBanglaNum(number_format($a->unit_price, 2)) }}
                                    </td>
                                    <td class="py-3.5 px-4 text-right font-black font-mono text-emerald-800 dark:text-emerald-300 border-r border-gray-150 dark:border-slate-800">
                                        ৳{{ toBanglaNum(number_format($a->total_qty * $a->unit_price, 2)) }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <button type="button" wire:click="viewAssetModal({{ $a->id }})" title="ভিউ"
                                                    class="p-1.5 rounded-lg bg-sky-50 dark:bg-slate-800 text-sky-600 dark:text-sky-400 hover:bg-sky-600 hover:text-white dark:hover:bg-sky-600 dark:hover:text-white transition-all cursor-pointer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </button>
                                            <button type="button" wire:confirm="আপনি কি নিশ্চিতভাবে এই প্রোডাক্ট মুছে ফেলতে চান?" wire:click="deleteAsset({{ $a->id }})" title="ডিলিট"
                                                    class="p-1.5 rounded-lg bg-rose-50 dark:bg-slate-800 text-rose-600 dark:text-rose-400 hover:bg-rose-600 hover:text-white dark:hover:bg-rose-600 dark:hover:text-white transition-all cursor-pointer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="py-12 text-center text-gray-400 font-semibold text-xs">
                                        কোনো প্রোডাক্ট স্টক ডাটা পাওয়া যায়নি।
                                    </td>
                                </tr>
                            @endforelse

                        @elseif($activeTab === 'issue_list')
                            @forelse($records as $index => $issue)
                                <tr class="hover:bg-emerald-50/30 dark:hover:bg-slate-800/50">
                                    <td class="py-3.5 px-4 text-center text-gray-500 font-bold border-r border-gray-150 dark:border-slate-800">
                                        {{ toBanglaNum($records->firstItem() + $index) }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center border-r border-gray-150 dark:border-slate-800">
                                        @php
                                            $imgUrl = $issue->image ? Storage::url($issue->image) : ($issue->asset && $issue->asset->image ? Storage::url($issue->asset->image) : null);
                                        @endphp
                                        @if($imgUrl)
                                            <div class="relative group w-9 h-9 mx-auto rounded-xl overflow-hidden cursor-pointer"
                                                 wire:click="openQuickView('{{ $imgUrl }}', '{{ $issue->asset ? $issue->asset->name : 'ইস্যু প্রুফ' }}')">
                                                <img src="{{ $imgUrl }}" class="w-9 h-9 object-cover rounded-xl border border-gray-200 dark:border-slate-700">
                                                <div class="absolute inset-0 bg-slate-950/60 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white transition-opacity duration-200">
                                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                        @else
                                            <div class="w-9 h-9 bg-gray-100 dark:bg-slate-800 rounded-xl flex items-center justify-center text-gray-400 mx-auto">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 font-extrabold text-gray-900 dark:text-white border-r border-gray-150 dark:border-slate-800">
                                        {{ $issue->asset ? $issue->asset->name : '—' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-gray-600 dark:text-slate-400 border-r border-gray-150 dark:border-slate-800">
                                        {{ ($issue->asset && $issue->asset->category) ? $issue->asset->category->name : '—' }}
                                    </td>
                                    <td class="py-3.5 px-4 font-bold text-gray-800 dark:text-slate-200 border-r border-gray-150 dark:border-slate-800">
                                        {{ $issue->issued_to }}
                                    </td>
                                    <td class="py-3.5 px-4 text-gray-600 dark:text-slate-400 border-r border-gray-150 dark:border-slate-800">
                                        {{ $issue->location ?: '—' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center font-black font-mono text-gray-900 dark:text-white border-r border-gray-150 dark:border-slate-800">
                                        {{ toBanglaNum($issue->quantity) }} টি
                                    </td>
                                    <td class="py-3.5 px-4 text-center font-semibold text-gray-800 dark:text-slate-200 border-r border-gray-150 dark:border-slate-800 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($issue->issue_date)->format('d-m-Y') }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        <button type="button" wire:click="openReturnModal({{ $issue->id }})"
                                                class="px-3.5 py-1.5 bg-[#034C3C] hover:bg-emerald-800 text-white text-[11px] font-bold rounded-xl shadow-xs transition-all cursor-pointer flex items-center justify-center gap-1 mx-auto">
                                            <span>ফেরত নেওয়া</span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="py-12 text-center text-gray-400 font-semibold text-xs">
                                        কোনো সক্রিয় ইস্যু তালিকা পাওয়া যায়নি।
                                    </td>
                                </tr>
                            @endforelse

                        @elseif($activeTab === 'damaged_items')
                            @forelse($records as $index => $item)
                                <tr class="hover:bg-emerald-50/30 dark:hover:bg-slate-800/50">
                                    <td class="py-3.5 px-4 text-center text-gray-500 font-bold border-r border-gray-150 dark:border-slate-800">
                                        {{ toBanglaNum($records->firstItem() + $index) }}
                                    </td>
                                    <td class="py-3.5 px-4 font-extrabold text-gray-900 dark:text-white border-r border-gray-150 dark:border-slate-800">
                                        {{ $item->name }}
                                        <span class="text-[10px] text-gray-400 font-mono block">{{ $item->code }}</span>
                                    </td>
                                    <td class="py-3.5 px-4 text-center font-black font-mono text-amber-600 border-r border-gray-150 dark:border-slate-800">
                                        {{ toBanglaNum($item->damaged_qty) }}
                                    </td>
                                    <td class="py-3.5 px-4 text-right font-black font-mono text-rose-500 border-r border-gray-150 dark:border-slate-800">
                                        ৳{{ toBanglaNum(number_format($item->damaged_qty * $item->unit_price, 0)) }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        <button type="button" wire:click="openRepairModal({{ $item->id }})"
                                                class="px-3.5 py-1.5 bg-amber-600 hover:bg-amber-700 text-white text-[11px] font-bold rounded-xl shadow-xs transition-all cursor-pointer flex items-center justify-center gap-1 mx-auto">
                                            <span>🔧 মেরামত</span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center text-gray-400 font-semibold text-xs">
                                        <div class="flex flex-col items-center justify-center space-y-2">
                                            <div class="p-3 bg-gray-100 dark:bg-slate-800/80 rounded-2xl text-gray-300">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                            </div>
                                            <span>No data</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse

                        @elseif($activeTab === 'lost_items')
                            @forelse($records as $index => $item)
                                <tr class="hover:bg-emerald-50/30 dark:hover:bg-slate-800/50">
                                    <td class="py-3.5 px-4 text-center text-gray-500 font-bold border-r border-gray-150 dark:border-slate-800">
                                        {{ toBanglaNum($records->firstItem() + $index) }}
                                    </td>
                                    <td class="py-3.5 px-4 font-extrabold text-gray-900 dark:text-white border-r border-gray-150 dark:border-slate-800">
                                        {{ $item->name }}
                                        <span class="text-[10px] text-gray-400 font-mono block">{{ $item->code }}</span>
                                    </td>
                                    <td class="py-3.5 px-4 text-center font-black font-mono text-rose-500 border-r border-gray-150 dark:border-slate-800">
                                        <span class="px-2 py-0.5 rounded border border-rose-200 bg-rose-50 text-rose-700 text-xs font-bold">{{ toBanglaNum($item->lost_qty) }}</span>
                                    </td>
                                    <td class="py-3.5 px-4 text-right font-black font-mono text-rose-600 border-r border-gray-150 dark:border-slate-800">
                                        ৳{{ toBanglaNum(number_format($item->lost_qty * $item->unit_price, 0)) }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        <button type="button" wire:click="openFoundModal({{ $item->id }})"
                                                class="px-3.5 py-1.5 bg-[#034C3C] hover:bg-emerald-800 text-white text-[11px] font-bold rounded-xl shadow-xs transition-all cursor-pointer flex items-center justify-center gap-1 mx-auto">
                                            <span>✓ পাওয়া গেছে</span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center text-gray-400 font-semibold text-xs">
                                        <div class="flex flex-col items-center justify-center space-y-2">
                                            <div class="p-3 bg-gray-100 dark:bg-slate-800/80 rounded-2xl text-gray-300">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                            </div>
                                            <span>No data</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse

                        @elseif($activeTab === 'history_log')
                            @forelse($records as $index => $log)
                                <tr class="hover:bg-emerald-50/30 dark:hover:bg-slate-800/50">
                                    <td class="py-3.5 px-4 text-center text-gray-500 font-bold border-r border-gray-150 dark:border-slate-800">
                                        {{ toBanglaNum($records->firstItem() + $index) }}
                                    </td>
                                    <td class="py-3.5 px-4 font-semibold text-gray-800 dark:text-slate-200 border-r border-gray-150 dark:border-slate-800 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($log->created_at)->format('d-m-Y') }}
                                        <span class="text-[10px] text-gray-400 font-mono block">{{ \Carbon\Carbon::parse($log->created_at)->format('h:i A') }}</span>
                                    </td>
                                    <td class="py-3.5 px-4 text-center border-r border-gray-150 dark:border-slate-800">
                                        @if($log->action_type === 'return')
                                            <span class="inline-flex px-2 py-0.5 rounded bg-sky-100 text-sky-800 text-[10px] font-extrabold">ফেরত</span>
                                        @elseif($log->action_type === 'add_stock')
                                            <span class="inline-flex px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 text-[10px] font-extrabold">নতুন স্টক</span>
                                        @elseif($log->action_type === 'issue')
                                            <span class="inline-flex px-2 py-0.5 rounded bg-indigo-100 text-indigo-800 text-[10px] font-extrabold">ইস্যু</span>
                                        @elseif($log->action_type === 'damaged')
                                            <span class="inline-flex px-2 py-0.5 rounded bg-amber-100 text-amber-800 text-[10px] font-extrabold">নষ্ট</span>
                                        @elseif($log->action_type === 'lost')
                                            <span class="inline-flex px-2 py-0.5 rounded bg-rose-100 text-rose-800 text-[10px] font-extrabold">হারানো</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 font-extrabold text-gray-900 dark:text-white border-r border-gray-150 dark:border-slate-800">
                                        {{ $log->asset ? $log->asset->name : '—' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-gray-600 dark:text-slate-400 border-r border-gray-150 dark:border-slate-800">
                                        {{ $log->notes ?: '—' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center font-black font-mono text-gray-900 dark:text-white border-r border-gray-150 dark:border-slate-800">
                                        {{ toBanglaNum($log->quantity) }}
                                    </td>

                                    <!-- Req 1: Dynamic Status Badge in History Log -->
                                    <td class="py-3.5 px-4 text-center border-r border-gray-150 dark:border-slate-800 font-bold">
                                        <span class="px-2 py-0.5 rounded-lg bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 font-extrabold text-[10px]">
                                            সম্পন্ন
                                        </span>
                                    </td>

                                    <!-- Req 1: Dynamic Proof Image Box in History Log -->
                                    <td class="py-3.5 px-4 text-center">
                                        @php
                                            $proofUrl = $log->proof_image ? Storage::url($log->proof_image) : ($log->asset && $log->asset->image ? Storage::url($log->asset->image) : null);
                                        @endphp
                                        @if($proofUrl)
                                            <div class="relative group w-8 h-8 mx-auto rounded-xl overflow-hidden cursor-pointer"
                                                 wire:click="openQuickView('{{ $proofUrl }}', '{{ $log->asset ? $log->asset->name : 'প্রমাণ ছবি' }}')">
                                                <img src="{{ $proofUrl }}" alt="Proof" class="w-8 h-8 object-cover rounded-xl border border-gray-200 dark:border-slate-700">
                                                <div class="absolute inset-0 bg-slate-950/60 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white transition-opacity duration-200">
                                                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-gray-400 font-bold">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-12 text-center text-gray-400 font-semibold text-xs">
                                        কোনো ইতিহাস ডাটা পাওয়া যায়নি।
                                    </td>
                                </tr>
                            @endforelse
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Root Dropdown Pagination toolbar -->
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
                                <button type="button" wire:click="selectPerPage({{ $size }})" @click="open = false"
                                        class="w-full text-left px-3 py-2 text-xs font-bold text-gray-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-slate-800 cursor-pointer">
                                    {{ toBanglaNum($size) }} / পেজ
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Box-Type Card View -->
        <div class="block md:hidden space-y-4">
            @forelse($records as $rec)
                <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl p-5 shadow-sm space-y-3">
                    @if($activeTab === 'stock_list')
                        <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-800 pb-2">
                            <h4 class="font-extrabold text-gray-900 dark:text-white text-xs">{{ $rec->name }}</h4>
                            <span class="text-[10px] font-mono text-gray-400">{{ $rec->code }}</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-xs font-mono">
                            <div><span class="text-gray-400 text-[10px]">একক মূল্য:</span> <span class="font-bold text-emerald-600">৳{{ toBanglaNum(number_format($rec->unit_price)) }}</span></div>
                            <div><span class="text-gray-400 text-[10px]">বর্তমান স্টক:</span> <span class="font-black text-[#034C3C] dark:text-emerald-400">{{ toBanglaNum($rec->current_qty) }} টি</span></div>
                        </div>
                        <div class="flex items-center gap-2 pt-2 border-t border-gray-100 dark:border-slate-800">
                            <button type="button" wire:click="viewAssetModal({{ $rec->id }})" class="flex-1 py-1.5 bg-sky-50 dark:bg-slate-800 text-sky-600 font-bold rounded-lg text-xs">ভিউ</button>
                            <button type="button" wire:click="openAssetModal({{ $rec->id }})" class="flex-1 py-1.5 bg-gray-100 dark:bg-slate-800 text-gray-700 font-bold rounded-lg text-xs">এডিট</button>
                        </div>
                    @elseif($activeTab === 'issue_list')
                        <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-800 pb-2">
                            <h4 class="font-extrabold text-gray-900 dark:text-white text-xs">{{ $rec->asset ? $rec->asset->name : '—' }}</h4>
                            <span class="text-[10px] font-bold text-sky-600">কার কাছে: {{ $rec->issued_to }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs font-mono">
                            <span class="text-gray-500 font-semibold">{{ \Carbon\Carbon::parse($rec->issue_date)->format('d-m-Y') }}</span>
                            <span class="font-black text-emerald-600">{{ toBanglaNum($rec->quantity) }} টি</span>
                        </div>
                        <button type="button" wire:click="openReturnModal({{ $rec->id }})" class="w-full py-2 bg-[#034C3C] text-white font-bold rounded-xl text-xs">
                            ফেরত নেওয়া
                        </button>
                    @elseif($activeTab === 'damaged_items')
                        <div class="flex items-center justify-between">
                            <h4 class="font-extrabold text-gray-900 dark:text-white text-xs">{{ $rec->name }}</h4>
                            <span class="font-black text-amber-600 font-mono text-xs">নষ্ট: {{ toBanglaNum($rec->damaged_qty) }} টি</span>
                        </div>
                        <button type="button" wire:click="openRepairModal({{ $rec->id }})" class="w-full py-2 bg-amber-600 text-white font-bold rounded-xl text-xs">
                            🔧 মেরামত
                        </button>
                    @elseif($activeTab === 'lost_items')
                        <div class="flex items-center justify-between">
                            <h4 class="font-extrabold text-gray-900 dark:text-white text-xs">{{ $rec->name }}</h4>
                            <span class="font-black text-rose-500 font-mono text-xs">হারানো: {{ toBanglaNum($rec->lost_qty) }} টি</span>
                        </div>
                        <button type="button" wire:click="openFoundModal({{ $rec->id }})" class="w-full py-2 bg-[#034C3C] text-white font-bold rounded-xl text-xs">
                            ✓ পাওয়া গেছে
                        </button>
                    @else
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-gray-900 dark:text-white">{{ $rec->asset ? $rec->asset->name : '—' }}</span>
                            <span class="font-semibold text-gray-500">{{ \Carbon\Carbon::parse($rec->created_at)->format('d-m-Y') }}</span>
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white dark:bg-slate-900 border border-gray-150 rounded-3xl p-8 text-center text-gray-400 font-semibold text-xs">
                    কোনো তথ্য পাওয়া যায়নি।
                </div>
            @endforelse

            <div class="pt-2">
                {{ $records->links() }}
            </div>
        </div>

    </div>

    <!-- Modal 0: Issue Asset Modal (Req 3: Shows red 0 badge for 0-stock products & disables selection - Image 3) -->
    @if($showIssueModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs"
             wire:click.self="$set('showIssueModal', false)">
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-gray-150 dark:border-slate-800 w-full max-w-lg p-6 space-y-4"
                 wire:click.stop>
                <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-800 pb-3">
                    <div class="flex items-center gap-2">
                        <div class="p-2 bg-sky-100 dark:bg-sky-950 text-sky-600 rounded-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-gray-900 dark:text-white">নতুন ইস্যু</h3>
                            <p class="text-[11px] text-gray-400 font-semibold">স্টক থেকে মালামাল ইস্যু করুন</p>
                        </div>
                    </div>
                    <button type="button" wire:click="$set('showIssueModal', false)" class="text-gray-400 hover:text-gray-600 cursor-pointer p-1 rounded-full bg-gray-100 dark:bg-slate-800">✕</button>
                </div>

                <div class="space-y-3.5 text-xs">
                    <!-- Req 3: Product Selector with Red 0 Stock Badge Matching Image 3 -->
                    <div>
                        <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">প্রোডাক্ট</label>
                        <div x-data="{ open: false }" class="relative text-xs">
                            @php
                                $selectedAst = $allAssets->firstWhere('id', $selectedAssetId);
                            @endphp
                            <button @click="open = !open" type="button"
                                    class="w-full flex items-center justify-between px-3.5 py-2.5 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white font-bold rounded-xl border border-emerald-500 cursor-pointer">
                                <span>{{ $selectedAst ? $selectedAst->name : 'প্রোডাক্ট নির্বাচন করুন' }}</span>
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded-lg border border-emerald-400 bg-emerald-50 text-emerald-700 font-mono text-[11px]">
                                        {{ $selectedAst ? toBanglaNum($selectedAst->current_qty) : 0 }}
                                    </span>
                                    <svg class="w-4 h-4 text-gray-400 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </button>

                            <div x-show="open" @click.outside="open = false" x-cloak
                                 class="absolute left-0 right-0 mt-1 z-[999] max-h-48 overflow-y-auto bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-2xl shadow-xl py-1 divide-y divide-gray-50 dark:divide-slate-800">
                                @foreach($allAssets as $ast)
                                    @if($ast->current_qty > 0)
                                        <button type="button" wire:click="selectAssetForIssue({{ $ast->id }})" @click="open = false"
                                                class="w-full text-left px-4 py-2 text-xs font-bold transition-colors cursor-pointer flex items-center justify-between {{ $selectedAssetId == $ast->id ? 'bg-emerald-100/80 dark:bg-emerald-900/60 text-emerald-900 dark:text-emerald-200' : 'text-gray-700 dark:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-800' }}">
                                            <span>{{ $ast->name }}</span>
                                            <span class="px-2 py-0.5 rounded-md border border-emerald-300 bg-emerald-50 text-emerald-700 font-mono text-[10px] font-bold">
                                                {{ toBanglaNum($ast->current_qty) }}
                                            </span>
                                        </button>
                                    @else
                                        <!-- Req 3: Unselectable Red 0 Badge for 0-stock products -->
                                        <div class="w-full text-left px-4 py-2 text-xs font-bold text-gray-300 dark:text-slate-600 cursor-not-allowed flex items-center justify-between bg-gray-50/50 dark:bg-slate-950/50 select-none">
                                            <span class="line-through">{{ $ast->name }}</span>
                                            <span class="px-2 py-0.5 rounded-md border border-rose-200 bg-rose-50 text-rose-500 font-mono text-[10px] font-bold">
                                                0
                                            </span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        @error('selectedAssetId') <span class="text-rose-500 text-[10px] font-bold mt-0.5 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">👤 কার কাছে? *</label>
                            <input type="text" wire:model="issuedTo" placeholder="নাম লিখুন"
                                   class="w-full px-3.5 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 font-semibold">
                            @error('issuedTo') <span class="text-rose-500 text-[10px] font-bold mt-0.5 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">📍 লোকেশন</label>
                            <input type="text" wire:model="issueLocation" placeholder="কোথায় ব্যবহার হবে?"
                                   class="w-full px-3.5 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 font-semibold">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1"># পরিমাণ *</label>
                            <input type="number" wire:model="issueQty" placeholder="0"
                                   class="w-full px-3.5 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none font-mono font-bold">
                            @error('issueQty') <span class="text-rose-500 text-[10px] font-bold mt-0.5 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                             <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">📅 ইস্যু তারিখ *</label>
                             <div class="relative" wire:ignore>
                                 <input type="text" data-flatpickr data-wire-prop="issueDate"
                                        data-default="{{ $issueDate }}"
                                        placeholder="তারিখ নির্বাচন করুন" readonly
                                        class="w-full pl-3 pr-8 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none font-semibold cursor-pointer">
                                 <span class="absolute right-2.5 top-2.5 text-emerald-500 pointer-events-none">
                                     <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                 </span>
                             </div>
                             @error('issueDate') <span class="text-rose-500 text-[10px] font-bold mt-0.5 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">প্রমাণ (ছবি)</label>
                        <label class="cursor-pointer border border-dashed border-gray-300 dark:border-slate-700 hover:border-emerald-500 rounded-xl p-2.5 bg-gray-50 dark:bg-slate-950 flex items-center justify-center gap-1.5 font-bold text-gray-600 dark:text-slate-300">
                            📷 ছবি তুলুন / আপলোড করুন
                            <input type="file" wire:model="issueImage" class="hidden">
                        </label>
                    </div>

                    @if($issueImage)
                        <div class="flex items-center justify-between p-2 rounded-xl bg-gray-50 dark:bg-slate-950 border border-gray-200 dark:border-slate-800 text-[11px]">
                            <div class="flex items-center gap-2 truncate">
                                <img src="{{ $issueImage->temporaryUrl() }}" class="w-8 h-8 object-cover rounded-lg">
                                <span class="truncate font-semibold text-gray-700 dark:text-slate-200">{{ $issueImage->getClientOriginalName() }}</span>
                            </div>
                            <button type="button" wire:click="$set('issueImage', null)" class="text-rose-500 p-1 cursor-pointer">🗑️</button>
                        </div>
                    @endif

                    <div>
                        <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">মন্তব্য</label>
                        <textarea wire:model="issueNotes" rows="2" placeholder="প্রয়োজনীয় নোট লিখুন..."
                                  class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 font-semibold"></textarea>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="button" wire:click="saveIssue()" wire:loading.attr="disabled"
                            class="w-full py-3 bg-[#034C3C] hover:bg-emerald-800 text-white rounded-2xl font-bold text-xs shadow-md cursor-pointer transition-all flex items-center justify-center gap-1">
                        <span wire:loading.remove wire:target="saveIssue">কনফার্ম</span>
                        <span wire:loading wire:target="saveIssue">ইস্যু হচ্ছে...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal 1: `মালামাল ফেরত নেওয়া` Detailed Return Modal (Req 5: Dynamic Exclusive Input Resets) -->
    @if($showReturnModal && $selectedIssueForReturn)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs"
             wire:click.self="$set('showReturnModal', false)">
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-gray-150 dark:border-slate-800 w-full max-w-md p-6 space-y-4"
                 wire:click.stop>
                <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-800 pb-3">
                    <div class="flex items-center gap-2">
                        <div class="p-2 bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400 rounded-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 15v-1a4 4 0 00-4-4H4m0 0l4-4m-4 4l4 4"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-gray-900 dark:text-white">মালামাল ফেরত নেওয়া</h3>
                            <p class="text-[11px] text-gray-400 font-semibold">স্টক ইনভেন্টরি ম্যানেজমেন্ট</p>
                        </div>
                    </div>
                    <button type="button" wire:click="$set('showReturnModal', false)" class="text-gray-400 hover:text-gray-600 cursor-pointer p-1 rounded-full bg-gray-100 dark:bg-slate-800">✕</button>
                </div>

                <div class="p-4 bg-gray-50 dark:bg-slate-950 rounded-2xl border-l-4 border-emerald-500 flex items-center justify-between">
                    <div>
                        <h4 class="font-extrabold text-sm text-gray-900 dark:text-white">{{ $selectedIssueForReturn->asset ? $selectedIssueForReturn->asset->name : '—' }}</h4>
                        <span class="text-[11px] text-gray-400 font-semibold block mt-0.5">📅 ইস্যু ডেট: {{ \Carbon\Carbon::parse($selectedIssueForReturn->issue_date)->format('d F, Y') }}</span>
                    </div>
                    <div class="p-3 bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-slate-800 text-center shadow-xs">
                        <span class="text-[10px] text-gray-400 font-bold block">মোট ইস্যু</span>
                        <span class="text-lg font-black text-sky-600 font-mono">{{ toBanglaNum($selectedIssueForReturn->quantity) }}</span>
                    </div>
                </div>

                <div class="space-y-3.5 text-xs">
                    <div>
                        <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">ফেরতকারী কর্মীর নাম *</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-gray-400">👤</span>
                            <input type="text" wire:model="returnEmployeeName" placeholder="কর্মীর নাম"
                                   class="w-full pl-8 pr-3 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 font-semibold">
                        </div>
                    </div>

                    <!-- Req 5: Exclusive single-choice input behavior -->
                    <div class="grid grid-cols-3 gap-2 text-[11px]">
                        <div>
                            <label class="block font-bold text-emerald-600 mb-1">ভালো (ওকে)</label>
                            <input type="number" wire:model.live="returnGoodQty" placeholder="0"
                                   class="w-full px-3 py-2 text-center rounded-xl border-2 border-emerald-400 bg-white dark:bg-slate-950 text-gray-900 dark:text-white focus:outline-none font-mono font-bold">
                        </div>
                        <div>
                            <label class="block font-bold text-rose-500 mb-1">নষ্ট (ড্যামেজ)</label>
                            <input type="number" wire:model.live="returnDamagedQty" placeholder="0"
                                   class="w-full px-3 py-2 text-center rounded-xl border-2 border-rose-300 bg-white dark:bg-slate-950 text-gray-900 dark:text-white focus:outline-none font-mono font-bold">
                        </div>
                        <div>
                            <label class="block font-bold text-amber-600 mb-1">হারানো (লস্ট)</label>
                            <input type="number" wire:model.live="returnLostQty" placeholder="0"
                                   class="w-full px-3 py-2 text-center rounded-xl border-2 border-amber-300 bg-white dark:bg-slate-950 text-gray-900 dark:text-white focus:outline-none font-mono font-bold">
                        </div>
                    </div>
                    @error('returnGoodQty') <span class="text-rose-500 text-[10px] font-bold block">{{ $message }}</span> @enderror

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">📅 ফেরতের তারিখ *</label>
                            <div class="relative" wire:ignore>
                                <input type="text" data-flatpickr data-wire-prop="returnDate"
                                       data-default="{{ $returnDate }}"
                                       placeholder="তারিখ নির্বাচন করুন" readonly
                                       class="w-full pl-3 pr-8 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none font-semibold cursor-pointer">
                                <span class="absolute right-2.5 top-2.5 text-emerald-500 pointer-events-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                </span>
                            </div>
                            @error('returnDate') <span class="text-rose-500 text-[10px] font-bold mt-0.5 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">প্রমাণ (ছবি)</label>
                            <label class="cursor-pointer border border-dashed border-gray-300 dark:border-slate-700 hover:border-emerald-500 rounded-xl p-2.5 bg-gray-50 dark:bg-slate-950 flex items-center justify-center gap-1.5 font-bold text-gray-600 dark:text-slate-300">
                                📷 ছবি তুলুন
                                <input type="file" wire:model="returnImage" class="hidden">
                            </label>
                        </div>
                    </div>

                    @if($returnImage)
                        <div class="flex items-center justify-between p-2 rounded-xl bg-gray-50 dark:bg-slate-950 border border-gray-200 dark:border-slate-800 text-[11px]">
                            <div class="flex items-center gap-2 truncate">
                                <img src="{{ $returnImage->temporaryUrl() }}" class="w-8 h-8 object-cover rounded-lg">
                                <span class="truncate font-semibold text-gray-700 dark:text-slate-200">{{ $returnImage->getClientOriginalName() }}</span>
                            </div>
                            <button type="button" wire:click="$set('returnImage', null)" class="text-rose-500 p-1 cursor-pointer">🗑️</button>
                        </div>
                    @endif
                </div>

                <div class="pt-2">
                    <button type="button" wire:click="saveReturn()" wire:loading.attr="disabled"
                            class="w-full py-3 bg-[#034C3C] hover:bg-emerald-800 text-white rounded-2xl font-bold text-xs shadow-md cursor-pointer transition-all flex items-center justify-center gap-1">
                        <span wire:loading.remove wire:target="saveReturn">✓ ফেরত সম্পন্ন করুন</span>
                        <span wire:loading wire:target="saveReturn">প্রসেস হচ্ছে...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal 2: Image Quick View Modal -->
    @if($showQuickViewModal && $quickViewImageUrl)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md"
             wire:click.self="$set('showQuickViewModal', false)">
            <div class="relative max-w-3xl w-full bg-white dark:bg-slate-900 rounded-3xl p-4 shadow-2xl space-y-3" wire:click.stop>
                <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-800 pb-2">
                    <h4 class="font-extrabold text-sm text-gray-800 dark:text-white truncate">{{ $quickViewTitle ?: 'ছবি প্রিভিউ' }}</h4>
                    <button type="button" wire:click="$set('showQuickViewModal', false)" class="text-gray-400 hover:text-gray-600 cursor-pointer p-1 rounded-full bg-gray-100 dark:bg-slate-800">✕</button>
                </div>
                <div class="flex items-center justify-center max-h-[75vh] overflow-hidden rounded-2xl bg-slate-950">
                    <img src="{{ $quickViewImageUrl }}" alt="Quick View" class="max-h-[75vh] w-auto object-contain rounded-2xl">
                </div>
            </div>
        </div>
    @endif

    <!-- Modal 3: `মেরামত রেকর্ড` Modal for Damaged Items -->
    @if($showRepairModal && $selectedAssetForRepair)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs"
             wire:click.self="$set('showRepairModal', false)">
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-gray-150 dark:border-slate-800 w-full max-w-md overflow-hidden"
                 wire:click.stop>
                <div class="bg-amber-600 text-white p-5 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="p-2 bg-white/20 rounded-xl">🔧</div>
                        <div>
                            <h3 class="text-base font-extrabold">মেরামত রেকর্ড</h3>
                            <p class="text-[11px] text-amber-100 font-semibold">মেনটেইনেন্স আপডেট</p>
                        </div>
                    </div>
                    <button type="button" wire:click="$set('showRepairModal', false)" class="text-white hover:text-amber-200 cursor-pointer p-1">✕</button>
                </div>

                <div class="p-6 space-y-4 text-xs">
                    <div class="p-3 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900/50 rounded-2xl flex items-start gap-2 text-amber-800 dark:text-amber-300">
                        <span class="font-extrabold text-sm">ⓘ</span>
                        <div class="font-semibold">
                            বর্তমানে <strong>{{ $selectedAssetForRepair->name }}</strong><br>
                            মোট নষ্ট স্টকে আছে: <strong class="font-mono text-sm text-amber-900 dark:text-amber-200">{{ toBanglaNum($selectedAssetForRepair->damaged_qty) }}</strong> টি
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">🔧 মেরামতের পরিমাণ *</label>
                        <input type="number" wire:model="repairQty" placeholder="0"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none font-mono font-bold">
                        @error('repairQty') <span class="text-rose-500 text-[10px] font-bold mt-0.5 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">📅 মেরামতের তারিখ *</label>
                        <div class="relative" wire:ignore>
                            <input type="text" data-flatpickr data-wire-prop="repairDate"
                                   data-default="{{ $repairDate }}"
                                   placeholder="তারিখ নির্বাচন করুন" readonly
                                   class="w-full pl-3.5 pr-10 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none font-semibold cursor-pointer">
                            <span class="absolute right-3 top-3 text-amber-500 pointer-events-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            </span>
                        </div>
                        @error('repairDate') <span class="text-rose-500 text-[10px] font-bold mt-0.5 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">📝 অতিরিক্ত নোট (যদি থাকে)</label>
                        <textarea wire:model="repairNotes" rows="2" placeholder="মেরামত সম্পর্কে কিছু লিখুন..."
                                  class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none focus:border-amber-500 font-semibold"></textarea>
                    </div>

                    <div class="pt-2">
                        <button type="button" wire:click="saveRepair()" wire:loading.attr="disabled"
                                class="w-full py-3 bg-amber-600 hover:bg-amber-700 text-white rounded-2xl font-bold text-xs shadow-md cursor-pointer transition-all flex items-center justify-center gap-1">
                            <span wire:loading.remove wire:target="saveRepair">✓ রেকর্ড সেভ করুন</span>
                            <span wire:loading wire:target="saveRepair">প্রসেস হচ্ছে...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal 4: `পাওয়া গেছে` Found Modal for Lost Items -->
    @if($showFoundModal && $selectedAssetForFound)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs"
             wire:click.self="$set('showFoundModal', false)">
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-gray-150 dark:border-slate-800 w-full max-w-xs p-5 space-y-4 text-center"
                 wire:click.stop>
                <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto text-xl font-extrabold">
                    ✓
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-gray-900 dark:text-white">আইটেমটি কি পাওয়া গেছে?</h3>
                    <p class="text-[11px] text-gray-500 dark:text-slate-400 mt-1">এটি অটোমেটিক মেইন স্টকে যুক্ত হবে।</p>
                </div>
                <div class="flex items-center justify-center gap-3 pt-2">
                    <button type="button" wire:click="$set('showFoundModal', false)" class="px-4 py-2 bg-gray-200 dark:bg-slate-800 text-gray-700 dark:text-slate-200 rounded-xl font-bold text-xs cursor-pointer">
                        না
                    </button>
                    <button type="button" wire:click="confirmFound()" wire:loading.attr="disabled" class="px-4 py-2 bg-[#034C3C] hover:bg-emerald-800 text-white rounded-xl font-bold text-xs cursor-pointer shadow-sm">
                        হ্যাঁ, যোগ করুন
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal 5: Asset Add/Edit Modal (Req 2: Category Dropdown outside click fix & styled Warranty Datepicker) -->
    @if($showAssetModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs"
             wire:click.self="$set('showAssetModal', false)">
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-gray-150 dark:border-slate-800 w-full max-w-2xl p-6 space-y-4"
                 wire:click.stop>
                <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-800 pb-3">
                    <div>
                        <h3 class="text-base font-extrabold text-gray-900 dark:text-white flex items-center gap-2">
                            <span>📦</span> {{ $editingAssetId ? 'অ্যাসেট তথ্য এডিট' : 'নতুন মালামাল এন্ট্রি' }}
                        </h3>
                        <p class="text-[11px] text-gray-500 dark:text-slate-400 font-semibold">স্টকের বিবরণ এবং ছবি যুক্ত করুন</p>
                    </div>
                    <button type="button" wire:click="$set('showAssetModal', false)" class="text-gray-400 hover:text-gray-600 cursor-pointer p-1 rounded-full bg-gray-100 dark:bg-slate-800">✕</button>
                </div>

                <div x-data="{ 
                    qty: @entangle('initialQty').live, 
                    price: @entangle('unitPrice').live, 
                    get totalPrice() { 
                        let q = parseFloat(this.qty) || 0; 
                        let p = parseFloat(this.price) || 0; 
                        return (q * p).toFixed(2); 
                    } 
                }" class="grid grid-cols-1 md:grid-cols-2 gap-5 text-xs">
                    
                    <div class="space-y-3.5">
                        <div>
                            <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">প্রোডাক্টের নাম *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-400">⊞</span>
                                <input type="text" wire:model="assetName" placeholder="নাম লিখুন"
                                       class="w-full pl-8 pr-3 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 font-semibold">
                            </div>
                            @error('assetName') <span class="text-rose-500 text-[10px] font-bold mt-0.5 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Root Dropdown Category Selector with Outside Click Close -->
                        <div>
                            <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">ক্যাটাগরি</label>
                            <div x-data="{ open: false }" class="relative text-xs" @click.outside="open = false">
                                @php
                                    $selectedCatName = $categories->firstWhere('id', $assetCategoryId)?->name ?: 'ক্যাটাগরি নির্বাচন করুন';
                                @endphp
                                <button @click.stop="open = !open" type="button"
                                        class="w-full flex items-center justify-between px-3.5 py-2.5 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white font-bold rounded-xl border border-gray-200 dark:border-slate-700 cursor-pointer">
                                    <span>{{ $selectedCatName }}</span>
                                    <svg class="w-4 h-4 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="open" x-cloak @click.stop
                                     class="absolute left-0 right-0 mt-1 z-[9999] bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-2xl shadow-xl py-1 max-h-52 overflow-y-auto">
                                    @foreach($categories as $cat)
                                        <button type="button" wire:click="selectAssetCategory({{ $cat->id }})" @click="open = false"
                                                class="w-full text-left px-4 py-2.5 text-xs font-bold transition-colors cursor-pointer flex items-center justify-between {{ $assetCategoryId == $cat->id ? 'bg-emerald-100/80 dark:bg-emerald-900/60 text-emerald-900 dark:text-emerald-200' : 'text-gray-700 dark:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-800' }}">
                                            <span>{{ $cat->name }}</span>
                                            @if($assetCategoryId == $cat->id) <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> @endif
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">ভেন্ডর/দোকান</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-400">🏪</span>
                                <input type="text" wire:model="vendor" placeholder="কার থেকে কেনা?"
                                       class="w-full pl-8 pr-3 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none font-semibold">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">পরিমাণ *</label>
                                <input type="number" wire:model.live="initialQty" x-model="qty" placeholder="0" {{ $editingAssetId ? 'disabled' : '' }}
                                       class="w-full px-3.5 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none font-mono font-bold disabled:opacity-50">
                                @error('initialQty') <span class="text-rose-500 text-[10px] font-bold mt-0.5 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">একক মূল্য *</label>
                                <input type="number" step="0.01" wire:model.live="unitPrice" x-model="price" placeholder="0"
                                       class="w-full px-3.5 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none font-mono font-bold">
                                @error('unitPrice') <span class="text-rose-500 text-[10px] font-bold mt-0.5 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="p-3 bg-sky-50/60 dark:bg-slate-800/60 rounded-2xl border border-sky-100 dark:border-slate-800 text-center">
                            <span class="text-[10px] text-gray-500 font-bold block uppercase">মোট মূল্য</span>
                            <span class="text-lg font-black text-sky-700 dark:text-sky-400 font-mono">
                                ৳ <span x-text="totalPrice">0</span>
                            </span>
                        </div>
                    </div>

                    <div class="space-y-4 flex flex-col justify-between">
                        <div>
                            <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">প্রোডাক্টের ছবি</label>
                            <label class="cursor-pointer border-2 border-dashed border-gray-200 dark:border-slate-700 hover:border-emerald-500 rounded-2xl p-6 bg-gray-50 dark:bg-slate-950 flex flex-col items-center justify-center text-center space-y-2 transition-all">
                                @if($assetImage)
                                    <img src="{{ $assetImage->temporaryUrl() }}" class="h-28 object-cover rounded-xl shadow-xs">
                                @else
                                    <div class="p-3 bg-white dark:bg-slate-900 rounded-full shadow-xs text-gray-400">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                    </div>
                                    <span class="text-xs font-bold text-gray-500 dark:text-slate-400">ছবি আপলোড করুন</span>
                                @endif
                                <input type="file" wire:model="assetImage" class="hidden">
                            </label>
                        </div>

                        <!-- Warranty Flatpickr Datepicker -->
                        <div class="p-4 bg-sky-50/50 dark:bg-slate-800/40 border border-sky-100 dark:border-slate-800 rounded-2xl space-y-3">
                            <label class="flex items-center gap-2 cursor-pointer font-bold text-gray-800 dark:text-slate-200 text-xs">
                                <input type="checkbox" wire:model.live="hasWarranty" class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                                <span>ওয়ারেন্টি আছে?</span>
                            </label>

                            @if($hasWarranty)
                                <div x-transition class="pt-1">
                                    <label class="block font-bold text-gray-600 dark:text-slate-400 text-[11px] mb-1">মেয়াদ শেষ কবে? 📅</label>
                                    <div class="relative" wire:ignore>
                                        <input type="text" data-flatpickr data-wire-prop="warrantyExpiry"
                                               data-default="{{ $warrantyExpiry }}"
                                               placeholder="তারিখ নির্বাচন করুন" readonly
                                               class="w-full pl-3 pr-8 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 font-semibold shadow-xs cursor-pointer">
                                        <span class="absolute right-2.5 top-2.5 text-emerald-500 pointer-events-none">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                        </span>
                                    </div>
                                    @error('warrantyExpiry') <span class="text-rose-500 text-[10px] font-bold mt-0.5 block">{{ $message }}</span> @enderror
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="pt-3 border-t border-gray-100 dark:border-slate-800">
                    <button type="button" wire:click="saveAsset()" wire:loading.attr="disabled"
                            class="w-full py-3 bg-[#034C3C] hover:bg-emerald-800 text-white rounded-2xl font-bold text-xs shadow-md cursor-pointer transition-all">
                        <span wire:loading.remove wire:target="saveAsset">স্টকে যুক্ত করুন</span>
                        <span wire:loading wire:target="saveAsset">সংরক্ষণ হচ্ছে...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal 6: Enlarged Product Detail Modal with Tab System -->
    @if($showAssetViewModal && $selectedAssetForView)
        @php
            $assetIssues = \App\Models\AssetIssue::where('asset_id', $selectedAssetForView->id)->where('status','issued')->orderBy('issue_date','desc')->get();
            $assetReturnHistory = \App\Models\AssetIssue::where('asset_id', $selectedAssetForView->id)->where('status','returned')->orderBy('return_date','desc')->get();
            $assetPurchaseHistory = \App\Models\AssetHistory::where('asset_id', $selectedAssetForView->id)->where('action_type','add_stock')->orderBy('created_at','desc')->get();
        @endphp
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs"
             x-data="{ quickView: false, quickViewUrl: '' }"
             @click.self="if(quickView){ quickView=false; } else { $wire.set('showAssetViewModal', false); }">
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-gray-150 dark:border-slate-800 w-full max-w-2xl overflow-hidden"
                 wire:click.stop>

                <!-- Header with image + info + actions -->
                <div class="flex items-start gap-4 p-5 bg-gray-50 dark:bg-slate-950 border-b border-gray-100 dark:border-slate-800">
                    <!-- Product Image -->
                    <div class="relative flex-shrink-0 group cursor-pointer"
                         @click="quickView = true; quickViewUrl = '{{ $selectedAssetForView->image ? Storage::url($selectedAssetForView->image) : '' }}'">
                        @if($selectedAssetForView->image)
                            <img src="{{ Storage::url($selectedAssetForView->image) }}" class="w-20 h-20 object-cover rounded-2xl border-2 border-emerald-200 dark:border-emerald-800 shadow">
                            @if($selectedAssetForView->current_qty == 0)
                                <span class="absolute bottom-0 left-0 right-0 bg-rose-500/80 text-white text-[8px] font-black text-center py-0.5 rounded-b-2xl">OUT OF STOCK</span>
                            @endif
                            <div class="absolute inset-0 bg-black/50 rounded-2xl opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity duration-200">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </div>
                        @else
                            <div class="w-20 h-20 rounded-2xl bg-gray-200 dark:bg-slate-800 flex items-center justify-center border-2 border-gray-200 dark:border-slate-700">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </div>
                        @endif
                    </div>

                    <!-- Name + Category + Stats -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <h3 class="text-base font-extrabold text-gray-900 dark:text-white leading-tight">{{ $selectedAssetForView->name }}</h3>
                                <p class="text-[11px] text-gray-400 font-semibold mt-0.5 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                                    {{ $selectedAssetForView->category ? $selectedAssetForView->category->name : '—' }}
                                </p>
                            </div>
                            <div class="flex items-center gap-1.5 flex-shrink-0">
                                <button type="button" wire:click="openAssetModal({{ $selectedAssetForView->id }})"
                                        class="px-3 py-1.5 bg-sky-500 hover:bg-sky-600 text-white text-[11px] font-bold rounded-xl flex items-center gap-1 cursor-pointer transition-all">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    এডিট
                                </button>
                                <button type="button" wire:click="deleteAsset({{ $selectedAssetForView->id }})" wire:confirm="নিশ্চিত করুন: এই আইটেমটি মুছে দিতে চান?"
                                        class="px-3 py-1.5 bg-rose-500 hover:bg-rose-600 text-white text-[11px] font-bold rounded-xl flex items-center gap-1 cursor-pointer transition-all">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    ডিলিট
                                </button>
                                <button type="button" wire:click="$set('showAssetViewModal', false)" class="p-1.5 text-gray-400 hover:text-gray-600 cursor-pointer bg-gray-100 dark:bg-slate-800 rounded-xl">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </div>
                        <!-- 3 key metrics -->
                        <div class="mt-3 grid grid-cols-3 gap-2 text-xs">
                            <div class="bg-white dark:bg-slate-900 rounded-xl p-2.5 border border-gray-100 dark:border-slate-800 text-center shadow-xs">
                                <span class="text-[10px] text-gray-400 font-bold block">বর্তমান স্টক</span>
                                <span class="text-base font-black {{ $selectedAssetForView->current_qty > 0 ? 'text-emerald-600' : 'text-rose-500' }}">{{ toBanglaNum($selectedAssetForView->current_qty) }}</span>
                            </div>
                            <div class="bg-white dark:bg-slate-900 rounded-xl p-2.5 border border-gray-100 dark:border-slate-800 text-center shadow-xs">
                                <span class="text-[10px] text-gray-400 font-bold block">গড় মূল্য</span>
                                <span class="text-sm font-black text-sky-600">৳{{ toBanglaNum(number_format($selectedAssetForView->unit_price, 0)) }}</span>
                            </div>
                            <div class="bg-white dark:bg-slate-900 rounded-xl p-2.5 border border-gray-100 dark:border-slate-800 text-center shadow-xs">
                                <span class="text-[10px] text-gray-400 font-bold block">মোট ভ্যালু (বর্তমান)</span>
                                <span class="text-sm font-black text-[#034C3C] dark:text-emerald-400">৳{{ toBanglaNum(number_format($selectedAssetForView->current_qty * $selectedAssetForView->unit_price, 0)) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab Navigation + Tab Content -->
                <div x-data="{ tab: 'issues' }" class="p-5 space-y-3">
                    <!-- Tabs -->
                    <div class="flex items-center gap-1 text-[11px] font-bold border-b border-gray-100 dark:border-slate-800 pb-0">
                        <button type="button" @click="tab='issues'"
                                :class="tab==='issues' ? 'text-[#034C3C] dark:text-emerald-400 border-b-2 border-[#034C3C] dark:border-emerald-400' : 'text-gray-400 hover:text-gray-600 dark:hover:text-slate-300'"
                                class="px-3 py-2.5 -mb-px transition-all cursor-pointer flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            চলমান ইস্যু
                            <span class="px-1.5 py-0.5 rounded-full text-[9px] font-black bg-sky-100 text-sky-700 dark:bg-sky-900 dark:text-sky-300">{{ $assetIssues->count() }}</span>
                        </button>
                        <button type="button" @click="tab='returns'"
                                :class="tab==='returns' ? 'text-[#034C3C] dark:text-emerald-400 border-b-2 border-[#034C3C] dark:border-emerald-400' : 'text-gray-400 hover:text-gray-600 dark:hover:text-slate-300'"
                                class="px-3 py-2.5 -mb-px transition-all cursor-pointer flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 15v-1a4 4 0 00-4-4H4m0 0l4-4m-4 4l4 4"/></svg>
                            রিটার্ন হিস্ট্রি
                            <span class="px-1.5 py-0.5 rounded-full text-[9px] font-black bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300">{{ $assetReturnHistory->count() }}</span>
                        </button>
                        <button type="button" @click="tab='purchases'"
                                :class="tab==='purchases' ? 'text-[#034C3C] dark:text-emerald-400 border-b-2 border-[#034C3C] dark:border-emerald-400' : 'text-gray-400 hover:text-gray-600 dark:hover:text-slate-300'"
                                class="px-3 py-2.5 -mb-px transition-all cursor-pointer flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            ক্রয় হিস্ট্রি
                            <span class="px-1.5 py-0.5 rounded-full text-[9px] font-black bg-amber-100 text-amber-700 dark:bg-amber-900 dark:text-amber-300">{{ $assetPurchaseHistory->count() }}</span>
                        </button>
                    </div>

                    <!-- Tab: চলমান ইস্যু -->
                    <div x-show="tab==='issues'" x-cloak class="max-h-64 overflow-y-auto">
                        <table class="w-full text-xs border-collapse">
                            <thead>
                                <tr class="bg-[#034C3C] text-white">
                                    <th class="py-2.5 px-4 font-bold text-left rounded-tl-xl">নাম</th>
                                    <th class="py-2.5 px-4 font-bold text-left">লোকেশন</th>
                                    <th class="py-2.5 px-4 font-bold text-center">পরিমাণ</th>
                                    <th class="py-2.5 px-4 font-bold text-center rounded-tr-xl">ছবি</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                                @forelse($assetIssues as $iss)
                                    <tr class="bg-white dark:bg-slate-900 hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors">
                                        <td class="py-2.5 px-4 font-bold text-gray-800 dark:text-white">{{ $iss->issued_to }}</td>
                                        <td class="py-2.5 px-4 text-gray-600 dark:text-slate-300">{{ $iss->location ?: '—' }}</td>
                                        <td class="py-2.5 px-4 font-mono font-black text-center text-sky-600">{{ toBanglaNum($iss->quantity) }}</td>
                                        <td class="py-2.5 px-4 text-center">
                                            @php
                                                $issImg = $iss->image ? Storage::url($iss->image) : ($selectedAssetForView->image ? Storage::url($selectedAssetForView->image) : null);
                                            @endphp
                                            @if($issImg)
                                                <div class="relative group w-8 h-8 mx-auto rounded-xl overflow-hidden cursor-pointer"
                                                     @click="quickView = true; quickViewUrl = '{{ $issImg }}'">
                                                    <img src="{{ $issImg }}" class="w-8 h-8 object-cover rounded-xl border border-gray-200 dark:border-slate-700">
                                                    <div class="absolute inset-0 bg-slate-950/60 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white transition-opacity duration-200">
                                                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-gray-400 font-bold">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-10 text-center">
                                            <div class="flex flex-col items-center justify-center space-y-2 text-gray-400">
                                                <div class="p-3 bg-gray-100 dark:bg-slate-800 rounded-2xl">
                                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                                </div>
                                                <span class="text-xs font-semibold">No data</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Tab: রিটার্ন হিস্ট্রি -->
                    <div x-show="tab==='returns'" x-cloak class="max-h-64 overflow-y-auto">
                        <table class="w-full text-xs border-collapse">
                            <thead>
                                <tr class="bg-[#034C3C] text-white">
                                    <th class="py-2.5 px-4 font-bold text-left rounded-tl-xl">তারিখ</th>
                                    <th class="py-2.5 px-4 font-bold text-left">ফেরতকারী</th>
                                    <th class="py-2.5 px-4 font-bold text-center">অবস্থা</th>
                                    <th class="py-2.5 px-4 font-bold text-center rounded-tr-xl">প্রমাণ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                                @forelse($assetReturnHistory as $ret)
                                    <tr class="bg-white dark:bg-slate-900 hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors">
                                        <td class="py-2.5 px-4 font-mono text-gray-600 dark:text-slate-300">{{ \Carbon\Carbon::parse($ret->return_date ?: $ret->created_at)->format('d-m-Y') }}</td>
                                        <td class="py-2.5 px-4 font-bold text-gray-800 dark:text-white">{{ $ret->return_employee_name ?: $ret->issued_to }}</td>
                                        <td class="py-2.5 px-4 text-center">
                                            @if($ret->return_good_qty > 0)
                                                <span class="px-2 py-0.5 rounded-lg border border-emerald-300 bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300 font-mono text-[11px] font-bold">G:{{ toBanglaNum($ret->return_good_qty) }}</span>
                                            @elseif($ret->return_damaged_qty > 0)
                                                <span class="px-2 py-0.5 rounded-lg border border-amber-300 bg-amber-50 text-amber-700 dark:bg-amber-950 dark:text-amber-300 font-mono text-[11px] font-bold">D:{{ toBanglaNum($ret->return_damaged_qty) }}</span>
                                            @elseif($ret->return_lost_qty > 0)
                                                <span class="px-2 py-0.5 rounded-lg border border-rose-300 bg-rose-50 text-rose-700 dark:bg-rose-950 dark:text-rose-300 font-mono text-[11px] font-bold">L:{{ toBanglaNum($ret->return_lost_qty) }}</span>
                                            @else
                                                <span class="px-2 py-0.5 rounded-lg border border-emerald-300 bg-emerald-50 text-emerald-700 font-bold text-[10px]">সম্পন্ন</span>
                                            @endif
                                        </td>
                                        <td class="py-2.5 px-4 text-center">
                                            @php
                                                $retImg = $ret->return_image ? Storage::url($ret->return_image) : ($ret->image ? Storage::url($ret->image) : ($selectedAssetForView->image ? Storage::url($selectedAssetForView->image) : null));
                                            @endphp
                                            @if($retImg)
                                                <div class="relative group w-8 h-8 mx-auto rounded-xl overflow-hidden cursor-pointer"
                                                     @click="quickView = true; quickViewUrl = '{{ $retImg }}'">
                                                    <img src="{{ $retImg }}" class="w-8 h-8 object-cover rounded-xl border border-gray-200 dark:border-slate-700">
                                                    <div class="absolute inset-0 bg-slate-950/60 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white transition-opacity duration-200">
                                                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-gray-400 font-bold">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-10 text-center">
                                            <div class="flex flex-col items-center justify-center space-y-2 text-gray-400">
                                                <div class="p-3 bg-gray-100 dark:bg-slate-800 rounded-2xl">
                                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                                </div>
                                                <span class="text-xs font-semibold">No data</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Tab: ক্রয় হিস্ট্রি -->
                    <div x-show="tab==='purchases'" x-cloak class="max-h-64 overflow-y-auto">
                        <table class="w-full text-xs border-collapse">
                            <thead>
                                <tr class="bg-[#034C3C] text-white">
                                    <th class="py-2.5 px-4 font-bold text-left rounded-tl-xl">তারিখ</th>
                                    <th class="py-2.5 px-4 font-bold text-left">ভেন্ডর</th>
                                    <th class="py-2.5 px-4 font-bold text-center">পরিমাণ</th>
                                    <th class="py-2.5 px-4 font-bold text-left">একক মূল্য</th>
                                    <th class="py-2.5 px-4 font-bold text-left">মোট খরচ</th>
                                    <th class="py-2.5 px-4 font-bold text-left">ওয়ারেন্টি</th>
                                    <th class="py-2.5 px-4 font-bold text-center rounded-tr-xl">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                                @forelse($assetPurchaseHistory as $ph)
                                    <tr class="bg-white dark:bg-slate-900 hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors">
                                        <td class="py-2.5 px-4 font-mono text-gray-600 dark:text-slate-300">{{ \Carbon\Carbon::parse($ph->created_at)->format('d-m-Y') }}</td>
                                        <td class="py-2.5 px-4 font-bold text-gray-800 dark:text-white">{{ $selectedAssetForView->vendor ?: '—' }}</td>
                                        <td class="py-2.5 px-4 font-mono font-black text-center text-amber-600">{{ toBanglaNum($ph->quantity) }}</td>
                                        <td class="py-2.5 px-4 font-mono font-bold text-emerald-600">৳{{ toBanglaNum(number_format($selectedAssetForView->unit_price, 0)) }}</td>
                                        <td class="py-2.5 px-4 font-mono font-bold text-amber-700">৳{{ toBanglaNum(number_format($ph->quantity * $selectedAssetForView->unit_price, 0)) }}</td>
                                        <td class="py-2.5 px-4">
                                            @if($selectedAssetForView->has_warranty && $selectedAssetForView->warranty_expiry)
                                                @php
                                                    $wExpiry = \Carbon\Carbon::parse($selectedAssetForView->warranty_expiry);
                                                @endphp
                                                @if($wExpiry->isPast())
                                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-700 border border-rose-200">
                                                        Expired ({{ $wExpiry->format('d-m-Y') }})
                                                    </span>
                                                @else
                                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 dark:bg-emerald-950 dark:text-emerald-300">
                                                        Active ({{ $wExpiry->format('d-m-Y') }})
                                                    </span>
                                                @endif
                                            @else
                                                <span class="text-gray-400 font-bold text-xs">—</span>
                                            @endif
                                        </td>
                                        <td class="py-2.5 px-4 text-center">
                                            <button type="button" wire:click="openPurchaseEditModal({{ $selectedAssetForView->id }})"
                                                    class="px-2.5 py-1 bg-sky-500 hover:bg-sky-600 text-white text-[11px] font-bold rounded-lg cursor-pointer flex items-center justify-center gap-1 mx-auto transition-all shadow-xs">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                এডিট
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-10 text-center">
                                            <div class="flex flex-col items-center justify-center space-y-2 text-gray-400">
                                                <div class="p-3 bg-gray-100 dark:bg-slate-800 rounded-2xl">
                                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                                </div>
                                                <span class="text-xs font-semibold">No data</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Quick Image Full-Screen Overlay -->
                <div x-show="quickView" x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute inset-0 z-[60] bg-black/80 rounded-3xl flex items-center justify-center p-4"
                     @click.self="quickView = false">
                    <div class="relative max-w-full max-h-full">
                        <button @click="quickView = false" class="absolute -top-3 -right-3 z-10 bg-white dark:bg-slate-800 text-gray-800 dark:text-white rounded-full p-1.5 shadow-xl cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                        <img :src="quickViewUrl" class="max-h-[70vh] max-w-full rounded-2xl shadow-2xl object-contain">
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal 7: Damage Asset Modal -->
    @if($showDamageModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs"
             wire:click.self="$set('showDamageModal', false)">
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-gray-150 dark:border-slate-800 w-full max-w-md p-6 space-y-4"
                 wire:click.stop>
                <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-800 pb-3">
                    <h3 class="text-base font-extrabold text-gray-800 dark:text-white">
                        {{ $damageType === 'damaged' ? 'নষ্ট আইটেম চিহ্নিতকরণ' : 'হারানো আইটেম চিহ্নিতকরণ' }}
                    </h3>
                    <button type="button" wire:click="$set('showDamageModal', false)" class="text-gray-400 hover:text-gray-600 cursor-pointer">✕</button>
                </div>
                <div class="space-y-3 text-xs">
                    <div>
                        <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">পরিমাণ (টি) *</label>
                        <input type="number" wire:model="damageQty" placeholder="0"
                               class="w-full px-3.5 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none font-mono font-bold">
                        @error('damageQty') <span class="text-rose-500 text-[10px] font-bold mt-0.5 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">কারণ / বিবরণ</label>
                        <textarea wire:model="damageNotes" rows="2" placeholder="কী কারণে নষ্ট বা হারিয়ে গেছে..."
                                  class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 font-semibold"></textarea>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100 dark:border-slate-800">
                    <button type="button" wire:click="$set('showDamageModal', false)" class="px-4 py-2 bg-gray-200 dark:bg-slate-800 text-gray-700 dark:text-slate-200 rounded-xl font-bold text-xs cursor-pointer">বাতিল</button>
                    <button type="button" wire:click="saveDamage()" wire:loading.attr="disabled" class="px-4 py-2 bg-rose-600 text-white rounded-xl font-bold text-xs cursor-pointer">
                        <span wire:loading.remove wire:target="saveDamage">নিশ্চিত করুন</span>
                        <span wire:loading wire:target="saveDamage">আপডেট হচ্ছে...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal 8: Purchase Info Edit Modal (Image 4 Design: ক্রয়ের তথ্য আপডেট করুন) -->
    @if($showPurchaseEditModal)
        <div class="fixed inset-0 z-[70] flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs"
             wire:click.self="$set('showPurchaseEditModal', false)">
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-gray-150 dark:border-slate-800 w-full max-w-md p-6 space-y-5 relative"
                 wire:click.stop>
                <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-800 pb-3">
                    <h3 class="text-base font-extrabold text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="text-blue-600 dark:text-blue-400">✏️</span>
                        <span>ক্রয়ের তথ্য আপডেট করুন</span>
                    </h3>
                    <button type="button" wire:click="$set('showPurchaseEditModal', false)"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-slate-200 cursor-pointer p-1.5 rounded-full bg-gray-100 dark:bg-slate-800 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="space-y-4 text-xs font-semibold">
                    <div>
                        <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1.5 flex items-center gap-1.5">
                            <span class="text-blue-500">🏪</span>
                            <span>ভেন্ডর নাম</span>
                        </label>
                        <input type="text" wire:model="editVendor" placeholder="ভেন্ডরের নাম লিখুন"
                               class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white font-bold focus:outline-none focus:border-blue-500 transition-all">
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1.5 flex items-center gap-1.5">
                            <span class="text-blue-500">🪙</span>
                            <span>একক মূল্য</span>
                        </label>
                        <input type="number" step="0.01" wire:model="editUnitPrice" placeholder="0.00"
                               class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white font-bold focus:outline-none focus:border-blue-500 font-mono transition-all">
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1.5 flex items-center gap-1.5">
                            <span class="text-blue-500">📅</span>
                            <span>ওয়ারেন্টি শেষ হওয়ার তারিখ</span>
                        </label>
                        <div class="relative" wire:ignore>
                            <input type="text" data-flatpickr data-wire-prop="editWarrantyExpiry"
                                   data-default="{{ $editWarrantyExpiry }}"
                                   placeholder="তারিখ নির্বাচন করুন" readonly
                                   class="w-full pl-4 pr-10 py-3 rounded-2xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-gray-800 dark:text-white font-bold focus:outline-none focus:border-blue-500 cursor-pointer shadow-xs">
                            <span class="absolute right-3 top-3.5 text-gray-400 pointer-events-none">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-3 border-t border-gray-100 dark:border-slate-800">
                    <button type="button" wire:click="$set('showPurchaseEditModal', false)"
                            class="px-6 py-3 bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-300 rounded-2xl font-bold text-xs hover:bg-gray-200 dark:hover:bg-slate-700 cursor-pointer transition-all">
                        বাতিল
                    </button>
                    <button type="button" wire:click="savePurchaseEdit()" wire:loading.attr="disabled"
                            class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold text-xs shadow-md cursor-pointer transition-all flex items-center justify-center gap-1.5">
                        <span wire:loading.remove wire:target="savePurchaseEdit">তথ্য আপডেট করুন 🚀</span>
                        <span wire:loading wire:target="savePurchaseEdit">আপডেট হচ্ছে...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
