@php
if (!function_exists('toBanglaNum')) {
    function toBanglaNum($num) {
        $eng = ['0','1','2','3','4','5','6','7','8','9',',','.'];
        $bn  = ['০','১','২','৩','৪','৫','৬','৭','৮','৯',',','.'];
        return str_replace($eng, $bn, $num);
    }
}
@endphp

<div class="min-h-screen bg-gray-50 dark:bg-slate-950 transition-colors duration-300 pb-12">
    <div class="w-full space-y-6">
        
        <!-- Header & Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1: Total Sales -->
            <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl p-5 shadow-sm relative overflow-hidden transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold text-gray-500 dark:text-slate-400 font-sans block uppercase">মোট বিক্রি (ভ্যাট সহ)</span>
                        <h3 class="text-xl sm:text-2xl font-black text-[#034C3C] dark:text-emerald-400 font-mono mt-1">
                            ৳{{ toBanglaNum(number_format((float)($totalGrand), (float)($totalGrand) == (int)($totalGrand) ? 0 : 2)) }}
                        </h3>
                    </div>
                    <div class="p-3 bg-emerald-50 dark:bg-emerald-950/40 text-[#034C3C] dark:text-emerald-400 rounded-2xl border border-emerald-100 dark:border-emerald-900/50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Card 2: Total Paid -->
            <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl p-5 shadow-sm relative overflow-hidden transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold text-gray-500 dark:text-slate-400 font-sans block uppercase">মোট জমা / পরিশোধ</span>
                        <h3 class="text-xl sm:text-2xl font-black text-emerald-600 dark:text-emerald-400 font-mono mt-1">
                            ৳{{ toBanglaNum(number_format((float)($totalPaid), (float)($totalPaid) == (int)($totalPaid) ? 0 : 2)) }}
                        </h3>
                    </div>
                    <div class="p-3 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 rounded-2xl border border-emerald-100 dark:border-emerald-900/50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Card 3: Total Due -->
            <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl p-5 shadow-sm relative overflow-hidden transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold text-gray-500 dark:text-slate-400 font-sans block uppercase">মোট বাকি</span>
                        <h3 class="text-xl sm:text-2xl font-black text-rose-500 dark:text-rose-400 font-mono mt-1">
                            ৳{{ toBanglaNum(number_format((float)($totalDue), (float)($totalDue) == (int)($totalDue) ? 0 : 2)) }}
                        </h3>
                    </div>
                    <div class="p-3 bg-rose-50 dark:bg-rose-950/40 text-rose-500 dark:text-rose-400 rounded-2xl border border-rose-100 dark:border-rose-900/50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Card 4: Total Bricks Sold -->
            <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl p-5 shadow-sm relative overflow-hidden transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold text-gray-500 dark:text-slate-400 font-sans block uppercase">মোট বিক্রিত ইট</span>
                        <h3 class="text-xl sm:text-2xl font-black text-sky-600 dark:text-sky-400 font-mono mt-1">
                            {{ toBanglaNum(number_format($totalQuantity)) }} টি
                        </h3>
                    </div>
                    <div class="p-3 bg-sky-50 dark:bg-sky-950/40 text-sky-600 dark:text-sky-400 rounded-2xl border border-sky-100 dark:border-sky-900/50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Chart Section -->
        <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl p-5 sm:p-6 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 mb-4 border-b border-gray-100 dark:border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-[#034C3C] text-white rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-gray-808 dark:text-white font-sans">বিক্রি অ্যানালিটিক্স ও চার্ট</h3>
                        <p class="text-[11px] text-gray-500 dark:text-slate-400 font-sans">ফিল্টারকৃত ডেটার গ্রাফিক্যাল ভিজ্যুয়ালাইজেশন</p>
                    </div>
                </div>

                <!-- Chart Filter Buttons -->
                <div class="flex items-center gap-1.5 bg-gray-100 dark:bg-slate-800 p-1 rounded-xl text-xs font-bold font-sans">
                    <button type="button" wire:click="$set('chartPeriod', 'monthly')"
                            class="px-3 py-1.5 rounded-lg transition-all cursor-pointer {{ $chartPeriod === 'monthly' ? 'bg-[#034C3C] text-white shadow-sm' : 'text-gray-600 dark:text-slate-300 hover:bg-gray-200 dark:hover:bg-slate-700' }}">
                        মাসিক বিক্রি
                    </button>
                    <button type="button" wire:click="$set('chartPeriod', 'daily')"
                            class="px-3 py-1.5 rounded-lg transition-all cursor-pointer {{ $chartPeriod === 'daily' ? 'bg-[#034C3C] text-white shadow-sm' : 'text-gray-600 dark:text-slate-300 hover:bg-gray-200 dark:hover:bg-slate-700' }}">
                        দৈনিক বিক্রি
                    </button>
                    <button type="button" wire:click="$set('chartPeriod', 'category')"
                            class="px-3 py-1.5 rounded-lg transition-all cursor-pointer {{ $chartPeriod === 'category' ? 'bg-[#034C3C] text-white shadow-sm' : 'text-gray-600 dark:text-slate-300 hover:bg-gray-200 dark:hover:bg-slate-700' }}">
                        শ্রেণি ভিত্তিক
                    </button>
                </div>
            </div>

            <!-- Chart Canvas Container (wire:key ensures instant re-rendering on data/tab change) -->
            <div wire:key="sales-chart-{{ $chartPeriod }}-{{ md5(json_encode($chartData)) }}"
                 x-data="{
                    chart: null,
                    renderChart() {
                        if (typeof Chart === 'undefined') {
                            setTimeout(() => this.renderChart(), 100);
                            return;
                        }
                        const canvas = this.$refs.canvas;
                        if (!canvas) return;

                        const data = @js($chartData);
                        const labels = (data && data.labels && data.labels.length > 0) ? data.labels : ['কোনো বিক্রি ডেটা নেই'];
                        const series = (data && data.series && data.series.length > 0) ? data.series : [0];

                        if (this.chart) {
                            this.chart.destroy();
                            this.chart = null;
                        }

                        const isDarkMode = document.documentElement.classList.contains('dark');
                        const textColor = isDarkMode ? '#cbd5e1' : '#475569';
                        const gridColor = isDarkMode ? 'rgba(255, 255, 255, 0.08)' : 'rgba(0, 0, 0, 0.06)';

                        const ctx = canvas.getContext('2d');

                        if (data && data.type === 'category') {
                            this.chart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                        label: 'বিক্রির পরিমাণ (টি)',
                                        data: series,
                                        backgroundColor: '#059669',
                                        borderRadius: 8,
                                        maxBarThickness: 45
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: { display: true, labels: { color: textColor, font: { family: 'Inter', weight: 'bold' } } }
                                    },
                                    scales: {
                                        x: { ticks: { color: textColor, font: { family: 'Inter', weight: 'bold' } }, grid: { color: gridColor } },
                                        y: { ticks: { color: textColor, font: { family: 'Inter' } }, grid: { color: gridColor }, beginAtZero: true }
                                    }
                                }
                            });
                        } else {
                            const cash = (data && data.cash && data.cash.length > 0) ? data.cash : [0];
                            const due = (data && data.due && data.due.length > 0) ? data.due : [0];
                            this.chart = new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: labels,
                                    datasets: [
                                        {
                                            label: 'মোট বিক্রি (৳)',
                                            data: series,
                                            borderColor: '#059669',
                                            backgroundColor: 'rgba(5, 150, 105, 0.12)',
                                            fill: true,
                                            tension: 0.3,
                                            borderWidth: 3,
                                            pointRadius: 4,
                                            pointHoverRadius: 6
                                        },
                                        {
                                            label: 'নগদ জমা (৳)',
                                            data: cash,
                                            borderColor: '#0284c7',
                                            backgroundColor: 'transparent',
                                            tension: 0.3,
                                            borderWidth: 2.5,
                                            pointRadius: 3
                                        },
                                        {
                                            label: 'বাকি (৳)',
                                            data: due,
                                            borderColor: '#f43f5e',
                                            backgroundColor: 'transparent',
                                            borderDash: [5, 5],
                                            tension: 0.3,
                                            borderWidth: 2,
                                            pointRadius: 3
                                        }
                                    ]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: { display: true, labels: { color: textColor, font: { family: 'Inter', weight: 'bold' } } }
                                    },
                                    scales: {
                                        x: { ticks: { color: textColor, font: { family: 'Inter', weight: 'bold' } }, grid: { color: gridColor } },
                                        y: { ticks: { color: textColor, font: { family: 'Inter' } }, grid: { color: gridColor }, beginAtZero: true }
                                    }
                                }
                            });
                        }
                    }
                 }"
                 x-init="$nextTick(() => renderChart())"
                 class="h-64 sm:h-72 w-full relative">
                <canvas x-ref="canvas"></canvas>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl p-4 sm:p-5 shadow-sm space-y-4">
            <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
                
                <!-- Left: Search & Filter Inputs -->
                <div class="flex flex-wrap items-center gap-3 flex-grow">
                    
                    <!-- Search Input -->
                    <div class="relative flex-grow sm:flex-grow-0 sm:w-64 font-sans">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="কাস্টমার নাম, ফোন বা মেমো নং সার্চ..."
                               class="w-full pl-4 pr-9 py-2 text-xs font-semibold rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white placeholder-gray-400 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all">
                        <span class="absolute right-3 top-2.5 text-gray-400 pointer-events-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </span>
                    </div>

                    <!-- Date From -->
                    <div class="relative font-sans text-xs flex items-center">
                        <input type="text" data-flatpickr data-wire-prop="dateFrom" data-default="{{ $dateFrom }}" wire:model.live="dateFrom" placeholder="শুরু তারিখ"
                               readonly class="pl-3 pr-8 py-2 text-xs font-bold rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white w-32 cursor-pointer focus:outline-none">
                        <span class="absolute right-2.5 text-emerald-500 pointer-events-none">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </span>
                    </div>

                    <!-- Date To -->
                    <div class="relative font-sans text-xs flex items-center">
                        <input type="text" data-flatpickr data-wire-prop="dateTo" data-default="{{ $dateTo }}" wire:model.live="dateTo" placeholder="শেষ তারিখ"
                               readonly class="pl-3 pr-8 py-2 text-xs font-bold rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white w-32 cursor-pointer focus:outline-none">
                        <span class="absolute right-2.5 text-emerald-500 pointer-events-none">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </span>
                    </div>

                    <!-- Challan Type Selector -->
                    <div x-data="{ open: false }" class="relative font-sans text-xs">
                        <button @click="open = !open" type="button"
                                class="flex items-center justify-between gap-2 px-3 py-2 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white font-bold rounded-xl border border-gray-200 dark:border-slate-700 cursor-pointer min-w-[110px]">
                            <span>{{ $challanType === 'all' ? 'সকল ধরণ' : $challanType }}</span>
                            <svg class="w-3.5 h-3.5 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" @click.outside="open = false" x-cloak
                             class="absolute left-0 mt-1.5 z-[999] w-36 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl shadow-xl overflow-hidden py-1">
                            <button type="button" wire:click="$set('challanType', 'all')" @click="open = false" class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-200 font-bold">সকল ধরণ</button>
                            <button type="button" wire:click="$set('challanType', 'আজকের')" @click="open = false" class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-200 font-bold">আজকের চালান</button>
                            <button type="button" wire:click="$set('challanType', 'অগ্রিম')" @click="open = false" class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-200 font-bold">অগ্রিম চালান</button>
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div x-data="{ open: false }" class="relative font-sans text-xs">
                        <button @click="open = !open" type="button"
                                class="flex items-center justify-between gap-2 px-3 py-2 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white font-bold rounded-xl border border-gray-200 dark:border-slate-700 cursor-pointer min-w-[120px]">
                            <span>{{ $categoryFilter === 'all' ? 'সকল শ্রেণি' : $categoryFilter }}</span>
                            <svg class="w-3.5 h-3.5 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" @click.outside="open = false" x-cloak
                             class="absolute left-0 mt-1.5 z-[999] w-40 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl shadow-xl overflow-hidden py-1 max-h-48 overflow-y-auto">
                            <button type="button" wire:click="$set('categoryFilter', 'all')" @click="open = false" class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-200 font-bold">সকল শ্রেণি</button>
                            @foreach($categories as $cat)
                                <button type="button" wire:click="$set('categoryFilter', '{{ $cat->name }}')" @click="open = false" class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-200 font-bold">{{ $cat->name }}</button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Reset Filters Button -->
                    <button type="button" wire:click="resetFilters()"
                            class="px-3.5 py-2 bg-gray-200 dark:bg-slate-800 hover:bg-gray-300 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-200 rounded-xl text-xs font-bold font-sans cursor-pointer transition-all flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        রিসেট
                    </button>
                </div>

                <!-- Right: Print Button -->
                <div class="flex items-center gap-2">
                    <button type="button" onclick="window.print()"
                            class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold rounded-xl shadow-sm transition-all cursor-pointer flex items-center gap-1.5 font-sans">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/>
                        </svg>
                        প্রিন্ট করুন
                    </button>
                </div>

            </div>
        </div>

        <!-- Sales Data Table (Desktop View) -->
        <div class="hidden md:block bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left border border-gray-200 dark:border-slate-800 font-sans">
                    <thead>
                        <tr class="bg-[#034C3C] text-white text-[11px] font-bold uppercase">
                            <th class="py-3.5 px-4 text-center border-r border-white/20 w-12">#</th>
                            <th class="py-3.5 px-4 border-r border-white/20">তারিখ</th>
                            <th class="py-3.5 px-4 border-r border-white/20">মেমো নং</th>
                            <th class="py-3.5 px-4 border-r border-white/20">কাস্টমারের তথ্য</th>
                            <th class="py-3.5 px-4 text-center border-r border-white/20">ধরণ</th>
                            <th class="py-3.5 px-4 border-r border-white/20">ইটের বিবরণ</th>
                            <th class="py-3.5 px-4 text-center border-r border-white/20">মোট ইট</th>
                            <th class="py-3.5 px-4 text-right border-r border-white/20">মোট মূল্য</th>
                            <th class="py-3.5 px-4 text-right border-r border-white/20">পরিশোধ</th>
                            <th class="py-3.5 px-4 text-right">বাকি</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800 text-xs">
                        @forelse($challans as $index => $c)
                            @php
                                $cQty = $c->items->sum('quantity');
                            @endphp
                            <tr class="hover:bg-emerald-50/30 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="py-3.5 px-4 text-center text-gray-500 dark:text-slate-400 font-bold border-r border-gray-150 dark:border-slate-800">
                                    {{ toBanglaNum($challans->firstItem() + $index) }}
                                </td>
                                <td class="py-3.5 px-4 font-semibold text-gray-800 dark:text-slate-200 border-r border-gray-150 dark:border-slate-800 whitespace-nowrap">
                                    {{ $c->date ? \Carbon\Carbon::parse($c->date)->format('d-m-Y') : '—' }}
                                </td>
                                <td class="py-3.5 px-4 font-mono font-bold text-gray-900 dark:text-white border-r border-gray-150 dark:border-slate-800">
                                    #{{ toBanglaNum($c->challan_no ?: $c->id) }}
                                </td>
                                <td class="py-3.5 px-4 border-r border-gray-150 dark:border-slate-800">
                                    <div class="flex flex-col">
                                        <a href="{{ route('challan.customer-profile', ['phone' => $c->customer_phone ?: $c->customer_name, 'from' => 'sales-report']) }}" wire:navigate
                                           class="font-extrabold text-emerald-700 dark:text-emerald-400 hover:underline">
                                            {{ $c->customer_name }}
                                        </a>
                                        @if($c->customer_phone)
                                            <span class="text-[10px] text-gray-500 dark:text-slate-400 font-mono">{{ toBanglaNum($c->customer_phone) }}</span>
                                        @endif
                                        @if($c->customer_address)
                                            <span class="text-[10px] text-gray-400 dark:text-slate-500 truncate max-w-[180px]">{{ $c->customer_address }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-center border-r border-gray-150 dark:border-slate-800">
                                    <span class="inline-flex px-2 py-0.5 rounded-lg text-[10px] font-extrabold {{ $c->challan_type === 'অগ্রিম' ? 'bg-amber-100 text-amber-800 dark:bg-amber-950/40 dark:text-amber-300' : 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300' }}">
                                        {{ $c->challan_type }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 border-r border-gray-150 dark:border-slate-800">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($c->items as $item)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-slate-300 border border-gray-200 dark:border-slate-700">
                                                {{ $item->category_name }}: {{ toBanglaNum(number_format($item->quantity)) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-center font-bold text-gray-800 dark:text-slate-200 border-r border-gray-150 dark:border-slate-800 font-mono">
                                    {{ toBanglaNum(number_format($cQty)) }}
                                </td>
                                <td class="py-3.5 px-4 text-right font-black text-gray-900 dark:text-white border-r border-gray-150 dark:border-slate-800 font-mono">
                                    ৳{{ toBanglaNum(number_format((float)($c->grand_total), (float)($c->grand_total) == (int)($c->grand_total) ? 0 : 2)) }}
                                </td>
                                <td class="py-3.5 px-4 text-right font-bold text-emerald-600 dark:text-emerald-400 border-r border-gray-150 dark:border-slate-800 font-mono">
                                    ৳{{ toBanglaNum(number_format((float)($c->cash), (float)($c->cash) == (int)($c->cash) ? 0 : 2)) }}
                                </td>
                                <td class="py-3.5 px-4 text-right font-black {{ $c->due > 0 ? 'text-rose-500' : 'text-gray-500' }} font-mono">
                                    ৳{{ toBanglaNum(number_format((float)($c->due), (float)($c->due) == (int)($c->due) ? 0 : 2)) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="py-12 text-center text-gray-400 dark:text-slate-500 font-semibold text-xs">
                                    কোনো বিক্রি তথ্য পাওয়া যায়নি।
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    
                    <!-- Table Footer Summary -->
                    @if($challans->count() > 0)
                        <tfoot>
                            <tr class="bg-gray-100 dark:bg-slate-800 font-bold text-xs border-t-2 border-gray-300 dark:border-slate-700">
                                <td colspan="6" class="py-3.5 px-4 text-right font-sans text-gray-800 dark:text-white">সর্বমোট (বর্তমান ফিল্টার):</td>
                                <td class="py-3.5 px-4 text-center font-mono text-gray-900 dark:text-white">{{ toBanglaNum(number_format($totalQuantity)) }}</td>
                                <td class="py-3.5 px-4 text-right font-mono text-[#034C3C] dark:text-emerald-400">৳{{ toBanglaNum(number_format((float)($totalGrand), (float)($totalGrand) == (int)($totalGrand) ? 0 : 2)) }}</td>
                                <td class="py-3.5 px-4 text-right font-mono text-emerald-600">৳{{ toBanglaNum(number_format((float)($totalPaid), (float)($totalPaid) == (int)($totalPaid) ? 0 : 2)) }}</td>
                                <td class="py-3.5 px-4 text-right font-mono text-rose-500">৳{{ toBanglaNum(number_format((float)($totalDue), (float)($totalDue) == (int)($totalDue) ? 0 : 2)) }}</td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>

            <!-- Table Bottom Pagination Toolbar -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 px-5 py-4 border-t border-gray-150 dark:border-slate-800 bg-white dark:bg-slate-900">
                <div class="text-xs text-gray-500 dark:text-gray-400 font-sans font-semibold">
                    মোট বিক্রি রেকর্ড: <strong class="text-gray-800 dark:text-white">{{ toBanglaNum($challans->total()) }} টি</strong>
                </div>

                <div class="flex items-center gap-4">
                    {{ $challans->links() }}

                    <!-- Root Per Page Dropdown -->
                    <div x-data="{ open: false }" class="relative font-sans text-xs">
                        <button @click="open = !open" type="button"
                                class="flex items-center justify-between gap-1.5 px-3 py-1.5 bg-white dark:bg-slate-800 text-gray-800 dark:text-white font-bold rounded-lg text-xs border border-gray-200 dark:border-slate-700 cursor-pointer">
                            <span>{{ toBanglaNum($perPage) }} বিক্রি / পেজ</span>
                            <svg class="w-3.5 h-3.5 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" @click.outside="open = false" x-cloak
                             class="absolute bottom-full mb-1.5 right-0 z-[999] w-36 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl shadow-xl overflow-hidden">
                            <div class="py-1">
                                @foreach([5, 10, 15, 20, 30, 50, 100] as $size)
                                    <button type="button" wire:click="$set('perPage', {{ $size }})" @click="open = false"
                                            class="w-full text-left px-3 py-2 text-xs font-bold text-gray-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-slate-800 hover:text-emerald-700 dark:hover:text-emerald-400 font-sans cursor-pointer">
                                        {{ toBanglaNum($size) }} বিক্রি / পেজ
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Card View (Small Screens) -->
        <div class="block md:hidden space-y-4">
            @forelse($challans as $c)
                @php $cQty = $c->items->sum('quantity'); @endphp
                <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl p-5 shadow-sm space-y-3 font-sans">
                    <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-800 pb-3">
                        <div>
                            <span class="text-xs font-mono font-bold text-emerald-700 dark:text-emerald-400">#{{ toBanglaNum($c->challan_no ?: $c->id) }}</span>
                            <h4 class="font-extrabold text-gray-800 dark:text-white text-xs mt-0.5">{{ $c->customer_name }}</h4>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-lg {{ $c->challan_type === 'অগ্রিম' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">
                            {{ $c->challan_type }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div>
                            <span class="text-gray-400 text-[10px] block">তারিখ:</span>
                            <span class="font-semibold text-gray-700 dark:text-slate-200">{{ $c->date ? \Carbon\Carbon::parse($c->date)->format('d-m-Y') : '—' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 text-[10px] block">মোট ইট:</span>
                            <span class="font-bold text-gray-900 dark:text-white font-mono">{{ toBanglaNum(number_format($cQty)) }} টি</span>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 dark:border-slate-800 pt-2 flex items-center justify-between text-xs font-mono">
                        <div>
                            <span class="text-gray-400 text-[10px] block">মোট বিল</span>
                            <span class="font-black text-[#034C3C] dark:text-emerald-400">৳{{ toBanglaNum(number_format($c->grand_total)) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 text-[10px] block">পরিশোধ</span>
                            <span class="font-bold text-emerald-600">৳{{ toBanglaNum(number_format($c->cash)) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 text-[10px] block">বাকি</span>
                            <span class="font-black {{ $c->due > 0 ? 'text-rose-500' : 'text-gray-500' }}">৳{{ toBanglaNum(number_format($c->due)) }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-3xl p-8 text-center text-gray-400 font-semibold text-xs">
                    কোনো বিক্রি তথ্য পাওয়া যায়নি।
                </div>
            @endforelse

            <div class="pt-2">
                {{ $challans->links() }}
            </div>
        </div>

    </div>
</div>

<script>
    window.addEventListener('reset-flatpickrs', () => {
        document.querySelectorAll('[data-flatpickr]').forEach(el => {
            if (el._flatpickr) {
                el._flatpickr.clear();
            } else {
                el.value = '';
            }
        });
    });
</script>
