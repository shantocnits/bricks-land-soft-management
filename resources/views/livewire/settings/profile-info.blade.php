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


    <!-- Top Alert Box -->
    <div class="flex items-start gap-4 p-4 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/50 rounded-2xl">
        <div class="flex-shrink-0 p-2 bg-emerald-600 text-white rounded-xl flex items-center justify-center">
            <!-- Info Icon -->
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 111.063.852l-.708 2.836a.75.75 0 001.063.852l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
            </svg>
        </div>
        <div>
            <h4 class="font-bold text-gray-800 dark:text-white text-sm">প্রতিষ্ঠানের তথ্য</h4>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">ওনার প্রোফাইল এবং বিলিং তথ্য। যে কোনও তথ্য আপডেটের জন্য হেল্পলাইনে যোগাযোগ করুন</p>
        </div>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left: Business Info Form (2/3 width on large screens) -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-3xl p-6 shadow-sm transition-colors duration-300">
            <div class="flex items-center gap-3 border-b border-gray-100 dark:border-slate-800 pb-4 mb-6">
                <h3 class="font-bold text-sm text-gray-800 dark:text-white">বিজনেস বিবরণ</h3>
            </div>

            <form wire:submit.prevent="save" class="space-y-5">
                
                <!-- Row 1: Name Bangla & English -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-400 dark:text-gray-500 mb-1.5 uppercase tracking-wide">ডাটার নাম (বাংলা)</label>
                        <input type="text" wire:model="company_name_bn"
                               class="w-full py-3 px-4 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-250/20 dark:focus:ring-emerald-950/30 transition-all font-semibold shadow-inner">
                        @error('company_name_bn') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-400 dark:text-gray-500 mb-1.5 uppercase tracking-wide">ডাটার নাম (ইংরেজি)</label>
                        <input type="text" wire:model="company_name_en"
                               class="w-full py-3 px-4 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-250/20 dark:focus:ring-emerald-950/30 transition-all font-semibold shadow-inner">
                        @error('company_name_en') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Row 2: Address -->
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 dark:text-gray-500 mb-1.5 uppercase tracking-wide">ঠিকানা (চালান অনুযায়ী)</label>
                    <input type="text" wire:model="address"
                           class="w-full py-3 px-4 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-250/20 dark:focus:ring-emerald-950/30 transition-all font-semibold shadow-inner">
                    @error('address') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Row 3: Owner Name & Personal Phone -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-400 dark:text-gray-500 mb-1.5 uppercase tracking-wide">মালিকের নাম</label>
                        <input type="text" wire:model="owner_name"
                               class="w-full py-3 px-4 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-250/20 dark:focus:ring-emerald-950/30 transition-all font-semibold shadow-inner">
                        @error('owner_name') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-400 dark:text-gray-500 mb-1.5 uppercase tracking-wide">ব্যক্তিগত যোগাযোগ</label>
                        <input type="text" wire:model="owner_phone"
                               class="w-full py-3 px-4 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-250/20 dark:focus:ring-emerald-950/30 transition-all font-semibold shadow-inner">
                        @error('owner_phone') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Row 4: Invoice Phone Numbers -->
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 dark:text-gray-500 mb-1.5 uppercase tracking-wide">ইনভয়েস ফোন নম্বর (কমা দিয়ে একাধিক লিখুন)</label>
                    <input type="text" wire:model="invoice_phones"
                           class="w-full py-3 px-4 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-250/20 dark:focus:ring-emerald-950/30 transition-all font-semibold shadow-inner">
                    @error('invoice_phones') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Action Button -->
                <div class="flex pt-2">
                    <button type="submit"
                            class="px-6 py-2.5 bg-primary hover:bg-primary-light text-white text-xs font-bold rounded-xl transition-all shadow-md hover:scale-[1.01] active:scale-[0.99] cursor-pointer">
                        তথ্য আপডেট করুন
                    </button>
                </div>
            </form>
        </div>

        <!-- Right: Billing Info Card (1/3 width on large screens) -->
        <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-3xl p-6 shadow-sm flex flex-col transition-colors duration-300">
            <div class="flex items-center gap-3 border-b border-gray-100 dark:border-slate-800 pb-4 mb-6">
                <h3 class="font-bold text-sm text-gray-800 dark:text-white">বিলিং তথ্য</h3>
            </div>

            <div class="space-y-5 flex-grow">
                <!-- Client ID -->
                <div>
                    <span class="block text-[10px] font-bold text-gray-400 dark:text-gray-500 mb-1 uppercase tracking-wide">ক্লায়েন্ট আইডি</span>
                    <div class="w-full py-3 px-4 bg-gray-50 dark:bg-slate-950/50 border border-gray-100 dark:border-slate-800 text-xs font-bold text-emerald-700 dark:text-emerald-400 rounded-xl">
                        {{ $client_id }}
                    </div>
                </div>

                <!-- Monthly Software Fee -->
                <div>
                    <span class="block text-[10px] font-bold text-gray-400 dark:text-gray-500 mb-1 uppercase tracking-wide">মাসিক সফটওয়্যার ফি</span>
                    <div class="w-full py-3 px-4 bg-gray-50 dark:bg-slate-950/50 border border-gray-100 dark:border-slate-800 text-xs font-bold text-gray-800 dark:text-gray-200 rounded-xl">
                        ৳ {{ number_format($monthly_fee, 2) }}
                    </div>
                </div>

                <!-- SMS Rate -->
                <div>
                    <span class="block text-[10px] font-bold text-gray-400 dark:text-gray-500 mb-1 uppercase tracking-wide">এসএমএস রেট (প্রতিটি)</span>
                    <div class="w-full py-3 px-4 bg-gray-50 dark:bg-slate-950/50 border border-gray-100 dark:border-slate-800 text-xs font-bold text-gray-800 dark:text-gray-200 rounded-xl">
                        ৳ {{ $sms_rate }}
                    </div>
                </div>

                <!-- Next Payment Date Banner (Light orange background) -->
                <div class="mt-8 p-4 bg-orange-50 dark:bg-orange-950/20 border border-orange-100 dark:border-orange-900/50 rounded-2xl flex flex-col items-center justify-center text-center gap-1.5">
                    <span class="text-[10px] font-bold text-orange-600 dark:text-orange-400 uppercase tracking-wide">পরবর্তী পেমেন্ট</span>
                    <div class="flex items-center gap-2 text-sm font-extrabold text-orange-700 dark:text-orange-450">
                        <span>{{ $next_payment_date }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
