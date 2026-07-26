<div class="space-y-5 pb-10">
    
    {{-- Toast Notification --}}
    <template x-teleport="body">
        <div x-data="{ show: false, message: '', timer: null }"
             x-init="
                window.addEventListener('show-toast', e => {
                    let d = e.detail;
                    let msg = '';
                    if (typeof d === 'string') msg = d;
                    else if (d && d.message) msg = d.message;
                    else if (d && d[0]) msg = typeof d[0] === 'string' ? d[0] : (d[0].message || '');
                    if (msg) {
                        message = msg;
                        show = false;
                        if (timer) clearTimeout(timer);
                        setTimeout(() => { show = true; timer = setTimeout(() => show = false, 3000); }, 50);
                    }
                });
             "
             @show-toast.window="
                let d = $event.detail;
                let msg = '';
                if (typeof d === 'string') msg = d;
                else if (d && d.message) msg = d.message;
                else if (d && d[0]) msg = typeof d[0] === 'string' ? d[0] : (d[0].message || '');
                if (msg) {
                    message = msg;
                    show = false;
                    if (timer) clearTimeout(timer);
                    $nextTick(() => { show = true; timer = setTimeout(() => show = false, 3000); });
                }
             "
             x-show="show"
             x-transition:enter="transition ease-out duration-200 transform"
             x-transition:enter-start="-translate-y-10 opacity-0 scale-95"
             x-transition:enter-end="translate-y-0 opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150 transform"
             x-transition:leave-start="translate-y-0 opacity-100 scale-100"
             x-transition:leave-end="-translate-y-10 opacity-0 scale-95"
             x-cloak
             class="fixed top-5 left-1/2 -translate-x-1/2 z-[9999999] px-5 py-3 bg-[#034C3C] text-white rounded-2xl shadow-2xl flex items-center gap-3 font-bold text-xs border border-emerald-400/30">
            <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            <span x-text="message"></span>
            <button @click="show = false" class="text-white/70 hover:text-white ml-2 cursor-pointer">✕</button>
        </div>
    </template>
    
    @if($transaction)
    @php
        $totalLoan = $transaction->amount;
        $paid = $transaction->paid_amount;
        $due = max(0, $totalLoan - $paid);
        $pct = $totalLoan > 0 ? min(100, round(($paid / $totalLoan) * 100)) : 0;
        $isGivenProfile = in_array(trim($transaction->transaction_type), ['দেওয়া', 'দেওয়া']);
    @endphp

    {{-- ========== TOP TITLE BAR ========== --}}
    <div class="flex items-center justify-between bg-white dark:bg-slate-900 rounded-2xl p-4 border border-gray-200/80 dark:border-slate-800 shadow-sm transition-colors">
        <div class="flex items-center gap-3">
            <a href="{{ route('deuna-pauna') }}" wire:navigate
               class="p-2 rounded-xl bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-300 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 hover:text-emerald-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            </a>
            <h2 class="text-lg font-bold text-gray-800 dark:text-white font-sans">{{ $transaction->ledger_name }}</h2>
        </div>
        <span class="px-3.5 py-1.5 rounded-full text-xs font-bold font-sans {{ $isGivenProfile ? 'bg-red-50 text-red-600 dark:bg-red-950/40 dark:text-red-400 border border-red-200 dark:border-red-900' : 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900' }}">
            {{ $isGivenProfile ? 'আমার দেওয়ার হিসাব' : 'আমার নেওয়ার হিসাব' }}
        </span>
    </div>

    {{-- ========== MAIN CONTENT GRID ========== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        
        {{-- ===== LEFT COLUMN (2/3 width) ===== --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- === ১. ব্যক্তিগত তথ্য === --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200/80 dark:border-slate-800 shadow-sm overflow-hidden transition-colors">
                <div class="px-5 py-3.5 border-b border-gray-100 dark:border-slate-800 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#009669]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <h3 class="font-bold text-sm text-gray-800 dark:text-white">ব্যক্তিগত তথ্য</h3>
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                    {{-- Left Details --}}
                    <div class="space-y-4">
                        <div>
                            <span class="text-gray-400 text-[10px] uppercase tracking-wide block font-sans">আইডি</span>
                            <span class="font-bold text-gray-800 dark:text-slate-200 text-sm font-mono">{{ $transaction->id }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 text-[10px] uppercase tracking-wide block font-sans">ঠিকানা</span>
                            <span class="font-semibold text-gray-800 dark:text-slate-200 text-sm font-sans">{{ $transaction->address ?: '—' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 text-[10px] uppercase tracking-wide block font-sans">বিবরণ</span>
                            <span class="font-semibold text-gray-800 dark:text-slate-200 text-xs font-sans leading-relaxed">{{ $transaction->description ?: '—' }}</span>
                        </div>
                    </div>
                    {{-- Right Details --}}
                    <div class="space-y-4">
                        <div>
                            <span class="text-gray-400 text-[10px] uppercase tracking-wide block font-sans">নাম</span>
                            <span class="font-bold text-gray-800 dark:text-slate-200 text-sm font-sans">{{ $transaction->ledger_name }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 text-[10px] uppercase tracking-wide block font-sans">ফোন নম্বর</span>
                            <span class="font-bold text-emerald-700 dark:text-emerald-400 text-sm font-mono">{{ $transaction->phone ?: '—' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- === ২. ঋণ বিবরণ === --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200/80 dark:border-slate-800 shadow-sm overflow-hidden transition-colors">
                <div class="px-5 py-3.5 border-b border-gray-100 dark:border-slate-800 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="font-bold text-sm text-gray-800 dark:text-white">ঋণ বিবরণ</h3>
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                    <div class="space-y-4">
                        <div>
                            <span class="text-gray-400 text-[10px] uppercase tracking-wide block font-sans">{{ $isGivenProfile ? 'দেওয়ার তারিখ' : 'পাওয়ার তারিখ' }}</span>
                            <span class="font-bold text-gray-800 dark:text-slate-200 font-sans">
                                {{ $transaction->transaction_date ? $transaction->transaction_date->format('d F, Y') : ($transaction->created_at ? $transaction->created_at->format('d F, Y') : '—') }}
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-400 text-[10px] uppercase tracking-wide block font-sans">পরিশোধের তারিখ</span>
                            <span class="font-bold text-amber-600 dark:text-amber-400 font-sans">
                                {{ $transaction->due_date ? $transaction->due_date->format('d F, Y') : '—' }}
                            </span>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <span class="text-gray-400 text-[10px] uppercase tracking-wide block font-sans">সাক্ষী ১</span>
                            <span class="font-semibold text-gray-800 dark:text-slate-200 font-sans">{{ $transaction->row1 ?: '—' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 text-[10px] uppercase tracking-wide block font-sans">সাক্ষী ২</span>
                            <span class="font-semibold text-gray-800 dark:text-slate-200 font-sans">{{ $transaction->row2 ?: '—' }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ===== RIGHT COLUMN (1/3 width) ===== --}}
        <div class="space-y-5">

    {{-- Toast Notification --}}
    <template x-teleport="body">
        <div x-data="{ show: false, message: '', timer: null }"
             x-init="
                window.addEventListener('show-toast', e => {
                    let d = e.detail;
                    let msg = '';
                    if (typeof d === 'string') msg = d;
                    else if (d && d.message) msg = d.message;
                    else if (d && d[0]) msg = typeof d[0] === 'string' ? d[0] : (d[0].message || '');
                    if (msg) {
                        message = msg;
                        show = false;
                        if (timer) clearTimeout(timer);
                        setTimeout(() => { show = true; timer = setTimeout(() => show = false, 3000); }, 50);
                    }
                });
             "
             @show-toast.window="
                let d = $event.detail;
                let msg = '';
                if (typeof d === 'string') msg = d;
                else if (d && d.message) msg = d.message;
                else if (d && d[0]) msg = typeof d[0] === 'string' ? d[0] : (d[0].message || '');
                if (msg) {
                    message = msg;
                    show = false;
                    if (timer) clearTimeout(timer);
                    $nextTick(() => { show = true; timer = setTimeout(() => show = false, 3000); });
                }
             "
             x-show="show"
             x-transition:enter="transition ease-out duration-200 transform"
             x-transition:enter-start="-translate-y-10 opacity-0 scale-95"
             x-transition:enter-end="translate-y-0 opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150 transform"
             x-transition:leave-start="translate-y-0 opacity-100 scale-100"
             x-transition:leave-end="-translate-y-10 opacity-0 scale-95"
             x-cloak
             class="fixed top-5 left-1/2 -translate-x-1/2 z-[9999999] px-5 py-3 bg-[#034C3C] text-white rounded-2xl shadow-2xl flex items-center gap-3 font-bold text-xs border border-emerald-400/30">
            <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            <span x-text="message"></span>
            <button @click="show = false" class="text-white/70 hover:text-white ml-2 cursor-pointer">✕</button>
        </div>
    </template>

            {{-- === ৩. দ্রুত পদক্ষেপ (Action Buttons) === --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200/80 dark:border-slate-800 shadow-sm overflow-hidden transition-colors">
                <div class="px-5 py-3.5 border-b border-gray-100 dark:border-slate-800 flex items-center gap-2">
                    <svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <h3 class="font-bold text-sm text-gray-800 dark:text-white">দ্রুত পদক্ষেপ</h3>
                </div>
                <div class="p-4 space-y-3">
                    {{-- Cyan Button: + নতুন লেনদেন --}}
                    <button type="button" wire:click="openNewLoanModal"
                        class="w-full py-3 px-4 bg-[#00838F] hover:bg-[#006064] text-white font-bold text-xs rounded-xl shadow-sm transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        + নতুন লেনদেন
                    </button>
                    {{-- Green Button: ঋণ পরিশোধ --}}
                    <button type="button" wire:click="openPayModal"
                        class="w-full py-3 px-4 bg-[#4CAF50] hover:bg-[#388E3C] text-white font-bold text-xs rounded-xl shadow-sm transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        ঋণ পরিশোধ
                    </button>
                </div>
            </div>

            {{-- === ৪. আর্থিক সারাংশ === --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200/80 dark:border-slate-800 shadow-sm overflow-hidden transition-colors">
                <div class="px-5 py-3.5 border-b border-gray-100 dark:border-slate-800 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <h3 class="font-bold text-sm text-gray-800 dark:text-white">আর্থিক সারাংশ</h3>
                </div>
                <div class="p-5 space-y-4">
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-gray-500 font-semibold font-sans">মোট ঋণ:</span>
                        <span class="font-bold text-gray-800 dark:text-slate-200 font-mono text-sm">৳ {{ number_format($totalLoan, 0) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-gray-500 font-semibold font-sans">পরিশোধিত:</span>
                        <span class="font-bold text-emerald-600 dark:text-emerald-400 font-mono text-sm">৳ {{ number_format($paid, 0) }}</span>
                    </div>

                    {{-- Large Red Box for Remaining Balance --}}
                    <div class="bg-gray-50 dark:bg-slate-950/60 rounded-2xl p-5 text-center border border-gray-100 dark:border-slate-800 shadow-inner">
                        <span class="text-xs text-gray-400 uppercase font-bold tracking-wider font-sans block mb-1">অবশিষ্ট</span>
                        <span class="text-4xl font-black text-rose-600 dark:text-rose-400 font-mono">
                            ৳ {{ number_format($due, 0) }}
                        </span>
                    </div>

                    {{-- Dynamic Progress Bar --}}
                    <div class="space-y-1.5">
                        <div class="flex justify-between items-center text-[10px] text-gray-400 font-sans">
                            <span class="font-bold text-gray-500">অবগতি</span>
                            <span class="font-mono font-bold text-emerald-600 dark:text-emerald-400">{{ $pct }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-slate-800 rounded-full h-2.5 overflow-hidden">
                            <div class="h-2.5 bg-gradient-to-r from-emerald-500 to-[#009669] rounded-full transition-all duration-500"
                                 style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ========== 📊 লেনদেন ইতিহাস TABLE ========== --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200/80 dark:border-slate-800 shadow-sm overflow-hidden transition-colors mt-6">
        <div class="px-5 py-3.5 border-b border-gray-100 dark:border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="font-bold text-sm text-gray-800 dark:text-white">লেনদেন ইতিহাস</h3>
            </div>
            <span class="text-xs text-gray-400 font-sans">মোট {{ $histories->total() }} টি ইতিহাস রেকর্ড</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-gray-50 dark:bg-slate-950 text-gray-500 dark:text-slate-400 font-bold uppercase border-b border-gray-100 dark:border-slate-800">
                        <th class="py-3 px-4">তারিখ</th>
                        <th class="py-3 px-4">বিবরণ</th>
                        <th class="py-3 px-4 text-right">দেওয়া</th>
                        <th class="py-3 px-4 text-right">নেওয়া</th>
                        <th class="py-3 px-4 text-right">পরিশোধ</th>
                        <th class="py-3 px-4 text-right">বাকি</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800/60 font-sans">
                    @forelse($histories as $h)
                        @php
                            $gVal = $h->given_amount;
                            $rVal = $h->received_amount;
                            if ($h->type === 'initial' && $gVal == 0 && $rVal == 0) {
                                if (in_array(trim($transaction->transaction_type), ['দেওয়া', 'দেওয়া'])) {
                                    $gVal = $transaction->amount;
                                } else {
                                    $rVal = $transaction->amount;
                                }
                            }
                        @endphp
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="py-3 px-4 font-semibold text-gray-700 dark:text-slate-300 whitespace-nowrap">
                                {{ $h->transaction_date ? $h->transaction_date->format('d F, Y') : ($h->created_at ? $h->created_at->format('d F, Y') : '—') }}
                            </td>
                            <td class="py-3 px-4 text-gray-600 dark:text-slate-400 max-w-sm">
                                {{ $h->description ?: '—' }}
                            </td>
                            <td class="py-3 px-4 text-right font-mono font-bold text-red-600 dark:text-red-400">
                                {{ $gVal > 0 ? '৳ ' . number_format($gVal, 0) : '—' }}
                            </td>
                            <td class="py-3 px-4 text-right font-mono font-bold text-emerald-600 dark:text-emerald-400">
                                {{ $rVal > 0 ? '৳ ' . number_format($rVal, 0) : '—' }}
                            </td>
                            <td class="py-3 px-4 text-right font-mono font-bold text-emerald-600 dark:text-emerald-400">
                                {{ $h->paid_amount > 0 ? '৳ ' . number_format($h->paid_amount, 0) : '—' }}
                            </td>
                            <td class="py-3 px-4 text-right font-mono font-bold text-rose-600 dark:text-rose-400">
                                ৳ {{ number_format($h->balance, 0) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400 dark:text-slate-500">কোনো ইতিহাস পাওয়া যায়নি।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Table Footer for History List --}}
        @if($histories->total() > 0)
        <div class="px-4 py-3 border-t border-gray-100 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-3 bg-gray-50/50 dark:bg-slate-950/30">
            <p class="text-xs text-gray-500 dark:text-slate-400 font-sans whitespace-nowrap">
                Showing {{ $histories->firstItem() }} to {{ $histories->lastItem() }} of {{ $histories->total() }} results
            </p>

            <div class="flex items-center gap-3">
                {{-- Sort / Per Page Selector --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" type="button"
                            class="flex items-center gap-1.5 px-3 py-1.5 bg-white dark:bg-slate-800 text-gray-700 dark:text-white font-bold rounded-lg text-xs border border-gray-200 dark:border-slate-700 transition-all cursor-pointer">
                        <span>{{ $historyPerPage > 9999 ? 'সব (All)' : $historyPerPage . ' / পেজ' }}</span>
                        <svg class="w-3.5 h-3.5 text-gray-500 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" @click.outside="open = false"
                         class="absolute bottom-full mb-1.5 right-0 z-[999] w-32 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl shadow-xl overflow-hidden"
                         x-cloak>
                        <div class="py-1">
                            <button type="button" wire:click="setHistoryPerPage(10)" @click="open = false"
                                    class="w-full text-left px-3 py-2 text-xs font-semibold transition-all cursor-pointer {{ $historyPerPage == 10 ? 'bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                                10 রেকর্ড
                            </button>
                            <button type="button" wire:click="setHistoryPerPage(30)" @click="open = false"
                                    class="w-full text-left px-3 py-2 text-xs font-semibold transition-all cursor-pointer {{ $historyPerPage == 30 ? 'bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                                30 রেকর্ড
                            </button>
                            <button type="button" wire:click="setHistoryPerPage(100)" @click="open = false"
                                    class="w-full text-left px-3 py-2 text-xs font-semibold transition-all cursor-pointer {{ $historyPerPage == 100 ? 'bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                                100 রেকর্ড
                            </button>
                            <button type="button" wire:click="setHistoryPerPage(999999)" @click="open = false"
                                    class="w-full text-left px-3 py-2 text-xs font-semibold transition-all cursor-pointer {{ $historyPerPage > 9999 ? 'bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                                সব (All)
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Page Buttons --}}
                <div class="flex items-center gap-1">
                    @if($histories->onFirstPage())
                        <button disabled class="p-1.5 rounded-lg border border-gray-200 dark:border-slate-800 text-gray-300 dark:text-slate-700 cursor-not-allowed">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                        </button>
                    @else
                        <button wire:click="previousPage('historyPage')" class="p-1.5 rounded-lg border border-gray-200 dark:border-slate-800 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-300 cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                        </button>
                    @endif

                    @php
                        $hStart = max(1, $histories->currentPage() - 1);
                        $hEnd   = min($histories->lastPage(), $histories->currentPage() + 1);
                    @endphp
                    @for($page = $hStart; $page <= $hEnd; $page++)
                        @if($page == $histories->currentPage())
                            <span class="px-2.5 py-1 bg-emerald-50 dark:bg-emerald-950/30 text-[#034C3C] dark:text-emerald-400 font-bold rounded-lg text-xs border border-emerald-200 dark:border-emerald-900 font-mono">{{ $page }}</span>
                        @else
                            <button wire:click="gotoPage({{ $page }}, 'historyPage')" class="px-2.5 py-1 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-300 font-bold rounded-lg text-xs border border-gray-200 dark:border-slate-700 font-mono cursor-pointer">{{ $page }}</button>
                        @endif
                    @endfor

                    @if($histories->hasMorePages())
                        <button wire:click="nextPage('historyPage')" class="p-1.5 rounded-lg border border-gray-200 dark:border-slate-800 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-300 cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                        </button>
                    @else
                        <button disabled class="p-1.5 rounded-lg border border-gray-200 dark:border-slate-800 text-gray-300 dark:text-slate-700 cursor-not-allowed">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                        </button>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    @else
    <div class="text-center py-20 text-gray-400">
        <p>হিসাব পাওয়া যায়নি।</p>
        <a href="{{ route('deuna-pauna') }}" wire:navigate class="text-[#009669] hover:underline text-sm mt-2 inline-block">← দেনা-পাওনায় ফিরুন</a>
    </div>
    @endif

    {{-- ========== MODAL 1: 🟢 ঋণ পরিশোধ (টাকা ফেরত পাওয়ার/দেওয়ার হিসাব) MODAL ========== --}}
    <template x-teleport="body">
        <div x-data="{ open: @entangle('showPayModal') }"
             x-show="open"
             @click.self="$wire.set('showPayModal', false)"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[70] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
             x-cloak>
            <div x-show="open"
                 x-transition:enter="transition ease-out duration-250 transform"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="bg-white dark:bg-slate-900 rounded-2xl w-full max-w-md border border-gray-200 dark:border-slate-700 shadow-2xl overflow-hidden">
                {{-- Green Header --}}
                <div class="bg-[#4CAF50] px-6 py-4 flex items-center justify-between text-white">
                    <h3 class="font-bold text-sm font-sans">
                        {{ $transaction && $transaction->transaction_type === 'দেওয়া' ? 'টাকা ফেরত দেওয়ার হিসাব' : 'টাকা ফেরত পাওয়ার হিসাব' }}
                    </h3>
                    <button type="button" @click="$wire.set('showPayModal', false)" class="text-white/80 hover:text-white cursor-pointer text-lg font-bold">✕</button>
                </div>
                <div class="p-6 space-y-4 text-xs">
                    {{-- 1. টাকা পাওয়া (Readonly Current Due) --}}
                    <div>
                        <label class="block font-bold text-emerald-700 dark:text-emerald-400 mb-1 font-sans">টাকা পাওয়া</label>
                        <input type="text" readonly value="{{ number_format($due, 0) }}"
                               class="w-full py-2 px-3 rounded-xl border border-emerald-200 dark:border-emerald-900 bg-emerald-50/50 dark:bg-slate-950 font-mono font-bold text-emerald-800 dark:text-emerald-300">
                    </div>
                    {{-- 2. টাকা প্রদানের পরিমাণ --}}
                    <div>
                        <label class="block font-bold text-emerald-700 dark:text-emerald-400 mb-1 font-sans">টাকা প্রদানের পরিমাণ <span class="text-red-500">*</span></label>
                        <input type="text" wire:model.live="payAmount" placeholder="০.০০"
                               oninput="this.value = this.value.replace(/[^0-9.]/g, '')"
                               class="w-full py-2 px-3 rounded-xl border border-gray-250 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 font-mono font-bold text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500">
                        @error('payAmount') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                    </div>
                    {{-- 3. প্রদেয় টাকা বাকি রবে (Calculated Remaining) --}}
                    <div>
                        <label class="block font-bold text-red-600 dark:text-red-400 mb-1 font-sans">প্রদেয় টাকা বাকি রবে</label>
                        @php
                            $calcPay = is_numeric($payAmount) ? (float)$payAmount : 0;
                            $remAfterPay = max(0, $due - $calcPay);
                        @endphp
                        <input type="text" readonly value="{{ number_format($remAfterPay, 0) }}"
                               class="w-full py-2 px-3 rounded-xl border border-red-200 dark:border-red-900 bg-red-50/40 dark:bg-slate-950 font-mono font-bold text-red-600 dark:text-red-400">
                    </div>
                    {{-- 4. পরবর্তী পরিশোধের তারিখ --}}
                    <div>
                        <label class="block font-bold text-gray-600 dark:text-gray-400 mb-1 font-sans">পরবর্তী পরিশোধের তারিখ</label>
                        <div class="relative flex items-center">
                            <input type="text"
                                   data-flatpickr
                                   data-wire-prop="nextPayDate"
                                   data-default="{{ $nextPayDate }}"
                                   wire:model="nextPayDate"
                                   placeholder="পরবর্তী তারিখ বেছে নিন"
                                   readonly
                                   class="w-full py-2 pl-3 pr-9 rounded-xl border border-gray-250 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 font-sans font-semibold text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 cursor-pointer">
                            <span class="absolute right-2.5 text-emerald-600 pointer-events-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            </span>
                        </div>
                    </div>
                    {{-- 5. মন্তব্য --}}
                    <div>
                        <label class="block font-bold text-gray-600 dark:text-gray-400 mb-1 font-sans">মন্তব্য</label>
                        <textarea wire:model="payNotes" rows="2" placeholder="টাকা প্রদানের সাপেক্ষে কিছু টাকা বিলম্ব রাখবে না পরিশোধ করে দেওয়ার নিতে পারে খবর"
                                  class="w-full py-2 px-3 rounded-xl border border-gray-250 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 font-sans resize-none"></textarea>
                    </div>
                </div>
                {{-- Footer --}}
                <div class="px-6 pb-6 flex items-center justify-end gap-3 border-t border-gray-100 dark:border-slate-800 pt-4">
                    <button type="button" @click="$wire.set('payAmount', ''); $wire.set('payNotes', '')"
                            class="px-4 py-2 text-xs font-bold rounded-xl border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800 cursor-pointer font-sans">
                        রিসেট
                    </button>
                    <button type="button" wire:click="savePay"
                            class="px-6 py-2 bg-[#4CAF50] hover:bg-[#388E3C] text-white text-xs font-bold rounded-xl cursor-pointer transition-all active:scale-95 font-sans flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        সেভ করুন
                    </button>
                </div>
            </div>
        </div>
    </template>

    {{-- ========== MODAL 2: 🔷 + নতুন লেনদেন (নতুন করে টাকা নেওয়ার/দেওয়ার হিসাব) MODAL ========== --}}
    <template x-teleport="body">
        <div x-data="{ open: @entangle('showNewLoanModal') }"
             x-show="open"
             @click.self="$wire.set('showNewLoanModal', false)"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[70] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
             x-cloak>
            <div x-show="open"
                 x-transition:enter="transition ease-out duration-250 transform"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="bg-white dark:bg-slate-900 rounded-2xl w-full max-w-md border border-gray-200 dark:border-slate-700 shadow-2xl overflow-hidden">
                {{-- Cyan Header --}}
                <div class="bg-[#00838F] px-6 py-4 flex items-center justify-between text-white">
                    <h3 class="font-bold text-sm font-sans">
                        {{ $transaction && $transaction->transaction_type === 'দেওয়া' ? 'নতুন করে টাকা দেওয়ার হিসাব' : 'নতুন করে টাকা নেওয়ার হিসাব' }}
                    </h3>
                    <button type="button" @click="$wire.set('showNewLoanModal', false)" class="text-white/80 hover:text-white cursor-pointer text-lg font-bold">✕</button>
                </div>
                <div class="p-6 space-y-4 text-xs">
                    {{-- 1. টাকা বাকি (Current Balance Display) --}}
                    <div>
                        <label class="block font-bold text-cyan-700 dark:text-cyan-400 mb-1 font-sans">টাকা বাকি</label>
                        <input type="text" readonly value="{{ number_format($due, 0) }}"
                               class="w-full py-2 px-3 rounded-xl border border-cyan-200 dark:border-cyan-900 bg-cyan-50/50 dark:bg-slate-950 font-mono font-bold text-cyan-800 dark:text-cyan-300">
                    </div>
                    {{-- 2. নতুন করে টাকার হিসাব --}}
                    <div>
                        <label class="block font-bold text-cyan-700 dark:text-cyan-400 mb-1 font-sans">নতুন করে টাকার হিসাব <span class="text-red-500">*</span></label>
                        <input type="text" wire:model.live="newLoanAmount" placeholder="০.০০"
                               oninput="this.value = this.value.replace(/[^0-9.]/g, '')"
                               class="w-full py-2 px-3 rounded-xl border border-gray-250 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 font-mono font-bold text-gray-800 dark:text-white focus:outline-none focus:border-cyan-600">
                        @error('newLoanAmount') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                    </div>
                    {{-- 3. সর্বমোট টাকা বাকি (Calculated New Total) --}}
                    <div>
                        <label class="block font-bold text-rose-600 dark:text-rose-400 mb-1 font-sans">সর্বমোট টাকা বাকি</label>
                        @php
                            $calcNew = is_numeric($newLoanAmount) ? (float)$newLoanAmount : 0;
                            $newTotalDue = $due + $calcNew;
                        @endphp
                        <input type="text" readonly value="{{ number_format($newTotalDue, 0) }}"
                               class="w-full py-2 px-3 rounded-xl border border-rose-200 dark:border-rose-900 bg-rose-50/40 dark:bg-slate-950 font-mono font-bold text-rose-600 dark:text-rose-400">
                    </div>
                    {{-- 4. পরবর্তী তারিখ দিতে পারেন --}}
                    <div>
                        <label class="block font-bold text-gray-600 dark:text-gray-400 mb-1 font-sans">পরবর্তী তারিখ দিতে পারেন</label>
                        <div class="relative flex items-center">
                            <input type="text"
                                   data-flatpickr
                                   data-wire-prop="newLoanDueDate"
                                   data-default="{{ $newLoanDueDate }}"
                                   wire:model="newLoanDueDate"
                                   placeholder="তারিখ নির্বাচন করুন"
                                   readonly
                                   class="w-full py-2 pl-3 pr-9 rounded-xl border border-gray-250 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 font-sans font-semibold text-gray-800 dark:text-white focus:outline-none focus:border-cyan-600 cursor-pointer">
                            <span class="absolute right-2.5 text-cyan-600 pointer-events-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            </span>
                        </div>
                    </div>
                    {{-- 5. বর্ণনা --}}
                    <div>
                        <label class="block font-bold text-gray-600 dark:text-gray-400 mb-1 font-sans">বর্ণনা</label>
                        <textarea wire:model="newLoanDescription" rows="2" placeholder="টাকা দেওয়ার সাপেক্ষে কিছু টাকা বিলম্ব রাখবে না পরিশোধ করে দেওয়ার নিতে পারে খবর"
                                  class="w-full py-2 px-3 rounded-xl border border-gray-250 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-cyan-600 font-sans resize-none"></textarea>
                    </div>
                </div>
                {{-- Footer --}}
                <div class="px-6 pb-6 flex items-center justify-end gap-3 border-t border-gray-100 dark:border-slate-800 pt-4">
                    <button type="button" @click="$wire.set('newLoanAmount', ''); $wire.set('newLoanDescription', '')"
                            class="px-4 py-2 text-xs font-bold rounded-xl border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800 cursor-pointer font-sans">
                        রিসেট
                    </button>
                    <button type="button" wire:click="saveNewLoan"
                            class="px-6 py-2 bg-[#00838F] hover:bg-[#006064] text-white text-xs font-bold rounded-xl cursor-pointer transition-all active:scale-95 font-sans flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        সেভ করুন
                    </button>
                </div>
            </div>
        </div>
    </template>

</div>
