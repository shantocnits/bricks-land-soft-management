<div class="space-y-6">
    
    <!-- Top Filter Area -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 bg-white dark:bg-slate-900 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800/80 transition-colors duration-300">
        
        <!-- Period Filters (Left) -->
        <div class="flex flex-wrap items-center gap-2">
            <button 
                type="button"
                wire:click="setPeriod('today')" 
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-150 cursor-pointer font-sans shadow-sm {{ $filterPeriod === 'today' ? 'bg-[#0088A8] text-white ring-2 ring-cyan-500/20' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                আজকের হিসাব
            </button>
            <button 
                type="button"
                wire:click="setPeriod('7days')" 
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-150 cursor-pointer font-sans shadow-sm {{ $filterPeriod === '7days' ? 'bg-[#009669] text-white ring-2 ring-emerald-500/20' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                গত ৭ দিনের হিসাব
            </button>
            <button 
                type="button"
                wire:click="setPeriod('15days')" 
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-150 cursor-pointer font-sans shadow-sm {{ $filterPeriod === '15days' ? 'bg-[#008080] text-white ring-2 ring-teal-500/20' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                গত ১৫ দিনের হিসাব
            </button>
            <button 
                type="button"
                wire:click="setPeriod('season')" 
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-150 cursor-pointer font-sans shadow-sm {{ $filterPeriod === 'season' ? 'bg-[#E65100] text-white ring-2 ring-orange-500/20' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                সিজনের হিসাব
            </button>
            <button 
                type="button"
                wire:click="setPeriod('profit_loss')" 
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-150 cursor-pointer font-sans shadow-sm {{ $filterPeriod === 'profit_loss' ? 'bg-[#7E57C2] text-white ring-2 ring-purple-500/20' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                লাভ লসের হিসাব
            </button>
        </div>

        <!-- Search & Date Inputs (Right) -->
        <div class="flex items-center gap-3 w-full lg:w-auto">
            <!-- Search bar -->
            <div class="relative flex-grow lg:flex-grow-0">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </span>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search"
                    class="w-full lg:w-48 py-1.5 pl-9 pr-4 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-950/30 transition-all placeholder:text-gray-400"
                    placeholder="খুঁজুন...">
            </div>

            <!-- Date picker (Flatpickr) -->
            <div class="relative flex items-center">
                <input 
                    type="text" 
                    data-flatpickr 
                    data-wire-prop="dateFilter" 
                    data-default="{{ $dateFilter }}"
                    wire:model.live="dateFilter" 
                    placeholder="তারিখ" 
                    readonly
                    class="pl-3 pr-8 py-1.5 text-xs rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white w-36 font-sans font-semibold cursor-pointer focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-950/30 transition-all">
                <span class="absolute right-2.5 text-emerald-500 pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                </span>
            </div>
        </div>
    </div>

    <!-- Quick Status Cards Grid (6 Summary Cards) -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
        
        <!-- Card 1: মোট বিক্রি -->
        <a href="{{ route('challan.today') }}" wire:navigate
           class="bg-[#00609C] hover:bg-[#005185] text-white p-3.5 sm:p-5 rounded-2xl shadow-md border border-blue-700/10 hover:shadow-xl transition-all duration-200 flex flex-col justify-between cursor-pointer group active:scale-98">
            <span class="text-[11px] sm:text-xs font-bold opacity-90 group-hover:underline">মোট বিক্রি ( ভারা সহ )</span>
            <span class="text-base sm:text-2xl lg:text-3xl font-extrabold mt-2 sm:mt-3 tracking-wide font-mono">৳ {{ number_format($totalSalesVat, 0) }}</span>
        </a>

        <!-- Card 2: নগদ বিক্রি -->
        <a href="{{ route('challan.today') }}" wire:navigate
           class="bg-[#0E8C4E] hover:bg-[#0C7642] text-white p-3.5 sm:p-5 rounded-2xl shadow-md border border-emerald-700/10 hover:shadow-xl transition-all duration-200 flex flex-col justify-between cursor-pointer group active:scale-98">
            <span class="text-[11px] sm:text-xs font-bold opacity-90 group-hover:underline">নগদ বিক্রি</span>
            <span class="text-base sm:text-2xl lg:text-3xl font-extrabold mt-2 sm:mt-3 tracking-wide font-mono">৳ {{ number_format($cashSales, 0) }}</span>
        </a>

        <!-- Card 3: বাকি বিক্রি -->
        <a href="{{ route('challan.pending') }}" wire:navigate
           class="bg-[#BF20BA] hover:bg-[#A31B9E] text-white p-3.5 sm:p-5 rounded-2xl shadow-md border border-purple-700/10 hover:shadow-xl transition-all duration-200 flex flex-col justify-between cursor-pointer group active:scale-98">
            <span class="text-[11px] sm:text-xs font-bold opacity-90 group-hover:underline">বাকি বিক্রি</span>
            <span class="text-base sm:text-2xl lg:text-3xl font-extrabold mt-2 sm:mt-3 tracking-wide font-mono">৳ {{ number_format($dueSales, 0) }}</span>
        </a>

        <!-- Card 4: মোট পেমেন্ট -->
        <a href="{{ route('payment-khata') }}" wire:navigate
           class="bg-[#E05A16] hover:bg-[#BE4B11] text-white p-3.5 sm:p-5 rounded-2xl shadow-md border border-orange-700/10 hover:shadow-xl transition-all duration-200 flex flex-col justify-between cursor-pointer group active:scale-98">
            <span class="text-[11px] sm:text-xs font-bold opacity-90 group-hover:underline">মোট পেমেন্ট</span>
            <span class="text-base sm:text-2xl lg:text-3xl font-extrabold mt-2 sm:mt-3 tracking-wide font-mono">৳ {{ number_format($totalPayment, 0) }}</span>
        </a>

        <!-- Card 5: বাকি জমা -->
        <a href="{{ route('due-ledger.today') }}" wire:navigate
           class="bg-[#0B958E] hover:bg-[#097E78] text-white p-3.5 sm:p-5 rounded-2xl shadow-md border border-teal-700/10 hover:shadow-xl transition-all duration-200 flex flex-col justify-between cursor-pointer group active:scale-98">
            <span class="text-[11px] sm:text-xs font-bold opacity-90 group-hover:underline">বাকি জমা</span>
            <span class="text-base sm:text-2xl lg:text-3xl font-extrabold mt-2 sm:mt-3 tracking-wide font-mono">৳ {{ number_format($dueDeposit, 0) }}</span>
        </a>

        <!-- Card 6: মোট ক্যাশ -->
        <a href="{{ route('cash-khata') }}" wire:navigate
           class="bg-[#5D55EA] hover:bg-[#4E46D7] text-white p-3.5 sm:p-5 rounded-2xl shadow-md border border-indigo-700/10 hover:shadow-xl transition-all duration-200 flex flex-col justify-between cursor-pointer group active:scale-98">
            <span class="text-[11px] sm:text-xs font-bold opacity-90 group-hover:underline">মোট ক্যাশ</span>
            <span class="text-base sm:text-2xl lg:text-3xl font-extrabold mt-2 sm:mt-3 tracking-wide font-mono">৳ {{ number_format($netCash, 0) }}</span>
        </a>

    </div>

    <!-- 6 Tables Main Section Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">
        
        <!-- Column 1: চালান (xl:col-span-4) -->
        <div class="xl:col-span-4 space-y-4">
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800/80 overflow-hidden flex flex-col transition-colors duration-300">
                <!-- Header -->
                <a href="{{ route('challan.today') }}" wire:navigate
                   class="bg-[#037A61] hover:bg-[#02624e] text-white px-4 py-3 font-bold text-sm tracking-wide text-center flex items-center justify-center gap-2 cursor-pointer transition-colors">
                    📄 চালান
                </a>
                
                <!-- Table Container -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-[#A3E635]/20 dark:bg-emerald-950/40 text-emerald-900 dark:text-emerald-300 font-bold border-b border-gray-100 dark:border-slate-800">
                                <th class="px-3 py-2.5 text-left">শ্রেণি</th>
                                <th class="px-3 py-2.5 text-center">চালান</th>
                                <th class="px-3 py-2.5 text-center">পরিমাণ</th>
                                <th class="px-3 py-2.5 text-right">মোট মূল্য</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-800/50 font-sans">
                            @forelse($challanCategories as $c)
                                <tr class="text-gray-700 dark:text-slate-300 hover:bg-emerald-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                    <td class="px-3 py-2.5 text-left font-bold text-emerald-700 dark:text-emerald-400">{{ $c->category_name }}</td>
                                    <td class="px-3 py-2.5 text-center font-mono font-bold">{{ $c->total_challan }}</td>
                                    <td class="px-3 py-2.5 text-center font-mono font-bold">{{ number_format($c->total_qty) }}</td>
                                    <td class="px-3 py-2.5 text-right font-mono font-bold">৳ {{ number_format($c->total_amount, 0) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-gray-400 dark:text-slate-500">কোন চালান ডাটা পাওয়া যায়নি</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Financial Summary Box Below Challan Table -->
            <div class="bg-[#D1E7DD] dark:bg-emerald-950/40 border border-emerald-300/60 dark:border-emerald-900/60 rounded-2xl p-4 space-y-2.5 text-xs font-sans">
                <div class="flex justify-between items-center text-emerald-900 dark:text-emerald-200">
                    <span class="font-bold">মোট বিক্রি মূল্য</span>
                    <span class="font-mono font-extrabold text-sm">৳ {{ number_format($totalChallanValue, 0) }}</span>
                </div>
                <div class="flex justify-between items-center text-emerald-800 dark:text-emerald-300">
                    <span class="font-semibold">ছাড় (-)</span>
                    <span class="font-mono font-bold text-amber-700 dark:text-amber-400">৳ {{ number_format($totalDiscount, 0) }}</span>
                </div>
                <div class="flex justify-between items-center text-emerald-800 dark:text-emerald-300">
                    <span class="font-semibold">গাড়ি ভাড়া (+)</span>
                    <span class="font-mono font-bold text-blue-700 dark:text-blue-400">৳ {{ number_format($totalTransportRent, 0) }}</span>
                </div>
                <div class="flex justify-between items-center pt-2 border-t border-emerald-300 dark:border-emerald-800/80 text-emerald-950 dark:text-emerald-100 font-extrabold">
                    <span>মোট বিক্রি (ভাড়া সহ)</span>
                    <span class="font-mono text-base text-emerald-800 dark:text-emerald-300">৳ {{ number_format($totalSalesVat, 0) }}</span>
                </div>
                <div class="flex justify-between items-center text-emerald-800 dark:text-emerald-300">
                    <span class="font-semibold">নগদ</span>
                    <span class="font-mono font-bold text-emerald-700 dark:text-emerald-400">৳ {{ number_format($cashSales, 0) }}</span>
                </div>
                <div class="flex justify-between items-center text-rose-700 dark:text-rose-400 font-bold">
                    <span>বাকি</span>
                    <span class="font-mono text-sm">৳ {{ number_format($dueSales, 0) }}</span>
                </div>
            </div>
        </div>

        <!-- Column 2: পেমেন্ট (xl:col-span-4) -->
        <div class="xl:col-span-4 bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800/80 overflow-hidden flex flex-col min-h-[420px] transition-colors duration-300">
            <!-- Header -->
            <a href="{{ route('payment-khata') }}" wire:navigate
               class="bg-[#E89E53] hover:bg-[#d68a41] text-white px-4 py-3 font-bold text-sm tracking-wide text-center flex items-center justify-center gap-2 cursor-pointer transition-colors">
                💵 পেমেন্ট
            </a>
            
            <!-- Table Container -->
            <div class="flex-grow overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-orange-50 dark:bg-orange-950/20 text-orange-900 dark:text-orange-300 font-bold border-b border-gray-100 dark:border-slate-800">
                            <th class="px-4 py-2.5 text-left">খতিয়ান</th>
                            <th class="px-4 py-2.5 text-center">পরিমাণ</th>
                            <th class="px-4 py-2.5 text-right">পেমেন্ট দেওয়া</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800/50 font-sans">
                        @forelse($paymentSummary as $p)
                            <tr class="text-gray-700 dark:text-slate-300 hover:bg-orange-50/40 dark:hover:bg-slate-800/20 transition-colors">
                                <td class="px-4 py-2.5 text-left font-bold text-orange-700 dark:text-orange-400">{{ $p->ledger }}</td>
                                <td class="px-4 py-2.5 text-center font-mono font-bold text-orange-600 dark:text-orange-300">৳ {{ number_format($p->total_payment, 0) }}</td>
                                <td class="px-4 py-2.5 text-right text-gray-600 dark:text-slate-400">{{ $p->desc ?: 'পেমেন্ট দেওয়া' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-gray-400 dark:text-slate-500">কোন পেমেন্ট ডাটা পাওয়া যায়নি</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Column 3: Stacked smaller sections (xl:col-span-4) -->
        <div class="xl:col-span-4 flex flex-col gap-6">
            
            <!-- Top Subgrid: প্রোডাকশন & লোড (Side-by-Side) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                
                <!-- প্রোডাকশন -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800/80 overflow-hidden flex flex-col transition-colors duration-300">
                    <div class="bg-[#3C82F6] text-white px-3 py-2.5 font-bold text-xs text-center flex items-center justify-center gap-1.5">
                        ⚙️ প্রোডাকশন
                    </div>
                    <div class="flex-grow overflow-y-auto">
                        <table class="w-full text-xs border-collapse">
                            <thead>
                                <tr class="bg-blue-50 dark:bg-blue-950/20 text-blue-900 dark:text-blue-300 text-[11px] font-bold border-b border-gray-100 dark:border-slate-800">
                                    <th class="px-3 py-2 text-left">মেল</th>
                                    <th class="px-3 py-2 text-right">প্রোডাকশন</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-slate-800/50 font-sans">
                                @forelse($productions as $pr)
                                    <tr class="text-gray-700 dark:text-slate-300 hover:bg-blue-50/40 dark:hover:bg-slate-800/20">
                                        <td class="px-3 py-2 text-left font-semibold">{{ $pr['mill'] }}</td>
                                        <td class="px-3 py-2 text-right font-mono font-bold text-blue-600 dark:text-blue-400">{{ number_format($pr['qty']) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-3 py-6 text-center text-gray-400">খালি</td>
                                    </tr>
                                @endforelse
                                <tr class="bg-blue-50/60 dark:bg-blue-950/40 font-bold text-blue-950 dark:text-blue-200 border-t border-blue-200 dark:border-blue-900">
                                    <td class="px-3 py-2 text-left">মোট প্রোডাকশন</td>
                                    <td class="px-3 py-2 text-right font-mono">{{ number_format(array_sum(array_column($productions, 'qty'))) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- লোড -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800/80 overflow-hidden flex flex-col transition-colors duration-300">
                    <a href="{{ route('load-khata') }}" wire:navigate
                       class="bg-[#0DA89B] hover:bg-[#0B958A] text-white px-3 py-2.5 font-bold text-xs text-center flex items-center justify-center gap-1.5 cursor-pointer transition-colors">
                        🚚 লোড
                    </a>
                    <div class="flex-grow overflow-y-auto">
                        <table class="w-full text-xs border-collapse">
                            <thead>
                                <tr class="bg-teal-50 dark:bg-teal-950/20 text-teal-900 dark:text-teal-300 text-[11px] font-bold border-b border-gray-100 dark:border-slate-800">
                                    <th class="px-3 py-2 text-left">বিবরণ</th>
                                    <th class="px-3 py-2 text-right">পরিমাণ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-slate-800/50 font-sans">
                                @forelse($loadSummary as $l)
                                    <tr class="text-gray-700 dark:text-slate-300 hover:bg-teal-50/40 dark:hover:bg-slate-800/20">
                                        <td class="px-3 py-2 text-left font-semibold">{{ $l->description ?: ($l->category ?: 'লোডিং') }}</td>
                                        <td class="px-3 py-2 text-right font-mono font-bold text-teal-600 dark:text-teal-400">{{ number_format($l->total_qty) }}</td>
                                    </tr>
                                @empty
                                    <tr class="text-gray-600 dark:text-slate-300">
                                        <td class="px-3 py-2 text-left font-semibold">মাঠ থেকে লোড হয়েছে</td>
                                        <td class="px-3 py-2 text-right font-mono font-bold text-teal-600 dark:text-teal-400">০</td>
                                    </tr>
                                    <tr class="text-gray-600 dark:text-slate-300">
                                        <td class="px-3 py-2 text-left font-semibold">পাকা ইট লোড হয়েছে</td>
                                        <td class="px-3 py-2 text-right font-mono font-bold text-teal-600 dark:text-teal-400">০</td>
                                    </tr>
                                    <tr class="text-gray-600 dark:text-slate-300">
                                        <td class="px-3 py-2 text-left font-semibold">স্টক থেকে লোড হয়েছে</td>
                                        <td class="px-3 py-2 text-right font-mono font-bold text-teal-600 dark:text-teal-400">০</td>
                                    </tr>
                                @endforelse
                                <tr class="bg-teal-50/60 dark:bg-teal-950/40 font-bold text-teal-950 dark:text-teal-200 border-t border-teal-200 dark:border-teal-900">
                                    <td class="px-3 py-2 text-left">মোট লোড</td>
                                    <td class="px-3 py-2 text-right font-mono">{{ number_format($loadSummary->sum('total_qty')) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- Bottom Subgrid: ডেলিভারি & আনলোড (Side-by-Side) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                
                <!-- ডেলিভারি -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800/80 overflow-hidden flex flex-col transition-colors duration-300">
                    <a href="{{ route('delivery.today') }}" wire:navigate
                       class="bg-[#7B86FC] hover:bg-[#6874EB] text-white px-3 py-2.5 font-bold text-xs text-center flex items-center justify-center gap-1.5 cursor-pointer transition-colors">
                        📦 ডেলিভারি
                    </a>
                    <div class="flex-grow overflow-y-auto">
                        <table class="w-full text-xs border-collapse">
                            <thead>
                                <tr class="bg-indigo-50 dark:bg-indigo-950/20 text-indigo-900 dark:text-indigo-300 text-[11px] font-bold border-b border-gray-100 dark:border-slate-800">
                                    <th class="px-3 py-2 text-left">শ্রেণি</th>
                                    <th class="px-3 py-2 text-right">ডেলিভারি</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-slate-800/50 font-sans">
                                @forelse($deliverySummary as $d)
                                    <tr class="text-gray-700 dark:text-slate-300 hover:bg-indigo-50/40 dark:hover:bg-slate-800/20">
                                        <td class="px-3 py-2 text-left font-semibold">{{ $d->category_name }}</td>
                                        <td class="px-3 py-2 text-right font-mono font-bold text-indigo-600 dark:text-indigo-400">{{ number_format($d->total_qty) }}</td>
                                    </tr>
                                @empty
                                    <tr class="text-gray-600 dark:text-slate-300">
                                        <td class="px-3 py-2 text-left font-semibold">পিকট</td>
                                        <td class="px-3 py-2 text-right font-mono font-bold text-indigo-600 dark:text-indigo-400">০</td>
                                    </tr>
                                    <tr class="text-gray-600 dark:text-slate-300">
                                        <td class="px-3 py-2 text-left font-semibold">২ নং (ক)</td>
                                        <td class="px-3 py-2 text-right font-mono font-bold text-indigo-600 dark:text-indigo-400">০</td>
                                    </tr>
                                    <tr class="text-gray-600 dark:text-slate-300">
                                        <td class="px-3 py-2 text-left font-semibold">২ নং (খ)</td>
                                        <td class="px-3 py-2 text-right font-mono font-bold text-indigo-600 dark:text-indigo-400">০</td>
                                    </tr>
                                @endforelse
                                <tr class="bg-indigo-50/60 dark:bg-indigo-950/40 font-bold text-indigo-950 dark:text-indigo-200 border-t border-indigo-200 dark:border-indigo-900">
                                    <td class="px-3 py-2 text-left">মোট ডেলিভারি</td>
                                    <td class="px-3 py-2 text-right font-mono">{{ number_format($deliverySummary->sum('total_qty')) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- আনলোড -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800/80 overflow-hidden flex flex-col transition-colors duration-300">
                    <a href="{{ route('unload-khata') }}" wire:navigate
                       class="bg-[#8D8378] hover:bg-[#7a7167] text-white px-3 py-2.5 font-bold text-xs text-center flex items-center justify-center gap-1.5 cursor-pointer transition-colors">
                        🏗️ আনলোড
                    </a>
                    <div class="flex-grow overflow-y-auto">
                        <table class="w-full text-xs border-collapse">
                            <thead>
                                <tr class="bg-amber-50 dark:bg-amber-950/20 text-amber-900 dark:text-amber-300 text-[11px] font-bold border-b border-gray-100 dark:border-slate-800">
                                    <th class="px-3 py-2 text-left">শ্রেণি</th>
                                    <th class="px-3 py-2 text-right">পরিমাণ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-slate-800/50 font-sans">
                                @forelse($unloadSummary as $u)
                                    <tr class="text-gray-700 dark:text-slate-300 hover:bg-amber-50/40 dark:hover:bg-slate-800/20">
                                        <td class="px-3 py-2 text-left font-semibold">{{ $u->category_name }}</td>
                                        <td class="px-3 py-2 text-right font-mono font-bold text-amber-700 dark:text-amber-400">{{ number_format($u->total_qty) }}</td>
                                    </tr>
                                @empty
                                    <tr class="text-gray-600 dark:text-slate-300">
                                        <td class="px-3 py-2 text-left font-semibold">১ নং</td>
                                        <td class="px-3 py-2 text-right font-mono font-bold text-amber-700 dark:text-amber-400">০</td>
                                    </tr>
                                    <tr class="text-gray-600 dark:text-slate-300">
                                        <td class="px-3 py-2 text-left font-semibold">পিকট</td>
                                        <td class="px-3 py-2 text-right font-mono font-bold text-amber-700 dark:text-amber-400">০</td>
                                    </tr>
                                    <tr class="text-gray-600 dark:text-slate-300">
                                        <td class="px-3 py-2 text-left font-semibold">২ নং (ক)</td>
                                        <td class="px-3 py-2 text-right font-mono font-bold text-amber-700 dark:text-amber-400">০</td>
                                    </tr>
                                @endforelse
                                <tr class="bg-amber-50/60 dark:bg-amber-950/40 font-bold text-amber-950 dark:text-amber-200 border-t border-amber-200 dark:border-amber-900">
                                    <td class="px-3 py-2 text-left">মোট আনলোড</td>
                                    <td class="px-3 py-2 text-right font-mono">{{ number_format($unloadSummary->sum('total_qty')) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- ========================================== -->
    <!-- PROFIT & LOSS (লাভ লসের হিসাব) MODAL      -->
    <!-- ========================================== -->
    @if($showProfitLossModal)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-xs flex items-center justify-center z-50 p-4 transition-all duration-200" wire:keydown.escape.window="closeProfitLossModal">
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-gray-200 dark:border-slate-800 max-w-md w-full overflow-hidden font-sans transform transition-all" @click.away="$wire.closeProfitLossModal()">
                
                <!-- Modal Header (Matching Screenshot 2 green title bar) -->
                <div class="bg-[#034C3C] text-white px-6 py-3.5 flex items-center justify-between">
                    <div class="w-6"></div>
                    <h3 class="text-base sm:text-lg font-bold tracking-tight text-white flex-1 text-center">
                        লাভ লসের হিসাব
                    </h3>
                    <button 
                        type="button" 
                        wire:click="closeProfitLossModal"
                        class="text-white/80 hover:text-white hover:bg-white/10 rounded-lg p-1 transition-colors cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Modal Content (Mint background table container) -->
                <div class="p-5 space-y-4">
                    <div class="bg-[#D1F2D9] dark:bg-emerald-950/40 border border-emerald-300 dark:border-emerald-900/60 rounded-xl overflow-hidden divide-y divide-emerald-200 dark:divide-emerald-900/50">
                        
                        <!-- Row 1: সর্বমোট ইট বিক্রি -->
                        <div class="flex items-center justify-between p-3">
                            <span class="text-xs font-extrabold text-emerald-900 dark:text-emerald-300">সর্বমোট ইট বিক্রি</span>
                            <span class="text-sm font-mono font-bold text-emerald-800 dark:text-emerald-300">
                                ৳ {{ function_exists('toBanglaNum') ? toBanglaNum(number_format($mSales, 1)) : number_format($mSales, 1) }}
                            </span>
                        </div>

                        <!-- Row 2: সর্বমোট খরচ -->
                        <div class="flex items-center justify-between p-3">
                            <span class="text-xs font-extrabold text-rose-700 dark:text-rose-400">সর্বমোট খরচ</span>
                            <span class="text-sm font-mono font-bold text-rose-700 dark:text-rose-400">
                                ৳ {{ function_exists('toBanglaNum') ? toBanglaNum(number_format($mExpenses, 1)) : number_format($mExpenses, 1) }}
                            </span>
                        </div>

                        <!-- Row 3: বেশি পেমেন্ট -->
                        <div class="flex items-center justify-between p-3">
                            <span class="text-xs font-extrabold text-amber-700 dark:text-amber-400">বেশি পেমেন্ট</span>
                            <span class="text-sm font-mono font-bold text-amber-700 dark:text-amber-400">
                                ৳ {{ function_exists('toBanglaNum') ? toBanglaNum(number_format($mOverpayment, 1)) : number_format($mOverpayment, 1) }}
                            </span>
                        </div>

                        <!-- Row 4: লাভ / লস -->
                        <div class="flex items-center justify-between p-3">
                            <span class="text-xs font-extrabold {{ $mNetProfitLoss < 0 ? 'text-rose-700 dark:text-rose-400' : 'text-emerald-800 dark:text-emerald-300' }}">
                                {{ $mNetProfitLoss < 0 ? 'লস' : 'লাভ' }}
                            </span>
                            <span class="text-sm font-mono font-black {{ $mNetProfitLoss < 0 ? 'text-rose-700 dark:text-rose-400' : 'text-emerald-800 dark:text-emerald-300' }}">
                                ৳ {{ function_exists('toBanglaNum') ? toBanglaNum(number_format($mNetProfitLoss, 1)) : number_format($mNetProfitLoss, 1) }}
                            </span>
                        </div>

                        <!-- Row 5: বাকি রয়েছে -->
                        <div class="flex items-center justify-between p-3">
                            <span class="text-xs font-extrabold text-slate-700 dark:text-slate-300">বাকি রয়েছে</span>
                            <span class="text-sm font-mono font-bold text-slate-800 dark:text-slate-200">
                                ৳ {{ function_exists('toBanglaNum') ? toBanglaNum(number_format($mDue, 1)) : number_format($mDue, 1) }}
                            </span>
                        </div>

                        <!-- Row 6: সর্বমোট লাভ / লস -->
                        <div class="flex items-center justify-between p-3">
                            <span class="text-xs font-black {{ $mOverallProfitLoss < 0 ? 'text-rose-800 dark:text-rose-400' : 'text-emerald-900 dark:text-emerald-200' }}">
                                {{ $mOverallProfitLoss < 0 ? 'সর্বমোট লস' : 'সর্বমোট লাভ' }}
                            </span>
                            <span class="text-sm font-mono font-black {{ $mOverallProfitLoss < 0 ? 'text-rose-800 dark:text-rose-400' : 'text-emerald-900 dark:text-emerald-200' }}">
                                ৳ {{ function_exists('toBanglaNum') ? toBanglaNum(number_format($mOverallProfitLoss, 1)) : number_format($mOverallProfitLoss, 1) }}
                            </span>
                        </div>

                    </div>

                    <!-- Season Selector Buttons at Bottom (Matching Screenshot 2) -->
                    <div class="flex items-center gap-2 pt-2">
                        @foreach($availableSeasons as $seasonOpt)
                            <button 
                                type="button" 
                                wire:click="setModalSeason('{{ $seasonOpt }}')"
                                class="px-4 py-1.5 rounded-xl text-xs font-bold transition-all duration-150 cursor-pointer font-sans shadow-xs {{ $modalSeason === $seasonOpt ? 'bg-[#034C3C] text-white ring-2 ring-emerald-500/20' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-gray-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700' }}">
                                {{ $seasonOpt }}
                            </button>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    @endif

</div>
