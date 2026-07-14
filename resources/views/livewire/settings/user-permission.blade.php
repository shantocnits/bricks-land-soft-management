<div class="space-y-6">
    <!-- Header banner card -->
    <div class="flex items-start gap-4 p-4 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/50 rounded-2xl">
        <div class="flex-shrink-0 p-2 bg-emerald-600 text-white rounded-xl flex items-center justify-center">
            <!-- Shield Check Icon -->
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
            </svg>
        </div>
        <div>
            <h4 class="font-bold text-gray-800 dark:text-white text-sm">ইউজার পারমিশন</h4>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">ব্যবহারকারীদের বিভিন্ন মেনু এক্সেস বা পারমিশন বরাদ্দ করুন।</p>
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

    <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl p-6 shadow-sm transition-colors duration-300">
        <div class="flex items-center gap-3 border-b border-gray-150 dark:border-slate-800 pb-4 mb-5">
            <h3 class="font-bold text-sm text-gray-800 dark:text-white">পারমিশন কনফিগারেশন</h3>
        </div>

        <form wire:submit.prevent="save" class="space-y-6">
            
            <!-- User Selection -->
            <div class="max-w-md" x-data="{ open: false, selectedUser: 'ইউজার নির্বাচন করুন', userId: @entangle('selectedUserId') }">
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">ব্যবহারকারী নির্বাচন</label>
                <div class="relative">
                    <button type="button" @click="open = !open"
                            class="w-full flex items-center justify-between py-2.5 px-4 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none transition-all font-semibold text-left cursor-pointer">
                        <span x-text="selectedUser"></span>
                        <svg class="w-4 h-4 text-emerald-700 dark:text-emerald-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition class="absolute left-0 right-0 mt-1.5 bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-gray-150 dark:border-slate-800 py-1.5 z-55 text-xs max-h-56 overflow-y-auto" x-cloak>
                        <button type="button" @click="userId = null; selectedUser = 'ইউজার নির্বাচন করুন'; open = false" class="w-full text-left px-4 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-lg">ইউজার নির্বাচন করুন</button>
                        @foreach($users as $user)
                            <button type="button" @click="userId = {{ $user->id }}; selectedUser = '{{ $user->name }} ({{ $user->role }})'; open = false" class="w-full text-left px-4 py-2.5 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-lg cursor-pointer">
                                {{ $user->name }} ({{ $user->email }}) - <span class="uppercase text-[9px] font-bold text-orange-600">{{ $user->role }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
                @error('selectedUserId') <span class="text-red-500 text-[10px] mt-1 block font-sans">{{ $message }}</span> @enderror
            </div>

            <!-- Permission Checklist (Visible when user is selected) -->
            @if($selectedUserId)
                @php
                    $userObj = $users->firstWhere('id', $selectedUserId);
                @endphp

                @if($userObj && $userObj->role === 'admin')
                    <div class="p-4 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-800 dark:text-emerald-400 text-xs rounded-2xl border border-emerald-150 dark:border-emerald-900 font-semibold flex items-center gap-3 font-sans">
                        <svg class="w-5 h-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>এডমিন অ্যাকাউন্টের সব মেনুতে অটোমেটিক অ্যাক্সেস থাকবে। এর জন্য পারমিশন সেটিং পরিবর্তনের প্রয়োজন নেই।</span>
                    </div>
                @else
                    <div class="space-y-4">
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1 font-sans">মেনু অ্যাক্সেস পারমিশনসমূহ</label>
                        
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 p-5 bg-gray-50 dark:bg-slate-950/45 rounded-2xl border border-gray-150 dark:border-slate-800">
                            @foreach($menuOptions as $key => $label)
                                <label class="flex items-center space-x-2.5 text-xs text-gray-700 dark:text-slate-300 cursor-pointer hover:text-emerald-600 dark:hover:text-emerald-400 font-semibold transition-all font-sans">
                                    <input 
                                        type="checkbox" 
                                        value="{{ $key }}"
                                        wire:model="selectedPermissions"
                                        class="rounded-lg border-gray-300 text-emerald-600 focus:ring-emerald-500 h-4 w-4 cursor-pointer">
                                    <span>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="flex">
                        <button type="submit"
                                class="px-8 py-3.5 bg-primary hover:bg-primary-light text-white text-xs font-bold rounded-xl transition-all shadow-md cursor-pointer hover:scale-[1.01] active:scale-[0.99]">
                            পারমিশন সংরক্ষণ করুন
                        </button>
                    </div>
                @endif
            @else
                <div class="py-8 text-center text-gray-400 text-xs font-bold font-sans">
                    পারমিশন কনফিগার করতে অনুগ্রহ করে ওপরের তালিকা থেকে একজন ব্যবহারকারী নির্বাচন করুন।
                </div>
            @endif

        </form>
    </div>
</div>
