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

    <!-- Page Header Bar -->
    <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 p-5 rounded-2xl flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 transition-colors duration-300 shadow-sm">
        <div class="flex items-center gap-3">
            <!-- List Icon -->
            <div class="w-10 h-10 rounded-xl bg-emerald-600 dark:bg-emerald-950/20 text-white dark:text-emerald-400 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16M3 6h.01M3 12h.01M3 18h.01"/>
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-gray-800 dark:text-white text-base leading-tight">শ্রেণি এবং রেট পরিবর্তন</h2>
                <p class="text-[10px] text-gray-400 dark:text-gray-500 font-sans mt-0.5 font-semibold">শ্রেণি এবং রেট পরিবর্তন করুন</p>
            </div>
        </div>
        <div>
            <button wire:click="openModal"
                    class="flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-all shadow-md active:scale-95 cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                নতুন শ্রেণি
            </button>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-3xl overflow-hidden shadow-sm transition-colors duration-300">
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-emerald-600 text-white border-b border-gray-100 dark:border-slate-800 font-bold font-sans">
                        <th class="px-4 py-3.5 text-center w-12 rounded-tl-2xl">#</th>
                        <th class="px-4 py-3.5">শ্রেণির নাম</th>
                        <th class="px-4 py-3.5 text-center">শ্রেণির ধরণ</th>
                        <th class="px-4 py-3.5 text-right">রেট</th>
                        <th class="px-4 py-3.5 text-center w-28 rounded-tr-2xl">বাটন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800/80 font-sans">
                    @forelse($categories as $category)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/25 transition-colors
                                   {{ $editingCategoryId == $category->id ? 'bg-emerald-50/40 dark:bg-emerald-950/10 ring-1 ring-emerald-200 dark:ring-emerald-900' : '' }}">
                            <td class="px-4 py-3.5 text-center text-gray-500 dark:text-slate-400 font-semibold border-r border-gray-100 dark:border-slate-800/50 w-12">
                                {{ $loop->iteration }}
                            </td>
                            <td class="px-4 py-3.5 font-semibold text-gray-800 dark:text-slate-200">
                                {{ $category->name }}
                            </td>
                            <td class="px-4 py-3.5 text-center text-gray-600 dark:text-slate-400 font-medium">
                                {{ $category->type }}
                            </td>
                            <td class="px-4 py-3.5 text-right font-bold text-gray-900 dark:text-white">
                                ৳ {{ floatval($category->rate) }}
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="flex items-center justify-center gap-2">
                                    <button wire:click="editCategory({{ $category->id }})"
                                            class="p-1.5 border border-gray-200 dark:border-slate-700 hover:border-emerald-500 text-emerald-600 dark:text-emerald-400 rounded-lg transition-all cursor-pointer"
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
                            <td colspan="5" class="px-4 py-8 text-center text-gray-400 dark:text-gray-500 italic">
                                কোনো শ্রেণি যুক্ত করা হয়নি।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        <div class="px-5 py-4 border-t border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900 flex items-center justify-between text-xs">
            <div class="text-gray-500 dark:text-gray-400 font-sans font-semibold">
                মোট শ্রেণি {{ count($categories) }} টি
            </div>
            <div class="flex items-center gap-1 font-sans">
                <button type="button" class="w-6 h-6 flex items-center justify-center rounded-lg border border-gray-200 dark:border-slate-800 text-gray-400 hover:bg-gray-50 dark:hover:bg-slate-800 cursor-pointer">&lt;</button>
                <button type="button" class="w-6 h-6 flex items-center justify-center rounded-lg bg-emerald-600 text-white font-bold cursor-pointer shadow-sm">১</button>
                <button type="button" class="w-6 h-6 flex items-center justify-center rounded-lg border border-gray-200 dark:border-slate-800 text-gray-400 hover:bg-gray-50 dark:hover:bg-slate-800 cursor-pointer">&gt;</button>
            </div>
            <div class="flex items-center gap-1.5 border border-gray-200 dark:border-slate-800 rounded-xl px-3 py-1 bg-white dark:bg-slate-900 shadow-sm text-gray-500 dark:text-gray-400 font-sans font-semibold">
                <span>{{ count($categories) }} শ্রেণি / পেজ</span>
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
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
                    {{ $editingCategoryId ? 'শ্রেণি সংশোধন করুন' : 'নতুন শ্রেণি যুক্ত করুন' }}
                </h3>

                <form wire:submit.prevent="save" class="space-y-4">
                    <!-- Category Name -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">শ্রেণির নাম</label>
                        <input type="text" wire:model="name" placeholder="যেমন: ১ নম্বর পিকেট"
                               class="w-full py-2.5 px-3 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all font-semibold">
                        @error('name') <span class="text-red-500 text-[10px] mt-1 block font-sans">{{ $message }}</span> @enderror
                    </div>

                    <!-- Type Dropdown: Dynamic with filter/add/delete -->
                    <div class="relative" x-data="{ open3: false, typeVal: @entangle('type') }">
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">শ্রেণির ধরন</label>
                        <button type="button" @click="open3 = !open3"
                                class="w-full flex items-center justify-between py-2.5 px-3 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs font-semibold text-gray-800 dark:text-white focus:outline-none cursor-pointer text-left transition-all">
                            <span x-text="typeVal || 'ধরন নির্বাচন করুন'"></span>
                            <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open3 }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open3" @click.away="open3 = false" x-transition
                             class="absolute left-0 right-0 mt-1.5 bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-gray-100 dark:border-slate-800 z-50 overflow-hidden" x-cloak>
                            <!-- Single Filter + Add Input -->
                            <div class="p-2 border-b border-gray-100 dark:border-slate-800 flex gap-2">
                                <input type="text" wire:model="newTypeInput"
                                       placeholder="ফিল্টার বা নতুন ধরন..."
                                       class="flex-1 py-1.5 px-3 rounded-lg border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 transition-all font-sans"
                                       wire:keydown.enter.prevent="addType">
                                <button type="button" wire:click="addType"
                                        class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg transition-all cursor-pointer flex-shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                    </svg>
                                </button>
                            </div>
                            <!-- Options filtered by newTypeInput -->
                            <div class="max-h-40 overflow-y-auto py-1">
                                @foreach($typeOptions as $opt)
                                    <div class="flex items-center justify-between px-3 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 transition-all"
                                         x-show="$wire.newTypeInput === '' || '{{ $opt }}'.toLowerCase().includes($wire.newTypeInput.toLowerCase())">
                                        <button type="button" @click="typeVal = '{{ $opt }}'; open3 = false; $wire.set('newTypeInput', '')"
                                                class="flex-1 text-left text-xs font-semibold text-gray-800 dark:text-white hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-sans">
                                            {{ $opt }}
                                        </button>
                                        <button type="button" wire:click="deleteType('{{ $opt }}')"
                                                onclick="confirm('এই ধরনটি মুছবেন?') || event.stopImmediatePropagation()"
                                                class="ml-2 p-1 text-gray-400 hover:text-red-500 transition-all rounded-lg cursor-pointer flex-shrink-0">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @error('type') <span class="text-red-500 text-[10px] mt-1 block font-sans">{{ $message }}</span> @enderror
                    </div>

                    <!-- Rate -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">রেট (প্রতি হাজার টাকা)</label>
                        <input type="number" step="0.01" wire:model="rate" placeholder="যেমন: 9500"
                               class="w-full py-2.5 px-3 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all font-semibold">
                        @error('rate') <span class="text-red-500 text-[10px] mt-1 block font-sans">{{ $message }}</span> @enderror
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
