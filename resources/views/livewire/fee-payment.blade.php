<div class="space-y-6 font-sans">

    <!-- Toast Popup -->
    <div
        x-data="{ show: false, message: '', type: 'success' }"
        x-init="window.addEventListener('show-toast', e => { message = e.detail.message; type = e.detail.type || 'success'; show = true; setTimeout(() => show = false, 3000); })"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-4"
        class="fixed top-5 left-1/2 -translate-x-1/2 z-[99999] p-4 rounded-xl border shadow-2xl flex items-center gap-3 max-w-sm w-[90vw] md:w-auto"
        :class="type==='danger' ? 'bg-red-50 border-red-200 text-red-800 dark:bg-red-950/90 dark:border-red-900' : 'bg-emerald-50 border-emerald-200 text-emerald-800 dark:bg-[#034C3C]/95 dark:border-[#034C3C] dark:text-emerald-50'"
        x-cloak>
        <span class="p-1.5 rounded-lg bg-emerald-100 text-emerald-600 dark:bg-[#023E31]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
        </span>
        <p class="text-xs font-bold flex-1 font-sans" x-text="message"></p>
        <button @click="show = false" class="text-gray-400 hover:text-gray-600 cursor-pointer ml-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    <!-- Page Header with Method Dropdown -->
    <div class="flex flex-col items-center justify-center gap-4 p-5 bg-red-50/60 dark:bg-red-950/20 border border-red-100 dark:border-red-900/30 rounded-xl">
        <h1 class="text-lg md:text-xl font-extrabold text-red-600 dark:text-red-400 font-sans">সফটওয়্যারের ফি জমা দিন</h1>

        <!-- Root-style Dropdown -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" type="button"
                class="flex items-center justify-between gap-2 px-4 py-1.5 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-900 dark:text-emerald-300 font-semibold rounded-full text-xs border border-emerald-200 dark:border-emerald-900 focus:outline-none transition-all duration-150 cursor-pointer min-w-[130px]">
                <span class="font-sans">
                    পেমেন্টের মাধ্যম:
                    <span class="{{ $method === 'bkash' ? 'text-pink-600 dark:text-pink-400' : 'text-orange-500 dark:text-orange-400' }} font-bold">
                        {{ $method === 'bkash' ? 'বিকাশ' : 'নগদ' }}
                    </span>
                </span>
                <svg class="w-3.5 h-3.5 transition-transform duration-200 text-emerald-700 dark:text-emerald-400 flex-shrink-0" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="open"
                 @click.away="open = false"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute left-0 mt-1.5 w-40 bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-gray-100 dark:border-slate-800 py-1 z-50 text-xs overflow-hidden"
                 x-cloak>
                <button type="button"
                    wire:click="$set('method', 'bkash')"
                    @click="open = false"
                    class="w-full text-left px-3.5 py-2.5 font-semibold transition-all font-sans flex items-center gap-2
                        {{ $method === 'bkash' ? 'bg-emerald-50 dark:bg-emerald-950/10 text-emerald-700 dark:text-emerald-400' : 'text-gray-700 dark:text-gray-200 hover:bg-emerald-50 dark:hover:bg-emerald-950/10 hover:text-emerald-700 dark:hover:text-emerald-400' }}">
                    <span class="w-2 h-2 rounded-full bg-pink-500 flex-shrink-0"></span>
                    বিকাশ
                    @if($method === 'bkash')
                        <svg class="w-3 h-3 ml-auto text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    @endif
                </button>
                <button type="button"
                    wire:click="$set('method', 'nagad')"
                    @click="open = false"
                    class="w-full text-left px-3.5 py-2.5 font-semibold transition-all font-sans flex items-center gap-2
                        {{ $method === 'nagad' ? 'bg-emerald-50 dark:bg-emerald-950/10 text-emerald-700 dark:text-emerald-400' : 'text-gray-700 dark:text-gray-200 hover:bg-emerald-50 dark:hover:bg-emerald-950/10 hover:text-emerald-700 dark:hover:text-emerald-400' }}">
                    <span class="w-2 h-2 rounded-full bg-orange-500 flex-shrink-0"></span>
                    নগদ
                    @if($method === 'nagad')
                        <svg class="w-3 h-3 ml-auto text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    @endif
                </button>
            </div>
        </div>
    </div>

    <!-- Main 2-col Layout -->
    <div class="flex flex-col-reverse lg:grid lg:grid-cols-5 gap-6">

        <!-- LEFT: Live Preview Card -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-slate-900 rounded-2xl border {{ $method === 'bkash' ? 'border-pink-200 dark:border-pink-900/40' : 'border-orange-200 dark:border-orange-900/40' }} shadow-md overflow-hidden h-full">

                <!-- Logo Section -->
                <div class="flex flex-col items-center justify-center py-10 px-6
                    {{ $method === 'bkash' ? 'bg-gradient-to-br from-pink-50 to-red-50 dark:from-pink-950/20 dark:to-red-950/20' : 'bg-gradient-to-br from-orange-50 to-amber-50 dark:from-orange-950/20 dark:to-amber-950/20' }}
                    border-b {{ $method === 'bkash' ? 'border-pink-100 dark:border-pink-900/30' : 'border-orange-100 dark:border-orange-900/30' }}">

                    @if ($method === 'bkash')
                        <div class="mb-3">
                            <img src="{{ asset('images/bkash.webp') }}" alt="bKash Logo" class="h-14 w-auto object-contain rounded-md">
                        </div>
                        <p class="text-xs text-pink-500 font-semibold font-sans">মোবাইল ব্যাংকিং সেবা</p>
                    @else
                        <div class="mb-3">
                            <img src="{{ asset('images/nagad.png') }}" alt="Nagad Logo" class="h-14 w-auto object-contain rounded-md">
                        </div>
                        <p class="text-xs text-orange-500 font-semibold font-sans">মোবাইল ব্যাংকিং সেবা</p>
                    @endif
                </div>

                <!-- Live Info Preview -->
                <div class="p-6 space-y-4">
                    <div class="space-y-3">
                        <div class="flex items-start justify-between gap-2 text-sm">
                            <span class="font-semibold text-gray-500 dark:text-slate-400 whitespace-nowrap">ব্যাংকের নাম</span>
                            <span class="font-bold {{ $method === 'bkash' ? 'text-pink-600 dark:text-pink-400' : 'text-orange-600 dark:text-orange-400' }} text-right">
                                {{ $method === 'bkash' ? 'বিকাশ' : 'নগদ' }}
                            </span>
                        </div>
                        <div class="flex items-start justify-between gap-2 text-sm">
                            <span class="font-semibold text-gray-500 dark:text-slate-400 whitespace-nowrap">অ্যাকাউন্ট নম্বর</span>
                            <span class="font-bold text-gray-800 dark:text-white text-right font-mono">
                                {{ $method === 'bkash' ? '01797-926335' : '01912-345678' }}
                            </span>
                        </div>
                        @if($userId || $accountNumber || $transactionId || $amount)
                        <div class="border-t border-gray-100 dark:border-slate-800 pt-3 space-y-3">
                            @if($userId)
                            <div class="flex items-start justify-between gap-2 text-sm">
                                <span class="font-semibold text-gray-500 dark:text-slate-400">ইউজার আইডি</span>
                                <span class="font-bold text-gray-800 dark:text-white text-right">{{ $userId }}</span>
                            </div>
                            @endif
                            @if($accountNumber)
                            <div class="flex items-start justify-between gap-2 text-sm">
                                <span class="font-semibold text-gray-500 dark:text-slate-400">আপনার নম্বর</span>
                                <span class="font-bold text-gray-800 dark:text-white text-right font-mono">{{ $accountNumber }}</span>
                            </div>
                            @endif
                            @if($transactionId)
                            <div class="flex items-start justify-between gap-2 text-sm">
                                <span class="font-semibold text-gray-500 dark:text-slate-400">TrxID</span>
                                <span class="font-bold text-gray-800 dark:text-white text-right font-mono">{{ $transactionId }}</span>
                            </div>
                            @endif
                            @if($amount)
                            <div class="flex items-start justify-between gap-2 text-sm">
                                <span class="font-semibold {{ $method === 'bkash' ? 'text-pink-600 dark:text-pink-400' : 'text-orange-600 dark:text-orange-400' }}">ফি বাকিয়া</span>
                                <span class="font-extrabold text-gray-900 dark:text-white text-right text-base">৳ {{ number_format((float)$amount, 0, '.', ',') }}</span>
                            </div>
                            @endif
                        </div>
                        @endif
                        <div class="flex items-start justify-between gap-2 text-sm pt-1">
                            <span class="font-semibold text-red-500">নোট</span>
                            <span class="font-semibold text-gray-600 dark:text-slate-300 text-right text-xs leading-relaxed">
                                হেল্প লাইন- {{ $method === 'bkash' ? '01918908070' : '01711234567' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT: Form -->
        <div class="lg:col-span-3">
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-md p-6 md:p-8 space-y-5">

                <!-- User ID -->
                <div>
                    <label class="block text-xs font-bold text-gray-600 dark:text-slate-300 mb-1.5 font-sans">ইউজার আইডি <span class="text-red-500">*</span></label>
                    <input type="text" wire:model.live="userId" placeholder="আপনার ইউজার আইডি লিখুন"
                        class="w-full px-4 py-3 rounded-xl border {{ $errors->has('userId') ? 'border-red-400 bg-red-50/30 dark:bg-red-950/10' : 'border-gray-200 dark:border-slate-700' }} bg-gray-50 dark:bg-slate-800 text-gray-800 dark:text-white text-sm font-semibold font-sans focus:outline-none focus:ring-2 focus:ring-[#034C3C]/30 focus:border-[#034C3C] dark:focus:border-emerald-500 transition-all">
                    @error('userId')
                        <p class="text-red-500 text-xs mt-1.5 font-semibold font-sans flex items-center gap-1">
                            <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Account Number — numbers only -->
                <div>
                    <label class="block text-xs font-bold text-gray-600 dark:text-slate-300 mb-1.5 font-sans">
                        যে {{ $method === 'bkash' ? 'বিকাশ' : 'নগদ' }} নম্বর থেকে টাকা পাঠাবেন <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="tel"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        wire:model.live="accountNumber"
                        placeholder="01XXXXXXXXX"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        class="w-full px-4 py-3 rounded-xl border {{ $errors->has('accountNumber') ? 'border-red-400 bg-red-50/30 dark:bg-red-950/10' : 'border-gray-200 dark:border-slate-700' }} bg-gray-50 dark:bg-slate-800 text-gray-800 dark:text-white text-sm font-semibold font-mono focus:outline-none focus:ring-2 focus:ring-[#034C3C]/30 focus:border-[#034C3C] dark:focus:border-emerald-500 transition-all">
                    @error('accountNumber')
                        <p class="text-red-500 text-xs mt-1.5 font-semibold font-sans flex items-center gap-1">
                            <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Transaction ID -->
                <div>
                    <label class="block text-xs font-bold text-gray-600 dark:text-slate-300 mb-1.5 font-sans">Transaction ID (TnxID) <span class="text-red-500">*</span></label>
                    <input type="text" wire:model.live="transactionId" placeholder="TrxID লিখুন"
                        class="w-full px-4 py-3 rounded-xl border {{ $errors->has('transactionId') ? 'border-red-400 bg-red-50/30 dark:bg-red-950/10' : 'border-gray-200 dark:border-slate-700' }} bg-gray-50 dark:bg-slate-800 text-gray-800 dark:text-white text-sm font-semibold font-mono focus:outline-none focus:ring-2 focus:ring-[#034C3C]/30 focus:border-[#034C3C] dark:focus:border-emerald-500 transition-all">
                    @error('transactionId')
                        <p class="text-red-500 text-xs mt-1.5 font-semibold font-sans flex items-center gap-1">
                            <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Amount -->
                <div>
                    <label class="block text-xs font-bold text-gray-600 dark:text-slate-300 mb-1.5 font-sans">টাকার পরিমাণ <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-sm select-none">৳</span>
                        <input type="number" wire:model.live="amount" placeholder="0"
                            class="w-full pl-8 pr-4 py-3 rounded-xl border {{ $errors->has('amount') ? 'border-red-400 bg-red-50/30 dark:bg-red-950/10' : 'border-gray-200 dark:border-slate-700' }} bg-gray-50 dark:bg-slate-800 text-gray-800 dark:text-white text-sm font-semibold font-mono focus:outline-none focus:ring-2 focus:ring-[#034C3C]/30 focus:border-[#034C3C] dark:focus:border-emerald-500 transition-all">
                    </div>
                    @error('amount')
                        <p class="text-red-500 text-xs mt-1.5 font-semibold font-sans flex items-center gap-1">
                            <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex items-center gap-3 pt-2">
                    <button wire:click="clearForm" type="button"
                        class="flex-1 px-5 py-3 rounded-xl border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-slate-300 font-bold text-sm hover:bg-gray-50 dark:hover:bg-slate-800 transition-all font-sans cursor-pointer">
                        ক্লিয়ার
                    </button>
                    <button wire:click="submit" type="button"
                        class="flex-1 px-5 py-3 rounded-xl bg-[#034C3C] hover:bg-[#02382c] text-white font-bold text-sm shadow-md hover:shadow-lg active:scale-[0.98] transition-all font-sans cursor-pointer">
                        সাবমিট
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment History Section — always centered toggle, full-width table -->
    <div x-data="{ open: true }" class="space-y-3">

        <!-- Toggle Button — always centered -->
        <div class="flex justify-center">
            <button @click="open = !open"
                class="flex items-center gap-2 px-6 py-2.5 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-full text-sm font-bold text-gray-700 dark:text-slate-200 hover:bg-gray-50 dark:hover:bg-slate-800 transition-all shadow-sm cursor-pointer font-sans">
                <svg class="w-4 h-4 text-[#034C3C]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 6h18M3 14h12M3 18h8"/>
                </svg>
                ফি পেমেন্ট হিস্ট্রি দেখুন
                <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
        </div>

        <!-- Full-width History Table -->
        <div x-show="open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="w-full bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">

            <!-- Desktop Table — full width -->
            <div class="hidden md:block overflow-x-auto w-full">
                <table class="w-full">
                    <thead class="bg-[#034C3C] text-white">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold tracking-wider">সময়</th>
                            <th class="px-6 py-4 text-left text-xs font-bold tracking-wider">পেমেন্ট পদ্ধতি</th>
                            <th class="px-6 py-4 text-left text-xs font-bold tracking-wider">ফোন নম্বর</th>
                            <th class="px-6 py-4 text-left text-xs font-bold tracking-wider">TRXID</th>
                            <th class="px-6 py-4 text-left text-xs font-bold tracking-wider">টাকা</th>
                            <th class="px-6 py-4 text-left text-xs font-bold tracking-wider">স্ট্যাটাস</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                        @foreach ($history as $row)
                        <tr class="odd:bg-gray-50/30 even:bg-white dark:odd:bg-slate-900 dark:even:bg-slate-800/40 hover:bg-emerald-50/20 dark:hover:bg-slate-800/70 transition-colors">
                            <td class="px-6 py-4 text-xs text-gray-600 dark:text-slate-400 font-semibold whitespace-nowrap">{{ $row['date'] }}</td>
                            <td class="px-6 py-4 text-sm font-bold {{ $row['method'] === 'বিকাশ' ? 'text-pink-600 dark:text-pink-400' : 'text-orange-600 dark:text-orange-400' }}">
                                {{ $row['method'] }}
                            </td>
                            <td class="px-6 py-4 text-sm font-mono font-semibold text-gray-700 dark:text-slate-300">{{ $row['account'] }}</td>
                            <td class="px-6 py-4 text-sm font-mono font-semibold text-gray-700 dark:text-slate-300">{{ $row['trxid'] }}</td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-900 dark:text-white">{{ $row['amount'] }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold
                                    @if($row['status'] === 'Completed') bg-emerald-100 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400
                                    @elseif($row['status'] === 'Pending') bg-amber-100 text-amber-700 dark:bg-amber-950/30 dark:text-amber-400
                                    @else bg-red-100 text-red-600 dark:bg-red-950/30 dark:text-red-400
                                    @endif">
                                    {{ $row['status'] }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="md:hidden p-4 space-y-3">
                @foreach ($history as $row)
                <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-xl p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-sm {{ $row['method'] === 'বিকাশ' ? 'text-pink-600 dark:text-pink-400' : 'text-orange-600 dark:text-orange-400' }}">
                            {{ $row['method'] }}
                        </span>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold
                            @if($row['status'] === 'Completed') bg-emerald-100 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400
                            @elseif($row['status'] === 'Pending') bg-amber-100 text-amber-700 dark:bg-amber-950/30 dark:text-amber-400
                            @else bg-red-100 text-red-600 dark:bg-red-950/30 dark:text-red-400
                            @endif">
                            {{ $row['status'] }}
                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div>
                            <p class="text-gray-400 dark:text-slate-500 font-medium">ফোন নম্বর</p>
                            <p class="font-bold text-gray-800 dark:text-white font-mono mt-0.5">{{ $row['account'] }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 dark:text-slate-500 font-medium">টাকা</p>
                            <p class="font-extrabold text-gray-900 dark:text-white mt-0.5">{{ $row['amount'] }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 dark:text-slate-500 font-medium">TRXID</p>
                            <p class="font-bold text-gray-700 dark:text-slate-300 font-mono mt-0.5">{{ $row['trxid'] }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 dark:text-slate-500 font-medium">সময়</p>
                            <p class="font-semibold text-gray-600 dark:text-slate-400 mt-0.5">{{ $row['date'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>
