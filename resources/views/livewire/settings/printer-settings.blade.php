<div class="space-y-6">
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

    <div class="max-w-xl bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-3xl p-6 shadow-sm transition-colors duration-300">
        <div class="flex items-center gap-3 border-b border-gray-100 dark:border-slate-800 pb-4 mb-5">
            <h3 class="font-bold text-sm text-gray-800 dark:text-white">প্রিন্টার কনফিগারেশন</h3>
        </div>

        <form wire:submit.prevent="save" class="space-y-5">
            
            <!-- Printer Type Dropdown -->
            <div class="relative" x-data="{ open: false, type: @entangle('printer_type') }">
                <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 mb-1.5 uppercase">প্রিন্ট লেআউট ধরণ</label>
                <button type="button" @click="open = !open"
                        class="w-full flex items-center justify-between py-2.5 px-4 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none transition-all font-semibold text-left">
                    <span x-text="type === 'thermal' ? 'মোবাইল সেটিংস (Thermal POS)' : 'ডেস্কটপ সেটিংস (A4 Size Paper)'"></span>
                    <svg class="w-4 h-4 text-emerald-700 dark:text-emerald-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" @click.away="open = false" x-transition class="absolute left-0 right-0 mt-1.5 bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-gray-100 dark:border-slate-800 py-1 z-55 text-xs overflow-hidden" x-cloak>
                    <button type="button" @click="type = 'thermal'; open = false" class="w-full text-left px-4 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold">মোবাইল সেটিংস (Thermal POS)</button>
                    <button type="button" @click="type = 'desktop'; open = false" class="w-full text-left px-4 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold">ডেস্কটপ সেটিংস (A4 Size Paper)</button>
                </div>
                @error('printer_type') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Print Copy Count -->
            <div>
                <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 mb-1.5 uppercase">প্রিন্ট কপি সংখ্যা (কাস্টমার + অফিস কপি)</label>
                <input type="number" wire:model="print_copies" placeholder="যেমন: ২"
                       class="w-full py-2.5 px-4 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-250/20 dark:focus:ring-emerald-950/30 transition-all font-semibold">
                @error('print_copies') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Thermal padding settings (toggled visual state) -->
            <div class="p-4 bg-gray-50 dark:bg-slate-950/40 border border-gray-200 dark:border-slate-800 rounded-2xl space-y-4"
                 :class="{ 'opacity-50 pointer-events-none': printer_type !== 'thermal' }">
                <span class="block text-xs font-black text-emerald-800 dark:text-emerald-400 uppercase">থার্মাল POS প্যাডিং সেটিংস</span>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 dark:text-gray-500 mb-1 uppercase">টপ প্যাডিং (mm)</label>
                        <input type="number" wire:model="thermal_padding_top" placeholder="যেমন: ১০"
                               class="w-full py-2.5 px-4 rounded-xl border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-250/20 dark:focus:ring-emerald-950/30 transition-all font-semibold">
                        @error('thermal_padding_top') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 dark:text-gray-500 mb-1 uppercase">বটম প্যাডিং (mm)</label>
                        <input type="number" wire:model="thermal_padding_bottom" placeholder="যেমন: ১৫"
                               class="w-full py-2.5 px-4 rounded-xl border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-250/20 dark:focus:ring-emerald-950/30 transition-all font-semibold">
                        @error('thermal_padding_bottom') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-relaxed">
                    * থার্মাল রোল থেকে রসিদ কাটার সময় লেখা যেন কেটে না যায়, সে জন্য উপরে ও নিচে মার্জিন বা প্যাডিং (মিলিমিটার এককে) কনফিগার করুন।
                </p>
            </div>

            <!-- Submit -->
            <div class="flex">
                <button type="submit"
                        class="px-8 py-2.5 bg-primary hover:bg-primary-light text-white text-xs font-bold rounded-xl transition-all shadow-md">
                    প্রিন্টার সেটিংস সংরক্ষণ করুন
                </button>
            </div>
        </form>
    </div>
</div>
