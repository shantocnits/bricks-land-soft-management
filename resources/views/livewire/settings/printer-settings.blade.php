<div class="space-y-6">
    <!-- Header banner card matching the design of the screenshot -->
    <div class="flex items-center gap-4 pb-4 border-b border-gray-100 dark:border-slate-800">
        <div class="flex-shrink-0 p-3 bg-emerald-100 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 rounded-2xl flex items-center justify-center shadow-sm">
            <!-- Printer Icon -->
            <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0a2.25 2.25 0 01-2.25 2.25H8.59a2.25 2.25 0 01-2.25-2.25M17.66 18l.128-1.28A2.25 2.25 0 0015.542 14.5H8.458a2.25 2.25 0 00-2.248 2.22L6.34 18M12 9V3m0 0L8.25 6M12 3l3.75 3"/>
            </svg>
        </div>
        <div>
            <h2 class="font-bold text-gray-800 dark:text-white text-lg font-sans">প্রিন্টার কনফিগারেশন</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 font-sans">চালান প্রিন্ট করার ডিফল্ট সেটিংস এখান থেকে পরিবর্তন করুন</p>
        </div>
    </div>

    <!-- Success Alert -->
    @if (session()->has('message'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 3000)"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="p-4 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-900 text-emerald-800 dark:text-emerald-400 rounded-2xl flex items-center gap-3 text-sm shadow-sm font-sans"
             x-cloak>
            <span class="font-medium">{{ session('message') }}</span>
        </div>
    @endif

    <form wire:submit.prevent="save" class="space-y-6">
        
        <!-- Mobile & Desktop Settings Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Left Card: Mobile Settings -->
            <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800/80 rounded-2xl p-5 shadow-sm space-y-4">
                <div class="flex items-center gap-2 text-emerald-700 dark:text-emerald-400">
                    <!-- Mobile Icon -->
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/>
                    </svg>
                    <span class="text-xs font-bold font-sans">মোবাইল সেটিংস</span>
                </div>
                
                <div class="relative" x-data="{ open: false, type: @entangle('printer_type') }">
                    <label class="block text-[10px] font-bold text-gray-400 dark:text-gray-500 mb-1 uppercase font-sans">ডিফল্ট ফরম্যাট</label>
                    <button type="button" @click="open = !open"
                            class="w-full flex items-center justify-between py-2 px-3.5 rounded-xl border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-xs font-semibold text-gray-800 dark:text-white focus:outline-none transition-all cursor-pointer text-left shadow-sm">
                        <span x-text="type === 'thermal' ? 'Thermal (POS)' : 'A4 Size Paper'"></span>
                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition class="absolute left-0 right-0 mt-1 bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-gray-150 dark:border-slate-800 py-1 z-55 text-xs flex flex-col" x-cloak>
                        <button type="button" @click="type = 'thermal'; open = false" class="w-full text-left px-4 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-lg cursor-pointer">Thermal (POS)</button>
                        <button type="button" @click="type = 'desktop'; open = false" class="w-full text-left px-4 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-lg cursor-pointer">A4 Size Paper</button>
                    </div>
                </div>
            </div>

            <!-- Right Card: Desktop Settings -->
            <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800/80 rounded-2xl p-5 shadow-sm space-y-4">
                <div class="flex items-center gap-2 text-emerald-700 dark:text-emerald-400">
                    <!-- Desktop Icon -->
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25"/>
                    </svg>
                    <span class="text-xs font-bold font-sans">ডেস্কটপ সেটিংস</span>
                </div>
                
                <div class="relative" x-data="{ open: false, type: @entangle('printer_type') }">
                    <label class="block text-[10px] font-bold text-gray-400 dark:text-gray-500 mb-1 uppercase font-sans">ডিফল্ট ফরম্যাট</label>
                    <button type="button" @click="open = !open"
                            class="w-full flex items-center justify-between py-2 px-3.5 rounded-xl border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-xs font-semibold text-gray-800 dark:text-white focus:outline-none transition-all cursor-pointer text-left shadow-sm">
                        <span x-text="type === 'desktop' ? 'A4 Size Paper' : 'Thermal (POS)'"></span>
                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition class="absolute left-0 right-0 mt-1 bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-gray-150 dark:border-slate-800 py-1 z-55 text-xs flex flex-col" x-cloak>
                        <button type="button" @click="type = 'desktop'; open = false" class="w-full text-left px-4 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-lg cursor-pointer">A4 Size Paper</button>
                        <button type="button" @click="type = 'thermal'; open = false" class="w-full text-left px-4 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-lg cursor-pointer">Thermal (POS)</button>
                    </div>
                </div>
            </div>

        </div>

        <!-- Middle Card: Print Copy count selection -->
        <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800/80 rounded-2xl p-5 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-start gap-3">
                <div class="text-orange-500 p-1">
                    <!-- Document Orange Icon -->
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800 dark:text-white text-xs font-sans">প্রিন্ট কপির সংখ্যা</h4>
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 font-sans mt-0.5">চালান প্রিন্ট করার সময় কয়টি কপি বের হবে</p>
                </div>
            </div>

            <div class="relative w-full md:w-64" x-data="{ open: false, count: @entangle('print_copies') }">
                <button type="button" @click="open = !open"
                        class="w-full flex items-center justify-between py-2.5 px-3.5 rounded-xl border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-xs font-semibold text-gray-800 dark:text-white focus:outline-none transition-all cursor-pointer text-left shadow-sm">
                    <span x-text="count == 1 ? 'শুধু কাস্টমার কপি' : (count == 2 ? 'কাস্টমার + অফিস কপি' : count + ' কপি')"></span>
                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" @click.away="open = false" x-transition class="absolute left-0 right-0 mt-1 bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-gray-150 dark:border-slate-800 py-1 z-55 text-xs flex flex-col" x-cloak>
                    <button type="button" @click="count = 1; open = false" class="w-full text-left px-4 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-lg cursor-pointer">শুধু কাস্টমার কপি</button>
                    <button type="button" @click="count = 2; open = false" class="w-full text-left px-4 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-lg cursor-pointer">কাস্টমার + অফিস কপি</button>
                    <button type="button" @click="count = 3; open = false" class="w-full text-left px-4 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-lg cursor-pointer">৩ কপি</button>
                </div>
            </div>
        </div>

        <!-- Bottom Card: Padding Settings -->
        <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800/80 rounded-2xl p-5 shadow-sm space-y-4">
            <div class="flex items-center gap-2 text-purple-700 dark:text-purple-400">
                <!-- Settings/Scissors Icon -->
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"/>
                </svg>
                <span class="text-xs font-black font-sans">থার্মাল প্যাডিং সেটিংস (mm)</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 dark:text-gray-500 mb-1 uppercase font-sans">টপ প্যাডিং (উপরে)</label>
                    <input type="number" wire:model="thermal_padding_top" placeholder="১০"
                           class="w-full py-2.5 px-4 rounded-xl border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-250/20 transition-all font-semibold shadow-inner">
                    @error('thermal_padding_top') <span class="text-red-500 text-[10px] mt-1 block font-sans">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 dark:text-gray-500 mb-1 uppercase font-sans">বটম প্যাডিং (নিচে)</label>
                    <input type="number" wire:model="thermal_padding_bottom" placeholder="০"
                           class="w-full py-2.5 px-4 rounded-xl border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-250/20 transition-all font-semibold shadow-inner">
                    @error('thermal_padding_bottom') <span class="text-red-500 text-[10px] mt-1 block font-sans">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Submit Button: Full-width green button -->
        <button type="submit"
                class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-all shadow-md cursor-pointer hover:scale-[1.005] active:scale-[0.995] font-sans flex items-center justify-center gap-2">
            <!-- Save Icon -->
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0z"/>
            </svg>
            সব সেটিংস আপডেট করুন
        </button>

    </form>
</div>
