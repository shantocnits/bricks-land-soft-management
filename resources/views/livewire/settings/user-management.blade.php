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

    <!-- Error Alert -->
    @if (session()->has('error'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 4000)"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="p-4 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-900 text-red-800 dark:text-red-400 rounded-2xl flex items-center gap-3 text-sm shadow-sm font-sans"
             x-cloak>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Create User Form Section -->
    @if($showCreateForm && !$editingId)
        <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-3xl p-6 shadow-sm transition-all duration-300"
             x-data x-init="$el.scrollIntoView({ behavior: 'smooth', block: 'start' })">
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-800 pb-4 mb-5">
                <h3 class="font-bold text-sm text-gray-800 dark:text-white">নতুন ব্যবহারকারী যুক্ত করুন</h3>
                <button wire:click="toggleCreateForm" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Name -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 mb-1.5 uppercase">নাম</label>
                    <input type="text" wire:model="name" placeholder="যেমন: মোঃ মানিক মিয়া"
                           class="w-full py-2.5 px-4 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-250/20 dark:focus:ring-emerald-950/30 transition-all font-semibold">
                    @error('name') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 mb-1.5 uppercase">ইমেইল / ইউজারনেম</label>
                    <input type="email" wire:model="email" placeholder="যেমন: owner@gmail.com"
                           class="w-full py-2.5 px-4 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-250/20 dark:focus:ring-emerald-950/30 transition-all font-semibold">
                    @error('email') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 mb-1.5 uppercase">পাসওয়ার্ড</label>
                    <input type="password" wire:model="password" placeholder="কমপক্ষে ৬ অক্ষরের পাসওয়ার্ড"
                           class="w-full py-2.5 px-4 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-250/20 dark:focus:ring-emerald-950/30 transition-all font-semibold">
                    @error('password') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Role Dropdown -->
                <div class="relative" x-data="{ open: false, role: @entangle('role') }">
                    <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 mb-1.5 uppercase">ইউজার টাইপ (Role)</label>
                    <button type="button" @click="open = !open"
                            class="w-full flex items-center justify-between py-2.5 px-4 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none transition-all font-semibold">
                        <span x-text="role === 'admin' ? 'এডমিন (Admin)' : (role === 'owner' ? 'মালিক (Owner)' : (role === 'operator' ? 'অপারেটর (Operator)' : 'ইউজার (User)'))"></span>
                        <svg class="w-4 h-4 text-emerald-700 dark:text-emerald-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition class="absolute left-0 right-0 mt-1.5 bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-gray-100 dark:border-slate-800 py-1 z-50 text-xs overflow-hidden" x-cloak>
                        <button type="button" @click="role = 'admin'; open = false" class="w-full text-left px-4 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold">এডমিন (Admin)</button>
                        <button type="button" @click="role = 'owner'; open = false" class="w-full text-left px-4 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold">মালিক (Owner)</button>
                        <button type="button" @click="role = 'operator'; open = false" class="w-full text-left px-4 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold">অপারেটর (Operator)</button>
                        <button type="button" @click="role = 'user'; open = false" class="w-full text-left px-4 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold">ইউজার (User)</button>
                    </div>
                    @error('role') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="col-span-1 md:col-span-2 flex gap-2 justify-end pt-2">
                    <button type="button" wire:click="resetForm"
                            class="py-2.5 px-6 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 text-gray-700 dark:text-slate-300 text-xs font-bold rounded-xl transition-all cursor-pointer">
                        ফাঁকা করুন
                    </button>
                    <button type="submit"
                            class="py-2.5 px-8 bg-primary hover:bg-primary-light text-white text-xs font-bold rounded-xl transition-all shadow-md cursor-pointer">
                        ইউজার তৈরি করুন
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- Table Card -->
    <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-3xl p-6 shadow-sm overflow-hidden flex flex-col transition-colors duration-300">
        
        <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-800 pb-4 mb-4">
            <h3 class="font-bold text-sm text-gray-800 dark:text-white">ব্যবহারকারীদের তালিকা</h3>
            @if(!$showCreateForm)
                <button wire:click="toggleCreateForm"
                        class="px-4 py-2 bg-primary hover:bg-primary-light text-white text-xs font-bold rounded-xl transition-all shadow-md flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    <span>অ্যাড ইউজার</span>
                </button>
            @endif
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-emerald-50 dark:bg-emerald-950/20 text-emerald-900 dark:text-emerald-300 font-bold border-b border-gray-100 dark:border-slate-800">
                        <th class="px-4 py-3 text-center w-14">#</th>
                        <th class="px-4 py-3">নাম</th>
                        <th class="px-4 py-3">ইমেইল</th>
                        <th class="px-4 py-3 text-center">রোল</th>
                        <th class="px-4 py-3 text-center w-24">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800/80">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-950/30 text-gray-700 dark:text-slate-200 transition-all">
                            <td class="px-4 py-3.5 text-center font-bold text-gray-400">#{{ $user->id }}</td>
                            <td class="px-4 py-3.5 font-bold">{{ $user->name }}</td>
                            <td class="px-4 py-3.5 font-semibold text-gray-500 dark:text-gray-400 font-mono text-[10px]">{{ $user->email }}</td>
                            <td class="px-4 py-3.5 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold 
                                    @if($user->role === 'admin') bg-red-50 text-red-700 dark:bg-red-950/20 dark:text-red-400
                                    @elseif($user->role === 'owner') bg-orange-50 text-orange-700 dark:bg-orange-950/20 dark:text-orange-400
                                    @elseif($user->role === 'operator') bg-blue-50 text-blue-700 dark:bg-blue-950/20 dark:text-blue-400
                                    @else bg-gray-100 text-gray-700 dark:bg-slate-800 dark:text-slate-300 @endif">
                                    @if($user->role === 'admin') এডমিন
                                    @elseif($user->role === 'owner') মালিক
                                    @elseif($user->role === 'operator') অপারেটর
                                    @else ইউজার @endif
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <div class="flex justify-center gap-1.5">
                                    <!-- Edit Button -->
                                    <button wire:click="edit({{ $user->id }})"
                                            class="p-1.5 text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-950/35 rounded-lg transition-colors cursor-pointer"
                                            title="এডিট">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/>
                                        </svg>
                                    </button>
                                    <!-- Delete Button -->
                                    @if($user->id !== auth()->id())
                                        <button onclick="confirm('আপনি কি এই ইউজারটি মুছে ফেলতে চান?') || event.stopImmediatePropagation()"
                                                wire:click="delete({{ $user->id }})"
                                                class="p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-950/35 rounded-lg transition-colors cursor-pointer"
                                                title="ডিলিট">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-400 dark:text-gray-500 italic text-xs">কোনো ইউজার পাওয়া যায়নি।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit User Modal — smooth open/close, outside click closes, close icon -->
    <div
        x-data="{ open: false }"
        x-init="$watch('open', val => { if (!val) { setTimeout(() => $wire.resetForm(), 250); } })"
        x-effect="open = !!$wire.editingId"
        x-show="open"
        @click.self="open = false"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-250"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
        x-cloak>

        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-250 transform"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-4"
            class="bg-white dark:bg-slate-900 rounded-3xl max-w-lg w-full border border-gray-100 dark:border-slate-800 shadow-2xl p-6 relative max-h-[90vh] overflow-y-auto">

            <!-- Close Icon -->
            <button type="button" @click="open = false"
                    class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <h3 class="text-sm font-bold text-gray-800 dark:text-white mb-5 border-b border-gray-100 dark:border-slate-800 pb-3">
                ব্যবহারকারীর তথ্য সংশোধন
            </h3>

            <form wire:submit.prevent="save" class="space-y-4">
                <!-- Name -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 mb-1.5 uppercase">নাম</label>
                    <input type="text" wire:model="name" placeholder="যেমন: মোঃ মানিক মিয়া"
                           class="w-full py-2.5 px-4 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-250/20 dark:focus:ring-emerald-950/30 transition-all font-semibold">
                    @error('name') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 mb-1.5 uppercase">ইমেইল / ইউজারনেম</label>
                    <input type="email" wire:model="email" placeholder="যেমন: owner@gmail.com"
                           class="w-full py-2.5 px-4 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-250/20 dark:focus:ring-emerald-950/30 transition-all font-semibold">
                    @error('email') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Password (optional) -->
                <div x-data="{ showPass: false }">
                    <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 mb-1.5 uppercase">
                        পাসওয়ার্ড <span class="font-normal normal-case text-gray-400">(পরিবর্তন না করলে খালি রাখুন)</span>
                    </label>
                    <div class="relative">
                        <input :type="showPass ? 'text' : 'password'" wire:model="password" placeholder="কমপক্ষে ৬ অক্ষর"
                               class="w-full py-2.5 pl-4 pr-10 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-250/20 dark:focus:ring-emerald-950/30 transition-all font-semibold">
                        <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-emerald-600 focus:outline-none transition-colors">
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
                    @error('password') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Role -->
                <div class="relative" x-data="{ open: false, role: @entangle('role') }">
                    <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 mb-1.5 uppercase">ইউজার টাইপ (Role)</label>
                    <button type="button" @click="open = !open"
                            class="w-full flex items-center justify-between py-2.5 px-4 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none transition-all font-semibold text-left">
                        <span x-text="role === 'admin' ? 'এডমিন (Admin)' : (role === 'owner' ? 'মালিক (Owner)' : (role === 'operator' ? 'অপারেটর (Operator)' : 'ইউজার (User)'))"></span>
                        <svg class="w-4 h-4 text-emerald-700 dark:text-emerald-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition
                         class="absolute left-0 right-0 mt-1.5 bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-gray-100 dark:border-slate-800 py-1 z-50 text-xs overflow-hidden" x-cloak>
                        <button type="button" @click="role = 'admin'; open = false" class="w-full text-left px-4 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold">এডমিন (Admin)</button>
                        <button type="button" @click="role = 'owner'; open = false" class="w-full text-left px-4 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold">মালিক (Owner)</button>
                        <button type="button" @click="role = 'operator'; open = false" class="w-full text-left px-4 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold">অপারেটর (Operator)</button>
                        <button type="button" @click="role = 'user'; open = false" class="w-full text-left px-4 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold">ইউজার (User)</button>
                    </div>
                    @error('role') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Modal Actions -->
                <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100 dark:border-slate-800">
                    <button type="button" @click="open = false"
                            class="px-4 py-2 bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-slate-200 text-xs font-semibold rounded-xl cursor-pointer hover:bg-gray-200 transition-all">
                        বাতিল
                    </button>
                    <button type="submit"
                            class="px-6 py-2 bg-primary hover:bg-primary-light text-white text-xs font-bold rounded-xl cursor-pointer shadow-md transition-all">
                        সংরক্ষণ করুন
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
