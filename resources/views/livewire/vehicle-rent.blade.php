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
    
    <!-- Main Card Container -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200/80 dark:border-slate-800 transition-colors duration-300">
        
        <!-- Header Bar -->
        <div class="p-4 sm:p-6 border-b border-gray-100 dark:border-slate-800 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <h2 class="text-lg font-bold text-emerald-700 dark:text-emerald-400 flex items-center gap-2">
                <span>গাড়ি ভাড়ার তালিকা</span>
            </h2>

            <!-- Search Bar -->
            <div class="w-full sm:w-72 relative">
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search"
                    placeholder="সাইট খুঁজুন..." 
                    class="w-full pl-3 pr-9 py-1.5 text-xs bg-gray-50 dark:bg-slate-800 text-gray-800 dark:text-white rounded-lg border border-gray-200 dark:border-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all placeholder:text-gray-400"
                >
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Table View (Desktop) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-[#009669] text-white text-xs font-bold uppercase tracking-wider">
                        <th class="py-3 px-4 w-12 text-center">#</th>
                        <th class="py-3 px-4">ঠিকানা</th>
                        <th class="py-3 px-4">এরিয়া</th>
                        <th class="py-3 px-4 text-center">ভাড়া</th>
                        <th class="py-3 px-4 text-center w-20">বাটন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800/60 font-sans">
                    @forelse($rents as $index => $rent)
                        <tr class="hover:bg-emerald-50/30 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-3 px-4 text-center font-mono font-semibold text-gray-500 dark:text-slate-400">
                                {{ toBanglaNum($rents->firstItem() + $index) }}
                            </td>
                            <td class="py-3 px-4 font-semibold text-gray-800 dark:text-slate-200">
                                {{ $rent->address }}
                            </td>
                            <td class="py-3 px-4 text-rose-400 dark:text-rose-400 font-mono text-xs">
                                {{ $rent->area ?: '' }}
                            </td>
                            <td class="py-3 px-4 text-center font-mono font-bold text-emerald-600 dark:text-emerald-400">
                                ৳ {{ toBanglaNum($rent->fare) }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <button 
                                    type="button" 
                                    wire:click="openEditModal({{ $rent->id }})"
                                    class="p-1.5 rounded-lg text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-950/60 transition-all cursor-pointer"
                                    title="সম্পাদনা">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-400 dark:text-slate-500 text-sm">
                                কোনো গাড়ি ভাড়ার তথ্য পাওয়া যায়নি।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile View (Card List) -->
        <div class="md:hidden divide-y divide-gray-100 dark:divide-slate-800">
            @forelse($rents as $index => $rent)
                <div class="p-4 space-y-2 bg-white dark:bg-slate-900">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded text-xs font-mono font-bold bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300">
                                #{{ toBanglaNum($rents->firstItem() + $index) }}
                            </span>
                            <span class="font-bold text-gray-800 dark:text-slate-200">
                                {{ $rent->address }}
                            </span>
                        </div>
                        <button 
                            type="button" 
                            wire:click="openEditModal({{ $rent->id }})"
                            class="p-1.5 rounded-lg text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-950/60 transition-all cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                    </div>
                    <div class="flex items-center justify-between text-xs pt-1">
                        <span class="text-gray-500 dark:text-slate-400">এরিয়া: <strong class="text-rose-400">{{ $rent->area ?: '—' }}</strong></span>
                        <span class="font-mono font-bold text-emerald-600 dark:text-emerald-400">ভাড়া: ৳ {{ toBanglaNum($rent->fare) }}</span>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-400 dark:text-slate-500 text-sm">
                    কোনো গাড়ি ভাড়ার তথ্য পাওয়া যায়নি।
                </div>
            @endforelse
        </div>

        <!-- Footer / Pagination -->
        <div class="p-4 border-t border-gray-100 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
            <!-- Left Side: Per Page Dropdown & Total Counter -->
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
                        @foreach([5, 10, 15, 20, 40, 50] as $size)
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

            <!-- Right Side: Pagination Links with Gap -->
            <div class="flex items-center gap-4 [&_p]:mr-6 [&_p]:md:mr-8 [&_p]:text-xs [&_div]:gap-3">
                {{ $rents->links() }}
            </div>
        </div>

    </div>

    <!-- Edit Rent Modal (ভাড়া আপডেট করুন) -->
    @if($showEditModal)
        <div 
            x-data="{ show: true }"
            x-show="show"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
            
            <div 
                @click.away="$wire.closeModal()"
                class="w-full max-w-md bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-gray-200 dark:border-slate-800 overflow-hidden space-y-4 p-6">
                
                <!-- Modal Header -->
                <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-800 pb-3">
                    <h3 class="text-base font-bold text-gray-800 dark:text-white">
                        ভাড়া আপডেট করুন
                    </h3>
                    <button 
                        type="button" 
                        wire:click="closeModal" 
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 p-1 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body Form -->
                <form wire:submit.prevent="saveRent" class="space-y-4">
                    <!-- ঠিকানা (Address) -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-slate-300 mb-1">ঠিকানা</label>
                        <input 
                            type="text" 
                            wire:model="address" 
                            placeholder="ঠিকানা লিখুন" 
                            class="w-full px-3 py-2 text-xs bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-gray-800 dark:text-white"
                        >
                        @error('address') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- এরিয়া (Area) -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-slate-300 mb-1">এরিয়া</label>
                        <input 
                            type="text" 
                            wire:model="area" 
                            placeholder="এরিয়া" 
                            class="w-full px-3 py-2 text-xs bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-gray-800 dark:text-white placeholder:text-gray-400"
                        >
                    </div>

                    <!-- ভাড়া (Rent) -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-slate-300 mb-1">ভাড়া</label>
                        <input 
                            type="number" 
                            step="any"
                            wire:model="fare" 
                            placeholder="ভাড়া" 
                            class="w-full px-3 py-2 text-xs bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-gray-800 dark:text-white font-mono"
                        >
                        @error('fare') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Modal Actions -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-slate-800">
                        <button 
                            type="button" 
                            wire:click="closeModal" 
                            class="px-4 py-2 text-xs font-semibold text-gray-600 dark:text-slate-300 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 rounded-xl transition-all flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            বাতিল
                        </button>
                        <button 
                            type="submit" 
                            class="px-4 py-2 text-xs font-semibold text-white bg-[#009669] hover:bg-emerald-700 rounded-xl transition-all shadow-md flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            আপডেট
                        </button>
                    </div>
                </form>

            </div>
        </div>
    @endif

</div>
