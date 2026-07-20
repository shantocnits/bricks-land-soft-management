@php
if (!function_exists('toBanglaNum')) {
    function toBanglaNum($num) {
        $eng = ['0','1','2','3','4','5','6','7','8','9', ','];
        $bg = ['০','১','২','৩','৪','৫','৬','৭','৮','৯', ','];
        return str_replace($eng, $bg, $num);
    }
}
@endphp

<div class="min-h-screen bg-gray-50 dark:bg-slate-950 transition-colors duration-300 pb-12">
    <!-- Toast Notification Wrapper -->
    <div x-data="{ show: false, message: '', type: 'success' }"
         x-init="window.addEventListener('show-toast', e => { message = e.detail.message; type = e.detail.type || 'success'; show = true; setTimeout(() => show = false, 3000); })"
         x-show="show"
         x-transition
         class="fixed top-4 right-4 z-[9999] px-4 py-3 rounded-2xl shadow-2xl flex items-center gap-2.5 font-sans font-bold text-xs"
         :class="type === 'success' ? 'bg-emerald-500 text-white' : 'bg-red-500 text-white'"
         x-cloak>
        <span x-text="type === 'success' ? '✓' : '✕'"></span>
        <span x-text="message"></span>
    </div>

    <!-- Main Content Area -->
    <div class="w-full">
        <!-- Top Toolbar & Indicators -->
        <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-2xl p-4 shadow-sm mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            
            <!-- Left Side: Buttons & Search -->
            <div class="flex flex-wrap items-center gap-3">
                <button type="button" wire:click="openModal()"
                        class="px-4 py-2 bg-[#034C3C] hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-emerald-500/20 active:scale-95 cursor-pointer flex items-center gap-1.5 font-sans">
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
            <div class="flex flex-wrap items-center gap-3">
                <!-- Stat 1 -->
                <span class="inline-flex items-center px-3 py-1.5 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/50 rounded-xl text-xs font-bold font-sans">
                    আজকের ক্যাশ: {{ toBanglaNum(number_format($todayCashIn)) }} টাকা
                </span>

                <!-- Stat 2 -->
                <span class="inline-flex items-center px-3 py-1.5 bg-sky-50 dark:bg-sky-950/30 text-sky-700 dark:text-sky-400 border border-sky-100 dark:border-sky-900/50 rounded-xl text-xs font-bold font-sans">
                    ক্যাশ জের: {{ toBanglaNum(number_format($cashJer)) }} টাকা
                </span>

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
                <button type="button" onclick="window.print()"
                        class="px-3 py-2 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-705 dark:text-slate-200 text-xs font-semibold rounded-xl cursor-pointer transition-all font-sans border border-gray-200 dark:border-slate-700 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/>
                    </svg>
                    প্রিন্ট
                </button>
            </div>
        </div>

        <!-- Cash Table Card Section -->
        <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left border border-gray-200 dark:border-slate-800">
                    <thead>
                        <tr class="bg-[#034C3C] text-white text-[11px] font-bold uppercase font-sans">
                            <th class="py-3 px-4 text-center border-r border-white/20 last:border-r-0 w-12">#</th>
                            <th class="py-3 px-4 border-r border-white/20 last:border-r-0">ক্যাশের বিবরণ</th>
                            <th class="py-3 px-4 text-center border-r border-white/20 last:border-r-0">ক্যাশ ইন</th>
                            <th class="py-3 px-4 text-center border-r border-white/20 last:border-r-0">ক্যাশ আউট</th>
                            <th class="py-3 px-4 text-center border-r border-white/20 last:border-r-0 w-28">সময়</th>
                            <th class="py-3 px-4 text-center w-24">বাটন</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800 font-sans text-xs">
                        @forelse($entries as $index => $entry)
                            <tr class="hover:bg-emerald-50/40 dark:hover:bg-emerald-950/10 transition-colors">
                                <td class="py-3.5 px-4 text-center text-gray-500 dark:text-slate-455 font-semibold border-r border-gray-150 dark:border-slate-800 last:border-r-0">
                                    {{ toBanglaNum($entries->firstItem() + $index) }}
                                </td>
                                <td class="py-3.5 px-4 font-bold border-r border-gray-150 dark:border-slate-800 last:border-r-0 {{ $entry->description == 'মোট পেমেন্ট দেওয়া' ? 'text-red-500 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">
                                    {{ $entry->description }}
                                </td>
                                <td class="py-3.5 px-4 text-center font-bold text-emerald-600 dark:text-emerald-400 border-r border-gray-150 dark:border-slate-800 last:border-r-0 font-mono">
                                    {{ $entry->cash_in !== null ? '৳ ' . toBanglaNum(number_format($entry->cash_in)) : '-' }}
                                </td>
                                <td class="py-3.5 px-4 text-center font-bold text-rose-500 border-r border-gray-150 dark:border-slate-800 last:border-r-0 font-mono">
                                    {{ $entry->cash_out !== null ? '৳ ' . toBanglaNum(number_format($entry->cash_out)) : '-' }}
                                </td>
                                <td class="py-3.5 px-4 text-center text-gray-500 dark:text-slate-400 border-r border-gray-150 dark:border-slate-800 last:border-r-0">
                                    {{ $entry->time ? toBanglaNum(date('h:i A', strtotime($entry->time))) : '-' }}
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Edit button triggers popup alert -->
                                        <button type="button" onclick="alert('ক্যাশ খাতা থেকে এডিট করা যাবে না')"
                                                class="inline-flex text-emerald-600 hover:text-emerald-800 hover:scale-110 transition-all cursor-pointer focus:outline-none" title="সম্পাদনা">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                            </svg>
                                        </button>
                                        <!-- Delete button triggers popup alert -->
                                        <button type="button" onclick="alert('ক্যাশ খাতা থেকে ডিলিট করা যাবে না')"
                                                class="inline-flex text-red-500 hover:text-red-755 hover:scale-110 transition-all cursor-pointer focus:outline-none" title="মুছে ফেলুন">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-sm font-semibold text-gray-400 dark:text-slate-500">
                                    কোনো বিবরণ পাওয়া যায়নি।
                                </td>
                            </tr>
                        @endforelse

                        <!-- Total Bottom Summary Row -->
                        @if($entries->count() > 0)
                            <tr class="bg-blue-50/40 dark:bg-blue-950/10 border-t-2 border-gray-200 dark:border-slate-800">
                                <td colspan="2" class="py-3 px-4 font-extrabold text-gray-800 dark:text-white text-right border-r border-gray-150 dark:border-slate-800">
                                    মোট
                                </td>
                                <td class="py-3 px-4 text-center font-black text-emerald-600 dark:text-emerald-400 border-r border-gray-150 dark:border-slate-800 font-mono">
                                    {{ $viewTotalCashIn > 0 ? '৳ ' . toBanglaNum(number_format($viewTotalCashIn)) : '-' }}
                                </td>
                                <td class="py-3 px-4 text-center font-black text-rose-500 border-r border-gray-150 dark:border-slate-800 font-mono">
                                    {{ $viewTotalCashOut > 0 ? '৳ ' . toBanglaNum(number_format($viewTotalCashOut)) : '-' }}
                                </td>
                                <td colspan="2" class="py-3 px-4"></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 px-5 py-4 border-t border-gray-100 dark:border-slate-800">
                <div class="text-xs text-gray-500 dark:text-gray-400 font-sans font-semibold">
                    মোট: <strong class="text-gray-800 dark:text-white">{{ toBanglaNum($entries->total()) }} টি</strong>
                </div>

                <div class="flex items-center gap-4">
                    {{-- Pagination Links --}}
                    {{ $entries->links() }}

                    {{-- Per Page --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" type="button"
                                class="flex items-center justify-between gap-1.5 px-3 py-1.5 bg-white dark:bg-slate-800 text-gray-800 dark:text-white font-bold rounded-lg text-xs border border-gray-200 dark:border-slate-700 cursor-pointer">
                            <span class="font-sans">{{ toBanglaNum($perPage) }} / পেজ</span>
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
                                        {{ toBanglaNum($size) }} / পেজ
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
        
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-150 dark:border-slate-850 p-6 md:p-8 max-w-lg w-full relative overflow-y-auto max-h-[90vh] shadow-2xl">
            <!-- Close button -->
            <button @click="show = false" class="absolute top-4 right-4 p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-400 hover:text-gray-600 dark:hover:text-gray-250 cursor-pointer focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <!-- Title with Graphic icon -->
            <div class="flex items-center gap-3.5 border-b border-gray-100 dark:border-slate-800/80 pb-4 mb-6 text-left">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-950/40 text-[#034C3C] dark:text-emerald-400 flex items-center justify-center flex-shrink-0">
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
                <!-- Description Field -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 mb-1.5 font-sans">হিসাবের বিবরণ <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-3.5 text-[#034C3C] dark:text-emerald-400">
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

                <!-- Cash In & Cash Out Grid -->
                <div class="grid grid-cols-2 gap-4">
                    <!-- Cash In -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 mb-1.5 font-sans">ক্যাশ ইন</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-3 text-sm font-extrabold text-emerald-600 dark:text-emerald-400">৳</span>
                            <input type="number" wire:model="cashIn" placeholder="০"
                                   class="w-full pl-7 pr-3 py-2.5 text-xs font-bold font-mono rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-55 dark:bg-slate-800 text-gray-808 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                        </div>
                        @error('cashIn')
                            <p class="text-red-500 text-[10px] mt-1 font-semibold font-sans">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cash Out -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 mb-1.5 font-sans">ক্যাশ আউট</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-3 text-sm font-extrabold text-red-500">৳</span>
                            <input type="number" wire:model="cashOut" placeholder="০"
                                   class="w-full pl-7 pr-3 py-2.5 text-xs font-bold font-mono rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-55 dark:bg-slate-800 text-gray-808 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                        </div>
                        @error('cashOut')
                            <p class="text-red-500 text-[10px] mt-1 font-semibold font-sans">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Date & Time Hidden or Editable Fields -->
                <div class="grid grid-cols-2 gap-4">
                    <!-- Date with Flatpickr picker -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 mb-1.5 font-sans">তারিখ <span class="text-red-500">*</span></label>
                        <div class="relative flex items-center">
                            <input type="text"
                                   data-flatpickr
                                   data-wire-prop="date"
                                   data-default="{{ $date }}"
                                   wire:model="date"
                                   placeholder="তারিখ"
                                   readonly
                                   class="w-full pl-3 pr-8 py-2.5 text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-55 dark:bg-slate-800 text-gray-808 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all font-sans font-semibold cursor-pointer">
                            <span class="absolute right-2.5 top-3 text-emerald-500 pointer-events-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            </span>
                        </div>
                        @error('date')
                            <p class="text-red-500 text-[10px] mt-1 font-semibold font-sans">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 mb-1.5 font-sans">সময় <span class="text-red-500">*</span></label>
                        <input type="time" wire:model="time"
                               class="w-full px-3 py-2.5 text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-55 dark:bg-slate-800 text-gray-808 dark:text-white font-sans font-semibold focus:outline-none">
                        @error('time')
                            <p class="text-red-500 text-[10px] mt-1 font-semibold font-sans">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Footer Action Buttons -->
            <div class="flex items-center justify-end gap-3.5 mt-8 pt-5 border-t border-gray-100 dark:border-slate-800/80">
                <button type="button" wire:click="resetForm()"
                        class="px-5 py-2.5 border border-gray-200 dark:border-slate-800 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-white text-xs font-bold rounded-xl transition-all cursor-pointer">
                    ক্লিয়ার
                </button>
                <button type="button" wire:click="save()"
                        class="px-6 py-2.5 bg-[#034C3C] hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-all cursor-pointer shadow-md shadow-emerald-500/25 active:scale-95">
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
                ইনভেস্টমেন্ট এর হিসাব
            </h2>

            <!-- Empty State Table -->
            <div class="border border-gray-150 dark:border-slate-800 rounded-2xl overflow-hidden mb-6">
                <table class="w-full border-collapse border border-gray-200 dark:border-slate-800">
                    <thead>
                        <tr class="bg-[#034C3C] text-white text-xs">
                            <th class="py-2.5 px-3 text-xs font-bold text-center border-r border-white/20 last:border-r-0">উৎস</th>
                            <th class="py-2.5 px-3 text-xs font-bold text-center border-r border-white/20 last:border-r-0">ইনভেস্ট (ক্যাশ ইন)</th>
                            <th class="py-2.5 px-3 text-xs font-bold text-center border-r border-white/20 last:border-r-0">রিটার্ন (ক্যাশ আউট)</th>
                            <th class="py-2.5 px-3 text-xs font-bold text-center border-r border-white/20 last:border-r-0">ব্যালেন্স</th>
                            <th class="py-2.5 px-3 text-xs font-bold text-center w-24">প্রিন্ট</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="5" class="py-16 text-center">
                                <div class="flex flex-col items-center justify-center space-y-2.5 text-gray-400">
                                    <span class="text-3xl">📥</span>
                                    <p class="text-xs font-bold font-sans">No data</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Action Button -->
            <div class="text-center">
                <button type="button" class="px-5 py-2.5 bg-white hover:bg-gray-50 border border-[#034C3C] text-[#034C3C] dark:bg-slate-900 dark:border-emerald-500 dark:text-emerald-400 dark:hover:bg-slate-800 text-xs font-bold rounded-xl transition-all cursor-pointer inline-flex items-center gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/>
                    </svg>
                    ক্যাশ ইন-আউট এর সকল রেকর্ড প্রিন্ট করুন
                </button>
            </div>
        </div>
    </div>
</div>
