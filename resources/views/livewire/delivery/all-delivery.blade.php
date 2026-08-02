<div class="min-h-screen bg-gray-50 dark:bg-slate-950 transition-colors duration-300">
    <!-- Page Header Bar -->
    <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 px-4 sm:px-6 py-3 rounded-lg flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 transition-colors duration-300">
        <div>
            <h2 class="font-bold text-gray-800 dark:text-white text-sm leading-tight">বাকি ডেলিভারি লিস্ট</h2>
            <p class="text-[10px] text-gray-400 dark:text-gray-500 font-sans mt-0.5 font-semibold">প্রতিষ্ঠানের সকল ডেলিভারি চালানের তথ্য তালিকা</p>
        </div>

        <!-- Top Actions -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3 flex-wrap">
            <!-- Search -->
            <div class="relative w-full sm:w-auto">
                <input type="text" wire:model.live="search" placeholder="সার্চ করুন..."
                       class="pl-4 pr-4 py-2 text-xs rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/10 transition-all w-full sm:w-48 font-sans font-semibold">
            </div>

            <!-- Date Fields Grid: 2-column in mobile view -->
            <div class="grid grid-cols-2 gap-2 w-full sm:flex sm:items-center sm:gap-3 sm:w-auto">
                <!-- Date Range: From -->
                <div class="flex items-center gap-2 col-span-1">
                    <span class="text-[10px] font-bold text-gray-500 dark:text-gray-400 font-sans whitespace-nowrap hidden sm:inline">শুরু:</span>
                    <div class="relative flex items-center w-full">
                        <input type="text"
                               data-flatpickr
                               data-wire-prop="dateFrom"
                               data-default="{{ $dateFrom }}"
                               wire:model="dateFrom"
                               placeholder="শুরু তারিখ"
                               readonly
                               class="pl-3 pr-8 py-2 text-xs rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/10 transition-all w-full sm:w-32 font-sans font-semibold cursor-pointer">
                        <span class="absolute right-2.5 top-2 text-primary-500 pointer-events-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        </span>
                    </div>
                </div>

                <!-- Date Range: To -->
                <div class="flex items-center gap-2 col-span-1">
                    <span class="text-[10px] font-bold text-gray-500 dark:text-gray-400 font-sans whitespace-nowrap hidden sm:inline">শেষ:</span>
                    <div class="relative flex items-center w-full">
                        <input type="text"
                               data-flatpickr
                               data-wire-prop="dateTo"
                               data-default="{{ $dateTo }}"
                               wire:model="dateTo"
                               placeholder="শেষ তারিখ"
                               readonly
                               class="pl-3 pr-8 py-2 text-xs rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/10 transition-all w-full sm:w-32 font-sans font-semibold cursor-pointer">
                        <span class="absolute right-2.5 top-2 text-primary-500 pointer-events-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Buttons Layout: Report & Print in 2 columns on mobile -->
            <div class="grid grid-cols-2 gap-2 w-full sm:flex sm:items-center sm:gap-3 sm:w-auto">
                <!-- Report Button -->
                <button type="button" wire:click="$set('showReportModal', true)"
                        class="px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl cursor-pointer transition-all font-sans flex items-center justify-center gap-1.5 shadow-sm active:scale-95 w-full sm:w-auto">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    রিপোর্ট
                </button>

                <!-- Print Button -->
                <button type="button" onclick="printChallanArea('delivery-table-print-all')"
                        class="px-3 py-2 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-200 text-xs font-semibold rounded-xl cursor-pointer transition-all font-sans border border-gray-200 dark:border-slate-700 flex items-center justify-center gap-1.5 shadow-sm w-full sm:w-auto">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    প্রিন্ট
                </button>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="py-4 sm:py-6">
        <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-lg shadow-sm overflow-hidden transition-colors duration-300">
            <!-- Responsive Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse border border-gray-200 dark:border-slate-800" style="min-width: 1100px">
                    <thead>
                        <tr class="bg-primary text-white text-[11px] font-bold uppercase font-sans select-none">
                            <th wire:click="sortBy('challan_no')" class="px-3 py-3 border-r border-white/20 cursor-pointer hover:bg-primary-dark transition-colors">
                                <div class="flex items-center gap-1">
                                    <span>চালান নং</span>
                                    @if ($sortField === 'challan_no')
                                        <span class="text-[9px]">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </div>
                            </th>
                            <th wire:click="sortBy('customer_name')" class="px-3 py-3 border-r border-white/20 cursor-pointer hover:bg-primary-dark transition-colors">
                                <div class="flex items-center gap-1">
                                    <span>কাস্টমার</span>
                                    @if ($sortField === 'customer_name')
                                        <span class="text-[9px]">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </div>
                            </th>
                            <th class="px-3 py-3 border-r border-white/20">ঠিকানা</th>
                            <th class="px-3 py-3 border-r border-white/20">শ্রেণি</th>
                            <th class="px-3 py-3 text-right border-r border-white/20">ক্রয়</th>
                            <th class="px-3 py-3 text-right border-r border-white/20">ডেলিভারি</th>
                            <th class="px-3 py-3 text-right border-r border-white/20">ডে.বাকি</th>
                            <th class="px-3 py-3 text-right border-r border-white/20">মোট ডে.বাকি</th>
                            <th class="px-3 py-3 text-right border-r border-white/20">টাকা বাকি</th>
                            <th wire:click="sortBy('delivery_date')" class="px-3 py-3 border-r border-white/20 cursor-pointer hover:bg-primary-dark transition-colors">
                                <div class="flex items-center gap-1">
                                    <span>ডে.তারিখ</span>
                                    @if ($sortField === 'delivery_date')
                                        <span class="text-[9px]">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </div>
                            </th>
                            <th class="px-3 py-3 border-r border-white/20">নোট</th>
                            <th class="px-3 py-3 text-center">বাটন</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs font-semibold text-gray-700 dark:text-slate-350 divide-y divide-gray-100 dark:divide-slate-800">
                        @forelse ($deliveries as $item)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition-all font-sans">
                                <td class="px-3 py-3.5 font-mono text-[11px] text-gray-550 border-r border-gray-100 dark:border-slate-800">{{ $item->challan->challan_no ?? '' }}</td>
                                <td class="px-3 py-3.5 border-r border-gray-100 dark:border-slate-800 text-gray-900 dark:text-white font-bold">{{ $item->challan->customer_name ?? '' }}</td>
                                <td class="px-3 py-3.5 text-gray-500 border-r border-gray-100 dark:border-slate-800">{{ $item->challan->customer_address ?? '' }}</td>
                                <td class="px-3 py-3.5 border-r border-gray-100 dark:border-slate-800">
                                    <span class="px-2 py-0.5 rounded bg-primary-50 dark:bg-primary-950/30 text-primary-dark dark:text-primary-400 text-[10px]">{{ $item->category_name }}</span>
                                </td>
                                <td class="px-3 py-3.5 text-right font-mono border-r border-gray-100 dark:border-slate-800">{{ number_format($item->quantity) }}</td>
                                <td class="px-3 py-3.5 text-right font-mono border-r border-gray-100 dark:border-slate-800 text-blue-600 dark:text-blue-450">{{ number_format($item->delivered_quantity) }}</td>
                                <td class="px-3 py-3.5 text-right font-mono border-r border-gray-100 dark:border-slate-800 text-amber-600 dark:text-amber-450 font-bold">
                                    {{ number_format(max(0, $item->quantity - $item->delivered_quantity)) }}
                                </td>
                                <td class="px-3 py-3.5 text-right font-mono border-r border-gray-100 dark:border-slate-800 text-primary dark:text-primary-light">
                                    @php
                                        $totalChPending = 0;
                                        if ($item->challan) {
                                            foreach($item->challan->items as $it) {
                                                $totalChPending += max(0, $it->quantity - $it->delivered_quantity);
                                            }
                                        }
                                    @endphp
                                    {{ number_format($totalChPending) }}
                                </td>
                                <td class="px-3 py-3.5 text-right font-mono border-r border-gray-100 dark:border-slate-800 text-red-600 dark:text-red-450 font-bold">
                                    ৳ {{ number_format($item->challan->due ?? 0) }}
                                </td>
                                <td class="px-3 py-3.5 text-[11px] text-gray-500 border-r border-gray-100 dark:border-slate-800 font-mono">
                                    {{ $item->challan->date ? $item->challan->date->format('d-m-Y') : '' }}
                                </td>
                                <td class="px-3 py-3.5 text-gray-500 border-r border-gray-100 dark:border-slate-800 max-w-[150px] truncate">{{ $item->challan->notes ?? '' }}</td>
                                <td class="px-3 py-3.5 text-center relative" x-data="{ openDropdown: false, buttonRect: null }">
                                    <button @click="openDropdown = !openDropdown; buttonRect = $el.getBoundingClientRect()" type="button" class="p-1 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-lg transition-all cursor-pointer">
                                        <svg class="w-5 h-5 mx-auto text-gray-500 hover:text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z"/></svg>
                                    </button>
                                    <template x-teleport="body">
                                        <div x-show="openDropdown" @click.away="openDropdown = false" x-transition
                                             class="fixed w-48 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-2xl shadow-2xl p-1.5 z-[9999] text-left text-xs flex flex-col gap-0.5"
                                             :style="buttonRect ? ('left: ' + (buttonRect.left - 140) + 'px; position: fixed; ' + (window.innerHeight - buttonRect.bottom < 240 ? 'bottom: ' + (window.innerHeight - buttonRect.top + 4) + 'px;' : 'top: ' + (buttonRect.bottom + 4) + 'px;')) : ''"
                                             x-cloak>
                                            <button type="button" wire:click="openChangeDateModal({{ $item->challan_id }})" @click="openDropdown = false" class="w-full text-left px-3 py-2 hover:bg-primary-50 dark:hover:bg-primary-950/20 text-gray-700 dark:text-slate-200 hover:text-primary-dark dark:hover:text-primary-400 transition-all font-semibold rounded-xl cursor-pointer flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                                তারিখ পরিবর্তন
                                            </button>
                                            @if($item->challan)
                                            <button type="button" wire:click="openDeliveryModal({{ $item->challan->id }})" @click="openDropdown = false" class="w-full text-left px-3 py-2 hover:bg-primary-50 dark:hover:bg-primary-950/20 text-gray-700 dark:text-slate-200 hover:text-primary-dark dark:hover:text-primary-400 transition-all font-semibold rounded-xl cursor-pointer flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                                                ডেলিভারি দিন
                                            </button>
                                            <a href="{{ route('challan.customer-profile', ['phone' => $item->challan->customer_phone ?: $item->challan->customer_name, 'from' => 'delivery.all']) }}" class="w-full text-left px-3 py-2 hover:bg-primary-50 dark:hover:bg-primary-950/20 text-gray-700 dark:text-slate-200 hover:text-primary-dark dark:hover:text-primary-400 transition-all font-semibold rounded-xl cursor-pointer flex items-center gap-2 block">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                প্রোফাইলে যান
                                            </a>
                                            @endif
                                        </div>
                                    </template>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="px-5 py-8 text-center text-gray-400 dark:text-gray-500 font-medium">কোনো চালানের ডেলিভারি বাকি নেই।</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Dynamic Bottom Footer: Pagination & Per Page selection -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 px-5 py-4 border-t border-gray-100 dark:border-slate-800">
                <div class="text-xs text-gray-500 dark:text-gray-400 font-sans font-semibold">
                    মোট ডেলিভারি {{ $deliveries->total() }} টি
                </div>
                
                <div class="flex items-center gap-4">
                    <!-- Pagination Numbers -->
                    <div class="flex items-center gap-1">
                        @if ($deliveries->onFirstPage())
                            <button type="button" class="p-1.5 rounded-lg border border-gray-200 dark:border-slate-800 text-gray-300 dark:text-slate-700 cursor-not-allowed" disabled>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                            </button>
                        @else
                            <button type="button" wire:click="previousPage" class="p-1.5 rounded-lg border border-gray-200 dark:border-slate-800 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-350 cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                            </button>
                        @endif

                        @php
                            $startPage = max(1, $deliveries->currentPage() - 2);
                            $endPage = min($deliveries->lastPage(), $deliveries->currentPage() + 2);
                        @endphp

                        @if ($startPage > 1)
                            <button type="button" wire:click="gotoPage(1)" class="px-2.5 py-1 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-300 font-bold rounded-lg text-xs border border-gray-200 dark:border-slate-750 font-mono cursor-pointer">1</button>
                            @if ($startPage > 2)
                                <span class="px-1 text-gray-400">...</span>
                            @endif
                        @endif

                        @for ($page = $startPage; $page <= $endPage; $page++)
                            @if ($page == $deliveries->currentPage())
                                <span class="px-2.5 py-1 bg-primary-50 dark:bg-primary-950 text-[#034C3C] dark:text-primary-400 font-bold rounded-lg text-xs border border-primary-200 dark:border-primary-900 font-mono">{{ $page }}</span>
                            @else
                                <button type="button" wire:click="gotoPage({{ $page }})" class="px-2.5 py-1 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-300 font-bold rounded-lg text-xs border border-gray-200 dark:border-slate-750 font-mono cursor-pointer">{{ $page }}</button>
                            @endif
                        @endfor

                        @if ($endPage < $deliveries->lastPage())
                            @if ($endPage < $deliveries->lastPage() - 1)
                                <span class="px-1 text-gray-400">...</span>
                            @endif
                            <button type="button" wire:click="gotoPage({{ $deliveries->lastPage() }})" class="px-2.5 py-1 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-300 font-bold rounded-lg text-xs border border-gray-200 dark:border-slate-750 font-mono cursor-pointer">{{ $deliveries->lastPage() }}</button>
                        @endif

                        @if ($deliveries->hasMorePages())
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
                        <button @click="open = !open" type="button" class="flex items-center justify-between gap-1.5 px-3 py-1.5 bg-gray-55 dark:bg-slate-800 text-gray-805 dark:text-white font-bold rounded-lg text-xs border border-gray-200 dark:border-slate-700 focus:outline-none transition-all cursor-pointer">
                            <span>{{ $perPage }} ডেলিভারি / পেজ</span>
                            <svg class="w-3.5 h-3.5 transition-transform duration-200 text-gray-550" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        <div x-show="open" @click.outside="open = false" class="absolute bottom-full mb-1.5 right-0 z-[999] w-36 bg-white dark:bg-slate-900 border border-gray-255 dark:border-slate-800 rounded-xl shadow-xl overflow-hidden focus:outline-none" x-cloak>
                            <div class="py-1">
                                @foreach ([10, 20, 30, 50] as $size)
                                <button type="button" wire:click="$set('perPage', {{ $size }})" @click="open = false" class="w-full text-left px-3 py-2 text-xs font-bold text-gray-855 dark:text-white hover:bg-primary-50 dark:hover:bg-slate-800 hover:text-primary-dark dark:hover:text-primary-400 transition-colors font-sans">
                                    {{ $size }} ডেলিভারি / পেজ
                                </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ====================== NEW DELIVERY MODAL ====================== -->
    @if($showDeliveryModal)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex justify-center items-center p-4" wire:click.self="$set('showDeliveryModal', false)">
        <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-3xl w-full border border-gray-255 dark:border-slate-700 shadow-2xl p-6 relative max-h-[92vh] overflow-y-auto challan-modal-scroll animate-in fade-in zoom-in-95 duration-150">
            <div class="flex items-center justify-between border-b border-gray-150 dark:border-slate-800 pb-4 mb-5">
                <h3 class="font-bold text-base font-sans text-gray-800 dark:text-white flex items-center gap-2">নতুন ডেলিভারি 🚚</h3>
                <button type="button" wire:click="$set('showDeliveryModal', false)" class="p-1.5 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-xl cursor-pointer text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit.prevent="saveDelivery" class="space-y-4 text-xs font-semibold text-gray-600 dark:text-slate-400">
                @if($customerDue > 0 && $customer_name)
                    <div class="bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-900/50 rounded-2xl p-3 text-center mb-4">
                        <p class="text-xs font-bold text-red-600 dark:text-red-400">
                            {{ $customer_name }} এর বাকি রয়েছে: {{ function_exists('toBanglaNum') ? toBanglaNum(number_format($customerDue)) : number_format($customerDue) }} টাকা
                        </p>
                    </div>
                @endif
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block mb-1.5">ডেলিভারি নং</label>
                        <input type="text" wire:model="deliveryNo" class="w-full py-2 px-3 bg-gray-55 border border-gray-255 dark:border-slate-700 rounded-xl dark:bg-slate-950 dark:text-white text-gray-800">
                    </div>
                    <div>
                        <label class="block mb-1.5">চালান নং</label>
                        <input type="text" wire:model="challan_no" disabled class="w-full py-2 px-3 bg-gray-100 border border-gray-255 dark:border-slate-700 rounded-xl text-gray-500 dark:bg-slate-900/50">
                    </div>
                    <div>
                        <label class="block mb-1.5">ডেলিভারি তারিখ</label>
                        <input type="text" data-flatpickr data-wire-prop="deliveryDate" data-default="{{ $deliveryDate }}" wire:model="deliveryDate" readonly class="w-full py-2 px-3 bg-gray-55 border border-gray-255 dark:border-slate-700 rounded-xl focus:outline-none dark:bg-slate-950 dark:text-white text-gray-800 cursor-pointer">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block mb-1.5">কাস্টমার নাম</label>
                        <input type="text" wire:model="customer_name" disabled class="w-full py-2 px-3 bg-gray-100 border border-gray-255 dark:border-slate-700 rounded-xl text-gray-500 dark:bg-slate-900/50">
                    </div>
                    <div>
                        <label class="block mb-1.5">ফোন নম্বর</label>
                        <input type="text" wire:model="customer_phone" disabled class="w-full py-2 px-3 bg-gray-100 border border-gray-255 dark:border-slate-700 rounded-xl text-gray-500 dark:bg-slate-900/50">
                    </div>
                    <div>
                        <label class="block mb-1.5">ডেলিভারি ঠিকানা</label>
                        <input type="text" wire:model="customer_address" disabled class="w-full py-2 px-3 bg-gray-100 border border-gray-255 dark:border-slate-700 rounded-xl text-gray-500 dark:bg-slate-900/50">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1.5">পরবর্তী ডেলিভারি তারিখ</label>
                        <input type="text" data-flatpickr data-wire-prop="nextDeliveryDate" data-default="{{ $nextDeliveryDate }}" wire:model="nextDeliveryDate" readonly class="w-full py-2 px-3 bg-gray-55 border border-gray-255 dark:border-slate-700 rounded-xl focus:outline-none dark:bg-slate-950 dark:text-white text-gray-800 cursor-pointer">
                    </div>
                    <div>
                        <label class="block mb-1.5">নোট</label>
                        <textarea wire:model="deliveryNotes" rows="1" class="w-full py-2 px-3 bg-gray-55 border border-gray-255 dark:border-slate-700 rounded-xl focus:outline-none dark:bg-slate-950 dark:text-white text-gray-800"></textarea>
                    </div>
                </div>

                <!-- Product Specs grid -->
                <div class="bg-gray-50/50 dark:bg-slate-950/30 border border-gray-150 dark:border-slate-800 rounded-2xl p-4 space-y-3">
                    <div class="grid grid-cols-4 gap-3 text-center font-bold text-[11px] text-gray-500">
                        <div>শ্রেণি</div>
                        <div>ডেলিভারি পাবে</div>
                        <div>আজকের ডেলিভারি</div>
                        <div>ডেলিভারি বাকি</div>
                    </div>
                    <div class="grid grid-cols-4 gap-3 items-center">
                        <div>
                            <select wire:model.live="selectedChallanItemId" class="w-full py-2 px-3 bg-white dark:bg-slate-950 border border-gray-205 dark:border-slate-800 rounded-xl text-gray-800 dark:text-white font-semibold focus:ring-2 focus:ring-primary-500/20">
                                @foreach($challanItems as $chItem)
                                    <option value="{{ $chItem->id }}">{{ $chItem->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <input type="text" value="{{ number_format(max(0, (int)$deliveryTotalQty - (int)$deliveredQtySoFar)) }}" disabled class="w-full py-2 px-3 bg-gray-100 border border-gray-205 dark:border-slate-800 rounded-xl text-center text-gray-500 dark:bg-slate-900/50 font-sans">
                        </div>
                        <div>
                            <input type="number" wire:model.live="todayDeliveryQty" class="w-full py-2 px-3 bg-white dark:bg-slate-950 border border-gray-300 dark:border-slate-700 rounded-xl text-center text-gray-800 dark:text-white font-bold font-sans focus:ring-2 focus:ring-primary-500/20" placeholder="0">
                        </div>
                        <div>
                            <input type="text" value="{{ number_format(max(0, (int)$deliveryTotalQty - (int)$deliveredQtySoFar - (int)$todayDeliveryQty)) }}" disabled class="w-full py-2 px-3 bg-gray-100 border border-gray-205 dark:border-slate-800 rounded-xl text-center text-gray-500 dark:bg-slate-900/50 font-sans">
                        </div>
                    </div>
                </div>

                <!-- Driver details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-2">
                    <div class="space-y-3">
                        <h4 class="font-bold text-[11px] text-gray-500 tracking-wider">ড্রাইভারের তথ্য</h4>
                        <div>
                            <input type="text" wire:model="driverName" placeholder="ড্রাইভারের নাম" class="w-full py-2 px-3 bg-gray-55 border border-gray-255 dark:border-slate-700 rounded-xl focus:outline-none dark:bg-slate-950 dark:text-white">
                        </div>
                        <div>
                            <input type="text" wire:model="driverPhone" placeholder="ড্রাইভারের ফোন নম্বর" class="w-full py-2 px-3 bg-gray-55 border border-gray-255 dark:border-slate-700 rounded-xl focus:outline-none dark:bg-slate-950 dark:text-white">
                        </div>
                        <div>
                            <input type="text" wire:model="vehicleNo" placeholder="গাড়ি নম্বর" class="w-full py-2 px-3 bg-gray-55 border border-gray-255 dark:border-slate-700 rounded-xl focus:outline-none dark:bg-slate-950 dark:text-white">
                        </div>
                    </div>
                    <div class="flex flex-col justify-between">
                        <div class="space-y-2">
                            <h4 class="font-bold text-[11px] text-gray-500 tracking-wider">গাড়ি ভাড়া</h4>
                            <div class="relative flex items-center justify-center">
                                <span class="absolute left-4 text-gray-400 text-lg">৳</span>
                                <input type="number" wire:model="vehicleRent" placeholder="ভাড়া" class="w-full py-4 pl-10 pr-4 bg-gray-55 border border-gray-255 dark:border-slate-700 rounded-2xl text-center text-2xl font-bold text-gray-800 dark:text-white focus:outline-none dark:bg-slate-950 font-sans">
                            </div>
                        </div>
                        <div class="flex items-center justify-between pt-4">
                            <span>কাস্টমারকে এসএমএস দিন</span>
                            <button type="button" @click="$wire.smsToCustomer = !$wire.smsToCustomer" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none" :class="$wire.smsToCustomer ? 'bg-primary' : 'bg-gray-200 dark:bg-slate-800'">
                                <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out" :class="$wire.smsToCustomer ? 'translate-x-5' : 'translate-x-0'"></span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-gray-150 dark:border-slate-800 mt-4">
                    <button type="button" wire:click="$set('showDeliveryModal', false)" class="px-5 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/20 border border-gray-200 dark:border-slate-700 rounded-xl cursor-pointer transition-all">ক্লিয়ার</button>
                    <button type="submit" class="px-6 py-2 bg-primary hover:bg-primary-dark text-white text-xs font-bold rounded-xl cursor-pointer transition-all shadow-md flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>সেভ করুন</button>
                    <button type="button" wire:click="saveDelivery(true)" class="px-6 py-2 bg-primary hover:bg-primary-dark text-white text-xs font-bold rounded-xl cursor-pointer transition-all shadow-md flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>সেভ + প্রিন্ট ডেলিভারি</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- ====================== DATE CHANGE MODAL ====================== -->
    @if($showChangeDateModal)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex justify-center items-center p-4" wire:click.self="$set('showChangeDateModal', false)">
        <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-md w-full border border-gray-205 dark:border-slate-800 shadow-2xl p-6 relative animate-in fade-in zoom-in-95 duration-150 text-xs font-semibold text-gray-700 dark:text-slate-200">
            <div class="flex items-center justify-between border-b border-gray-150 dark:border-slate-800 pb-3 mb-5">
                <h3 class="font-bold text-sm font-sans text-gray-800 dark:text-white flex items-center gap-2">চালান নং - {{ $selectedChallanNo }}</h3>
                <button type="button" wire:click="$set('showChangeDateModal', false)" class="p-1.5 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-full text-gray-400 dark:text-slate-350 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit.prevent="updateDeliveryDate" class="space-y-4">
                <!-- Option Selection -->
                <div class="space-y-2">
                    <label class="block text-gray-400">অপশন নির্বাচন</label>
                    <div class="flex items-center gap-6">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" wire:model.live="changeOption" value="all" class="w-4 h-4 text-primary focus:ring-primary-500 border-gray-300 dark:border-slate-700 dark:bg-slate-950 accent-primary">
                            <span>পুরো চালান</span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" wire:model.live="changeOption" value="category" class="w-4 h-4 text-primary focus:ring-primary-500 border-gray-300 dark:border-slate-700 dark:bg-slate-950 accent-primary">
                            <span>শ্রেণি অনুযায়ী</span>
                        </label>
                    </div>
                </div>

                <!-- Category Select: visible when changeOption is category -->
                @if($changeOption === 'category')
                <div class="space-y-2">
                    <label class="block text-gray-400">শ্রেণি নির্বাচন</label>
                    <select wire:model.live="selectedDeliveryId" class="w-full py-2 px-3 bg-white dark:bg-slate-950 border border-gray-205 dark:border-slate-800 rounded-xl text-gray-800 dark:text-white font-semibold focus:ring-2 focus:ring-primary-500/20 focus:outline-none cursor-pointer">
                        @foreach($changeDeliveries as $cd)
                            <option value="{{ $cd['id'] }}">{{ $cd['category_name'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="text-xs font-semibold">
                    <span class="text-gray-500 dark:text-gray-400 font-bold">বর্তমান ডেলিভারি তারিখ:</span>
                    <span class="text-orange-500 font-bold font-mono">{{ $currentDeliveryDate }}</span>
                </div>
                @endif

                <!-- Date Input -->
                <div class="relative">
                    <label class="block mb-1.5 text-gray-400">নতুন ডেলিভারি তারিখ</label>
                    <div class="relative flex items-center">
                        <input type="text" data-flatpickr data-wire-prop="newDeliveryDate" data-default="{{ $newDeliveryDate }}" wire:model="newDeliveryDate" readonly class="w-full py-2.5 pl-3 pr-10 bg-white dark:bg-slate-950 border border-gray-255 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500/20 text-gray-800 dark:text-white cursor-pointer">
                        <span class="absolute right-3 text-gray-400 pointer-events-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        </span>
                    </div>
                </div>

                <!-- Warning box -->
                @if($changeOption === 'all')
                <div class="space-y-1 pt-2">
                    <div class="text-orange-600 dark:text-orange-500 font-bold text-sm">সতর্কতা:</div>
                    <div class="text-[11px] text-gray-500 dark:text-gray-450 leading-relaxed font-semibold">
                        এই চালানের যদি আরও শ্রেণির ইট ডেলিভারি বাকি থাকে তাহলে সেই ইটের ডেলিভারি তারিখ ও এটার সাথে পরিবর্তন হয়ে যাবে । তাই নিশ্চিত হয়ে তারিখ পরিবর্তন করুন ।
                    </div>
                </div>
                @endif

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-150 dark:border-slate-800 mt-4">
                    <button type="button" wire:click="$set('showChangeDateModal', false)" class="px-5 py-2 text-xs font-bold text-gray-500 dark:text-gray-400 bg-white dark:bg-slate-900 hover:bg-gray-50 dark:hover:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl cursor-pointer transition-all">বাতিল</button>
                    <button type="submit" class="px-6 py-2 bg-primary hover:bg-primary-dark text-white rounded-xl text-xs font-bold transition-all shadow-md active:scale-95">পরিবর্তন</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- ====================== REPORT MODAL ====================== -->
    @if($showReportModal)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex justify-center items-center p-4" wire:click.self="$set('showReportModal', false)">
        <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-md w-full border border-gray-255 dark:border-slate-700 shadow-2xl p-6 relative animate-in fade-in zoom-in-95 duration-150">
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-800 pb-3 mb-4">
                <h3 class="font-bold text-sm font-sans text-gray-800 dark:text-white flex items-center gap-2">ডেলিভারি রিপোর্ট 📊</h3>
                <button type="button" wire:click="$set('showReportModal', false)" class="p-1.5 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-xl cursor-pointer text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="space-y-4">
                <table class="w-full text-left border-collapse border border-gray-200 dark:border-slate-800">
                    <thead>
                        <tr class="bg-primary text-white text-[10px] font-bold uppercase font-sans">
                            <th class="px-4 py-2 border-r border-white/20">শ্রেণি</th>
                            <th class="px-4 py-2 text-right">মোট পরিমাণ</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs font-semibold text-gray-700 dark:text-slate-200 divide-y divide-gray-150 dark:divide-slate-800">
                        @forelse($reportData as $row)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30">
                                <td class="px-4 py-2.5 border-r border-gray-100 dark:border-slate-800 text-gray-800 dark:text-slate-200 font-semibold">{{ $row->category_name }}</td>
                                <td class="px-4 py-2.5 text-right font-mono text-primary dark:text-primary-light font-bold">{{ number_format($row->total_qty) }} টি</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-4 py-6 text-center text-gray-400 font-medium">কোনো তথ্য নেই।</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="bg-blue-50/40 dark:bg-blue-950/20 border-t border-gray-200 dark:border-slate-800 font-bold text-xs text-gray-800 dark:text-white">
                            <td class="px-4 py-2.5 border-r border-gray-100 dark:border-slate-800">সর্বমোট</td>
                            <td class="px-4 py-2.5 text-right font-mono text-primary-dark dark:text-primary-400">{{ number_format($totalQty) }} টি</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Universal Print Preview Modal (4 Formats) -->
    <x-print-modal :showPrintModal="$showPrintModal" :printChallan="$printChallan" :printDelivery="$printDelivery" :isDeliveryPrint="$isDeliveryPrint" />

    <!-- Delete Confirmation Modal (হ্যাঁ / না) -->
    @if($showDeleteConfirmModal)
        <template x-teleport="body">
            <div class="fixed inset-0 z-[99999999] flex items-center justify-center p-4 bg-black/70 backdrop-blur-xs font-sans">
                <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-xs w-full p-6 border border-gray-100 dark:border-slate-800 shadow-2xl text-center space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center mx-auto">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-gray-800 dark:text-white">আপনি কি ডেলিভারিটি মুছে ফেলতে চান?</h3>
                        <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-1">এই কার্যক্রমটি পরবর্তীতে পুনরুদ্ধার করা যাবে না।</p>
                    </div>
                    <div class="flex items-center justify-center gap-3 pt-1">
                        <button type="button" wire:click="cancelDeleteDelivery" class="flex-1 py-2 px-3 bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-200 text-xs font-bold rounded-xl transition-all cursor-pointer">
                            না
                        </button>
                        <button type="button" wire:click="deleteDeliveryConfirmed" class="flex-1 py-2 px-3 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl transition-all shadow-md cursor-pointer">
                            হ্যাঁ
                        </button>
                    </div>
                </div>
            </div>
        </template>
    @endif

    <!-- Full Delivery Table Report Printable Container -->
    <div id="delivery-table-print-all" style="display:none;">
        <x-print-layout type="delivery-report"
                        :deliveries="$deliveries->items()"
                        :totalDeliverySum="$deliveries->sum('quantity')"
                        reportTitle="সব ডেলিভারি তালিকা"
                        :reportDate="null"
                        :activeSeason="\App\Models\Setting::get('season', '২৫-২৬')" />
    </div>
</div>
