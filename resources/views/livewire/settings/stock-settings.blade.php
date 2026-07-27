<div class="space-y-6">
    <!-- Header banner card -->
    <div class="flex items-start gap-4 p-4 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/50 rounded-2xl">
        <div class="flex-shrink-0 p-2 bg-emerald-600 text-white rounded-xl flex items-center justify-center">
            <!-- Layers Icon -->
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6.878V6a2.25 2.25 0 012.25-2.25h7.5A2.25 2.25 0 0118 6v.878m-12 0c.235-.083.487-.128.75-.128h10.5c.263 0 .515.045.75.128m-12 0A2.25 2.25 0 004.5 9v.878m13.5-3c.235-.083.487-.128.75-.128h10.5c.263 0 .515.045.75.128m0 0A2.25 2.25 0 0119.5 9v.878m-15 0a2.25 2.25 0 00-2.25 2.25v6.75A2.25 2.25 0 004.5 21h15a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25m-15 0c.235-.083.487-.128.75-.128h10.5c.263 0 .515.045.75.128"/>
            </svg>
        </div>
        <div>
            <h4 class="font-bold text-gray-800 dark:text-white text-sm">স্টক সেটিংস</h4>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">ভাটার ইটের সামগ্রিক স্টক ট্র্যাকিং অথবা খামাল ও শ্রেণি ভিত্তিক পৃথক স্টক পদ্ধতি বেছে নিন।</p>
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

    <div class="bg-white dark:bg-slate-900 border border-gray-155 dark:border-slate-800 rounded-3xl p-6 shadow-sm transition-colors duration-300">
        <div class="flex items-center gap-3 border-b border-gray-150 dark:border-slate-800 pb-4 mb-6">
            <h3 class="font-bold text-sm text-gray-800 dark:text-white">স্টক সিস্টেম কনফিগারেশন</h3>
        </div>

        <!-- Danger/Warning Alert -->
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-900/60 text-red-800 dark:text-red-400 text-xs rounded-2xl flex gap-3 font-sans leading-relaxed">
            <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div>
                <span class="font-black">ঝুঁকি সতর্কতা:</span> স্টক সিস্টেম পরিবর্তন করলে সফটওয়্যারের চালানের হিসাব ও স্টক ক্যালকুলেশন মেকানিজম পরিবর্তিত হয়। রানিং প্রোডাকশন ডেটা সহ চলমান ভাটার ক্ষেত্রে স্টক সিস্টেম পরিবর্তনের পূর্বে ডেটা ব্যাকআপ নেওয়া এবং সিস্টেম এডমিনের পরামর্শ নেওয়া আবশ্যক।
            </div>
        </div>

        <form wire:submit.prevent="save" class="space-y-6">
            
            <!-- Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4" x-data="{ selectedSystem: @entangle('stock_system') }">
                
                <!-- Card 1: Total Stock -->
                <div @click="selectedSystem = 'total_stock'"
                     :class="selectedSystem === 'total_stock' ? 'border-primary dark:border-emerald-500 ring-2 ring-emerald-250/25 bg-emerald-50/10 dark:bg-emerald-950/5' : 'border-gray-200 dark:border-slate-800 hover:border-emerald-300 dark:hover:border-slate-700'"
                     class="cursor-pointer border-2 p-5 rounded-2xl flex flex-col gap-3 transition-all relative overflow-hidden select-none">
                    
                    <div class="flex items-center justify-between">
                        <div></div>
                        <!-- Radio dot -->
                        <div :class="selectedSystem === 'total_stock' ? 'bg-primary dark:bg-emerald-500' : 'bg-gray-200 dark:bg-slate-800'" class="w-4 h-4 rounded-full flex items-center justify-center">
                            <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-bold text-sm text-gray-800 dark:text-white">মোট স্টক</h4>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1 leading-relaxed">
                            কোন শ্রেণি বা খামাল বিভাজন ছাড়া সামগ্রিকভাবে ইটের মোট স্টক হিসাব করা হয়।
                        </p>
                    </div>
                </div>

                <!-- Card 2: Category Stock -->
                <div @click="selectedSystem = 'category_stock'"
                     :class="selectedSystem === 'category_stock' ? 'border-primary dark:border-emerald-500 ring-2 ring-emerald-250/25 bg-emerald-50/10 dark:bg-emerald-950/5' : 'border-gray-200 dark:border-slate-800 hover:border-emerald-300 dark:hover:border-slate-700'"
                     class="cursor-pointer border-2 p-5 rounded-2xl flex flex-col gap-3 transition-all relative overflow-hidden select-none">
                    
                    <div class="flex items-center justify-between">
                        <div></div>
                        <div :class="selectedSystem === 'category_stock' ? 'bg-primary dark:bg-emerald-500' : 'bg-gray-200 dark:bg-slate-800'" class="w-4 h-4 rounded-full flex items-center justify-center">
                            <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-bold text-sm text-gray-800 dark:text-white">শ্রেণি অনুযায়ী স্টক</h4>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1 leading-relaxed">
                            ১ম শ্রেণি, ২য় শ্রেণি, আধলা ইত্যাদি ইট ক্যাটাগরি অনুসারে পৃথক উৎপাদন ও স্টক ট্র্যাক করা হয়।
                        </p>
                    </div>
                </div>

                <!-- Card 3: Category + Khamal Stock -->
                <div @click="selectedSystem = 'category_khamal_stock'"
                     :class="selectedSystem === 'category_khamal_stock' ? 'border-primary dark:border-emerald-500 ring-2 ring-emerald-250/25 bg-emerald-50/10 dark:bg-emerald-950/5' : 'border-gray-200 dark:border-slate-800 hover:border-emerald-300 dark:hover:border-slate-700'"
                     class="cursor-pointer border-2 p-5 rounded-2xl flex flex-col gap-3 transition-all relative overflow-hidden select-none">
                    
                    <div class="flex items-center justify-between">
                        <div></div>
                        <div :class="selectedSystem === 'category_khamal_stock' ? 'bg-primary dark:bg-emerald-500' : 'bg-gray-200 dark:bg-slate-800'" class="w-4 h-4 rounded-full flex items-center justify-center">
                            <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-bold text-sm text-gray-800 dark:text-white">শ্রেণি + খামাল স্টক</h4>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1 leading-relaxed">
                            ইটের ক্লাসের পাশাপাশি ভাটার কোন স্তূপ বা খামাল (Yard/Kiln stack) থেকে স্টক লোড/ডেলিভারি হচ্ছে তা গভীর ট্র্যাক করে।
                        </p>
                    </div>
                </div>

            </div>



            <!-- Submit -->
            <div class="flex mt-6">
                <button type="submit" onclick="return confirm('আপনি কি নিশ্চিত যে স্টক সেটিংস পরিবর্তন করতে চান?')"
                        class="px-8 py-3.5 bg-primary hover:bg-primary-light text-white text-xs font-bold rounded-xl transition-all shadow-md cursor-pointer hover:scale-[1.01] active:scale-[0.99]">
                    স্টক সেটিংস সংরক্ষণ করুন
                </button>
            </div>
        </form>
    </div>
</div>
