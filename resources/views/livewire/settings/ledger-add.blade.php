<div class="space-y-6">
    <!-- Success Alert -->
    @if (session()->has('message'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 3000)"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="p-4 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-900 text-emerald-800 dark:text-emerald-400 rounded-2xl flex items-center gap-3 text-sm shadow-sm"
             x-cloak>
            <span class="font-medium">{{ session('message') }}</span>
        </div>
    @endif

    <!-- Full-width Table Card -->
    <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-3xl p-6 shadow-sm transition-colors duration-300">
        <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-800 pb-4 mb-6">
            <div>
                <h3 class="font-bold text-sm text-gray-800 dark:text-white">খতিয়ান তালিকা</h3>
                <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5 font-sans">সকল খতিয়ান একসাথে দেখুন ও পরিচালনা করুন</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-2.5 py-0.5 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-100 dark:border-emerald-900 text-emerald-700 dark:text-emerald-400 font-bold rounded-full text-[10px]">
                    মোট: {{ count($ledgers) }} টি
                </span>
                <button wire:click="openModal"
                        class="flex items-center gap-1.5 px-4 py-2 bg-primary hover:bg-emerald-600 text-white text-xs font-bold rounded-xl transition-all shadow-md cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    নতুন খতিয়ান
                </button>
            </div>
        </div>

        <!-- Search -->
        <div class="mb-4">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                </svg>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="খতিয়ান বা গ্রুপ খুঁজুন..."
                       class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all">
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-slate-950 border-b border-gray-100 dark:border-slate-800 text-gray-500 dark:text-slate-400 font-bold">
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
                            <td class="px-4 py-3 font-semibold text-gray-800 dark:text-slate-200">
                                {{ $ledger->name }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold
                                    @if($ledger->group === 'কাস্টমার') bg-emerald-50 text-emerald-700 dark:bg-emerald-950/20 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/50
                                    @elseif($ledger->group === 'সরবরাহকারী') bg-blue-50 text-blue-700 dark:bg-blue-950/20 dark:text-blue-400 border border-blue-100 dark:border-blue-900/50
                                    @elseif($ledger->group === 'খরচ') bg-rose-50 text-rose-700 dark:bg-rose-950/20 dark:text-rose-400 border border-rose-100 dark:border-rose-900/50
                                    @elseif($ledger->group === 'আয়') bg-orange-50 text-orange-700 dark:bg-orange-950/20 dark:text-orange-400 border border-orange-100 dark:border-orange-900/50
                                    @else bg-gray-100 text-gray-600 dark:bg-slate-800 dark:text-slate-300 border border-gray-200 dark:border-slate-700 @endif">
                                    {{ $ledger->group }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right font-bold text-gray-900 dark:text-white">
                                {{ $ledger->rate ? '৳ ' . number_format($ledger->rate, 2) : '—' }}
                            </td>
                            <td class="px-4 py-3 text-center font-semibold text-gray-600 dark:text-slate-300">
                                {{ $ledger->divisor }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="editLedger({{ $ledger->id }})"
                                            class="p-1.5 border border-gray-200 dark:border-slate-700 hover:border-emerald-500 text-gray-500 hover:text-emerald-600 dark:hover:text-emerald-400 rounded-lg transition-all cursor-pointer"
                                            title="এডিট">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"/>
                                        </svg>
                                    </button>
                                    <button wire:click="deleteLedger({{ $ledger->id }})"
                                            onclick="confirm('খতিয়ানটি মুছে ফেলবেন?') || event.stopImmediatePropagation()"
                                            class="p-1.5 border border-red-100 dark:border-red-950/30 hover:bg-red-50 dark:hover:bg-red-950/20 text-red-500 rounded-lg transition-all cursor-pointer"
                                            title="মুছে ফেলুন">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-400 dark:text-gray-500 italic">
                                কোনো খতিয়ান যুক্ত করা হয়নি।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Form (teleported to root) -->
    <template x-teleport="body">
        <div x-data="{ open: @entangle('showModal') }"
             x-show="open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click.self="open = false; $wire.cancelEdit()"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
             x-cloak>

            <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-md w-full border border-gray-100 dark:border-slate-800 shadow-2xl p-6 relative"
                 x-show="open"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4">

                <!-- Close -->
                <button type="button" @click="open = false; $wire.cancelEdit()"
                        class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors focus:outline-none cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <h3 class="text-base font-bold text-gray-800 dark:text-white mb-5 border-b border-gray-100 dark:border-slate-800 pb-2 font-sans">
                    {{ $editingLedgerId ? 'খতিয়ান সংশোধন করুন' : 'নতুন খতিয়ান যুক্ত করুন' }}
                </h3>

                <form wire:submit.prevent="save" class="space-y-4">
                    <!-- Name -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">খতিয়ানের নাম</label>
                        <input type="text" wire:model="name" placeholder="যেমন: কয়লা ক্রয় খতিয়ান"
                               class="w-full py-2.5 px-3 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all font-semibold">
                        @error('name') <span class="text-red-500 text-[10px] mt-1 block font-sans">{{ $message }}</span> @enderror
                    </div>

                    <!-- Group Dropdown: Dynamic with filter/add/delete -->
                    <div class="relative" x-data="{ open2: false, groupSearch: '', groupVal: @entangle('group') }">
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">গ্রুপ / ধরন</label>
                        <button type="button" @click="open2 = !open2"
                                class="w-full flex items-center justify-between py-2.5 px-3 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs font-semibold text-gray-800 dark:text-white focus:outline-none cursor-pointer text-left transition-all">
                            <span x-text="groupVal || 'গ্রুপ নির্বাচন করুন'"></span>
                            <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open2 }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open2" @click.away="open2 = false" x-transition
                             class="absolute left-0 right-0 mt-1.5 bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-gray-100 dark:border-slate-800 z-50 overflow-hidden" x-cloak>
                            <!-- Single Filter + Add Input -->
                            <div class="p-2 border-b border-gray-100 dark:border-slate-800 flex gap-2">
                                <input type="text" wire:model="newGroupInput"
                                       placeholder="ফিল্টার বা নতুন গ্রুপ..."
                                       class="flex-1 py-1.5 px-3 rounded-lg border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 transition-all font-sans"
                                       wire:keydown.enter.prevent="addGroup">
                                <button type="button" wire:click="addGroup"
                                        class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg transition-all cursor-pointer flex-shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                    </svg>
                                </button>
                            </div>
                            <!-- Options filtered by newGroupInput -->
                            <div class="max-h-40 overflow-y-auto py-1">
                                @foreach($groupOptions as $opt)
                                    <div class="flex items-center justify-between px-3 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 transition-all"
                                         x-show="$wire.newGroupInput === '' || '{{ $opt }}'.toLowerCase().includes($wire.newGroupInput.toLowerCase())">
                                        <button type="button" @click="groupVal = '{{ $opt }}'; open2 = false; $wire.set('newGroupInput', '')"
                                                class="flex-1 text-left text-xs font-semibold text-gray-800 dark:text-white hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-sans">
                                            {{ $opt }}
                                        </button>
                                        <button type="button" wire:click="deleteGroup('{{ $opt }}')"
                                                onclick="confirm('এই গ্রুপটি মুছবেন?') || event.stopImmediatePropagation()"
                                                class="ml-2 p-1 text-gray-400 hover:text-red-500 transition-all rounded-lg cursor-pointer flex-shrink-0">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @error('group') <span class="text-red-500 text-[10px] mt-1 block font-sans">{{ $message }}</span> @enderror
                    </div>

                    <!-- Rate & Divisor side by side -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">রেট (ঐচ্ছিক)</label>
                            <input type="number" step="0.01" wire:model="rate" placeholder="৳ 0.00"
                                   class="w-full py-2.5 px-3 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all font-semibold">
                            @error('rate') <span class="text-red-500 text-[10px] mt-1 block font-sans">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">পরিমাণ ভাজক</label>
                            <input type="number" min="1" wire:model="divisor" placeholder="যেমন: 1"
                                   class="w-full py-2.5 px-3 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all font-semibold">
                            @error('divisor') <span class="text-red-500 text-[10px] mt-1 block font-sans">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100 dark:border-slate-800">
                        <button type="button" @click="open = false; $wire.cancelEdit()"
                                class="px-4 py-2 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-200 hover:text-gray-900 dark:hover:text-white text-xs font-semibold rounded-xl cursor-pointer transition-all font-sans">
                            বাতিল
                        </button>
                        <button type="submit"
                                class="px-6 py-2 bg-primary text-white text-xs font-bold rounded-xl cursor-pointer shadow-md hover:bg-emerald-600 transition-all font-sans">
                            সংরক্ষণ করুন
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>
