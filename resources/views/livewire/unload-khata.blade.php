@php
if (!function_exists('toBanglaNum')) {
    function toBanglaNum($num) {
        return str_replace(['0','1','2','3','4','5','6','7','8','9'],
                           ['০','১','২','৩','৪','৫','৬','৭','৮','৯'], $num);
    }
}
@endphp

<div class="min-h-screen bg-gray-50 dark:bg-slate-950 transition-colors duration-300 pb-12">

    {{-- ─── Toast ─────────────────────────────────────────────────────────── --}}
    <div x-data="{ show:false, message:'', type:'success' }"
         x-init="window.addEventListener('show-toast', e=>{ message=e.detail.message; type=e.detail.type||'success'; show=true; setTimeout(()=>show=false,3000); })"
         x-show="show" x-transition x-cloak
         class="fixed top-4 right-4 z-[9999] px-4 py-3 rounded-2xl shadow-2xl flex items-center gap-2.5 font-sans font-bold text-xs"
         :class="type==='success' ? 'bg-emerald-500 text-white' : 'bg-red-500 text-white'">
        <span x-text="type==='success' ? '✓' : '✕'"></span>
        <span x-text="message"></span>
    </div>

    {{-- ─── Main ──────────────────────────────────────────────────────────── --}}
    <div class="w-full">

        {{-- Toolbar --}}
        <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-2xl p-4 shadow-sm mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">

            {{-- Left --}}
            <div class="flex flex-wrap items-center gap-3">
                <button wire:click="openModal()"
                        class="px-4 py-2 bg-[#034C3C] hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-emerald-500/20 active:scale-95 cursor-pointer flex items-center gap-1.5 font-sans">
                    + নতুন আনলোড
                </button>

                <span class="inline-flex items-center px-3 py-1.5 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/50 rounded-xl text-xs font-bold font-sans">
                    সর্বমোট আনলোড: {{ toBanglaNum(number_format($totalQuantitySum)) }} পিস
                </span>
            </div>

            {{-- Right --}}
            <div class="flex flex-wrap items-center gap-3">

                {{-- Datepicker --}}
                <div class="relative flex items-center">
                    <input type="text" data-flatpickr data-wire-prop="dateFilter" data-default="{{ $dateFilter }}"
                           wire:model="dateFilter" placeholder="তারিখ" readonly
                           class="pl-3 pr-8 py-2 text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-800 dark:text-white w-36 font-sans font-semibold cursor-pointer focus:outline-none">
                    <span class="absolute right-2.5 top-2.5 text-emerald-500 pointer-events-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                    </span>
                </div>

                {{-- Round Filter Dropdown --}}
                <div x-data="{ open:false }" class="relative">
                    <button @click="open=!open" type="button"
                            class="flex items-center justify-between gap-2.5 px-4 py-2 bg-white dark:bg-slate-800 text-gray-808 dark:text-white font-bold rounded-xl text-xs border border-gray-200 dark:border-slate-700 cursor-pointer w-44">
                        <span class="font-sans truncate" x-text="$wire.roundFilter ? $wire.roundFilter : 'সকল রাউন্ড'"></span>
                        <svg class="w-4 h-4 flex-shrink-0 transition-transform" :class="{'rotate-180':open}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" @click.outside="open=false" x-cloak
                         class="absolute z-[999] mt-1.5 w-52 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl shadow-xl overflow-hidden">
                        <div class="py-1">
                            <button type="button" wire:click="$set('roundFilter','')" @click="open=false"
                                    class="w-full text-left px-4 py-2 text-xs font-bold text-gray-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-slate-800 hover:text-emerald-700 dark:hover:text-emerald-400 transition-colors font-sans">
                                সকল রাউন্ড
                            </button>
                            @foreach($rounds as $rnd)
                                <button type="button" wire:click="$set('roundFilter','{{ $rnd->name }}')" @click="open=false"
                                        class="w-full text-left px-4 py-2 text-xs font-bold text-gray-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-slate-800 hover:text-emerald-700 dark:hover:text-emerald-400 transition-colors font-sans">
                                    {{ $rnd->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Print --}}
                <button type="button" onclick="window.print()"
                        class="px-3 py-2 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-200 text-xs font-semibold rounded-xl cursor-pointer transition-all font-sans border border-gray-200 dark:border-slate-700 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/>
                    </svg>
                    প্রিন্ট
                </button>

                {{-- Report Button --}}
                <button type="button" wire:click="$set('showReport',true)"
                        class="px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl cursor-pointer transition-all font-sans flex items-center gap-1.5 active:scale-95">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    রিপোর্ট
                </button>
            </div>
        </div>

        {{-- ─── Table Card ─────────────────────────────────────────────────── --}}
        <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left border border-gray-200 dark:border-slate-800">
                    <thead>
                        <tr class="bg-[#034C3C] text-white text-[11px] font-bold uppercase font-sans">
                            <th class="py-3 px-4 border-r border-white/20">#</th>
                            <th class="py-3 px-4 border-r border-white/20">তারিখ</th>
                            <th class="py-3 px-4 text-center border-r border-white/20">রাউন্ড</th>
                            @foreach($brickCategories as $cat)
                                <th class="py-3 px-4 text-center border-r border-white/20">{{ $cat->name }}</th>
                            @endforeach
                            <th class="py-3 px-4 text-center border-r border-white/20">মোট ইট</th>
                            <th class="py-3 px-4 text-center w-24">বাটন</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800 font-sans text-xs">
                        @forelse($entries as $index => $entry)
                            @php
                                $rowTotal = $entry->items->whereIn('category_name', $brickCategories->pluck('name'))->sum('quantity');
                            @endphp
                            <tr class="hover:bg-emerald-50/40 dark:hover:bg-emerald-950/10 transition-colors">
                                <td class="py-3.5 px-4 text-gray-500 dark:text-slate-400 font-semibold border-r border-gray-150 dark:border-slate-800">
                                    {{ toBanglaNum($entries->firstItem() + $index) }}
                                </td>
                                <td class="py-3.5 px-4 font-bold text-gray-909 dark:text-white border-r border-gray-150 dark:border-slate-800 whitespace-nowrap">
                                    {{ toBanglaNum($entry->date->format('d-m-Y')) }}
                                </td>
                                <td class="py-3.5 px-4 font-bold text-center text-emerald-600 dark:text-emerald-400 border-r border-gray-150 dark:border-slate-800">
                                    {{ $entry->round }}
                                </td>
                                @foreach($brickCategories as $cat)
                                    @php
                                        $qty = $entry->items->where('category_name', $cat->name)->first()?->quantity ?? 0;
                                    @endphp
                                    <td class="py-3.5 px-4 text-center border-r border-gray-150 dark:border-slate-800 font-semibold text-gray-800 dark:text-slate-200">
                                        {{ toBanglaNum(number_format($qty)) }}
                                    </td>
                                @endforeach
                                <td class="py-3.5 px-4 font-black text-center text-red-500 dark:text-red-400 border-r border-gray-150 dark:border-slate-800 font-mono">
                                    {{ toBanglaNum(number_format($rowTotal)) }}
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" wire:click="edit({{ $entry->id }})" title="সম্পাদনা"
                                                class="inline-flex text-emerald-600 hover:text-emerald-800 hover:scale-110 transition-all cursor-pointer focus:outline-none">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                            </svg>
                                        </button>
                                        <button type="button" wire:click="delete({{ $entry->id }})"
                                                wire:confirm="আপনি কি নিশ্চিতভাবে এই আনলোড হিসাবটি মুছে ফেলতে চান?"
                                                title="মুছে ফেলুন"
                                                class="inline-flex text-red-505 hover:text-red-700 hover:scale-110 transition-all cursor-pointer focus:outline-none">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ 4 + count($brickCategories) }}" class="py-12 text-center text-sm font-semibold text-gray-400 dark:text-slate-500">
                                    কোনো আনলোড বিবরণ পাওয়া যায়নি।
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Footer --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 px-5 py-4 border-t border-gray-100 dark:border-slate-800">
                <div class="text-xs text-gray-500 dark:text-gray-400 font-sans font-semibold">
                    মোট: <strong class="text-gray-808 dark:text-white">{{ toBanglaNum($entries->total()) }} টি</strong>
                    | সর্বমোট আনলোড: <strong class="text-emerald-600 dark:text-emerald-400">{{ toBanglaNum(number_format($totalQuantitySum)) }} পিস</strong>
                </div>

                <div class="flex items-center gap-4">
                    {{-- Pagination Links --}}
                    {{ $entries->links() }}

                    {{-- Per Page --}}
                    <div x-data="{ open:false }" class="relative">
                        <button @click="open=!open" type="button"
                                class="flex items-center justify-between gap-1.5 px-3 py-1.5 bg-white dark:bg-slate-800 text-gray-808 dark:text-white font-bold rounded-lg text-xs border border-gray-200 dark:border-slate-700 cursor-pointer">
                            <span class="font-sans">{{ toBanglaNum($perPage) }} / পেজ</span>
                            <svg class="w-3.5 h-3.5 transition-transform" :class="{'rotate-180':open}" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" @click.outside="open=false" x-cloak
                             class="absolute bottom-full mb-1.5 right-0 z-[999] w-32 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl shadow-xl overflow-hidden">
                            <div class="py-1">
                                @foreach([10,20,30,50] as $size)
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
    </div>

    {{-- ══════════════════════════════════════════════════════════════════════
         Modal: আজকের আনলোড / আপডেট
    ══════════════════════════════════════════════════════════════════════════ --}}
    <div x-data="{ show: @entangle('showModal') }"
         x-show="show" @click.self="show=false"
         class="fixed inset-0 z-[9990] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
         x-cloak x-transition>

        <div @click.outside="show=false" class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-200 dark:border-slate-800 p-6 md:p-8 max-w-lg w-full relative shadow-2xl">

            {{-- Close --}}
            <button @click="show=false" class="absolute top-4 right-4 p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-400 cursor-pointer focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <h2 class="text-base font-black text-gray-808 dark:text-white font-sans border-b border-gray-105 dark:border-slate-800 pb-3 mb-5">
                {{ $editingId ? 'আনলোড আপডেট' : 'আজকের আনলোড' }}
            </h2>

            <div class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    {{-- Round Dropdown --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 mb-1.5 font-sans">রাউন্ড <span class="text-red-500">*</span></label>
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" type="button"
                                    class="w-full flex items-center justify-between gap-2 px-4 py-2.5 bg-gray-50 dark:bg-slate-800 text-gray-808 dark:text-white font-semibold rounded-xl text-xs border border-gray-200 dark:border-slate-700 cursor-pointer focus:outline-none"
                                    :class="{ 'border-emerald-500 ring-2 ring-emerald-500/20': open }">
                                <span class="font-sans truncate" x-text="$wire.round ? $wire.round : 'সিলেক্ট করুন'"></span>
                                <svg class="w-4 h-4 flex-shrink-0 transition-transform text-gray-400" :class="{'rotate-180':open}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <div x-show="open" @click.outside="open=false" x-cloak
                                 class="absolute left-0 right-0 z-[9999] mt-1.5 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl shadow-2xl overflow-hidden">
                                <div class="py-1 max-h-52 overflow-y-auto">
                                    @foreach($rounds as $rnd)
                                        <button type="button"
                                                wire:click="$set('round','{{ $rnd->name }}')"
                                                @click="open=false"
                                                class="w-full text-left px-3 py-2.5 text-xs font-bold text-gray-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-slate-800 hover:text-emerald-700 dark:hover:text-emerald-400 transition-colors font-sans cursor-pointer">
                                            {{ $rnd->name }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @error('round')<p class="text-red-500 text-[10px] mt-1 font-sans">{{ $message }}</p>@enderror
                    </div>

                    {{-- Datepicker --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-555 dark:text-slate-400 mb-1.5 font-sans">আনলোডের তারিখ <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="text" data-flatpickr data-wire-prop="date" data-default="{{ $date }}"
                                   wire:model="date" placeholder="তারিখ" readonly
                                   class="w-full pl-3 pr-8 py-2.5 text-xs rounded-xl border border-gray-200 dark:border-slate-707 bg-gray-50 dark:bg-slate-800 text-gray-808 dark:text-white font-sans font-semibold cursor-pointer focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                            <span class="absolute right-2.5 top-3 text-emerald-500 pointer-events-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            </span>
                        </div>
                        @error('date')<p class="text-red-500 text-[10px] mt-1 font-sans">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    {{-- Category (শ্রেণি) Dropdown --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 mb-1.5 font-sans">শ্রেণি <span class="text-red-500">*</span></label>
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" type="button"
                                    class="w-full flex items-center justify-between gap-2 px-4 py-2.5 bg-gray-50 dark:bg-slate-800 text-gray-808 dark:text-white font-semibold rounded-xl text-xs border border-gray-200 dark:border-slate-700 cursor-pointer focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                                    :class="{ 'border-emerald-500 ring-2 ring-emerald-500/20': open }">
                                <span class="font-sans truncate" x-text="$wire.category ? $wire.category : 'শ্রেণি নির্বাচন করুন...'"></span>
                                <svg class="w-4 h-4 flex-shrink-0 transition-transform text-gray-400" :class="{'rotate-180':open}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <div x-show="open" @click.outside="open=false" x-cloak
                                 class="absolute left-0 right-0 z-[9999] mt-1.5 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl shadow-2xl overflow-hidden">
                                <div class="py-1 max-h-52 overflow-y-auto">
                                    @foreach($allCategories as $cat)
                                        <button type="button"
                                                wire:click="$set('category','{{ $cat->name }}')"
                                                @click="open=false"
                                                class="w-full text-left px-4 py-2.5 text-xs font-bold text-gray-788 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-slate-800 hover:text-emerald-700 dark:hover:text-emerald-400 transition-colors font-sans cursor-pointer">
                                            {{ $cat->name }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @error('category')<p class="text-red-500 text-[10px] mt-1 font-sans">{{ $message }}</p>@enderror
                    </div>

                    {{-- Quantity --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 mb-1.5 font-sans">পরিমান <span class="text-red-500">*</span></label>
                        <input type="number" wire:model="quantity" placeholder="আনলোডের পরিমাণ"
                               class="w-full px-3 py-2.5 text-xs font-bold font-mono rounded-xl border border-gray-200 dark:border-slate-707 bg-gray-50 dark:bg-slate-800 text-gray-808 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                        @error('quantity')<p class="text-red-500 text-[10px] mt-1 font-sans">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex items-center justify-end gap-3.5 mt-8 pt-5 border-t border-gray-100 dark:border-slate-800">
                <button type="button" wire:click="resetForm()"
                        class="px-5 py-2.5 border border-gray-200 dark:border-slate-700 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-500 dark:text-slate-400 text-xs font-bold rounded-xl transition-all cursor-pointer">
                    ক্লিয়ার
                </button>
                <button type="button" wire:click="save()"
                        class="px-6 py-2.5 bg-[#034C3C] hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-all cursor-pointer shadow-md shadow-emerald-500/20 active:scale-95">
                    সেভ করুন
                </button>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════════
         Modal: আনলোড রিপোর্ট (Dynamic Tab System)
    ══════════════════════════════════════════════════════════════════════════ --}}
    <div x-data="{ show: @entangle('showReport') }"
         x-show="show" @click.self="show=false"
         class="fixed inset-0 z-[9990] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
         x-cloak x-transition>

        <div @click.outside="show=false" class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-200 dark:border-slate-800 max-w-5xl w-full relative shadow-2xl max-h-[90vh] overflow-y-auto">

            {{-- Header Title & Print inside a green block matching the theme --}}
            <div class="bg-[#034C3C] px-6 py-4 flex items-center justify-between rounded-t-3xl text-white">
                <h2 class="text-base font-black font-sans">
                    আনলোড রিপোর্ট
                </h2>
                <div class="flex items-center gap-3">
                    <button type="button" onclick="window.print()"
                            class="px-4 py-1.5 border border-white hover:bg-white hover:text-[#034C3C] text-white text-[11px] font-bold rounded-lg transition-all cursor-pointer flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/>
                        </svg>
                        প্রিন্ট
                    </button>
                    {{-- Close --}}
                    <button @click="show=false" class="p-1 rounded-full hover:bg-emerald-800 text-white cursor-pointer focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <div class="p-6 md:p-8">

                {{-- Tabs Bar --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 mb-6">
                    <button type="button" wire:click="$set('activeTab','quantity')"
                            class="py-2.5 px-4 rounded-xl text-xs font-bold font-sans transition-all active:scale-95 border cursor-pointer text-center"
                            :class="'{{ $activeTab }}' === 'quantity' ? 'bg-[#034C3C] border-[#034C3C] text-white shadow-md' : 'bg-white dark:bg-slate-800 border-gray-200 dark:border-slate-700 text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-700/50'">
                        সংখ্যায় আনলোডের রিপোর্ট
                    </button>
                    <button type="button" wire:click="$set('activeTab','percentage')"
                            class="py-2.5 px-4 rounded-xl text-xs font-bold font-sans transition-all active:scale-95 border cursor-pointer text-center"
                            :class="'{{ $activeTab }}' === 'percentage' ? 'bg-[#034C3C] border-[#034C3C] text-white shadow-md' : 'bg-white dark:bg-slate-800 border-gray-200 dark:border-slate-700 text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-700/50'">
                        শতকরায় আনলোডের রিপোর্ট
                    </button>
                    <button type="button" wire:click="$set('activeTab','bricks_adla')"
                            class="py-2.5 px-4 rounded-xl text-xs font-bold font-sans transition-all active:scale-95 border cursor-pointer text-center"
                            :class="'{{ $activeTab }}' === 'bricks_adla' ? 'bg-[#034C3C] border-[#034C3C] text-white shadow-md' : 'bg-white dark:bg-slate-800 border-gray-200 dark:border-slate-700 text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-700/50'">
                        ইট এবং আধলার রিপোর্ট
                    </button>
                </div>

                {{-- Tab Content: 1. Quantity Report --}}
                @if($activeTab === 'quantity')
                    <div class="border border-gray-200 dark:border-slate-800 rounded-xl overflow-hidden mb-2">
                        <table class="w-full border-collapse text-xs font-sans text-left">
                            <thead>
                                <tr class="bg-[#034C3C] text-white font-bold text-[11px]">
                                    <th class="py-3 px-4 border-r border-white/20">রাউন্ড</th>
                                    @foreach($brickCategories as $cat)
                                        <th class="py-3 px-4 text-center border-r border-white/20">{{ $cat->name }}</th>
                                    @endforeach
                                    <th class="py-3 px-4 text-center">মোট ইট</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-slate-800 font-sans text-xs">
                                @forelse($reportRows as $row)
                                    <tr class="hover:bg-emerald-50/30 dark:hover:bg-emerald-950/10 transition-colors">
                                        <td class="py-3 px-4 font-bold text-gray-800 dark:text-white border-r border-gray-100 dark:border-slate-800">{{ $row['round'] }}</td>
                                        @foreach($brickCategories as $cat)
                                            <td class="py-3 px-4 text-center font-mono font-bold text-gray-700 dark:text-slate-300 border-r border-gray-100 dark:border-slate-800">
                                                {{ toBanglaNum(number_format($row[$cat->name])) }}
                                            </td>
                                        @endforeach
                                        <td class="py-3 px-4 text-center font-mono font-black text-red-500 dark:text-red-400">
                                            {{ toBanglaNum(number_format($row['total'])) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ 2 + count($brickCategories) }}" class="py-10 text-center text-gray-400">কোনো তথ্য নেই</td>
                                    </tr>
                                @endforelse

                                @if($reportRows->count() > 0)
                                    <tr class="bg-emerald-50/60 dark:bg-emerald-950/20 border-t-2 border-emerald-200 dark:border-emerald-900">
                                        <td class="py-3 px-4 font-extrabold text-gray-900 dark:text-white border-r border-gray-200 dark:border-slate-700">মোট</td>
                                        @foreach($brickCategories as $cat)
                                            <td class="py-3 px-4 text-center font-mono font-black text-gray-800 dark:text-white border-r border-gray-200 dark:border-slate-700">
                                                {{ toBanglaNum(number_format($reportRows->sum($cat->name))) }}
                                            </td>
                                        @endforeach
                                        <td class="py-3 px-4 text-center font-mono font-black text-red-500 dark:text-red-400">
                                            {{ toBanglaNum(number_format($reportRows->sum('total'))) }}
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                @endif

                {{-- Tab Content: 2. Percentage Report --}}
                @if($activeTab === 'percentage')
                    <div class="border border-gray-200 dark:border-slate-800 rounded-xl overflow-hidden mb-2">
                        <table class="w-full border-collapse text-xs font-sans text-left">
                            <thead>
                                <tr class="bg-[#034C3C] text-white font-bold text-[11px]">
                                    <th class="py-3 px-4 border-r border-white/20">রাউন্ড</th>
                                    @foreach($brickCategories as $cat)
                                        <th class="py-3 px-4 text-center border-r border-white/20">{{ $cat->name }}</th>
                                    @endforeach
                                    <th class="py-3 px-4 text-center">মোট ইট</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-slate-800 font-sans text-xs">
                                @forelse($reportRows as $row)
                                    <tr class="hover:bg-emerald-50/30 dark:hover:bg-emerald-950/10 transition-colors">
                                        <td class="py-3 px-4 font-bold text-gray-808 dark:text-white border-r border-gray-105 dark:border-slate-800">{{ $row['round'] }}</td>
                                        @foreach($brickCategories as $cat)
                                            @php
                                                $pct = $row['total'] > 0 ? ($row[$cat->name] / $row['total']) * 100 : 0;
                                            @endphp
                                            <td class="py-3 px-4 text-center font-mono font-bold text-gray-700 dark:text-slate-300 border-r border-gray-100 dark:border-slate-800">
                                                {{ toBanglaNum(number_format($pct, 2)) }} %
                                            </td>
                                        @endforeach
                                        <td class="py-3 px-4 text-center font-mono font-black text-red-500 dark:text-red-400">
                                            100.00 %
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ 2 + count($brickCategories) }}" class="py-10 text-center text-gray-405">কোনো তথ্য নেই</td>
                                    </tr>
                                @endforelse

                                @if($reportRows->count() > 0)
                                    @php
                                        $grandTotalSum = $reportRows->sum('total');
                                    @endphp
                                    <tr class="bg-emerald-50/60 dark:bg-emerald-950/20 border-t-2 border-emerald-200 dark:border-emerald-900">
                                        <td class="py-3 px-4 font-extrabold text-gray-900 dark:text-white border-r border-gray-200 dark:border-slate-700">মোট</td>
                                        @foreach($brickCategories as $cat)
                                            @php
                                                $totalCatSum = $reportRows->sum($cat->name);
                                                $grandPct = $grandTotalSum > 0 ? ($totalCatSum / $grandTotalSum) * 100 : 0;
                                            @endphp
                                            <td class="py-3 px-4 text-center font-mono font-black text-gray-808 dark:text-white border-r border-gray-200 dark:border-slate-707">
                                                {{ toBanglaNum(number_format($grandPct, 2)) }} %
                                            </td>
                                        @endforeach
                                        <td class="py-3 px-4 text-center font-mono font-black text-red-500 dark:text-red-400">
                                            100.00 %
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                @endif

                {{-- Tab Content: 3. Bricks and Adla Report --}}
                @if($activeTab === 'bricks_adla')
                    <div class="border border-gray-200 dark:border-slate-800 rounded-xl overflow-hidden mb-2">
                        <table class="w-full border-collapse text-xs font-sans text-left">
                            <thead>
                                <tr class="bg-[#034C3C] text-white font-bold text-[11px]">
                                    <th class="py-3 px-4 border-r border-white/20">রাউন্ড</th>
                                    <th class="py-3 px-4 text-center border-r border-white/20">লোড</th>
                                    <th class="py-3 px-4 text-center border-r border-white/20">ইট</th>
                                    <th class="py-3 px-4 text-center border-r border-white/20">আধলা</th>
                                    <th class="py-3 px-4 text-center border-r border-white/20">ইট %</th>
                                    <th class="py-3 px-4 text-center">আধলা %</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-slate-800 font-sans text-xs">
                                @forelse($compareRows as $row)
                                    @php
                                        $brickPct = $row['load'] > 0 ? ($row['brick'] / $row['load']) * 100 : 0;
                                        $adlaPct = $row['load'] > 0 ? ($row['adla'] / $row['load']) * 100 : 0;
                                    @endphp
                                    <tr class="hover:bg-emerald-50/30 dark:hover:bg-emerald-950/10 transition-colors">
                                        <td class="py-3 px-4 font-bold text-gray-808 dark:text-white border-r border-gray-100 dark:border-slate-800">{{ $row['round'] }}</td>
                                        <td class="py-3 px-4 text-center font-mono font-bold text-gray-700 dark:text-slate-300 border-r border-gray-100 dark:border-slate-800">
                                            {{ toBanglaNum(number_format($row['load'])) }}
                                        </td>
                                        <td class="py-3 px-4 text-center font-mono font-bold text-emerald-600 dark:text-emerald-400 border-r border-gray-100 dark:border-slate-800">
                                            {{ toBanglaNum(number_format($row['brick'])) }}
                                        </td>
                                        <td class="py-3 px-4 text-center font-mono font-bold text-amber-600 dark:text-amber-400 border-r border-gray-100 dark:border-slate-800">
                                            {{ toBanglaNum(number_format($row['adla'])) }}
                                        </td>
                                        <td class="py-3 px-4 text-center font-mono font-black text-gray-700 dark:text-slate-300 border-r border-gray-100 dark:border-slate-800">
                                            {{ toBanglaNum(number_format($brickPct, 2)) }} %
                                        </td>
                                        <td class="py-3 px-4 text-center font-mono font-black text-gray-700 dark:text-slate-300">
                                            {{ toBanglaNum(number_format($adlaPct, 2)) }} %
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-10 text-center text-gray-400">কোনো তথ্য নেই</td>
                                    </tr>
                                @endforelse

                                @if($compareRows->count() > 0)
                                    @php
                                        $totLoad = $compareRows->sum('load');
                                        $totBrick = $compareRows->sum('brick');
                                        $totAdla = $compareRows->sum('adla');
                                        $totBrickPct = $totLoad > 0 ? ($totBrick / $totLoad) * 100 : 0;
                                        $totAdlaPct = $totLoad > 0 ? ($totAdla / $totLoad) * 100 : 0;
                                    @endphp
                                    <tr class="bg-emerald-50/60 dark:bg-emerald-950/20 border-t-2 border-emerald-200 dark:border-emerald-900">
                                        <td class="py-3 px-4 font-extrabold text-gray-900 dark:text-white border-r border-gray-200 dark:border-slate-707">মোট</td>
                                        <td class="py-3 px-4 text-center font-mono font-black text-gray-800 dark:text-white border-r border-gray-200 dark:border-slate-707">
                                            {{ toBanglaNum(number_format($totLoad)) }}
                                        </td>
                                        <td class="py-3 px-4 text-center font-mono font-black text-emerald-600 dark:text-emerald-400 border-r border-gray-200 dark:border-slate-707">
                                            {{ toBanglaNum(number_format($totBrick)) }}
                                        </td>
                                        <td class="py-3 px-4 text-center font-mono font-black text-amber-600 dark:text-amber-400 border-r border-gray-200 dark:border-slate-707">
                                            {{ toBanglaNum(number_format($totAdla)) }}
                                        </td>
                                        <td class="py-3 px-4 text-center font-mono font-black text-gray-808 dark:text-white border-r border-gray-200 dark:border-slate-707">
                                            {{ toBanglaNum(number_format($totBrickPct, 2)) }} %
                                        </td>
                                        <td class="py-3 px-4 text-center font-mono font-black text-gray-808 dark:text-white">
                                            {{ toBanglaNum(number_format($totAdlaPct, 2)) }} %
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                @endif

            </div>
        </div>
    </div>

</div>
