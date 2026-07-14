<div class="min-h-screen bg-gray-50 dark:bg-slate-950 transition-colors duration-300">

    <!-- Page Header Bar -->
    <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 px-4 sm:px-6 py-3 rounded-lg flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 transition-colors duration-300">
        <div>
            <h2 class="font-bold text-gray-800 dark:text-white text-sm leading-tight">à¦…à¦—à§à¦°à¦¿à¦® à¦šà¦¾à¦²à¦¾à¦¨</h2>
            <p class="text-[10px] text-gray-400 dark:text-gray-500 font-sans mt-0.5">à¦…à¦—à§à¦°à¦¿à¦® à¦ªà§à¦°à¦¦à§‡à¦¯à¦¼ à¦“ à¦¬à¦•à§‡à¦¯à¦¼à¦¾ à¦šà¦¾à¦²à¦¾à¦¨à§‡à¦° à¦¤à¦¾à¦²à¦¿à¦•à¦¾</p>
        </div>

        <!-- Top Actions -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3">
            <div class="relative">
                <input type="text" wire:model.live="search"
                       placeholder="à¦¸à¦¾à¦°à§à¦š à¦•à¦°à§à¦¨..."
                       class="pl-4 pr-4 py-2 text-xs rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all w-full sm:w-52 font-sans font-semibold">
            </div>
            <button type="button" onclick="window.print()"
                    class="px-3 py-2 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-200 text-xs font-semibold rounded-xl cursor-pointer transition-all font-sans border border-gray-200 dark:border-slate-700">
                à¦°à¦¿à¦ªà§‹à¦°à§à¦Ÿ
            </button>
            <button type="button" wire:click="openAddModal"
                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl cursor-pointer transition-all shadow-md active:scale-95 font-sans">
                à¦¨à¦¤à§à¦¨ à¦…à¦—à§à¦°à¦¿à¦® à¦šà¦¾à¦²à¦¾à¦¨
            </button>
        </div>
    </div>

    <!-- Flash Message -->
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
             x-transition:leave="transition ease-in duration-300" x-transition:leave-end="opacity-0"
             class="mx-4 sm:mx-6 mt-4 p-3.5 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-900 text-emerald-800 dark:text-emerald-400 rounded-2xl text-xs font-medium font-sans" x-cloak>
            {{ session('message') }}
        </div>
    @endif

    <!-- Table Card -->
    <div class="py-4 sm:py-6">
        <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-lg shadow-sm overflow-hidden transition-colors duration-300">
            <!-- Summary Bar -->
            <div class="flex flex-wrap items-center gap-4 px-5 py-3.5 border-b border-gray-100 dark:border-slate-800 bg-amber-50/60 dark:bg-amber-950/10">
                <span class="text-xs text-gray-500 dark:text-gray-400 font-sans">à¦®à§‹à¦Ÿ: <strong class="text-gray-800 dark:text-white">{{ $challans->total() }} à¦Ÿà¦¿</strong></span>
            </div>

            <!-- Responsive Table with headers & vertical column borders -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse border border-gray-200 dark:border-slate-800" style="min-width: 1050px">
                    <thead>
                        <tr class="bg-emerald-600 text-white text-[11px] font-bold uppercase font-sans">
                            <th class="px-3 py-3 text-center w-10 border-r border-white/20 last:border-r-0">#</th>
                            <th class="px-3 py-3 border-r border-white/20 last:border-r-0">à¦–à¦¤à¦¿à¦¯à¦¼à¦¾à¦¨</th>
                            <th class="px-3 py-3 border-r border-white/20 last:border-r-0">à¦ à¦¿à¦•à¦¾à¦¨à¦¾</th>
                            <th class="px-3 py-3 border-r border-white/20 last:border-r-0">à¦¶à§à¦°à§‡à¦£à¦¿</th>
                            <th class="px-3 py-3 text-right border-r border-white/20 last:border-r-0">à¦ªà¦°à¦¿à¦®à¦¾à¦£</th>
                            <th class="px-3 py-3 text-right border-r border-white/20 last:border-r-0">à¦°à§‡à¦Ÿ</th>
                            <th class="px-3 py-3 text-right border-r border-white/20 last:border-r-0">à¦®à§‚à¦²à§à¦¯</th>
                            <th class="px-3 py-3 text-right border-r border-white/20 last:border-r-0">à¦®à§‹à¦Ÿ à¦®à§‚à¦²à§à¦¯</th>
                            <th class="px-3 py-3 text-right border-r border-white/20 last:border-r-0">à¦›à¦¾à¦¡à¦¼</th>
                            <th class="px-3 py-3 text-right border-r border-white/20 last:border-r-0">à¦—à¦¾à§œà¦¿ à¦­à¦¾à§œà¦¾</th>
                            <th class="px-3 py-3 text-right border-r border-white/20 last:border-r-0">à¦¸à¦°à§à¦¬à¦®à§‹à¦Ÿ</th>
                            <th class="px-3 py-3 text-right border-r border-white/20 last:border-r-0">à¦¨à¦—à¦¦</th>
                            <th class="px-3 py-3 text-right border-r border-white/20 last:border-r-0">à¦¬à¦¾à¦•à¦¿</th>
                            <th class="px-3 py-3 text-center">à¦¬à¦¾à¦Ÿà¦¨</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800 font-sans">
                        @forelse($challans as $challan)
                            <tr class="hover:bg-emerald-50/40 dark:hover:bg-emerald-950/10 transition-colors text-xs">
                                <td class="px-3 py-3.5 text-center text-gray-500 dark:text-slate-400 font-semibold border-r border-gray-150 dark:border-slate-800 last:border-r-0">{{ $loop->iteration }}</td>
                                <td class="px-3 py-3.5 font-semibold text-gray-800 dark:text-slate-200 whitespace-nowrap border-r border-gray-150 dark:border-slate-800 last:border-r-0">{{ $challan->customer_name }}</td>
                                <td class="px-3 py-3.5 text-gray-600 dark:text-slate-400 border-r border-gray-150 dark:border-slate-800 last:border-r-0">{{ $challan->customer_address }}</td>
                                <td class="px-3 py-3.5 border-r border-gray-150 dark:border-slate-800 last:border-r-0">
                                    @foreach($challan->items as $item)
                                        <span class="block font-semibold text-emerald-700 dark:text-emerald-400">{{ $item->category_name }}</span>
                                    @endforeach
                                </td>
                                <td class="px-3 py-3.5 text-right font-semibold text-gray-700 dark:text-slate-300 border-r border-gray-150 dark:border-slate-800 last:border-r-0">
                                    @foreach($challan->items as $item)
                                        <span class="block">{{ number_format($item->quantity) }}</span>
                                    @endforeach
                                </td>
                                <td class="px-3 py-3.5 text-right text-gray-600 dark:text-slate-400 border-r border-gray-150 dark:border-slate-800 last:border-r-0">
                                    @foreach($challan->items as $item)
                                        <span class="block">à§³{{ number_format($item->rate, 2) }}</span>
                                    @endforeach
                                </td>
                                <td class="px-3 py-3.5 text-right font-semibold text-gray-700 dark:text-slate-300 border-r border-gray-150 dark:border-slate-800 last:border-r-0">à§³{{ number_format($challan->value, 2) }}</td>
                                <td class="px-3 py-3.5 text-right font-semibold text-gray-700 dark:text-slate-300 border-r border-gray-150 dark:border-slate-800 last:border-r-0">à§³{{ number_format($challan->value, 2) }}</td>
                                <td class="px-3 py-3.5 text-right text-amber-600 dark:text-amber-400 border-r border-gray-150 dark:border-slate-800 last:border-r-0">à§³{{ number_format($challan->discount, 2) }}</td>
                                <td class="px-3 py-3.5 text-right font-semibold text-gray-700 dark:text-slate-300 border-r border-gray-150 dark:border-slate-800 last:border-r-0">à§³{{ number_format($challan->transport_rent, 2) }}</td>
                                <td class="px-3 py-3.5 text-right font-bold text-gray-800 dark:text-slate-200 border-r border-gray-150 dark:border-slate-800 last:border-r-0">à§³{{ number_format($challan->grand_total, 2) }}</td>
                                <td class="px-3 py-3.5 text-right text-emerald-600 dark:text-emerald-400 font-semibold border-r border-gray-150 dark:border-slate-800 last:border-r-0">à§³{{ number_format($challan->cash, 2) }}</td>
                                <td class="px-3 py-3.5 text-right border-r border-gray-150 dark:border-slate-800 last:border-r-0">
                                    <span class="font-bold {{ $challan->due > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-400' }}">à§³{{ number_format($challan->due, 2) }}</span>
                                </td>
                                <!-- Dropdown Button Teleported to Body -->
                                <td class="px-3 py-3.5 text-center relative" x-data="{ openDropdown: false, buttonRect: null }">
                                    <button type="button" @click="openDropdown = !openDropdown; buttonRect = $el.getBoundingClientRect()" class="p-1.5 text-gray-500 hover:text-emerald-600 dark:hover:text-emerald-400 focus:outline-none transition-all cursor-pointer">
                                        <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z"/>
                                        </svg>
                                    </button>
                                    
                                    <template x-teleport="body">
                                        <div x-show="openDropdown" @click.away="openDropdown = false" x-transition
                                             class="fixed w-44 bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-2xl shadow-2xl p-1.5 z-[9999] text-left text-xs flex flex-col gap-0.5"
                                             :style="'top: ' + (buttonRect ? (buttonRect.bottom + window.scrollY) : 0) + 'px; left: ' + (buttonRect ? (buttonRect.left + window.scrollX - 130) : 0) + 'px;'"
                                             x-cloak>
                                            <button type="button" wire:click="edit({{ $challan->id }})" @click="openDropdown = false" class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 text-gray-700 dark:text-slate-200 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-xl cursor-pointer">
                                                à¦†à¦ªà¦¡à§‡à¦Ÿ à¦•à¦°à§à¦¨
                                            </button>
                                            <button type="button" @click="openDropdown = false" class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 text-gray-700 dark:text-slate-200 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-xl cursor-pointer">
                                                à¦ªà§à¦°à¦¿à¦¨à§à¦Ÿ à¦šà¦¾à¦²à¦¾à¦¨
                                            </button>
                                            <button type="button" @click="openDropdown = false" class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 text-gray-700 dark:text-slate-200 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-xl cursor-pointer">
                                                à¦¡à§‡à¦²à¦¿à¦­à¦¾à¦°à¦¿ à¦¦à¦¿à¦¨
                                            </button>
                                            <button type="button" @click="openDropdown = false" class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 text-gray-700 dark:text-slate-200 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-xl cursor-pointer">
                                                à¦šà¦¾à¦²à¦¾à¦¨ à¦¬à¦¿à¦¸à§à¦¤à¦¾à¦°à¦¿à¦¤
                                            </button>
                                            <button type="button" @click="openDropdown = false" class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 text-gray-700 dark:text-slate-200 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-xl cursor-pointer">
                                                à¦ªà§‡à¦®à§‡à¦¨à§à¦Ÿ à¦ à¦¯à¦¾à¦¨
                                            </button>
                                            <button type="button" wire:click="delete({{ $challan->id }})" onclick="confirm('à¦šà¦¾à¦²à¦¾à¦¨à¦Ÿà¦¿ à¦®à§à¦›à§‡ à¦«à§‡à¦²à¦¬à§‡à¦¨?') || event.stopImmediatePropagation()" @click="openDropdown = false" class="w-full text-left px-3 py-2 hover:bg-red-50 dark:hover:bg-red-950/20 text-red-650 hover:text-red-700 transition-all font-semibold rounded-xl cursor-pointer">
                                                à¦¡à¦¿à¦²à¦¿à¦Ÿ à¦•à¦°à§à¦¨
                                            </button>
                                        </div>
                                    </template>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" class="px-4 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <p class="text-xs text-gray-400 dark:text-gray-500 italic font-sans">à¦•à§‹à¦¨à§‹ à¦…à¦—à§à¦°à¦¿à¦® à¦šà¦¾à¦²à¦¾à¦¨ à¦¨à§‡à¦‡à¥¤</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <!-- Totals Footer Row -->
                    @if($challans->count() > 0)
                    <tfoot>
                        <tr class="bg-emerald-600/10 dark:bg-emerald-950/30 border-t-2 border-emerald-200 dark:border-emerald-900/50 text-xs font-bold font-sans">
                            <td colspan="4" class="px-3 py-3 text-emerald-700 dark:text-emerald-400 border-r border-gray-200 dark:border-slate-800 last:border-r-0">à¦®à§‹à¦Ÿ à¦šà¦¾à¦²à¦¾à¦¨ {{ $challans->count() }} à¦Ÿà¦¿</td>
                            <td class="px-3 py-3 text-right text-emerald-700 dark:text-emerald-400 border-r border-gray-200 dark:border-slate-800 last:border-r-0">
                                {{ number_format($challans->sum(fn($c) => $c->items->sum('quantity'))) }}
                            </td>
                            <td class="px-3 py-3 text-right border-r border-gray-200 dark:border-slate-800 last:border-r-0">â€”</td>
                            <td class="px-3 py-3 text-right text-emerald-700 dark:text-emerald-400 border-r border-gray-200 dark:border-slate-800 last:border-r-0">à§³{{ number_format($challans->sum('value'), 2) }}</td>
                            <td class="px-3 py-3 text-right text-emerald-700 dark:text-emerald-400 border-r border-gray-200 dark:border-slate-800 last:border-r-0">à§³{{ number_format($challans->sum('value'), 2) }}</td>
                            <td class="px-3 py-3 text-right text-amber-600 border-r border-gray-200 dark:border-slate-800 last:border-r-0">à§³{{ number_format($challans->sum('discount'), 2) }}</td>
                            <td class="px-3 py-3 text-right text-emerald-700 dark:text-emerald-400 border-r border-gray-200 dark:border-slate-800 last:border-r-0">à§³{{ number_format($challans->sum('transport_rent'), 2) }}</td>
                            <td class="px-3 py-3 text-right text-gray-800 dark:text-slate-200 border-r border-gray-200 dark:border-slate-800 last:border-r-0">à§³{{ number_format($challans->sum('grand_total'), 2) }}</td>
                            <td class="px-3 py-3 text-right text-emerald-700 dark:text-emerald-400 border-r border-gray-200 dark:border-slate-800 last:border-r-0">à§³{{ number_format($challans->sum('cash'), 2) }}</td>
                            <td class="px-3 py-3 text-right text-red-650 border-r border-gray-200 dark:border-slate-800 last:border-r-0">à§³{{ number_format($challans->sum('due'), 2) }}</td>
                            <td class="px-3 py-3"></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>

            <div class="p-4 border-t border-gray-100 dark:border-slate-800">
                {{ $challans->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Form (same modal contents) -->
    <template x-teleport="body">
        <div x-data="{ open: @entangle('showModal') }"
             x-show="open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click.self="open = false; $wire.closeModal()"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
             x-cloak>
            <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-2xl w-full border border-gray-100 dark:border-slate-800 shadow-2xl p-6 relative max-h-[92vh] overflow-y-auto"
                 x-show="open"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4">

                <button type="button" @click="open = false; $wire.closeModal()"
                        class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>

                <h3 class="text-base font-bold text-gray-800 dark:text-white mb-5 border-b border-gray-100 dark:border-slate-800 pb-2 font-sans">
                    à¦¨à¦¤à§à¦¨ à¦…à¦—à§à¦°à¦¿à¦® à¦šà¦¾à¦²à¦¾à¦¨
                </h3>

                <form wire:submit.prevent="save" class="space-y-4">
                    <!-- Tabs -->
                    <div class="flex flex-wrap items-center gap-3 mb-6">
                        <button type="button" @click="$wire.set('customer_type', 'new')"
                                class="px-4 py-2 text-xs font-bold rounded-xl transition-all cursor-pointer font-sans"
                                :class="$wire.customer_type === 'new' ? 'bg-emerald-600 text-white shadow-sm' : 'border border-emerald-500 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-950/20'">
                            à¦¨à¦¤à§à¦¨ à¦•à¦¾à¦¸à§à¦Ÿà¦®à¦¾à¦°
                        </button>
                        <button type="button" @click="$wire.set('customer_type', 'old')"
                                class="px-4 py-2 text-xs font-bold rounded-xl transition-all cursor-pointer font-sans"
                                :class="$wire.customer_type === 'old' ? 'bg-orange-500 text-white shadow-sm' : 'border border-orange-500 text-orange-500 hover:bg-orange-50 dark:hover:bg-orange-950/20'">
                            à¦ªà§à¦°à¦¾à¦¤à¦¨ à¦•à¦¾à¦¸à§à¦Ÿà¦®à¦¾à¦°
                        </button>
                        <div class="ml-auto flex items-center gap-2">
                            <div class="flex items-center gap-1.5 bg-gray-55 dark:bg-slate-950 border border-gray-200 dark:border-slate-850 rounded-xl px-3 py-1.5">
                                <span class="text-[10px] font-bold text-gray-500 dark:text-gray-400 font-sans">à¦šà¦¾à¦²à¦¾à¦¨ à¦¨à¦®à§à¦¬à¦°:</span>
                                <input type="text" wire:model="challan_no" class="w-12 bg-transparent text-xs font-bold text-gray-800 dark:text-white focus:outline-none border-none p-0">
                            </div>
                            <input type="date" wire:model="date" class="py-1.5 px-3 text-xs rounded-xl border border-gray-200 dark:border-slate-850 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all font-sans font-semibold">
                        </div>
                    </div>

                    <!-- Customer fields -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4" x-show="$wire.customer_type === 'new'">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">à¦«à§‹à¦¨ à¦¨à¦®à§à¦¬à¦° - à§¦</label>
                            <div class="relative">
                                <input type="text" wire:model="customer_phone" placeholder="à¦«à§‹à¦¨ à¦¨à¦®à§à¦¬à¦°" maxlength="11"
                                       class="w-full py-2.5 px-3 rounded-xl border border-gray-200 dark:border-slate-850 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all font-semibold">
                                <span class="absolute right-2 top-3 text-[10px] text-gray-400 font-sans" x-text="($wire.customer_phone || '').length + '/11'"></span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">à¦•à¦¾à¦¸à§à¦Ÿà¦®à¦¾à¦°à§‡à¦° à¦¨à¦¾à¦®</label>
                            <input type="text" wire:model="customer_name" placeholder="à¦•à¦¾à¦¸à§à¦Ÿà¦®à¦¾à¦°à§‡à¦° à¦¨à¦¾à¦®" class="w-full py-2.5 px-3 rounded-xl border border-gray-200 dark:border-slate-850 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all font-semibold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">à¦•à¦¾à¦¸à§à¦Ÿà¦®à¦¾à¦°à§‡à¦° à¦ à¦¿à¦•à¦¾à¦¨à¦¾</label>
                            <input type="text" wire:model="customer_address" placeholder="à¦•à¦¾à¦¸à§à¦Ÿà¦®à¦¾à¦°à§‡à¦° à¦ à¦¿à¦•à¦¾à¦¨à¦¾" class="w-full py-2.5 px-3 rounded-xl border border-gray-200 dark:border-slate-850 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all font-semibold">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" x-show="$wire.customer_type === 'old'" x-cloak>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">à¦ªà§à¦°à¦¾à¦¤à¦¨ à¦–à¦¤à¦¿à§Ÿà¦¾à¦¨ à¦—à§à¦°à¦¾à¦¹à¦•</label>
                            <select wire:model.live="ledger_id" class="w-full py-2.5 px-3 rounded-xl border border-gray-200 dark:border-slate-850 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all font-semibold">
                                <option value="">à¦—à§à¦°à¦¾à¦¹à¦• à¦¨à¦¿à¦°à§à¦¬à¦¾à¦šà¦¨ à¦•à¦°à§à¦¨...</option>
                                @foreach($ledgers as $ledger)
                                    <option value="{{ $ledger->id }}">{{ $ledger->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">à¦—à§à¦°à¦¾à¦¹à¦• à¦«à§‹à¦¨ à¦¨à¦®à§à¦¬à¦°</label>
                            <input type="text" wire:model="customer_phone" placeholder="à¦«à§‹à¦¨ à¦¨à¦®à§à¦¬à¦°" class="w-full py-2.5 px-3 rounded-xl border border-gray-200 dark:border-slate-850 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all font-semibold">
                        </div>
                    </div>

                    <!-- Row 2 -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">à¦šà¦¾à¦²à¦¾à¦¨à§‡à¦° à¦§à¦°à¦¨</label>
                            <select wire:model="challan_type" class="w-full py-2.5 px-3 rounded-xl border border-gray-200 dark:border-slate-850 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all font-semibold">
                                <option value="à¦…à¦—à§à¦°à¦¿à¦®">à¦…à¦—à§à¦°à¦¿à¦®</option>
                                <option value="à¦†à¦œà¦•à§‡à¦°">à¦†à¦œà¦•à§‡à¦°</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">à¦¡à§‡à¦²à¦¿à¦­à¦¾à¦°à¦¿ à¦¤à¦¾à¦°à¦¿à¦–</label>
                            <input type="date" wire:model="date" class="w-full py-2.5 px-3 rounded-xl border border-gray-200 dark:border-slate-850 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all font-sans font-semibold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">à¦¨à§‹à¦Ÿ</label>
                            <input type="text" wire:model="notes" placeholder="à¦¨à§‹à¦Ÿ..." class="w-full py-2.5 px-3 rounded-xl border border-gray-200 dark:border-slate-855 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all font-semibold">
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="mt-6 border border-gray-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-slate-950 border-b border-gray-200 dark:border-slate-800 text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase font-sans">
                                    <th class="px-3 py-2.5 text-center w-12 border-r border-gray-200 dark:border-slate-800">#</th>
                                    <th class="px-3 py-2.5 border-r border-gray-200 dark:border-slate-800">à¦¶à§à¦°à§‡à¦£à¦¿</th>
                                    <th class="px-3 py-2.5 text-right border-r border-gray-200 dark:border-slate-800 w-28">à¦°à§‡à¦Ÿ</th>
                                    <th class="px-3 py-2.5 text-right border-r border-gray-200 dark:border-slate-800 w-28">à¦ªà¦°à¦¿à¦®à¦¾à¦£</th>
                                    <th class="px-3 py-2.5 text-right border-r border-gray-200 dark:border-slate-800 w-32">à¦®à§‚à¦²à§à¦¯</th>
                                    <th class="px-3 py-2.5 text-center w-12">à¦®à§à¦›à§à¦¨</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-150 dark:divide-slate-800 font-sans">
                                @foreach($items as $index => $item)
                                    <tr class="text-xs">
                                        <td class="px-3 py-2.5 text-center border-r border-gray-150 dark:border-slate-850 font-bold">
                                            @if($loop->first)
                                                <button type="button" wire:click="addItem" class="w-6 h-6 rounded-lg bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 font-bold flex items-center justify-center cursor-pointer transition-all border border-emerald-200">+</button>
                                            @else
                                                {{ $loop->iteration }}
                                            @endif
                                        </td>
                                        
                                        <!-- Category select with Search, Add, Delete inside modal table row -->
                                        <td class="px-3 py-2.5 border-r border-gray-150 dark:border-slate-850 relative" x-data="{ openCat: false }">
                                            <button type="button" @click="openCat = !openCat"
                                                    class="w-full flex items-center justify-between py-1 px-2 rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs font-semibold text-gray-800 dark:text-white focus:outline-none cursor-pointer">
                                                <span x-text="$wire.items[{{ $index }}]['category_name'] || 'à¦¶à§à¦°à§‡à¦£à¦¿ à¦¨à¦¿à¦°à§à¦¬à¦¾à¦šà¦¨ à¦•à¦°à§à¦¨...'"></span>
                                                <svg class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': openCat }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                                </svg>
                                            </button>
                                            <div x-show="openCat" @click.away="openCat = false" x-transition
                                                 class="absolute left-0 w-64 mt-1 bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-gray-200 dark:border-slate-800 z-50 overflow-hidden text-left" x-cloak>
                                                <div class="p-2 border-b border-gray-100 dark:border-slate-800 flex gap-1.5 bg-gray-50 dark:bg-slate-950">
                                                    <input type="text" wire:model="newCategoryInput" placeholder="à¦«à¦¿à¦²à§à¦Ÿà¦¾à¦° à¦¬à¦¾ à¦¨à¦¤à§à¦¨ à¦¶à§à¦°à§‡à¦£à¦¿..."
                                                           class="flex-1 py-1 px-2 text-[10px] rounded-lg border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 font-sans"
                                                           @keydown.enter.prevent="$wire.addCategoryOption()">
                                                    <button type="button" wire:click="addCategoryOption"
                                                            class="px-2 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition-all cursor-pointer">
                                                        +
                                                    </button>
                                                </div>
                                                <div class="max-h-36 overflow-y-auto py-1">
                                                    @foreach($categories as $cat)
                                                        <div class="flex items-center justify-between px-3 py-1.5 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 transition-all text-xs"
                                                             x-show="$wire.newCategoryInput === '' || '{{ $cat->name }}'.toLowerCase().includes($wire.newCategoryInput.toLowerCase())">
                                                            <button type="button" @click="$wire.set('items.{{ $index }}.category_name', '{{ $cat->name }}'); $wire.updatedItems('{{ $cat->name }}', '{{ $index }}.category_name'); openCat = false; $wire.set('newCategoryInput', '')"
                                                                    class="flex-1 text-left font-semibold text-gray-800 dark:text-white hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-sans">
                                                                {{ $cat->name }}
                                                            </button>
                                                            <button type="button" wire:click="deleteCategoryOption({{ $cat->id }})"
                                                                    onclick="confirm('à¦à¦‡ à¦¶à§à¦°à§‡à¦£à¦¿à¦Ÿà¦¿ à¦®à§à¦›à¦¬à§‡à¦¨?') || event.stopImmediatePropagation()"
                                                                    class="ml-2 text-gray-400 hover:text-red-500 transition-all rounded cursor-pointer">
                                                                Ã—
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-3 py-2.5 border-r border-gray-150 dark:border-slate-850">
                                            <input type="number" step="0.01" wire:model.live="items.{{ $index }}.rate" placeholder="à§³ à§¦"
                                                   class="w-full py-1 px-2 rounded-lg border border-gray-250 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-right text-xs font-semibold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all text-gray-800 dark:text-white">
                                        </td>
                                        <td class="px-3 py-2.5 border-r border-gray-150 dark:border-slate-850">
                                            <input type="number" wire:model.live="items.{{ $index }}.quantity" placeholder="à§¦"
                                                   class="w-full py-1 px-2 rounded-lg border border-gray-250 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-right text-xs font-semibold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all text-gray-800 dark:text-white">
                                        </td>
                                        <td class="px-3 py-2.5 border-r border-gray-150 dark:border-slate-850 text-right text-gray-500 font-bold select-none">
                                            à§³{{ number_format(floatval($item['amount'] ?? 0), 2) }}
                                        </td>
                                        <td class="px-3 py-2.5 text-center">
                                            @if(!$loop->first)
                                                <button type="button" wire:click="removeItem({{ $index }})" class="text-red-500 hover:text-red-700 transition-all cursor-pointer">ðŸ—‘ï¸</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Split calculations details grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 items-end">
                        <div class="flex flex-col items-center justify-center p-4 bg-gray-50 dark:bg-slate-950 border border-gray-200 dark:border-slate-800 rounded-3xl gap-4">
                            <div class="flex items-center gap-2 py-2 px-4 bg-red-100 dark:bg-red-950/40 text-red-600 dark:text-red-400 font-bold rounded-2xl text-sm tracking-wider uppercase">DEMO</div>
                            <div class="flex items-center justify-between w-full border-t border-gray-200 dark:border-slate-850 pt-3">
                                <span class="text-xs font-bold text-gray-700 dark:text-slate-300 font-sans">à¦•à¦¾à¦¸à§à¦Ÿà¦®à¦¾à¦°à¦•à§‡ à¦à¦¸à¦à¦®à¦à¦¸ à¦¦à¦¿à¦¨</span>
                                <button type="button" @click="$wire.send_sms = !$wire.send_sms" class="relative flex-shrink-0 focus:outline-none cursor-pointer w-11 h-6">
                                    <div class="w-11 h-6 rounded-full transition-colors duration-300 absolute inset-0" :class="$wire.send_sms ? 'bg-emerald-600' : 'bg-slate-200 dark:bg-slate-700'"></div>
                                    <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow-md transition-transform duration-300" :class="$wire.send_sms ? 'translate-x-5' : 'translate-x-0'"></div>
                                </button>
                            </div>
                        </div>

                        <div class="space-y-3 font-sans">
                            <div class="grid grid-cols-2 items-center gap-2">
                                <span class="text-xs font-bold text-gray-600 dark:text-gray-400">à¦®à§‚à¦²à§à¦¯:</span>
                                <div class="py-2 px-3 text-xs bg-gray-50 dark:bg-slate-950 border border-gray-200 dark:border-slate-800 rounded-xl text-right text-gray-800 dark:text-white font-bold select-none">à§³{{ number_format($value, 2) }}</div>
                            </div>
                            <div class="grid grid-cols-2 items-center gap-2">
                                <span class="text-xs font-bold text-gray-600 dark:text-gray-400">à¦—à¦¾à§œà¦¿ à¦­à¦¾à§œà¦¾:</span>
                                <input type="number" wire:model.live="transport_rent" class="py-2 px-3 text-xs bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl text-right text-gray-850 dark:text-white font-bold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all">
                            </div>
                            <div class="grid grid-cols-2 items-center gap-2">
                                <span class="text-xs font-bold text-gray-600 dark:text-gray-400">à¦›à¦¾à§œ:</span>
                                <input type="number" wire:model.live="discount" class="py-2 px-3 text-xs bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl text-right text-gray-850 dark:text-white font-bold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all">
                            </div>
                            <div class="grid grid-cols-2 items-center gap-2">
                                <span class="text-xs font-bold text-gray-600 dark:text-gray-400">à¦®à§‹à¦Ÿ:</span>
                                <div class="py-2 px-3 text-xs bg-gray-50 dark:bg-slate-950 border border-gray-200 dark:border-slate-800 rounded-xl text-right text-emerald-700 dark:text-emerald-400 font-bold select-none">à§³{{ number_format($grand_total, 2) }}</div>
                            </div>
                            <div class="grid grid-cols-2 items-center gap-2">
                                <span class="text-xs font-bold text-gray-600 dark:text-gray-400">à¦¨à¦—à¦¦:</span>
                                <input type="number" wire:model.live="cash" class="py-2 px-3 text-xs bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl text-right text-emerald-600 dark:text-emerald-400 font-bold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all">
                            </div>
                            <div class="grid grid-cols-2 items-center gap-2">
                                <span class="text-xs font-bold text-gray-600 dark:text-gray-400">à¦¬à¦¾à¦•à¦¿:</span>
                                <div class="py-2 px-3 text-xs bg-gray-50 dark:bg-slate-950 border border-gray-200 dark:border-slate-800 rounded-xl text-right text-red-600 dark:text-red-400 font-bold select-none">à§³{{ number_format($due, 2) }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer actions -->
                    <div class="flex items-center justify-end gap-2.5 pt-5 border-t border-gray-200 dark:border-slate-850 mt-6">
                        <button type="button" wire:click="resetForm" class="px-5 py-2 text-xs font-semibold text-gray-500 hover:text-gray-800 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-xl cursor-pointer transition-all font-sans">à¦•à§à¦²à¦¿à§Ÿà¦¾à¦°</button>
                        <button type="submit" class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl cursor-pointer transition-all shadow-md active:scale-95 font-sans">à¦¸à§‡à¦­ à¦•à¦°à§à¦¨</button>
                        <button type="button" wire:click="save" class="px-6 py-2 bg-primary hover:bg-emerald-700 text-white text-xs font-bold rounded-xl cursor-pointer transition-all shadow-md active:scale-95 font-sans">à¦¸à§‡à¦­ + à¦ªà§à¦°à¦¿à¦¨à§à¦Ÿ</button>
                    </div>
                </form>
            </div>
        </div>
    </template>

</div>

