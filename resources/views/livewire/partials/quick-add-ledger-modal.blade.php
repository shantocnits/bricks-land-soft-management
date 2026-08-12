<!-- Modal 3: "নতুন খতিয়ান" Quick Add Modal -->
<template x-teleport="body">
    <div x-data="{ show: @entangle('showQuickAddLedgerModal') }" x-show="show"
        class="fixed inset-0 z-[999999]" x-cloak>

        <!-- Backdrop: click backdrop to close -->
        <div @click="show = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm cursor-pointer"></div>

        <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
            <div x-show="show"
                class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-150 dark:border-slate-800 p-6 md:p-8 max-w-md w-full relative overflow-y-auto max-h-[90vh] shadow-2xl pointer-events-auto">

                <!-- Close Button -->
                <button @click="show = false"
                    class="absolute top-4 right-4 p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-400 hover:text-gray-600 dark:hover:text-gray-250 cursor-pointer focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Modal Title -->
                <h2 class="text-lg md:text-xl font-extrabold text-gray-855 dark:text-white font-sans tracking-wide border-b border-gray-100 dark:border-slate-800 pb-3 mb-5">
                    নতুন খতিয়ান
                </h2>

                <div class="space-y-4">
                    <!-- Serial -->
                    <div>
                        <label class="block text-xs font-bold text-gray-600 dark:text-slate-350 mb-1.5 font-sans">সিরিয়াল</label>
                        <input type="text" wire:model="quickLedgerSerial"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-55 dark:bg-slate-800 text-gray-808 dark:text-white text-sm font-semibold font-sans focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                            placeholder="সিরিয়াল নম্বর">
                    </div>

                    <!-- Khotiyan Name -->
                    <div>
                        <label class="block text-xs font-bold text-gray-600 dark:text-slate-350 mb-1.5 font-sans">খতিয়ানের নাম</label>
                        <input type="text" wire:model="quickLedgerName"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-55 dark:bg-slate-800 text-gray-808 dark:text-white text-sm font-semibold font-sans focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                            placeholder="খতিয়ানের নাম লিখুন">
                        @error('quickLedgerName')
                            <p class="text-red-500 text-xs mt-1 font-semibold font-sans">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Payment Type Dropdown (root-style teleport) -->
                    <div class="relative" x-data="{
                        openPT: false,
                        rectPT: null,
                        ptStyle() {
                            if (!this.rectPT) return '';
                            const gap = 6;
                            const estH = 220;
                            const spaceBelow = window.innerHeight - this.rectPT.bottom;
                            const showAbove = spaceBelow < estH && this.rectPT.top > estH;
                            const top = showAbove
                                ? Math.max(8, this.rectPT.top - estH - gap)
                                : Math.min(window.innerHeight - estH - gap, this.rectPT.bottom + gap);
                            return 'left: ' + this.rectPT.left + 'px; top: ' + top + 'px; width: ' + this.rectPT.width + 'px; position: fixed;';
                        }
                    }">
                        <label class="block text-xs font-bold text-gray-600 dark:text-slate-350 mb-1.5 font-sans">পেমেন্ট টাইপ</label>
                        <button type="button" @click="openPT = !openPT; rectPT = $el.getBoundingClientRect()"
                            class="w-full flex items-center justify-between py-3 px-4 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-55 dark:bg-slate-800 text-sm font-semibold text-gray-808 dark:text-white focus:outline-none cursor-pointer text-left transition-all font-sans">
                            <span class="truncate flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <span class="truncate text-gray-500 dark:text-slate-400 font-sans"
                                    :class="{ 'text-gray-808 dark:text-white font-bold': '{{ $quickLedgerPaymentType }}' !== '' }">
                                    @php
                                        $ptLabels2 = ['production' => 'উৎপাদন (কাঁচা ইট)', 'expense' => 'খরচ', 'income' => 'আয়', 'other' => 'অন্যান্য'];
                                    @endphp
                                    {{ $ptLabels2[$quickLedgerPaymentType] ?? 'পেমেন্ট টাইপ নির্বাচন করুন' }}
                                </span>
                            </span>
                            <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 shrink-0 ml-1"
                                :class="{ 'rotate-180': openPT }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <template x-teleport="body">
                            <div x-show="openPT" @click.outside="openPT = false" x-transition
                                class="fixed z-[99999999] bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-gray-100 dark:border-slate-800 overflow-hidden font-sans"
                                :style="ptStyle()"
                                x-cloak>
                                <div class="max-h-56 overflow-y-auto py-1">
                                    @foreach([
                                        ['value' => 'production', 'label' => 'উৎপাদন', 'sub' => 'কাঁচা ইট'],
                                        ['value' => 'expense',    'label' => 'খরচ',    'sub' => ''],
                                        ['value' => 'income',     'label' => 'আয়',     'sub' => ''],
                                        ['value' => 'other',      'label' => 'অন্যান্য','sub' => ''],
                                    ] as $pt)
                                    <div class="px-3.5 py-2.5 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 transition-all cursor-pointer flex items-center gap-2
                                            {{ $quickLedgerPaymentType === $pt['value'] ? 'bg-emerald-50 dark:bg-emerald-950/20' : '' }}"
                                        @click="$wire.set('quickLedgerPaymentType', '{{ $pt['value'] }}'); openPT = false">
                                        <svg class="w-4 h-4 shrink-0 {{ $quickLedgerPaymentType === $pt['value'] ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-400' }}"
                                            fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        <span class="text-xs font-semibold font-sans block truncate
                                                {{ $quickLedgerPaymentType === $pt['value'] ? 'text-emerald-700 dark:text-emerald-400' : 'text-gray-808 dark:text-white' }}">
                                            {{ $pt['label'] }}@if($pt['sub']) <span class="font-normal text-gray-400 dark:text-slate-500">({{ $pt['sub'] }})</span>@endif
                                        </span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Khotiyan Group Dropdown matching Settings Root Design -->
                    <div x-data="{
                        openGrp: false,
                        searchGrp: '',
                        rectGrp: null,
                        grpStyle() {
                            if (!this.rectGrp) return '';
                            const gap = 6;
                            const estH = 230;
                            const spaceBelow = window.innerHeight - this.rectGrp.bottom;
                            const showAbove = spaceBelow < estH && this.rectGrp.top > estH;
                            const top = showAbove
                                ? Math.max(8, this.rectGrp.top - estH - gap)
                                : Math.min(window.innerHeight - estH - gap, this.rectGrp.bottom + gap);
                            return 'left: ' + this.rectGrp.left + 'px; top: ' + top + 'px; width: ' + this.rectGrp.width + 'px; position: fixed;';
                        }
                    }">
                        <label class="block text-xs font-bold text-gray-600 dark:text-slate-350 mb-1.5 font-sans">খতিয়ানের গ্রুপ <span class="text-red-500">*</span></label>
                        <button type="button" @click="openGrp = !openGrp; rectGrp = $el.getBoundingClientRect()"
                            class="w-full flex items-center justify-between py-3 px-4 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-55 dark:bg-slate-800 text-sm font-semibold text-gray-808 dark:text-white focus:outline-none cursor-pointer text-left transition-all font-sans">
                            <span class="truncate flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-19.5 0A2.25 2.25 0 004.5 15h15a2.25 2.25 0 002.25-2.25m-19.5 0v.243a2.25 2.25 0 00.864 1.765l.775.62c.39.312.617.781.617 1.274v1.848a2.25 2.25 0 002.25 2.25h10.5a2.25 2.25 0 002.25-2.25v-1.848c0-.493.227-.962.617-1.274l.775-.62a2.25 2.25 0 00.864-1.765V12.75M3.75 6h4.875c.621 0 1.15.402 1.314 1.002L10.3 8.5H20.25A2.25 2.25 0 0122.5 10.75v.75H1.5v-.75A2.25 2.25 0 013.75 6z" />
                                </svg>
                                <span x-text="$wire.quickLedgerGroup || 'গ্রুপ নির্বাচন করুন'" class="truncate text-gray-500 dark:text-slate-400 font-sans" :class="{ 'text-gray-808 dark:text-white font-bold': $wire.quickLedgerGroup }"></span>
                            </span>
                            <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 shrink-0 ml-1"
                                :class="{ 'rotate-180': openGrp }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <template x-teleport="body">
                            <div x-show="openGrp" @click.outside="openGrp = false" x-transition
                                class="fixed z-[99999999] bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-gray-100 dark:border-slate-800 overflow-hidden font-sans"
                                :style="grpStyle()"
                                x-cloak>
                                <!-- Single Filter Input -->
                                <div class="p-2 border-b border-gray-100 dark:border-slate-800">
                                    <input type="text" x-model="searchGrp"
                                        placeholder="গ্রুপ ফিল্টার করুন..."
                                        class="w-full py-1.5 px-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-xs text-gray-808 dark:text-white focus:outline-none focus:border-emerald-500 transition-all font-sans font-medium">
                                </div>
                                <!-- Options list with folder icon -->
                                <div class="max-h-56 overflow-y-auto py-1">
                                    @foreach($ledgerGroups as $grp)
                                    <div class="px-3.5 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 transition-all cursor-pointer flex items-center gap-2"
                                        x-show="searchGrp === '' || {{ json_encode($grp) }}.toLowerCase().includes(searchGrp.toLowerCase())"
                                        @click="$wire.set('quickLedgerGroup', {{ json_encode($grp) }}); openGrp = false; searchGrp = ''">
                                        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0"
                                            fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-19.5 0A2.25 2.25 0 004.5 15h15a2.25 2.25 0 002.25-2.25m-19.5 0v.243a2.25 2.25 0 00.864 1.765l.775.62c.39.312.617.781.617 1.274v1.848a2.25 2.25 0 002.25 2.25h10.5a2.25 2.25 0 002.25-2.25v-1.848c0-.493.227-.962.617-1.274l.775-.62a2.25 2.25 0 00.864-1.765V12.75M3.75 6h4.875c.621 0 1.15.402 1.314 1.002L10.3 8.5H20.25A2.25 2.25 0 0122.5 10.75v.75H1.5v-.75A2.25 2.25 0 013.75 6z" />
                                        </svg>
                                        <span class="text-xs font-semibold text-gray-808 dark:text-white hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-sans block truncate">
                                            {{ $grp }}
                                        </span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </template>
                        @error('quickLedgerGroup')
                            <p class="text-red-500 text-xs mt-1 font-semibold font-sans">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center gap-3.5 pt-4 border-t border-gray-100 dark:border-slate-800/60">
                        <button type="button" wire:click="resetQuickAddLedgerForm"
                            class="flex-grow py-3 border border-gray-200 dark:border-slate-700 hover:bg-gray-55 dark:hover:bg-slate-800/60 text-gray-650 dark:text-slate-205 font-bold rounded-xl text-xs font-sans transition-all cursor-pointer">
                            ক্লিয়ার
                        </button>
                        <button type="button" wire:click="saveQuickLedger"
                            class="flex-grow py-3 bg-[#034C3C] hover:bg-emerald-700 text-white font-bold rounded-xl text-xs font-sans transition-all cursor-pointer">
                            অ্যাড করুন
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>
