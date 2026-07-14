<div class="space-y-6">
    <!-- Header banner card -->
    <div class="flex items-start gap-4 p-4 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/50 rounded-2xl">
        <div class="flex-shrink-0 p-2 bg-emerald-600 text-white rounded-xl flex items-center justify-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
            </svg>
        </div>
        <div>
            <h4 class="font-bold text-gray-800 dark:text-white text-sm">আমার প্রোফাইল</h4>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">আপনার ব্যক্তিগত প্রোফাইল তথ্য এবং ছবি পরিচালনা করুন।</p>
        </div>
    </div>

    <!-- Main Beautiful Full Width Grid -->
    <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl p-6 shadow-sm transition-colors duration-300">
        @if (session()->has('user_message'))
            <div x-data="{ show: true }"
                 x-show="show"
                 x-init="setTimeout(() => show = false, 3000)"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-900 text-emerald-800 dark:text-emerald-400 rounded-2xl text-xs font-semibold flex items-center gap-2 shadow-sm"
                 x-cloak>
                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('user_message') }}</span>
            </div>
        @endif

        <form wire:submit.prevent="saveUserProfile" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left Side: Profile Photo Upload (lg:col-span-4) -->
            <div class="lg:col-span-4 flex flex-col items-center justify-center p-6 bg-gray-50 dark:bg-slate-950/40 rounded-2xl border border-gray-100 dark:border-slate-800/80">
                <span class="block text-[11px] font-bold text-gray-400 dark:text-gray-500 mb-4 uppercase tracking-wider">প্রোফাইল ছবি</span>
                
                <div class="relative w-28 h-28 rounded-full overflow-hidden border-2 border-emerald-500 dark:border-emerald-700 bg-gray-150 dark:bg-slate-950 flex items-center justify-center shadow-md mb-4 animate-settings-fade">
                    @if ($user_photo)
                        <img src="{{ $user_photo->temporaryUrl() }}" class="w-full h-full object-cover animate-pulse">
                    @elseif ($current_photo_path)
                        <img src="{{ asset('storage/' . $current_photo_path) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-emerald-600 text-white flex items-center justify-center font-bold text-3xl uppercase">
                            {{ strtoupper(substr($user_name ?? 'U', 0, 1)) }}
                        </div>
                    @endif
                </div>
                
                <!-- Upload Input Button -->
                <div class="relative">
                    <label class="px-4 py-2 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/30 dark:hover:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400 text-xs font-bold rounded-xl border border-emerald-250 cursor-pointer transition-all shadow-sm active:scale-95 block">
                        ছবি পরিবর্তন করুন
                        <input type="file" wire:model="user_photo" class="hidden" accept="image/*">
                    </label>
                </div>
                <div wire:loading wire:target="user_photo" class="text-[10px] text-emerald-600 dark:text-emerald-400 font-bold mt-2 animate-bounce font-sans">ছবি আপলোড হচ্ছে...</div>
                @error('user_photo') <span class="text-red-500 text-[10px] mt-2 block font-sans text-center">{{ $message }}</span> @enderror
            </div>

            <!-- Right Side: Details Inputs (lg:col-span-8) -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Personal Name -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-2 font-sans">নাম (Name)</label>
                    <input type="text" wire:model="user_name"
                           class="w-full py-3 px-4 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-250/20 dark:focus:ring-emerald-950/30 transition-all font-semibold shadow-inner">
                    @error('user_name') <span class="text-red-500 text-[10px] mt-1 block font-sans">{{ $message }}</span> @enderror
                </div>

                <!-- Personal Username/Email -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-2 font-sans">ইউজারনেম / ইমেইল</label>
                    <input type="text" wire:model="user_email"
                           class="w-full py-3 px-4 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-250/20 dark:focus:ring-emerald-950/30 transition-all font-semibold shadow-inner">
                    @error('user_email') <span class="text-red-500 text-[10px] mt-1 block font-sans">{{ $message }}</span> @enderror
                </div>

                <!-- Save User Profile Button -->
                <button type="submit"
                        class="w-full sm:w-auto px-8 py-3.5 bg-primary hover:bg-primary-light text-white text-xs font-bold rounded-xl transition-all shadow-md hover:scale-[1.01] active:scale-[0.99] cursor-pointer text-center">
                    প্রোফাইল সংরক্ষণ করুন
                </button>
            </div>
        </form>
    </div>
</div>
