<div class="min-h-screen bg-gray-50 dark:bg-slate-950 transition-colors duration-300">

    <!-- Page Header Bar (Matches TodayChallan exactly, shadow removed) -->
    <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 px-4 sm:px-6 py-3 rounded-lg flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 transition-colors duration-300">
        <div>
            <h2 class="font-bold text-gray-800 dark:text-white text-sm leading-tight">পেমেন্ট খাতা</h2>
            <p class="text-[10px] text-gray-405 dark:text-gray-500 font-sans mt-0.5 font-semibold">পেমেন্ট খাতার বিবরণী</p>
        </div>

        <!-- Top Actions -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3">
            <!-- Search -->
            <div class="relative">
                <input type="text" wire:model.live="search"
                       placeholder="সার্চ করুন..."
                       class="pl-4 pr-4 py-2 text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-55 dark:bg-slate-950 text-gray-855 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all w-full sm:w-52 font-sans font-semibold">
            </div>
            
            <!-- Date picker -->
            <div class="relative flex items-center">
                <input type="text"
                       data-flatpickr
                       data-wire-prop="dateFilter"
                       data-default="{{ $dateFilter }}"
                       wire:model="dateFilter"
                       placeholder="তারিখ"
                       readonly
                       class="pl-3 pr-8 py-2 text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-55 dark:bg-slate-950 text-gray-855 dark:text-white focus:outline-none focus:border-emerald-500/20 transition-all w-44 font-sans font-semibold cursor-pointer">
                <span class="absolute right-2.5 top-2.5 text-emerald-500 pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                </span>
            </div>

            <!-- Report Button -->
            <button type="button" wire:click="$set('showReportModal', true)"
                    class="px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl cursor-pointer transition-all font-sans flex items-center gap-1.5 active:scale-95">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                রিপোর্ট
            </button>

            <!-- Print Button -->
            <button type="button" onclick="window.print()"
                    class="px-3 py-2 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-705 dark:text-slate-200 text-xs font-semibold rounded-xl cursor-pointer transition-all font-sans border border-gray-200 dark:border-slate-700 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                প্রিন্ট
            </button>

            <!-- New Payment Button -->
            <button type="button" wire:click="openAddModal"
                    class="px-4 py-2 bg-[#034C3C] hover:bg-emerald-700 text-white text-xs font-bold rounded-xl cursor-pointer transition-all active:scale-95 font-sans">
                + নতুন পেমেন্ট
            </button>
        </div>
    </div>

    <!-- Table Card Section -->
    <div class="py-4 sm:py-6">
        <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-lg shadow-sm overflow-hidden transition-colors duration-300">
            
            <!-- Summary Bar -->
            <div class="flex items-center justify-between w-full px-5 py-3.5 border-b border-gray-100 dark:border-slate-800 bg-blue-50/40 dark:bg-blue-950/10">
                <span class="text-xs text-gray-500 dark:text-gray-400 font-sans">মোট: <strong class="text-gray-805 dark:text-white font-black">{{ count($payments) }} টি</strong></span>
                <span class="px-3.5 py-1.5 bg-[#034C3C] text-white border border-[#034C3C] rounded-lg text-xs font-black font-sans leading-none shadow-sm">
                    মোট পেমেন্ট: ৳ {{ number_format($totalPaymentsSum) }} টাকা
                </span>
            </div>

            <!-- Desktop View: Responsive Table -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full text-left border-collapse border border-gray-200 dark:border-slate-800" style="min-width: 1050px">
                    <thead>
                        <tr class="bg-emerald-600 text-white text-[11px] font-bold uppercase font-sans">
                            <th class="px-3 py-3 text-center w-10 border-r border-white/20 last:border-r-0">#</th>
                            <th class="px-3 py-3 border-r border-white/20 last:border-r-0 text-center">তারিখ</th>
                            <th class="px-3 py-3 border-r border-white/20 last:border-r-0 text-center">খতিয়ান</th>
                            <th class="px-3 py-3 border-r border-white/20 last:border-r-0 text-center">লেজারের বিবরণ</th>
                            <th class="px-3 py-3 text-right border-r border-white/20 last:border-r-0">পরিমাণ</th>
                            <th class="px-3 py-3 text-right border-r border-white/20 last:border-r-0">রেট</th>
                            <th class="px-3 py-3 text-right border-r border-white/20 last:border-r-0">মোট বিল</th>
                            <th class="px-3 py-3 text-right border-r border-white/20 last:border-r-0">অগ্রিম</th>
                            <th class="px-3 py-3 text-right border-r border-white/20 last:border-r-0">কর্তন</th>
                            <th class="px-3 py-3 text-right border-r border-white/20 last:border-r-0">পেমেন্ট</th>
                            <th class="px-3 py-3 text-right border-r border-white/20 last:border-r-0">ক্রয়/রেশি</th>
                            <th class="px-3 py-3 text-center border-r border-white/20 last:border-r-0 w-12">ডক</th>
                            <th class="px-3 py-3 text-center w-24">বাটন</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800 font-sans">
                        @forelse ($payments as $idx => $pay)
                            <tr class="hover:bg-emerald-50/40 dark:hover:bg-emerald-950/10 transition-colors text-xs">
                                <td class="px-3 py-3.5 text-center text-gray-500 dark:text-slate-455 font-semibold border-r border-gray-150 dark:border-slate-800 last:border-r-0">{{ $idx + 1 }}</td>
                                <td class="px-3 py-3.5 text-center text-gray-500 dark:text-slate-400 whitespace-nowrap border-r border-gray-150 dark:border-slate-800 last:border-r-0">{{ $pay['date'] ?? '18/07/2026' }}</td>
                                <td class="px-3 py-3.5 text-center font-bold text-[#034C3C] dark:text-emerald-400 border-r border-gray-150 dark:border-slate-800 last:border-r-0">{{ $pay['ledger'] }}</td>
                                <td class="px-3 py-3.5 text-center text-gray-600 dark:text-slate-400 border-r border-gray-150 dark:border-slate-800 last:border-r-0 max-w-[220px] truncate" title="{{ $pay['desc'] }}">{{ $pay['desc'] }}</td>

                                <td class="px-3 py-3.5 text-right font-semibold text-gray-600 dark:text-slate-400 border-r border-gray-150 dark:border-slate-800 last:border-r-0 font-mono">{{ number_format($pay['qty']) }}</td>
                                <td class="px-3 py-3.5 text-right font-semibold text-gray-600 dark:text-slate-400 border-r border-gray-150 dark:border-slate-800 last:border-r-0 font-mono">৳ {{ number_format($pay['rate']) }}</td>

                                <td class="px-3 py-3.5 text-right font-bold text-gray-900 dark:text-white border-r border-gray-150 dark:border-slate-800 last:border-r-0 font-mono">৳ {{ number_format($pay['total']) }}</td>
                                <td class="px-3 py-3.5 text-right font-semibold text-amber-600 border-r border-gray-150 dark:border-slate-800 last:border-r-0 font-mono">৳ {{ number_format($pay['advance']) }}</td>
                                <td class="px-3 py-3.5 text-right font-semibold text-red-500 border-r border-gray-150 dark:border-slate-800 last:border-r-0 font-mono">৳ {{ number_format($pay['deduction']) }}</td>
                                <td class="px-3 py-3.5 text-right font-black text-emerald-600 dark:text-emerald-400 border-r border-gray-150 dark:border-slate-800 last:border-r-0 font-mono">৳ {{ number_format($pay['payment']) }}</td>
                                <td class="px-3 py-3.5 text-right font-bold text-gray-900 dark:text-white border-r border-gray-150 dark:border-slate-800 last:border-r-0 font-mono">৳ {{ number_format($pay['purchase_receive']) }}</td>
                                <td class="px-3 py-3.5 text-center border-r border-gray-150 dark:border-slate-800 last:border-r-0">
                                    @if ($pay['has_doc'])
                                        <a href="{{ $pay['doc_url'] }}" target="_blank" class="inline-flex text-emerald-600 hover:text-emerald-700 hover:scale-110 transition-transform" title="ডকুমেন্ট দেখুন">
                                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                            </svg>
                                        </a>
                                    @else
                                        <span class="text-gray-350 dark:text-slate-850 text-[10px]">-</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3.5 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button 
                                            wire:click="editPayment({{ $pay['id'] }})"
                                            class="inline-flex text-indigo-600 hover:text-indigo-850 hover:scale-110 transition-all cursor-pointer focus:outline-none"
                                            title="সম্পাদনা">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                            </svg>
                                        </button>

                                        <a href="/khotian?selectedLedger={{ urlencode($pay['ledger']) }}"
                                           wire:navigate
                                           class="inline-flex text-emerald-600 hover:text-emerald-800 hover:scale-110 transition-all cursor-pointer focus:outline-none"
                                           title="খতিয়ান">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                            </svg>
                                        </a>

                                        <button 
                                            wire:click="confirmDelete({{ $pay['id'] }})"
                                            class="inline-flex text-red-500 hover:text-red-755 hover:scale-110 transition-all cursor-pointer focus:outline-none"
                                            title="ডিলিট করুন">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="px-5 py-12 text-center text-gray-400 font-sans text-sm">
                                    কোনো পেমেন্ট রেকর্ড খুঁজে পাওয়া যায়নি।
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile View: Box Type Layout -->
            <div class="lg:hidden p-4 grid grid-cols-1 sm:grid-cols-2 gap-4 bg-gray-50/50 dark:bg-slate-950/20">
                @forelse ($payments as $pay)
                    <div class="bg-gray-50/80 dark:bg-slate-900 p-4 rounded-xl border border-gray-200 dark:border-slate-800 shadow-sm space-y-3 transition-colors">
                        <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-800/60 pb-2">
                            <span class="text-xs font-black text-[#034C3C] dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-955/40 px-2.5 py-1 rounded-md">{{ $pay['ledger'] }}</span>
                            <span class="text-sm font-black text-emerald-600 dark:text-emerald-400 font-mono">৳ {{ number_format($pay['payment']) }}</span>
                        </div>

                        <p class="text-xs text-gray-555 dark:text-slate-400 leading-relaxed font-sans">{{ $pay['desc'] }}</p>

                        <div class="grid grid-cols-2 gap-2 text-[11px] bg-gray-100 dark:bg-slate-955 p-2.5 rounded-lg border border-gray-150 dark:border-slate-800/60 font-mono">
                            <div>
                                <span class="text-gray-450 block uppercase text-[9px] font-sans font-bold">তারিখ</span>
                                <span class="text-gray-650 dark:text-slate-400 font-semibold">{{ $pay['date'] ?? '18/07/2026' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-455 block uppercase text-[9px] font-sans font-bold">মোট বিল</span>
                                <span class="text-gray-655 dark:text-slate-400 font-bold">৳ {{ number_format($pay['total']) }}</span>
                            </div>
                            <div class="mt-1">
                                <span class="text-gray-455 block uppercase text-[9px] font-sans font-bold">পরিমাণ @ রেট</span>
                                <span class="text-gray-655 dark:text-slate-400 font-semibold">{{ number_format($pay['qty']) }} @ ৳ {{ number_format($pay['rate']) }}</span>
                            </div>
                            <div class="mt-1">
                                <span class="text-gray-455 block uppercase text-[9px] font-sans font-bold">ক্রয়/রেশি</span>
                                <span class="text-gray-655 dark:text-slate-400 font-semibold">৳ {{ number_format($pay['purchase_receive']) }}</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-2 border-t border-gray-100 dark:border-slate-800/40 text-[11px] font-sans">
                            <div class="flex items-center gap-2">
                                @if ($pay['has_doc'])
                                    <a href="{{ $pay['doc_url'] }}" target="_blank" class="inline-flex text-emerald-600 hover:text-emerald-700 font-bold items-center gap-1">
                                        📂 ফাইল দেখুন
                                    </a>
                                @else
                                    <span class="text-gray-400">কোন ফাইল নেই</span>
                                @endif
                            </div>

                            <div class="flex items-center gap-3">
                                <button 
                                    wire:click="editPayment({{ $pay['id'] }})"
                                    class="text-indigo-600 hover:text-indigo-850 font-bold flex items-center gap-0.5 cursor-pointer focus:outline-none">
                                    📝 এডিট
                                </button>
                                <button 
                                    wire:click="confirmDelete({{ $pay['id'] }})"
                                    class="text-red-500 hover:text-red-755 font-bold flex items-center gap-0.5 cursor-pointer focus:outline-none">
                                    🗑️ মুছুন
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-10 text-center text-gray-400 font-sans text-xs col-span-full">
                        কোনো পেমেন্ট রেকর্ড খুঁজে পাওয়া যায়নি।
                    </div>
                @endforelse
            </div>

            <!-- Dynamic Bottom Footer: Pagination & Per Page selection -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 px-5 py-4 border-t border-gray-100 dark:border-slate-800">
                <!-- Dynamic Info text -->
                <div class="text-xs text-gray-500 dark:text-gray-455 font-sans font-semibold">
                    মোট পেমেন্ট {{ count($payments) }} টি | মোট পেমেন্ট {{ number_format($totalPaymentsSum) }} টাকা
                </div>
                
                <!-- Page navigation & Page Size dropdown -->
                <div class="flex items-center gap-4">
                    <!-- Pagination numbers -->
                    <div class="flex items-center gap-1">
                        <button type="button" class="p-1.5 rounded-lg border border-gray-200 dark:border-slate-800 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-450 dark:text-slate-500 cursor-not-allowed">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                        </button>
                        <span class="px-2.5 py-1 bg-emerald-50 dark:bg-emerald-950 text-[#034C3C] dark:text-emerald-400 font-bold rounded-lg text-xs border border-emerald-200 dark:border-emerald-900 font-mono">1</span>
                        <button type="button" class="p-1.5 rounded-lg border border-gray-200 dark:border-slate-800 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-405 dark:text-slate-500 cursor-not-allowed">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                        </button>
                    </div>

                    <!-- Per Page Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" type="button" 
                                class="flex items-center justify-between gap-1.5 px-3 py-1.5 bg-gray-55 dark:bg-slate-800 text-gray-805 dark:text-white font-bold rounded-lg text-xs border border-gray-200 dark:border-slate-700 focus:outline-none transition-all cursor-pointer">
                            <span>{{ $perPage }} পেমেন্ট / পেজ</span>
                            <svg class="w-3.5 h-3.5 transition-transform duration-200 text-gray-550" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        <div x-show="open" 
                             @click.outside="open = false"
                             class="absolute bottom-full mb-1.5 right-0 z-[999] w-36 bg-white dark:bg-slate-900 border border-gray-205 dark:border-slate-800 rounded-xl shadow-xl overflow-hidden focus:outline-none animate-none"
                             x-cloak>
                            <div class="py-1">
                                @foreach ([10, 20, 30, 50] as $size)
                                    <button type="button" 
                                            wire:click="$set('perPage', {{ $size }})"
                                            @click="open = false"
                                            class="w-full text-left px-3 py-2 text-xs font-bold text-gray-805 dark:text-white hover:bg-emerald-50 dark:hover:bg-slate-800 hover:text-emerald-700 dark:hover:text-emerald-400 transition-colors font-sans">
                                        {{ $size }} পেমেন্ট / পেজ
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal 1: "নতুন পেমেন্ট" modal form -->
    <template x-teleport="body">
    <div 
        x-data="{ show: @entangle('showPaymentModal') }"
        x-show="show" 
        @click.self="show = false"
        class="fixed inset-0 z-[99999] bg-black/60 backdrop-blur-sm flex items-center justify-center p-4"
        x-cloak>
        
        <div 
            x-show="show"
            class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-150 dark:border-slate-800 p-6 md:p-8 max-w-2xl w-full relative overflow-y-auto max-h-[90vh]">
            
            <!-- Close Button -->
            <button @click="show = false" class="absolute top-4 right-4 p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-400 hover:text-gray-600 dark:hover:text-gray-250 cursor-pointer focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Modal Title -->
            <h2 class="text-lg md:text-xl font-extrabold text-gray-805 dark:text-white font-sans tracking-wide border-b border-gray-100 dark:border-slate-800 pb-3 mb-5">
                {{ $editingId ? 'পেমেন্ট সংশোধন' : 'নতুন পেমেন্ট' }}
            </h2>

            <!-- Form Content -->
            <div class="space-y-4">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Khotiyan Input Selector -->
                    <div>
                        <label class="block text-xs font-bold text-gray-650 dark:text-slate-350 mb-1.5 font-sans">খতিয়ান <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input 
                                type="text" 
                                readonly
                                placeholder="খতিয়ান নির্বাচন করুন"
                                wire:click="$set('showKhotiyanModal', true)"
                                wire:model="selectedLedger"
                                class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-55 dark:bg-slate-800 text-gray-805 dark:text-white text-sm font-semibold font-sans cursor-pointer focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-450">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                        </div>
                        @error('selectedLedger')
                            <p class="text-red-500 text-xs mt-1.5 font-semibold font-sans">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Payment Type Selection -->
                    <div>
                        <label class="block text-xs font-bold text-gray-655 dark:text-slate-350 mb-1.5 font-sans">পেমেন্টের ধরণ <span class="text-red-500">*</span></label>
                        
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" type="button" 
                                    class="w-full flex items-center justify-between gap-2.5 px-4 py-3 bg-gray-55 dark:bg-slate-800 text-gray-805 dark:text-white font-bold rounded-xl text-sm border border-gray-200 dark:border-slate-700 focus:outline-none transition-all cursor-pointer">
                                <span class="font-sans" x-text="$wire.paymentType ? $wire.paymentType : 'সিলেক্ট করুন'"></span>
                                <svg class="w-4 h-4 transition-transform duration-200 text-gray-550" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            
                            <div x-show="open" 
                                 @click.outside="open = false"
                                 class="absolute z-[99999] mt-1.5 w-full bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl shadow-2xl overflow-hidden focus:outline-none"
                                 x-cloak>
                                <div class="py-1">
                                    @foreach (['রেগুলার', 'অগ্রিম', 'বাকি'] as $type)
                                        <button type="button" 
                                                wire:click="$set('paymentType', '{{ $type }}')"
                                                @click="open = false;"
                                                class="w-full text-left px-4 py-2.5 text-xs font-bold text-gray-855 dark:text-white hover:bg-emerald-50 dark:hover:bg-slate-700 hover:text-emerald-700 dark:hover:text-emerald-400 transition-colors font-sans">
                                            {{ $type }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        @error('paymentType')
                            <p class="text-red-500 text-xs mt-1.5 font-semibold font-sans">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Payment Details Description -->
                <div>
                    <label class="block text-xs font-bold text-gray-600 dark:text-slate-350 mb-1.5 font-sans">পেমেন্টের বিবরণ</label>
                    <textarea 
                        wire:model="paymentDesc"
                        rows="2"
                        placeholder="পেমেন্টের বিস্তারিত বর্ণনা লিখুন"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-55 dark:bg-slate-800 text-gray-808 dark:text-white text-sm font-semibold font-sans focus:outline-none focus:ring-2 focus:ring-emerald-500/20"></textarea>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <!-- Qty -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 mb-1.5 font-sans">পরিমাণ</label>
                        <input type="number" wire:model.live.debounce.300ms="quantity" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-55 dark:bg-slate-800 text-gray-805 dark:text-white text-xs font-bold font-mono focus:outline-none" placeholder="0">
                    </div>
                    <!-- Rate -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 mb-1.5 font-sans">রেট</label>
                        <input type="number" wire:model.live.debounce.300ms="rate" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-55 dark:bg-slate-800 text-gray-805 dark:text-white text-xs font-bold font-mono focus:outline-none" placeholder="0">
                    </div>
                    <!-- Total Bill -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 mb-1.5 font-sans">মোট বিল</label>
                        <input type="number" step="0.01" wire:model.live.debounce.300ms="totalBill" class="w-full px-3 py-2.5 rounded-xl border border-emerald-300 dark:border-emerald-700/60 bg-emerald-50/40 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400 text-xs font-black font-mono focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20" placeholder="0">
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <!-- Deduction -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 mb-1.5 font-sans">কর্তন</label>
                        <input type="number" wire:model.live.debounce.300ms="deduction" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-55 dark:bg-slate-800 text-gray-805 dark:text-white text-xs font-bold font-mono focus:outline-none" placeholder="0">
                    </div>
                    <!-- Payment -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 mb-1.5 font-sans">পেমেন্ট</label>
                        <input type="number" wire:model.live.debounce.300ms="paymentAmount" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-55 dark:bg-slate-800 text-gray-805 dark:text-white text-xs font-bold font-mono focus:outline-none" placeholder="0">
                        @error('paymentAmount')
                            <p class="text-red-500 text-xs mt-1.5 font-semibold font-sans">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Purchase/Receive -> Payment Kom/Beshi -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 mb-1.5 font-sans">পেমেন্ট কম/বেশি</label>
                        <input type="number" wire:model="purchaseReceive" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-55 dark:bg-slate-800 text-gray-805 dark:text-white text-xs font-bold font-mono focus:outline-none" placeholder="0">
                    </div>
                </div>

                <!-- Document Upload Section -->
                <div>
                    <label class="block text-xs font-bold text-gray-600 dark:text-slate-350 mb-1.5 font-sans">ডকুমেন্ট / মানি রিসিভ্ট</label>
                    <div class="border-2 border-dashed border-gray-200 dark:border-slate-800 rounded-2xl p-6 text-center hover:bg-gray-55 dark:hover:bg-slate-800/40 transition-colors cursor-pointer relative min-h-[120px] flex flex-col items-center justify-center">
                        <input type="file" wire:model="documentFile" class="absolute inset-0 opacity-0 cursor-pointer z-10">
                        
                        @if ($documentFile)
                            <div class="flex items-center gap-3 overflow-hidden z-20">
                                @if (in_array(strtolower($documentFile->getClientOriginalExtension()), ['png', 'jpg', 'jpeg', 'webp']))
                                    <img src="{{ $documentFile->temporaryUrl() }}" class="w-12 h-12 object-cover rounded-lg border border-emerald-250">
                                @else
                                    <span class="w-12 h-12 rounded-lg bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center text-2xl">📄</span>
                                @endif
                                <div class="text-left overflow-hidden">
                                    <p class="text-xs font-bold text-gray-800 dark:text-white truncate font-sans">{{ $documentFile->getClientOriginalName() }}</p>
                                    <p class="text-[10px] text-gray-400 font-sans font-medium">{{ round($documentFile->getSize() / 1024, 1) }} KB</p>
                                </div>
                                <button type="button" @click.stop="$wire.set('documentFile', null)" class="text-red-500 hover:text-red-755 p-1 cursor-pointer focus:outline-none text-xs font-black">
                                    ✕
                                </button>
                            </div>
                        @else
                            <div class="space-y-1.5 text-gray-500 dark:text-slate-400">
                                <span class="text-2xl">☁️</span>
                                <p class="text-xs font-bold font-sans">ফাইল আপলোড করুন বা ড্রপ করুন</p>
                                <p class="text-[10px] text-gray-400 font-sans">(সর্বোচ্চ ৫ এমবি)</p>
                            </div>
                        @endif
                    </div>
                </div>

                @if($editingId)
                    <!-- Payment Date (Shown only in Payment Edit Modal) -->
                    <div>
                        <label class="block text-xs font-bold text-gray-655 dark:text-slate-350 mb-1.5 font-sans">পেমেন্টের তারিখ <span class="text-red-500">*</span></label>
                        <div class="relative flex items-center">
                            <input type="text"
                                   data-flatpickr
                                   data-wire-prop="paymentDate"
                                   data-default="{{ $paymentDate }}"
                                   wire:model="paymentDate"
                                   placeholder="তারিখ"
                                   readonly
                                   class="w-full pl-3 pr-8 py-3 text-sm rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-55 dark:bg-slate-800 text-gray-805 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all font-sans font-semibold cursor-pointer">
                            <span class="absolute right-3.5 top-3.5 text-emerald-500 pointer-events-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            </span>
                        </div>
                    </div>
                @endif

                <!-- Footer Buttons -->
                <div class="flex items-center gap-3.5 pt-4 border-t border-gray-100 dark:border-slate-800/60">
                    <button 
                        type="button"
                        wire:click="resetForm"
                        class="flex-grow py-3 border border-gray-200 dark:border-slate-700 hover:bg-gray-55 dark:hover:bg-slate-800/60 text-gray-650 dark:text-slate-205 font-bold rounded-xl text-xs font-sans transition-all cursor-pointer">
                        ক্লিয়ার
                    </button>
                    <button 
                        type="button"
                        wire:click="submitPayment"
                        class="flex-grow py-3 bg-[#034C3C] hover:bg-emerald-700 text-white font-bold rounded-xl text-xs font-sans transition-all cursor-pointer">
                        {{ $editingId ? 'আপডেট করুন' : 'সেভ করুন' }}
                    </button>
                </div>
            </div>

        </div>
    </div>
    </template>

    <!-- Modal 2: "খতিয়ান নির্বাচন করুন" sub-modal -->
    <template x-teleport="body">
    <div 
        x-data="{ show: @entangle('showKhotiyanModal') }"
        x-show="show" 
        @click.self="show = false"
        class="fixed inset-0 z-[99999]"
        x-cloak>
        
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>

        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div 
                x-show="show"
                class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-150 dark:border-slate-800 p-6 md:p-8 max-w-3xl w-full relative overflow-y-auto max-h-[90vh]">
                
                <!-- Close Button -->
                <button @click="show = false" class="absolute top-4 right-4 p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-400 hover:text-gray-600 dark:hover:text-gray-250 cursor-pointer focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Modal Title -->
                <h2 class="text-lg md:text-xl font-extrabold text-gray-855 dark:text-white font-sans tracking-wide border-b border-gray-100 dark:border-slate-800 pb-3 mb-5">
                    খতিয়ান নির্বাচন করুন
                </h2>

                <!-- Search and Add ledger header line -->
                <div class="flex items-center gap-3 mb-5">
                    <!-- Search bar -->
                    <div class="relative flex-grow">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-450">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </span>
                        <input 
                            type="text" 
                            wire:model.live="khotiyanSearch"
                            class="w-full py-2.5 pl-9 pr-4 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-55 dark:bg-slate-800 text-xs font-semibold text-gray-805 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-250 transition-all font-sans"
                            placeholder="গ্রুপ বা খতিয়ান নাম দিয়ে সার্চ করুন">
                    </div>
                    
                    <!-- Add Ledger button -->
                    <button 
                        type="button"
                        wire:click="openNewKhotiyanModal"
                        class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-black font-sans cursor-pointer transition-colors shrink-0">
                        + নতুন খতিয়ান
                    </button>
                </div>

                <!-- Professional Group Box Cards Grid -->
                <div x-data="{ hoverGroup: null, dropRect: null, staying: false }" class="relative">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 max-h-[420px] overflow-y-auto pr-1">
                        @forelse($groupedLedgers as $groupName => $groupLedgers)
                        @php $hasItems = count($groupLedgers) > 0; @endphp
                        <div 
                            @mouseenter="if ({{ $hasItems ? 'true' : 'false' }}) { hoverGroup = {{ json_encode($groupName) }}; dropRect = $el.getBoundingClientRect(); }"
                            @mouseleave="setTimeout(() => { if (hoverGroup === {{ json_encode($groupName) }} && !staying) hoverGroup = null; }, 150)"
                            class="relative">
                            
                            <!-- Group Box Card -->
                            <div @click="if ({{ $hasItems ? 'true' : 'false' }}) { hoverGroup = (hoverGroup === {{ json_encode($groupName) }} ? null : {{ json_encode($groupName) }}); dropRect = $el.getBoundingClientRect(); } else { $wire.selectLedger({{ json_encode($groupName) }}); hoverGroup = null; showKhotiyanModal = false; }"
                                 class="w-full p-3 bg-slate-50 dark:bg-slate-800/90 hover:bg-emerald-50 dark:hover:bg-emerald-950/30 border border-gray-200 dark:border-slate-700/80 hover:border-emerald-500 dark:hover:border-emerald-500 rounded-2xl cursor-pointer transition-all group/box flex items-center justify-between shadow-2xs relative">
                                
                                <!-- Group Name & Count Badge -->
                                <span class="text-xs font-extrabold text-gray-800 dark:text-slate-100 group-hover/box:text-emerald-700 dark:group-hover/box:text-emerald-400 font-sans leading-tight line-clamp-2">
                                    {{ $groupName }}
                                </span>
                                <span class="shrink-0 text-[10px] bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200/50 dark:border-emerald-800/50 rounded-full px-2 py-0.5 font-black font-mono leading-none ml-1.5">
                                    {{ count($groupLedgers) }}
                                </span>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-4 py-10 text-center text-gray-400 font-sans text-xs">কোনো গ্রুপ পাওয়া যায়নি।</div>
                        @endforelse
                    </div>

                    <!-- Hover Dropdown: shows all khotiyans under this group -->
                    <template x-teleport="body">
                    <div 
                        x-show="hoverGroup !== null"
                        @mouseenter="staying = true"
                        @mouseleave="staying = false; hoverGroup = null"
                        class="fixed z-[9999999] w-64 bg-[#0f1c2e] dark:bg-slate-900 border border-slate-700/80 rounded-2xl shadow-2xl overflow-hidden font-sans"
                        :style="dropRect ? ('left: ' + Math.max(8, Math.min(dropRect.left + dropRect.width/2 - 128, window.innerWidth - 272)) + 'px; top: ' + (dropRect.bottom + 6) + 'px;') : ''"
                        x-cloak>
                        <!-- Header -->
                        <div class="flex items-center justify-between px-3.5 py-2 bg-emerald-700/20 border-b border-slate-700/60">
                            <span class="text-[10px] font-black text-emerald-400 uppercase tracking-wider" x-text="hoverGroup"></span>
                            <span class="text-[9px] text-slate-400 font-sans">খতিয়ান নির্বাচন করুন</span>
                        </div>
                        @foreach($groupedLedgers as $gName => $gLedgers)
                        <div x-show="hoverGroup === {{ json_encode($gName) }}" class="max-h-56 overflow-y-auto py-1">
                            @forelse($gLedgers as $gLedg)
                            <button type="button"
                                    wire:click="selectLedger({{ json_encode($gLedg['name']) }})"
                                    @click="hoverGroup = null; staying = false; showKhotiyanModal = false"
                                    class="w-full flex items-center justify-between px-3.5 py-2 hover:bg-emerald-700/20 transition-colors text-left cursor-pointer group/item">
                                <span class="text-xs font-semibold text-slate-200 group-hover/item:text-emerald-300 font-sans truncate">
                                    {{ $gLedg['name'] }}
                                </span>
                            </button>
                            @empty
                            <div class="px-3 py-3 text-center text-[10px] text-slate-500 italic">কোনো খতিয়ান যুক্ত নেই</div>
                            @endforelse
                        </div>
                        @endforeach
                    </div>
                    </template>
                </div>

            </div>
        </div>
    </div>
    </template>

    <!-- Modal 4: "নতুন খতিয়ান / সম্পাদনা খতিয়ান" modal form -->
    <template x-teleport="body">
    <div 
        x-data="{ show: @entangle('showNewKhotiyanModal') }"
        x-show="show" 
        @click.self="show = false"
        class="fixed inset-0 z-[999999] bg-black/60 backdrop-blur-sm flex items-center justify-center p-4"
        x-cloak>
        
        <div 
            x-show="show"
            class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-150 dark:border-slate-800 p-6 md:p-8 max-w-md w-full relative overflow-y-auto max-h-[90vh]">
            
            <!-- Close Button -->
            <button @click="show = false" class="absolute top-4 right-4 p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-400 hover:text-gray-600 dark:hover:text-gray-250 cursor-pointer focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Modal Title dynamically switches -->
            <h2 class="text-lg md:text-xl font-extrabold text-gray-855 dark:text-white font-sans tracking-wide border-b border-gray-100 dark:border-slate-800 pb-3 mb-5"
                x-text="$wire.editingLedgerOldName ? 'সম্পাদনা খতিয়ান' : 'নতুন খতিয়ান'">
            </h2>

            <!-- Form Content -->
            <div class="space-y-4 text-left">
                <!-- Serial Number field -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 mb-1.5 font-sans">সিরিয়াল</label>
                    <input 
                        type="text" 
                        readonly
                        wire:model="newLedgerSerial"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-100 dark:bg-slate-800 text-gray-500 dark:text-slate-400 text-sm font-semibold font-mono focus:outline-none">
                </div>

                <!-- Name field -->
                <div>
                    <label class="block text-xs font-bold text-gray-600 dark:text-slate-350 mb-1.5 font-sans">খতিয়ানের নাম</label>
                    <input 
                        type="text" 
                        wire:model="newLedgerName"
                        placeholder="খতিয়ানের নাম লিখুন"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-55 dark:bg-slate-800 text-gray-805 dark:text-white text-sm font-semibold font-sans focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-250/20">
                    @error('newLedgerName')
                        <p class="text-red-500 text-xs mt-1.5 font-semibold font-sans">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Group Dropdown: Dynamic with filter/add & Folder Icon (Teleported to Root) -->
                <div class="relative" x-data="{ openGroup: false, search: '', dropRect: null }">
                    <label class="block text-xs font-bold text-gray-655 dark:text-slate-350 mb-1.5 font-sans">খতিয়ানের গ্রুপ</label>
                    <button type="button" @click="openGroup = !openGroup; dropRect = $el.getBoundingClientRect()" 
                            class="w-full flex items-center justify-between py-2.5 px-3 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs font-semibold text-gray-800 dark:text-white focus:outline-none cursor-pointer text-left transition-all">
                        <span x-text="$wire.newLedgerGroup || 'গ্রুপ বাছুন বা টাইপ করুন'" class="truncate"></span>
                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 shrink-0" :class="{ 'rotate-180': openGroup }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    <template x-teleport="body">
                        <div x-show="openGroup" @click.outside="openGroup = false" x-transition
                             class="fixed z-[99999999] bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-gray-100 dark:border-slate-800 overflow-hidden font-sans"
                             :style="dropRect ? ('left: ' + dropRect.left + 'px; top: ' + (dropRect.bottom + 6) + 'px; width: ' + dropRect.width + 'px; position: fixed;') : ''"
                             x-cloak>
                            <!-- Single Filter + Add Input -->
                            <div class="p-2 border-b border-gray-100 dark:border-slate-800 flex items-center gap-1.5">
                                <input type="text" x-model="search" wire:model="newGroupInput"
                                       placeholder="ফিল্টার বা নতুন গ্রুপ..."
                                       class="flex-1 min-w-0 py-1.5 px-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 transition-all font-sans font-medium"
                                       @keydown.enter.prevent="$wire.addGroup(search); search = ''">
                                <button type="button" @click="$wire.addGroup(search); search = ''"
                                        class="px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-all cursor-pointer flex-shrink-0 whitespace-nowrap">
                                    + অ্যাড
                                </button>
                            </div>

                            <!-- Options List with Folder Icons (Delete removed) -->
                            <div class="max-h-60 overflow-y-auto py-1">
                                @foreach($ledgerGroups as $group)
                                    <div class="px-3 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 transition-all cursor-pointer flex items-center gap-2"
                                         x-show="search === '' || {{ json_encode($group) }}.toLowerCase().includes(search.toLowerCase())"
                                         @click="$wire.set('newLedgerGroup', {{ json_encode($group) }}); openGroup = false; search = ''">
                                        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-19.5 0A2.25 2.25 0 004.5 15h15a2.25 2.25 0 002.25-2.25m-19.5 0v.243a2.25 2.25 0 00.864 1.765l.775.62c.39.312.617.781.617 1.274v1.848a2.25 2.25 0 002.25 2.25h10.5a2.25 2.25 0 002.25-2.25v-1.848c0-.493.227-.962.617-1.274l.775-.62a2.25 2.25 0 00.864-1.765V12.75M3.75 6h4.875c.621 0 1.15.402 1.314 1.002L10.3 8.5H20.25A2.25 2.25 0 0122.5 10.75v.75H1.5v-.75A2.25 2.25 0 013.75 6z"/>
                                        </svg>
                                        <span class="text-xs font-semibold text-gray-800 dark:text-white hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-sans block truncate">
                                            {{ $group }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Rate & Divisor side by side -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 dark:text-slate-350 mb-1.5 font-sans">খতিয়ানের রেট</label>
                        <input type="number" step="0.01" wire:model="newLedgerRate" placeholder="0"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-55 dark:bg-slate-800 text-gray-805 dark:text-white text-sm font-semibold font-sans focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-250/20">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 dark:text-slate-350 mb-1.5 font-sans">পরিমাণ ভাজক (যদি থাকে)</label>
                        <input type="number" min="1" wire:model="newLedgerDivisor" placeholder="1"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-55 dark:bg-slate-800 text-gray-805 dark:text-white text-sm font-semibold font-sans focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-250/20">
                    </div>
                </div>

                <!-- Notes section inside New Khotiyan Modal -->
                <div class="p-3.5 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-[11px] text-amber-600 dark:text-amber-400 font-medium font-sans leading-relaxed space-y-1.5">
                    <p><strong>নোট ১:</strong> কত পরিমাণ কাজের জন্য এই রেট দেওয়া হবে, তা 'পরিমাণ ভাজক' ঘরে উল্লেখ করুন। উদাহরণস্বরূপ: প্রোডাকশন রেট যদি ৮০০ টাকা হয় এবং তা ১০০০ ইট কাটার জন্য হয়, তবে পরিমাণ ভাজক হবে ১০০০।</p>
                    <p><strong>নোট ২:</strong> যদি কাজ পিস (Piece) বা একক অনুযায়ী হয়, তবে পরিমাণ ভাজকের ঘরে ১ দিন। এক্ষেত্রে সিস্টেম স্বয়ংক্রিয়ভাবে প্রতি পিসের রেট অনুযায়ী মোট বিল হিসাব করবে।</p>
                </div>

                <!-- Action buttons -->
                <div class="flex items-center gap-3.5 pt-4 border-t border-gray-100 dark:border-slate-800/60">
                    <button 
                        type="button"
                        @click="show = false"
                        class="flex-grow py-3 border border-gray-200 dark:border-slate-700 hover:bg-gray-55 dark:hover:bg-slate-800/60 text-gray-650 dark:text-slate-205 font-bold rounded-xl text-xs font-sans transition-all cursor-pointer">
                        ক্লিয়ার
                    </button>
                    <button 
                        type="button"
                        wire:click="addLedger"
                        class="flex-grow py-3 bg-[#034C3C] hover:bg-emerald-700 text-white font-bold rounded-xl text-xs font-sans transition-all cursor-pointer">
                        <span x-text="$wire.editingLedgerOldName ? 'আপডেট করুন' : 'অ্যাড করুন'"></span>
                    </button>
                </div>
            </div>

        </div>
    </div>
    </template>

    <!-- Modal 3: "গ্রুপ অনুযায়ী পেমেন্ট রিপোর্ট" -->
    <template x-teleport="body">
        <div x-data="{ open: @entangle('showReportModal') }"
             x-show="open"
             @click.self="open = false"
             class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
             x-cloak>
             
            <div class="bg-white dark:bg-slate-900 rounded-2xl w-full max-w-lg border border-gray-200 dark:border-slate-700 shadow-2xl relative overflow-hidden"
                 x-show="open">

                <!-- Report Header -->
                <div class="bg-emerald-600 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-white font-bold text-base font-sans flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        পেমেন্ট রিপোর্ট
                    </h3>
                    <button type="button" @click="open = false" class="text-white/80 hover:text-white transition-colors cursor-pointer focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Report Body -->
                <div class="p-5">
                    
                    <!-- Dynamic Tab switches in Report -->
                    <div class="flex items-center gap-2 bg-gray-100 dark:bg-slate-800 p-1 rounded-xl mb-4 text-xs font-sans font-semibold">
                        <button type="button" wire:click="$set('reportTab', 'date')" class="flex-1 py-1.5 rounded-lg text-center transition-colors focus:outline-none {{ $reportTab === 'date' ? 'bg-emerald-600 text-white' : 'text-gray-650 dark:text-slate-400 hover:text-gray-805' }}">
                            আজকের পেমেন্ট
                        </button>
                        <button type="button" wire:click="$set('reportTab', 'all')" class="flex-1 py-1.5 rounded-lg text-center transition-colors focus:outline-none {{ $reportTab === 'all' ? 'bg-emerald-600 text-white' : 'text-gray-650 dark:text-slate-400 hover:text-gray-805' }}">
                            সকল পেমেন্ট
                        </button>
                    </div>

                    @php $report = $this->reportData; @endphp

                    <!-- Category Table -->
                    <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-slate-700 mb-5 max-h-[220px] overflow-y-auto pr-1">
                        <table class="w-full text-sm text-left">
                            <thead>
                                <tr class="bg-emerald-600 text-white text-xs font-bold uppercase">
                                    <th class="px-4 py-3 border-r border-white/20">খতিয়ান</th>
                                    <th class="px-4 py-3 text-center border-r border-white/20">পেমেন্ট সংখ্যা</th>
                                    <th class="px-4 py-3 text-right">মোট পেমেন্ট</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                                @forelse($report['rows'] as $row)
                                    <tr class="text-xs hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors">
                                        <td class="px-4 py-3 font-semibold text-[#034C3C] dark:text-emerald-400 border-r border-gray-100 dark:border-slate-800">{{ $row['ledger'] }}</td>
                                        <td class="px-4 py-3 text-center font-semibold text-gray-700 dark:text-slate-350 border-r border-gray-100 dark:border-slate-800">{{ $row['count'] }}</td>
                                        <td class="px-4 py-3 text-right font-bold text-gray-900 dark:text-white font-mono">৳ {{ number_format($row['payment']) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-4 py-8 text-center text-xs text-gray-400 italic">কোনো ডেটা নেই।</td></tr>
                                @endforelse
                                @if(count($report['rows']) > 0)
                                    <tr class="bg-emerald-50 dark:bg-emerald-950/30 text-xs font-bold border-t border-emerald-250 dark:border-emerald-850">
                                        <td class="px-4 py-3 text-emerald-700 dark:text-emerald-400 border-r border-emerald-100 dark:border-emerald-900">মোট</td>
                                        <td class="px-4 py-3 text-center text-emerald-700 dark:text-emerald-400 border-r border-emerald-100 dark:border-emerald-900">{{ $report['count'] }}</td>
                                        <td class="px-4 py-3 text-right text-emerald-700 dark:text-emerald-400 font-mono">৳ {{ number_format($report['total_payment']) }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- Summary Rows -->
                    <div class="space-y-2.5 text-xs font-sans">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-slate-800">
                            <span class="text-gray-650 dark:text-gray-400 font-semibold">মোট বিল</span>
                            <span class="font-bold text-gray-800 dark:text-white font-mono">৳ {{ number_format($report['total_bill']) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-slate-800">
                            <span class="text-amber-600 dark:text-amber-400 font-semibold">অগ্রিম (-)</span>
                            <span class="font-bold text-amber-600 dark:text-amber-400 font-mono">৳ {{ number_format($report['total_advance']) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-slate-800">
                            <span class="text-red-500 font-semibold">কর্তন (-)</span>
                            <span class="font-bold text-red-500 font-mono">৳ {{ number_format($report['total_deduction']) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-slate-800">
                            <span class="text-emerald-600 dark:text-emerald-400 font-bold">মোট পেমেন্ট</span>
                            <span class="font-bold text-emerald-600 dark:text-emerald-400 font-mono">৳ {{ number_format($report['total_payment']) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-650 dark:text-gray-400 font-semibold">মোট ক্রয়/রেশি</span>
                            <span class="font-bold text-gray-800 dark:text-white font-mono">৳ {{ number_format($report['total_purchase_rec']) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!-- Delete Confirmation Modal -->
    <template x-teleport="body">
        <div x-data="{ open: @entangle('confirmingDeleteId') }"
             x-show="open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[999999] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
             x-cloak>
            <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-xs w-full border border-gray-100 dark:border-slate-800 shadow-2xl p-6 text-center space-y-4 font-sans"
                 x-show="open"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-gray-800 dark:text-white">আপনি কি পেমেন্টটি মুছে ফেলতে চান?</h3>
                    <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-1">এই কার্যক্রমটি পরবর্তীতে পুনরুদ্ধার করা যাবে না।</p>
                </div>
                <div class="flex items-center justify-center gap-3 pt-1">
                    <button type="button" wire:click="cancelDelete"
                            class="flex-1 py-2 px-3 bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-200 text-xs font-bold rounded-xl transition-all cursor-pointer">
                        না
                    </button>
                    <button type="button" wire:click="deletePaymentConfirmed"
                            class="flex-1 py-2 px-3 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl transition-all shadow-md cursor-pointer">
                        হ্যাঁ
                    </button>
                </div>
            </div>
        </div>
    </template>

    <!-- Group Delete Confirmation Modal (হ্যাঁ / না) -->
    @if($showDeleteConfirmModal)
    <template x-teleport="body">
    <div class="fixed inset-0 z-[99999999] flex items-center justify-center p-4 bg-black/70 backdrop-blur-xs font-sans">
        <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-xs w-full p-6 border border-gray-100 dark:border-slate-800 shadow-2xl text-center space-y-4">
            <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center mx-auto">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-extrabold text-gray-800 dark:text-white">গ্রুপ মুছে ফেলার নিশ্চিতকরণ</h3>
                <p class="text-xs text-gray-500 dark:text-slate-400 mt-1 font-medium">
                    আপনি কি নিশ্চিতভাবে <span class="font-bold text-rose-600 dark:text-rose-400">'{{ $groupToDelete }}'</span> গ্রুপটি মুছে ফেলতে চান?
                </p>
            </div>
            <div class="flex items-center justify-center gap-3 pt-1">
                <button type="button" wire:click="cancelDeleteGroup"
                        class="flex-1 py-2 px-3 bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-200 text-xs font-bold rounded-xl transition-all cursor-pointer">
                    না (No)
                </button>
                <button type="button" wire:click="deleteGroupConfirmed"
                        class="flex-1 py-2 px-3 bg-rose-600 hover:bg-rose-700 text-white text-xs font-black rounded-xl transition-all shadow-md cursor-pointer">
                    হ্যাঁ (Yes)
                </button>
            </div>
        </div>
    </div>
    </template>
    @endif

</div>
