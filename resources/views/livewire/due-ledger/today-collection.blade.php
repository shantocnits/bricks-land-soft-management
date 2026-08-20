<div class="min-h-screen bg-gray-50 dark:bg-slate-950 transition-colors duration-300">

    <!-- Page Header Bar -->
    <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 px-4 sm:px-6 py-3 rounded-lg flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 transition-colors duration-300">
        <div>
            <h2 class="font-bold text-gray-800 dark:text-white text-sm leading-tight">{{ $date ? 'আজকের জমা' : 'সকল জমা' }}</h2>
            <p class="text-[10px] text-gray-400 dark:text-gray-500 font-sans mt-0.5 font-semibold">{{ $date ? 'আজকের দিনের সকল বকেয়া জমা কালেকশনের তালিকা' : 'সকল বকেয়া জমা কালেকশনের তালিকা' }}</p>
        </div>

        <!-- Top Actions -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3 w-full sm:w-auto flex-wrap">
            <!-- Search -->
            <div class="relative w-full sm:w-auto">
                <input type="text" wire:model.live="search"
                       placeholder="সার্চ করুন..."
                       class="pl-4 pr-4 py-2 text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all w-full sm:w-52 font-sans font-semibold">
            </div>
            
            <div class="grid grid-cols-2 gap-2 w-full sm:flex sm:items-center sm:gap-3 sm:w-auto">
                <!-- Date picker (Flatpickr) -->
                <div class="col-span-2 sm:col-span-1 relative flex items-center w-full">
                    <input type="text"
                           data-flatpickr
                           data-wire-prop="date"
                           data-default="{{ $date }}"
                           wire:model="date"
                           placeholder="তারিখ"
                           readonly
                           class="pl-3 pr-8 py-2 text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all w-full font-sans font-semibold cursor-pointer">
                    <span class="absolute right-2.5 top-2.5 text-primary-500 pointer-events-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </span>
                </div>
                <!-- Sob Joma List -->
                <button type="button" wire:click="$set('date', '')"
                        class="px-3 py-2 bg-secondary hover:bg-secondary-dark text-white text-xs font-semibold rounded-xl cursor-pointer transition-all font-sans flex items-center justify-center gap-1.5 w-full sm:w-auto shadow-sm active:scale-95 whitespace-nowrap">
                    সব জমা লিস্ট
                </button>
                <!-- Print -->
                <button type="button" onclick="printChallanArea('due-collection-table-print')"
                        class="px-3 py-2 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-200 text-xs font-semibold rounded-xl cursor-pointer transition-all font-sans border border-gray-200 dark:border-slate-700 flex items-center justify-center gap-1.5 w-full sm:w-auto whitespace-nowrap">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    প্রিন্ট
                </button>
                <!-- New Collection -->
                <button type="button" wire:click="openAddModal"
                        class="col-span-2 sm:col-span-1 px-4 py-2 bg-primary hover:bg-primary-dark text-white text-xs font-bold rounded-xl cursor-pointer transition-all shadow-md active:scale-95 font-sans w-full sm:w-auto whitespace-nowrap">
                    নতুন বাকি জমা
                </button>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="py-4 sm:py-6">
        <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-lg shadow-sm overflow-hidden transition-colors duration-300">
            <!-- Summary Bar -->
            <div class="flex flex-wrap items-center justify-between gap-4 px-5 py-3.5 border-b border-gray-100 dark:border-slate-800 bg-blue-50/40 dark:bg-blue-950/10">
                <span class="text-xs text-gray-500 dark:text-gray-400 font-sans">মোট কালেকশন: <strong class="text-gray-850 dark:text-white">{{ $collections->total() }} টি</strong></span>
                <span class="text-xs font-sans px-3 py-1 bg-primary text-white rounded-xl font-bold">মোট জমাঃ {{ number_format($totalCollectionSum) }} টাকা</span>
            </div>

            <!-- Responsive Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse border border-gray-200 dark:border-slate-800" style="min-width: 1050px">
                    <thead>
                        <tr class="bg-primary text-white text-[11px] font-bold uppercase font-sans select-none">
                            <th class="px-3 py-3 text-center w-10 border-r border-white/20 last:border-r-0">#</th>
                            <th class="px-3 py-3 text-center w-20 border-r border-white/20 last:border-r-0">কা.আইডি</th>
                            <th class="px-3 py-3 border-r border-white/20 last:border-r-0">নাম</th>
                            <th class="px-3 py-3 border-r border-white/20 last:border-r-0">ঠিকানা</th>
                            <th class="px-3 py-3 text-right border-r border-white/20 last:border-r-0">বাকি ছিল</th>
                            <th class="px-3 py-3 text-right border-r border-white/20 last:border-r-0">জমা</th>
                            <th class="px-3 py-3 text-right border-r border-white/20 last:border-r-0">বাকি রইল</th>
                            <th class="px-3 py-3 text-center border-r border-white/20 last:border-r-0">নতুন তারিখ</th>
                            <th class="px-3 py-3 border-r border-white/20 last:border-r-0">নোট</th>
                            <th class="px-3 py-3 text-center border-r border-white/20 last:border-r-0">সিজন</th>
                            <th class="px-3 py-3 text-center">বাটন</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800 font-sans">
                        @forelse($collections as $col)
                            @php
                                $prevDue = $this->getPreviousDue($col);
                                $remaining = $prevDue - (float) $col->cash;
                                $netKey = $col->customer_name . '|' . ($col->customer_phone ?: '');
                            @endphp
                            <tr class="hover:bg-primary-50/40 dark:hover:bg-primary-950/10 transition-colors text-xs">
                                <td class="px-3 py-3.5 text-center text-gray-500 dark:text-slate-400 font-semibold border-r border-gray-150 dark:border-slate-800 last:border-r-0">{{ $loop->iteration }}</td>
                                <td class="px-3 py-3.5 text-center text-gray-500 dark:text-slate-400 font-bold border-r border-gray-150 dark:border-slate-800 last:border-r-0 font-sans">
                                    {{ $customerIdMap[$netKey] ?? $col->id }}
                                </td>
                                <td class="px-3 py-3.5 font-semibold text-gray-808 dark:text-slate-202 whitespace-nowrap border-r border-gray-150 dark:border-slate-800 last:border-r-0">{{ $col->customer_name }}</td>
                                <td class="px-3 py-3.5 text-gray-600 dark:text-slate-400 border-r border-gray-150 dark:border-slate-800 last:border-r-0">{{ $col->customer_address ?: '—' }}</td>
                                <td class="px-3 py-3.5 text-right font-semibold text-gray-700 dark:text-slate-300 border-r border-gray-150 dark:border-slate-800 last:border-r-0">
                                    ৳{{ number_format((float)($prevDue), (float)($prevDue) == (int)($prevDue) ? 0 : 2) }}
                                </td>
                                <td class="px-3 py-3.5 text-right font-bold text-primary dark:text-primary-400 border-r border-gray-150 dark:border-slate-800 last:border-r-0">
                                    ৳{{ number_format((float)($col->cash), (float)($col->cash) == (int)($col->cash) ? 0 : 2) }}
                                </td>
                                <td class="px-3 py-3.5 text-right font-bold border-r border-gray-150 dark:border-slate-800 last:border-r-0 {{ $remaining > 0 ? 'text-red-500' : 'text-primary' }}">
                                    ৳{{ number_format((float)($remaining), (float)($remaining) == (int)($remaining) ? 0 : 2) }}
                                </td>
                                <td class="px-3 py-3.5 text-center text-gray-700 dark:text-slate-300 border-r border-gray-150 dark:border-slate-800 last:border-r-0 font-sans">
                                    {{ $col->due_payment_date ? \Carbon\Carbon::parse($col->due_payment_date)->format('d-m-Y') : '—' }}
                                </td>
                                <td class="px-3 py-3.5 text-gray-600 dark:text-slate-400 border-r border-gray-150 dark:border-slate-800 last:border-r-0">{{ $col->notes ?: '—' }}</td>
                                <td class="px-3 py-3.5 text-center text-gray-600 dark:text-slate-400 border-r border-gray-150 dark:border-slate-800 last:border-r-0 font-sans">{{ $col->season ?: '—' }}</td>
                                
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
                                            <button type="button" wire:click="edit({{ $col->id }})" @click="openDropdown = false" class="w-full text-left px-3 py-2 hover:bg-primary-50 dark:hover:bg-primary-950/20 text-gray-700 dark:text-slate-200 hover:text-primary-dark dark:hover:text-primary-400 transition-all font-semibold rounded-xl cursor-pointer flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                আপডেট করুন
                                            </button>
                                            <button type="button" wire:click="openPrintModal({{ $col->id }})" @click="openDropdown = false" class="w-full text-left px-3 py-2 hover:bg-primary-50 dark:hover:bg-primary-950/20 text-gray-700 dark:text-slate-200 hover:text-primary-dark dark:hover:text-primary-400 transition-all font-semibold rounded-xl cursor-pointer flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                                                প্রিন্ট করুন
                                            </button>
                                            <a href="{{ route('challan.customer-profile', ['phone' => $col->customer_phone ?: $col->customer_name, 'from' => 'due-ledger.today']) }}" class="w-full text-left px-3 py-2 hover:bg-primary-50 dark:hover:bg-primary-950/20 text-gray-700 dark:text-slate-200 hover:text-primary-dark dark:hover:text-primary-400 transition-all font-semibold rounded-xl cursor-pointer flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                প্রোফাইলে যান
                                            </a>
                                            <div class="border-t border-gray-100 dark:border-slate-800 my-1"></div>
                                            <button type="button" wire:click="confirmDelete({{ $col->id }})" @click="openDropdown = false" class="w-full text-left px-3 py-2 hover:bg-red-50 dark:hover:bg-red-950/20 text-red-500 hover:text-red-700 transition-all font-semibold rounded-xl cursor-pointer flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                ডিলিট করুন
                                            </button>
                                        </div>
                                    </template>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="px-4 py-16 text-center">
                                    <p class="text-xs text-gray-400 dark:text-gray-500 italic font-sans">আজকের কোনো জমা সংগ্রহ পাওয়া যায়নি।</p>
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
                    মোট কালেকশন {{ $collections->total() }} টি
                </div>
                
                <!-- Page navigation & Page Size dropdown -->
                <div class="flex items-center gap-4">
                    <!-- Pagination numbers -->
                    <div class="flex items-center gap-1">
                        @if ($collections->onFirstPage())
                            <button type="button" class="p-1.5 rounded-lg border border-gray-200 dark:border-slate-800 text-gray-300 dark:text-slate-700 cursor-not-allowed" disabled>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                            </button>
                        @else
                            <button type="button" wire:click="previousPage" class="p-1.5 rounded-lg border border-gray-200 dark:border-slate-800 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-350 cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                            </button>
                        @endif

                        @php
                            $startPage = max(1, $collections->currentPage() - 2);
                            $endPage = min($collections->lastPage(), $collections->currentPage() + 2);
                        @endphp

                        @if ($startPage > 1)
                            <button type="button" wire:click="gotoPage(1)" class="px-2.5 py-1 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-300 font-bold rounded-lg text-xs border border-gray-200 dark:border-slate-750 font-mono cursor-pointer">1</button>
                            @if ($startPage > 2)
                                <span class="px-1 text-gray-405">...</span>
                            @endif
                        @endif

                        @for ($page = $startPage; $page <= $endPage; $page++)
                            @if ($page == $collections->currentPage())
                                <span class="px-2.5 py-1 bg-primary-50 dark:bg-primary-950 text-[#034C3C] dark:text-primary-400 font-bold rounded-lg text-xs border border-primary-200 dark:border-primary-900 font-mono">{{ $page }}</span>
                            @else
                                <button type="button" wire:click="gotoPage({{ $page }})" class="px-2.5 py-1 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-300 font-bold rounded-lg text-xs border border-gray-200 dark:border-slate-750 font-mono cursor-pointer">{{ $page }}</button>
                            @endif
                        @endfor

                        @if ($endPage < $collections->lastPage())
                            @if ($endPage < $collections->lastPage() - 1)
                                <span class="px-1 text-gray-455">...</span>
                            @endif
                            <button type="button" wire:click="gotoPage({{ $collections->lastPage() }})" class="px-2.5 py-1 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-300 font-bold rounded-lg text-xs border border-gray-200 dark:border-slate-750 font-mono cursor-pointer">{{ $collections->lastPage() }}</button>
                        @endif

                        @if ($collections->hasMorePages())
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
                            <span>{{ $perPage }} জমা / পেজ</span>
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
                                    {{ $size }} জমা / পেজ
                                </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- নতুন বাকি জমা / জমা আপডেট Modal -->
    <div x-data="{ show: @entangle('showModal') }" x-show="show" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/50 dark:bg-black/70 backdrop-blur-sm transition-opacity" @click="show = false; $wire.closeModal()"></div>

        <!-- Modal Wrapper -->
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-2xl bg-white dark:bg-slate-900 border border-primary-200 dark:border-primary-900/50 rounded-3xl shadow-2xl p-6 transition-all duration-300 animate-settings-fade text-xs font-sans text-gray-700 dark:text-slate-200" @click.stop>
                
                <!-- Modal Header -->
                <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-850 pb-4 mb-4">
                    <h3 class="text-sm font-bold text-gray-808 dark:text-white flex items-center gap-1.5">
                        বাকি জমা 🤩
                    </h3>
                    <div class="flex items-center gap-3">
                        <!-- Date display -->
                        <span class="px-3 py-1 bg-gray-50 dark:bg-slate-800 border border-gray-150 dark:border-slate-700 rounded-lg text-gray-600 dark:text-slate-300 font-sans font-semibold">
                            {{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}
                        </span>
                        <!-- Close button -->
                        <button type="button" @click="show = false; $wire.closeModal()" class="p-1 rounded-full hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-400 hover:text-gray-600 transition-colors cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <form wire:submit.prevent="save" class="space-y-4">
                    <!-- Customer Details Inputs Row 1 -->
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-gray-500 dark:text-slate-400 font-bold mb-1.5">কাস্টমার আইডি</label>
                            <input type="text" wire:model.live="customer_id" placeholder="আইডি লিখুন" class="w-full px-3.5 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all font-sans font-semibold">
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
                            <div class="relative flex items-center">
                                <span class="absolute left-3 text-gray-400 font-semibold">৳</span>
                                <div class="w-full pl-7 pr-3.5 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-red-500 font-sans font-semibold">
                                    {{ number_format((float)$total_due) }}
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-500 dark:text-slate-400 font-bold mb-1.5">জমা</label>
                            <div class="relative flex items-center">
                                <span class="absolute left-3 text-gray-400 font-semibold">৳</span>
                                <input type="text" inputmode="numeric" step="0.01" wire:model.live="cash" placeholder="০.০০" class="w-full pl-7 pr-3.5 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-gray-800 dark:text-white font-sans font-semibold focus:outline-none focus:border-primary">
                            </div>
                            @error('cash') <span class="text-red-550 text-[10px] block mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-gray-500 dark:text-slate-400 font-bold mb-1.5">নতুন বাকি</label>
                            <div class="relative flex items-center">
                                <span class="absolute left-3 text-gray-400 font-semibold">৳</span>
                                <div class="w-full pl-7 pr-3.5 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 font-sans font-semibold {{ $new_due > 0 ? 'text-red-500' : 'text-primary' }}">
                                    {{ number_format((float)$new_due) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dates and Toggle Row -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-500 dark:text-slate-400 font-bold mb-1.5">নতুন তারিখ</label>
                            <div class="relative flex items-center">
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
                            <!-- Toggle switch styled -->
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

                    <!-- Modal Actions Footer -->
                    <div class="flex flex-col sm:flex-row justify-end gap-2.5 pt-4 border-t border-gray-100 dark:border-slate-850">
                        <button type="button" @click="show = false; $wire.closeModal()" class="px-5 py-2.5 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-750 dark:text-slate-200 hover:text-gray-900 dark:hover:text-white border border-gray-200 dark:border-slate-700 font-bold rounded-xl cursor-pointer transition-all active:scale-95 text-center">
                            ক্লিয়ার
                        </button>
                        <button type="submit" class="px-5 py-2.5 bg-primary hover:bg-primary-dark text-white font-bold rounded-xl cursor-pointer transition-all active:scale-95 flex items-center justify-center gap-1.5 shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            সেভ
                        </button>
                        <button type="button" wire:click="saveAndPrint" class="px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-xl cursor-pointer transition-all active:scale-95 flex items-center justify-center gap-1.5 shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                            সেভ + প্রিন্ট
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Full Table Report Printable Container (আজকের জমা টেবিল প্রিন্ট) -->
    <div id="due-collection-table-print" style="display:none;">
        <x-print-layout type="due-report"
                        :collections="$collections->items()"
                        :totalCollectionSum="$totalCollectionSum"
                        :customerIdMap="$customerIdMap"
                        reportTitle="আজকের জমা রিপোর্ট"
                        :reportDate="$date"
                        :activeSeason="$seasonFilter" />
    </div>

    <!-- Universal Print Preview Modal (4 Formats: A4 Customer, A4 Dual, POS Customer, POS Dual) -->
    <x-print-modal :showPrintModal="$showPrintModal" :printChallan="$printChallan" :isDuePrint="$isDuePrint" />

    {{-- Delete Confirmation Modal --}}
    @if($confirmDeleteId)
    <div class="fixed inset-0 z-[999999] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
         x-data
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-sm w-full p-6 shadow-2xl border border-gray-150 dark:border-slate-800 text-center font-sans">
            <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 mx-auto flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                </svg>
            </div>
            <h3 class="text-base font-bold text-gray-800 dark:text-white mb-2">আপনি কি নিশ্চিত?</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-6">
                আপনি কি নিশ্চিত যে এই জমার তথ্যটি মুছে ফেলতে চান?
            </p>
            <div class="flex items-center justify-center gap-3">
                <button type="button"
                        wire:click="deleteConfirmed"
                        class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white font-bold text-xs rounded-xl shadow-md transition-all cursor-pointer">
                    হ্যাঁ, ডিলিট করুন
                </button>
                <button type="button"
                        wire:click="$set('confirmDeleteId', null)"
                        class="px-5 py-2 bg-gray-200 dark:bg-slate-800 hover:bg-gray-300 dark:hover:bg-slate-700 text-gray-700 dark:text-gray-300 font-bold text-xs rounded-xl transition-all cursor-pointer">
                    না
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
