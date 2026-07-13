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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left: Add / Edit Form -->
        <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-3xl p-6 shadow-sm transition-colors duration-300">
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-800 pb-4 mb-6">
                <h3 class="font-bold text-sm text-gray-800 dark:text-white">
                    {{ $editingCategoryId ? 'শ্রেণি সংশোধন' : 'নতুন শ্রেণি যুক্ত করুন' }}
                </h3>
                @if($editingCategoryId)
                    <button type="button" wire:click="cancelEdit"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors focus:outline-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                @endif
            </div>

            <form wire:submit.prevent="save" class="space-y-5">
                <!-- Category Name -->
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 dark:text-gray-500 mb-1.5 uppercase tracking-wide">শ্রেণির নাম</label>
                    <input type="text" wire:model="name" placeholder="যেমন: ১ নম্বর পিকেট"
                           class="w-full py-3 px-4 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all font-semibold">
                    @error('name') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Type Dropdown -->
                <div class="relative" x-data="{ open: false, type: @entangle('type') }">
                    <label class="block text-[11px] font-bold text-gray-400 dark:text-gray-500 mb-1.5 uppercase tracking-wide">শ্রেণির ধরন</label>
                    <button type="button" @click="open = !open"
                            class="w-full flex items-center justify-between py-3 px-4 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs font-semibold text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/10 cursor-pointer text-left transition-all">
                        <span x-text="type === 'ইট' ? 'ইট' : (type === 'আধলা' ? 'আধলা' : 'অন্যান্য')"></span>
                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition
                         class="absolute left-0 right-0 mt-1.5 bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-gray-100 dark:border-slate-800 py-1 z-50 text-xs overflow-hidden" x-cloak>
                        <button type="button" @click="type = 'ইট'; open = false" class="w-full text-left px-4 py-2.5 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold">ইট</button>
                        <button type="button" @click="type = 'আধলা'; open = false" class="w-full text-left px-4 py-2.5 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold">আধলা</button>
                        <button type="button" @click="type = 'অন্যান্য'; open = false" class="w-full text-left px-4 py-2.5 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold">অন্যান্য</button>
                    </div>
                    @error('type') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Rate -->
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 dark:text-gray-500 mb-1.5 uppercase tracking-wide">রেট (প্রতি হাজার টাকা)</label>
                    <input type="number" step="0.01" wire:model="rate" placeholder="যেমন: 9500"
                           class="w-full py-3 px-4 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all font-semibold">
                    @error('rate') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Buttons -->
                <div class="flex items-center gap-2 pt-2">
                    <button type="submit"
                            class="px-5 py-2.5 bg-primary hover:bg-primary-light text-white text-xs font-bold rounded-xl transition-all shadow-md cursor-pointer">
                        {{ $editingCategoryId ? 'আপডেট করুন' : 'সংরক্ষণ করুন' }}
                    </button>
                    @if($editingCategoryId)
                        <button type="button" wire:click="cancelEdit"
                                class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-700/80 text-gray-700 dark:text-slate-300 text-xs font-bold rounded-xl transition-all cursor-pointer">
                            বাতিল
                        </button>
                    @endif
                </div>
            </form>
        </div>

        <!-- Right: Category List Table -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-3xl p-6 shadow-sm transition-colors duration-300">
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-800 pb-4 mb-6">
                <h3 class="font-bold text-sm text-gray-800 dark:text-white">শ্রেণি তালিকা</h3>
                <span class="px-2.5 py-0.5 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-100 dark:border-emerald-900 text-emerald-700 dark:text-emerald-400 font-bold rounded-full text-[10px]">
                    মোট: {{ count($categories) }} টি
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-slate-950 border-b border-gray-100 dark:border-slate-800 text-gray-500 dark:text-slate-400 font-bold">
                            <th class="px-4 py-3">শ্রেণির নাম</th>
                            <th class="px-4 py-3 text-center">ধরন</th>
                            <th class="px-4 py-3 text-right">রেট (হাজারে)</th>
                            <th class="px-4 py-3 text-right">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800/80">
                        @forelse($categories as $category)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/25 transition-colors
                                       {{ $editingCategoryId == $category->id ? 'bg-emerald-50/40 dark:bg-emerald-950/10 ring-1 ring-emerald-200 dark:ring-emerald-900 rounded-xl' : '' }}">
                                <td class="px-4 py-3 font-semibold text-gray-800 dark:text-slate-200">
                                    {{ $category->name }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold
                                        {{ $category->type === 'ইট' ? 'bg-orange-50 text-orange-700 dark:bg-orange-950/20 dark:text-orange-400' : ($category->type === 'আধলা' ? 'bg-sky-50 text-sky-700 dark:bg-sky-950/20 dark:text-sky-400' : 'bg-gray-100 text-gray-600 dark:bg-slate-800 dark:text-slate-300') }}">
                                        {{ $category->type }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right font-bold text-gray-900 dark:text-white">
                                    ৳ {{ number_format($category->rate, 2) }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button wire:click="editCategory({{ $category->id }})"
                                                class="p-1.5 border border-gray-200 dark:border-slate-700 hover:border-emerald-500 text-gray-500 hover:text-emerald-600 dark:hover:text-emerald-400 rounded-lg transition-all cursor-pointer"
                                                title="এডিট">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"/>
                                            </svg>
                                        </button>
                                        <button wire:click="deleteCategory({{ $category->id }})"
                                                onclick="confirm('শ্রেণিটি মুছে ফেলবেন?') || event.stopImmediatePropagation()"
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
                                <td colspan="4" class="px-4 py-8 text-center text-gray-400 dark:text-gray-500 italic">
                                    কোনো শ্রেণি যুক্ত করা হয়নি।
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
