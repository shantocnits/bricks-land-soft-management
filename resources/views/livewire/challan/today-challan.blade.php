<div class="min-h-screen bg-gray-50 dark:bg-slate-950 transition-colors duration-300">

    <!-- Page Header Bar -->
    <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 px-4 sm:px-6 py-3 rounded-lg flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 transition-colors duration-300">
        <div>
            <h2 class="font-bold text-gray-800 dark:text-white text-sm leading-tight">আজকের চালান</h2>
            <p class="text-[10px] text-gray-400 dark:text-gray-500 font-sans mt-0.5 font-semibold">{{ now()->format('d/m/Y') }} তারিখের সকল চালান</p>
        </div>

        <!-- Top Actions -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3">
            <!-- Search -->
            <div class="relative">
                <input type="text" wire:model.live="search"
                       placeholder="সার্চ করুন..."
                       class="pl-4 pr-4 py-2 text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all w-full sm:w-52 font-sans font-semibold">
            </div>
            <!-- Date picker (Flatpickr) -->
            <div class="relative flex items-center">
                <input type="text"
                       data-flatpickr
                       data-wire-prop="date"
                       data-default="{{ $date }}"
                       wire:model="date"
                       placeholder="তারিখ"
                       readonly
                       class="pl-3 pr-8 py-2 text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all w-44 font-sans font-semibold cursor-pointer">
                <span class="absolute right-2.5 top-2.5 text-emerald-500 pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                </span>
            </div>
            <!-- Report Button -->
            <button type="button" wire:click="openReport"
                    class="px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl cursor-pointer transition-all font-sans flex items-center gap-1.5 shadow-sm active:scale-95">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                রিপোর্ট
            </button>
            <!-- Print -->
            <button type="button" onclick="printChallanArea('today-challan-print-area')"
                    class="px-3 py-2 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-200 text-xs font-semibold rounded-xl cursor-pointer transition-all font-sans border border-gray-200 dark:border-slate-700 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                প্রিন্ট
            </button>
            <!-- New Challan -->
            <button type="button" wire:click="openAddModal"
                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl cursor-pointer transition-all shadow-md active:scale-95 font-sans">
                নতুন চালান
            </button>
        </div>
    </div>

    <!-- Flash Message -->
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
             x-transition:leave="transition ease-in duration-300" x-transition:leave-end="opacity-0"
             class="mx-4 sm:mx-6 mt-4 p-3.5 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-900 text-emerald-800 dark:text-emerald-400 rounded-2xl text-xs font-medium font-sans" x-cloak>
            {{ session('message') }}
        </div>
    @endif

    <!-- Table Card -->
    <div class="py-4 sm:py-6">
        <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-lg shadow-sm overflow-hidden transition-colors duration-300">
            <!-- Summary Bar -->
            <div class="flex flex-wrap items-center gap-4 px-5 py-3.5 border-b border-gray-100 dark:border-slate-800 bg-blue-50/40 dark:bg-blue-950/10">
                <span class="text-xs text-gray-500 dark:text-gray-400 font-sans">মোট: <strong class="text-gray-800 dark:text-white">{{ $challans->total() }} টি</strong></span>
            </div>

            <!-- Responsive Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse border border-gray-200 dark:border-slate-800" style="min-width: 1050px">
                    <thead>
                        <tr class="bg-emerald-600 text-white text-[11px] font-bold uppercase font-sans">
                            <th class="px-3 py-3 text-center w-10 border-r border-white/20 last:border-r-0">#</th>
                            <th class="px-3 py-3 border-r border-white/20 last:border-r-0">খতিয়ান</th>
                            <th class="px-3 py-3 border-r border-white/20 last:border-r-0">ঠিকানা</th>
                            <th class="px-3 py-3 border-r border-white/20 last:border-r-0">শ্রেণি</th>
                            <th class="px-3 py-3 text-right border-r border-white/20 last:border-r-0">পরিমাণ</th>
                            <th class="px-3 py-3 text-right border-r border-white/20 last:border-r-0">রেট</th>
                            <th class="px-3 py-3 text-right border-r border-white/20 last:border-r-0">মূল্য</th>
                            <th class="px-3 py-3 text-right border-r border-white/20 last:border-r-0">মোট মূল্য</th>
                            <th class="px-3 py-3 text-right border-r border-white/20 last:border-r-0">ছাড়</th>
                            <th class="px-3 py-3 text-right border-r border-white/20 last:border-r-0">গাড়ি ভাড়া</th>
                            <th class="px-3 py-3 text-right border-r border-white/20 last:border-r-0">সর্বমোট</th>
                            <th class="px-3 py-3 text-right border-r border-white/20 last:border-r-0">নগদ</th>
                            <th class="px-3 py-3 text-right border-r border-white/20 last:border-r-0">বাকি</th>
                            <th class="px-3 py-3 text-center">বাটন</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800 font-sans">
                        @forelse($challans as $challan)
                            <tr class="hover:bg-emerald-50/40 dark:hover:bg-emerald-950/10 transition-colors text-xs">
                                <td class="px-3 py-3.5 text-center text-gray-500 dark:text-slate-400 font-semibold border-r border-gray-150 dark:border-slate-800 last:border-r-0">{{ $loop->iteration }}</td>
                                <td class="px-3 py-3.5 font-semibold text-gray-800 dark:text-slate-200 whitespace-nowrap border-r border-gray-150 dark:border-slate-800 last:border-r-0">{{ $challan->customer_name }}</td>
                                <td class="px-3 py-3.5 text-gray-600 dark:text-slate-400 border-r border-gray-150 dark:border-slate-800 last:border-r-0">{{ $challan->customer_address }}</td>
                                <td class="px-3 py-3.5 border-r border-gray-150 dark:border-slate-800 last:border-r-0">
                                    @foreach($challan->items as $item)
                                        <span class="block font-semibold text-emerald-700 dark:text-emerald-400">{{ $item->category_name }}</span>
                                    @endforeach
                                </td>
                                <td class="px-3 py-3.5 text-right font-semibold text-gray-700 dark:text-slate-300 border-r border-gray-150 dark:border-slate-800 last:border-r-0">
                                    @foreach($challan->items as $item)
                                        <div x-data="{ openTooltip: false, rect: null }" 
                                             @mouseenter="openTooltip = true; rect = $el.getBoundingClientRect()" 
                                             @mouseleave="openTooltip = false"
                                             class="relative flex items-center justify-end gap-1.5 cursor-pointer">
                                            <span>{{ number_format($item->quantity) }}</span>
                                            <span class="w-3.5 h-3.5 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0">
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                            </span>
                                            
                                            <!-- Teleported Tooltip box -->
                                            <template x-teleport="body">
                                                <div x-show="openTooltip" 
                                                     class="fixed bg-white dark:bg-slate-900 text-gray-800 dark:text-slate-200 border border-gray-255 dark:border-slate-700 p-3.5 rounded-2xl shadow-2xl z-[99999] text-left w-48 font-sans font-semibold text-xs pointer-events-none"
                                                     :style="'top: ' + (rect ? Math.max(8, rect.top - 140) : 0) + 'px; left: ' + (rect ? Math.max(8, Math.min(rect.left - 60, window.innerWidth - 200)) : 0) + 'px;'"
                                                     x-cloak>
                                                    <h4 class="text-gray-800 dark:text-white border-b border-gray-100 dark:border-slate-800 pb-1.5 mb-1.5 font-bold flex items-center gap-1.5">
                                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                        ডেলিভারি তথ্য
                                                    </h4>
                                                    <div class="space-y-1">
                                                        <div class="flex justify-between"><span>শ্রেণি:</span> <span class="text-emerald-600 dark:text-emerald-400 font-bold">{{ $item->category_name }}</span></div>
                                                        <div class="flex justify-between"><span>পরিমাণ:</span> <span class="font-bold">{{ number_format($item->quantity) }}</span></div>
                                                        <div class="flex justify-between"><span>ডেলিভারি:</span> <span class="text-blue-600 dark:text-blue-400 font-bold">{{ number_format($item->quantity) }}</span></div>
                                                        <div class="flex justify-between"><span>ডেলিভারি বাকি:</span> <span class="text-red-500 font-bold">০</span></div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    @endforeach
                                </td>
                                <td class="px-3 py-3.5 text-right text-gray-600 dark:text-slate-400 border-r border-gray-150 dark:border-slate-800 last:border-r-0">
                                    @foreach($challan->items as $item)
                                        <span class="block">৳{{ number_format($item->rate, 2) }}</span>
                                    @endforeach
                                </td>
                                <td class="px-3 py-3.5 text-right font-semibold text-gray-700 dark:text-slate-300 border-r border-gray-150 dark:border-slate-800 last:border-r-0">৳{{ number_format($challan->value, 2) }}</td>
                                <td class="px-3 py-3.5 text-right font-semibold text-gray-700 dark:text-slate-300 border-r border-gray-150 dark:border-slate-800 last:border-r-0">৳{{ number_format($challan->value, 2) }}</td>
                                <td class="px-3 py-3.5 text-right text-amber-600 dark:text-amber-400 border-r border-gray-150 dark:border-slate-800 last:border-r-0">৳{{ number_format($challan->discount, 2) }}</td>
                                <td class="px-3 py-3.5 text-right font-semibold text-gray-700 dark:text-slate-300 border-r border-gray-150 dark:border-slate-800 last:border-r-0">৳{{ number_format($challan->transport_rent, 2) }}</td>
                                <td class="px-3 py-3.5 text-right font-bold text-gray-800 dark:text-slate-200 border-r border-gray-150 dark:border-slate-800 last:border-r-0">৳{{ number_format($challan->grand_total, 2) }}</td>
                                <td class="px-3 py-3.5 text-right text-emerald-600 dark:text-emerald-400 font-semibold border-r border-gray-150 dark:border-slate-800 last:border-r-0">৳{{ number_format($challan->cash, 2) }}</td>
                                <td class="px-3 py-3.5 text-right border-r border-gray-150 dark:border-slate-800 last:border-r-0">
                                    <span class="font-bold {{ $challan->due > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-400' }}">৳{{ number_format($challan->due, 2) }}</span>
                                </td>
                                <!-- Dropdown Button -->
                                <td class="px-3 py-3.5 text-center relative" x-data="{ openDropdown: false, buttonRect: null }">
                                    <button type="button" @click="openDropdown = !openDropdown; buttonRect = $el.getBoundingClientRect()" class="p-1.5 text-gray-500 hover:text-emerald-600 dark:hover:text-emerald-400 focus:outline-none transition-all cursor-pointer">
                                        <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z"/>
                                        </svg>
                                    </button>

                                    <template x-teleport="body">
                                        <div x-show="openDropdown" @click.away="openDropdown = false" x-transition
                                             class="fixed w-48 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-2xl shadow-2xl p-1.5 z-[9999] text-left text-xs flex flex-col gap-0.5"
                                             :style="buttonRect ? ('left: ' + (buttonRect.left - 140) + 'px; position: fixed; ' + (window.innerHeight - buttonRect.bottom < 240 ? 'bottom: ' + (window.innerHeight - buttonRect.top + 4) + 'px;' : 'top: ' + (buttonRect.bottom + 4) + 'px;')) : ''"
                                             x-cloak>
                                            <button type="button" wire:click="edit({{ $challan->id }})" @click="openDropdown = false" class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 text-gray-700 dark:text-slate-200 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-xl cursor-pointer flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                আপডেট করুন
                                            </button>
                                            <button type="button" @click="openDropdown = false" class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 text-gray-700 dark:text-slate-200 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-xl cursor-pointer flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                                                প্রিন্ট চালান
                                            </button>
                                            <button type="button" @click="openDropdown = false" wire:click="openDeliveryModal({{ $challan->id }})" class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 text-gray-700 dark:text-slate-200 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-xl cursor-pointer flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                                                ডেলিভারি দিন
                                            </button>
                                            <button type="button" @click="openDropdown = false" wire:click="openChallanDetailsModal({{ $challan->id }})" class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 text-gray-700 dark:text-slate-200 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-xl cursor-pointer flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                চালান বিস্তারিত
                                            </button>
                                            <a href="{{ route('challan.customer-profile', ['phone' => $challan->customer_phone ?: $challan->customer_name]) }}" class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 text-gray-700 dark:text-slate-200 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-xl cursor-pointer flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                প্রোফাইল এ যান
                                            </a>
                                            <div class="border-t border-gray-100 dark:border-slate-800 my-1"></div>
                                            <button type="button" wire:click="delete({{ $challan->id }})" onclick="confirm('চালানটি মুছে ফেলবেন?') || event.stopImmediatePropagation()" @click="openDropdown = false" class="w-full text-left px-3 py-2 hover:bg-red-50 dark:hover:bg-red-950/20 text-red-500 hover:text-red-700 transition-all font-semibold rounded-xl cursor-pointer flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                ডিলিট করুন
                                            </button>
                                        </div>
                                    </template>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" class="px-4 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <p class="text-xs text-gray-400 dark:text-gray-500 italic font-sans">আজকের কোনো চালান পাওয়া যায়নি।</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <!-- Totals Footer Row -->
                    @if($challans->count() > 0)
                    <tfoot>
                        <tr class="bg-emerald-600/10 dark:bg-emerald-950/30 border-t-2 border-emerald-200 dark:border-emerald-900/50 text-xs font-bold font-sans">
                            <td colspan="4" class="px-3 py-3 text-emerald-700 dark:text-emerald-400 border-r border-gray-200 dark:border-slate-800 last:border-r-0">মোট চালান {{ $challans->count() }} টি</td>
                            <td class="px-3 py-3 text-right text-emerald-700 dark:text-emerald-400 border-r border-gray-200 dark:border-slate-800 last:border-r-0">
                                {{ number_format($challans->sum(fn($c) => $c->items->sum('quantity'))) }}
                            </td>
                            <td class="px-3 py-3 text-right border-r border-gray-200 dark:border-slate-800 last:border-r-0">—</td>
                            <td class="px-3 py-3 text-right text-emerald-700 dark:text-emerald-400 border-r border-gray-200 dark:border-slate-800 last:border-r-0">৳{{ number_format($challans->sum('value'), 2) }}</td>
                            <td class="px-3 py-3 text-right text-emerald-700 dark:text-emerald-400 border-r border-gray-200 dark:border-slate-800 last:border-r-0">৳{{ number_format($challans->sum('value'), 2) }}</td>
                            <td class="px-3 py-3 text-right text-amber-600 border-r border-gray-200 dark:border-slate-800 last:border-r-0">৳{{ number_format($challans->sum('discount'), 2) }}</td>
                            <td class="px-3 py-3 text-right text-emerald-700 dark:text-emerald-400 border-r border-gray-200 dark:border-slate-800 last:border-r-0">৳{{ number_format($challans->sum('transport_rent'), 2) }}</td>
                            <td class="px-3 py-3 text-right text-gray-800 dark:text-slate-200 border-r border-gray-200 dark:border-slate-800 last:border-r-0">৳{{ number_format($challans->sum('grand_total'), 2) }}</td>
                            <td class="px-3 py-3 text-right text-emerald-700 dark:text-emerald-400 border-r border-gray-200 dark:border-slate-800 last:border-r-0">৳{{ number_format($challans->sum('cash'), 2) }}</td>
                            <td class="px-3 py-3 text-right text-red-500 border-r border-gray-200 dark:border-slate-800 last:border-r-0">৳{{ number_format($challans->sum('due'), 2) }}</td>
                            <td class="px-3 py-3"></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-gray-100 dark:border-slate-800">
                {{ $challans->links() }}
            </div>
        </div>
    </div>

    <!-- Report Modal -->
    <template x-teleport="body">
        <div x-data="{ open: @entangle('showReport') }"
             x-show="open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click.self="open = false; $wire.closeReport()"
             class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
             x-cloak>
            <div class="bg-white dark:bg-slate-900 rounded-2xl w-full max-w-lg border border-gray-200 dark:border-slate-700 shadow-2xl relative overflow-hidden"
                 x-show="open"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4">

                <!-- Report Header -->
                <div class="bg-emerald-600 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-white font-bold text-base font-sans flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        বিক্রি রিপোর্ট
                    </h3>
                    <button type="button" @click="open = false; $wire.closeReport()" class="text-white/80 hover:text-white transition-colors cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Report Body -->
                <div class="p-5">
                    @php $report = $this->reportData; @endphp

                    <!-- Category Table -->
                    <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-slate-700 mb-5">
                        <table class="w-full text-sm text-left">
                            <thead>
                                <tr class="bg-emerald-600 text-white text-xs font-bold uppercase">
                                    <th class="px-4 py-3 border-r border-white/20">শ্রেণি</th>
                                    <th class="px-4 py-3 text-center border-r border-white/20">চালান</th>
                                    <th class="px-4 py-3 text-right">পরিমাণ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                                @forelse($report['rows'] as $row)
                                    <tr class="text-xs hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors">
                                        <td class="px-4 py-3 font-semibold text-gray-800 dark:text-slate-200 border-r border-gray-100 dark:border-slate-800">{{ $row['category'] }}</td>
                                        <td class="px-4 py-3 text-center font-semibold text-gray-700 dark:text-slate-300 border-r border-gray-100 dark:border-slate-800">{{ $row['challan_count'] }}</td>
                                        <td class="px-4 py-3 text-right font-semibold text-gray-700 dark:text-slate-300">{{ number_format($row['quantity']) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-4 py-8 text-center text-xs text-gray-400 italic">কোনো ডেটা নেই।</td></tr>
                                @endforelse
                                @if(count($report['rows']) > 0)
                                    <tr class="bg-emerald-50 dark:bg-emerald-950/30 text-xs font-bold border-t-2 border-emerald-200 dark:border-emerald-800">
                                        <td class="px-4 py-3 text-emerald-700 dark:text-emerald-400 border-r border-emerald-100 dark:border-emerald-900">মোট</td>
                                        <td class="px-4 py-3 text-center text-emerald-700 dark:text-emerald-400 border-r border-emerald-100 dark:border-emerald-900">{{ $report['total_challans'] }}</td>
                                        <td class="px-4 py-3 text-right text-emerald-700 dark:text-emerald-400">{{ number_format($report['total_qty']) }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- Summary Rows -->
                    <div class="space-y-2 text-xs font-sans">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-slate-800">
                            <span class="text-gray-600 dark:text-gray-400 font-semibold">মোট বিক্রি মূল্য</span>
                            <span class="font-bold text-gray-800 dark:text-white">৳{{ number_format($report['total_value'], 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-slate-800">
                            <span class="text-amber-600 dark:text-amber-400 font-semibold">ছাড় (-)</span>
                            <span class="font-bold text-amber-600 dark:text-amber-400">৳{{ number_format($report['total_discount'], 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-slate-800">
                            <span class="text-blue-600 dark:text-blue-400 font-semibold">মোট ভাড়া (+)</span>
                            <span class="font-bold text-blue-600 dark:text-blue-400">৳{{ number_format($report['total_transport'], 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-slate-800">
                            <span class="text-gray-600 dark:text-gray-400 font-semibold">মোট বিক্রি (ভাড়া সহ)</span>
                            <span class="font-bold text-gray-800 dark:text-white">৳{{ number_format($report['total_grand'], 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-slate-800">
                            <span class="text-emerald-600 dark:text-emerald-400 font-bold">নগদ</span>
                            <span class="font-bold text-emerald-600 dark:text-emerald-400">৳{{ number_format($report['total_cash'], 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-red-500 font-bold">বাকি</span>
                            <span class="font-bold text-red-500">৳{{ number_format($report['total_due'], 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!-- Modal: Add/Edit Challan -->
    @if($showModal)
        <div x-data="{ open: true }"
             x-show="open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click.self="open = false; $wire.closeModal()"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
             x-cloak>
            <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-4xl w-full border border-gray-200 dark:border-slate-700 shadow-2xl p-6 relative max-h-[92vh] overflow-y-auto challan-modal-scroll"
                 @scroll.passive="$dispatch('close-cat-dropdowns')"
                 x-show="open"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4">

                <!-- Close Button -->
                <button type="button" @click="open = false; $wire.closeModal()"
                        class="absolute top-4 right-4 bg-gray-100 hover:bg-gray-250 dark:bg-slate-800 dark:hover:bg-slate-700 text-gray-400 dark:text-gray-300 w-8 h-8 rounded-full flex items-center justify-center cursor-pointer transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>

                <!-- Modal Heading -->
                <h3 class="text-lg font-black text-gray-800 dark:text-white mb-5 pb-3 font-sans flex items-center gap-2">
                    {{ $editingId ? 'চালান আপডেট' : 'নতুন চালান' }} 
                </h3>

                <form wire:submit.prevent="save" class="space-y-4 max-w-full">
                    <!-- Top controls row (Tabs + Challan No + Date) -->
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 border-b border-gray-100 dark:border-slate-800 pb-4 mb-6">
                        <!-- Tab Buttons -->
                        <div class="flex items-center gap-2">
                            <button type="button" @click="$wire.set('customer_type', 'new')"
                                    class="px-4 py-2 text-xs font-bold rounded-lg transition-all cursor-pointer font-sans"
                                    :class="$wire.customer_type === 'new' ? 'bg-[#009E74] text-white shadow-sm' : 'border border-[#009E74] text-[#009E74] hover:bg-emerald-50 dark:hover:bg-emerald-950/20'">
                                নতুন কাস্টমার
                            </button>
                            <button type="button" @click="$wire.set('customer_type', 'old')"
                                    class="px-4 py-2 text-xs font-bold rounded-lg transition-all cursor-pointer font-sans"
                                    :class="$wire.customer_type === 'old' ? 'bg-white border border-orange-500 text-orange-500 shadow-sm' : 'border border-orange-500 text-orange-500 hover:bg-orange-50 dark:hover:bg-orange-950/20'">
                                পুরাতন কাস্টমার
                            </button>
                        </div>

                        <!-- Challan No & Date -->
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-2 bg-white dark:bg-slate-900 border border-gray-250 dark:border-slate-700 rounded-lg px-3 py-1.5 w-36">
                                <span class="text-[10px] font-bold text-gray-500 dark:text-gray-400 font-sans whitespace-nowrap">চালান নম্বর:</span>
                                <input type="text" wire:model="challan_no" class="w-full bg-transparent text-xs font-bold text-gray-800 dark:text-white focus:outline-none border-none p-0 text-center font-sans">
                            </div>
                            <div class="relative flex items-center">
                                <input type="text"
                                       data-flatpickr
                                       data-wire-prop="date"
                                       data-default="{{ $date }}"
                                       wire:model="date"
                                       placeholder="তারিখ"
                                       readonly
                                       class="pl-3 pr-8 py-1.5 text-xs rounded-lg border border-gray-250 dark:border-slate-700 bg-white dark:bg-slate-900 text-gray-808 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-505/10 transition-all w-32 font-sans font-semibold cursor-pointer text-center">
                                <span class="absolute right-2.5 top-2 text-gray-450 pointer-events-none">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Selection Row (Only for old customer) -->
                    <div x-show="$wire.customer_type === 'old'" class="mb-4" x-cloak>
                        <label class="block text-xs font-bold text-gray-505 dark:text-gray-400 mb-1.5 font-sans">পুরাতন খতিয়ান গ্রাহক</label>
                        <div class="relative" x-data="{ openLedger: false, triggerRect: null, searchLedger: '' }">
                            <button type="button" @click="openLedger = !openLedger; triggerRect = $el.getBoundingClientRect()"
                                    class="w-full flex items-center justify-between py-2 px-3 rounded-lg border border-gray-250 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-semibold text-gray-808 dark:text-white focus:outline-none focus:border-emerald-500 cursor-pointer text-left">
                                @php
                                    $selectedLedgerName = '';
                                    if ($ledger_id) {
                                        $selectedLedger = $ledgers->firstWhere('id', $ledger_id);
                                        if ($selectedLedger) {
                                            $selectedLedgerName = $selectedLedger->name;
                                        }
                                    }
                                @endphp
                                <span>{{ $selectedLedgerName ?: 'গ্রাহক নির্বাচন করুন...' }}</span>
                                <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': openLedger }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <template x-teleport="body">
                                <div x-show="openLedger" @click.away="openLedger = false" @close-cat-dropdowns.window="openLedger = false" x-transition
                                     class="absolute w-64 bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-gray-200 dark:border-slate-700 z-[9999] overflow-hidden text-left"
                                     :style="'top: ' + (triggerRect ? (triggerRect.bottom + window.scrollY + 2) : 0) + 'px; left: ' + (triggerRect ? (triggerRect.left + window.scrollX) : 0) + 'px;'"
                                     x-cloak>
                                    <div class="p-2 border-b border-gray-100 dark:border-slate-800 bg-gray-50 dark:bg-slate-950">
                                        <input type="text" x-model="searchLedger" placeholder="সার্চ করুন..."
                                               class="w-full py-1.5 px-3 text-xs rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-gray-808 dark:text-white focus:outline-none focus:border-emerald-500 font-sans">
                                    </div>
                                    <div class="max-h-48 overflow-y-auto py-1">
                                        <button type="button" @click="$wire.set('ledger_id', ''); $wire.updatedLedgerId(''); openLedger = false; searchLedger = ''"
                                                class="w-full text-left px-3 py-2 hover:bg-emerald-50/30 dark:hover:bg-emerald-950/20 text-xs font-semibold text-gray-400 dark:text-gray-505 font-sans cursor-pointer block">
                                            গ্রাহক নির্বাচন করুন...
                                        </button>
                                        @php
                                            $orderedLedgers = $ledgers->sortBy('name');
                                        @endphp
                                        @foreach($orderedLedgers as $ledger)
                                            <button type="button"
                                                    x-show="searchLedger === '' || '{{ $ledger->name }}'.toLowerCase().includes(searchLedger.toLowerCase())"
                                                    @click="$wire.set('ledger_id', '{{ $ledger->id }}'); $wire.updatedLedgerId('{{ $ledger->id }}'); openLedger = false; searchLedger = ''"
                                                    class="w-full text-left px-3 py-2 hover:bg-emerald-50/30 dark:hover:bg-emerald-950/20 text-xs font-semibold text-gray-808 dark:text-white hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-sans cursor-pointer block">
                                                {{ $ledger->name }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Customer fields (Phone, Name, Address) -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-505 dark:text-gray-400 mb-1.5 font-sans">ফোন নম্বর - ০</label>
                            <div class="relative">
                                <input type="text" wire:model="customer_phone" placeholder="ফোন নম্বর" maxlength="11"
                                       class="w-full py-2 px-3 rounded-lg border border-gray-250 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs text-gray-808 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-505/10 transition-all font-sans font-semibold pr-12">
                                <span class="absolute right-2.5 top-2.5 text-[10px] text-gray-400 font-sans" x-text="($wire.customer_phone || '').length + ' / 11'"></span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-550 dark:text-gray-400 mb-1.5 font-sans">কাস্টমারের নাম</label>
                            <input type="text" wire:model="customer_name" placeholder="কাস্টমারের নাম" 
                                   class="w-full py-2 px-3 rounded-lg border border-gray-250 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs text-gray-808 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-505/10 transition-all font-sans font-semibold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-550 dark:text-gray-400 mb-1.5 font-sans">কাস্টমারের ঠিকানা</label>
                            <input type="text" wire:model="customer_address" placeholder="কাস্টমারের ঠিকানা" 
                                   class="w-full py-2 px-3 rounded-lg border border-gray-250 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs text-gray-808 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-505/10 transition-all font-sans font-semibold">
                        </div>
                    </div>

                    <!-- Row 2: Challan type, Delivery date, Notes -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-550 dark:text-gray-400 mb-1.5 font-sans">চালানের ধরণ</label>
                            <!-- Custom dropdown matching category selector -->
                            <div class="relative" x-data="{ openType: false, triggerRect: null }">
                                <button type="button" @click="openType = !openType; triggerRect = $el.getBoundingClientRect()"
                                        class="w-full flex items-center justify-between py-2 px-3 rounded-lg border border-gray-250 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-semibold text-gray-808 dark:text-white focus:outline-none focus:border-emerald-500 cursor-pointer text-left">
                                    <span>{{ $challan_type === 'অগ্রিম' ? 'অগ্রিম চালান' : 'রেগুলার চালান' }}</span>
                                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': openType }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <template x-teleport="body">
                                    <div x-show="openType" @click.away="openType = false" @close-cat-dropdowns.window="openType = false" x-transition
                                         class="absolute w-48 bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-gray-200 dark:border-slate-700 z-[9999] overflow-hidden text-left"
                                         :style="'top: ' + (triggerRect ? (triggerRect.bottom + window.scrollY + 2) : 0) + 'px; left: ' + (triggerRect ? (triggerRect.left + window.scrollX) : 0) + 'px;'"
                                         x-cloak>
                                        <div class="py-1">
                                            <button type="button" @click="$wire.set('challan_type', 'আজকের'); openType = false;"
                                                    class="w-full text-left px-3.5 py-2 hover:bg-emerald-50/30 dark:hover:bg-emerald-950/20 text-xs font-semibold text-gray-808 dark:text-white hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-sans cursor-pointer block">
                                                রেগুলার চালান
                                            </button>
                                            <button type="button" @click="$wire.set('challan_type', 'অগ্রিম'); openType = false;"
                                                    class="w-full text-left px-3.5 py-2 hover:bg-emerald-50/30 dark:hover:bg-emerald-950/20 text-xs font-semibold text-gray-808 dark:text-white hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-sans cursor-pointer block">
                                                অগ্রিম চালান
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-550 dark:text-gray-400 mb-1.5 font-sans">ডেলিভারি তারিখ</label>
                            <div class="relative flex items-center">
                                <input type="text"
                                       data-flatpickr
                                       data-wire-prop="date"
                                       data-default="{{ $date }}"
                                       wire:model="date"
                                       placeholder="ডেলিভারি তারিখ"
                                       readonly
                                       class="w-full py-2 pl-3 pr-10 rounded-lg border border-gray-250 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs text-gray-808 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-505/10 transition-all font-sans font-semibold cursor-pointer text-center">
                                <span class="absolute right-3 top-2.5 text-gray-400 pointer-events-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                </span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-550 dark:text-gray-400 mb-1.5 font-sans">নোট</label>
                            <input type="text" wire:model="notes" placeholder="নোট" 
                                   class="w-full py-2 px-3 rounded-lg border border-gray-250 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs text-gray-808 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-505/10 transition-all font-sans font-semibold">
                        </div>
                    </div>

                    <!-- Items Card Wrapper -->
                    <div class="mt-6 bg-slate-50/50 dark:bg-slate-950/20 border border-gray-200 dark:border-slate-800 rounded-2xl p-4">
                        <div class="hidden md:grid grid-cols-12 gap-3 text-[10px] font-bold text-gray-500 uppercase font-sans border-b border-gray-200 dark:border-slate-800 pb-2 mb-3">
                            <div class="col-span-1 text-center">#</div>
                            <div class="col-span-4">শ্রেণি</div>
                            <div class="col-span-2 text-right">রেট</div>
                            <div class="col-span-2 text-right">পরিমাণ</div>
                            <div class="col-span-2 text-right">মূল্য</div>
                            <div class="col-span-1 text-center">মুছুন</div>
                        </div>

                        <div class="space-y-3">
                            @foreach($items as $index => $item)
                                <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center text-xs bg-white dark:bg-slate-900 md:bg-transparent p-3 md:p-0 rounded-xl border border-gray-100 dark:border-slate-800 md:border-none">
                                    <!-- Mobile header row: item label + add/delete button -->
                                    <div class="flex md:hidden items-center justify-between mb-1 pb-1.5 border-b border-gray-100 dark:border-slate-800">
                                        <span class="font-bold text-gray-600 dark:text-gray-400 text-[11px]">
                                            @if($loop->first)
                                                আইটেম ১
                                            @else
                                                আইটেম {{ $loop->iteration }}
                                            @endif
                                        </span>
                                        <div class="flex items-center gap-2">
                                            @if($loop->first)
                                                <button type="button" wire:click="addItem" class="w-7 h-7 rounded-lg bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 font-bold flex items-center justify-center cursor-pointer transition-all border border-emerald-200 text-base leading-none shrink-0">+</button>
                                            @else
                                                <button type="button" wire:click="removeItem({{ $index }})" class="p-1.5 text-gray-400 hover:text-red-500 transition-all cursor-pointer bg-red-50 hover:bg-red-100 dark:bg-red-950/20 dark:hover:bg-red-900/30 rounded-lg shrink-0">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- Desktop: # column -->
                                    <div class="col-span-1 hidden md:flex justify-center items-center">
                                        @if($loop->first)
                                            <button type="button" wire:click="addItem" class="w-7 h-7 rounded-lg bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 font-bold flex items-center justify-center cursor-pointer transition-all border border-emerald-200">+</button>
                                        @else
                                            <span class="font-bold text-gray-600 dark:text-gray-400 font-sans">{{ $loop->iteration }}</span>
                                        @endif
                                    </div>

                                    <div class="col-span-4 relative" x-data="{ openCat: false, triggerRect: null }">
                                        <span class="md:hidden block font-bold text-gray-505 mb-1 text-[11px]">শ্রেণিঃ</span>
                                        <button type="button" @click="openCat = !openCat; triggerRect = $el.getBoundingClientRect()"
                                                class="w-full flex items-center justify-between py-2 px-3 rounded-lg border border-gray-250 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-semibold text-gray-808 dark:text-white focus:outline-none focus:border-emerald-500 cursor-pointer">
                                            <span x-text="$wire.items[$index]['category_name'] || 'শ্রেণি নির্বাচন করুন...'"></span>
                                            <svg class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': openCat }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </button>
                                        <template x-teleport="body">
                                            <div x-show="openCat" @click.away="openCat = false" @close-cat-dropdowns.window="openCat = false" x-transition
                                                 class="absolute w-64 bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-gray-200 dark:border-slate-700 z-[9999] overflow-hidden text-left"
                                                 :style="'top: ' + (triggerRect ? (triggerRect.bottom + window.scrollY + 2) : 0) + 'px; left: ' + (triggerRect ? (triggerRect.left + window.scrollX) : 0) + 'px;'"
                                                 x-cloak>
                                                <div class="p-2 border-b border-gray-100 dark:border-slate-800 flex gap-1.5 bg-gray-50 dark:bg-slate-950">
                                                    <input type="text" wire:model="newCategoryInput" placeholder="ফিল্টার বা নতুন শ্রেণি..."
                                                           class="flex-1 py-1 px-2 text-[10px] rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-gray-808 dark:text-white focus:outline-none focus:border-emerald-500 font-sans"
                                                           @keydown.enter.prevent="$wire.addCategoryOption()">
                                                    <button type="button" wire:click="addCategoryOption"
                                                            class="px-2 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition-all cursor-pointer">
                                                        +
                                                    </button>
                                                </div>
                                                <div class="max-h-40 overflow-y-auto py-1">
                                                    @foreach($categories as $cat)
                                                        <div class="flex items-center justify-between px-3 py-1.5 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 transition-all text-xs"
                                                             x-show="$wire.newCategoryInput === '' || '{{ $cat->name }}'.toLowerCase().includes($wire.newCategoryInput.toLowerCase())">
                                                            <button type="button" @click="$wire.selectCategory({{ $index }}, '{{ $cat->name }}'); openCat = false; $wire.set('newCategoryInput', '')"
                                                                    class="flex-1 text-left font-semibold text-gray-800 dark:text-white hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-sans">
                                                                {{ $cat->name }} <span class="text-emerald-600 dark:text-emerald-400 font-normal">(৳{{ floatval($cat->rate) }})</span>
                                                            </button>
                                                            <button type="button" wire:click="deleteCategoryOption({{ $cat->id }})"
                                                                    onclick="confirm('এই শ্রেণিটি মুছবেন?') || event.stopImmediatePropagation()"
                                                                    class="ml-2 text-gray-405 hover:text-red-505 transition-all rounded cursor-pointer">
                                                                ×
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </template>
                                    </div>

                                    <div class="col-span-2">
                                        <span class="md:hidden block font-bold text-gray-505 mb-1 text-[11px]">রেটঃ</span>
                                        <input type="number" step="0.01" wire:model.live="items.{{ $index }}.rate" placeholder="৮০" @focus="$el.select()"
                                               class="w-full py-1.5 px-2.5 rounded-lg border border-gray-250 dark:border-slate-700 bg-white dark:bg-slate-900 text-right text-xs font-semibold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all text-gray-808 dark:text-white font-sans">
                                    </div>
                                    <div class="col-span-2">
                                        <span class="md:hidden block font-bold text-gray-550 mb-1 text-[11px]">পরিমাণঃ</span>
                                        <input type="number" wire:model.live="items.{{ $index }}.quantity" placeholder="০" @focus="$el.select()"
                                               class="w-full py-1.5 px-2.5 rounded-lg border border-gray-250 dark:border-slate-700 bg-white dark:bg-slate-900 text-right text-xs font-semibold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all text-gray-808 dark:text-white font-sans">
                                    </div>
                                    <div class="col-span-2 text-right font-sans font-bold text-gray-700 dark:text-slate-300">
                                        <span class="md:hidden font-bold text-gray-555 float-left text-[11px]">মূল্যঃ</span>
                                        ৳{{ number_format(floatval($item['amount'] ?? 0), 2) }}
                                    </div>
                                    <!-- Desktop delete column only -->
                                    <div class="col-span-1 text-center hidden md:flex justify-center items-center">
                                        @if(!$loop->first)
                                            <button type="button" wire:click="removeItem({{ $index }})" class="p-1 text-gray-408 hover:text-red-500 transition-all cursor-pointer bg-red-50 hover:bg-red-100 dark:bg-red-950/20 dark:hover:bg-red-900/30 rounded-lg">
                                                <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Split calculations (Demo/SMS + calculations) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 items-start">
                        <!-- Left Side: Demo/SMS or Due Date Field -->
                        <div class="flex flex-col items-center justify-center p-5 bg-gray-50/50 dark:bg-slate-950/20 border border-gray-150 dark:border-slate-800 rounded-2xl gap-4 w-full">
                            @if($due > 0)
                                <div class="w-full">
                                    <label class="block text-xs font-bold text-red-500 dark:text-red-400 mb-1.5 font-sans">বাকি পরিশোধের তারিখ</label>
                                    <div class="relative flex items-center">
                                        <input type="text"
                                               data-flatpickr
                                               data-wire-prop="due_payment_date"
                                               data-default="{{ $due_payment_date }}"
                                               wire:model="due_payment_date"
                                               placeholder="পরিশোধের সম্ভাব্য তারিখ"
                                               readonly
                                               class="w-full py-2 px-3 rounded-lg border border-red-350 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs text-gray-808 dark:text-white focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-505/20 transition-all font-sans font-semibold cursor-pointer text-center text-red-650 dark:text-red-450 font-bold">
                                        <span class="absolute right-3 top-2.5 text-red-450 pointer-events-none">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                        </span>
                                    </div>
                                </div>
                            @else
                                <!-- DEMO banner -->
                                <div class="flex items-center justify-center gap-2 bg-[#FEE2E2] dark:bg-red-950/30 border border-red-200 dark:border-red-900/50 text-red-600 dark:text-red-400 px-6 py-2.5 rounded-xl font-black text-sm tracking-wider uppercase select-none w-full max-w-[200px] text-center">
                                    <span>🚨</span> DEMO
                                </div>
                            @endif

                            <div class="flex items-center justify-between w-full border-t border-gray-150 dark:border-slate-800 pt-4">
                                <span class="text-xs font-bold text-gray-708 dark:text-slate-350 font-sans">কাস্টমারকে এসএমএস দিন</span>
                                <button type="button" @click="$wire.send_sms = !$wire.send_sms" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none" :class="$wire.send_sms ? 'bg-[#009E74]' : 'bg-gray-200 dark:bg-slate-700'">
                                    <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out" :class="$wire.send_sms ? 'translate-x-5' : 'translate-x-0'"></span>
                                </button>
                            </div>
                        </div>

                        <!-- Right Side: Calculations (2-column compact grid) -->
                        <div class="grid grid-cols-[max-content_1fr] gap-x-3 gap-y-2 items-center font-sans">
                            <span class="text-xs font-bold text-gray-600 dark:text-gray-400 whitespace-nowrap">মূল্য:</span>
                            <input type="text" value="{{ number_format($value) }}" disabled class="py-1.5 px-3 text-xs bg-gray-50 dark:bg-slate-900 border border-gray-250 dark:border-slate-700 rounded-lg text-right text-gray-700 dark:text-gray-300 font-bold select-none font-sans w-full">

                            <span class="text-xs font-bold text-[#E57E22] dark:text-orange-400 whitespace-nowrap">ছাড়:</span>
                            <input type="number" wire:model.live="discount" @focus="$el.select()" placeholder="৳" class="py-1.5 px-3 text-xs bg-white dark:bg-slate-900 border border-gray-250 dark:border-slate-700 rounded-lg text-right text-[#E57E22] dark:text-orange-400 font-bold focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/10 transition-all font-sans w-full">

                            <span class="text-xs font-bold text-blue-600 dark:text-blue-400 whitespace-nowrap">গাড়ি ভাড়া:</span>
                            <input type="number" wire:model.live="transport_rent" @focus="$el.select()" placeholder="৳" class="py-1.5 px-3 text-xs bg-white dark:bg-slate-900 border border-gray-250 dark:border-slate-700 rounded-lg text-right text-blue-600 dark:text-blue-400 font-bold focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all font-sans w-full">

                            <span class="text-xs font-bold text-gray-800 dark:text-slate-200 whitespace-nowrap">মোট:</span>
                            <input type="text" value="{{ number_format($grand_total) }}" disabled class="py-1.5 px-3 text-xs bg-gray-50 dark:bg-slate-900 border border-gray-250 dark:border-slate-700 rounded-lg text-right text-gray-800 dark:text-slate-200 font-extrabold select-none font-sans w-full">

                            <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 whitespace-nowrap">নগদ:</span>
                            <input type="number" wire:model.live="cash" @focus="$el.select()" @click="$wire.set('cash', $wire.grand_total)" placeholder="৳" class="py-1.5 px-3 text-xs bg-white dark:bg-slate-900 border border-gray-255 dark:border-slate-700 rounded-lg text-right text-emerald-600 dark:text-emerald-400 font-bold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all font-sans w-full">

                            <span class="text-xs font-bold text-red-500 dark:text-red-400 whitespace-nowrap">বাকি:</span>
                            <input type="text" value="{{ number_format($due) }}" disabled class="py-1.5 px-3 text-xs bg-gray-50 dark:bg-slate-900 border border-gray-250 dark:border-slate-700 rounded-lg text-right text-red-600 dark:text-red-400 font-bold select-none font-sans w-full">
                        </div>
                    </div>

                    <!-- Footer Actions -->
                    <div class="flex items-center justify-end gap-3 pt-5 border-t border-gray-200 dark:border-slate-800 mt-6">
                        <button type="button" wire:click="resetForm" class="px-6 py-2 text-xs font-bold text-gray-500 dark:text-gray-400 hover:text-white hover:bg-red-500 border border-gray-255 dark:border-slate-750 rounded-lg cursor-pointer transition-all font-sans font-bold">ক্লিয়ার</button>
                        <button type="submit" class="px-6 py-2 bg-[#009E74] hover:bg-[#008763] text-white text-xs font-bold rounded-lg cursor-pointer transition-all shadow-md active:scale-95 font-sans flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>সেভ করুন</button>
                        <button type="button" wire:click="save" class="px-6 py-2 bg-[#009E74] hover:bg-[#008763] text-white text-xs font-bold rounded-lg cursor-pointer transition-all shadow-md active:scale-95 font-sans flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>সেভ + প্রিন্ট</button>
                    </div>
                </form>
            </div>
        </div>
@endif

    <!-- ====================== PRINT AREA ====================== -->
    <template x-teleport="body">
        <div id="today-challan-print-area" class="hidden">
            @php
                $printTotal = [
                    'quantity'      => $printChallans->sum(fn($c) => $c->items->sum('quantity')),
                    'value'         => $printChallans->sum('value'),
                    'discount'      => $printChallans->sum('discount'),
                    'transport'     => $printChallans->sum('transport_rent'),
                    'grand'         => $printChallans->sum('grand_total'),
                    'cash'          => $printChallans->sum('cash'),
                    'due'           => $printChallans->sum('due'),
                ];
                $rangeLabel = \Carbon\Carbon::parse($date ?: now())->format('d-m-Y');
            @endphp

            <div class="print-page">
                <!-- Company Header -->
                <div class="print-header">
                    <h1 class="print-company">ডেমো ব্রিকস</h1>
                    <p class="print-sub">হিলালিপাড়া, কাটাবাড়ি, গোবিন্দগঞ্জ</p>
                    <p class="print-sub">০১৯০১৩৪৯৯০১, ০১৯০১৩৪৯৯০৬</p>
                    <p class="print-sub">প্রোপাইটরঃ মোঃ মানিক মিয়া</p>
                </div>

                <!-- Report Meta Row -->
                <div class="print-meta-row">
                    <span class="print-meta-date">তারিখ: {{ $rangeLabel }} | ২৫-২৬</span>
                    <span class="print-meta-title">দৈনিক চালান তালিকা</span>
                    <span class="print-meta-total">মোট চালান: {{ $printChallans->count() }}</span>
                </div>

                <!-- Main Table -->
                <table class="print-table">
                    <thead>
                        <tr class="print-thead-row">
                            <th class="pt-cell text-center" style="width:28px">চালান</th>
                            <th class="pt-cell" style="width:90px">কাস্টমার</th>
                            <th class="pt-cell" style="width:60px">শ্রেণি</th>
                            <th class="pt-cell text-right" style="width:42px">পরিমাণ</th>
                            <th class="pt-cell text-right" style="width:36px">দর</th>
                            <th class="pt-cell text-right" style="width:56px">মূল্য</th>
                            <th class="pt-cell text-right" style="width:38px">ভাড়া</th>
                            <th class="pt-cell text-right" style="width:38px">ছাড়</th>
                            <th class="pt-cell text-right" style="width:56px">সর্বমোট</th>
                            <th class="pt-cell text-right" style="width:56px">নগদ</th>
                            <th class="pt-cell text-right" style="width:36px">বাকি</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($printChallans as $i => $challan)
                            <tr class="print-row {{ $loop->even ? 'print-row-even' : '' }}">
                                <td class="pt-cell text-center font-bold">{{ $loop->iteration }}</td>
                                <td class="pt-cell">
                                    <span class="block font-semibold">{{ $challan->customer_name }}</span>
                                    @if($challan->customer_address)
                                        <span class="print-small">{{ $challan->customer_address }}</span>
                                    @endif
                                </td>
                                <td class="pt-cell">
                                    @foreach($challan->items as $item)
                                        <span class="block print-cat">{{ $item->category_name }}</span>
                                    @endforeach
                                </td>
                                <td class="pt-cell text-right font-semibold">
                                    @foreach($challan->items as $item)
                                        <span class="block">{{ number_format($item->quantity) }}</span>
                                    @endforeach
                                </td>
                                <td class="pt-cell text-right">
                                    @foreach($challan->items as $item)
                                        <span class="block">{{ number_format($item->rate,2) }}</span>
                                    @endforeach
                                </td>
                                <td class="pt-cell text-right">৳{{ number_format($challan->value,2) }}</td>
                                <td class="pt-cell text-right">৳{{ number_format($challan->transport_rent,2) }}</td>
                                <td class="pt-cell text-right print-amber">৳{{ number_format($challan->discount,2) }}</td>
                                <td class="pt-cell text-right font-bold">৳{{ number_format($challan->grand_total,2) }}</td>
                                <td class="pt-cell text-right font-semibold">৳{{ number_format($challan->cash,2) }}</td>
                                <td class="pt-cell text-right print-red">৳{{ number_format($challan->due,2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="11" style="text-align:center;padding:8px;font-size:9pt;">কোনো ডেটা নেই</td></tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="print-tfoot-row">
                            <td class="pt-cell" colspan="3">সর্বমোট:</td>
                            <td class="pt-cell text-right font-bold">{{ number_format($printTotal['quantity']) }}</td>
                            <td class="pt-cell">—</td>
                            <td class="pt-cell text-right font-bold">৳{{ number_format($printTotal['value'],2) }}</td>
                            <td class="pt-cell text-right font-bold">৳{{ number_format($printTotal['transport'],2) }}</td>
                            <td class="pt-cell text-right font-bold print-amber">৳{{ number_format($printTotal['discount'],2) }}</td>
                            <td class="pt-cell text-right font-bold">৳{{ number_format($printTotal['grand'],2) }}</td>
                            <td class="pt-cell text-right font-bold print-green">৳{{ number_format($printTotal['cash'],2) }}</td>
                            <td class="pt-cell text-right font-bold print-red">৳{{ number_format($printTotal['due'],2) }}</td>
                        </tr>
                    </tfoot>
                </table>

                <!-- Signature Area -->
                <div class="print-signature-row">
                    <div class="print-signature-box">
                        <div class="print-sig-line"></div>
                        <p class="print-sig-label">ম্যানেজার</p>
                    </div>
                    <div class="print-signature-box">
                        <div class="print-sig-line"></div>
                        <p class="print-sig-label">মালিক</p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="print-footer">
                    প্রিন্ট তারিখ: {{ now()->format('d-m-Y h:i A') }} | Software by: Payratech.com
                </div>
            </div>
        </div>
    </template>

    <!-- ====================== NEW DELIVERY MODAL ====================== -->
    @if($showDeliveryModal)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex justify-center items-center p-4"
         wire:click.self="$set('showDeliveryModal', false)">
        <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-3xl w-full border border-gray-255 dark:border-slate-700 shadow-2xl p-6 relative max-h-[92vh] overflow-y-auto challan-modal-scroll animate-in fade-in zoom-in-95 duration-150">
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-gray-150 dark:border-slate-800 pb-4 mb-5">
                <h3 class="font-bold text-base font-sans text-gray-800 dark:text-white flex items-center gap-2">
                    নতুন ডেলিভারি 🚚
                </h3>
                <button type="button" wire:click="$set('showDeliveryModal', false)" class="p-1.5 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-xl cursor-pointer text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Form -->
            <form wire:submit.prevent="saveDelivery" class="space-y-4 text-xs font-semibold text-gray-600 dark:text-slate-400">
                <!-- Delivery Info Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block mb-1.5">ডেলিভারি নং</label>
                        <input type="text" wire:model="deliveryNo" class="w-full py-2 px-3 bg-gray-55 border border-gray-255 dark:border-slate-700 rounded-xl focus:outline-none dark:bg-slate-950 dark:text-white text-gray-800">
                    </div>
                    <div>
                        <label class="block mb-1.5">চালান নং</label>
                        <input type="text" wire:model="challan_no" disabled class="w-full py-2 px-3 bg-gray-100 border border-gray-255 dark:border-slate-700 rounded-xl text-gray-500 dark:bg-slate-900/50">
                    </div>
                    <div>
                        <label class="block mb-1.5">ডেলিভারি তারিখ</label>
                        <input type="text"
                               data-flatpickr
                               data-wire-prop="deliveryDate"
                               data-default="{{ $deliveryDate }}"
                               wire:model="deliveryDate"
                               readonly
                               class="w-full py-2 px-3 bg-gray-55 border border-gray-255 dark:border-slate-700 rounded-xl focus:outline-none dark:bg-slate-950 dark:text-white text-gray-800 cursor-pointer">
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
                        <input type="text"
                               data-flatpickr
                               data-wire-prop="nextDeliveryDate"
                               data-default="{{ $nextDeliveryDate }}"
                               wire:model="nextDeliveryDate"
                               readonly
                               class="w-full py-2 px-3 bg-gray-55 border border-gray-255 dark:border-slate-700 rounded-xl focus:outline-none dark:bg-slate-950 dark:text-white text-gray-800 cursor-pointer">
                    </div>
                    <div>
                        <label class="block mb-1.5">নোট</label>
                        <textarea wire:model="deliveryNotes" rows="1" class="w-full py-2 px-3 bg-gray-55 border border-gray-255 dark:border-slate-700 rounded-xl focus:outline-none dark:bg-slate-950 dark:text-white text-gray-800"></textarea>
                    </div>
                </div>

                <!-- Product specs grid -->
                <div class="bg-gray-50/50 dark:bg-slate-950/30 border border-gray-150 dark:border-slate-800 rounded-2xl p-4 space-y-3">
                    <div class="grid grid-cols-4 gap-3 text-center font-bold text-[11px] text-gray-500">
                        <div>শ্রেণি</div>
                        <div>ডেলিভারি পাবে</div>
                        <div>আজকের ডেলিভারি</div>
                        <div>ডেলিভারি বাকি</div>
                    </div>
                    <div class="grid grid-cols-4 gap-3 items-center">
                        <div>
                            <select disabled class="w-full py-2 px-3 bg-gray-100 border border-gray-205 dark:border-slate-800 rounded-xl text-gray-500 dark:bg-slate-900/50">
                                <option>{{ $deliveryItemCategory }}</option>
                            </select>
                        </div>
                        <div>
                            <input type="text" value="{{ number_format((int)$deliveryTotalQty) }}" disabled class="w-full py-2 px-3 bg-gray-100 border border-gray-205 dark:border-slate-800 rounded-xl text-center text-gray-500 dark:bg-slate-900/50 font-sans">
                        </div>
                        <div>
                            <input type="number" wire:model.live="todayDeliveryQty" class="w-full py-2 px-3 bg-white dark:bg-slate-950 border border-gray-300 dark:border-slate-700 rounded-xl text-center text-gray-800 dark:text-white font-bold font-sans focus:ring-2 focus:ring-emerald-500/20">
                        </div>
                        <div>
                            <input type="text" value="{{ number_format(max(0, (int)$deliveryTotalQty - (int)$todayDeliveryQty)) }}" disabled class="w-full py-2 px-3 bg-gray-100 border border-gray-205 dark:border-slate-800 rounded-xl text-center text-gray-500 dark:bg-slate-900/50 font-sans">
                        </div>
                    </div>
                </div>

                <!-- Driver details & rent -->
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
                            <button type="button" @click="$wire.smsToCustomer = !$wire.smsToCustomer" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none" :class="$wire.smsToCustomer ? 'bg-emerald-600' : 'bg-gray-200 dark:bg-slate-800'">
                                <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out" :class="$wire.smsToCustomer ? 'translate-x-5' : 'translate-x-0'"></span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Footer buttons -->
                <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-gray-150 dark:border-slate-800 mt-4">
                    <button type="button" wire:click="$set('showDeliveryModal', false)" class="px-5 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/20 border border-gray-200 dark:border-slate-700 rounded-xl cursor-pointer transition-all">ক্লিয়ার</button>
                    <button type="submit" class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl cursor-pointer transition-all shadow-md flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>সেভ করুন</button>
                    <button type="button" wire:click="saveDelivery" class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl cursor-pointer transition-all shadow-md">সেভ + নতুন ডেলিভারি</button>
                    <button type="button" onclick="window.print()" class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl cursor-pointer transition-all shadow-md flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>সেভ + প্রিন্ট ডেলিভারি</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- ====================== CHALLAN DETAILS MODAL ====================== -->
    @if($showChallanDetailsModal && $detailsChallan)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex justify-center items-center p-4"
         wire:click.self="$set('showChallanDetailsModal', false)">
        <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-4xl w-full border border-gray-255 dark:border-slate-700 shadow-2xl p-7 relative max-h-[92vh] overflow-y-auto challan-modal-scroll animate-in fade-in zoom-in-95 duration-150">
            <!-- Close Button -->
            <button type="button" wire:click="$set('showChallanDetailsModal', false)" class="absolute top-4 right-4 p-1.5 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-xl cursor-pointer text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <!-- Modal Content -->
            <div class="space-y-5 text-gray-800 dark:text-slate-200">
                <!-- Header Title -->
                <h3 class="font-bold text-sm font-sans text-emerald-700 dark:text-emerald-400 border-b border-gray-100 dark:border-slate-800 pb-2">
                    চালান এর বিস্তারিত
                </h3>

                <!-- Top Meta Section -->
                <div class="flex flex-col sm:flex-row justify-between gap-4">
                    <div>
                        <h4 class="font-extrabold text-emerald-600 dark:text-emerald-400 text-lg">চালান নং: {{ $detailsChallan->challan_no }}</h4>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 font-sans">চালান তৈরি করেছেন: Demo</p>
                    </div>
                    <div class="sm:text-right">
                        <h4 class="font-extrabold text-gray-800 dark:text-white text-base">ডেমো ব্রিকস</h4>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500">হিলালিপাড়া, কাটাবাড়ি, গোবিন্দগঞ্জ</p>
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-2 font-semibold text-xs text-gray-600 dark:text-slate-400">
                    <!-- Column 1 -->
                    <div class="bg-gray-50/50 dark:bg-slate-950/20 border border-gray-150 dark:border-slate-800 rounded-2xl p-4 space-y-2">
                        <div class="flex justify-between border-b border-gray-100 dark:border-slate-800 pb-1.5"><span>নাম</span> <span class="font-bold text-gray-800 dark:text-white">{{ $detailsChallan->customer_name }}</span></div>
                        <div class="flex justify-between border-b border-gray-100 dark:border-slate-800 pb-1.5"><span>ঠিকানা</span> <span class="text-gray-700 dark:text-slate-300">{{ $detailsChallan->customer_address }}</span></div>
                        <div class="flex justify-between"><span>মোবাইল</span> <span class="font-sans font-bold text-gray-800 dark:text-white">{{ $detailsChallan->customer_phone }}</span></div>
                    </div>
                    <!-- Column 2 -->
                    <div class="bg-gray-55/50 dark:bg-slate-950/20 border border-gray-150 dark:border-slate-800 rounded-2xl p-4 space-y-2">
                        <div class="flex justify-between border-b border-gray-100 dark:border-slate-800 pb-1.5"><span>কাস্টমার আইডি</span> <span class="font-sans font-bold text-gray-800 dark:text-white">{{ $detailsChallan->id }}</span></div>
                        <div class="flex justify-between border-b border-gray-100 dark:border-slate-800 pb-1.5"><span>ধরন</span> <span class="text-gray-800 dark:text-white">রেগুলার চালান</span></div>
                        <div class="flex justify-between"><span>ডেলিভারি তারিখ</span> <span class="font-sans text-gray-500">—</span></div>
                    </div>
                    <!-- Column 3 -->
                    <div class="bg-gray-55/50 dark:bg-slate-950/20 border border-gray-150 dark:border-slate-800 rounded-2xl p-4 space-y-2">
                        <div class="flex justify-between border-b border-gray-100 dark:border-slate-800 pb-1.5"><span>তারিখ</span> <span class="font-sans text-gray-800 dark:text-white">{{ $detailsChallan->date ? $detailsChallan->date->format('d-m-Y') : '' }}</span></div>
                        <div class="flex justify-between border-b border-gray-100 dark:border-slate-800 pb-1.5"><span>সময়</span> <span class="text-gray-700 dark:text-slate-300">বিকেল ৫:০৪</span></div>
                        <div class="flex justify-between"><span>সিজন</span> <span class="font-sans font-bold text-gray-800 dark:text-white">২০২৬</span></div>
                    </div>
                </div>

                <!-- Notes -->
                @if($detailsChallan->notes)
                <div class="bg-red-50/50 dark:bg-red-950/10 border border-red-100 dark:border-red-900/30 rounded-2xl p-3.5 text-xs">
                    <span class="font-bold text-red-600 dark:text-red-400 block mb-1">নোট</span>
                    <p class="text-red-700 dark:text-red-300 font-sans">{{ $detailsChallan->notes }}</p>
                </div>
                @endif

                <!-- Items Table -->
                <div class="border border-gray-150 dark:border-slate-800 rounded-2xl overflow-hidden text-xs">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-slate-950 border-b border-gray-150 dark:border-slate-800 text-[10px] uppercase font-bold text-gray-500">
                                <th class="px-4 py-3">শ্রেণি</th>
                                <th class="px-4 py-3 text-right">পরিমাণ</th>
                                <th class="px-4 py-3 text-right text-amber-600">ডেলিভারি</th>
                                <th class="px-4 py-3 text-right">দর</th>
                                <th class="px-4 py-3 text-right">মূল্য</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-800 font-semibold text-gray-700 dark:text-slate-300">
                            @foreach($detailsChallan->items as $item)
                            <tr>
                                <td class="px-4 py-3.5 font-bold text-emerald-700 dark:text-emerald-400">{{ $item->category_name }}</td>
                                <td class="px-4 py-3.5 text-right font-sans">{{ number_format($item->quantity) }}</td>
                                <td class="px-4 py-3.5 text-right font-sans text-amber-600 font-bold">{{ number_format($item->quantity) }}</td>
                                <td class="px-4 py-3.5 text-right font-sans">৳{{ number_format($item->rate, 2) }}</td>
                                <td class="px-4 py-3.5 text-right font-sans font-bold">৳{{ number_format($item->amount, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Bottom Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                    <!-- Left card: Payment state -->
                    <div class="border border-emerald-500/20 dark:border-emerald-500/10 rounded-3xl p-6 flex flex-col items-center justify-center bg-emerald-50/10 dark:bg-emerald-950/5">
                        <span class="text-4xl sm:text-5xl font-black text-emerald-600 dark:text-emerald-400 tracking-wider">
                            পরিশোধ
                        </span>
                    </div>

                    <!-- Right card: Stats list -->
                    <div class="grid grid-cols-2 gap-3 text-xs font-bold font-sans">
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-950 border border-gray-100 dark:border-slate-800 rounded-2xl">
                            <span class="text-gray-500">মোট মূল্য</span>
                            <span class="text-gray-800 dark:text-white">৳{{ number_format($detailsChallan->value) }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-950 border border-gray-100 dark:border-slate-800 rounded-2xl">
                            <span class="text-orange-500">ছাড়</span>
                            <span class="text-orange-600 dark:text-orange-400">৳{{ number_format($detailsChallan->discount) }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-950 border border-gray-100 dark:border-slate-800 rounded-2xl">
                            <span class="text-blue-500">গাড়ি ভাড়া</span>
                            <span class="text-blue-600 dark:text-blue-400">৳{{ number_format($detailsChallan->transport_rent) }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-950 border border-gray-100 dark:border-slate-800 rounded-2xl">
                            <span class="text-purple-500">সর্বমোট</span>
                            <span class="text-purple-600 dark:text-purple-400">৳{{ number_format($detailsChallan->grand_total) }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100/50 dark:border-emerald-900/30 rounded-2xl col-span-2">
                            <span class="text-emerald-700 dark:text-emerald-400">জমা</span>
                            <span class="text-emerald-700 dark:text-emerald-400 text-sm">৳{{ number_format($detailsChallan->cash) }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-950/15 border border-red-100 dark:border-red-950/30 rounded-2xl col-span-2">
                            <span class="text-red-600 dark:text-red-400">বাকি</span>
                            <span class="text-red-600 dark:text-red-400 text-sm">৳{{ number_format($detailsChallan->due) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Footer Brand Note -->
                <div class="text-center text-[9px] text-gray-400 dark:text-gray-500 font-sans tracking-wide pt-4 border-t border-gray-100 dark:border-slate-800">
                    [ PAYRA TECH ] a sister concern of [ ORIOSIS LTD ]
                </div>
            </div>
        </div>
    </div>
    @endif

    <style>
        .challan-modal-scroll::-webkit-scrollbar { width: 5px; }
        .challan-modal-scroll::-webkit-scrollbar-track { background: transparent; }
        .challan-modal-scroll::-webkit-scrollbar-thumb { background: #10b981; border-radius: 999px; }
        .challan-modal-scroll::-webkit-scrollbar-thumb:hover { background: #059669; }

        /* Preview / Print stylesheet classes */
        .print-page {
            width: 100%;
            background: #fff;
            color: #111;
            font-family: 'Noto Serif Bengali', 'SolaimanLipi', 'Kalpurush', Arial, sans-serif;
            font-size: 8.5pt;
        }
        .print-header {
            text-align: center;
            margin-bottom: 8pt;
            border-bottom: 1.5pt solid #111;
            padding-bottom: 6pt;
        }
        .print-company {
            font-size: 20pt;
            font-weight: 900;
            margin: 0 0 2pt;
            letter-spacing: 0.5pt;
            color: #000;
        }
        .print-sub {
            font-size: 9.5pt;
            font-weight: 700;
            margin: 1.5pt 0;
            color: #222;
        }
        .print-meta-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 8pt 0;
            font-size: 9pt;
        }
        .print-meta-date { font-weight: 700; }
        .print-meta-title {
            font-size: 12pt;
            font-weight: 900;
            background-color: #e5e7eb;
            border-radius: 9999px;
            padding: 3pt 18pt;
            color: #000;
        }
        .print-meta-total { font-weight: 700; }
        .print-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12pt;
            table-layout: fixed;
        }
        .print-thead-row th {
            background: #f3f4f6;
            color: #000;
            font-size: 8.5pt;
            font-weight: 700;
            border: 0.5pt solid #9ca3af;
        }
        .pt-cell {
            padding: 4.5pt 4pt;
            border: 0.5pt solid #9ca3af;
            font-size: 8.5pt;
            vertical-align: top;
            color: #000;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: 700; }
        .font-semibold { font-weight: 600; }
        .block { display: block; }
        .print-row-even { background: #f9fafb; }
        .print-cat { color: #000; font-weight: 600; }
        .print-small { font-size: 7.5pt; color: #4b5563; }
        .print-amber { color: #000; }
        .print-green { color: #000; }
        .print-red { color: #000; font-weight: 700; }
        .print-tfoot-row td {
            background: #e8f5e9;
            font-weight: 700;
            border-top: 1.5pt solid #111;
            font-size: 8.5pt;
            color: #000;
        }
        .print-signature-row {
            display: flex;
            justify-content: space-between;
            margin-top: 36pt;
            padding: 0 16pt;
        }
        .print-signature-box {
            width: 120pt;
            text-align: center;
        }
        .print-sig-line {
            border-top: 1.2pt solid #111;
            margin-bottom: 4pt;
        }
        .print-sig-label {
            font-size: 9pt;
            font-weight: 700;
            color: #000;
        }
        .print-footer {
            text-align: center;
            font-size: 7.5pt;
            color: #6b7280;
            border-top: 0.5pt solid #d1d5db;
            margin-top: 12pt;
            padding-top: 6pt;
        }

        /* ========== PRINT RULES ========== */
        @media print {
            body * { visibility: hidden !important; }
            #today-challan-print-area,
            #today-challan-print-area * { visibility: visible !important; }
            #today-challan-print-area {
                display: block !important;
                position: fixed !important;
                left: 0 !important;
                top: 0 !important;
                width: 100% !important;
                background: white !important;
                padding: 12mm 10mm 12mm 12mm !important;
                margin: 0 !important;
                z-index: 99999 !important;
            }
            .print-table thead { display: table-header-group !important; }
            .print-table tfoot { display: table-footer-group !important; }
            .print-table tbody { display: table-row-group !important; }
            .print-table tbody tr { page-break-inside: avoid !important; }
            @page {
                size: A4 portrait;
                margin: 0;
            }
        }
    </style>

</div>
