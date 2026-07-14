<div class="min-h-screen bg-gray-50 dark:bg-slate-950 transition-colors duration-300">

    <!-- Page Header Bar -->
    <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 px-4 sm:px-6 py-3 rounded-lg flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 transition-colors duration-300">
        <div>
            <h2 class="font-bold text-gray-800 dark:text-white text-sm leading-tight">অগ্রিম চালান</h2>
            <p class="text-[10px] text-gray-400 dark:text-gray-500 font-sans mt-0.5 font-semibold">অগ্রিম প্রদেয় ও বকেয়া চালানের তালিকা</p>
        </div>

        <!-- Top Actions -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3">
            <div class="relative">
                <input type="text" wire:model.live="search"
                       placeholder="সার্চ করুন..."
                       class="pl-4 pr-4 py-2 text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all w-full sm:w-52 font-sans font-semibold">
            </div>
            <!-- Report Button -->
            <button type="button" wire:click="openReport"
                    class="px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl cursor-pointer transition-all font-sans flex items-center gap-1.5 shadow-sm active:scale-95">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                রিপোর্ট
            </button>
            <button type="button" onclick="window.print()"
                    class="px-3 py-2 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-200 text-xs font-semibold rounded-xl cursor-pointer transition-all font-sans border border-gray-200 dark:border-slate-700 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                প্রিন্ট
            </button>
            <button type="button" wire:click="openAddModal"
                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl cursor-pointer transition-all shadow-md active:scale-95 font-sans">
                নতুন অগ্রিম চালান
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
            <div class="flex flex-wrap items-center gap-4 px-5 py-3.5 border-b border-gray-100 dark:border-slate-800 bg-amber-50/60 dark:bg-amber-950/10">
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
                                        <span class="block">{{ number_format($item->quantity) }}</span>
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
                                <td class="px-3 py-3.5 text-center relative" x-data="{ openDropdown: false, buttonRect: null }">
                                    <button type="button" @click="openDropdown = !openDropdown; buttonRect = $el.getBoundingClientRect()" class="p-1.5 text-gray-500 hover:text-emerald-600 dark:hover:text-emerald-400 focus:outline-none transition-all cursor-pointer">
                                        <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z"/>
                                        </svg>
                                    </button>
                                    <template x-teleport="body">
                                        <div x-show="openDropdown" @click.away="openDropdown = false" x-transition
                                             class="absolute w-48 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-2xl shadow-2xl p-1.5 z-[9999] text-left text-xs flex flex-col gap-0.5"
                                             :style="'top: ' + (buttonRect ? (buttonRect.bottom + window.scrollY + 4) : 0) + 'px; left: ' + (buttonRect ? (buttonRect.left + window.scrollX - 140) : 0) + 'px;'"
                                             x-cloak>
                                            <button type="button" wire:click="edit({{ $challan->id }})" @click="openDropdown = false" class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 text-gray-700 dark:text-slate-200 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-xl cursor-pointer flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                আপডেট করুন
                                            </button>
                                            <button type="button" @click="openDropdown = false" class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 text-gray-700 dark:text-slate-200 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-xl cursor-pointer flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                                                প্রিন্ট চালান
                                            </button>
                                            <button type="button" @click="openDropdown = false" class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 text-gray-700 dark:text-slate-200 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-xl cursor-pointer flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                                                ডেলিভারি দিন
                                            </button>
                                            <button type="button" @click="openDropdown = false" class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 text-gray-700 dark:text-slate-200 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-xl cursor-pointer flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                চালান বিস্তারিত
                                            </button>
                                            <button type="button" @click="openDropdown = false" class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 text-gray-700 dark:text-slate-200 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-xl cursor-pointer flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                প্রোফাইল এ যান
                                            </button>
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
                                        <p class="text-xs text-gray-400 dark:text-gray-500 italic font-sans">কোনো অগ্রিম চালান নেই।</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($challans->count() > 0)
                    <tfoot>
                        <tr class="bg-emerald-600/10 dark:bg-emerald-950/30 border-t-2 border-emerald-200 dark:border-emerald-900/50 text-xs font-bold font-sans">
                            <td colspan="4" class="px-3 py-3 text-emerald-700 dark:text-emerald-400 border-r border-gray-200 dark:border-slate-800 last:border-r-0">মোট চালান {{ $challans->count() }} টি</td>
                            <td class="px-3 py-3 text-right text-emerald-700 dark:text-emerald-400 border-r border-gray-200 dark:border-slate-800 last:border-r-0">{{ number_format($challans->sum(fn($c) => $c->items->sum('quantity'))) }}</td>
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
                <div class="bg-emerald-600 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-white font-bold text-base font-sans flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        বিক্রি রিপোর্ট (অগ্রিম)
                    </h3>
                    <button type="button" @click="open = false; $wire.closeReport()" class="text-white/80 hover:text-white transition-colors cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="p-5">
                    @php $report = $this->reportData; @endphp
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
    <template x-teleport="body">
        <div x-data="{ open: @entangle('showModal') }"
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
            <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-2xl w-full border border-gray-200 dark:border-slate-700 shadow-2xl p-6 relative max-h-[92vh] overflow-y-auto challan-modal-scroll"
                 @scroll.passive="$dispatch('close-cat-dropdowns')"
                 x-show="open"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4">

                 <button type="button" @click="open = false; $wire.closeModal()"
                         class="absolute top-4 right-4 text-gray-400 hover:text-red-500 dark:hover:text-red-400 transition-colors cursor-pointer">
                     <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                     </svg>
                 </button>

                 <h3 class="text-base font-bold text-gray-800 dark:text-white mb-5 border-b border-emerald-100 dark:border-emerald-900/40 pb-3 font-sans flex items-center gap-2">
                     <span class="w-1.5 h-5 bg-amber-500 rounded-full inline-block"></span>
                     {{ $editingId ? 'অগ্রিম চালান আপডেট' : 'নতুন অগ্রিম চালান' }}
                 </h3>

                 <form wire:submit.prevent="save" class="space-y-4">
                     <div class="flex flex-wrap items-center gap-3 mb-6">
                         <button type="button" @click="$wire.set('customer_type', 'new')"
                                 class="px-4 py-2 text-xs font-bold rounded-xl transition-all cursor-pointer font-sans"
                                 :class="$wire.customer_type === 'new' ? 'bg-emerald-600 text-white shadow-sm' : 'border border-emerald-500 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-950/20'">
                             নতুন কাস্টমার
                         </button>
                         <button type="button" @click="$wire.set('customer_type', 'old')"
                                 class="px-4 py-2 text-xs font-bold rounded-xl transition-all cursor-pointer font-sans"
                                 :class="$wire.customer_type === 'old' ? 'bg-orange-500 text-white shadow-sm' : 'border border-orange-500 text-orange-500 hover:bg-orange-50 dark:hover:bg-orange-950/20'">
                             পুরাতন কাস্টমার
                         </button>
                         <div class="ml-auto flex items-center gap-2">
                             <div class="flex items-center gap-1.5 bg-gray-50 dark:bg-slate-950 border border-emerald-200 dark:border-emerald-800/50 rounded-xl px-3 py-1.5">
                                 <span class="text-[10px] font-bold text-gray-500 dark:text-gray-400 font-sans">চালান নম্বর:</span>
                                 <input type="text" wire:model="challan_no" class="w-12 bg-transparent text-xs font-bold text-gray-800 dark:text-white focus:outline-none border-none p-0">
                             </div>
                             <div class="relative flex items-center">
                                 <input type="text"
                                        data-flatpickr
                                        data-wire-prop="date"
                                        data-default="{{ $date }}"
                                        wire:model="date"
                                        placeholder="তারিখ"
                                        readonly
                                        class="pl-3 pr-8 py-1.5 text-xs rounded-xl border border-emerald-200 dark:border-emerald-800/50 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all w-36 font-sans font-semibold cursor-pointer">
                                 <span class="absolute right-2 top-1.5 text-emerald-500 pointer-events-none">
                                     <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                 </span>
                             </div>
                         </div>
                     </div>

                     <div class="grid grid-cols-1 sm:grid-cols-3 gap-4" x-show="$wire.customer_type === 'new'">
                         <div>
                             <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">ফোন নম্বর - ০</label>
                             <div class="relative">
                                 <input type="text" wire:model="customer_phone" placeholder="ফোন নম্বর" maxlength="11"
                                        class="w-full py-2.5 px-3 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all font-semibold">
                                 <span class="absolute right-2 top-3 text-[10px] text-gray-400 font-sans" x-text="($wire.customer_phone || '').length + '/11'"></span>
                             </div>
                         </div>
                         <div>
                             <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">কাস্টমারের নাম</label>
                             <input type="text" wire:model="customer_name" placeholder="কাস্টমারের নাম" class="w-full py-2.5 px-3 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all font-semibold">
                         </div>
                         <div>
                             <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">কাস্টমারের ঠিকানা</label>
                             <input type="text" wire:model="customer_address" placeholder="কাস্টমারের ঠিকানা" class="w-full py-2.5 px-3 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all font-semibold">
                         </div>
                     </div>

                     <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" x-show="$wire.customer_type === 'old'" x-cloak>
                          <div>
                              <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">পুরাতন খতিয়ান গ্রাহক</label>
                              <div class="relative" x-data="{ openLedger: false, triggerRect: null, searchLedger: '' }">
                                  <button type="button" @click="openLedger = !openLedger; triggerRect = $el.getBoundingClientRect()"
                                          class="w-full flex items-center justify-between py-2.5 px-3 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-xs font-semibold text-gray-800 dark:text-white focus:outline-none focus:border-primary cursor-pointer text-left">
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
                                                     class="w-full py-1.5 px-3 text-xs rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-gray-800 dark:text-white focus:outline-none focus:border-primary font-sans">
                                          </div>
                                          <div class="max-h-48 overflow-y-auto py-1">
                                              <button type="button" @click="$wire.set('ledger_id', ''); $wire.updatedLedgerId(''); openLedger = false; searchLedger = ''"
                                                      class="w-full text-left px-3 py-2 hover:bg-emerald-50/30 dark:hover:bg-emerald-950/20 text-xs font-semibold text-gray-400 dark:text-gray-500 font-sans cursor-pointer block">
                                                  গ্রাহক নির্বাচন করুন...
                                              </button>
                                              @foreach($ledgers as $ledger)
                                                  <button type="button" 
                                                          x-show="searchLedger === '' || '{{ $ledger->name }}'.toLowerCase().includes(searchLedger.toLowerCase())"
                                                          @click="$wire.set('ledger_id', '{{ $ledger->id }}'); $wire.updatedLedgerId('{{ $ledger->id }}'); openLedger = false; searchLedger = ''"
                                                          class="w-full text-left px-3 py-2 hover:bg-emerald-50/30 dark:hover:bg-emerald-950/20 text-xs font-semibold text-gray-800 dark:text-white hover:text-primary dark:hover:text-secondary transition-all font-sans cursor-pointer block">
                                                      {{ $ledger->name }}
                                                  </button>
                                              @endforeach
                                          </div>
                                      </div>
                                  </template>
                              </div>
                          </div>
                          <div>
                              <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">গ্রাহক ফোন নম্বর</label>
                              <input type="text" wire:model="customer_phone" placeholder="ফোন নম্বর" class="w-full py-2.5 px-3 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all font-semibold">
                          </div>
                      </div>

                     <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                         <div>
                             <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">ডেলিভারি তারিখ</label>
                             <div class="relative flex items-center">
                                 <input type="text"
                                        data-flatpickr
                                        data-wire-prop="date"
                                        data-default="{{ $date }}"
                                        wire:model="date"
                                        placeholder="তারিখ নির্বাচন করুন"
                                        readonly
                                        class="w-full py-2.5 pl-3 pr-10 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all font-sans font-semibold cursor-pointer">
                                 <span class="absolute right-3 top-2.5 text-emerald-500 pointer-events-none">
                                     <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                 </span>
                             </div>
                         </div>
                         <div>
                             <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">নোট</label>
                             <input type="text" wire:model="notes" placeholder="নোট..." class="w-full py-2.5 px-3 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all font-semibold">
                         </div>
                     </div>

                     <!-- Items Table -->
                     <div class="mt-6 border border-gray-200 dark:border-slate-700 rounded-2xl overflow-visible shadow-sm">
                         <table class="w-full text-left border-collapse">
                             <thead>
                                 <tr class="bg-gray-50 dark:bg-slate-950 border-b border-gray-200 dark:border-slate-700 text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase font-sans">
                                     <th class="px-3 py-2.5 text-center w-12 border-r border-gray-200 dark:border-slate-700">#</th>
                                     <th class="px-3 py-2.5 border-r border-gray-200 dark:border-slate-700">শ্রেণি</th>
                                     <th class="px-3 py-2.5 text-right border-r border-gray-200 dark:border-slate-700 w-28">রেট</th>
                                     <th class="px-3 py-2.5 text-right border-r border-gray-200 dark:border-slate-700 w-28">পরিমাণ</th>
                                     <th class="px-3 py-2.5 text-right border-r border-gray-200 dark:border-slate-700 w-32">মূল্য</th>
                                     <th class="px-3 py-2.5 text-center w-12">মুছুন</th>
                                 </tr>
                             </thead>
                             <tbody class="divide-y divide-gray-100 dark:divide-slate-800 font-sans">
                                 @foreach($items as $index => $item)
                                     <tr class="text-xs">
                                         <td class="px-3 py-2.5 text-center border-r border-gray-100 dark:border-slate-800 font-bold">
                                             @if($loop->first)
                                                 <button type="button" wire:click="addItem" class="w-6 h-6 rounded-lg bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 font-bold flex items-center justify-center cursor-pointer transition-all border border-emerald-200">+</button>
                                             @else
                                                 {{ $loop->iteration }}
                                             @endif
                                         </td>
                                         <td class="px-3 py-2.5 border-r border-gray-100 dark:border-slate-800 relative" x-data="{ openCat: false, triggerRect: null }">
                                            <button type="button" @click="openCat = !openCat; triggerRect = $el.getBoundingClientRect()"
                                                     class="w-full flex items-center justify-between py-1 px-2 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-xs font-semibold text-gray-800 dark:text-white focus:outline-none focus:border-primary cursor-pointer">
                                                 <span x-text="$wire.items[{{ $index }}]['category_name'] || 'শ্রেণি নির্বাচন করুন...'"></span>
                                                 <svg class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': openCat }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                             </button>
                                             <template x-teleport="body">
                                                 <div x-show="openCat" @click.away="openCat = false" @close-cat-dropdowns.window="openCat = false" x-transition
                                                      class="absolute w-64 bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-gray-200 dark:border-slate-700 z-[9999] overflow-hidden text-left"
                                                      :style="'top: ' + (triggerRect ? (triggerRect.bottom + window.scrollY + 2) : 0) + 'px; left: ' + (triggerRect ? (triggerRect.left + window.scrollX) : 0) + 'px;'"
                                                      x-cloak>
                                                     <div class="p-2 border-b border-gray-100 dark:border-slate-800 flex gap-1.5 bg-gray-50 dark:bg-slate-950">
                                                         <input type="text" wire:model="newCategoryInput" placeholder="ফিল্টার বা নতুন শ্রেণি..."
                                                                class="flex-1 py-1 px-2 text-[10px] rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-gray-800 dark:text-white focus:outline-none focus:border-primary font-sans"
                                                                @keydown.enter.prevent="$wire.addCategoryOption()">
                                                         <button type="button" wire:click="addCategoryOption" class="px-2 py-1 bg-primary hover:bg-primary-dark text-white rounded-lg text-xs font-bold transition-all cursor-pointer">+</button>
                                                     </div>
                                                     <div class="max-h-40 overflow-y-auto py-1">
                                                         @foreach($categories as $cat)
                                                             <div class="flex items-center justify-between px-3 py-1.5 hover:bg-emerald-50/30 dark:hover:bg-emerald-950/20 transition-all text-xs"
                                                                  x-show="$wire.newCategoryInput === '' || '{{ $cat->name }}'.toLowerCase().includes($wire.newCategoryInput.toLowerCase())">
                                                                 <button type="button" @click="$wire.selectCategory({{ $index }}, '{{ $cat->name }}'); openCat = false; $wire.set('newCategoryInput', '')"
                                                                         class="flex-1 text-left font-semibold text-gray-800 dark:text-white hover:text-primary dark:hover:text-secondary transition-all font-sans">
                                                                     {{ $cat->name }} <span class="text-gray-400">(৳{{ floatval($cat->rate) }})</span>
                                                                 </button>
                                                                 <button type="button" wire:click="deleteCategoryOption({{ $cat->id }})"
                                                                         onclick="confirm('এই শ্রেণিটি মুছবেন?') || event.stopImmediatePropagation()"
                                                                         class="ml-2 text-gray-400 hover:text-red-500 transition-all rounded cursor-pointer">×</button>
                                                             </div>
                                                         @endforeach
                                                     </div>
                                                 </div>
                                             </template>
                                         </td>
                                         <td class="px-3 py-2.5 border-r border-gray-100 dark:border-slate-800">
                                             <input type="number" step="0.01" wire:model.live="items.{{ $index }}.rate" placeholder="৳ ০" @focus="$el.select()"
                                                    class="w-full py-1 px-2 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-right text-xs font-semibold focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all text-gray-800 dark:text-white">
                                         </td>
                                         <td class="px-3 py-2.5 border-r border-gray-100 dark:border-slate-800">
                                             <input type="number" wire:model.live="items.{{ $index }}.quantity" placeholder="০" @focus="$el.select()"
                                                    class="w-full py-1 px-2 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-right text-xs font-semibold focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all text-gray-800 dark:text-white">
                                         </td>
                                         <td class="px-3 py-2.5 border-r border-gray-100 dark:border-slate-800 text-right text-gray-500 font-bold select-none">
                                             ৳{{ number_format(floatval($item['amount'] ?? 0), 2) }}
                                         </td>
                                         <td class="px-3 py-2.5 text-center">
                                             @if(!$loop->first)
                                                 <button type="button" wire:click="removeItem({{ $index }})" class="text-gray-400 hover:text-red-500 transition-all cursor-pointer">
                                                     <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                 </button>
                                             @endif
                                         </td>
                                     </tr>
                                 @endforeach
                             </tbody>
                         </table>
                     </div>

                     <!-- Calculations -->
                     <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 items-end">
                         <div class="flex flex-col items-center justify-center p-4 bg-gray-50 dark:bg-slate-950 border border-gray-100 dark:border-slate-800 rounded-3xl gap-4">
                             <div class="flex items-center gap-2 py-2 px-4 bg-red-100 dark:bg-red-950/40 text-red-600 dark:text-red-400 font-bold rounded-2xl text-sm tracking-wider uppercase">DEMO</div>
                             <div class="flex items-center justify-between w-full border-t border-gray-200 dark:border-slate-800 pt-3">
                                 <span class="text-xs font-bold text-gray-700 dark:text-slate-300 font-sans">কাস্টমারকে এসএমএস দিন</span>
                                 <button type="button" @click="$wire.send_sms = !$wire.send_sms" class="relative flex-shrink-0 focus:outline-none cursor-pointer w-11 h-6">
                                     <div class="w-11 h-6 rounded-full transition-colors duration-300 absolute inset-0" :class="$wire.send_sms ? 'bg-emerald-600' : 'bg-slate-200 dark:bg-slate-700'"></div>
                                     <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow-md transition-transform duration-300" :class="$wire.send_sms ? 'translate-x-5' : 'translate-x-0'"></div>
                                 </button>
                             </div>
                         </div>
                         <div class="space-y-3 font-sans">
                             <div class="grid grid-cols-2 items-center gap-2">
                                 <span class="text-xs font-bold text-gray-600 dark:text-gray-400">মূল্য:</span>
                                 <div class="py-2 px-3 text-xs bg-gray-50 dark:bg-slate-950 border border-gray-200 dark:border-slate-700 rounded-xl text-right text-gray-800 dark:text-white font-bold select-none">৳{{ number_format($value, 2) }}</div>
                             </div>
                             <div class="grid grid-cols-2 items-center gap-2">
                                 <span class="text-xs font-bold text-gray-600 dark:text-gray-400">গাড়ি ভাড়া:</span>
                                 <input type="number" wire:model.live="transport_rent" @focus="$el.select()" class="py-2 px-3 text-xs bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-600 rounded-xl text-right text-gray-800 dark:text-white font-bold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all">
                             </div>
                             <div class="grid grid-cols-2 items-center gap-2">
                                 <span class="text-xs font-bold text-gray-600 dark:text-gray-400">ছাড়:</span>
                                 <input type="number" wire:model.live="discount" @focus="$el.select()" class="py-2 px-3 text-xs bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-600 rounded-xl text-right text-gray-800 dark:text-white font-bold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all">
                             </div>
                             <div class="grid grid-cols-2 items-center gap-2">
                                 <span class="text-xs font-bold text-gray-600 dark:text-gray-400">মোট:</span>
                                 <div class="py-2 px-3 text-xs bg-gray-50 dark:bg-slate-950 border border-gray-200 dark:border-slate-700 rounded-xl text-right text-emerald-700 dark:text-emerald-400 font-bold select-none">৳{{ number_format($grand_total, 2) }}</div>
                             </div>
                             <div class="grid grid-cols-2 items-center gap-2">
                                 <span class="text-xs font-bold text-gray-600 dark:text-gray-400">নগদ:</span>
                                 <input type="number" wire:model.live="cash" @focus="$el.select()" class="py-2 px-3 text-xs bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-600 rounded-xl text-right text-emerald-600 dark:text-emerald-400 font-bold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all">
                             </div>
                             <div class="grid grid-cols-2 items-center gap-2">
                                 <span class="text-xs font-bold text-gray-600 dark:text-gray-400">বাকি:</span>
                                 <div class="py-2 px-3 text-xs bg-gray-50 dark:bg-slate-950 border border-gray-200 dark:border-slate-700 rounded-xl text-right text-red-500 dark:text-red-400 font-bold select-none">৳{{ number_format($due, 2) }}</div>
                             </div>
                         </div>
                     </div>

                     <div class="flex items-center justify-end gap-2.5 pt-5 border-t border-gray-200 dark:border-slate-700 mt-6">
                         <button type="button" wire:click="resetForm" class="px-5 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/20 border border-gray-200 dark:border-slate-700 rounded-xl cursor-pointer transition-all font-sans">ক্লিয়ার</button>
                         <button type="submit" class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl cursor-pointer transition-all shadow-md active:scale-95 font-sans">সেভ করুন</button>
                         <button type="button" wire:click="save" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl cursor-pointer transition-all shadow-md active:scale-95 font-sans">সেভ + প্রিন্ট</button>
                     </div>
                 </form>
            </div>
        </div>
    </template>

    <style>
        .challan-modal-scroll::-webkit-scrollbar { width: 5px; }
        .challan-modal-scroll::-webkit-scrollbar-track { background: transparent; }
        .challan-modal-scroll::-webkit-scrollbar-thumb { background: #10b981; border-radius: 999px; }
        .challan-modal-scroll::-webkit-scrollbar-thumb:hover { background: #059669; }
    </style>

</div>
