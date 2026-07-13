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

    <div class="max-w-2xl bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-3xl p-6 shadow-sm transition-colors duration-300">
        <div class="flex items-center gap-3 border-b border-gray-100 dark:border-slate-800 pb-4 mb-6">
            <h3 class="font-bold text-sm text-gray-800 dark:text-white">এসএমএস অটো-নোটিফিকেশন সেটিংস</h3>
        </div>

        <form wire:submit.prevent="save" class="space-y-6">
            
            <div class="divide-y divide-gray-100 dark:divide-slate-800">
                
                <!-- 1. New Invoice -->
                <div class="flex items-center justify-between py-4">
                    <div class="pr-4">
                        <h4 class="font-bold text-xs text-gray-800 dark:text-white">নতুন ইনভয়েস (চালান) নোটিফিকেশন</h4>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1 leading-relaxed">
                            নতুন চালান বা ইনভয়েস এন্ট্রি করার সাথে সাথে কাস্টমারের মোবাইলে কনফার্মেশন এসএমএস পাঠানো হবে।
                        </p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                        <input type="checkbox" wire:model="sms_new_invoice" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 dark:bg-slate-800 rounded-full peer peer-focus:ring-2 peer-focus:ring-emerald-250/20 dark:peer-focus:ring-emerald-950/20 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-700 peer-checked:bg-primary"></div>
                    </label>
                </div>

                <!-- 2. Invoice Update -->
                <div class="flex items-center justify-between py-4">
                    <div class="pr-4">
                        <h4 class="font-bold text-xs text-gray-800 dark:text-white">ইনভয়েস আপডেট নোটিফিকেশন</h4>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1 leading-relaxed">
                            পূর্বে তৈরি করা কোনো চালানের পরিমাণ, রেট বা তথ্য পরিবর্তন করা হলে কাস্টমারকে নোটিফিকেশন পাঠানো হবে।
                        </p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                        <input type="checkbox" wire:model="sms_update_invoice" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 dark:bg-slate-800 rounded-full peer peer-focus:ring-2 peer-focus:ring-emerald-250/20 dark:peer-focus:ring-emerald-950/20 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-700 peer-checked:bg-primary"></div>
                    </label>
                </div>

                <!-- 3. Invoice Delete -->
                <div class="flex items-center justify-between py-4">
                    <div class="pr-4">
                        <h4 class="font-bold text-xs text-gray-800 dark:text-white">ইনভয়েস ডিলিট নোটিফিকেশন</h4>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1 leading-relaxed">
                            নিরাপত্তার স্বার্থে কোনো চালান বাতিল বা সম্পূর্ণ ডিলিট করা হলে মালিক পক্ষকে সতর্কতা নোটিফিকেশন পাঠানো হবে।
                        </p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                        <input type="checkbox" wire:model="sms_delete_invoice" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 dark:bg-slate-800 rounded-full peer peer-focus:ring-2 peer-focus:ring-emerald-250/20 dark:peer-focus:ring-emerald-950/20 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-700 peer-checked:bg-primary"></div>
                    </label>
                </div>

                <!-- 4. New Delivery -->
                <div class="flex items-center justify-between py-4">
                    <div class="pr-4">
                        <h4 class="font-bold text-xs text-gray-800 dark:text-white">নতুন ইট ডেলিভারি নোটিফিকেশন</h4>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1 leading-relaxed">
                            ভাটা থেকে ইট লোডিং বা ডেলিভারি এন্ট্রি করা হলে চালকের বিবরণ সহ কাস্টমারকে নোটিফিকেশন পাঠানো হবে।
                        </p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                        <input type="checkbox" wire:model="sms_new_delivery" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 dark:bg-slate-800 rounded-full peer peer-focus:ring-2 peer-focus:ring-emerald-250/20 dark:peer-focus:ring-emerald-950/20 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-700 peer-checked:bg-primary"></div>
                    </label>
                </div>

                <!-- 5. Due Collection -->
                <div class="flex items-center justify-between py-4">
                    <div class="pr-4">
                        <h4 class="font-bold text-xs text-gray-800 dark:text-white">বাকি টাকা আদায় (Due Collection) নোটিফিকেশন</h4>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1 leading-relaxed">
                            কাস্টমার থেকে বকেয়া পেমেন্ট সংগ্রহ করে রিসিভ এন্ট্রি করা হলে প্রাপ্ত টাকার পরিমাণ ও বর্তমান বকেয়া ব্যালেন্সের এসএমএস যাবে।
                        </p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                        <input type="checkbox" wire:model="sms_due_collection" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 dark:bg-slate-800 rounded-full peer peer-focus:ring-2 peer-focus:ring-emerald-250/20 dark:peer-focus:ring-emerald-950/20 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-700 peer-checked:bg-primary"></div>
                    </label>
                </div>

            </div>

            <!-- Submit -->
            <div class="flex pt-4">
                <button type="submit"
                        class="px-8 py-2.5 bg-primary hover:bg-primary-light text-white text-xs font-bold rounded-xl transition-all shadow-md">
                    এসএমএস সেটিংস সংরক্ষণ করুন
                </button>
            </div>
        </form>
    </div>
</div>
