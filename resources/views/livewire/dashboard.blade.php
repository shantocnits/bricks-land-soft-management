<div class="space-y-6">
    
    <!-- Top Filter Area -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 bg-white dark:bg-slate-900 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800/80 transition-colors duration-300">
        
        <!-- Period Filters (Left) -->
        <div class="flex flex-wrap items-center gap-2">
            <button 
                wire:click="setPeriod('today')" 
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-150 cursor-pointer font-sans shadow-sm"
                :class="filterPeriod === 'today' ? 'bg-primary text-white ring-2 ring-emerald-500/10' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-750'">
                আজকের হিসাব
            </button>
            <button 
                wire:click="setPeriod('7days')" 
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-150 cursor-pointer font-sans shadow-sm"
                :class="filterPeriod === '7days' ? 'bg-primary text-white ring-2 ring-emerald-500/10' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-750'">
                গত ৭ দিনের হিসাব
            </button>
            <button 
                wire:click="setPeriod('15days')" 
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-150 cursor-pointer font-sans shadow-sm"
                :class="filterPeriod === '15days' ? 'bg-primary text-white ring-2 ring-emerald-500/10' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-750'">
                গত ১৫ দিনের হিসাব
            </button>
            <button 
                wire:click="setPeriod('month')" 
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-150 cursor-pointer font-sans shadow-sm"
                :class="filterPeriod === 'month' ? 'bg-primary text-white ring-2 ring-emerald-500/10' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-750'">
                চলতি মাসের হিসাব
            </button>
            <button 
                wire:click="setPeriod('last_month')" 
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-150 cursor-pointer font-sans shadow-sm"
                :class="filterPeriod === 'last_month' ? 'bg-primary text-white ring-2 ring-emerald-500/10' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-750'">
                গত মাসের হিসাব
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
                    wire:model.live="search"
                    class="w-full lg:w-48 py-1.5 pl-9 pr-4 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-950/30 transition-all"
                    placeholder="সার্চ...">
            </div>

            <!-- Date picker -->
            <div class="relative w-36 lg:w-auto">
                <input 
                    type="date" 
                    wire:model.live="dateFilter"
                    class="w-full py-1.5 px-3 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-950/30 transition-all">
            </div>
        </div>
    </div>

    <!-- Quick Status Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        
        <!-- Card 1: মোট বিক্রি -->
        <div class="bg-[#00609C] hover:bg-[#005185] text-white p-5 rounded-2xl shadow-md border border-blue-700/10 hover:shadow-lg transition-all duration-200 flex flex-col justify-between">
            <span class="text-xs font-bold opacity-90">মোট বিক্রি (ভ্যাট সহ)</span>
            <span class="text-3xl font-extrabold mt-3 tracking-wide">৳ ০</span>
        </div>

        <!-- Card 2: নগদ বিক্রি -->
        <div class="bg-[#0E8C4E] hover:bg-[#0C7642] text-white p-5 rounded-2xl shadow-md border border-emerald-700/10 hover:shadow-lg transition-all duration-200 flex flex-col justify-between">
            <span class="text-xs font-bold opacity-90">নগদ বিক্রি</span>
            <span class="text-3xl font-extrabold mt-3 tracking-wide">৳ ০</span>
        </div>

        <!-- Card 3: বাকি বিক্রি -->
        <div class="bg-[#BF20BA] hover:bg-[#A31B9E] text-white p-5 rounded-2xl shadow-md border border-purple-700/10 hover:shadow-lg transition-all duration-200 flex flex-col justify-between">
            <span class="text-xs font-bold opacity-90">বাকি বিক্রি</span>
            <span class="text-3xl font-extrabold mt-3 tracking-wide">৳ ০</span>
        </div>

        <!-- Card 4: মোট পেমেন্ট -->
        <div class="bg-[#E05A16] hover:bg-[#BE4B11] text-white p-5 rounded-2xl shadow-md border border-orange-700/10 hover:shadow-lg transition-all duration-200 flex flex-col justify-between">
            <span class="text-xs font-bold opacity-90">মোট পেমেন্ট</span>
            <span class="text-3xl font-extrabold mt-3 tracking-wide">৳ ০</span>
        </div>

        <!-- Card 5: বাকি জমা -->
        <div class="bg-[#0B958E] hover:bg-[#097E78] text-white p-5 rounded-2xl shadow-md border border-teal-700/10 hover:shadow-lg transition-all duration-200 flex flex-col justify-between">
            <span class="text-xs font-bold opacity-90">বাকি জমা</span>
            <span class="text-3xl font-extrabold mt-3 tracking-wide">৳ ০</span>
        </div>

        <!-- Card 6: বাকি ক্যাশ -->
        <div class="bg-[#5D55EA] hover:bg-[#4E46D7] text-white p-5 rounded-2xl shadow-md border border-indigo-700/10 hover:shadow-lg transition-all duration-200 flex flex-col justify-between">
            <span class="text-xs font-bold opacity-90">বাকি ক্যাশ</span>
            <span class="text-3xl font-extrabold mt-3 tracking-wide">৳ ০</span>
        </div>

    </div>

    <!-- 6 Tables Main Section Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">
        
        <!-- Column 1: চালান (xl:col-span-4) -->
        <div class="xl:col-span-4 bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800/80 overflow-hidden flex flex-col min-h-[420px] transition-colors duration-300">
            <!-- Header -->
            <div class="bg-[#037A61] text-white px-4 py-3 font-bold text-sm tracking-wide text-center flex items-center justify-center">
                📄 চালান
            </div>
            
            <!-- Table Container -->
            <div class="flex-grow overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-emerald-50 dark:bg-emerald-950/30 text-emerald-900 dark:text-emerald-300 font-semibold border-b border-gray-100 dark:border-slate-800">
                            <th class="px-4 py-2 text-center">শ্রেণি</th>
                            <th class="px-4 py-2 text-center">চালান</th>
                            <th class="px-4 py-2 text-center">পরিমাণ</th>
                            <th class="px-4 py-2 text-center">মোট মূল্য</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800/50">
                        @forelse($challans as $c)
                            <tr class="text-gray-700 dark:text-slate-350 hover:bg-gray-50/50 dark:hover:bg-slate-800/20">
                                <td class="px-4 py-2.5 text-center">{{ $c['category'] }}</td>
                                <td class="px-4 py-2.5 text-center font-semibold text-emerald-600 dark:text-emerald-400">{{ $c['challan_no'] }}</td>
                                <td class="px-4 py-2.5 text-center">{{ number_format($c['qty']) }}</td>
                                <td class="px-4 py-2.5 text-center font-bold">{{ $c['total'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-gray-400 dark:text-gray-600">কোন চালান ডাটা পাওয়া যায়নি</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Column 2: পেমেন্ট (xl:col-span-4) -->
        <div class="xl:col-span-4 bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800/80 overflow-hidden flex flex-col min-h-[420px] transition-colors duration-300">
            <!-- Header -->
            <div class="bg-[#E89E53] text-white px-4 py-3 font-bold text-sm tracking-wide text-center flex items-center justify-center">
                💵 পেমেন্ট
            </div>
            
            <!-- Table Container -->
            <div class="flex-grow overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-orange-50 dark:bg-orange-950/20 text-orange-900 dark:text-orange-300 font-semibold border-b border-gray-100 dark:border-slate-800">
                            <th class="px-4 py-2 text-center">খতিয়ান</th>
                            <th class="px-4 py-2 text-center">পরিমাণ</th>
                            <th class="px-4 py-2 text-center">পেমেন্ট নেওয়া</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800/50">
                        @forelse($payments as $p)
                            <tr class="text-gray-700 dark:text-slate-350 hover:bg-gray-50/50 dark:hover:bg-slate-800/20">
                                <td class="px-4 py-2.5 text-center font-semibold">{{ $p['khatian'] }}</td>
                                <td class="px-4 py-2.5 text-center font-bold text-orange-600 dark:text-orange-400">{{ $p['qty'] }}</td>
                                <td class="px-4 py-2.5 text-center">{{ $p['received_by'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-gray-400 dark:text-gray-600">কোন পেমেন্ট ডাটা পাওয়া যায়নি</td>
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
                <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800/80 overflow-hidden flex flex-col min-h-[196px] transition-colors duration-300">
                    <div class="bg-[#3C82F6] text-white px-3 py-2 font-bold text-xs text-center">
                        ⚙️ প্রোডাকশন
                    </div>
                    <div class="flex-grow overflow-y-auto">
                        <table class="w-full text-xs border-collapse">
                            <thead>
                                <tr class="bg-blue-50 dark:bg-blue-950/20 text-blue-900 dark:text-blue-300 text-[10px] font-semibold border-b border-gray-100 dark:border-slate-800">
                                    <th class="px-3 py-1.5 text-center">তেল</th>
                                    <th class="px-3 py-1.5 text-center">প্রোডাকশন</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-slate-800/50">
                                @forelse($productions as $pr)
                                    <tr class="text-gray-600 dark:text-slate-350 hover:bg-gray-50/50 dark:hover:bg-slate-800/20">
                                        <td class="px-3 py-2 text-center">{{ $pr['oil'] }}</td>
                                        <td class="px-3 py-2 text-center font-bold text-blue-600 dark:text-blue-400">{{ $pr['production'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-3 py-6 text-center text-gray-400">খালি</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- লোড -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800/80 overflow-hidden flex flex-col min-h-[196px] transition-colors duration-300">
                    <div class="bg-[#0DA89B] text-white px-3 py-2 font-bold text-xs text-center">
                        🚚 লোড
                    </div>
                    <div class="flex-grow overflow-y-auto">
                        <table class="w-full text-xs border-collapse">
                            <thead>
                                <tr class="bg-teal-50 dark:bg-teal-950/20 text-teal-900 dark:text-teal-300 text-[10px] font-semibold border-b border-gray-100 dark:border-slate-800">
                                    <th class="px-3 py-1.5 text-center">বিবরণ</th>
                                    <th class="px-3 py-1.5 text-center">পরিমাণ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-slate-800/50">
                                @forelse($loads as $l)
                                    <tr class="text-gray-600 dark:text-slate-350 hover:bg-gray-50/50 dark:hover:bg-slate-800/20">
                                        <td class="px-3 py-2 text-center">{{ $l['details'] }}</td>
                                        <td class="px-3 py-2 text-center font-bold text-teal-600 dark:text-teal-400">{{ $l['qty'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-3 py-6 text-center text-gray-400">খালি</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- Bottom Subgrid: ডেলিভারি & আমানত (Side-by-Side) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                
                <!-- ডেলিভারি -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800/80 overflow-hidden flex flex-col min-h-[196px] transition-colors duration-300">
                    <div class="bg-[#7B86FC] text-white px-3 py-2 font-bold text-xs text-center">
                        📦 ডেলিভারি
                    </div>
                    <div class="flex-grow overflow-y-auto">
                        <table class="w-full text-xs border-collapse">
                            <thead>
                                <tr class="bg-indigo-50 dark:bg-indigo-950/20 text-indigo-900 dark:text-indigo-300 text-[10px] font-semibold border-b border-gray-100 dark:border-slate-800">
                                    <th class="px-3 py-1.5 text-center">শ্রেণি</th>
                                    <th class="px-3 py-1.5 text-center">ডেলিভারি</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-slate-800/50">
                                @forelse($deliveries as $d)
                                    <tr class="text-gray-600 dark:text-slate-350 hover:bg-gray-50/50 dark:hover:bg-slate-800/20">
                                        <td class="px-3 py-2 text-center">{{ $d['category'] }}</td>
                                        <td class="px-3 py-2 text-center font-bold text-indigo-600 dark:text-indigo-400">{{ $d['qty'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-3 py-6 text-center text-gray-400">খালি</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- আমানত -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800/80 overflow-hidden flex flex-col min-h-[196px] transition-colors duration-300">
                    <div class="bg-[#B3A69A] text-white px-3 py-2 font-bold text-xs text-center">
                        💼 আমানত
                    </div>
                    <div class="flex-grow overflow-y-auto">
                        <table class="w-full text-xs border-collapse">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-slate-800 text-gray-900 dark:text-gray-300 text-[10px] font-semibold border-b border-gray-100 dark:border-slate-800">
                                    <th class="px-3 py-1.5 text-center">শ্রেণি</th>
                                    <th class="px-3 py-1.5 text-center">পরিমাণ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-slate-800/50">
                                @forelse($deposits as $dp)
                                    <tr class="text-gray-600 dark:text-slate-350 hover:bg-gray-50/50 dark:hover:bg-slate-800/20">
                                        <td class="px-3 py-2 text-center">{{ $dp['category'] }}</td>
                                        <td class="px-3 py-2 text-center font-bold text-slate-600 dark:text-slate-400">{{ $dp['amount'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-3 py-6 text-center text-gray-400">খালি</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>

</div>

