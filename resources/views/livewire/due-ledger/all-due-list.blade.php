<div class="min-h-screen bg-gray-50 dark:bg-slate-950 transition-colors duration-300">

    <!-- Page Header Bar -->
    <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 px-4 sm:px-6 py-3 rounded-lg flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 transition-colors duration-300">
        <div>
            <h2 class="font-bold text-gray-800 dark:text-white text-sm leading-tight">বাকি টাকার লিস্ট</h2>
            <p class="text-[10px] text-gray-400 dark:text-gray-500 font-sans mt-0.5 font-semibold">সকল বকেয়া বাকি চালানের তালিকা</p>
        </div>

        <!-- Top Actions -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3 w-full sm:w-auto flex-wrap">
            <!-- Search -->
            <div class="relative w-full sm:w-auto">
                <input type="text" wire:model.live="search"
                       placeholder="সার্চ করুন..."
                       class="pl-4 pr-4 py-2 text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all w-full sm:w-48 font-sans font-semibold">
            </div>

            <div class="grid grid-cols-2 gap-2 w-full sm:flex sm:items-center sm:gap-3 sm:w-auto">
                <!-- Date Range: From -->
                <div class="flex items-center gap-1.5 w-full">
                    <span class="text-[10px] font-bold text-gray-550 dark:text-gray-400 font-sans whitespace-nowrap">শুরু:</span>
                    <div class="relative flex items-center w-full">
                        <input type="text"
                               data-flatpickr
                               data-wire-prop="dateFrom"
                               data-default="{{ $dateFrom }}"
                               wire:model="dateFrom"
                               placeholder="শুরু তারিখ"
                               readonly
                               class="pl-3 pr-8 py-2 text-xs rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/10 transition-all w-full font-sans font-semibold cursor-pointer">
                        <span class="absolute right-2.5 top-2.5 text-gray-450 pointer-events-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        </span>
                    </div>
                </div>
                <!-- Date Range: To -->
                <div class="flex items-center gap-1.5 w-full">
                    <span class="text-[10px] font-bold text-gray-550 dark:text-gray-400 font-sans whitespace-nowrap">শেষ:</span>
                    <div class="relative flex items-center w-full">
                        <input type="text"
                               data-flatpickr
                               data-wire-prop="dateTo"
                               data-default="{{ $dateTo }}"
                               wire:model="dateTo"
                               placeholder="শেষ তারিখ"
                               readonly
                               class="pl-3 pr-8 py-2 text-xs rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/10 transition-all w-full font-sans font-semibold cursor-pointer">
                        <span class="absolute right-2.5 top-2.5 text-gray-455 pointer-events-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        </span>
                    </div>
                </div>

                <!-- Season Filters Dropdown -->
                <div x-data="{ open: false }" class="relative w-full sm:w-auto">
                    <button @click="open = !open" type="button" 
                            class="flex items-center justify-between gap-1.5 px-3 py-2 bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-white font-bold rounded-xl text-xs border border-gray-200 dark:border-slate-700 focus:outline-none transition-all cursor-pointer w-full whitespace-nowrap">
                        <span>{{ $seasonFilter === 'all' ? 'সব সিজন' : ($seasonFilter === '25-26' ? '২৫-২৬' : '২৩-২৪') }}</span>
                        <svg class="w-3.5 h-3.5 transition-transform duration-200 text-gray-550" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    <div x-show="open" 
                         @click.outside="open = false"
                         class="absolute top-full mt-1.5 right-0 z-[999] w-full sm:w-36 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl shadow-xl overflow-hidden focus:outline-none"
                         x-cloak>
                        <div class="py-1">
                            <button type="button" wire:click="setSeasonFilter('all')" @click="open = false" class="w-full text-left px-3 py-2 text-xs font-bold text-gray-800 dark:text-white hover:bg-primary-50 dark:hover:bg-slate-800 hover:text-primary-dark dark:hover:text-primary-400 transition-colors font-sans">
                                সব সিজন
                            </button>
                            <button type="button" wire:click="setSeasonFilter('25-26')" @click="open = false" class="w-full text-left px-3 py-2 text-xs font-bold text-gray-800 dark:text-white hover:bg-primary-50 dark:hover:bg-slate-800 hover:text-primary-dark dark:hover:text-primary-400 transition-colors font-sans">
                                ২৫-২৬
                            </button>
                            <button type="button" wire:click="setSeasonFilter('23-24')" @click="open = false" class="w-full text-left px-3 py-2 text-xs font-bold text-gray-800 dark:text-white hover:bg-primary-50 dark:hover:bg-slate-800 hover:text-primary-dark dark:hover:text-primary-400 transition-colors font-sans">
                                ২৩-২৪
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Print Button -->
                <button type="button" onclick="window.print()"
                        class="px-3 py-2 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-200 text-xs font-semibold rounded-xl cursor-pointer transition-all font-sans border border-gray-200 dark:border-slate-700 flex items-center justify-center gap-1.5 shadow-sm w-full sm:w-auto whitespace-nowrap">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    প্রিন্ট করুন
                </button>
            </div>
        </div>
    </div>

    <!-- Flash Message -->
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
             x-transition:leave="transition ease-in duration-300" x-transition:leave-end="opacity-0"
             class="mx-4 sm:mx-6 mt-4 p-3.5 bg-primary-50 dark:bg-primary-950/20 border border-primary-200 dark:border-primary-900 text-primary-800 dark:text-primary-400 rounded-2xl text-xs font-medium font-sans" x-cloak>
            {{ session('message') }}
        </div>
    @endif

    <!-- Table Card -->
    <div class="py-4 sm:py-6">
        <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-lg shadow-sm overflow-hidden transition-colors duration-300">
            <!-- Summary Bar -->
            <div class="flex flex-wrap items-center justify-between gap-4 px-5 py-3.5 border-b border-gray-100 dark:border-slate-800 bg-blue-50/40 dark:bg-blue-950/10">
                <span class="text-xs text-gray-500 dark:text-gray-400 font-sans">মোট বাকি রেকর্ড: <strong class="text-gray-850 dark:text-white">{{ $challans->total() }} টি</strong></span>
                <span class="text-xs font-sans px-3 py-1 bg-primary text-white rounded-xl font-bold">সর্বমোট বাকিঃ {{ number_format($totalDueSum) }} টাকা</span>
            </div>

            <!-- Responsive Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse border border-gray-200 dark:border-slate-800" style="min-width: 1100px">
                    <thead>
                        <tr class="bg-primary text-white text-[11px] font-bold uppercase font-sans select-none">
                            <th class="px-3 py-3 text-center w-20 border-r border-white/20 last:border-r-0">কা.আইডি</th>
                            <th class="px-3 py-3 border-r border-white/20 last:border-r-0">নাম</th>
                            <th class="px-3 py-3 border-r border-white/20 last:border-r-0">ঠিকানা</th>
                            <th class="px-3 py-3 border-r border-white/20 last:border-r-0">ফোন নম্বর</th>
                            <th class="px-3 py-3 text-right border-r border-white/20 last:border-r-0">ডেলিভারি বাকি</th>
                            <th class="px-3 py-3 text-right border-r border-white/20 last:border-r-0">টাকা বাকি</th>
                            <th class="px-3 py-3 border-r border-white/20 last:border-r-0">নোট</th>
                            <th class="px-3 py-3 text-center border-r border-white/20 last:border-r-0">পরিশোধের তারিখ</th>
                            <th class="px-3 py-3 text-center border-r border-white/20 last:border-r-0">সিজন</th>
                            <th class="px-3 py-3 text-center">বাটন</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800 font-sans">
                        @forelse($challans as $challan)
                            @php
                                $delQty = $challan->items->sum('quantity');
                                $delSoFar = $challan->items->sum('delivered_quantity');
                                $delRemaining = max(0, $delQty - $delSoFar);
                            @endphp
                            <tr class="hover:bg-primary-50/40 dark:hover:bg-primary-950/10 transition-colors text-xs">
                                <td class="px-3 py-3.5 text-center text-gray-500 dark:text-slate-400 font-bold border-r border-gray-150 dark:border-slate-800 last:border-r-0 font-sans">
                                    {{ $this->getLedgerId($challan->customer_name) }}
                                </td>
                                <td class="px-3 py-3.5 font-semibold text-gray-808 dark:text-slate-202 whitespace-nowrap border-r border-gray-150 dark:border-slate-800 last:border-r-0">
                                    {{ $challan->customer_name }}
                                </td>
                                <td class="px-3 py-3.5 text-gray-600 dark:text-slate-400 border-r border-gray-150 dark:border-slate-800 last:border-r-0">
                                    {{ $challan->customer_address ?: '—' }}
                                </td>
                                <td class="px-3 py-3.5 text-gray-750 dark:text-slate-350 border-r border-gray-150 dark:border-slate-800 last:border-r-0 font-sans">
                                    {{ $challan->customer_phone ?: '—' }}
                                </td>
                                <td class="px-3 py-3.5 text-right font-semibold text-amber-600 dark:text-amber-400 border-r border-gray-150 dark:border-slate-800 last:border-r-0">
                                    {{ number_format($delRemaining) }} টি
                                </td>
                                <td class="px-3 py-3.5 text-right font-bold text-red-500 border-r border-gray-150 dark:border-slate-800 last:border-r-0">
                                    ৳{{ number_format((float)($challan->due), (float)($challan->due) == (int)($challan->due) ? 0 : 2) }}
                                </td>
                                <td class="px-3 py-3.5 text-gray-650 dark:text-slate-400 border-r border-gray-150 dark:border-slate-800 last:border-r-0">
                                    {{ $challan->notes ?: '—' }}
                                </td>
                                <td class="px-3 py-3.5 text-center text-gray-700 dark:text-slate-300 border-r border-gray-150 dark:border-slate-800 last:border-r-0 font-sans">
                                    {{ $challan->due_payment_date ? \Carbon\Carbon::parse($challan->due_payment_date)->format('d-m-Y') : '—' }}
                                </td>
                                <td class="px-3 py-3.5 text-center text-gray-600 dark:text-slate-400 border-r border-gray-150 dark:border-slate-800 last:border-r-0 font-sans">২৫-২৬</td>
                                
                                <!-- Action Dropdown -->
                                <td class="px-3 py-3.5 text-center relative" x-data="{ openDropdown: false, buttonRect: null }">
                                    <button type="button" @click="openDropdown = !openDropdown; buttonRect = $el.getBoundingClientRect()" class="p-1.5 text-gray-500 hover:text-primary dark:hover:text-primary-400 focus:outline-none transition-all cursor-pointer">
                                        <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z"/>
                                        </svg>
                                    </button>

                                    <template x-teleport="body">
                                        <div x-show="openDropdown" @click.away="openDropdown = false" x-transition
                                             class="fixed w-48 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-2xl shadow-2xl p-1.5 z-[9999] text-left text-xs flex flex-col gap-0.5"
                                             :style="buttonRect ? ('left: ' + (buttonRect.left - 140) + 'px; position: fixed; ' + (window.innerHeight - buttonRect.bottom < 240 ? 'bottom: ' + (window.innerHeight - buttonRect.top + 4) + 'px;' : 'top: ' + (buttonRect.bottom + 4) + 'px;')) : ''"
                                             x-cloak>
                                            <button type="button" wire:click="openDateModal({{ $challan->id }})" @click="openDropdown = false" class="w-full text-left px-3 py-2 hover:bg-primary-50 dark:hover:bg-primary-950/20 text-gray-700 dark:text-slate-200 hover:text-primary-dark dark:hover:text-primary-400 transition-all font-semibold rounded-xl cursor-pointer flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                                তারিখ আপডেট করুন
                                            </button>
                                            <button type="button" wire:click="openCollectionModal({{ $challan->id }})" @click="openDropdown = false" class="w-full text-left px-3 py-2 hover:bg-primary-50 dark:hover:bg-primary-950/20 text-gray-700 dark:text-slate-200 hover:text-primary-dark dark:hover:text-primary-400 transition-all font-semibold rounded-xl cursor-pointer flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                জমা করুন
                                            </button>
                                            <button type="button" wire:click="openSmsModal({{ $challan->id }})" @click="openDropdown = false" class="w-full text-left px-3 py-2 hover:bg-primary-50 dark:hover:bg-primary-950/20 text-gray-700 dark:text-slate-200 hover:text-primary-dark dark:hover:text-primary-400 transition-all font-semibold rounded-xl cursor-pointer flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.684 10.742l8.139-4.07a1 1 0 00.57-1.07l-.358-1.792a1 1 0 00-.895-.802L7.306 2.37a1 1 0 00-1.121.758L3.25 15.228a1 1 0 00.758 1.121l8.139 1.628a1 1 0 001.07-.57l4.07-8.139a1 1 0 00-.57-1.07l-8.139-1.628a1 1 0 00-1.07.57L4.5 10.742"/></svg>
                                                মেসেজ করুন
                                            </button>
                                            <a href="{{ route('challan.customer-profile', ['phone' => $challan->customer_phone ?: $challan->customer_name, 'from' => 'due-ledger.all-due']) }}" class="w-full text-left px-3 py-2 hover:bg-primary-50 dark:hover:bg-primary-950/20 text-gray-700 dark:text-slate-200 hover:text-primary-dark dark:hover:text-primary-400 transition-all font-semibold rounded-xl cursor-pointer flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                প্রোফাইলে যান
                                            </a>
                                        </div>
                                    </template>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-4 py-16 text-center">
                                    <p class="text-xs text-gray-400 dark:text-gray-500 italic font-sans">কোনো বকেয়া বাকি রেকর্ড পাওয়া যায়নি।</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Dynamic Bottom Footer: Pagination & Per Page selection -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 px-5 py-4 border-t border-gray-100 dark:border-slate-800">
                <!-- Dynamic Info text -->
                <div class="text-xs text-gray-500 dark:text-gray-400 font-sans font-semibold">
                    মোট রেকর্ড {{ $challans->total() }} টি
                </div>
                
                <!-- Page navigation & Page Size dropdown -->
                <div class="flex items-center gap-4">
                    <!-- Pagination numbers -->
                    <div class="flex items-center gap-1">
                        @if ($challans->onFirstPage())
                            <button type="button" class="p-1.5 rounded-lg border border-gray-200 dark:border-slate-800 text-gray-300 dark:text-slate-700 cursor-not-allowed" disabled>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                            </button>
                        @else
                            <button type="button" wire:click="previousPage" class="p-1.5 rounded-lg border border-gray-200 dark:border-slate-800 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-350 cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                            </button>
                        @endif

                        @php
                            $startPage = max(1, $challans->currentPage() - 2);
                            $endPage = min($challans->lastPage(), $challans->currentPage() + 2);
                        @endphp

                        @if ($startPage > 1)
                            <button type="button" wire:click="gotoPage(1)" class="px-2.5 py-1 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-300 font-bold rounded-lg text-xs border border-gray-200 dark:border-slate-750 font-mono cursor-pointer">1</button>
                            @if ($startPage > 2)
                                <span class="px-1 text-gray-405">...</span>
                            @endif
                        @endif

                        @for ($page = $startPage; $page <= $endPage; $page++)
                            @if ($page == $challans->currentPage())
                                <span class="px-2.5 py-1 bg-primary-50 dark:bg-primary-950 text-[#034C3C] dark:text-primary-400 font-bold rounded-lg text-xs border border-primary-200 dark:border-primary-900 font-mono">{{ $page }}</span>
                            @else
                                <button type="button" wire:click="gotoPage({{ $page }})" class="px-2.5 py-1 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-300 font-bold rounded-lg text-xs border border-gray-200 dark:border-slate-750 font-mono cursor-pointer">{{ $page }}</button>
                            @endif
                        @endfor

                        @if ($endPage < $challans->lastPage())
                            @if ($endPage < $challans->lastPage() - 1)
                                <span class="px-1 text-gray-455">...</span>
                            @endif
                            <button type="button" wire:click="gotoPage({{ $challans->lastPage() }})" class="px-2.5 py-1 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-300 font-bold rounded-lg text-xs border border-gray-200 dark:border-slate-750 font-mono cursor-pointer">{{ $challans->lastPage() }}</button>
                        @endif

                        @if ($challans->hasMorePages())
                            <button type="button" wire:click="nextPage" class="p-1.5 rounded-lg border border-gray-200 dark:border-slate-800 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-350 cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                            </button>
                        @else
                            <button type="button" class="p-1.5 rounded-lg border border-gray-200 dark:border-slate-800 text-gray-300 dark:text-slate-700 cursor-not-allowed" disabled>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                            </button>
                        @endif
                    </div>

                    <!-- Per Page Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" type="button" 
                                class="flex items-center justify-between gap-1.5 px-3 py-1.5 bg-gray-50 dark:bg-slate-800 text-gray-805 dark:text-white font-bold rounded-lg text-xs border border-gray-200 dark:border-slate-700 focus:outline-none transition-all cursor-pointer">
                            <span>{{ $perPage }} রেকর্ড / পেজ</span>
                            <svg class="w-3.5 h-3.5 transition-transform duration-200 text-gray-550" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        <div x-show="open" 
                             @click.outside="open = false"
                             class="absolute bottom-full mb-1.5 right-0 z-[999] w-36 bg-white dark:bg-slate-900 border border-gray-205 dark:border-slate-800 rounded-xl shadow-xl overflow-hidden focus:outline-none"
                             x-cloak>
                            <div class="py-1">
                                @foreach ([10, 20, 30, 50] as $size)
                                <button type="button" 
                                        wire:click="$set('perPage', {{ $size }})"
                                        @click="open = false"
                                        class="w-full text-left px-3 py-2 text-xs font-bold text-gray-855 dark:text-white hover:bg-primary-50 dark:hover:bg-slate-800 hover:text-primary-dark dark:hover:text-primary-400 transition-colors font-sans">
                                    {{ $size }} রেকর্ড / পেজ
                                </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- তারিখ আপডেট করুন Modal -->
    <div x-data="{ show: @entangle('showDateModal') }" x-show="show" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="fixed inset-0 bg-black/50 dark:bg-black/70 backdrop-blur-sm transition-opacity" @click="show = false; $wire.closeModal()"></div>

        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-md bg-white dark:bg-slate-900 border border-primary-200 dark:border-primary-900/50 rounded-3xl shadow-2xl p-6 transition-all duration-300 animate-settings-fade text-xs font-sans text-gray-700 dark:text-slate-200" @click.stop>
                
                <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-850 pb-4 mb-4">
                    <h3 class="text-sm font-bold text-gray-808 dark:text-white">
                        পরিশোধের তারিখ পরিবর্তন করুন 📅
                    </h3>
                    <button type="button" @click="show = false; $wire.closeModal()" class="p-1 rounded-full hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-400 hover:text-gray-600 transition-colors cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit.prevent="saveDate" class="space-y-4">
                    <div>
                        <label class="block text-gray-500 dark:text-slate-400 font-bold mb-1.5">নতুন তারিখ</label>
                        <div class="relative flex items-center text-xs">
                            <input type="text"
                                   data-flatpickr
                                   data-wire-prop="new_payment_date"
                                   data-default="{{ $new_payment_date }}"
                                   wire:model="new_payment_date"
                                   placeholder="তারিখ নির্বাচন করুন"
                                   readonly
                                   class="pl-3.5 pr-8 py-2 w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-gray-800 dark:text-white font-sans font-semibold cursor-pointer focus:outline-none">
                            <span class="absolute right-2.5 top-2.5 text-gray-450 pointer-events-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            </span>
                        </div>
                        @error('new_payment_date') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-gray-500 dark:text-slate-400 font-bold mb-1.5">নোট</label>
                        <input type="text" wire:model="notes" placeholder="নোট লিখুন (ঐচ্ছিক)" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-gray-800 dark:text-white font-semibold focus:outline-none focus:border-primary">
                    </div>

                    <div class="flex justify-end gap-2.5 pt-4 border-t border-gray-100 dark:border-slate-850">
                        <button type="button" @click="show = false; $wire.closeModal()" class="px-5 py-2.5 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-755 dark:text-slate-200 hover:text-gray-900 dark:hover:text-white border border-gray-200 dark:border-slate-700 font-bold rounded-xl cursor-pointer transition-all active:scale-95 text-center">
                            বাতিল
                        </button>
                        <button type="submit" class="px-5 py-2.5 bg-primary hover:bg-primary-dark text-white font-bold rounded-xl cursor-pointer transition-all active:scale-95 flex items-center justify-center gap-1.5 shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            সেভ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- জমা করুন (বাকি জমা) Modal -->
    <div x-data="{ show: @entangle('showCollectionModal') }" x-show="show" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="fixed inset-0 bg-black/50 dark:bg-black/70 backdrop-blur-sm transition-opacity" @click="show = false; $wire.closeModal()"></div>

        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-2xl bg-white dark:bg-slate-900 border border-primary-200 dark:border-primary-900/50 rounded-3xl shadow-2xl p-6 transition-all duration-300 animate-settings-fade text-xs font-sans text-gray-700 dark:text-slate-200" @click.stop>
                
                <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-850 pb-4 mb-4">
                    <h3 class="text-sm font-bold text-gray-808 dark:text-white">
                        বাকি জমা করুন 🤩
                    </h3>
                    <button type="button" @click="show = false; $wire.closeModal()" class="p-1 rounded-full hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-400 hover:text-gray-650 transition-colors cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit.prevent="saveCollection" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-gray-500 dark:text-slate-400 font-bold mb-1.5">কাস্টমার আইডি</label>
                            <input type="text" wire:model="customer_id" readonly class="w-full px-3.5 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-100 dark:bg-slate-800 text-gray-500 dark:text-slate-400 focus:outline-none font-semibold">
                        </div>
                        <div>
                            <label class="block text-gray-500 dark:text-slate-400 font-bold mb-1.5">নাম</label>
                            <input type="text" wire:model="customer_name" readonly class="w-full px-3.5 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-100 dark:bg-slate-800 text-gray-500 dark:text-slate-400 focus:outline-none font-semibold">
                        </div>
                        <div>
                            <label class="block text-gray-500 dark:text-slate-400 font-bold mb-1.5">ঠিকানা</label>
                            <input type="text" wire:model="customer_address" readonly class="w-full px-3.5 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-100 dark:bg-slate-800 text-gray-500 dark:text-slate-400 focus:outline-none font-semibold">
                        </div>
                        <div>
                            <label class="block text-gray-500 dark:text-slate-400 font-bold mb-1.5">সিজন</label>
                            <input type="text" wire:model="season" readonly class="w-full px-3.5 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-100 dark:bg-slate-800 text-gray-500 dark:text-slate-400 focus:outline-none font-semibold">
                        </div>
                    </div>

                    <!-- Payment Math Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-blue-50/30 dark:bg-slate-950/30 p-4 rounded-2xl border border-gray-100 dark:border-slate-800">
                        <div>
                            <label class="block text-gray-500 dark:text-slate-400 font-bold mb-1.5">মোট বাকি</label>
                            <div class="text-sm font-black text-red-500 font-sans mt-1">
                                ৳{{ number_format($total_due) }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-500 dark:text-slate-400 font-bold mb-1.5">জমা</label>
                            <div class="relative flex items-center">
                                <span class="absolute left-3 text-gray-400 font-semibold">৳</span>
                                <input type="number" step="0.01" wire:model.live="cash" placeholder="জমা পরিমাণ" class="w-full pl-7 pr-3.5 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-gray-800 dark:text-white font-sans font-semibold focus:outline-none focus:border-primary">
                            </div>
                            @error('cash') <span class="text-red-550 text-[10px] block mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-gray-500 dark:text-slate-400 font-bold mb-1.5">নতুন বাকি</label>
                            <div class="text-lg font-black font-sans mt-0.5 {{ $new_due > 0 ? 'text-red-500' : 'text-primary' }}">
                                ৳{{ number_format($new_due) }}
                            </div>
                        </div>
                    </div>

                    <!-- Dates and Toggle Row -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-500 dark:text-slate-400 font-bold mb-1.5">নতুন তারিখ</label>
                            <div class="relative flex items-center text-xs">
                                <input type="text"
                                       data-flatpickr
                                       data-wire-prop="due_payment_date"
                                       data-default="{{ $due_payment_date }}"
                                       wire:model="due_payment_date"
                                       placeholder="পরিশোধের তারিখ নির্বাচন করুন"
                                       readonly
                                       class="pl-3.5 pr-8 py-2 w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-gray-800 dark:text-white font-sans font-semibold cursor-pointer focus:outline-none">
                                <span class="absolute right-2.5 top-2.5 text-gray-450 pointer-events-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between bg-gray-50/50 dark:bg-slate-950/20 px-4 py-2.5 rounded-xl border border-gray-200/50 dark:border-slate-800 h-full mt-auto">
                            <span class="font-bold text-gray-600 dark:text-slate-350">এসএমএস পাঠান</span>
                            <label class="relative inline-flex items-center cursor-pointer select-none">
                                <input type="checkbox" wire:model="send_sms" class="sr-only peer">
                                <div class="w-9 h-5 bg-gray-200 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-primary"></div>
                            </label>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-gray-500 dark:text-slate-400 font-bold mb-1.5">কালেকশন নোট</label>
                        <input type="text" wire:model="notes" placeholder="কালেকশন সম্পর্কে বিবরণ লিখুন (ঐচ্ছিক)" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-gray-800 dark:text-white font-semibold focus:outline-none focus:border-primary">
                    </div>

                    <div class="flex justify-end gap-2.5 pt-4 border-t border-gray-100 dark:border-slate-850">
                        <button type="button" @click="show = false; $wire.closeModal()" class="px-5 py-2.5 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-755 dark:text-slate-200 hover:text-gray-900 dark:hover:text-white border border-gray-200 dark:border-slate-700 font-bold rounded-xl cursor-pointer transition-all active:scale-95 text-center">
                            বাতিল
                        </button>
                        <button type="submit" class="px-5 py-2.5 bg-primary hover:bg-primary-dark text-white font-bold rounded-xl cursor-pointer transition-all active:scale-95 flex items-center justify-center gap-1.5 shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            সেভ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- মেসেজ করুন (SMS Modal) -->
    <div x-data="{ show: @entangle('showSmsModal') }" x-show="show" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="fixed inset-0 bg-black/50 dark:bg-black/70 backdrop-blur-sm transition-opacity" @click="show = false; $wire.closeModal()"></div>

        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-md bg-white dark:bg-slate-900 border border-primary-200 dark:border-primary-900/50 rounded-3xl shadow-2xl p-6 transition-all duration-300 animate-settings-fade text-xs font-sans text-gray-700 dark:text-slate-200" @click.stop>
                
                <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-850 pb-4 mb-4">
                    <h3 class="text-sm font-bold text-gray-808 dark:text-white">
                        কাস্টমারকে বাকি টাকা পরিশোধের জন্য মেসেজ পাঠান 💬
                    </h3>
                    <button type="button" @click="show = false; $wire.closeModal()" class="p-1 rounded-full hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-400 hover:text-gray-600 transition-colors cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit.prevent="sendSms" class="space-y-4">
                    <div class="bg-gray-50 dark:bg-slate-950 p-3.5 rounded-2xl border border-gray-200/60 dark:border-slate-800 mb-2 space-y-1">
                        <div><strong class="text-gray-800 dark:text-white">গ্রাহকের নাম:</strong> {{ $sms_name }}</div>
                        <div><strong class="text-gray-800 dark:text-white">মোবাইল:</strong> <span class="font-sans font-semibold text-primary dark:text-primary-400">{{ $sms_phone }}</span></div>
                    </div>

                    <div>
                        <label class="block text-gray-500 dark:text-slate-400 font-bold mb-1.5">বার্তা</label>
                        <textarea wire:model.live="sms_text" rows="5" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-gray-800 dark:text-white font-semibold focus:outline-none focus:border-primary resize-none leading-relaxed"></textarea>
                    </div>

                    <div class="flex items-center justify-between px-1 text-gray-550 dark:text-slate-400 font-semibold font-sans">
                        <span>চরিত্র সংখ্যা: {{ mb_strlen($sms_text) }}</span>
                        <span>মেসেজ সংখ্যা: {{ $sms_count }}</span>
                    </div>

                    <div class="flex justify-end gap-2.5 pt-4 border-t border-gray-100 dark:border-slate-850">
                        <button type="button" @click="show = false; $wire.closeModal()" class="px-5 py-2.5 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-755 dark:text-slate-200 hover:text-gray-900 dark:hover:text-white border border-gray-200 dark:border-slate-700 font-bold rounded-xl cursor-pointer transition-all active:scale-95 text-center">
                            বাতিল
                        </button>
                        <button type="submit" class="px-5 py-2.5 bg-primary hover:bg-primary-dark text-white font-bold rounded-xl cursor-pointer transition-all active:scale-95 flex items-center justify-center gap-1.5 shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.684 10.742l8.139-4.07a1 1 0 00.57-1.07l-.358-1.792a1 1 0 00-.895-.802L7.306 2.37a1 1 0 00-1.121.758L3.25 15.228a1 1 0 00.758 1.121l8.139 1.628a1 1 0 001.07-.57l4.07-8.139a1 1 0 00-.57-1.07l-8.139-1.628a1 1 0 00-1.07.57L4.5 10.742"/></svg>
                            সেভ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
