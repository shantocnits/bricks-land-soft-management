<div class="space-y-6">
    <!-- Header banner card -->
    <div class="flex items-start gap-4 p-4 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/50 rounded-2xl">
        <div class="flex-shrink-0 p-2 bg-emerald-600 text-white rounded-xl flex items-center justify-center">
            <!-- Lock Icon -->
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
            </svg>
        </div>
        <div>
            <h4 class="font-bold text-gray-800 dark:text-white text-sm">পাসওয়ার্ড পরিবর্তন</h4>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">আপনার অ্যাকাউন্টের নিরাপত্তা নিশ্চিত করতে নিয়মিত পাসওয়ার্ড পরিবর্তন করুন।</p>
        </div>
    </div>

    <!-- Main Card -->
    <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl p-6 shadow-sm transition-colors duration-300">
        <div class="flex items-center gap-3 border-b border-gray-150 dark:border-slate-800 pb-4 mb-5">
            <h3 class="font-bold text-sm text-gray-800 dark:text-white">নিরাপত্তা সেটিংস</h3>
        </div>

        <!-- Warning Alert Banner -->
        <div class="mb-6 p-4 bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900/60 rounded-2xl text-amber-800 dark:text-amber-400 text-xs flex gap-3 font-sans leading-relaxed">
            <svg class="w-5 h-5 text-amber-600 dark:text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div>
                <span class="font-black">সতর্কতা:</span> পাসওয়ার্ড সফলভাবে পরিবর্তনের পর আপনার রানিং সেশনটি বন্ধ হয়ে যাবে এবং আপনাকে স্বয়ংক্রিয়ভাবে লগআউট করে দেওয়া হবে। পরবর্তীতে আপনাকে নতুন পাসওয়ার্ডটি ব্যবহার করে পুনরায় লগইন করতে হবে।
            </div>
        </div>

        <form wire:submit.prevent="changePassword" class="space-y-6 w-full">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Old Password (col-span-2) -->
                <div x-data="{ show: false }" class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">পুরাতন পাসওয়ার্ড</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" wire:model="current_password" placeholder="আপনার পুরাতন পাসওয়ার্ড দিন"
                               class="w-full py-2.5 pl-4 pr-10 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-250/20 dark:focus:ring-emerald-950/30 transition-all font-semibold">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-emerald-600 focus:outline-none transition-colors">
                            <svg x-show="show" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" x-cloak>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="!show" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.024 10.024 0 013.859-4.877m2.138-2.138A9.974 9.974 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21M12 9a3 3 0 00-3 3m3 0a3 3 0 003-3M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>
                    @error('current_password') <span class="text-red-500 text-[10px] mt-1 block font-sans">{{ $message }}</span> @enderror
                </div>

                <!-- New Password -->
                <div x-data="{ show: false }">
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">নতুন পাসওয়ার্ড</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" wire:model="new_password" placeholder="কমপক্ষে ৮ অক্ষরের নতুন পাসওয়ার্ড"
                               class="w-full py-2.5 pl-4 pr-10 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-250/20 dark:focus:ring-emerald-950/30 transition-all font-semibold">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-emerald-600 focus:outline-none transition-colors">
                            <svg x-show="show" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" x-cloak>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="!show" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.024 10.024 0 013.859-4.877m2.138-2.138A9.974 9.974 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21M12 9a3 3 0 00-3 3m3 0a3 3 0 003-3M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>
                    @error('new_password') <span class="text-red-500 text-[10px] mt-1 block font-sans">{{ $message }}</span> @enderror
                </div>

                <!-- Confirm New Password -->
                <div x-data="{ show: false }">
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">নতুন পাসওয়ার্ড নিশ্চিত করুন</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" wire:model="new_password_confirmation" placeholder="নতুন পাসওয়ার্ডটি আবার লিখুন"
                               class="w-full py-2.5 pl-4 pr-10 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-250/20 dark:focus:ring-emerald-950/30 transition-all font-semibold">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-emerald-600 focus:outline-none transition-colors">
                            <svg x-show="show" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" x-cloak>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="!show" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.024 10.024 0 013.859-4.877m2.138-2.138A9.974 9.974 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21M12 9a3 3 0 00-3 3m3 0a3 3 0 003-3M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex pt-2">
                <button type="submit"
                        class="px-8 py-3 bg-primary hover:bg-primary-light text-white text-xs font-bold rounded-xl transition-all shadow-md cursor-pointer hover:scale-[1.01] active:scale-[0.99]">
                    পাসওয়ার্ড পরিবর্তন করুন
                </button>
            </div>
        </form>
    </div>
</div>
