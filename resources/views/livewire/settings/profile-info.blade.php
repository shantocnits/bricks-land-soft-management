<div class="space-y-6">

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

    <!-- Main Stack (Grid of 1 column, always full width) -->
    <div class="space-y-6">
        
        <!-- 1. Business Info Form Card -->
        <div class="w-full bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl p-6 shadow-sm transition-colors duration-300">
            <div class="flex items-center gap-3 border-b border-gray-150 dark:border-slate-800 pb-4 mb-6">
                <h3 class="font-bold text-sm text-gray-800 dark:text-white">বিজনেস বিবরণ</h3>
            </div>

            <form wire:submit.prevent="save" class="space-y-5">
                
                <!-- Row 1: Name Bangla & English -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-400 dark:text-gray-500 mb-1.5 uppercase tracking-wide">ভাটার নাম (বাংলা)</label>
                        <input type="text" wire:model="company_name_bn" @disabled(!$isEditing)
                               class="w-full py-3 px-4 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-250/20 dark:focus:ring-emerald-950/30 transition-all font-semibold shadow-inner disabled:bg-gray-100/70 dark:disabled:bg-slate-900/60 disabled:text-gray-500 dark:disabled:text-gray-400 disabled:cursor-not-allowed">
                        @error('company_name_bn') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-400 dark:text-gray-500 mb-1.5 uppercase tracking-wide">ভাটার নাম (ইংরেজি)</label>
                        <input type="text" wire:model="company_name_en" @disabled(!$isEditing)
                               class="w-full py-3 px-4 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-250/20 dark:focus:ring-emerald-950/30 transition-all font-semibold shadow-inner disabled:bg-gray-100/70 dark:disabled:bg-slate-900/60 disabled:text-gray-500 dark:disabled:text-gray-400 disabled:cursor-not-allowed">
                        @error('company_name_en') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Row 2: Address -->
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 dark:text-gray-500 mb-1.5 uppercase tracking-wide">ঠিকানা (চালান অনুযায়ী)</label>
                    <input type="text" wire:model="address" @disabled(!$isEditing)
                           class="w-full py-3 px-4 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-250/20 dark:focus:ring-emerald-950/30 transition-all font-semibold shadow-inner disabled:bg-gray-100/70 dark:disabled:bg-slate-900/60 disabled:text-gray-500 dark:disabled:text-gray-400 disabled:cursor-not-allowed">
                    @error('address') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Row 3: Owner Name & Personal Phone -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-400 dark:text-gray-500 mb-1.5 uppercase tracking-wide">মালিকের নাম</label>
                        <input type="text" wire:model="owner_name" @disabled(!$isEditing)
                               class="w-full py-3 px-4 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-250/20 dark:focus:ring-emerald-950/30 transition-all font-semibold shadow-inner disabled:bg-gray-100/70 dark:disabled:bg-slate-900/60 disabled:text-gray-500 dark:disabled:text-gray-400 disabled:cursor-not-allowed">
                        @error('owner_name') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-400 dark:text-gray-500 mb-1.5 uppercase tracking-wide">ব্যক্তিগত যোগাযোগ</label>
                        <input type="text" wire:model="owner_phone" @disabled(!$isEditing)
                               class="w-full py-3 px-4 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-250/20 dark:focus:ring-emerald-950/30 transition-all font-semibold shadow-inner disabled:bg-gray-100/70 dark:disabled:bg-slate-900/60 disabled:text-gray-500 dark:disabled:text-gray-400 disabled:cursor-not-allowed">
                        @error('owner_phone') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Row 4: Invoice Phone Numbers -->
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 dark:text-gray-500 mb-1.5 uppercase tracking-wide">ইনভয়েস ফোন নম্বর (কমা দিয়ে একাধিক লিখুন)</label>
                    <input type="text" wire:model="invoice_phones" @disabled(!$isEditing)
                           class="w-full py-3 px-4 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-250/20 dark:focus:ring-emerald-950/30 transition-all font-semibold shadow-inner disabled:bg-gray-100/70 dark:disabled:bg-slate-900/60 disabled:text-gray-500 dark:disabled:text-gray-400 disabled:cursor-not-allowed">
                    @error('invoice_phones') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-3 pt-2">
                    @if(!$isEditing)
                        <button type="button" wire:click="enableEditing"
                                class="px-8 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-all shadow-md hover:scale-[1.01] active:scale-[0.99] cursor-pointer flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                            পরিবর্তন করুন
                        </button>
                    @else
                        <button type="submit"
                                class="px-8 py-3.5 bg-primary hover:bg-primary-light text-white text-xs font-bold rounded-xl transition-all shadow-md hover:scale-[1.01] active:scale-[0.99] cursor-pointer flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            তথ্য আপডেট করুন
                        </button>
                        <button type="button" wire:click="cancelEditing"
                                class="px-6 py-3.5 bg-gray-200 dark:bg-slate-800 text-gray-700 dark:text-slate-300 text-xs font-bold rounded-xl hover:bg-gray-300 dark:hover:bg-slate-700 transition-all cursor-pointer">
                            বাতিল
                        </button>
                    @endif
                </div>
            </form>
        </div>

        <!-- 2. Billing Info Card -->
        <div class="w-full bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl p-6 shadow-sm transition-colors duration-300">
            <div class="flex items-center gap-3 border-b border-gray-150 dark:border-slate-800 pb-4 mb-6">
                <h3 class="font-bold text-sm text-gray-800 dark:text-white">বিলিং তথ্য</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Client ID -->
                <div>
                    <span class="block text-[10px] font-bold text-gray-400 dark:text-gray-500 mb-1.5 uppercase tracking-wide">ক্লায়েন্ট আইডি</span>
                    <div class="w-full py-3.5 px-4 bg-gray-50 dark:bg-slate-950 border border-gray-200 dark:border-slate-800 text-xs font-bold text-emerald-700 dark:text-emerald-400 rounded-xl">
                        {{ $client_id }}
                    </div>
                </div>

                <!-- Monthly Software Fee -->
                <div>
                    <span class="block text-[10px] font-bold text-gray-400 dark:text-gray-500 mb-1.5 uppercase tracking-wide">মাসিক সফটওয়্যার ফি</span>
                    <div class="w-full py-3.5 px-4 bg-gray-50 dark:bg-slate-950 border border-gray-200 dark:border-slate-800 text-xs font-bold text-gray-800 dark:text-gray-200 rounded-xl">
                        ৳ {{ number_format($monthly_fee, 2) }}
                    </div>
                </div>

                <!-- SMS Rate -->
                <div>
                    <span class="block text-[10px] font-bold text-gray-400 dark:text-gray-500 mb-1.5 uppercase tracking-wide">এসএমএস রেট (প্রতিটি)</span>
                    <div class="w-full py-3.5 px-4 bg-gray-50 dark:bg-slate-950 border border-gray-200 dark:border-slate-800 text-xs font-bold text-gray-800 dark:text-gray-200 rounded-xl">
                        ৳ {{ $sms_rate }}
                    </div>
                </div>
            </div>

            <!-- Next Payment Date Banner (Light orange background) -->
            <div class="mt-6 p-4 bg-orange-50 dark:bg-orange-950/20 border border-orange-100 dark:border-orange-900/50 rounded-2xl flex flex-col items-center justify-center text-center gap-1.5">
                <span class="text-[10px] font-bold text-orange-600 dark:text-orange-400 uppercase tracking-wide">পরবর্তী পেমেন্ট</span>
                <div class="flex items-center gap-2 text-sm font-extrabold text-orange-700 dark:text-orange-450">
                    <span>{{ $next_payment_date }}</span>
                </div>
            </div>
        </div>

    </div>
</div>
