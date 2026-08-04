@php
if (!function_exists('cashKhataBanglaNum')) {
    function cashKhataBanglaNum($num) {
        if (is_numeric($num)) {
            $num = (float)$num == (int)$num ? number_format((float)$num, 0) : (string)$num;
        } else if (is_string($num)) {
            $num = preg_replace('/\.00$/', '', $num);
        }
        $eng = ['0','1','2','3','4','5','6','7','8','9', ','];
        $bg = ['০','১','২','৩','৪','৫','৬','৭','৮','৯', ','];
        return str_replace($eng, $bg, (string)$num);
    }
}
@endphp

<div class="min-h-screen bg-gray-50 dark:bg-slate-950 transition-colors duration-300 pb-12">
    <!-- Toast Notification (top-center, matches global toast style) -->
    <div x-data="{ show: false, message: '', type: 'success', timer: null }"
         x-init="window.addEventListener('cash-toast', e => { message = e.detail.message; type = e.detail.type || 'success'; show = false; if (timer) clearTimeout(timer); $nextTick(() => { show = true; timer = setTimeout(() => show = false, 3000); }); })"
         x-show="show"
         x-transition:enter="transition ease-out duration-200 transform"
         x-transition:enter-start="-translate-y-10 opacity-0 scale-95"
         x-transition:enter-end="translate-y-0 opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150 transform"
         x-transition:leave-start="translate-y-0 opacity-100 scale-100"
         x-transition:leave-end="-translate-y-10 opacity-0 scale-95"
         x-cloak
         class="fixed top-5 left-1/2 -translate-x-1/2 z-[9999999] px-5 py-3 rounded-2xl shadow-2xl flex items-center gap-3 font-bold text-xs border"
         :class="type === 'success' ? 'bg-[#034C3C] text-white border-emerald-400/30' : 'bg-red-500 text-white border-red-300/40'">
        <span class="text-sm" x-text="type === 'success' ? '✓' : '✕'"></span>
        <span x-text="message"></span>
        <button @click="show = false" class="text-white/70 hover:text-white ml-2 cursor-pointer">✕</button>
    </div>

    <!-- Main Content Area -->
    <div class="w-full">

        <!-- Top Toolbar & Indicators -->
        <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-2xl p-4 shadow-sm mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            
            <!-- Left Side: Buttons & Search -->
            <div class="flex flex-wrap items-center gap-3">
                <button type="button" wire:click="openModal()"
                        class="px-4 py-2 bg-[#034C3C] hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-all active:scale-95 cursor-pointer flex items-center gap-1.5 font-sans">
                    + নতুন হিসাব
                </button>

                <!-- Search Input -->
                <div class="relative min-w-[200px]">
                    <input type="text" wire:model.live="search" placeholder="সার্চ করুন"
                           class="pl-4 pr-9 py-2 text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-55 dark:bg-slate-950 text-gray-808 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all w-full font-sans font-semibold">
                    <span class="absolute right-3 top-2.5 text-gray-400 pointer-events-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                </div>
            </div>

            <!-- Right Side: Stats, Datepicker, Report, Print -->
            <div class="flex flex-wrap items-center gap-2 sm:gap-3 w-full sm:w-auto justify-between sm:justify-start">
                <!-- Stat Badges Container (2 columns on mobile) -->
                <div class="grid grid-cols-2 sm:flex sm:flex-wrap items-center gap-2 sm:gap-3 w-full sm:w-auto">
                    <!-- Stat 1 -->
                    <span class="inline-flex items-center justify-center text-center px-2.5 sm:px-3 py-1.5 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/50 rounded-xl text-xs font-bold font-sans">
                        আজকের ক্যাশ: {{ cashKhataBanglaNum(number_format($todayCashIn)) }} টাকা
                    </span>

                    <!-- Stat 2: ক্যাশ জের with tooltip -->
                    <span x-data="{ open: false }" class="relative inline-flex items-center justify-center">
                        <span @mouseenter="open = true" @mouseleave="open = false"
                              class="w-full inline-flex items-center justify-center gap-1.5 px-2.5 sm:px-3 py-1.5 bg-sky-50 dark:bg-sky-950/30 text-sky-700 dark:text-sky-400 border border-sky-100 dark:border-sky-900/50 rounded-xl text-xs font-bold font-sans cursor-help">
                            <svg class="w-3.5 h-3.5 text-sky-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                            ক্যাশ জের: {{ cashKhataBanglaNum(number_format($cashJer)) }} টাকা
                        </span>
                        <!-- Tooltip (shows below the badge) -->
                        <div x-show="open" x-cloak x-transition
                             class="absolute top-full left-1/2 -translate-x-1/2 mt-2.5 z-[9999] w-64 bg-slate-900 dark:bg-slate-800 border border-slate-700 rounded-2xl shadow-2xl p-4 text-left pointer-events-none">
                            <!-- arrow pointing up -->
                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 border-4 border-transparent border-b-slate-900 dark:border-b-slate-800"></div>
                            <p class="text-[11px] font-extrabold text-white mb-2.5 border-b border-slate-700 pb-2">ক্যাশ জের হিসাব</p>
                            <div class="space-y-1.5">
                                <div class="flex justify-between text-[11px]">
                                    <span class="text-slate-400">প্রারম্ভিক ব্যালেন্স</span>
                                    <span class="text-emerald-400 font-bold">+{{ cashKhataBanglaNum(number_format($cashJerBreakdown['base'])) }}</span>
                                </div>
                                <div class="flex justify-between text-[11px]">
                                    <span class="text-slate-400">সমস্ত চালান ক্যাশ</span>
                                    <span class="text-emerald-400 font-bold">+{{ cashKhataBanglaNum(number_format($cashJerBreakdown['challanCash'])) }}</span>
                                </div>
                                <div class="flex justify-between text-[11px]">
                                    <span class="text-slate-400">ম্যানুয়াল ক্যাশ ইন</span>
                                    <span class="text-emerald-400 font-bold">+{{ cashKhataBanglaNum(number_format($cashJerBreakdown['manualIn'])) }}</span>
                                </div>
                                <div class="flex justify-between text-[11px]">
                                    <span class="text-slate-400">সমস্ত পেমেন্ট খরচ</span>
                                    <span class="text-rose-400 font-bold">-{{ cashKhataBanglaNum(number_format($cashJerBreakdown['paymentOut'])) }}</span>
                                </div>
                                <div class="flex justify-between text-[11px]">
                                    <span class="text-slate-400">ম্যানুয়াল ক্যাশ আউট</span>
                                    <span class="text-rose-400 font-bold">-{{ cashKhataBanglaNum(number_format($cashJerBreakdown['manualOut'])) }}</span>
                                </div>
                            </div>
                            <div class="flex justify-between text-[11px] font-extrabold border-t border-slate-700 mt-2.5 pt-2">
                                <span class="text-white">সর্বমোট জের</span>
                                <span class="text-sky-400">{{ cashKhataBanglaNum(number_format($cashJer)) }} টাকা</span>
                            </div>
                        </div>
                    </span>
                </div>

                <!-- Date Picker & Action Buttons -->
                <div class="flex items-center gap-2 sm:gap-3 mt-2 sm:mt-0">
                    <!-- Date Picker -->
                    <div class="relative flex items-center">
                        <input type="text"
                               data-flatpickr
                               data-wire-prop="dateFilter"
                               data-default="{{ $dateFilter }}"
                               wire:model="dateFilter"
                               readonly
                               class="pl-3 pr-8 py-2 text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-55 dark:bg-slate-950 text-gray-855 dark:text-white focus:outline-none focus:border-emerald-500/20 transition-all w-36 font-sans font-semibold cursor-pointer">
                        <span class="absolute right-2.5 top-2.5 text-emerald-500 pointer-events-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                        </span>
                    </div>

                    <!-- Report (Invest) Button -->
                    <button type="button" wire:click="openInvestModal()"
                            class="px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl cursor-pointer transition-all font-sans flex items-center gap-1.5 active:scale-95">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        রিপোর্ট
                    </button>

                    <!-- Print Button -->
                    <button type="button" onclick="printChallanArea('cash-khata-table-print')"
                            class="px-3 py-2 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-705 dark:text-slate-200 text-xs font-semibold rounded-xl cursor-pointer transition-all font-sans border border-gray-200 dark:border-slate-700 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/>
                        </svg>
                        প্রিন্ট
                    </button>
                </div>
            </div>
        </div>

        <!-- Cash Table Card Section -->
        <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
            <!-- Desktop Table View -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full border-collapse text-left border border-gray-200 dark:border-slate-800">
                    <thead>
                        <tr class="bg-[#034C3C] text-white text-[11px] font-bold uppercase font-sans">
                            <th class="py-3 px-4 text-center border-r border-white/20 last:border-r-0 w-12">#</th>
                            <th class="py-3 px-4 border-r border-white/20 last:border-r-0">ক্যাশের বিবরণ</th>
                            <th class="py-3 px-4 border-r border-white/20 last:border-r-0">উৎস</th>
                            <th class="py-3 px-4 text-center border-r border-white/20 last:border-r-0">ক্যাশ ইন</th>
                            <th class="py-3 px-4 text-center border-r border-white/20 last:border-r-0">ক্যাশ আউট</th>
                            <th class="py-3 px-4 text-center border-r border-white/20 last:border-r-0 w-28">সময়</th>
                            <th class="py-3 px-4 text-center w-24">বাটন</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800 font-sans text-xs">
                        @foreach($systemRows as $sIndex => $entry)
                            <tr class="bg-emerald-50/40 dark:bg-emerald-950/15 transition-colors">
                                <td class="py-3.5 px-4 text-center text-gray-500 dark:text-slate-455 font-semibold border-r border-gray-150 dark:border-slate-800 last:border-r-0">
                                    {{ cashKhataBanglaNum($sIndex + 1) }}
                                </td>
                                <td class="py-3.5 px-4 font-bold border-r border-gray-150 dark:border-slate-800 last:border-r-0 {{ $entry->description == 'মোট পেমেন্ট দেওয়া' ? 'text-red-500 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">
                                    {{ $entry->description }}
                                </td>
                                <td class="py-3.5 px-4 text-xs italic text-gray-400 dark:text-slate-500 border-r border-gray-150 dark:border-slate-800 last:border-r-0">
                                    স্বয়ংক্রিয়
                                </td>
                                <td class="py-3.5 px-4 text-center font-bold text-emerald-600 dark:text-emerald-400 border-r border-gray-150 dark:border-slate-800 last:border-r-0 font-mono">
                                    {{ $entry->cash_in !== null ? '৳ ' . cashKhataBanglaNum(number_format($entry->cash_in)) : '-' }}
                                </td>
                                <td class="py-3.5 px-4 text-center font-bold text-rose-500 border-r border-gray-150 dark:border-slate-800 last:border-r-0 font-mono">
                                    {{ $entry->cash_out !== null ? '৳ ' . cashKhataBanglaNum(number_format($entry->cash_out)) : '-' }}
                                </td>
                                <td class="py-3.5 px-4 text-center text-gray-500 dark:text-slate-400 border-r border-gray-150 dark:border-slate-800 last:border-r-0">
                                    -
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="inline-flex items-center text-gray-300 dark:text-slate-600 cursor-not-allowed">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                        @forelse($entries as $index => $entry)
                            <tr class="hover:bg-emerald-50/40 dark:hover:bg-emerald-950/10 transition-colors">
                                <td class="py-3.5 px-4 text-center text-gray-500 dark:text-slate-455 font-semibold border-r border-gray-150 dark:border-slate-800 last:border-r-0">
                                    {{ cashKhataBanglaNum($systemRows->count() + $entries->firstItem() + $index) }}
                                </td>
                                <td class="py-3.5 px-4 font-bold border-r border-gray-150 dark:border-slate-800 last:border-r-0 text-gray-900 dark:text-white">
                                    {{ $entry->description }}
                                </td>
                                <td class="py-3.5 px-4 border-r border-gray-150 dark:border-slate-800 last:border-r-0 text-xs">
                                    {{ $entry->source ?: '-' }}
                                </td>
                                <td class="py-3.5 px-4 text-center font-bold text-emerald-600 dark:text-emerald-400 border-r border-gray-150 dark:border-slate-800 last:border-r-0 font-mono">
                                    {{ $entry->cash_in !== null ? '৳ ' . cashKhataBanglaNum(number_format($entry->cash_in)) : '-' }}
                                </td>
                                <td class="py-3.5 px-4 text-center font-bold text-rose-500 border-r border-gray-150 dark:border-slate-800 last:border-r-0 font-mono">
                                    {{ $entry->cash_out !== null ? '৳ ' . cashKhataBanglaNum(number_format($entry->cash_out)) : '-' }}
                                </td>
                                <td class="py-3.5 px-4 text-center text-gray-500 dark:text-slate-400 border-r border-gray-150 dark:border-slate-800 last:border-r-0">
                                    {{ $entry->time ? cashKhataBanglaNum(date('h:i A', strtotime($entry->time))) : '-' }}
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        @if($entry->is_system)
                                            <span class="text-gray-300 dark:text-slate-600 cursor-not-allowed"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg></span>
                                        @else
                                            <button type="button" wire:click="edit({{ $entry->id }})" class="text-emerald-600 hover:text-emerald-800 hover:scale-110 transition-all"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg></button>
                                            <button type="button" wire:click="confirmDelete({{ $entry->id }})" class="text-red-500 hover:text-red-700 hover:scale-110 transition-all"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="py-4 text-center text-sm text-gray-400">কোনো তথ্য নেই।</td></tr>
                        @endforelse
                        @if($systemRows->count() > 0 || $entries->count() > 0)
                            <tr class="bg-blue-50/40 dark:bg-blue-950/10 border-t-2 border-gray-200 dark:border-slate-800">
                                <td colspan="3" class="py-3 px-4 font-extrabold text-right border-r">মোট</td>
                                <td class="py-3 px-4 text-center font-black text-emerald-600 dark:text-emerald-400 border-r font-mono">৳ {{ cashKhataBanglaNum(number_format($viewTotalCashIn)) }}</td>
                                <td class="py-3 px-4 text-center font-black text-rose-500 border-r font-mono">৳ {{ cashKhataBanglaNum(number_format($viewTotalCashOut)) }}</td>
                                <td colspan="2" class="py-3 px-4"></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Mobile Box-Type Card View -->
            <div class="block sm:hidden p-3.5 space-y-3">
                @foreach($systemRows as $sIndex => $entry)
                    <div class="p-3.5 bg-emerald-50/40 dark:bg-emerald-950/20 rounded-2xl border border-emerald-100 dark:border-emerald-900/40 space-y-2 text-xs font-sans">
                        <div class="flex items-center justify-between border-b border-emerald-100 dark:border-emerald-900/40 pb-2">
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-full bg-emerald-200/60 dark:bg-emerald-900/60 text-emerald-800 dark:text-emerald-300 flex items-center justify-center font-bold text-[10px]">
                                    {{ cashKhataBanglaNum($sIndex + 1) }}
                                </span>
                                <span class="font-extrabold text-gray-900 dark:text-white {{ $entry->description == 'মোট পেমেন্ট দেওয়া' ? 'text-red-500 dark:text-red-400' : '' }}">
                                    {{ $entry->description }}
                                </span>
                            </div>
                            <span class="text-[10px] text-gray-400 dark:text-slate-500 font-semibold italic">স্বয়ংক্রিয়</span>
                        </div>
                        <div class="flex items-center justify-between pt-0.5">
                            <div>
                                <span class="text-gray-400 text-[11px]">উৎস:</span>
                                <span class="font-semibold text-gray-500 dark:text-slate-400 text-[11px] ml-1">স্বয়ংক্রিয়</span>
                            </div>
                            <div class="text-right">
                                @if($entry->cash_in !== null)
                                    <span class="block text-gray-400 text-[10px]">ক্যাশ ইন</span>
                                    <span class="font-bold text-emerald-600 dark:text-emerald-400 font-mono text-sm">৳ {{ cashKhataBanglaNum(number_format($entry->cash_in)) }}</span>
                                @elseif($entry->cash_out !== null)
                                    <span class="block text-gray-400 text-[10px]">ক্যাশ আউট</span>
                                    <span class="font-bold text-rose-500 font-mono text-sm">৳ {{ cashKhataBanglaNum(number_format($entry->cash_out)) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

                @forelse($entries as $index => $entry)
                    <div class="p-3.5 bg-white dark:bg-slate-900 rounded-2xl border border-gray-150 dark:border-slate-800 space-y-2 text-xs font-sans shadow-xs">
                        <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-800 pb-2">
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-full bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-300 flex items-center justify-center font-bold text-[10px]">
                                    {{ cashKhataBanglaNum($systemRows->count() + $entries->firstItem() + $index) }}
                                </span>
                                <span class="font-extrabold text-gray-900 dark:text-white">
                                    {{ $entry->description }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" wire:click="edit({{ $entry->id }})" class="text-emerald-600 hover:text-emerald-800"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg></button>
                                <button type="button" wire:click="confirmDelete({{ $entry->id }})" class="text-red-500 hover:text-red-700"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-[11px]">
                            <div class="space-y-0.5">
                                <div><span class="text-gray-400">উৎস:</span> <span class="font-semibold text-gray-700 dark:text-slate-300 ml-1">{{ $entry->source ?: '-' }}</span></div>
                                <div><span class="text-gray-400">সময়:</span> <span class="font-medium text-gray-500 dark:text-slate-400 ml-1">{{ $entry->time ? cashKhataBanglaNum(date('h:i A', strtotime($entry->time))) : '-' }}</span></div>
                            </div>
                            <div class="text-right">
                                @if($entry->cash_in !== null)
                                    <span class="block text-gray-400 text-[10px]">ক্যাশ ইন</span>
                                    <span class="font-bold text-emerald-600 dark:text-emerald-400 font-mono text-sm">৳ {{ cashKhataBanglaNum(number_format($entry->cash_in)) }}</span>
                                @elseif($entry->cash_out !== null)
                                    <span class="block text-gray-400 text-[10px]">ক্যাশ আউট</span>
                                    <span class="font-bold text-rose-500 font-mono text-sm">৳ {{ cashKhataBanglaNum(number_format($entry->cash_out)) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-6 text-center text-gray-400 font-semibold text-xs bg-white dark:bg-slate-900 rounded-2xl border">কোনো তথ্য নেই।</div>
                @endforelse

                @if($systemRows->count() > 0 || $entries->count() > 0)
                    <div class="p-3.5 bg-blue-50/50 dark:bg-blue-950/20 rounded-2xl border border-blue-100 dark:border-blue-900/40 font-sans text-xs space-y-1.5">
                        <div class="font-extrabold text-gray-800 dark:text-white border-b pb-1.5">সর্বমোট হিসাব</div>
                        <div class="flex justify-between text-emerald-600 font-bold font-mono"><span>মোট ক্যাশ ইন:</span><span>৳ {{ cashKhataBanglaNum(number_format($viewTotalCashIn)) }}</span></div>
                        <div class="flex justify-between text-rose-500 font-bold font-mono"><span>মোট ক্যাশ আউট:</span><span>৳ {{ cashKhataBanglaNum(number_format($viewTotalCashOut)) }}</span></div>
                    </div>
                @endif
            </div>

            <!-- Pagination Footer -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 px-5 py-4 border-t border-gray-100 dark:border-slate-800">
                <div class="text-xs text-gray-500 dark:text-gray-400 font-sans font-semibold">
                    মোট: <strong class="text-gray-800 dark:text-white">{{ cashKhataBanglaNum($entries->total()) }} টি</strong>
                </div>

                <div class="flex items-center gap-4">
                    {{-- Pagination Links --}}
                    {{ $entries->links() }}

                    {{-- Per Page --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" type="button"
                                class="flex items-center justify-between gap-1.5 px-3 py-1.5 bg-white dark:bg-slate-800 text-gray-800 dark:text-white font-bold rounded-lg text-xs border border-gray-200 dark:border-slate-700 cursor-pointer">
                            <span class="font-sans">{{ cashKhataBanglaNum($perPage) }} / পেজ</span>
                            <svg class="w-3.5 h-3.5 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" @click.outside="open = false" x-cloak
                             class="absolute bottom-full mb-1.5 right-0 z-[999] w-32 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl shadow-xl overflow-hidden">
                            <div class="py-1">
                                @foreach([10, 20, 30, 50] as $size)
                                    <button type="button" wire:click="$set('perPage', {{ $size }})" @click="open = false"
                                            class="w-full text-left px-3 py-2 text-xs font-bold text-gray-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-slate-800 hover:text-emerald-700 dark:hover:text-emerald-400 font-sans cursor-pointer">
                                        {{ cashKhataBanglaNum($size) }} / পেজ
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal 1: ক্যাশের হিসাব (মালিকের লেনদেন রেকর্ড) -->
    <div x-data="{ show: @entangle('showModal') }"
         x-show="show"
         @click.self="show = false"
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center bg-black/60 backdrop-blur-xs p-4"
         x-cloak
         x-transition>
        
        <div class="bg-white dark:bg-slate-900 rounded-3xl border-2 border-[#034C3C]/30 dark:border-emerald-800/50 p-6 md:p-8 max-w-lg w-full relative overflow-y-auto max-h-[90vh] shadow-2xl">
            <!-- Close button -->
            <button @click="show = false" class="absolute top-4 right-4 p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-400 hover:text-gray-600 dark:hover:text-gray-250 cursor-pointer focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <!-- Title with Graphic icon -->
            <div class="flex items-center gap-3.5 border-b border-gray-100 dark:border-slate-800/80 pb-4 mb-6 text-left">
                <div class="w-10 h-10 rounded-xl {{ $entryType === 'in' ? 'bg-emerald-100 dark:bg-emerald-950/40 text-[#034C3C] dark:text-emerald-400' : 'bg-orange-100 dark:bg-orange-950/40 text-orange-500 dark:text-orange-400' }} flex items-center justify-center flex-shrink-0 transition-colors duration-300">
                    <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18-3V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-gray-800 dark:text-white leading-tight font-sans">ক্যাশের হিসাব</h3>
                    <p class="text-[10px] text-gray-400 font-semibold font-sans mt-0.5">মালিকের লেনদেন রেকর্ড বিবরণী</p>
                </div>
            </div>

            <!-- Form Content -->
            <div class="space-y-4 text-left">
                <!-- Tab System: ক্যাশ ইন / ক্যাশ আউট -->
                <div class="grid grid-cols-2 gap-2 p-1.5 bg-gray-100 dark:bg-slate-800 rounded-xl">
                    <button type="button" wire:click="$set('entryType', 'in')"
                            class="px-4 py-2.5 rounded-lg text-xs font-bold cursor-pointer flex items-center justify-center gap-2
                                   {{ $entryType === 'in'
                                       ? 'bg-[#034C3C] text-white'
                                       : 'text-gray-500 dark:text-slate-400 hover:bg-white dark:hover:bg-slate-700' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 19V7m0 0l-4 4m4-4l4 4"/>
                        </svg>
                        ক্যাশ ইন (Income)
                    </button>
                    <button type="button" wire:click="$set('entryType', 'out')"
                            class="px-4 py-2.5 rounded-lg text-xs font-bold cursor-pointer flex items-center justify-center gap-2
                                   {{ $entryType === 'out'
                                       ? 'bg-orange-500 text-white'
                                       : 'text-gray-500 dark:text-slate-400 hover:bg-white dark:hover:bg-slate-700' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v12m0 0l4-4m-4 4l-4-4"/>
                        </svg>
                        ক্যাশ আউট (Expense)
                    </button>
                </div>

                <!-- Source Field: উৎস (cash in) / খাত (cash out) -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 mb-1.5 font-sans">
                        @if($entryType === 'in')
                            উৎস <span class="text-gray-400 dark:text-slate-500 font-normal">(কোথা থেকে টাকা আসলো?)</span>
                        @else
                            খাত <span class="text-gray-400 dark:text-slate-500 font-normal">(কোথায় খরচ হলো?)</span>
                        @endif
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-3 {{ $entryType === 'in' ? 'text-[#034C3C] dark:text-emerald-400' : 'text-orange-500 dark:text-orange-400' }} transition-colors duration-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                            </svg>
                        </span>
                        <input type="text" wire:model="source"
                               placeholder="{{ $entryType === 'in' ? 'যেমন: বিক্রয়, বিনিয়োগ, ঋণ...' : 'যেমন: মজুরি, কাঁচামাল, ব্যাংক...' }}"
                               class="w-full pl-10 pr-4 py-3 text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-55 dark:bg-slate-800 text-gray-808 dark:text-white font-semibold font-sans focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                    </div>
                </div>

                <!-- Description Field -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 mb-1.5 font-sans">
                        হিসাবের বিবরণ <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-3.5 {{ $entryType === 'in' ? 'text-[#034C3C] dark:text-emerald-400' : 'text-orange-500 dark:text-orange-400' }} transition-colors duration-300">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                            </svg>
                        </span>
                        <input type="text" wire:model="description" placeholder="বিবরণ লিখুন (যেমন: মহাজন দেওয়া-নেওয়া)"
                               class="w-full pl-10 pr-4 py-3 text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-55 dark:bg-slate-800 text-gray-808 dark:text-white font-semibold font-sans focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                    </div>
                    @error('description')
                        <p class="text-red-500 text-[10px] mt-1 font-semibold font-sans">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Amount Field (পরিমাণ) -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 mb-1.5 font-sans">পরিমাণ (৳) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-3 text-sm font-extrabold {{ $entryType === 'in' ? 'text-emerald-600 dark:text-emerald-400' : 'text-orange-500 dark:text-orange-400' }} transition-colors duration-300">৳</span>
                        <input type="number" min="0" step="0.01" wire:model="amount" placeholder="০"
                               class="w-full pl-7 pr-3 py-2.5 text-xs font-bold font-mono rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-55 dark:bg-slate-800 text-gray-808 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                    </div>
                    @error('amount')
                        <p class="text-red-500 text-[10px] mt-1 font-semibold font-sans">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date & Time (auto-set — no calendar, no manual change) -->
                <div class="grid grid-cols-2 gap-4">
                    <!-- Date (auto = today) -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 mb-1.5 font-sans">তারিখ <span class="text-red-500">*</span></label>
                        <div class="relative">
                            {{-- Hidden real date value for Livewire binding --}}
                            <input type="hidden" wire:model="date">
                            <input type="text"
                                   value="{{ $date ? date('d-m-y', strtotime($date)) : '' }}"
                                   readonly
                                   class="w-full pl-3 pr-8 py-2.5 text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-100 dark:bg-slate-900 text-gray-500 dark:text-slate-400 focus:outline-none transition-all font-sans font-semibold cursor-not-allowed">
                        </div>
                        @error('date')
                            <p class="text-red-500 text-[10px] mt-1 font-semibold font-sans">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 mb-1.5 font-sans">সময় <span class="text-red-500">*</span></label>
                        {{-- Hidden real time value for Livewire binding --}}
                        <input type="hidden" wire:model="time">
                        <input type="text"
                               value="{{ $time ? date('h:i A', strtotime($time)) : '' }}"
                               readonly
                               class="w-full px-3 py-2.5 text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-100 dark:bg-slate-900 text-gray-500 dark:text-slate-400 font-sans font-semibold focus:outline-none cursor-not-allowed">
                        @error('time')
                            <p class="text-red-500 text-[10px] mt-1 font-semibold font-sans">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Footer Action Buttons -->
            <div class="flex items-center justify-end gap-3.5 mt-8 pt-5 border-t border-gray-100 dark:border-slate-800/80">
                <button type="button" wire:click="resetForm()"
                        class="px-5 py-2.5 border border-gray-200 dark:border-slate-800 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-white text-xs font-bold rounded-xl cursor-pointer">
                    ক্লিয়ার
                </button>
                <button type="button" wire:click="save()"
                        class="px-6 py-2.5 text-white text-xs font-bold rounded-xl cursor-pointer active:scale-95
                               {{ $entryType === 'in'
                                   ? 'bg-[#034C3C] hover:bg-emerald-700'
                                   : 'bg-orange-500 hover:bg-orange-600' }}">
                    সেভ করুন
                </button>
            </div>
        </div>
    </div>

    <!-- Modal 2: ইনভেস্টমেন্ট এর হিসাব (রিপোর্ট) -->
    <div x-data="{ show: @entangle('showInvestModal') }"
         x-show="show"
         @click.self="show = false"
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center bg-black/60 backdrop-blur-xs p-4"
         x-cloak
         x-transition>
        
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-150 dark:border-slate-800 p-6 md:p-8 max-w-2xl w-full relative overflow-y-auto max-h-[90vh] shadow-2xl">
            <!-- Close button -->
            <button @click="show = false" class="absolute top-4 right-4 p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-400 hover:text-gray-600 dark:hover:text-gray-250 cursor-pointer focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <!-- Modal Title -->
            <h2 class="text-base md:text-lg font-black text-gray-850 dark:text-white font-sans tracking-wide border-b border-gray-100 dark:border-slate-800 pb-3 mb-5 text-left">
                ক্যাশ রিপোর্ট
                @if($dateFilter)
                    <span class="text-xs font-semibold text-gray-400 dark:text-slate-500 ml-2">({{ date('d-m-Y', strtotime($dateFilter)) }})</span>
                @endif
            </h2>

            <!-- Report Table — Desktop View (hidden on mobile) -->
            <div class="hidden sm:block border border-gray-150 dark:border-slate-800 rounded-2xl overflow-hidden mb-6">
                <table class="w-full border-collapse border border-gray-200 dark:border-slate-800">
                    <thead>
                        <tr class="bg-[#034C3C] text-white text-xs">
                            <th class="py-2.5 px-3 text-xs font-bold text-left border-r border-white/20">উৎস</th>
                            <th class="py-2.5 px-3 text-xs font-bold text-center border-r border-white/20">ইনভেস্ট (ক্যাশ ইন)</th>
                            <th class="py-2.5 px-3 text-xs font-bold text-center border-r border-white/20">রিটার্ন (ক্যাশ আউট)</th>
                            <th class="py-2.5 px-3 text-xs font-bold text-center">ব্যালেন্স</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $reportTotalIn = 0; $reportTotalOut = 0; $runningBalance = 0; @endphp
                        @forelse($investReportRows as $row)
                            @php
                                $in = $row->cash_in ?? 0;
                                $out = $row->cash_out ?? 0;
                                $reportTotalIn  += $in;
                                $reportTotalOut += $out;
                                $runningBalance += ($in - $out);
                            @endphp
                            <tr class="border-b border-gray-100 dark:border-slate-800 hover:bg-emerald-50/30 dark:hover:bg-emerald-950/10 transition-colors">
                                <td class="py-3 px-3 text-xs font-bold text-gray-800 dark:text-white border-r border-gray-100 dark:border-slate-800">{{ $row->source }}</td>
                                <td class="py-3 px-3 text-xs font-bold text-emerald-600 dark:text-emerald-400 text-center font-mono border-r border-gray-100 dark:border-slate-800">
                                    {{ $row->cash_in !== null ? '৳ ' . cashKhataBanglaNum(number_format($row->cash_in)) : '-' }}
                                </td>
                                <td class="py-3 px-3 text-xs font-bold text-rose-500 text-center font-mono border-r border-gray-100 dark:border-slate-800">
                                    {{ $row->cash_out !== null ? '৳ ' . cashKhataBanglaNum(number_format($row->cash_out)) : '-' }}
                                </td>
                                <td class="py-3 px-3 text-xs font-bold text-slate-700 dark:text-slate-200 text-center font-mono">
                                    ৳ {{ cashKhataBanglaNum(number_format($runningBalance)) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-14 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-2.5 text-gray-400">
                                        <span class="text-3xl">📥</span>
                                        <p class="text-xs font-bold font-sans">এই তারিখে কোনো ডেটা নেই।</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        @if($investReportRows->count() > 0)
                            <tr class="bg-slate-50 dark:bg-slate-800/50 font-extrabold border-t-2 border-gray-200 dark:border-slate-700">
                                <td class="py-3 px-3 text-xs text-gray-800 dark:text-white text-right border-r border-gray-200 dark:border-slate-700">মোট</td>
                                <td class="py-3 px-3 text-xs text-emerald-600 dark:text-emerald-400 text-center font-mono border-r border-gray-200 dark:border-slate-700">৳ {{ cashKhataBanglaNum(number_format($reportTotalIn)) }}</td>
                                <td class="py-3 px-3 text-xs text-rose-500 text-center font-mono border-r border-gray-200 dark:border-slate-700">৳ {{ cashKhataBanglaNum(number_format($reportTotalOut)) }}</td>
                                <td class="py-3 px-3 text-xs text-slate-800 dark:text-white text-center font-mono">৳ {{ cashKhataBanglaNum(number_format($reportTotalIn - $reportTotalOut)) }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Report Cards — Mobile View (hidden on sm+) -->
            <div class="block sm:hidden space-y-3 mb-6">
                @php $reportTotalIn = 0; $reportTotalOut = 0; $runningBalance = 0; @endphp
                @forelse($investReportRows as $row)
                    @php
                        $in  = $row->cash_in  ?? 0;
                        $out = $row->cash_out ?? 0;
                        $reportTotalIn  += $in;
                        $reportTotalOut += $out;
                        $runningBalance += ($in - $out);
                    @endphp
                    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-150 dark:border-slate-700 p-3.5 space-y-2.5 shadow-xs font-sans">
                        <!-- Card Header: উৎস -->
                        <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-700 pb-2">
                            <span class="font-extrabold text-gray-900 dark:text-white text-xs">{{ $row->source }}</span>
                            @php $bal = $runningBalance; @endphp
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $bal >= 0 ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400' : 'bg-rose-100 dark:bg-rose-900/40 text-rose-600 dark:text-rose-400' }}">
                                ব্যালেন্স: ৳ {{ cashKhataBanglaNum(number_format($bal)) }}
                            </span>
                        </div>
                        <!-- Card Body: ক্যাশ ইন / আউট -->
                        <div class="grid grid-cols-2 gap-2">
                            <div class="bg-emerald-50 dark:bg-emerald-950/30 rounded-xl p-2.5 text-center">
                                <p class="text-[10px] text-emerald-600 dark:text-emerald-400 font-semibold mb-0.5">ইনভেস্ট (ক্যাশ ইন)</p>
                                <p class="text-sm font-black text-emerald-700 dark:text-emerald-400 font-mono">
                                    {{ $row->cash_in !== null ? '৳ ' . cashKhataBanglaNum(number_format($row->cash_in)) : '-' }}
                                </p>
                            </div>
                            <div class="bg-rose-50 dark:bg-rose-950/30 rounded-xl p-2.5 text-center">
                                <p class="text-[10px] text-rose-500 dark:text-rose-400 font-semibold mb-0.5">রিটার্ন (ক্যাশ আউট)</p>
                                <p class="text-sm font-black text-rose-600 dark:text-rose-400 font-mono">
                                    {{ $row->cash_out !== null ? '৳ ' . cashKhataBanglaNum(number_format($row->cash_out)) : '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-14 space-y-2.5 text-gray-400">
                        <span class="text-3xl">📥</span>
                        <p class="text-xs font-bold font-sans">এই তারিখে কোনো ডেটা নেই।</p>
                    </div>
                @endforelse

                <!-- Mobile Total Summary Card -->
                @if($investReportRows->count() > 0)
                    <div class="bg-slate-50 dark:bg-slate-800/60 rounded-2xl border-2 border-gray-200 dark:border-slate-700 p-3.5 font-sans">
                        <p class="text-xs font-extrabold text-gray-800 dark:text-white border-b border-gray-200 dark:border-slate-700 pb-2 mb-2.5">সর্বমোট</p>
                        <div class="grid grid-cols-3 gap-2 text-center text-xs">
                            <div>
                                <p class="text-[10px] text-gray-400 font-semibold mb-0.5">ক্যাশ ইন</p>
                                <p class="font-black text-emerald-600 dark:text-emerald-400 font-mono">৳ {{ cashKhataBanglaNum(number_format($reportTotalIn)) }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 font-semibold mb-0.5">ক্যাশ আউট</p>
                                <p class="font-black text-rose-500 font-mono">৳ {{ cashKhataBanglaNum(number_format($reportTotalOut)) }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 font-semibold mb-0.5">ব্যালেন্স</p>
                                <p class="font-black {{ ($reportTotalIn - $reportTotalOut) >= 0 ? 'text-slate-700 dark:text-slate-200' : 'text-rose-600' }} font-mono">৳ {{ cashKhataBanglaNum(number_format($reportTotalIn - $reportTotalOut)) }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Delete Confirm Modal -->
    <div x-data="{ show: @entangle('confirmDeleteId').live }"
         x-show="show !== null && show !== false && show !== 0"
         @click.self="$wire.cancelDelete()"
         class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
         x-cloak
         x-transition>
        <div class="bg-white dark:bg-slate-900 rounded-2xl border-2 border-red-200 dark:border-red-900/60 p-6 max-w-sm w-full shadow-2xl">
            <div class="flex items-start gap-4 mb-5">
                <div class="w-11 h-11 rounded-xl bg-red-100 dark:bg-red-950/40 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-gray-900 dark:text-white font-sans">মুছে ফেলবেন?</h3>
                    <p class="text-xs text-gray-500 dark:text-slate-400 font-semibold font-sans mt-1">এই ক্যাশ হিসাবের রেকর্ডটি স্থায়ীভাবে মুছে যাবে। এটি আর ফিরিয়ে আনা যাবে না।</p>
                </div>
            </div>
            <div class="flex items-center gap-3 justify-end">
                <button type="button" wire:click="cancelDelete()"
                        class="px-5 py-2.5 border border-gray-200 dark:border-slate-700 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-600 dark:text-slate-300 text-xs font-bold rounded-xl transition-all cursor-pointer font-sans">
                    না, বাতিল
                </button>
                <button type="button" wire:click="delete()"
                        class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl transition-all cursor-pointer shadow-md shadow-red-500/25 active:scale-95 font-sans">
                    হ্যাঁ, মুছে দিন
                </button>
            </div>
        </div>
    </div>

    <!-- Hidden Full Cash Khata Printable Container (Target for printChallanArea) -->
    <div id="cash-khata-table-print" style="display:none;">
        <x-print-layout type="cash-report"
                        :systemRows="$systemRows"
                        :entries="$entries->items()"
                        :todayCashIn="$todayCashIn"
                        :todayCashOut="$todayCashOut"
                        :cashJer="$cashJer"
                        :reportDate="$dateFilter"
                        :activeSeason="$activeSeason"
                        :viewTotalCashIn="$viewTotalCashIn"
                        :viewTotalCashOut="$viewTotalCashOut" />
    </div>
</div>
