@php
if (!function_exists('toBanglaNum')) {
    function toBanglaNum($num) {
        $eng = ['0','1','2','3','4','5','6','7','8','9'];
        $bg  = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
        return str_replace($eng, $bg, $num);
    }
}

if (!function_exists('toBanglaDateTime')) {
    function toBanglaDateTime($date) {
        if (!$date) return '';
        $d = $date->format('d-m-Y');
        $hour = (int)$date->format('H');
        $timeStr = $date->format('h:i');
        
        $part = 'সকাল';
        if ($hour >= 12 && $hour < 16) {
            $part = 'দুপুর';
        } elseif ($hour >= 16 && $hour < 18) {
            $part = 'বিকাল';
        } elseif ($hour >= 18 && $hour < 20) {
            $part = 'সন্ধ্যা';
        } elseif ($hour >= 20 || $hour < 5) {
            $part = 'রাত';
        } else {
            $part = 'সকাল';
        }
        
        return toBanglaNum($d) . ' (' . $part . ' ' . toBanglaNum($timeStr) . ')';
    }
}
@endphp

<div class="w-full">
    <!-- View 1: Main Dashboard (স্টক খাতা) -->
    @if($view === 'stock')
        {{-- Header Card --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 p-5 rounded-3xl mb-6 shadow-sm transition-colors duration-300">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-emerald-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-500/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-black text-gray-800 dark:text-white font-sans">স্টক খাতা</h2>
                    <p class="text-[10px] text-gray-500 dark:text-gray-400 font-sans font-semibold mt-0.5">ভাটার সকল ইটের সামগ্রিক স্টক এবং হিসাব বিবরণী</p>
                </div>
            </div>
            <button type="button" wire:click="$set('view', 'update')"
                    class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-all cursor-pointer shadow-md shadow-emerald-500/20 flex items-center gap-1.5 active:scale-95">
                আপডেট স্টক »
            </button>
        </div>

        {{-- Section Title --}}
        <h3 class="text-xs font-black text-gray-800 dark:text-white font-sans mb-3.5 pl-1 uppercase tracking-wide">ইটের হিসাব ও স্টকের মূল্য</h3>

        {{-- Primary Grid Table --}}
        <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-3xl overflow-hidden shadow-sm mb-6">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-xs font-sans border border-gray-200 dark:border-slate-800">
                    <thead>
                        <tr class="bg-[#034C3C] text-white">
                            <th class="py-3.5 px-4 text-left font-bold border-r border-white/10">শ্রেণি</th>
                            @foreach($brickCategories as $cat)
                                <th class="py-3.5 px-4 text-center font-bold border-r border-white/10">{{ $cat->name }}</th>
                            @endforeach
                            <th class="py-3.5 px-4 text-center font-bold">মোট</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-150 dark:divide-slate-800">
                        {{-- Row 1: মোট স্টক --}}
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/20 transition-colors {{ $subTab === 'brick_calculation' ? 'bg-emerald-50/20 dark:bg-emerald-950/10' : '' }}">
                            <td class="py-3.5 px-4 font-bold text-rose-600 dark:text-rose-400 border-r border-gray-150 dark:border-slate-800">মোট স্টক</td>
                            @foreach($brickCategories as $cat)
                                <td class="py-3.5 px-4 text-center font-mono font-bold border-r border-gray-150 dark:border-slate-800 {{ $brickStockData[$cat->name]['total_stock'] < 0 ? 'text-rose-500' : 'text-gray-800 dark:text-slate-200' }}">
                                    {{ toBanglaNum(number_format($brickStockData[$cat->name]['total_stock'])) }}
                                </td>
                            @endforeach
                            <td class="py-3.5 px-4 text-center font-mono font-black {{ $totalStockSum < 0 ? 'text-rose-500' : 'text-rose-600 dark:text-rose-400' }}">
                                {{ toBanglaNum(number_format($totalStockSum)) }}
                            </td>
                        </tr>

                        {{-- Row 2: ডেলিভারি বাকি --}}
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/20 transition-colors">
                            <td class="py-3.5 px-4 font-bold text-gray-700 dark:text-slate-300 border-r border-gray-150 dark:border-slate-800">ডেলিভারি বাকি</td>
                            @foreach($brickCategories as $cat)
                                <td class="py-3.5 px-4 text-center font-mono font-bold border-r border-gray-150 dark:border-slate-800 text-gray-700 dark:text-slate-300">
                                    {{ toBanglaNum(number_format($brickStockData[$cat->name]['delivery_remaining'])) }}
                                </td>
                            @endforeach
                            <td class="py-3.5 px-4 text-center font-mono font-black text-gray-800 dark:text-slate-200">
                                {{ toBanglaNum(number_format($deliveryRemainingSum)) }}
                            </td>
                        </tr>

                        {{-- Row 3: আসল স্টক --}}
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/20 transition-colors {{ $subTab === 'brick_calculation' ? 'bg-amber-50/20 dark:bg-amber-950/10' : '' }}">
                            <td class="py-3.5 px-4 font-bold text-amber-600 dark:text-amber-400 border-r border-gray-150 dark:border-slate-800">আসল স্টক</td>
                            @foreach($brickCategories as $cat)
                                <td class="py-3.5 px-4 text-center font-mono font-bold border-r border-gray-150 dark:border-slate-800 {{ $brickStockData[$cat->name]['real_stock'] < 0 ? 'text-rose-500' : 'text-amber-600 dark:text-amber-400' }}">
                                    {{ toBanglaNum(number_format($brickStockData[$cat->name]['real_stock'])) }}
                                </td>
                            @endforeach
                            <td class="py-3.5 px-4 text-center font-mono font-black {{ $realStockSum < 0 ? 'text-rose-500' : 'text-amber-600 dark:text-amber-400' }}">
                                {{ toBanglaNum(number_format($realStockSum)) }}
                            </td>
                        </tr>

                        {{-- Row 4: স্টক মূল্য --}}
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/20 transition-colors {{ $subTab === 'stock_value' ? 'bg-emerald-50/20 dark:bg-emerald-950/10' : '' }}">
                            <td class="py-3.5 px-4 font-bold text-emerald-600 dark:text-emerald-400 border-r border-gray-150 dark:border-slate-800">স্টক মূল্য</td>
                            @foreach($brickCategories as $cat)
                                <td class="py-3.5 px-4 text-center font-mono font-bold border-r border-gray-150 dark:border-slate-800 {{ $brickStockData[$cat->name]['stock_price'] < 0 ? 'text-rose-500' : 'text-emerald-600 dark:text-emerald-400' }}">
                                    ৳ {{ toBanglaNum(number_format($brickStockData[$cat->name]['stock_price'])) }}
                                </td>
                            @endforeach
                            <td class="py-3.5 px-4 text-center font-mono font-black {{ $stockPriceSum < 0 ? 'text-rose-500' : 'text-emerald-600 dark:text-emerald-400' }}">
                                ৳ {{ toBanglaNum(number_format($stockPriceSum)) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Bottom Details Dashboard Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            {{-- Left column: Adla and Raw stock cards --}}
            <div class="lg:col-span-4 space-y-6">
                <!-- 1. Adla stock card -->
                <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-3xl overflow-hidden shadow-sm">
                    <div class="bg-[#034C3C] text-white py-3 px-4 text-center font-bold text-xs font-sans">
                        মোট আধলা স্টক
                    </div>
                    <div class="p-4 space-y-3 font-sans text-xs">
                        <div class="flex items-center justify-between py-1.5 border-b border-gray-150 dark:border-slate-800/50">
                            <span class="text-gray-500 dark:text-slate-400">সর্বমোট আধলা (লোড-আনলোড)</span>
                            <span class="font-mono font-bold text-gray-800 dark:text-white">{{ toBanglaNum(number_format($adlaTotalStock)) }}</span>
                        </div>
                        <div class="flex items-center justify-between py-1.5 border-b border-gray-150 dark:border-slate-800/50">
                            <span class="text-gray-500 dark:text-slate-400">আধলা ডেলিভারি</span>
                            <span class="font-mono font-bold text-gray-800 dark:text-white">{{ toBanglaNum(number_format($adlaDelivered)) }}</span>
                        </div>
                        <div class="flex items-center justify-between py-1.5">
                            <span class="text-emerald-600 dark:text-emerald-400 font-bold">আধলা স্টক রয়েছে</span>
                            <span class="font-mono font-black text-emerald-600 dark:text-emerald-400">{{ toBanglaNum(number_format($adlaRemainingStock)) }}</span>
                        </div>
                    </div>
                </div>

                <!-- 2. Raw Brick stock card -->
                <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-3xl overflow-hidden shadow-sm">
                    <div class="bg-[#034C3C] text-white py-3 px-4 text-center font-bold text-xs font-sans">
                        কাঁচা ইট স্টক
                    </div>
                    <div class="p-4 space-y-3 font-sans text-xs">
                        <div class="flex items-center justify-between py-1.5 border-b border-gray-150 dark:border-slate-800/50">
                            <span class="text-gray-500 dark:text-slate-400">মাঠে ইট রয়েছে</span>
                            <span class="font-mono font-bold text-gray-800 dark:text-white">{{ toBanglaNum(number_format($fieldBricksRemaining)) }}</span>
                        </div>
                        <div class="flex items-center justify-between py-1.5 border-b border-gray-150 dark:border-slate-800/50">
                            <span class="text-gray-500 dark:text-slate-400">ইঁটে রয়েছে</span>
                            <span class="font-mono font-bold {{ $kilnBricksRemaining < 0 ? 'text-rose-500' : 'text-gray-800 dark:text-white' }}">{{ toBanglaNum(number_format($kilnBricksRemaining)) }}</span>
                        </div>
                        <div class="flex items-center justify-between py-1.5">
                            <span class="text-emerald-600 dark:text-emerald-400 font-bold">মোট কাঁচা ইট রয়েছে</span>
                            <span class="font-mono font-black text-emerald-600 dark:text-emerald-400">{{ toBanglaNum(number_format($totalRawBricksRemaining)) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right column: Adjustment updates table --}}
            <div class="lg:col-span-8">
                <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-3xl overflow-visible shadow-sm flex flex-col h-full">
                    <div class="bg-[#034C3C] text-white py-3 px-4 font-bold text-xs font-sans rounded-t-3xl">
                        স্টক আপডেটের রেকর্ড
                    </div>
                    <div class="flex-grow overflow-x-auto rounded-b-3xl">
                        <table class="w-full border-collapse text-xs font-sans border border-gray-200 dark:border-slate-800">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-slate-800 border-b border-gray-150 dark:border-slate-800 text-gray-700 dark:text-slate-200">
                                    <th class="py-3 px-4 text-left font-bold border-r border-gray-150 dark:border-slate-800">তারিখ</th>
                                    <th class="py-3 px-4 text-left font-bold border-r border-gray-150 dark:border-slate-800">বিবরণ</th>
                                    <th class="py-3 px-4 text-left font-bold border-r border-gray-150 dark:border-slate-800">শ্রেণি</th>
                                    <th class="py-3 px-4 text-center font-bold border-r border-gray-150 dark:border-slate-800">স্টক ++</th>
                                    <th class="py-3 px-4 text-center font-bold">স্টক --</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-150 dark:divide-slate-800/50">
                                @forelse($adjustments as $adj)
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/20 transition-colors">
                                        <td class="py-3 px-4 text-gray-600 dark:text-slate-300 border-r border-gray-150 dark:border-slate-800 whitespace-nowrap">{{ toBanglaDateTime($adj->date) }}</td>
                                        <td class="py-3 px-4 text-gray-808 dark:text-white font-medium border-r border-gray-150 dark:border-slate-800 max-w-[150px] truncate" title="{{ $adj->description }}">{{ $adj->description ?: '-' }}</td>
                                        <td class="py-3 px-4 font-bold text-gray-808 dark:text-white border-r border-gray-150 dark:border-slate-800">{{ $adj->category_name }}</td>
                                        <td class="py-3 px-4 text-center font-mono font-bold text-emerald-600 dark:text-emerald-400 border-r border-gray-150 dark:border-slate-800">
                                            {{ $adj->stock_plus > 0 ? toBanglaNum(number_format($adj->stock_plus)) : '০' }}
                                        </td>
                                        <td class="py-3 px-4 text-center font-mono font-bold text-rose-500">
                                            {{ $adj->stock_minus > 0 ? toBanglaNum(number_format($adj->stock_minus)) : '০' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-12 text-center text-gray-400">কোনো স্টক আপডেট রেকর্ড খুঁজে পাওয়া যায়নি</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($adjustments->hasPages())
                        <div class="px-5 py-3.5 border-t border-gray-150 dark:border-slate-800 rounded-b-3xl bg-white dark:bg-slate-900">
                            {{ $adjustments->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

    <!-- View 2: Stock Update Record View (স্টক আপডেট রেকর্ড) -->
    @elseif($view === 'update')
        {{-- Header Card --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 p-5 rounded-3xl mb-6 shadow-sm transition-colors duration-300">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-emerald-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-500/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-black text-gray-800 dark:text-white font-sans">স্টক আপডেট রেকর্ড</h2>
                    <p class="text-[10px] text-gray-500 dark:text-gray-400 font-sans font-semibold mt-0.5">ম্যানুয়াল স্টক সংযোজন এবং বিয়োজন রেকর্ড ম্যানেজমেন্ট</p>
                </div>
            </div>

            {{-- Top Left Tabs Toggle inside header --}}
            <div class="flex items-center gap-2">
                <button type="button" wire:click="$set('view', 'stock')"
                        class="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition-all cursor-pointer shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                    </svg>
                    স্টক খাতা
                </button>
                <button type="button" disabled
                        class="flex items-center gap-2 px-4 py-2 border border-emerald-600 dark:border-emerald-500 text-emerald-600 dark:text-emerald-400 font-bold rounded-xl text-xs cursor-default">
                    আপডেট স্টক
                </button>
            </div>
        </div>

        <!-- Success Alert -->
        @if (session()->has('message'))
            <div x-data="{ show: true }" 
                 x-show="show" 
                 x-init="setTimeout(() => show = false, 3000)"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="p-4 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-250 dark:border-emerald-800 text-emerald-800 dark:text-emerald-400 rounded-2xl flex items-center gap-3 text-sm shadow-sm font-sans mb-6"
                 x-cloak>
                <span class="font-medium">{{ session('message') }}</span>
            </div>
        @endif

        {{-- Form Panel --}}
        <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl p-6 shadow-sm transition-colors duration-300 mb-6">
            <form wire:submit.prevent="save" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    {{-- Reason/Description Input --}}
                    <div class="md:col-span-1">
                        <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 mb-1.5 font-sans">স্টক পরিবর্তনের কারণ লিখুন</label>
                        <input type="text" wire:model.defer="description" placeholder="কারণ লিখুন"
                               class="w-full px-3 py-2.5 text-xs font-bold rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 font-sans">
                        @error('description')<p class="text-red-500 text-[10px] mt-1 font-sans">{{ $message }}</p>@enderror
                    </div>

                    {{-- Category Choice Dropdown --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 mb-1.5 font-sans">শ্রেণি <span class="text-red-500">*</span></label>
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" type="button"
                                    class="w-full flex items-center justify-between gap-2 px-4 py-2.5 bg-gray-50 dark:bg-slate-800 text-gray-808 dark:text-white font-semibold rounded-xl text-xs border border-gray-200 dark:border-slate-700 cursor-pointer focus:outline-none">
                                <span class="font-sans truncate text-gray-880 dark:text-white" x-text="$wire.category ? $wire.category : 'শ্রেণি'"></span>
                                <svg class="w-4 h-4 flex-shrink-0 transition-transform text-gray-400" :class="{'rotate-180':open}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <div x-show="open" @click.outside="open=false" x-cloak
                                 class="absolute left-0 right-0 z-[9999] mt-1.5 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl shadow-2xl overflow-hidden font-sans">
                                <div class="py-1 max-h-52 overflow-y-auto font-sans">
                                    @foreach($allCategories as $cat)
                                        <button type="button"
                                                wire:click="$set('category','{{ $cat->name }}')"
                                                @click="open=false"
                                                class="w-full text-left px-4 py-2.5 text-xs font-bold text-gray-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-slate-800 hover:text-emerald-700 dark:hover:text-emerald-400 transition-colors font-sans cursor-pointer">
                                            {{ $cat->name }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @error('category')<p class="text-red-500 text-[10px] mt-1 font-sans">{{ $message }}</p>@enderror
                    </div>

                    {{-- Stock Increase (স্টক ++) --}}
                    <div>
                        <label class="block text-xs font-bold text-emerald-600 dark:text-emerald-400 mb-1.5 font-sans">স্টক ++</label>
                        <input type="number" wire:model.defer="stock_plus" placeholder="যোগ করুন"
                               class="w-full px-3 py-2.5 text-xs font-bold font-mono rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 text-gray-808 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                        @error('stock_plus')<p class="text-red-500 text-[10px] mt-1 font-sans">{{ $message }}</p>@enderror
                    </div>

                    {{-- Stock Decrease (স্টক --) --}}
                    <div>
                        <label class="block text-xs font-bold text-rose-500 mb-1.5 font-sans">স্টক --</label>
                        <div class="flex gap-2">
                            <div class="flex-1">
                                <input type="number" wire:model.defer="stock_minus" placeholder="বিয়োগ করুন"
                                       class="w-full px-3 py-2.5 text-xs font-bold font-mono rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 text-gray-808 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                                @error('stock_minus')<p class="text-red-505 text-[10px] mt-1 font-sans">{{ $message }}</p>@enderror
                            </div>
                            <button type="submit"
                                    class="px-5 py-2.5 bg-[#034C3C] hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-all cursor-pointer shadow-md active:scale-95 whitespace-nowrap">
                                সেভ করুন
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- Adjustment list record table --}}
        <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-3xl overflow-visible shadow-sm flex flex-col">
            <div class="overflow-x-auto rounded-t-3xl">
                <table class="w-full border-collapse text-xs font-sans border border-gray-200 dark:border-slate-800">
                    <thead>
                        <tr class="bg-[#034C3C] text-white">
                            <th class="py-3.5 px-4 text-left font-bold border-r border-white/10">তারিখ</th>
                            <th class="py-3.5 px-4 text-left font-bold border-r border-white/10">বিবরণ</th>
                            <th class="py-3.5 px-4 text-left font-bold border-r border-white/10">শ্রেণি</th>
                            <th class="py-3.5 px-4 text-center font-bold border-r border-white/10">স্টক ++</th>
                            <th class="py-3.5 px-4 text-center font-bold border-r border-white/10">স্টক --</th>
                            <th class="py-3.5 px-4 text-left font-bold border-r border-white/10">ইউজার</th>
                            <th class="py-3.5 px-4 text-center font-bold">বাটন</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-150 dark:divide-slate-800/50">
                        @forelse($adjustments as $adj)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/20 transition-colors">
                                <td class="py-3.5 px-4 text-gray-600 dark:text-slate-300 border-r border-gray-150 dark:border-slate-800 whitespace-nowrap">{{ toBanglaDateTime($adj->date) }}</td>
                                <td class="py-3.5 px-4 text-gray-800 dark:text-white font-medium border-r border-gray-150 dark:border-slate-800 max-w-[150px] truncate" title="{{ $adj->description }}">{{ $adj->description ?: '-' }}</td>
                                <td class="py-3.5 px-4 font-bold text-gray-800 dark:text-white border-r border-gray-150 dark:border-slate-800">{{ $adj->category_name }}</td>
                                <td class="py-3.5 px-4 text-center font-mono font-bold text-emerald-600 dark:text-emerald-400 border-r border-gray-150 dark:border-slate-800">
                                    {{ $adj->stock_plus > 0 ? toBanglaNum(number_format($adj->stock_plus)) : '০' }}
                                </td>
                                <td class="py-3.5 px-4 text-center font-mono font-bold text-rose-500 border-r border-gray-150 dark:border-slate-800">
                                    {{ $adj->stock_minus > 0 ? toBanglaNum(number_format($adj->stock_minus)) : '০' }}
                                </td>
                                <td class="py-3.5 px-4 text-gray-600 dark:text-slate-300 border-r border-gray-150 dark:border-slate-800">{{ $adj->user->name ?? 'Demo' }}</td>
                                <td class="py-3.5 px-4 text-center">
                                    <button type="button"
                                            wire:click="deleteAdjustment({{ $adj->id }})"
                                            wire:confirm="আপনি কি নিশ্চিতভাবে এই স্টক আপডেট রেকর্ডটি মুছে ফেলতে চান?"
                                            class="p-1 text-rose-500 hover:text-rose-700 hover:bg-rose-50 dark:hover:bg-rose-950/20 rounded-lg cursor-pointer focus:outline-none">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-gray-400">কোনো স্টক আপডেট রেকর্ড খুঁজে পাওয়া যায়নি</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination footer with per page select --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 px-5 py-4 border-t border-gray-150 dark:border-slate-800 rounded-b-3xl bg-white dark:bg-slate-900">
                <div class="text-xs text-gray-500 dark:text-gray-400 font-sans font-semibold">
                    মোট রেকর্ড সংখ্যা: <strong class="text-gray-800 dark:text-white">{{ toBanglaNum($adjustments->total()) }} টি</strong>
                </div>

                <div class="flex items-center gap-4">
                    {{-- Pagination Links --}}
                    {{ $adjustments->links() }}

                    {{-- Per Page Select --}}
                    <div x-data="{ open:false }" class="relative font-sans">
                        <button @click="open=!open" type="button"
                                class="flex items-center justify-between gap-1.5 px-3 py-1.5 bg-white dark:bg-slate-800 text-gray-800 dark:text-white font-bold rounded-lg text-xs border border-gray-200 dark:border-slate-700 cursor-pointer">
                            <span class="font-sans">{{ toBanglaNum($perPage) }} / পেজ</span>
                            <svg class="w-3.5 h-3.5 transition-transform" :class="{'rotate-180':open}" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" @click.outside="open=false" x-cloak
                             class="absolute bottom-full mb-1.5 right-0 z-[999] w-32 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl shadow-xl overflow-hidden">
                            <div class="py-1">
                                @foreach([5,10,15,20,30,50] as $size)
                                    <button type="button" wire:click="$set('perPage',{{ $size }})" @click="open=false"
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
    @endif
</div>
