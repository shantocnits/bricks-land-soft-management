<div class="space-y-6">
    <!-- Header banner card matching screenshot -->
    <div class="flex items-center gap-4 pb-4 border-b border-gray-100 dark:border-slate-800">
        <div class="flex-shrink-0 p-3 bg-emerald-100 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 rounded-2xl flex items-center justify-center shadow-sm">
            <!-- SMS Icon -->
            <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 9.75a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z"/>
            </svg>
        </div>
        <div>
            <h2 class="font-bold text-gray-800 dark:text-white text-lg font-sans">এসএমএস সেটিংস</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 font-sans">কোন অ্যাকশনের সময় অটোমেটিক এসএমএস পাঠাতে চান তা ঠিক করুন</p>
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
        
        <!-- Toggle Switch Grid (2 column card grid) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            
            <!-- 1. New Invoice -->
            <div x-data="{ on: @entangle('sms_new_invoice') }"
                 class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800/80 rounded-2xl p-5 shadow-sm flex items-center justify-between gap-4 transition-all">
                <div class="space-y-1">
                    <h4 class="font-bold text-gray-800 dark:text-white text-xs font-sans">নতুন ইনভয়েস</h4>
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 font-sans leading-relaxed">কাস্টমার ইনভয়েস প্রিন্ট করার সময় এসএমএস যাবে</p>
                </div>
                <button type="button" @click="on = !on" class="relative flex-shrink-0 focus:outline-none cursor-pointer w-11 h-6">
                    <div class="w-11 h-6 rounded-full transition-colors duration-300 absolute inset-0"
                         :class="on ? 'bg-emerald-600' : 'bg-slate-200 dark:bg-slate-700'"></div>
                    <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow-md transition-transform duration-300"
                         :class="on ? 'translate-x-5' : 'translate-x-0'"></div>
                </button>
            </div>

            <!-- 2. Invoice Update -->
            <div x-data="{ on: @entangle('sms_update_invoice') }"
                 class="bg-white dark:bg-slate-900 border border-gray-155 dark:border-slate-800/80 rounded-2xl p-5 shadow-sm flex items-center justify-between gap-4 transition-all">
                <div class="space-y-1">
                    <h4 class="font-bold text-gray-800 dark:text-white text-xs font-sans">ইনভয়েস আপডেট</h4>
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 font-sans leading-relaxed">কোনো ইনভয়েস এডিট বা আপডেট করার সময় এসএমএস যাবে</p>
                </div>
                <button type="button" @click="on = !on" class="relative flex-shrink-0 focus:outline-none cursor-pointer w-11 h-6">
                    <div class="w-11 h-6 rounded-full transition-colors duration-300 absolute inset-0"
                         :class="on ? 'bg-emerald-600' : 'bg-slate-200 dark:bg-slate-700'"></div>
                    <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow-md transition-transform duration-300"
                         :class="on ? 'translate-x-5' : 'translate-x-0'"></div>
                </button>
            </div>

            <!-- 3. Invoice Delete -->
            <div x-data="{ on: @entangle('sms_delete_invoice') }"
                 class="bg-white dark:bg-slate-900 border border-gray-155 dark:border-slate-800/80 rounded-2xl p-5 shadow-sm flex items-center justify-between gap-4 transition-all">
                <div class="space-y-1">
                    <h4 class="font-bold text-gray-800 dark:text-white text-xs font-sans">ইনভয়েস ডিলিট</h4>
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 font-sans leading-relaxed">ইনভয়েস ডিলিট করলে কাস্টমার বা এডমিন এসএমএস পাবে</p>
                </div>
                <button type="button" @click="on = !on" class="relative flex-shrink-0 focus:outline-none cursor-pointer w-11 h-6">
                    <div class="w-11 h-6 rounded-full transition-colors duration-300 absolute inset-0"
                         :class="on ? 'bg-emerald-600' : 'bg-slate-200 dark:bg-slate-700'"></div>
                    <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow-md transition-transform duration-300"
                         :class="on ? 'translate-x-5' : 'translate-x-0'"></div>
                </button>
            </div>

            <!-- 4. New Delivery -->
            <div x-data="{ on: @entangle('sms_new_delivery') }"
                 class="bg-white dark:bg-slate-900 border border-gray-155 dark:border-slate-800/80 rounded-2xl p-5 shadow-sm flex items-center justify-between gap-4 transition-all">
                <div class="space-y-1">
                    <h4 class="font-bold text-gray-800 dark:text-white text-xs font-sans">নতুন ডেলিভারি</h4>
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 font-sans leading-relaxed">ইট ডেলিভারি এন্ট্রি দেওয়ার সময় এসএমএস যাবে</p>
                </div>
                <button type="button" @click="on = !on" class="relative flex-shrink-0 focus:outline-none cursor-pointer w-11 h-6">
                    <div class="w-11 h-6 rounded-full transition-colors duration-300 absolute inset-0"
                         :class="on ? 'bg-emerald-600' : 'bg-slate-200 dark:bg-slate-700'"></div>
                    <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow-md transition-transform duration-300"
                         :class="on ? 'translate-x-5' : 'translate-x-0'"></div>
                </button>
            </div>

            <!-- 5. New Due Collection -->
            <div x-data="{ on: @entangle('sms_due_collection') }"
                 class="bg-white dark:bg-slate-900 border border-gray-155 dark:border-slate-800/80 rounded-2xl p-5 shadow-sm flex items-center justify-between gap-4 transition-all">
                <div class="space-y-1">
                    <h4 class="font-bold text-gray-800 dark:text-white text-xs font-sans">নতুন বাকি কালেকশন</h4>
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 font-sans leading-relaxed">বকেয়া টাকা জমার সময় কনফার্মেশন এসএমএস যাবে</p>
                </div>
                <button type="button" @click="on = !on" class="relative flex-shrink-0 focus:outline-none cursor-pointer w-11 h-6">
                    <div class="w-11 h-6 rounded-full transition-colors duration-300 absolute inset-0"
                         :class="on ? 'bg-emerald-600' : 'bg-slate-200 dark:bg-slate-700'"></div>
                    <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow-md transition-transform duration-300"
                         :class="on ? 'translate-x-5' : 'translate-x-0'"></div>
                </button>
            </div>

            <!-- 6. Due Collection Update -->
            <div x-data="{ on: @entangle('sms_due_collection_update') }"
                 class="bg-white dark:bg-slate-900 border border-gray-155 dark:border-slate-800/80 rounded-2xl p-5 shadow-sm flex items-center justify-between gap-4 transition-all">
                <div class="space-y-1">
                    <h4 class="font-bold text-gray-800 dark:text-white text-xs font-sans">বাকি কালেকশন আপডেট</h4>
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 font-sans leading-relaxed">জমা বা বকেয়া এন্ট্রি আপডেট করার সময় এসএমএস যাবে</p>
                </div>
                <button type="button" @click="on = !on" class="relative flex-shrink-0 focus:outline-none cursor-pointer w-11 h-6">
                    <div class="w-11 h-6 rounded-full transition-colors duration-300 absolute inset-0"
                         :class="on ? 'bg-emerald-600' : 'bg-slate-200 dark:bg-slate-700'"></div>
                    <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow-md transition-transform duration-300"
                         :class="on ? 'translate-x-5' : 'translate-x-0'"></div>
                </button>
            </div>

        </div>

        <!-- Submit Button: Full-width green button -->
        <button type="submit"
                class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-all shadow-md cursor-pointer hover:scale-[1.005] active:scale-[0.995] font-sans flex items-center justify-center gap-2">
            <!-- Save Icon -->
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0z"/>
            </svg>
            সেটিংস সেভ করুন
        </button>

    </form>
</div>
