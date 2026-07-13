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

    <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-3xl p-6 shadow-sm transition-colors duration-300">
        <div class="flex items-center gap-3 border-b border-gray-100 dark:border-slate-800 pb-4 mb-5">
            <h3 class="font-bold text-sm text-gray-800 dark:text-white">ব্যবহারকারী পারমিশন কনফিগারেশন</h3>
        </div>

        <form wire:submit.prevent="save" class="space-y-6">
            
            <!-- User Selection -->
            <div class="max-w-md" x-data="{ open: false, selectedUser: 'ইউজার নির্বাচন করুন', userId: @entangle('selectedUserId') }">
                <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 mb-1.5 uppercase">ব্যবহারকারী নির্বাচন</label>
                <div class="relative">
                    <button type="button" @click="open = !open"
                            class="w-full flex items-center justify-between py-2.5 px-4 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none transition-all font-semibold text-left">
                        <span x-text="selectedUser"></span>
                        <svg class="w-4 h-4 text-emerald-700 dark:text-emerald-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition class="absolute left-0 right-0 mt-1.5 bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-gray-100 dark:border-slate-800 py-1 z-55 text-xs max-h-56 overflow-y-auto" x-cloak>
                        <button type="button" @click="userId = null; selectedUser = 'ইউজার নির্বাচন করুন'; open = false" class="w-full text-left px-4 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold">ইউজার নির্বাচন করুন</button>
                        @foreach($users as $user)
                            <button type="button" @click="userId = {{ $user->id }}; selectedUser = '{{ $user->name }} ({{ $user->role }})'; open = false" class="w-full text-left px-4 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold">
                                {{ $user->name }} ({{ $user->email }}) - <span class="uppercase text-[9px]">{{ $user->role }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
                @error('selectedUserId') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Permission Checklist (Visible when user is selected) -->
            @if($selectedUserId)
                @php
                    $userObj = $users->firstWhere('id', $selectedUserId);
                @endphp

                @if($userObj && $userObj->role === 'admin')
                    <div class="p-4 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-800 dark:text-emerald-400 text-xs rounded-2xl border border-emerald-150 dark:border-emerald-900 font-semibold flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>এডমিন অ্যাকাউন্টের সব মেনুতে অটোমেটিক অ্যাক্সেস থাকবে। এর জন্য পারমিশন সেটিং পরিবর্তনের প্রয়োজন নেই।</span>
                    </div>
                @else
                    <div class="space-y-4">
                        <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 mb-1 uppercase">মেনু অ্যাক্সেস পারমিশনসমূহ</label>
                        
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 p-5 bg-gray-50 dark:bg-slate-950 rounded-2xl border border-gray-200 dark:border-slate-800/80">
                            @foreach($menuOptions as $key => $label)
                                <label class="flex items-center space-x-2.5 text-xs text-gray-600 dark:text-slate-300 cursor-pointer hover:text-emerald-600 dark:hover:text-emerald-400 font-semibold transition-all">
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
                                class="px-8 py-2.5 bg-primary hover:bg-primary-light text-white text-xs font-bold rounded-xl transition-all shadow-md">
                            পারমিশন সংরক্ষণ করুন
                        </button>
                    </div>
                @endif
            @else
                <div class="py-8 text-center text-gray-400 text-xs font-bold">
                    পারমিশন কনফিগার করতে অনুগ্রহ করে ওপরের তালিকা থেকে একজন ব্যবহারকারী নির্বাচন করুন।
                </div>
            @endif

        </form>
    </div>
</div>
