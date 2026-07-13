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

    <div class="max-w-4xl bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-3xl p-6 shadow-sm transition-colors duration-300">
        <div class="flex items-center gap-3 border-b border-gray-100 dark:border-slate-800 pb-4 mb-6">
            <h3 class="font-bold text-sm text-gray-800 dark:text-white">স্টক সিস্টেম কনফিগারেশন</h3>
        </div>

        <!-- Danger/Warning Alert -->
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-900/60 text-red-800 dark:text-red-400 text-xs rounded-2xl flex gap-3 font-sans leading-relaxed">
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
            <div class="flex">
                <button type="submit" onclick="return confirm('আপনি কি নিশ্চিত যে স্টক সিস্টেম পরিবর্তন করতে চান?')"
                        class="px-8 py-2.5 bg-primary hover:bg-primary-light text-white text-xs font-bold rounded-xl transition-all shadow-md">
                    স্টক সেটিংস সংরক্ষণ করুন
                </button>
            </div>
        </form>
    </div>
</div>
