@php
    if (!function_exists('toBanglaNum')) {
        function toBanglaNum($num) {
            if ($num === null || $num === '') return '';
            $numStr = (string)$num;
            if (is_numeric($num)) {
                $numStr = rtrim(rtrim(number_format((float)$num, 2, '.', ''), '0'), '.');
            }
            $eng = ['0','1','2','3','4','5','6','7','8','9'];
            $bng = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
            return str_replace($eng, $bng, $numStr);
        }
    }
@endphp

<div class="space-y-6">

    <!-- Top Action Bar & Stats -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-slate-900 p-4 rounded-2xl shadow-sm border border-gray-200/80 dark:border-slate-800 transition-colors">
        
        <!-- Left Side: SMS Recharge Button & Stat Badges -->
        <div class="flex flex-wrap items-center gap-3">
            <!-- SMS Buy Button -->
            <button 
                type="button" 
                wire:click="openRechargeModal"
                class="px-4 py-2 bg-[#009669] hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-2 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                </svg>
                SMS কিনুন
            </button>

            <!-- Remaining SMS Badge -->
            <div class="px-3.5 py-1.5 bg-rose-50 dark:bg-rose-950/40 border border-rose-200/60 dark:border-rose-900/50 rounded-xl text-rose-700 dark:text-rose-300 text-xs font-semibold flex items-center gap-1.5 shadow-xs">
                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
                <span>অবশিষ্ট: <strong class="font-mono">{{ $remainingSms }}</strong></span>
            </div>

            <!-- Sent SMS Badge -->
            <div class="px-3.5 py-1.5 bg-sky-50 dark:bg-sky-950/40 border border-sky-200/60 dark:border-sky-900/50 rounded-xl text-sky-700 dark:text-sky-300 text-xs font-semibold flex items-center gap-1.5 shadow-xs">
                <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
                <span>সেন্ট: <strong class="font-mono">{{ $sentSms }}</strong></span>
            </div>
        </div>

        <!-- Right Side: Search Input -->
        <div class="w-full md:w-72 relative">
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search"
                placeholder="SMS খুঁজুন..." 
                class="w-full pl-3 pr-9 py-2 text-xs bg-gray-50 dark:bg-slate-800 text-gray-800 dark:text-white rounded-xl border border-gray-200 dark:border-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all placeholder:text-gray-400"
            >
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>

    </div>

    <!-- Main SMS Logs Table Card -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200/80 dark:border-slate-800 transition-colors duration-300 overflow-hidden">
        
        <!-- Table View (Desktop) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-[#009669] text-white text-xs font-bold uppercase tracking-wider">
                        <th class="py-3 px-4 w-44">সময়</th>
                        <th class="py-3 px-4 w-36">ফোন নম্বর</th>
                        <th class="py-3 px-4">মেসেজ</th>
                        <th class="py-3 px-4 text-center w-24">খরচ</th>
                        <th class="py-3 px-4 text-center w-28">স্ট্যাটাস</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800/60 font-sans">
                    @forelse($logs as $log)
                        <tr class="hover:bg-emerald-50/20 dark:hover:bg-slate-800/40 transition-colors">
                            <!-- সময় -->
                            <td class="py-3 px-4 font-mono text-xs text-gray-600 dark:text-slate-400 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($log->created_at)->format('d M Y, h:i A') }}
                            </td>
                            <!-- ফোন নম্বর -->
                            <td class="py-3 px-4 font-mono font-bold text-gray-800 dark:text-slate-200">
                                {{ $log->phone }}
                            </td>
                            <!-- মেসেজ -->
                            <td class="py-3 px-4 text-xs text-gray-700 dark:text-slate-300 leading-relaxed max-w-xl">
                                {{ $log->message }}
                            </td>
                            <!-- খরচ -->
                            <td class="py-3 px-4 text-center font-mono font-bold text-gray-700 dark:text-slate-300">
                                ৳ {{ toBanglaNum($log->cost) }}
                            </td>
                            <!-- স্ট্যাটাস -->
                            <td class="py-3 px-4 text-center">
                                @if(strtolower($log->status) === 'failed')
                                    <span class="inline-block px-2.5 py-0.5 rounded text-[11px] font-semibold bg-rose-50 text-rose-500 border border-rose-200 dark:bg-rose-950/40 dark:border-rose-900/50">
                                        Failed
                                    </span>
                                @else
                                    <span class="inline-block px-2.5 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-600 border border-emerald-200 dark:bg-emerald-950/40 dark:border-emerald-900/50">
                                        Sent
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-400 dark:text-slate-500 text-sm">
                                কোনো এসএমএস লগ পাওয়া যায়নি।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile View (Card List) -->
        <div class="md:hidden divide-y divide-gray-100 dark:divide-slate-800">
            @forelse($logs as $log)
                <div class="p-4 space-y-2 bg-white dark:bg-slate-900">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-mono text-gray-500 dark:text-slate-400">
                            {{ \Carbon\Carbon::parse($log->created_at)->format('d M Y, h:i A') }}
                        </span>
                        @if(strtolower($log->status) === 'failed')
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-rose-50 text-rose-500 border border-rose-200">
                                Failed
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-600 border border-emerald-200">
                                Sent
                            </span>
                        @endif
                    </div>
                    <div class="font-mono font-bold text-gray-800 dark:text-white text-xs">
                        ফোন: {{ $log->phone }}
                    </div>
                    <p class="text-xs text-gray-600 dark:text-slate-300 leading-relaxed bg-gray-50 dark:bg-slate-800 p-2 rounded-lg">
                        {{ $log->message }}
                    </p>
                    <div class="text-right text-xs font-mono font-semibold text-gray-500 dark:text-slate-400">
                        খরচ: ৳ {{ toBanglaNum($log->cost) }}
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-400 dark:text-slate-500 text-sm">
                    কোনো এসএমএস লগ পাওয়া যায়নি।
                </div>
            @endforelse
        </div>

        <!-- Footer / Pagination -->
        <div class="p-4 border-t border-gray-100 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
            
            <!-- Left Side: Per Page Dropdown -->
            <div class="flex items-center gap-4">
                <!-- Per Page Custom Dropdown -->
                <div x-data="{ openSort: false }" class="relative">
                    <button 
                        @click="openSort = !openSort" 
                        type="button" 
                        class="flex items-center justify-between gap-2 px-3 py-1.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg text-xs text-gray-700 dark:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors focus:outline-none shadow-2xs">
                        <span>{{ toBanglaNum($perPage) }} / পেজ</span>
                        <svg class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': openSort }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div 
                        x-show="openSort" 
                        @click.away="openSort = false" 
                        x-transition 
                        class="absolute left-0 bottom-full mb-1 w-28 bg-slate-900 text-white rounded-xl shadow-xl border border-slate-700 py-1 z-50 overflow-hidden">
                        @foreach([5, 10, 15, 20, 50] as $size)
                            <button 
                                type="button"
                                wire:click="$set('perPage', {{ $size }})" 
                                @click="openSort = false" 
                                class="w-full text-left px-3 py-1.5 text-xs hover:bg-emerald-600 transition-colors flex items-center justify-between {{ $perPage == $size ? 'text-emerald-400 font-bold bg-slate-800' : 'text-slate-300' }}">
                                <span>{{ toBanglaNum($size) }} / পেজ</span>
                                @if($perPage == $size)
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Side: Pagination Links with Gap between text and buttons -->
            <div class="flex items-center gap-4 [&_p]:mr-6 [&_p]:md:mr-8 [&_p]:text-xs [&_div]:gap-3">
                {{ $logs->links() }}
            </div>
        </div>

    </div>

    <!-- SMS Recharge Modal (SMS রিচার্জ) -->
    @if($showRechargeModal)
        <div 
            x-data="{ show: true }"
            x-show="show"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs overflow-y-auto">
            
            <div 
                @click.away="$wire.closeRechargeModal()"
                class="w-full max-w-2xl bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-gray-200 dark:border-slate-800 overflow-hidden my-6">
                
                <!-- Modal Header -->
                <div class="p-5 border-b border-gray-100 dark:border-slate-800 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-800 dark:text-white">
                                SMS রিচার্জ
                            </h3>
                            <p class="text-xs text-gray-400">ব্যালেন্স টপ-আপ</p>
                        </div>
                    </div>

                    <button 
                        type="button" 
                        wire:click="closeRechargeModal" 
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Tabs Header -->
                <div class="px-6 border-b border-gray-100 dark:border-slate-800 flex items-center gap-6 text-xs font-bold">
                    <button 
                        type="button" 
                        wire:click="$set('modalTab', 'payment')"
                        class="py-3 border-b-2 transition-all flex items-center gap-2 cursor-pointer {{ $modalTab === 'payment' ? 'border-[#009669] text-[#009669] dark:text-emerald-400 font-bold' : 'border-transparent text-gray-400 hover:text-gray-600' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                        পেমেন্ট
                    </button>
                    <button 
                        type="button" 
                        wire:click="$set('modalTab', 'history')"
                        class="py-3 border-b-2 transition-all flex items-center gap-2 cursor-pointer {{ $modalTab === 'history' ? 'border-[#009669] text-[#009669] dark:text-emerald-400 font-bold' : 'border-transparent text-gray-400 hover:text-gray-600' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        হিস্ট্রি
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6">
                    @if($modalTab === 'payment')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Left Column: Payment Instructions & Numbers -->
                            <div class="space-y-4">
                                <!-- Warning Note -->
                                <div class="p-3.5 bg-amber-50 dark:bg-amber-950/30 border border-amber-200/70 dark:border-amber-900/50 rounded-2xl text-amber-800 dark:text-amber-300 text-[11px] leading-relaxed flex items-start gap-2.5">
                                    <svg class="w-4 h-4 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    <div>
                                        নিচের নম্বরে টাকা পাঠায়ে TrxID কপি করুন। SMS রেট: ০.৩৫ পয়সা প্রতি এসএমএস।
                                    </div>
                                </div>

                                <!-- Payment Accounts Box -->
                                <div class="space-y-2.5">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="font-bold text-gray-700 dark:text-slate-300">পেমেন্ট নম্বরসমূহ</span>
                                        <button 
                                            type="button" 
                                            wire:click="$toggle('editingNumbers')"
                                            class="text-xs text-emerald-700 dark:text-emerald-400 font-semibold hover:text-emerald-800 transition-colors flex items-center gap-1 bg-emerald-50 dark:bg-emerald-950/60 px-2.5 py-1 rounded-lg border border-emerald-200 dark:border-emerald-900/60 cursor-pointer shadow-2xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                            <span>{{ $editingNumbers ? 'সম্পন্ন' : 'নম্বর পরিবর্তন' }}</span>
                                        </button>
                                    </div>

                                    @if($editingNumbers)
                                        <div class="space-y-2 bg-gray-50 dark:bg-slate-800 p-3 rounded-2xl border border-gray-200 dark:border-slate-700">
                                            <div>
                                                <label class="block text-[10px] text-gray-500 dark:text-slate-400">বিকাশ নম্বর</label>
                                                <input type="text" wire:model="bkashNumber" class="w-full px-2.5 py-1 text-xs bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-lg font-mono text-gray-800 dark:text-white">
                                            </div>
                                            <div>
                                                <label class="block text-[10px] text-gray-500 dark:text-slate-400">নগদ নম্বর</label>
                                                <input type="text" wire:model="nagadNumber" class="w-full px-2.5 py-1 text-xs bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-lg font-mono text-gray-800 dark:text-white">
                                            </div>
                                            <div>
                                                <label class="block text-[10px] text-gray-500 dark:text-slate-400">রকেট নম্বর</label>
                                                <input type="text" wire:model="rocketNumber" class="w-full px-2.5 py-1 text-xs bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-lg font-mono text-gray-800 dark:text-white">
                                            </div>
                                            <button 
                                                type="button" 
                                                wire:click="savePaymentNumbers" 
                                                class="w-full py-1.5 text-xs bg-[#009669] hover:bg-emerald-700 text-white rounded-lg font-bold transition-all cursor-pointer">
                                                সেভ করুন
                                            </button>
                                        </div>
                                    @else
                                        <!-- bKash Box -->
                                        <div class="p-3 bg-gray-50 dark:bg-slate-800/80 border border-gray-200/80 dark:border-slate-700/60 rounded-2xl flex items-center justify-between">
                                            <div class="text-xs">
                                                <span class="font-bold text-gray-700 dark:text-slate-300">বিকাশ</span>
                                                <span class="block font-mono font-bold text-emerald-600 dark:text-emerald-400 text-sm mt-0.5">{{ $bkashNumber }}</span>
                                            </div>
                                            <button 
                                                type="button" 
                                                onclick="navigator.clipboard.writeText('{{ $bkashNumber }}'); window.dispatchEvent(new CustomEvent('show-toast', { detail: { message: 'নম্বর কপি হয়েছে' } }));"
                                                class="px-3 py-1 text-[11px] font-bold text-slate-700 dark:text-slate-200 bg-slate-200/80 dark:bg-slate-700 hover:bg-[#009669] hover:text-white dark:hover:bg-[#009669] dark:hover:text-white rounded-lg transition-colors cursor-pointer border border-slate-300/60 dark:border-slate-600/60 shadow-xs">
                                                কপি করুন
                                            </button>
                                        </div>

                                        <!-- Nagad Box -->
                                        <div class="p-3 bg-gray-50 dark:bg-slate-800/80 border border-gray-200/80 dark:border-slate-700/60 rounded-2xl flex items-center justify-between">
                                            <div class="text-xs">
                                                <span class="font-bold text-gray-700 dark:text-slate-300">নগদ</span>
                                                <span class="block font-mono font-bold text-emerald-600 dark:text-emerald-400 text-sm mt-0.5">{{ $nagadNumber }}</span>
                                            </div>
                                            <button 
                                                type="button" 
                                                onclick="navigator.clipboard.writeText('{{ $nagadNumber }}'); window.dispatchEvent(new CustomEvent('show-toast', { detail: { message: 'নম্বর কপি হয়েছে' } }));"
                                                class="px-3 py-1 text-[11px] font-bold text-slate-700 dark:text-slate-200 bg-slate-200/80 dark:bg-slate-700 hover:bg-[#009669] hover:text-white dark:hover:bg-[#009669] dark:hover:text-white rounded-lg transition-colors cursor-pointer border border-slate-300/60 dark:border-slate-600/60 shadow-xs">
                                                কপি করুন
                                            </button>
                                        </div>

                                        <!-- Rocket Box -->
                                        <div class="p-3 bg-gray-50 dark:bg-slate-800/80 border border-gray-200/80 dark:border-slate-700/60 rounded-2xl flex items-center justify-between">
                                            <div class="text-xs">
                                                <span class="font-bold text-gray-700 dark:text-slate-300">রকেট</span>
                                                <span class="block font-mono font-bold text-emerald-600 dark:text-emerald-400 text-sm mt-0.5">{{ $rocketNumber }}</span>
                                            </div>
                                            <button 
                                                type="button" 
                                                onclick="navigator.clipboard.writeText('{{ $rocketNumber }}'); window.dispatchEvent(new CustomEvent('show-toast', { detail: { message: 'নম্বর কপি হয়েছে' } }));"
                                                class="px-3 py-1 text-[11px] font-bold text-slate-700 dark:text-slate-200 bg-slate-200/80 dark:bg-slate-700 hover:bg-[#009669] hover:text-white dark:hover:bg-[#009669] dark:hover:text-white rounded-lg transition-colors cursor-pointer border border-slate-300/60 dark:border-slate-600/60 shadow-xs">
                                                কপি করুন
                                            </button>
                                        </div>
                                    @endif
                                </div>

                            </div>

                            <!-- Right Column: Recharge Form -->
                            <form wire:submit.prevent="confirmPayment" class="space-y-3.5">
                                
                                <!-- পেমент মেথড (Select Dropdown - Topbar Root Style) -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 dark:text-slate-300 mb-1">পেমেন্ট মেথড</label>
                                    <div x-data="{ open: false }" class="relative">
                                        <button type="button" @click="open = !open" 
                                                class="w-full flex items-center justify-between space-x-2 px-4 py-2 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-900 dark:text-emerald-300 font-semibold rounded-xl text-xs border border-emerald-200 dark:border-emerald-900/60 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all duration-150 cursor-pointer shadow-2xs">
                                            <span x-text="$wire.paymentMethod" class="font-sans font-bold"></span>
                                            <svg class="w-3.5 h-3.5 transition-transform duration-200 text-emerald-700 dark:text-emerald-400" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </button>
                                        
                                        <div x-show="open" 
                                             @click.away="open = false"
                                             x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="transform opacity-0 scale-95"
                                             x-transition:enter-end="transform opacity-100 scale-100"
                                             class="absolute left-0 right-0 mt-1.5 bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-gray-100 dark:border-slate-800 py-1 z-50 text-xs overflow-hidden"
                                             x-cloak>
                                            <template x-for="method in ['বিকাশ', 'নগদ', 'রকেট']">
                                                <button type="button" 
                                                        @click="$wire.set('paymentMethod', method); open = false;" 
                                                        class="w-full text-left px-3.5 py-2 text-gray-700 dark:text-gray-200 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-[#009669] dark:hover:text-emerald-400 font-semibold transition-all font-sans flex items-center justify-between cursor-pointer"
                                                        :class="$wire.paymentMethod === method ? 'bg-emerald-50/80 dark:bg-emerald-950/30 text-[#009669] dark:text-emerald-400 font-bold' : ''">
                                                    <span x-text="method"></span>
                                                    <span x-show="$wire.paymentMethod === method" class="text-xs font-bold">✓</span>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <!-- প্রেরকের নম্বর -->
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 dark:text-slate-300 mb-1">প্রেরকের নম্বর</label>
                                        <input 
                                            type="text" 
                                            wire:model="senderPhone" 
                                            placeholder="প্রেরকের নম্বর" 
                                            class="w-full px-3 py-2 text-xs bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-gray-800 dark:text-white font-mono"
                                        >
                                        @error('senderPhone') <span class="text-[10px] text-rose-500 mt-0.5 block">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Transaction ID -->
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 dark:text-slate-300 mb-1">Transaction ID</label>
                                        <input 
                                            type="text" 
                                            wire:model="trxId" 
                                            placeholder="Transaction ID" 
                                            class="w-full px-3 py-2 text-xs bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-gray-800 dark:text-white font-mono"
                                        >
                                        @error('trxId') <span class="text-[10px] text-rose-500 mt-0.5 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3 items-end">
                                    <!-- টাকার পরিমাণ -->
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 dark:text-slate-300 mb-1">টাকার পরিমাণ</label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center text-xs font-mono font-bold text-gray-400">৳</span>
                                            <input 
                                                type="number" 
                                                wire:model.live="amount" 
                                                placeholder="টাকার পরিমাণ" 
                                                class="w-full pl-7 pr-3 py-2 text-xs bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-gray-800 dark:text-white font-mono"
                                            >
                                        </div>
                                        @error('amount') <span class="text-[10px] text-rose-500 mt-0.5 block">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Dynamic SMS Calculation Badge -->
                                    <div>
                                        @php
                                            $calcSms = floatval($amount) > 0 ? floor(floatval($amount) / 0.35) : 0;
                                        @endphp
                                        <div class="w-full py-2 px-3 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900 rounded-xl text-center">
                                            <span class="text-xs font-bold text-[#009669] dark:text-emerald-400">
                                                SMS পাবেন {{ toBanglaNum(number_format($calcSms, 0)) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Confirm Payment Button -->
                                <button 
                                    type="submit" 
                                    class="w-full py-2.5 px-4 bg-[#009669] hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md transition-all cursor-pointer">
                                    পেমেন্ট কনফার্ম করুন
                                </button>

                            </form>

                        </div>

                    @elseif($modalTab === 'history')
                        
                        <!-- History Table with Vertical Scroll when > 10 rows -->
                        <div class="max-h-[360px] overflow-y-auto border border-gray-100 dark:border-slate-800 rounded-2xl scrollbar-thin scrollbar-thumb-emerald-600">
                            <table class="w-full text-left text-xs">
                                <thead class="sticky top-0 z-10 bg-[#009669] text-white font-bold uppercase">
                                    <tr>
                                        <th class="py-2.5 px-4">তারিখ</th>
                                        <th class="py-2.5 px-4">পেমেন্ট</th>
                                        <th class="py-2.5 px-4 text-center">SMS</th>
                                        <th class="py-2.5 px-4 text-right">টাকা</th>
                                        <th class="py-2.5 px-4 text-center">স্ট্যাটাস</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-slate-800/60 font-sans">
                                    @forelse($recharges as $recharge)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/40 transition-colors">
                                            <td class="py-2.5 px-4 font-mono text-gray-600 dark:text-slate-400">
                                                {{ \Carbon\Carbon::parse($recharge->created_at)->format('d/m/y') }}
                                            </td>
                                            <td class="py-2.5 px-4 font-bold text-gray-800 dark:text-slate-200">
                                                {{ $recharge->payment_method }}
                                            </td>
                                            <td class="py-2.5 px-4 text-center font-mono font-bold text-emerald-600">
                                                {{ toBanglaNum($recharge->sms_count) }}
                                            </td>
                                            <td class="py-2.5 px-4 text-right font-mono font-bold text-gray-800 dark:text-white">
                                                ৳{{ toBanglaNum($recharge->amount) }}
                                            </td>
                                            <td class="py-2.5 px-4 text-center">
                                                @if(strtolower($recharge->status) === 'cancelled')
                                                    <span class="px-2 py-0.5 text-[10px] font-semibold bg-amber-50 text-amber-600 border border-amber-200 rounded">
                                                        Cancelled
                                                    </span>
                                                @elseif(strtolower($recharge->status) === 'approved')
                                                    <span class="px-2 py-0.5 text-[10px] font-semibold bg-emerald-50 text-emerald-600 border border-emerald-200 rounded">
                                                        Approved
                                                    </span>
                                                @else
                                                    <span class="px-2 py-0.5 text-[10px] font-semibold bg-sky-50 text-sky-600 border border-sky-200 rounded">
                                                        Pending
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="py-6 text-center text-gray-400">
                                                কোনো হিস্ট্রি পাওয়া যায়নি।
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    @endif
                </div>

            </div>
        </div>
    @endif

</div>
