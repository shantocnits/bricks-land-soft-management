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
            <span class="text-lg">🎉</span>
            <span class="font-medium">{{ session('message') }}</span>
        </div>
    @endif

    <div class="space-y-6">
        
        <!-- Section 1: Create User Form (Full Width) -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800/80 p-6 transition-colors duration-300">
            <h3 class="text-base font-bold text-gray-800 dark:text-white mb-4 border-b border-gray-100 dark:border-slate-800 pb-2 font-sans">
                নতুন ব্যবহারকারী তৈরি করুন
            </h3>

            <form wire:submit.prevent="createUser" class="space-y-4">
                <!-- Name Input -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">ইউজারনেম</label>
                    <input 
                        type="text" 
                        wire:model="name"
                        class="w-full py-2 px-3 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-950/30 transition-all font-sans"
                        placeholder="যেমন: operator1">
                    @error('name') <span class="text-red-500 text-[10px] mt-1 block font-sans">{{ $message }}</span> @enderror
                </div>

                <!-- Email Input -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">ইমেইল</label>
                    <input 
                        type="email" 
                        wire:model="email"
                        class="w-full py-2 px-3 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-950/30 transition-all font-sans"
                        placeholder="যেমন: operator1@example.com">
                    @error('email') <span class="text-red-500 text-[10px] mt-1 block font-sans">{{ $message }}</span> @enderror
                </div>

                <!-- Password Input -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">পাসওয়ার্ড</label>
                    <input 
                        type="password" 
                        wire:model="password"
                        class="w-full py-2 px-3 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-950/30 transition-all font-sans"
                        placeholder="কমপক্ষে ৬ অক্ষরের পাসওয়ার্ড">
                    @error('password') <span class="text-red-500 text-[10px] mt-1 block font-sans">{{ $message }}</span> @enderror
                </div>

                <!-- Role Custom Dropdown (Premium root style) -->
                <div class="relative" x-data="{ open: false, role: @entangle('role') }">
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">রোল (Role)</label>
                    <button type="button" @click="open = !open" 
                            class="w-full flex items-center justify-between py-2 px-3 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-950/30 cursor-pointer transition-all font-sans">
                        <span x-text="role === 'admin' ? 'এডমিন (Admin)' : 'ইউজার (User)'"></span>
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
                        <button type="button" @click="role = 'admin'; open = false" class="w-full text-left px-3.5 py-2 text-gray-700 dark:text-gray-200 hover:bg-emerald-50 dark:hover:bg-emerald-950/10 hover:text-emerald-700 dark:hover:text-emerald-400 font-semibold transition-all">এডমিন (Admin)</button>
                    </div>
                </div>

                <!-- Menu Access (Only visible if role is user) -->
                @if($role === 'user')
                    <div class="pt-2">
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-2 font-sans">মেনু অ্যাক্সেস পারমিশন</label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 max-h-48 overflow-y-auto p-3 bg-gray-50 dark:bg-slate-950 rounded-xl border border-gray-100 dark:border-slate-850">
                            @foreach($menuOptions as $key => $label)
                                <label class="flex items-center space-x-2 text-[10px] text-gray-600 dark:text-slate-300 cursor-pointer hover:text-emerald-600 dark:hover:text-emerald-400 font-sans">
                                    <input 
                                        type="checkbox" 
                                        value="{{ $key }}"
                                        wire:model="selectedPermissions"
                                        class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 h-3.5 w-3.5 cursor-pointer">
                                    <span>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="p-3 bg-emerald-50 dark:bg-emerald-950/10 text-emerald-800 dark:text-emerald-400 text-[10px] rounded-xl border border-emerald-100 dark:border-emerald-950 font-medium font-sans">
                        💡 এডমিন অ্যাকাউন্টের সব মেনুতে অটোমেটিক অ্যাক্সেস থাকবে।
                    </div>
                @endif

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full mt-4 py-2.5 px-4 bg-primary hover:bg-primary-light text-white text-xs font-extrabold rounded-xl shadow-md transition-all duration-150 transform hover:scale-[1.01] active:scale-[0.99] cursor-pointer font-sans">
                    ব্যবহারকারী তৈরি করুন
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
                            <th class="px-4 py-3">ইউজার বিবরণ</th>
                            <th class="px-4 py-3 text-center font-sans">রোল (Role)</th>
                            <th class="px-4 py-3 text-center font-sans">মেনু অ্যাক্সেস কনফিগারেশন</th>
                            <th class="px-4 py-3 text-right font-sans">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800/50 font-sans">
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/20 transition-colors">
                                <!-- User Identity -->
                                <td class="px-4 py-3">
                                    <div class="font-bold text-gray-800 dark:text-white text-sm font-sans">{{ $user->name }}</div>
                                    <div class="text-[10px] text-gray-400 mt-0.5 font-sans">{{ $user->email }}</div>
                                    <div class="text-[9px] text-gray-400 mt-1 italic font-sans">তৈরি: {{ $user->created_at->format('d-m-Y H:i') }}</div>
                                </td>

                                <!-- Role Column -->
                                <td class="px-4 py-3 text-center">
                                    @if($user->role === 'admin')
                                        <span class="inline-block px-2.5 py-0.5 bg-rose-50 dark:bg-rose-950/20 text-rose-600 dark:text-rose-400 font-bold rounded-full text-[10px] border border-rose-100 dark:border-rose-900/50 font-sans">
                                            Admin
                                        </span>
                                    @else
                                        <span class="inline-block px-2.5 py-0.5 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 font-bold rounded-full text-[10px] border border-emerald-100 dark:border-emerald-900/50 font-sans">
                                            User
                                        </span>
                                    @endif
                                </td>

                                <!-- Permissions Control Badges -->
                                <td class="px-4 py-3">
                                    @if($user->role === 'admin')
                                        <div class="text-[10px] text-center text-gray-400 italic font-sans">এডমিনের সর্বজনীন অ্যাক্সেস রয়েছে</div>
                                    @else
                                        <div class="flex flex-wrap gap-1.5 justify-center max-w-[480px] mx-auto">
                                            @foreach($menuOptions as $key => $label)
                                                @php
                                                    $hasPerm = is_array($user->permissions) && in_array($key, $user->permissions);
                                                @endphp
                                                <button 
                                                    wire:click="togglePermission({{ $user->id }}, '{{ $key }}')"
                                                    class="px-2 py-1 rounded text-[9px] font-bold border transition-all cursor-pointer font-sans {{ $hasPerm ? 'bg-emerald-600 border-emerald-700 text-white shadow-sm hover:bg-emerald-700' : 'bg-gray-50 border-gray-200 dark:border-slate-800 text-gray-400 dark:text-slate-500 hover:bg-gray-100 dark:hover:bg-slate-850' }}">
                                                    {{ $label }}
                                                </button>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>

                                <!-- Actions Column -->
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Role Upgrade/Downgrade Button -->
                                        @if($user->id !== auth()->id())
                                            <button 
                                                wire:click="toggleAdmin({{ $user->id }})" 
                                                class="px-2 py-1.5 border border-gray-200 dark:border-slate-700 hover:border-emerald-500 dark:hover:border-emerald-500 text-[10px] font-semibold text-gray-600 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 bg-gray-50 dark:bg-slate-800 rounded-lg transition-colors cursor-pointer font-sans"
                                                title="রোল পরিবর্তন করুন">
                                                🔄 রোল পরিবর্তন
                                            </button>

                                            <!-- Delete User Button -->
                                            <button 
                                                wire:click="deleteUser({{ $user->id }})" 
                                                class="px-2 py-1.5 border border-red-200 dark:border-red-900/50 hover:bg-red-50 dark:hover:bg-red-950/20 text-[10px] font-bold text-red-600 dark:text-red-400 rounded-lg transition-colors cursor-pointer font-sans"
                                                title="ব্যবহারকারী ডিলিট করুন">
                                                🗑️ ডিলিট
                                            </button>
                                        @else
                                            <span class="text-[10px] text-gray-400 italic font-sans pr-4">লগড-ইন ইউজার</span>
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
                                <div class="font-bold text-gray-800 dark:text-white text-sm font-sans">{{ $user->name }}</div>
                                <div class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5 font-sans">{{ $user->email }}</div>
                            </div>
                            <div>
                                @if($user->role === 'admin')
                                    <span class="inline-block px-2.5 py-0.5 bg-rose-50 dark:bg-rose-950/20 text-rose-600 dark:text-rose-400 font-bold rounded-full text-[10px] border border-rose-100 dark:border-rose-900/50 font-sans">Admin</span>
                                @else
                                    <span class="inline-block px-2.5 py-0.5 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 font-bold rounded-full text-[10px] border border-emerald-100 dark:border-emerald-900/50 font-sans">User</span>
                                @endif
                            </div>
                        </div>

                        <!-- Menu Access Badge List -->
                        <div class="space-y-1.5">
                            <div class="text-[10px] font-bold text-gray-500 dark:text-gray-400 font-sans">মেনু অ্যাক্সেস পারমিশন:</div>
                            @if($user->role === 'admin')
                                <div class="text-[10px] text-gray-400 dark:text-gray-500 italic font-sans">এডমিনের সর্বজনীন অ্যাক্সেস রয়েছে</div>
                            @else
                                <div class="flex flex-wrap gap-1">
                                    @foreach($menuOptions as $key => $label)
                                        @php
                                            $hasPerm = is_array($user->permissions) && in_array($key, $user->permissions);
                                        @endphp
                                        <button 
                                            wire:click="togglePermission({{ $user->id }}, '{{ $key }}')"
                                            class="px-1.5 py-0.5 rounded text-[8px] font-bold border transition-all cursor-pointer font-sans {{ $hasPerm ? 'bg-emerald-600 border-emerald-700 text-white shadow-sm' : 'bg-gray-50 dark:bg-slate-950 border-gray-250/15 text-gray-400 dark:text-slate-500' }}">
                                            {{ $label }}
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Bottom Section with Creation Info and Actions -->
                        <div class="flex items-center justify-between pt-2.5 border-t border-gray-100 dark:border-slate-800/80">
                            <div class="text-[9px] text-gray-400 dark:text-gray-500 font-sans">তৈরি: {{ $user->created_at->format('d-m-Y H:i') }}</div>
                            <div class="flex items-center gap-1.5">
                                @if($user->id !== auth()->id())
                                    <button wire:click="toggleAdmin({{ $user->id }})" class="px-2.5 py-1 border border-gray-250 dark:border-slate-700 text-[10px] font-semibold text-gray-600 dark:text-slate-300 bg-gray-50 dark:bg-slate-800 rounded-lg cursor-pointer font-sans transition-all">🔄 রোল</button>
                                    <button wire:click="deleteUser({{ $user->id }})" class="px-2.5 py-1 border border-red-200 dark:border-red-900/30 text-[10px] font-bold text-red-600 dark:text-red-400 rounded-lg cursor-pointer font-sans transition-all">🗑️ ডিলিট</button>
                                @else
                                    <span class="text-[10px] text-gray-400 dark:text-gray-500 italic pr-2 font-sans">লগড-ইন</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>

    </div>
</div>
