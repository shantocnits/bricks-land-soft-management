<div class="space-y-6">
    <!-- Full-width Table Card -->
    <div
        class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-3xl p-4 sm:p-6 shadow-sm transition-colors duration-300">
        <div
            class="flex flex-col md:flex-row items-start md:items-center gap-2 justify-between border-b border-gray-100 dark:border-slate-800 pb-4 mb-6">
            <div>
                <h3 class="font-bold text-sm text-gray-800 dark:text-white">খতিয়ান তালিকা</h3>
                <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5 font-sans">সকল খতিয়ান একসাথে দেখুন ও
                    পরিচালনা করুন</p>
            </div>
            <div class="flex items-center gap-3">
                <span
                    class="px-2.5 py-0.5 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-100 dark:border-emerald-900 text-emerald-700 dark:text-emerald-400 font-bold rounded-full text-[10px]">
                    মোট: {{ count($ledgers) }} টি
                </span>
                <button wire:click="openModal"
                    class="flex items-center gap-1.5 px-4 py-2 bg-primary hover:bg-emerald-600 text-white text-xs font-bold rounded-xl transition-all shadow-md cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    নতুন খতিয়ান
                </button>
            </div>
        </div>

        <!-- Search + Group Manager Button -->
        <div class="mb-4 flex items-center gap-2">
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" fill="none"
                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="খতিয়ান বা গ্রুপ খুঁজুন..."
                    class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all">
            </div>
            <button wire:click="openGroupManager" type="button"
                class="flex items-center gap-1.5 px-3 py-2.5 rounded-xl border border-rose-200 dark:border-rose-900/50 bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-950/50 text-xs font-bold transition-all whitespace-nowrap cursor-pointer shrink-0">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <span class="hidden sm:inline">গ্রুপ বাদ দিন</span>
            </button>
        </div>

        <!-- Desktop Table View -->
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr
                        class="bg-gray-50 dark:bg-slate-950 border-b border-gray-100 dark:border-slate-800 text-gray-500 dark:text-slate-400 font-bold">
                        <th class="px-4 py-3 text-center w-14">সিরিয়াল</th>
                        <th class="px-4 py-3">খতিয়ানের নাম</th>
                        <th class="px-4 py-3 text-center">গ্রুপ</th>
                        <th class="px-4 py-3 text-right">রেট</th>
                        <th class="px-4 py-3 text-center">ভাজক</th>
                        <th class="px-4 py-3 text-right">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800/80">
                    @forelse($ledgers as $ledger)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/25 transition-colors">
                            <td class="px-4 py-3 text-center font-semibold text-gray-700 dark:text-slate-300">
                                {{ $loop->iteration }}
                            </td>
                            <td class="px-4 py-3 font-semibold text-gray-800 dark:text-slate-200">
                                {{ $ledger->name }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span
                                    class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold
                                        @if($ledger->group === 'কাস্টমার') bg-emerald-50 text-emerald-700 dark:bg-emerald-950/20 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/50
                                        @elseif($ledger->group === 'সরবরাহকারী') bg-blue-50 text-blue-700 dark:bg-blue-950/20 dark:text-blue-400 border border-blue-100 dark:border-blue-900/50
                                        @elseif($ledger->group === 'খরচ') bg-rose-50 text-rose-700 dark:bg-rose-950/20 dark:text-rose-400 border border-rose-100 dark:border-rose-900/50
                                        @elseif($ledger->group === 'আয়') bg-orange-50 text-orange-700 dark:bg-orange-950/20 dark:text-orange-400 border border-orange-100 dark:border-orange-900/50
                                        @else bg-gray-100 text-gray-600 dark:bg-slate-800 dark:text-slate-300 border border-gray-200 dark:border-slate-700 @endif">
                                    {{ $ledger->group }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right font-bold text-gray-900 dark:text-white">
                                {{ $ledger->rate ? '৳ ' . number_format((float)($ledger->rate), (float)($ledger->rate) == (int)($ledger->rate) ? 0 : 2) : '—' }}
                            </td>
                            <td class="px-4 py-3 text-center font-semibold text-gray-600 dark:text-slate-300">
                                {{ $ledger->divisor }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="editLedger({{ $ledger->id }})"
                                        class="p-1.5 border border-gray-200 dark:border-slate-700 hover:border-emerald-500 text-gray-500 hover:text-emerald-600 dark:hover:text-emerald-400 rounded-lg transition-all cursor-pointer"
                                        title="এডিট">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                        </svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $ledger->id }})"
                                        class="p-1.5 border border-red-100 dark:border-red-950/30 hover:bg-red-50 dark:hover:bg-red-950/20 text-red-500 rounded-lg transition-all cursor-pointer"
                                        title="মুছে ফেলুন">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-400 dark:text-gray-500 italic">
                                কোনো খতিয়ান যুক্ত করা হয়নি।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Box Cards View -->
        <div class="sm:hidden grid grid-cols-1 gap-3">
            @forelse($ledgers as $ledger)
                <div
                    class="bg-gray-50/60 dark:bg-slate-950/40 p-4 rounded-2xl border border-gray-100 dark:border-slate-800 space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-gray-700 dark:text-slate-300 text-xs">
                                {{ $loop->iteration }}.
                            </span>
                            <span class="font-bold text-gray-800 dark:text-white text-xs">{{ $ledger->name }}</span>
                        </div>
                        <span
                            class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold
                                @if($ledger->group === 'কাস্টমার') bg-emerald-50 text-emerald-700 dark:bg-emerald-950/20 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/50
                                @elseif($ledger->group === 'সরবরাহকারী') bg-blue-50 text-blue-700 dark:bg-blue-950/20 dark:text-blue-400 border border-blue-100 dark:border-blue-900/50
                                @elseif($ledger->group === 'খরচ') bg-rose-50 text-rose-700 dark:bg-rose-950/20 dark:text-rose-400 border border-rose-100 dark:border-rose-900/50
                                @elseif($ledger->group === 'আয়') bg-orange-50 text-orange-700 dark:bg-orange-950/20 dark:text-orange-400 border border-orange-100 dark:border-orange-900/50
                                @else bg-gray-100 text-gray-600 dark:bg-slate-800 dark:text-slate-300 border border-gray-200 dark:border-slate-700 @endif">
                            {{ $ledger->group }}
                        </span>
                    </div>

                    <div
                        class="flex items-center justify-between text-xs pt-2 border-t border-gray-100 dark:border-slate-800/60 font-sans">
                        <div>
                            <span class="text-gray-400 font-normal">রেট: </span>
                            <span
                                class="font-bold text-gray-800 dark:text-white">{{ $ledger->rate ? '৳ ' . number_format((float)($ledger->rate), (float)($ledger->rate) == (int)($ledger->rate) ? 0 : 2) : '—' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 font-normal">ভাজক: </span>
                            <span class="font-bold text-gray-800 dark:text-white">{{ $ledger->divisor }}</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100 dark:border-slate-800/60">
                        <button wire:click="editLedger({{ $ledger->id }})"
                            class="px-3 py-1.5 bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-slate-200 rounded-lg text-xs font-bold flex items-center gap-1 cursor-pointer">
                            ✏️ এডিট
                        </button>
                        <button wire:click="confirmDelete({{ $ledger->id }})"
                            class="px-3 py-1.5 bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 rounded-lg text-xs font-bold flex items-center gap-1 cursor-pointer">
                            🗑️ মুছুন
                        </button>
                    </div>
                </div>
            @empty
                <div class="py-8 text-center text-gray-400 dark:text-gray-500 italic text-xs">
                    কোনো খতিয়ান যুক্ত করা হয়নি।
                </div>
            @endforelse
        </div>

        <!-- Pagination Footer -->
        <div
            class="px-5 py-4 border-t border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
            <div class="text-gray-500 dark:text-gray-400 font-sans font-semibold">
                মোট খতিয়ান {{ $totalCount }} টি
            </div>

            <!-- Page Controls -->
            <div class="flex items-center gap-1 font-sans">
                <button type="button" wire:click="setPage({{ $currentPage - 1 }})" @if($currentPage <= 1) disabled @endif
                    class="w-7 h-7 flex items-center justify-center rounded-lg border border-gray-200 dark:border-slate-800 text-gray-500 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer transition-all">
                    &lt;
                </button>

                @for($p = 1; $p <= $totalPages; $p++)
                    <button type="button" wire:click="setPage({{ $p }})"
                        class="w-7 h-7 flex items-center justify-center rounded-lg font-bold text-xs transition-all cursor-pointer {{ $currentPage == $p ? 'bg-emerald-600 text-white shadow-sm' : 'border border-gray-200 dark:border-slate-800 text-gray-600 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800' }}">
                        {{ $p }}
                    </button>
                @endfor

                <button type="button" wire:click="setPage({{ $currentPage + 1 }})" @if($currentPage >= $totalPages)
                disabled @endif
                    class="w-7 h-7 flex items-center justify-center rounded-lg border border-gray-200 dark:border-slate-800 text-gray-500 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer transition-all">
                    &gt;
                </button>
            </div>

            <!-- Per Page Dropdown (Project Root Dropdown Design) -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" type="button"
                    class="flex items-center justify-between gap-2 px-3.5 py-1.5 bg-white dark:bg-slate-900 text-gray-700 dark:text-slate-200 font-bold rounded-xl text-xs border border-gray-200 dark:border-slate-800 focus:outline-none transition-all shadow-xs cursor-pointer">
                    <span>
                        @if($perPage === 'all')
                            সব (All)
                        @else
                            {{ $perPage }} খতিয়ান / পেজ
                        @endif
                    </span>
                    <svg class="w-3.5 h-3.5 transition-transform duration-200 text-gray-400"
                        :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2.5"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open" @click.outside="open = false"
                    class="absolute bottom-full mb-1.5 right-0 z-[999] w-40 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl shadow-xl overflow-hidden focus:outline-none"
                    x-cloak>
                    <div class="py-1">
                        @foreach ([10, 20, 30, 50, 'all'] as $size)
                            <button type="button" wire:click="setPerPage('{{ $size }}')" @click="open = false"
                                class="w-full text-left px-3 py-2 text-xs font-bold text-gray-800 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-slate-800 hover:text-emerald-700 dark:hover:text-emerald-400 font-sans transition-colors cursor-pointer">
                                {{ $size === 'all' ? 'সব (All)' : $size . ' টি' }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form (teleported to root) -->
    <template x-teleport="body">
        <div x-data="{ open: @entangle('showModal').live }" x-show="open"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            @click.self="open = false; $wire.cancelEdit()"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak>

            <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-lg w-full border border-gray-100 dark:border-slate-800 shadow-2xl overflow-hidden relative font-sans"
                x-show="open" x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4">

                <!-- Header (Dark Navy Header matching Screenshot 1) -->
                <div class="bg-[#0f1c2e] dark:bg-slate-950 px-6 py-5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-white/10 border border-white/10 flex items-center justify-center text-white shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-white font-sans tracking-wide">
                                {{ $editingLedgerId ? 'সম্পাদনা খতিয়ান' : 'নতুন খতিয়ান' }}
                            </h3>
                            <p class="text-[11px] text-slate-300 font-sans mt-0.5">সঠিক তথ্য দিয়ে ডাটা এন্ট্রি করুন</p>
                        </div>
                    </div>
                    <button type="button" @click="open = false; $wire.cancelEdit()"
                        class="w-8 h-8 rounded-xl bg-white/10 hover:bg-white/20 text-white/80 hover:text-white flex items-center justify-center transition-all focus:outline-none cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Form Content -->
                <form wire:submit.prevent="save" class="p-6 space-y-4">
                    <!-- Row 1: Serial & Group -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">সিরিয়াল</label>
                            <input type="text" wire:model="serial" placeholder="01"
                                class="w-full py-2.5 px-3 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all font-mono font-semibold">
                            @error('serial') <span class="text-red-500 text-[10px] mt-1 block font-sans">{{ $message }}</span> @enderror
                        </div>

                        <!-- Group Dropdown: Dynamic with filter/add & Folder Icon (Teleported to Root) -->
                        <div class="relative" x-data="{ open2: false, search: '', rect2: null }">
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">খতিয়ানের গ্রুপ</label>
                            <button type="button" @click="open2 = !open2; rect2 = $el.getBoundingClientRect()"
                                class="w-full flex items-center justify-between py-2.5 px-3 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs font-semibold text-gray-800 dark:text-white focus:outline-none cursor-pointer text-left transition-all">
                                <span class="truncate flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-19.5 0A2.25 2.25 0 004.5 15h15a2.25 2.25 0 002.25-2.25m-19.5 0v.243a2.25 2.25 0 00.864 1.765l.775.62c.39.312.617.781.617 1.274v1.848a2.25 2.25 0 002.25 2.25h10.5a2.25 2.25 0 002.25-2.25v-1.848c0-.493.227-.962.617-1.274l.775-.62a2.25 2.25 0 00.864-1.765V12.75M3.75 6h4.875c.621 0 1.15.402 1.314 1.002L10.3 8.5H20.25A2.25 2.25 0 0122.5 10.75v.75H1.5v-.75A2.25 2.25 0 013.75 6z" />
                                    </svg>
                                    <span x-text="$wire.group || 'গ্রুপ সার্চ বা টাইপ করুন'" class="truncate text-gray-500 dark:text-slate-400" :class="{ 'text-gray-800 dark:text-white font-bold': $wire.group }"></span>
                                </span>
                                <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 shrink-0 ml-1"
                                    :class="{ 'rotate-180': open2 }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <template x-teleport="body">
                            <div x-show="open2" @click.outside="open2 = false" x-transition
                                class="fixed z-[99999999] bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-gray-100 dark:border-slate-800 overflow-hidden font-sans"
                                :style="rect2 ? ('left: ' + rect2.left + 'px; top: ' + (rect2.bottom + 6) + 'px; width: ' + rect2.width + 'px; position: fixed;') : ''"
                                x-cloak>
                                <!-- Single Filter + Add Input -->
                                <div class="p-2 border-b border-gray-100 dark:border-slate-800 flex items-center gap-1.5">
                                    <input type="text" x-model="search" wire:model="newGroupInput"
                                        placeholder="ফিল্টার বা নতুন গ্রুপ..."
                                        class="flex-1 min-w-0 py-1.5 px-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 transition-all font-sans font-medium"
                                        @keydown.enter.prevent="$wire.addGroup(search); search = ''">
                                    <button type="button" @click="$wire.addGroup(search); search = ''"
                                        class="px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-all cursor-pointer flex-shrink-0 whitespace-nowrap">
                                        + অ্যাড
                                    </button>
                                </div>
                                <!-- Options list with folder icon -->
                                <div class="max-h-60 overflow-y-auto py-1">
                                    @foreach($groupOptions as $opt)
                                    <div class="px-3 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 transition-all cursor-pointer flex items-center gap-2"
                                        x-show="search === '' || {{ json_encode($opt) }}.toLowerCase().includes(search.toLowerCase())"
                                        @click="$wire.set('group', {{ json_encode($opt) }}); open2 = false; search = ''; $wire.set('newGroupInput', '')">
                                        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0"
                                            fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-19.5 0A2.25 2.25 0 004.5 15h15a2.25 2.25 0 002.25-2.25m-19.5 0v.243a2.25 2.25 0 00.864 1.765l.775.62c.39.312.617.781.617 1.274v1.848a2.25 2.25 0 002.25 2.25h10.5a2.25 2.25 0 002.25-2.25v-1.848c0-.493.227-.962.617-1.274l.775-.62a2.25 2.25 0 00.864-1.765V12.75M3.75 6h4.875c.621 0 1.15.402 1.314 1.002L10.3 8.5H20.25A2.25 2.25 0 0122.5 10.75v.75H1.5v-.75A2.25 2.25 0 013.75 6z" />
                                        </svg>
                                        <span class="text-xs font-semibold text-gray-800 dark:text-white hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-sans block truncate">
                                            {{ $opt }}
                                        </span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            </template>
                            @error('group') <span class="text-red-500 text-[10px] mt-1 block font-sans">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Row 2: Name -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">খতিয়ানের নাম</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                </svg>
                            </span>
                            <input type="text" wire:model="name" placeholder="খতিয়ানের নাম লিখুন"
                                class="w-full py-2.5 pl-9 pr-3 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all font-semibold">
                        </div>
                        @error('name') <span class="text-red-500 text-[10px] mt-1 block font-sans">{{ $message }}</span> @enderror
                    </div>

                    <!-- Row 3: Rate & Divisor side by side -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">খতিয়ানের রেট</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-6h6"/>
                                    </svg>
                                </span>
                                <input type="number" step="0.01" wire:model="rate" placeholder="0"
                                    class="w-full py-2.5 pl-9 pr-3 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all font-semibold">
                            </div>
                            @error('rate') <span class="text-red-500 text-[10px] mt-1 block font-sans">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">পরিমাণ ভাজক (যদি থাকে)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                    </svg>
                                </span>
                                <input type="number" min="1" wire:model="divisor" placeholder="পরিমাণ ভাজক"
                                    class="w-full py-2.5 pl-9 pr-3 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all font-semibold">
                            </div>
                            @error('divisor') <span class="text-red-500 text-[10px] mt-1 block font-sans">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Note section -->
                    <div class="text-[11px] text-amber-600 dark:text-amber-500 font-medium font-sans leading-relaxed italic">
                        Note: কত পরিমাণ কাজের জন্য এই রেট দেওয়া হবে সেটা উল্লেখ করুন পরিমাণ ভাজকে। যেমন ৮০০ টাকা যদি প্রোডাকশন রেট হয় তাহলে ৫০,০০০ ইট কাটার জন্য ৪০০ টাকা পাবে অর্থাৎ পরিমাণ ভাজক হবে ৫০,০০০
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100 dark:border-slate-800">
                        <button type="button" @click="open = false; $wire.cancelEdit()"
                            class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-200 text-xs font-bold transition-all cursor-pointer">
                            বাতিল
                        </button>
                        <button type="submit"
                            class="px-6 py-2.5 rounded-xl bg-primary hover:bg-emerald-600 text-white text-xs font-bold transition-all shadow-md cursor-pointer">
                            {{ $editingLedgerId ? 'সংরক্ষণ করুন' : 'অ্যাড করুন' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>

    <!-- Delete Confirmation Modal -->
    <template x-teleport="body">
        <div x-data="{ open: @entangle('confirmingDeleteId').live }" x-show="open"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak>
            <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-xs w-full border border-gray-100 dark:border-slate-800 shadow-2xl p-6 text-center space-y-4 font-sans"
                x-show="open" x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                <div
                    class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-gray-800 dark:text-white">আপনি কি খতিয়ানটি মুছে ফেলতে চান?
                    </h3>
                    <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-1">এই কার্যক্রমটি পরবর্তীতে পুনরুদ্ধার করা
                        যাবে না।</p>
                </div>
                <div class="flex items-center justify-center gap-3 pt-1">
                    <button type="button" wire:click="cancelDelete"
                        class="flex-1 py-2 px-3 bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-200 text-xs font-bold rounded-xl transition-all cursor-pointer">
                        না
                    </button>
                    <button type="button" wire:click="deleteLedgerConfirmed"
                        class="flex-1 py-2 px-3 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl transition-all shadow-md cursor-pointer">
                        হ্যাঁ
                    </button>
                </div>
            </div>
        </div>
    </template>

    <!-- Group Manager Modal -->
    <template x-teleport="body">
        <div x-data="{ open: @entangle('showGroupManager').live }" x-show="open"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak>
            <div class="bg-white dark:bg-slate-900 rounded-3xl w-full max-w-md border border-gray-100 dark:border-slate-800 shadow-2xl font-sans overflow-hidden"
                x-show="open" x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                @click.outside="$wire.closeGroupManager()">

                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-slate-800" style="background-color:#0f1c2e;">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-rose-500/20 flex items-center justify-center">
                            <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-white">গ্রুপ বাদ দিন</h3>
                            <p class="text-[10px] text-slate-400 mt-0.5">গ্রুপ নির্বাচন করে মুছে ফেলুন</p>
                        </div>
                    </div>
                    <button type="button" wire:click="closeGroupManager"
                        class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-white/10 text-slate-400 hover:text-white transition-all cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Group List --}}
                <div class="p-4 sm:p-6 max-h-[60vh] overflow-y-auto">
                    @if(count($groupOptions) === 0)
                        <div class="text-center py-8 text-gray-400 dark:text-slate-500 text-xs italic">
                            কোনো গ্রুপ পাওয়া যায়নি।
                        </div>
                    @else
                        <div class="space-y-2">
                            @foreach($groupOptions as $grp)
                                <div class="flex items-center justify-between px-4 py-3 rounded-2xl bg-gray-50 dark:bg-slate-800/60 border border-gray-100 dark:border-slate-700/60 transition-all">
                                    <div class="flex items-center gap-3">
                                        <div class="w-7 h-7 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 flex items-center justify-center">
                                            <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                                            </svg>
                                        </div>
                                        <span class="text-xs font-semibold text-gray-800 dark:text-slate-200">{{ $grp }}</span>
                                    </div>

                                    @if($confirmingDeleteGroup === $grp)
                                        {{-- Confirm row --}}
                                        <div class="flex items-center gap-2">
                                            <span class="text-[10px] text-rose-500 font-semibold">মুছে ফেলবেন?</span>
                                            <button type="button" wire:click="cancelDeleteGroup"
                                                class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-300 hover:bg-gray-200 dark:hover:bg-slate-600 transition-all cursor-pointer">
                                                না
                                            </button>
                                            <button type="button" wire:click="deleteGroupConfirmed"
                                                class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-rose-600 hover:bg-rose-700 text-white transition-all shadow-sm cursor-pointer">
                                                হ্যাঁ
                                            </button>
                                        </div>
                                    @else
                                        <button type="button" wire:click="askDeleteGroup('{{ $grp }}')"
                                            class="w-7 h-7 flex items-center justify-center rounded-xl border border-rose-100 dark:border-rose-900/40 bg-rose-50 dark:bg-rose-950/20 text-rose-500 hover:bg-rose-100 dark:hover:bg-rose-950/40 transition-all cursor-pointer"
                                            title="মুছুন">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 border-t border-gray-100 dark:border-slate-800 flex justify-end">
                    <button type="button" wire:click="closeGroupManager"
                        class="px-4 py-2 rounded-xl text-xs font-bold bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-200 transition-all cursor-pointer">
                        বন্ধ করুন
                    </button>
                </div>
            </div>
        </div>
    </template>

</div>