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

    {{-- ========== TOP ACTION BAR ========== --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200/80 dark:border-slate-800 shadow-sm p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition-colors duration-300">
        <div class="flex items-center gap-3">
            <button type="button" wire:click="openCreateModal"
                class="px-4 py-2 bg-[#009669] hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-2 cursor-pointer active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                নতুন হিসাব
            </button>
        </div>
        
        {{-- Search --}}
        <div class="w-full sm:w-72 relative">
            <input type="text"
                   wire:model.live.debounce.300ms="search"
                   placeholder="নাম / ঠিকানা / ফোন খুঁজুন..."
                   class="w-full pl-3 pr-9 py-2 text-xs bg-gray-50 dark:bg-slate-800 text-gray-800 dark:text-white rounded-xl border border-gray-200 dark:border-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all placeholder:text-gray-400 font-sans">
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- ========== TWO-COLUMN LISTS ========== --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- ===== LEFT: টাকা দেওয়ার লিস্ট ===== --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200/80 dark:border-slate-800 shadow-sm overflow-hidden flex flex-col justify-between transition-colors duration-300">
            <div>
                {{-- Header --}}
                <div class="bg-[#009669] px-5 py-3.5 flex items-center justify-between">
                    <h3 class="text-white font-bold text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 text-white/80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                        টাকা দেওয়ার লিস্ট
                        <span class="bg-white/20 text-white text-xs font-bold px-2 py-0.5 rounded-full">({{ $givenList->total() }})</span>
                    </h3>
                    <span class="text-white/90 text-xs font-semibold font-mono">
                        মোট দেওয়াঃ ৳{{ number_format($givenTotal, 0) }}
                    </span>
                </div>

                {{-- Table Header --}}
                <div class="hidden md:grid grid-cols-12 text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase bg-gray-50 dark:bg-slate-950 border-b border-gray-100 dark:border-slate-800 px-4 py-2.5">
                    <div class="col-span-6">নাম / ঠিকানা</div>
                    <div class="col-span-4 text-right">টাকা দিয়েছি</div>
                    <div class="col-span-2 text-center">বাটন</div>
                </div>

                {{-- List --}}
                <div class="divide-y divide-gray-100 dark:divide-slate-800/60">
                    @forelse($givenList as $t)
                        <div class="grid grid-cols-12 items-center px-4 py-3 hover:bg-red-50/30 dark:hover:bg-slate-800/30 transition-colors gap-2">
                            {{-- Left: Info --}}
                            <div class="col-span-6 flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-full bg-red-100 dark:bg-red-950/30 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <a href="{{ route('deuna-pauna.profile', $t->id) }}" wire:navigate
                                       class="font-bold text-sm text-gray-800 dark:text-white hover:text-[#009669] dark:hover:text-emerald-400 transition-colors block truncate cursor-pointer">
                                        {{ $t->ledger_name }}
                                    </a>
                                    @if($t->address)
                                        <p class="text-[11px] text-rose-600 dark:text-rose-400 font-medium truncate">{{ $t->address }}</p>
                                    @endif
                                </div>
                            </div>

                            {{-- Middle: Amount --}}
                            <div class="col-span-4 text-right">
                                <span class="font-mono font-bold text-sm text-red-600 dark:text-red-400">
                                    ৳{{ number_format($t->amount - $t->paid_amount, 0) }}
                                </span>
                            </div>

                            {{-- Right: Dropdown Action --}}
                            <div class="col-span-2 flex justify-center">
                                <div class="relative" x-data="{ openDropdown: false, buttonRect: null }">
                                    <button type="button" @click="openDropdown = !openDropdown; buttonRect = $el.getBoundingClientRect()" class="p-1.5 text-gray-500 hover:text-emerald-600 dark:hover:text-emerald-400 focus:outline-none transition-all cursor-pointer">
                                        <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z"/>
                                        </svg>
                                    </button>
                                    <template x-teleport="body">
                                        <div x-show="openDropdown" @click.away="openDropdown = false" x-transition
                                             class="fixed w-44 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-2xl shadow-2xl p-1.5 z-[9999] text-left text-xs flex flex-col gap-0.5"
                                             :style="buttonRect ? ('left: ' + (buttonRect.left - 130) + 'px; position: fixed; ' + (window.innerHeight - buttonRect.bottom < 180 ? 'bottom: ' + (window.innerHeight - buttonRect.top + 4) + 'px;' : 'top: ' + (buttonRect.bottom + 4) + 'px;')) : ''"
                                             x-cloak>
                                            <a href="{{ route('deuna-pauna.profile', $t->id) }}" wire:navigate @click="openDropdown = false" class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 text-gray-700 dark:text-slate-200 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-xl cursor-pointer flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                প্রোফাইল দেখুন
                                            </a>
                                            <button type="button" wire:click="openEditModal({{ $t->id }})" @click="openDropdown = false" class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 text-gray-700 dark:text-slate-200 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-xl cursor-pointer flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                সম্পাদনা
                                            </button>
                                            <button type="button" wire:click="confirmDelete({{ $t->id }})" @click="openDropdown = false" class="w-full text-left px-3 py-2 hover:bg-red-50 dark:hover:bg-red-950/20 text-red-600 dark:text-red-400 transition-all font-semibold rounded-xl cursor-pointer flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                মুছুন
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-10 text-center text-gray-400 dark:text-slate-500 text-sm">
                            <svg class="w-8 h-8 mx-auto mb-2 text-gray-300 dark:text-slate-700" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            কোনো দেওয়া হিসাব নেই
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Table Footer for Given List --}}
            @if($givenList->total() > 0)
            <div class="px-4 py-3 border-t border-gray-100 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-3 bg-gray-50/50 dark:bg-slate-950/30">
                <p class="text-xs text-gray-500 dark:text-slate-400 font-sans whitespace-nowrap">
                    Showing {{ $givenList->firstItem() }} to {{ $givenList->lastItem() }} of {{ $givenList->total() }} results
                </p>

                <div class="flex items-center gap-3">
                    {{-- Per Page --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" type="button"
                                class="flex items-center gap-1.5 px-3 py-1.5 bg-white dark:bg-slate-800 text-gray-700 dark:text-white font-bold rounded-lg text-xs border border-gray-200 dark:border-slate-700 transition-all cursor-pointer">
                            <span>{{ $givenPerPage }} / পেজ</span>
                            <svg class="w-3.5 h-3.5 text-gray-500 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" @click.outside="open = false"
                             class="absolute bottom-full mb-1.5 right-0 z-[999] w-32 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl shadow-xl overflow-hidden"
                             x-cloak>
                            <div class="py-1">
                                @foreach([10, 15, 25, 50] as $size)
                                <button type="button" wire:click="$set('givenPerPage', {{ $size }}); open = false" @click="open = false"
                                        class="w-full text-left px-3 py-2 text-xs font-semibold transition-all cursor-pointer
                                            {{ $givenPerPage == $size ? 'bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                                    {{ $size }} রেকর্ড
                                </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Page Buttons --}}
                    <div class="flex items-center gap-1">
                        @if($givenList->onFirstPage())
                            <button disabled class="p-1.5 rounded-lg border border-gray-200 dark:border-slate-800 text-gray-300 dark:text-slate-700 cursor-not-allowed">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                            </button>
                        @else
                            <button wire:click="previousPage('givenPage')" class="p-1.5 rounded-lg border border-gray-200 dark:border-slate-800 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-300 cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                            </button>
                        @endif

                        @php
                            $gStart = max(1, $givenList->currentPage() - 1);
                            $gEnd   = min($givenList->lastPage(), $givenList->currentPage() + 1);
                        @endphp
                        @for($page = $gStart; $page <= $gEnd; $page++)
                            @if($page == $givenList->currentPage())
                                <span class="px-2.5 py-1 bg-emerald-50 dark:bg-emerald-950/30 text-[#034C3C] dark:text-emerald-400 font-bold rounded-lg text-xs border border-emerald-200 dark:border-emerald-900 font-mono">{{ $page }}</span>
                            @else
                                <button wire:click="gotoPage({{ $page }}, 'givenPage')" class="px-2.5 py-1 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-300 font-bold rounded-lg text-xs border border-gray-200 dark:border-slate-700 font-mono cursor-pointer">{{ $page }}</button>
                            @endif
                        @endfor

                        @if($givenList->hasMorePages())
                            <button wire:click="nextPage('givenPage')" class="p-1.5 rounded-lg border border-gray-200 dark:border-slate-800 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-300 cursor-pointer">
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

        {{-- ===== RIGHT: টাকা নেওয়ার লিস্ট ===== --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200/80 dark:border-slate-800 shadow-sm overflow-hidden flex flex-col justify-between transition-colors duration-300">
            <div>
                {{-- Header --}}
                <div class="bg-[#034C3C] px-5 py-3.5 flex items-center justify-between">
                    <h3 class="text-white font-bold text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 text-white/80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 17H5m0 0v-8m0 8l8-8 4 4 6-6"/>
                        </svg>
                        টাকা নেওয়ার লিস্ট
                        <span class="bg-white/20 text-white text-xs font-bold px-2 py-0.5 rounded-full">({{ $receivedList->total() }})</span>
                    </h3>
                    <span class="text-white/90 text-xs font-semibold font-mono">
                        মোট নেওয়াঃ ৳{{ number_format($receivedTotal, 0) }}
                    </span>
                </div>

                {{-- Table Header --}}
                <div class="hidden md:grid grid-cols-12 text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase bg-gray-50 dark:bg-slate-950 border-b border-gray-100 dark:border-slate-800 px-4 py-2.5">
                    <div class="col-span-6">নাম / ঠিকানা</div>
                    <div class="col-span-4 text-right">টাকা নিয়েছি</div>
                    <div class="col-span-2 text-center">বাটন</div>
                </div>

                {{-- List --}}
                <div class="divide-y divide-gray-100 dark:divide-slate-800/60">
                    @forelse($receivedList as $t)
                        <div class="grid grid-cols-12 items-center px-4 py-3 hover:bg-emerald-50/30 dark:hover:bg-slate-800/30 transition-colors gap-2">
                            {{-- Left: Info --}}
                            <div class="col-span-6 flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-950/30 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <a href="{{ route('deuna-pauna.profile', $t->id) }}" wire:navigate
                                       class="font-bold text-sm text-gray-800 dark:text-white hover:text-[#009669] dark:hover:text-emerald-400 transition-colors block truncate cursor-pointer">
                                        {{ $t->ledger_name }}
                                    </a>
                                    @if($t->address)
                                        <p class="text-[11px] text-emerald-600 dark:text-emerald-400 font-medium truncate">{{ $t->address }}</p>
                                    @endif
                                </div>
                            </div>
                            {{-- Middle: Amount --}}
                            <div class="col-span-4 text-right">
                                <span class="font-mono font-bold text-sm text-emerald-700 dark:text-emerald-400">
                                    ৳{{ number_format($t->amount - $t->paid_amount, 0) }}
                                </span>
                            </div>
                            {{-- Right: Dropdown Action --}}
                            <div class="col-span-2 flex justify-center">
                                <div class="relative" x-data="{ openDropdown: false, buttonRect: null }">
                                    <button type="button" @click="openDropdown = !openDropdown; buttonRect = $el.getBoundingClientRect()" class="p-1.5 text-gray-500 hover:text-emerald-600 dark:hover:text-emerald-400 focus:outline-none transition-all cursor-pointer">
                                        <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z"/>
                                        </svg>
                                    </button>
                                    <template x-teleport="body">
                                        <div x-show="openDropdown" @click.away="openDropdown = false" x-transition
                                             class="fixed w-44 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-2xl shadow-2xl p-1.5 z-[9999] text-left text-xs flex flex-col gap-0.5"
                                             :style="buttonRect ? ('left: ' + (buttonRect.left - 130) + 'px; position: fixed; ' + (window.innerHeight - buttonRect.bottom < 180 ? 'bottom: ' + (window.innerHeight - buttonRect.top + 4) + 'px;' : 'top: ' + (buttonRect.bottom + 4) + 'px;')) : ''"
                                             x-cloak>
                                            <a href="{{ route('deuna-pauna.profile', $t->id) }}" wire:navigate @click="openDropdown = false" class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 text-gray-700 dark:text-slate-200 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-xl cursor-pointer flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                প্রোফাইল দেখুন
                                            </a>
                                            <button type="button" wire:click="openEditModal({{ $t->id }})" @click="openDropdown = false" class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 text-gray-700 dark:text-slate-200 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-xl cursor-pointer flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                সম্পাদনা
                                            </button>
                                            <button type="button" wire:click="confirmDelete({{ $t->id }})" @click="openDropdown = false" class="w-full text-left px-3 py-2 hover:bg-red-50 dark:hover:bg-red-950/20 text-red-600 dark:text-red-400 transition-all font-semibold rounded-xl cursor-pointer flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                মুছুন
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-10 text-center text-gray-400 dark:text-slate-500 text-sm">
                            <svg class="w-8 h-8 mx-auto mb-2 text-gray-300 dark:text-slate-700" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            কোনো নেওয়া হিসাব নেই
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Table Footer for Received List --}}
            @if($receivedList->total() > 0)
            <div class="px-4 py-3 border-t border-gray-100 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-3 bg-gray-50/50 dark:bg-slate-950/30">
                <p class="text-xs text-gray-500 dark:text-slate-400 font-sans whitespace-nowrap">
                    Showing {{ $receivedList->firstItem() }} to {{ $receivedList->lastItem() }} of {{ $receivedList->total() }} results
                </p>

                <div class="flex items-center gap-3">
                    {{-- Per Page --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" type="button"
                                class="flex items-center gap-1.5 px-3 py-1.5 bg-white dark:bg-slate-800 text-gray-700 dark:text-white font-bold rounded-lg text-xs border border-gray-200 dark:border-slate-700 transition-all cursor-pointer">
                            <span>{{ $receivedPerPage }} / পেজ</span>
                            <svg class="w-3.5 h-3.5 text-gray-500 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" @click.outside="open = false"
                             class="absolute bottom-full mb-1.5 right-0 z-[999] w-32 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl shadow-xl overflow-hidden"
                             x-cloak>
                            <div class="py-1">
                                @foreach([10, 15, 25, 50] as $size)
                                <button type="button" wire:click="$set('receivedPerPage', {{ $size }}); open = false" @click="open = false"
                                        class="w-full text-left px-3 py-2 text-xs font-semibold transition-all cursor-pointer
                                            {{ $receivedPerPage == $size ? 'bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                                    {{ $size }} রেকর্ড
                                </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Page Buttons --}}
                    <div class="flex items-center gap-1">
                        @if($receivedList->onFirstPage())
                            <button disabled class="p-1.5 rounded-lg border border-gray-200 dark:border-slate-800 text-gray-300 dark:text-slate-700 cursor-not-allowed">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                            </button>
                        @else
                            <button wire:click="previousPage('receivedPage')" class="p-1.5 rounded-lg border border-gray-200 dark:border-slate-800 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-300 cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                            </button>
                        @endif

                        @php
                            $rStart = max(1, $receivedList->currentPage() - 1);
                            $rEnd   = min($receivedList->lastPage(), $receivedList->currentPage() + 1);
                        @endphp
                        @for($page = $rStart; $page <= $rEnd; $page++)
                            @if($page == $receivedList->currentPage())
                                <span class="px-2.5 py-1 bg-emerald-50 dark:bg-emerald-950/30 text-[#034C3C] dark:text-emerald-400 font-bold rounded-lg text-xs border border-emerald-200 dark:border-emerald-900 font-mono">{{ $page }}</span>
                            @else
                                <button wire:click="gotoPage({{ $page }}, 'receivedPage')" class="px-2.5 py-1 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-300 font-bold rounded-lg text-xs border border-gray-200 dark:border-slate-700 font-mono cursor-pointer">{{ $page }}</button>
                            @endif
                        @endfor

                        @if($receivedList->hasMorePages())
                            <button wire:click="nextPage('receivedPage')" class="p-1.5 rounded-lg border border-gray-200 dark:border-slate-800 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-300 cursor-pointer">
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
    </div>

    {{-- ========== ADD/EDIT MODAL ========== --}}
    <template x-teleport="body">
        <div x-data="{ open: @entangle('showModal') }"
             x-show="open"
             @click.self="$wire.closeModal()"
             x-transition:enter="transition ease-out duration-250"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[70] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
             x-cloak>

            <div x-show="open"
                 x-transition:enter="transition ease-out duration-250 transform"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150 transform"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="bg-white dark:bg-slate-900 rounded-2xl w-full max-w-xl border border-gray-200 dark:border-slate-700 shadow-2xl relative overflow-hidden max-h-[90vh] overflow-y-auto">

                {{-- Modal Header --}}
                <div class="bg-[#009669] px-6 py-4 flex items-center justify-between sticky top-0 z-10">
                    <h3 class="text-white font-bold text-sm flex items-center gap-2 font-sans">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        {{ $editingId ? 'হিসাব আপডেট করুন' : 'লেনদেনের নতুন হিসাব' }}
                    </h3>
                    <button type="button" wire:click="closeModal" class="text-white/80 hover:text-white cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="p-6 space-y-4">
                    
                    {{-- Row 1: Name + Amount --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1.5 font-sans">লেনদেনের নাম <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="ledger_name" placeholder="ব্যক্তি / প্রতিষ্ঠানের নাম"
                                   class="w-full py-2 px-3 rounded-xl border border-gray-250 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all font-sans font-semibold">
                            @error('ledger_name') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1.5 font-sans">টাকা <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="amount" placeholder="০.০০"
                                   oninput="this.value = this.value.replace(/[^0-9.]/g, '')"
                                   @if($editingId) readonly @endif
                                   class="w-full py-2 px-3 rounded-xl border border-gray-250 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all font-mono font-semibold {{ $editingId ? 'opacity-70 cursor-not-allowed bg-gray-100 dark:bg-slate-900' : '' }}">
                            @error('amount') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Row 2: Type --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1.5 font-sans">ধরণ</label>
                        <div class="relative" x-data="{ open: false }">
                            <button type="button" @click="open = !open"
                                    class="w-full flex items-center justify-between py-2 px-3 rounded-xl border border-gray-250 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-xs font-semibold text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 cursor-pointer text-left">
                                <span>{{ $transaction_type === 'দেওয়া' ? 'দেওয়া (আমার বাকি)' : 'নেওয়া (অন্যের বাকি)' }}</span>
                                <svg class="w-3.5 h-3.5 text-gray-400 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" @click.outside="open = false" x-cloak
                                 class="absolute top-full mt-1 left-0 w-full bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl shadow-xl z-[999] overflow-hidden">
                                <button type="button" @click="$wire.set('transaction_type', 'নেওয়া'); open = false"
                                        class="w-full text-left px-3 py-2.5 text-xs font-semibold hover:bg-emerald-50 dark:hover:bg-emerald-950/20 text-gray-800 dark:text-white hover:text-emerald-700 dark:hover:text-emerald-400 transition-all cursor-pointer">নেওয়া (অন্যের বাকি)</button>
                                <button type="button" @click="$wire.set('transaction_type', 'দেওয়া'); open = false"
                                        class="w-full text-left px-3 py-2.5 text-xs font-semibold hover:bg-emerald-50 dark:hover:bg-emerald-950/20 text-gray-800 dark:text-white hover:text-emerald-700 dark:hover:text-emerald-400 transition-all cursor-pointer">দেওয়া (আমার বাকি)</button>
                            </div>
                        </div>
                    </div>

                    {{-- Row 3: Address + Phone --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1.5 font-sans">ঠিকানা</label>
                            <input type="text" wire:model="address" placeholder="ঠিকানা"
                                   class="w-full py-2 px-3 rounded-xl border border-gray-250 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all font-sans font-semibold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1.5 font-sans">ফোন নম্বর</label>
                            <input type="text" wire:model="phone" placeholder="০১XXXXXXXXX"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                   class="w-full py-2 px-3 rounded-xl border border-gray-250 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all font-mono font-semibold">
                        </div>
                    </div>

                    {{-- Row 4: Transaction Date + Due Date (Side-by-Side) --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1.5 font-sans">লেনদেনের তারিখ</label>
                            <div class="relative flex items-center"
                                 x-data
                                 x-effect="
                                    let val = $wire.transaction_date;
                                    let input = $refs.trxInput;
                                    if (input && input._flatpickr) {
                                        if (val) input._flatpickr.setDate(val, false);
                                        else input._flatpickr.clear();
                                    }
                                 ">
                                <input x-ref="trxInput"
                                       type="text"
                                       data-flatpickr
                                       data-wire-prop="transaction_date"
                                       data-default="{{ $transaction_date }}"
                                       wire:model="transaction_date"
                                       placeholder="লেনদেনের তারিখ"
                                       readonly
                                       class="w-full py-2 pl-3 pr-9 rounded-xl border border-gray-250 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 transition-all font-sans font-semibold cursor-pointer">
                                <span class="absolute right-2.5 text-emerald-600 pointer-events-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                </span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1.5 font-sans">পরিশোধের তারিখ</label>
                            <div class="relative flex items-center"
                                 x-data
                                 x-effect="
                                    let val = $wire.due_date;
                                    let input = $refs.dueInput;
                                    if (input && input._flatpickr) {
                                        if (val) input._flatpickr.setDate(val, false);
                                        else input._flatpickr.clear();
                                    }
                                 ">
                                <input x-ref="dueInput"
                                       type="text"
                                       data-flatpickr
                                       data-wire-prop="due_date"
                                       data-default="{{ $due_date }}"
                                       wire:model="due_date"
                                       placeholder="পরিশোধের তারিখ"
                                       readonly
                                       class="w-full py-2 pl-3 pr-9 rounded-xl border border-gray-250 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 transition-all font-sans font-semibold cursor-pointer">
                                <span class="absolute right-2.5 text-emerald-600 pointer-events-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Row 5: Witness 1 + Witness 2 --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1.5 font-sans">সাক্ষী ১</label>
                            <input type="text" wire:model="row1" placeholder="সাক্ষী ১"
                                   class="w-full py-2 px-3 rounded-xl border border-gray-250 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all font-sans">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1.5 font-sans">সাক্ষী ২</label>
                            <input type="text" wire:model="row2" placeholder="সাক্ষী ২"
                                   class="w-full py-2 px-3 rounded-xl border border-gray-250 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all font-sans">
                        </div>
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1.5 font-sans">লেনদেনের কারণ বর্ণনা করুন</label>
                        <textarea wire:model="description" rows="3" placeholder="বিস্তারিত বিবরণ..."
                                  class="w-full py-2 px-3 rounded-xl border border-gray-250 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all font-sans resize-none"></textarea>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="px-6 pb-6 flex items-center justify-between gap-3 border-t border-gray-100 dark:border-slate-800 pt-4">
                    <button type="button" wire:click="closeModal"
                            class="px-5 py-2.5 text-xs font-bold rounded-xl border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800 transition-all cursor-pointer font-sans">
                        রিসেট
                    </button>
                    <button type="button" wire:click="save"
                            class="px-6 py-2.5 bg-[#009669] hover:bg-emerald-700 text-white text-xs font-bold rounded-xl cursor-pointer transition-all active:scale-95 font-sans flex items-center gap-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        সেভ
                    </button>
                </div>
            </div>
        </div>
    </template>

    {{-- ========== DELETE CONFIRMATION MODAL ========== --}}
    <template x-teleport="body">
        <div x-data="{ open: @entangle('showDeleteConfirmModal') }"
             x-show="open"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click.self="open = false; $wire.set('showDeleteConfirmModal', false)"
             class="fixed inset-0 z-[9998] flex items-center justify-center p-4 bg-black/30 backdrop-blur-xs"
             x-cloak>
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-gray-100 dark:border-slate-800 w-full max-w-xs p-5 flex flex-col items-center gap-3 text-center"
                 x-show="open"
                 x-transition:enter="transition ease-out duration-200 transform"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150 transform"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-2">
                <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-950/30 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-800 dark:text-white font-sans">হিসাব ডিলেট করবেন?</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 font-sans">এই হিসাবটি স্থায়ীভাবে মুছে যাবে।</p>
                </div>
                <div class="flex gap-2.5 w-full justify-center mt-1">
                    <button type="button"
                            @click="open = false; $wire.set('showDeleteConfirmModal', false)"
                            class="px-5 py-2 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-200 text-xs font-bold rounded-xl cursor-pointer transition-all font-sans">
                        না
                    </button>
                    <button type="button"
                            wire:click="delete"
                            class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl shadow-sm cursor-pointer transition-all active:scale-95 font-sans">
                        হ্যাঁ, ডিলেট করুন
                    </button>
                </div>
            </div>
        </div>
    </template>

</div>
