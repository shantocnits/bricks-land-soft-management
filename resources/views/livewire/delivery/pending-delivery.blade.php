<div class="min-h-screen bg-gray-50 dark:bg-slate-950 transition-colors duration-300">
    <!-- Page Header Bar -->
    <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 px-4 sm:px-6 py-3 rounded-lg flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 transition-colors duration-300">
        <div>
            <h2 class="font-bold text-gray-800 dark:text-white text-sm leading-tight">আজ ডেলিভারি যাবে (বাকি ডেলিভারি)</h2>
            <p class="text-[10px] text-gray-400 dark:text-gray-500 font-sans mt-0.5 font-semibold">সকল চালান যার ডেলিভারি এখনো সম্পন্ন হয়নি</p>
        </div>

        <!-- Top Actions -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3 flex-wrap">
            <div class="grid grid-cols-2 gap-2 w-full sm:flex sm:items-center sm:gap-3 sm:w-auto">
                <!-- Search -->
                <div class="relative col-span-1">
                    <input type="text" wire:model.live="search" placeholder="সার্চ করুন..."
                           class="pl-4 pr-4 py-2 text-xs rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/10 transition-all w-full sm:w-48 font-sans font-semibold">
                </div>

                <!-- Single Date filter -->
                <div class="relative flex items-center col-span-1">
                    <input type="text"
                           data-flatpickr
                           data-wire-prop="dateFilter"
                           data-default="{{ $dateFilter }}"
                           wire:model="dateFilter"
                           placeholder="চালানের তারিখ ফিল্টার"
                           readonly
                           class="pl-3 pr-8 py-2 text-xs rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/10 transition-all w-full sm:w-44 font-sans font-semibold cursor-pointer">
                    <span class="absolute right-2.5 top-2 text-primary-500 pointer-events-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </span>
                </div>
            </div>

            <!-- Report Button -->
            <button type="button" wire:click="$set('showReportModal', true)"
                    class="px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl cursor-pointer transition-all font-sans flex items-center justify-center gap-1.5 shadow-sm active:scale-95 w-full sm:w-auto">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                রিপোর্ট
            </button>
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
            <!-- Responsive Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse border border-gray-200 dark:border-slate-800" style="min-width: 1000px">
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
                            <th class="px-3 py-3 text-right border-r border-white/20">মোট বাকি</th>
                            <th class="px-3 py-3 text-center">বাটন</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs font-semibold text-gray-700 dark:text-slate-350 divide-y divide-gray-100 dark:divide-slate-800">
                        @forelse ($items as $item)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition-all font-sans">
                                <td class="px-3 py-3.5 font-mono text-[11px] text-gray-550 border-r border-gray-100 dark:border-slate-800">{{ $item->challan->challan_no ?? '' }}</td>
                                <td class="px-3 py-3.5 border-r border-gray-100 dark:border-slate-800 text-gray-900 dark:text-white font-bold">{{ $item->challan->customer_name ?? '' }}</td>
                                <td class="px-3 py-3.5 text-gray-500 border-r border-gray-100 dark:border-slate-800">{{ $item->challan->customer_address ?? '' }}</td>
                                <td class="px-3 py-3.5 border-r border-gray-100 dark:border-slate-800">
                                    <span class="px-2 py-0.5 rounded bg-primary-50 dark:bg-primary-950/30 text-primary-dark dark:text-primary-400 text-[10px]">{{ $item->category_name }}</span>
                                </td>
                                <td class="px-3 py-3.5 text-right font-mono border-r border-gray-100 dark:border-slate-800">{{ number_format($item->quantity) }}</td>
                                <td class="px-3 py-3.5 text-right font-mono border-r border-gray-100 dark:border-slate-800 text-blue-600 dark:text-blue-450">{{ number_format($item->delivered_quantity) }}</td>
                                <td class="px-3 py-3.5 text-right font-mono border-r border-gray-100 dark:border-slate-800 text-primary dark:text-primary-light font-bold">
                                    {{ number_format(max(0, $item->quantity - $item->delivered_quantity)) }}
                                </td>
                                <td class="px-3 py-3.5 text-right font-mono border-r border-gray-100 dark:border-slate-800 text-red-600 dark:text-red-450 font-bold">
                                    ৳ {{ number_format($item->challan->due ?? 0) }}
                                </td>
                                <td class="px-3 py-3.5 text-center">
                                    <button type="button" wire:click="openDeliveryModal({{ $item->id }})"
                                            class="px-2.5 py-1 bg-primary hover:bg-primary-dark text-white rounded-lg text-[10px] font-bold cursor-pointer transition-all active:scale-95">
                                        ডেলিভারি দিন
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-5 py-8 text-center text-gray-400 dark:text-gray-500 font-medium">কোনো চালানের ডেলিভারি বাকি নেই।</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Dynamic Bottom Footer: Pagination & Per Page selection -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 px-5 py-4 border-t border-gray-100 dark:border-slate-800">
                <div class="text-xs text-gray-500 dark:text-gray-400 font-sans font-semibold">
                    মোট চালান {{ $items->total() }} টি
                </div>
                
                <div class="flex items-center gap-4">
                    <!-- Pagination Numbers -->
                    <div class="flex items-center gap-1">
                        @if ($items->onFirstPage())
                            <button type="button" class="p-1.5 rounded-lg border border-gray-200 dark:border-slate-800 text-gray-300 dark:text-slate-700 cursor-not-allowed" disabled>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                            </button>
                        @else
                            <button type="button" wire:click="previousPage" class="p-1.5 rounded-lg border border-gray-200 dark:border-slate-800 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-350 cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                            </button>
                        @endif

                        @php
                            $startPage = max(1, $items->currentPage() - 2);
                            $endPage = min($items->lastPage(), $items->currentPage() + 2);
                        @endphp

                        @if ($startPage > 1)
                            <button type="button" wire:click="gotoPage(1)" class="px-2.5 py-1 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-300 font-bold rounded-lg text-xs border border-gray-200 dark:border-slate-750 font-mono cursor-pointer">1</button>
                            @if ($startPage > 2)
                                <span class="px-1 text-gray-400">...</span>
                            @endif
                        @endif

                        @for ($page = $startPage; $page <= $endPage; $page++)
                            @if ($page == $items->currentPage())
                                <span class="px-2.5 py-1 bg-primary-50 dark:bg-primary-950 text-[#034C3C] dark:text-primary-400 font-bold rounded-lg text-xs border border-primary-200 dark:border-primary-900 font-mono">{{ $page }}</span>
                            @else
                                <button type="button" wire:click="gotoPage({{ $page }})" class="px-2.5 py-1 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-300 font-bold rounded-lg text-xs border border-gray-200 dark:border-slate-750 font-mono cursor-pointer">{{ $page }}</button>
                            @endif
                        @endfor

                        @if ($endPage < $items->lastPage())
                            @if ($endPage < $items->lastPage() - 1)
                                <span class="px-1 text-gray-400">...</span>
                            @endif
                            <button type="button" wire:click="gotoPage({{ $items->lastPage() }})" class="px-2.5 py-1 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-300 font-bold rounded-lg text-xs border border-gray-200 dark:border-slate-750 font-mono cursor-pointer">{{ $items->lastPage() }}</button>
                        @endif

                        @if ($items->hasMorePages())
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
                            <span>{{ $perPage }} চালান / পেজ</span>
                            <svg class="w-3.5 h-3.5 transition-transform duration-200 text-gray-550" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        <div x-show="open" @click.outside="open = false" class="absolute bottom-full mb-1.5 right-0 z-[999] w-36 bg-white dark:bg-slate-900 border border-gray-205 dark:border-slate-800 rounded-xl shadow-xl overflow-hidden focus:outline-none" x-cloak>
                            <div class="py-1">
                                @foreach ([10, 20, 30, 50] as $size)
                                <button type="button" wire:click="$set('perPage', {{ $size }})" @click="open = false" class="w-full text-left px-3 py-2 text-xs font-bold text-gray-855 dark:text-white hover:bg-primary-50 dark:hover:bg-slate-800 hover:text-primary-dark dark:hover:text-primary-400 transition-colors font-sans">
                                    {{ $size }} চালান / পেজ
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
                            <input type="text" value="{{ number_format((int)$deliveryTotalQty) }}" disabled class="w-full py-2 px-3 bg-gray-100 border border-gray-205 dark:border-slate-800 rounded-xl text-center text-gray-500 dark:bg-slate-900/50 font-sans">
                        </div>
                        <div>
                            <input type="number" wire:model.live="todayDeliveryQty" class="w-full py-2 px-3 bg-white dark:bg-slate-950 border border-gray-300 dark:border-slate-700 rounded-xl text-center text-gray-800 dark:text-white font-bold font-sans focus:ring-2 focus:ring-primary-500/20">
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
                    <button type="button" wire:click="saveDelivery" class="px-6 py-2 bg-primary hover:bg-primary-dark text-white text-xs font-bold rounded-xl cursor-pointer transition-all shadow-md">সেভ + নতুন ডেলিভারি</button>
                    <button type="button" onclick="window.print()" class="px-6 py-2 bg-primary hover:bg-primary-dark text-white text-xs font-bold rounded-xl cursor-pointer transition-all shadow-md flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>সেভ + প্রিন্ট ডেলিভারি</button>
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
                <h3 class="font-bold text-sm font-sans text-gray-800 dark:text-white flex items-center gap-2">বাকি ডেলিভারি রিপোর্ট 📊</h3>
                <button type="button" wire:click="$set('showReportModal', false)" class="p-1.5 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-xl cursor-pointer text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="space-y-4">
                <table class="w-full text-left border-collapse border border-gray-200 dark:border-slate-800">
                    <thead>
                        <tr class="bg-primary text-white text-[10px] font-bold uppercase font-sans">
                            <th class="px-4 py-2 border-r border-white/20">শ্রেণি</th>
                            <th class="px-4 py-2 text-right">বাকি পরিমাণ</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs font-semibold text-gray-700 dark:text-slate-350 divide-y divide-gray-150 dark:divide-slate-800">
                        @forelse($reportData as $row)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-850/30">
                                <td class="px-4 py-2.5 border-r border-gray-100 dark:border-slate-800">{{ $row->category_name }}</td>
                                <td class="px-4 py-2.5 text-right font-mono text-primary dark:text-primary-light font-bold">{{ number_format($row->pending_qty) }} টি</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-4 py-6 text-center text-gray-400 font-medium">কোনো তথ্য নেই।</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="bg-blue-50/40 dark:bg-blue-950/10 border-t border-gray-200 dark:border-slate-800 font-bold text-xs text-gray-800 dark:text-white">
                            <td class="px-4 py-2.5 border-r border-gray-100 dark:border-slate-800">সর্বমোট বাকি</td>
                            <td class="px-4 py-2.5 text-right font-mono text-primary-dark dark:text-primary-400">{{ number_format($totalPendingQty) }} টি</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
