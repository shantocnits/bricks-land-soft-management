<div class="space-y-6">
    
    <!-- Header Title -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white font-sans">ব্যবহারকারী ও পারমিশন ম্যানেজমেন্ট</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-sans">এখানে নতুন ইউজার তৈরি করতে পারবেন এবং কার কোন মেনুতে অ্যাক্সেস থাকবে তা কনফিগার করতে পারবেন।</p>
        </div>
    </div>

    <!-- Alert Message with 3-second auto-dismiss -->
    @if (session()->has('message'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 3000)"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="p-4 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-900 text-emerald-800 dark:text-emerald-400 rounded-2xl flex items-center gap-3 text-sm shadow-sm transition-all duration-300 font-sans"
             x-cloak>
            <span class="font-medium">{{ session('message') }}</span>
        </div>
    @endif

    <!-- Alert for Demo Mode -->
    @if(auth()->user()->hasRole('demo'))
        <div class="p-4 bg-blue-50 dark:bg-blue-950/20 border border-blue-200 dark:border-blue-900 text-blue-800 dark:text-blue-400 rounded-2xl flex items-center gap-3 text-sm shadow-sm font-sans">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="font-semibold">বিজ্ঞপ্তি: আপনি বর্তমানে ডেমো মোডে লগইন আছেন। কোনো তথ্য তৈরি, সংশোধন বা মুছে ফেলা বন্ধ রয়েছে।</span>
        </div>
    @endif

    <div class="space-y-6">
        
        <!-- Section 1: Create User Form (Full Width) -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800/80 p-6 transition-colors duration-300">
            <h3 class="text-base font-bold text-gray-800 dark:text-white mb-4 border-b border-gray-100 dark:border-slate-800 pb-2 font-sans">
                নতুন ব্যবহারকারী তৈরি করুন
            </h3>

            <form wire:submit.prevent="createUser" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Name Input -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">নাম (Name)</label>
                        <input 
                            type="text" 
                            wire:model="name"
                            @if(auth()->user()->hasRole('demo')) disabled @endif
                            class="w-full py-2 px-3 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-950/30 transition-all font-sans disabled:opacity-50"
                            placeholder="যেমন: Shanto">
                        @error('name') <span class="text-red-500 text-[10px] mt-1 block font-sans">{{ $message }}</span> @enderror
                    </div>

                    <!-- Email Input -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">ইউজারনেম / ইমেইল</label>
                        <input 
                            type="text" 
                            wire:model="email"
                            @if(auth()->user()->hasRole('demo')) disabled @endif
                            class="w-full py-2 px-3 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-950/30 transition-all font-sans disabled:opacity-50"
                            placeholder="যেমন: shanto@gmail.com">
                        @error('email') <span class="text-red-500 text-[10px] mt-1 block font-sans">{{ $message }}</span> @enderror
                    </div>

                    <!-- Password Input with Eye Icon -->
                    <div x-data="{ showPass: false }">
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">পাসওয়ার্ড</label>
                        <div class="relative">
                            <input 
                                :type="showPass ? 'text' : 'password'" 
                                wire:model="password"
                                @if(auth()->user()->hasRole('demo')) disabled @endif
                                class="w-full py-2 pl-3 pr-10 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-950/30 transition-all font-sans disabled:opacity-50"
                                placeholder="৮ সংখ্যার পাসওয়ার্ড">
                            <!-- Toggle Button -->
                            <button 
                                type="button" 
                                @click="showPass = !showPass"
                                @if(auth()->user()->hasRole('demo')) disabled @endif
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-emerald-600 focus:outline-none transition-colors cursor-pointer disabled:opacity-30">
                                <!-- Eye icon -->
                                <svg x-show="!showPass" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <!-- Eye slash icon -->
                                <svg x-show="showPass" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" x-cloak>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.395 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.863 7.863L21 21m-2.228-2.228l-3.65-3.65m0 0a3 3 0 11-4.243-4.243m4.242 4.242L9.88 9.88"/>
                                </svg>
                            </button>
                        </div>
                        @error('password') <span class="text-red-500 text-[10px] mt-1 block font-sans">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Role Custom Dropdown (Premium root style) -->
                <div class="relative" x-data="{ open: false, role: @entangle('role') }">
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">রোল (Role)</label>
                    <button type="button" @click="if(!{{ auth()->user()->hasRole('demo') ? 'true' : 'false' }}) open = !open" 
                            @if(auth()->user()->hasRole('demo')) disabled @endif
                            class="w-full flex items-center justify-between py-2 px-3 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-950/30 cursor-pointer transition-all font-sans disabled:opacity-50">
                        <span x-text="role === 'admin' ? 'এডমিন (Admin)' : (role === 'demo' ? 'ডেমো (Demo)' : 'ইউজার (User)')"></span>
                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    <div x-show="open" 
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         class="absolute left-0 right-0 mt-1.5 bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-gray-100 dark:border-slate-800 py-1 z-45 text-xs overflow-hidden"
                         x-cloak>
                        <button type="button" @click="role = 'user'; open = false" class="w-full text-left px-3.5 py-2 text-gray-700 dark:text-gray-200 hover:bg-emerald-50 dark:hover:bg-emerald-950/10 hover:text-emerald-700 dark:hover:text-emerald-400 font-semibold transition-all">ইউজার (User)</button>
                        <button type="button" @click="role = 'demo'; open = false" class="w-full text-left px-3.5 py-2 text-gray-700 dark:text-gray-200 hover:bg-emerald-50 dark:hover:bg-emerald-950/10 hover:text-emerald-700 dark:hover:text-emerald-400 font-semibold transition-all">ডেমো (Demo)</button>
                        <button type="button" @click="role = 'admin'; open = false" class="w-full text-left px-3.5 py-2 text-gray-700 dark:text-gray-200 hover:bg-emerald-50 dark:hover:bg-emerald-950/10 hover:text-emerald-700 dark:hover:text-emerald-400 font-semibold transition-all">এডমিন (Admin)</button>
                    </div>
                </div>

                <!-- Menu Access (Only visible if role is user or demo) -->
                @if($role === 'user' || $role === 'demo')
                    <div class="pt-2">
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-2 font-sans">মেনু অ্যাক্সেস পারমিশন</label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 max-h-48 overflow-y-auto p-3 bg-gray-50 dark:bg-slate-950 rounded-xl border border-gray-100 dark:border-slate-800">
                            @foreach($menuOptions as $key => $label)
                                <label class="flex items-center space-x-2 text-[10px] text-gray-600 dark:text-slate-300 cursor-pointer hover:text-emerald-600 dark:hover:text-emerald-400 font-sans">
                                    <input 
                                        type="checkbox" 
                                        value="{{ $key }}"
                                        wire:model="selectedPermissions"
                                        @if(auth()->user()->hasRole('demo')) disabled @endif
                                        class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 h-3.5 w-3.5 cursor-pointer disabled:opacity-50">
                                    <span>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="p-3 bg-emerald-50 dark:bg-emerald-950/10 text-emerald-800 dark:text-emerald-400 text-[10px] rounded-xl border border-emerald-100 dark:border-emerald-950 font-medium font-sans flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>এডমিন অ্যাকাউন্টের সব মেনুতে অটোমেটিক অ্যাক্সেস থাকবে।</span>
                    </div>
                @endif

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    @if(auth()->user()->hasRole('demo')) disabled @endif
                    class="w-full mt-4 py-2.5 px-4 bg-primary hover:bg-primary-light text-white text-xs font-extrabold rounded-xl shadow-md transition-all duration-150 transform hover:scale-[1.01] active:scale-[0.99] cursor-pointer font-sans disabled:bg-gray-400 dark:disabled:bg-slate-800 disabled:text-gray-200 dark:disabled:text-slate-500 disabled:cursor-not-allowed disabled:transform-none">
                    @if(auth()->user()->hasRole('demo')) ব্যবহারকারী তৈরি করা নিষ্ক্রিয় (ডেমো মোড) @else ব্যবহারকারী তৈরি করুন @endif
                </button>
            </form>
        </div>

        <!-- Section 2: Users List (Below, Full Width) -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800/80 overflow-hidden transition-colors duration-300">
            <!-- Table Header -->
            <div class="bg-primary text-white px-4 py-3 font-bold text-sm flex items-center justify-between font-sans">
                <span>ব্যবহারকারীদের তালিকা</span>
                <span class="text-xs bg-emerald-800 px-2 py-0.5 rounded-full font-normal">মোট: {{ count($users) }} জন</span>
            </div>

            <!-- Table Container (Desktop Only) -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-emerald-50 dark:bg-emerald-950/20 text-emerald-900 dark:text-emerald-300 font-semibold border-b border-gray-100 dark:border-slate-800 font-sans">
                            <th class="px-4 py-3 text-center w-16">প্রোফাইল</th>
                            <th class="px-4 py-3">ইউজার বিবরণ</th>
                            <th class="px-4 py-3 text-center font-sans">রোল (Role)</th>
                            <th class="px-4 py-3 text-center font-sans">মেনু অ্যাক্সেস কনফিগারেশন</th>
                            <th class="px-4 py-3 text-right font-sans">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800/50 font-sans">
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/20 transition-colors">
                                <!-- Profile Column -->
                                <td class="px-4 py-3 text-center">
                                    @if($user->profile_photo)
                                        <img src="{{ asset('storage/' . $user->profile_photo) }}" class="w-8 h-8 rounded-full object-cover mx-auto ring-1 ring-emerald-150 dark:ring-emerald-900">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-emerald-600/10 text-emerald-600 flex items-center justify-center font-bold text-xs uppercase mx-auto">
                                            {{ strtoupper(substr($user->name ?? 'D', 0, 1)) }}
                                        </div>
                                    @endif
                                </td>

                                <!-- User Identity Column (Format: 1. Name 2. Email 3. Creation Date) -->
                                <td class="px-4 py-3">
                                    @php
                                        $displayUsername = $user->name;
                                        if (str_contains($displayUsername, '@')) {
                                            $displayUsername = explode('@', $displayUsername)[0];
                                        }
                                    @endphp
                                    <div class="font-bold text-gray-800 dark:text-white text-sm font-sans">{{ $displayUsername }}</div>
                                    <div class="text-[10px] text-gray-400 mt-0.5 font-sans">{{ $user->email }}</div>
                                    <div class="text-[9px] text-gray-400 mt-1 italic font-sans">তৈরি: {{ $user->created_at->format('d-m-Y H:i') }}</div>
                                </td>

                                <!-- Role Column -->
                                <td class="px-4 py-3 text-center">
                                    @if($user->hasRole('admin'))
                                        <span class="inline-block px-2.5 py-0.5 bg-rose-50 dark:bg-rose-950/20 text-rose-600 dark:text-rose-400 font-bold rounded-full text-[10px] border border-rose-100 dark:border-rose-900/50 font-sans">
                                            Admin
                                        </span>
                                    @elseif($user->hasRole('demo'))
                                        <span class="inline-block px-2.5 py-0.5 bg-blue-50 dark:bg-blue-950/20 text-blue-600 dark:text-blue-400 font-bold rounded-full text-[10px] border border-blue-100 dark:border-blue-900/50 font-sans">
                                            Demo
                                        </span>
                                    @else
                                        <span class="inline-block px-2.5 py-0.5 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 font-bold rounded-full text-[10px] border border-emerald-100 dark:border-emerald-900/50 font-sans">
                                            User
                                        </span>
                                    @endif
                                </td>

                                <!-- Permissions Control Badges -->
                                <td class="px-4 py-3">
                                    @if($user->hasRole('admin'))
                                        <div class="text-[10px] text-center text-gray-400 italic font-sans">এডমিনের সর্বজনীন অ্যাক্সেস রয়েছে</div>
                                    @elseif($user->hasRole('demo'))
                                        <div class="text-[10px] text-center text-blue-500 dark:text-blue-450 italic font-sans font-medium">ডেমো ইউজারের শুধু রিড-অনলি অ্যাক্সেস রয়েছে</div>
                                    @elseif($user->email === 'admin@gmail.com')
                                        <div class="text-[10px] text-center text-emerald-600 italic font-sans">সুপার এডমিনের অ্যাক্সেস রয়েছে</div>
                                    @else
                                        <div class="flex flex-wrap gap-1.5 justify-center max-w-[480px] mx-auto">
                                            @php $hasAny = false; @endphp
                                            @foreach($menuOptions as $key => $label)
                                                @php
                                                    try {
                                                        $hasPerm = $user->hasPermissionTo($key);
                                                    } catch (\Exception $e) {
                                                        $hasPerm = false;
                                                    }
                                                @endphp
                                                @if($hasPerm)
                                                    @php $hasAny = true; @endphp
                                                    <button 
                                                        @if(auth()->user()->hasRole('demo')) disabled @else wire:click="togglePermission({{ $user->id }}, '{{ $key }}')" @endif
                                                        class="px-2 py-1 rounded text-[9px] font-bold border transition-all cursor-pointer font-sans disabled:opacity-80 disabled:cursor-not-allowed {{ $hasPerm ? 'bg-emerald-600 border-emerald-700 text-white shadow-sm hover:bg-emerald-700' : 'bg-gray-50 border-gray-200 dark:border-slate-800 text-gray-400 dark:text-slate-500 hover:bg-gray-100 dark:hover:bg-slate-850' }}">
                                                        {{ $label }}
                                                    </button>
                                                @endif
                                            @endforeach
                                            @if(!$hasAny)
                                                <div class="text-[10px] text-center text-gray-400 italic font-sans">কোন মেনু অ্যাক্সেস নেই</div>
                                            @endif
                                        </div>
                                    @endif
                                </td>

                                <!-- Actions Column -->
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($user->email === 'admin@gmail.com')
                                            <span class="text-[10px] text-emerald-600 font-bold italic font-sans pr-4">সুরক্ষিত</span>
                                        @else
                                            <!-- Login as User Button -->
                                            @if($user->id !== auth()->id())
                                                <button 
                                                    @if(auth()->user()->hasRole('demo')) disabled class="px-2 py-1.5 border border-gray-200 dark:border-slate-800 text-[10px] font-bold text-gray-400 rounded-lg cursor-not-allowed font-sans bg-gray-50 dark:bg-slate-850 opacity-50" @else wire:click="loginAsUser({{ $user->id }})" class="px-2 py-1.5 border border-emerald-200 dark:border-emerald-900/50 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 text-[10px] font-bold text-emerald-600 dark:text-emerald-400 rounded-lg transition-colors cursor-pointer font-sans" @endif
                                                    title="এই ব্যবহারকারী হিসেবে লগইন করুন">
                                                    লগইন
                                                </button>
                                            @endif

                                            <!-- Edit User Button -->
                                            <button 
                                                @if(auth()->user()->hasRole('demo')) disabled class="px-2 py-1.5 border border-gray-200 dark:border-slate-800 text-[10px] font-bold text-gray-400 rounded-lg cursor-not-allowed font-sans bg-gray-50 dark:bg-slate-850 opacity-50" @else wire:click="editUser({{ $user->id }})" class="px-2 py-1.5 border border-gray-200 dark:border-slate-700 hover:border-emerald-50 text-[10px] font-semibold text-gray-600 dark:text-slate-300 hover:text-emerald-600 bg-gray-50 dark:bg-slate-800 rounded-lg transition-colors cursor-pointer font-sans" @endif
                                                title="তথ্য পরিবর্তন করুন">
                                                এডিট
                                            </button>

                                            @if($user->id !== auth()->id())
                                                <!-- Delete User Button -->
                                                <button 
                                                    @if(auth()->user()->hasRole('demo')) disabled class="px-2 py-1.5 border border-gray-200 dark:border-slate-800 text-[10px] font-bold text-gray-400 rounded-lg cursor-not-allowed font-sans bg-gray-50 dark:bg-slate-850 opacity-50" @else wire:click="deleteUser({{ $user->id }})" class="px-2 py-1.5 border border-red-200 dark:border-red-900/50 hover:bg-red-50 dark:hover:bg-red-950/20 text-[10px] font-bold text-red-600 dark:text-red-400 rounded-lg transition-colors cursor-pointer font-sans" @endif
                                                    title="ব্যবহারকারী মুছে ফেলুন">
                                                    ডিলিট
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card List View (Mobile Only Box Type Layout) -->
            <div class="block md:hidden divide-y divide-gray-100 dark:divide-slate-800/50 font-sans">
                @foreach($users as $user)
                    <div class="p-4 space-y-3 font-sans">
                        <!-- Top Header Card Area -->
                        <div class="flex items-center justify-between">
                            <div>
                                @php
                                    $displayUsernameMob = $user->name;
                                    if (str_contains($displayUsernameMob, '@')) {
                                        $displayUsernameMob = explode('@', $displayUsernameMob)[0];
                                    }
                                @endphp
                                <div class="font-bold text-gray-800 dark:text-white text-sm font-sans">{{ $displayUsernameMob }}</div>
                                <div class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5 font-sans">{{ $user->email }}</div>
                            </div>
                            <div>
                                @if($user->hasRole('admin'))
                                    <span class="inline-block px-2.5 py-0.5 bg-rose-50 dark:bg-rose-950/20 text-rose-600 dark:text-rose-400 font-bold rounded-full text-[10px] border border-rose-100 dark:border-rose-900/50 font-sans">Admin</span>
                                @elseif($user->hasRole('demo'))
                                    <span class="inline-block px-2.5 py-0.5 bg-blue-50 dark:bg-blue-950/20 text-blue-600 dark:text-blue-400 font-bold rounded-full text-[10px] border border-blue-100 dark:border-blue-900/50 font-sans">Demo</span>
                                @else
                                    <span class="inline-block px-2.5 py-0.5 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 font-bold rounded-full text-[10px] border border-emerald-100 dark:border-emerald-900/50 font-sans">User</span>
                                @endif
                            </div>
                        </div>

                        <!-- Menu Access Badge List -->
                        <div class="space-y-1.5">
                            <div class="text-[10px] font-bold text-gray-500 dark:text-gray-400 font-sans">মেনু অ্যাক্সেস পারমিশন:</div>
                            @if($user->hasRole('admin'))
                                <div class="text-[10px] text-gray-400 dark:text-gray-500 italic font-sans">এডমিনের সর্বজনীন অ্যাক্সেস রয়েছে</div>
                            @elseif($user->hasRole('demo'))
                                <div class="text-[10px] text-blue-500 dark:text-blue-450 italic font-sans">ডেমো ইউজারের শুধু রিড-অনলি অ্যাক্সেস রয়েছে</div>
                            @elseif($user->email === 'admin@gmail.com')
                                <div class="text-[10px] text-emerald-600 italic font-sans">সুপার এডমিনের অ্যাক্সেস রয়েছে</div>
                            @else
                                <div class="flex flex-wrap gap-1">
                                    @php $hasAnyMobile = false; @endphp
                                    @foreach($menuOptions as $key => $label)
                                        @php
                                            try {
                                                $hasPerm = $user->hasPermissionTo($key);
                                            } catch (\Exception $e) {
                                                $hasPerm = false;
                                            }
                                        @endphp
                                        @if($hasPerm)
                                            @php $hasAnyMobile = true; @endphp
                                            <span class="px-1.5 py-0.5 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 font-bold rounded text-[8px] border border-emerald-100 dark:border-emerald-900/50 font-sans">
                                                {{ $label }}
                                            </span>
                                        @endif
                                    @endforeach
                                    @if(!$hasAnyMobile)
                                        <div class="text-[10px] text-gray-400 italic font-sans">কোন মেনু অ্যাক্সেস নেই</div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Bottom Section with Creation Info and Actions -->
                        <div class="flex items-center justify-between pt-2.5 border-t border-gray-100 dark:border-slate-800/80">
                            <div class="text-[9px] text-gray-400 dark:text-gray-500 font-sans">তৈরি: {{ $user->created_at->format('d-m-Y H:i') }}</div>
                            <div class="flex items-center gap-1.5">
                                @if($user->email === 'admin@gmail.com')
                                    <span class="text-[9px] text-emerald-600 font-bold italic font-sans">সুরক্ষিত</span>
                                @else
                                    @if($user->id !== auth()->id())
                                        <button @if(auth()->user()->hasRole('demo')) disabled class="px-2 py-1 border border-gray-200 dark:border-slate-850 text-gray-400 text-[10px] font-bold rounded-lg cursor-not-allowed bg-gray-50 dark:bg-slate-800 opacity-50" @else wire:click="loginAsUser({{ $user->id }})" class="px-2 py-1 border border-emerald-200 dark:border-emerald-900/30 text-[10px] font-bold text-emerald-600 dark:text-emerald-400 rounded-lg cursor-pointer font-sans transition-all" @endif>লগইন</button>
                                    @endif
                                    <button @if(auth()->user()->hasRole('demo')) disabled class="px-2 py-1 border border-gray-200 dark:border-slate-850 text-gray-400 text-[10px] font-bold rounded-lg cursor-not-allowed bg-gray-50 dark:bg-slate-800 opacity-50" @else wire:click="editUser({{ $user->id }})" class="px-2 py-1 border border-gray-300 dark:border-slate-700 text-[10px] font-semibold text-gray-655 dark:text-slate-300 bg-gray-50 dark:bg-slate-800 rounded-lg cursor-pointer font-sans transition-all" @endif>এডিট</button>
                                    @if($user->id !== auth()->id())
                                        <button @if(auth()->user()->hasRole('demo')) disabled class="px-2 py-1 border border-gray-200 dark:border-slate-850 text-gray-400 text-[10px] font-bold rounded-lg cursor-not-allowed bg-gray-50 dark:bg-slate-800 opacity-50" @else wire:click="deleteUser({{ $user->id }})" class="px-2 py-1 border border-red-200 dark:border-red-900/30 text-[10px] font-bold text-red-600 dark:text-red-400 rounded-lg cursor-pointer font-sans transition-all" @endif>ডিলিট</button>
                                    @else
                                        <span class="text-[10px] text-gray-400 dark:text-gray-500 italic pr-2 font-sans">লগড-ইন</span>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination Links -->
            <div class="px-4 py-3 bg-gray-50 dark:bg-slate-900 border-t border-gray-100 dark:border-slate-800 font-sans">
                {{ $users->links() }}
            </div>
        </div>

    </div>

    <!-- Edit User Modal (Renders when editingUserId is set with Alpine persistent smooth state) -->
    <div x-data="{ open: @entangle('editingUserId') }"
         x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-250"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click.self="open = null; $wire.cancelEdit()"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
         x-cloak>
         
         <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-xl w-full border border-gray-100 dark:border-slate-800 shadow-2xl p-6 relative flex flex-col max-h-[90vh]"
              x-show="open"
              x-transition:enter="transition ease-out duration-300 transform"
              x-transition:enter-start="opacity-0 translate-y-4 scale-95"
              x-transition:enter-end="opacity-100 translate-y-0 scale-100"
              x-transition:leave="transition ease-in duration-250 transform"
              x-transition:leave-start="opacity-100 scale-100 translate-y-0"
              x-transition:leave-end="opacity-0 scale-95 translate-y-4">
              
             <!-- Close Icon button -->
             <button type="button" @click="open = null; $wire.cancelEdit()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors focus:outline-none cursor-pointer">
                 <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                 </svg>
             </button>

             <h3 class="text-base font-bold text-gray-800 dark:text-white mb-4 border-b border-gray-100 dark:border-slate-800 pb-2 font-sans">
                 ব্যবহারকারীর তথ্য সংশোধন করুন
             </h3>
             
             <form wire:submit.prevent="updateUser" class="space-y-4 overflow-y-auto flex-grow pr-1">
                 <!-- Username/Name Input -->
                 <div>
                     <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">নাম (Name)</label>
                     <input 
                         type="text" 
                         wire:model="editName"
                         class="w-full py-2 px-3 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-250/20 dark:focus:ring-emerald-950/30 transition-all font-sans"
                         placeholder="যেমন: Shanto">
                     @error('editName') <span class="text-red-500 text-[10px] mt-1 block font-sans">{{ $message }}</span> @enderror
                 </div>

                 <!-- Email Input -->
                 <div>
                     <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">ইউজারনেম / ইমেইল</label>
                     <input 
                         type="text" 
                         wire:model="editEmail"
                         class="w-full py-2 px-3 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-250/20 dark:focus:ring-emerald-950/30 transition-all font-sans"
                         placeholder="যেমন: shanto@gmail.com">
                     @error('editEmail') <span class="text-red-500 text-[10px] mt-1 block font-sans">{{ $message }}</span> @enderror
                 </div>

                 <!-- Password Input with Eye Icon -->
                 <div x-data="{ showPass: false }">
                     <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">পাসওয়ার্ড (পরিবর্তন না করতে চাইলে খালি রাখুন)</label>
                     <div class="relative">
                         <input 
                             :type="showPass ? 'text' : 'password'" 
                             wire:model="editPassword"
                             class="w-full py-2 pl-3 pr-10 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-250/20 dark:focus:ring-emerald-950/30 transition-all font-sans"
                             placeholder="৮ সংখ্যার পাসওয়ার্ড">
                         <!-- Toggle Button -->
                         <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-emerald-600 focus:outline-none transition-colors cursor-pointer">
                             <!-- Eye icon -->
                             <svg x-show="!showPass" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                 <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                 <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                             </svg>
                             <!-- Eye slash icon -->
                             <svg x-show="showPass" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" x-cloak>
                                 <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.395 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.863 7.863L21 21m-2.228-2.228l-3.65-3.65m0 0a3 3 0 11-4.243-4.243m4.242 4.242L9.88 9.88"/>
                             </svg>
                         </button>
                     </div>
                     @error('editPassword') <span class="text-red-500 text-[10px] mt-1 block font-sans">{{ $message }}</span> @enderror
                 </div>

                 <!-- Role Dropdown -->
                 <div class="relative" x-data="{ open: false, role: @entangle('editRole') }">
                     <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">রোল (Role)</label>
                     <button type="button" @click="open = !open" 
                             class="w-full flex items-center justify-between py-2 px-3 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-250/20 dark:focus:ring-emerald-950/30 cursor-pointer transition-all font-sans text-left">
                         <span x-text="role === 'admin' ? 'এডমিন (Admin)' : (role === 'demo' ? 'ডেমো (Demo)' : 'ইউজার (User)')"></span>
                         <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                         </svg>
                     </button>
                     
                     <div x-show="open" 
                          @click.away="open = false"
                          x-transition:enter="transition ease-out duration-100"
                          x-transition:enter-start="transform opacity-0 scale-95"
                          x-transition:enter-end="transform opacity-100 scale-100"
                          class="absolute left-0 right-0 mt-1.5 bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-gray-100 dark:border-slate-800 py-1 z-55 text-xs overflow-hidden"
                          x-cloak>
                         <button type="button" @click="role = 'user'; open = false" class="w-full text-left px-3.5 py-2 text-gray-700 dark:text-gray-200 hover:bg-emerald-50 dark:hover:bg-emerald-950/10 hover:text-emerald-700 dark:hover:text-emerald-400 font-semibold transition-all font-sans">ইউজার (User)</button>
                         <button type="button" @click="role = 'demo'; open = false" class="w-full text-left px-3.5 py-2 text-gray-700 dark:text-gray-200 hover:bg-emerald-50 dark:hover:bg-emerald-950/10 hover:text-emerald-700 dark:hover:text-emerald-400 font-semibold transition-all font-sans">ডেমো (Demo)</button>
                         <button type="button" @click="role = 'admin'; open = false" class="w-full text-left px-3.5 py-2 text-gray-700 dark:text-gray-200 hover:bg-emerald-50 dark:hover:bg-emerald-950/10 hover:text-emerald-700 dark:hover:text-emerald-400 font-semibold transition-all font-sans">এডমিন (Admin)</button>
                     </div>
                 </div>

                 <!-- Permissions Checklist (Only visible if role is user or demo) -->
                 <div x-show="editRole === 'user' || editRole === 'demo'" class="pt-2">
                     <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-2 font-sans">মেনু অ্যাক্সেস পারমিশন</label>
                     <div class="grid grid-cols-2 gap-3 max-h-40 overflow-y-auto p-3 bg-gray-50 dark:bg-slate-950 rounded-xl border border-gray-100 dark:border-slate-800">
                         @foreach($menuOptions as $key => $label)
                             <label class="flex items-center space-x-2 text-[10px] text-gray-600 dark:text-slate-300 cursor-pointer hover:text-emerald-600 dark:hover:text-emerald-400 font-sans">
                                 <input 
                                     type="checkbox" 
                                     value="{{ $key }}"
                                     wire:model="editSelectedPermissions"
                                     class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 h-3.5 w-3.5 cursor-pointer">
                                 <span>{{ $label }}</span>
                             </label>
                         @endforeach
                     </div>
                 </div>

                 <!-- Modal Actions -->
                 <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100 dark:border-slate-800">
                     <button type="button" @click="open = null; $wire.cancelEdit()" class="px-4 py-2 bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-slate-200 text-xs font-semibold rounded-xl cursor-pointer hover:bg-gray-300 transition-all font-sans">বাতিল</button>
                     <button type="submit" class="px-6 py-2 bg-primary text-white text-xs font-bold rounded-xl cursor-pointer shadow-md hover:bg-emerald-600 transition-all font-sans">সংরক্ষণ করুন</button>
                 </div>
             </form>
         </div>
     </div>
</div>
