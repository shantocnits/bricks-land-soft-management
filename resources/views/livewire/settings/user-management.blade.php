<div class="space-y-6">
    <!-- Header banner card -->
    <div class="flex items-start gap-4 p-4 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/50 rounded-2xl">
        <div class="flex-shrink-0 p-2 bg-emerald-600 text-white rounded-xl flex items-center justify-center">
            <!-- User Icon -->
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A11.386 11.386 0 0110.089 21c-2.243 0-4.352-.64-6.136-1.759a3.33 3.33 0 01-1.737-2.98V15.75c0-1.35.535-2.572 1.4-3.47m16.8 1.94c0-.772-.119-1.52-.338-2.222m-13.8 2.222a4.47 4.47 0 01-1.127-3.086c0-2.485 2.015-4.5 4.5-4.5s4.5 2.015 4.5 4.5a4.47 4.47 0 01-1.127 3.086M15 7.5a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
            </svg>
        </div>
        <div>
            <h4 class="font-bold text-gray-800 dark:text-white text-sm">সফটওয়্যার ইউজার</h4>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">সিস্টেম ব্যবহারের জন্য নতুন ব্যবহারকারী তৈরি করুন অথবা বিদ্যমান ব্যবহারকারীদের রোল ও পাসওয়ার্ড পরিবর্তন করুন।</p>
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

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 p-4 rounded-2xl border border-gray-150 dark:border-slate-800 shadow-sm transition-colors duration-300">
        <!-- Search bar -->
        <div class="relative w-full sm:w-64">
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </span>
            <input type="text" wire:model.live="search"
                   class="w-full py-2 pl-10 pr-4 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-950/30 transition-all font-sans"
                   placeholder="ব্যবহারকারী খুঁজুন...">
        </div>

        <!-- Add User Button -->
        <button type="button" wire:click="openAddModal"
                class="px-5 py-2.5 bg-primary hover:bg-primary-light text-white text-xs font-bold rounded-xl transition-all shadow-md active:scale-95 cursor-pointer text-center font-sans flex items-center gap-2 justify-center">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            নতুন ব্যবহারকারী যুক্ত করুন
        </button>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl p-6 shadow-sm transition-colors duration-300">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-slate-950/50 border-b border-gray-150 dark:border-slate-800 text-gray-500 dark:text-slate-400 font-bold font-sans">
                        <th class="px-4 py-3.5">ব্যবহারকারীর নাম</th>
                        <th class="px-4 py-3.5">ইমেইল / ইউজারনেম</th>
                        <th class="px-4 py-3.5 text-center">ইউজার টাইপ (Role)</th>
                        <th class="px-4 py-3.5 text-right">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800/80 font-sans">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50/40 dark:hover:bg-slate-800/25 transition-colors">
                            <td class="px-4 py-4 font-semibold text-gray-800 dark:text-slate-200">
                                <div class="flex items-center gap-3">
                                    <div class="relative w-8 h-8 rounded-full overflow-hidden border border-emerald-500 bg-gray-150 dark:bg-slate-950 flex items-center justify-center shadow-sm">
                                        @if ($user->profile_photo)
                                            <img src="{{ asset('storage/' . $user->profile_photo) }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="font-bold text-xs text-emerald-700 dark:text-emerald-400 uppercase">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </span>
                                        @endif
                                    </div>
                                    <span>{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-gray-600 dark:text-slate-400 font-medium">
                                {{ $user->email }}
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold
                                    {{ $user->role === 'admin' ? 'bg-red-55/10 text-red-700 dark:bg-red-950/20 dark:text-red-400 border border-red-200' : ($user->role === 'demo' ? 'bg-amber-55/10 text-amber-700 dark:bg-amber-950/20 dark:text-amber-400 border border-amber-200' : 'bg-emerald-55/10 text-emerald-700 dark:bg-emerald-950/20 dark:text-emerald-400 border border-emerald-200') }}">
                                    {{ $user->role === 'admin' ? 'এডমিন (Admin)' : ($user->role === 'demo' ? 'ডেমো (Demo)' : 'ইউজার (User)') }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="edit({{ $user->id }})"
                                            class="px-2.5 py-1.5 border border-gray-200 dark:border-slate-700 hover:border-emerald-550 text-gray-500 hover:text-emerald-600 dark:hover:text-emerald-400 rounded-lg transition-all cursor-pointer font-sans text-[11px] font-semibold"
                                            title="এডিট">
                                        এডিট
                                    </button>
                                    @if($user->id !== auth()->id())
                                        <button wire:click="delete({{ $user->id }})"
                                                onclick="confirm('ব্যবহারকারী মুছে ফেলবেন?') || event.stopImmediatePropagation()"
                                                class="px-2.5 py-1.5 border border-red-100 dark:border-red-950/30 hover:bg-red-50 dark:hover:bg-red-950/20 text-red-500 rounded-lg transition-all cursor-pointer font-sans text-[11px] font-semibold"
                                                title="মুছে ফেলুন">
                                            ডিলিট
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-400 dark:text-gray-500 italic">
                                কোনো ব্যবহারকারী খুঁজে পাওয়া যায়নি।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Form (Alpine persistent smooth state teleported to root) -->
    <template x-teleport="body">
        <div x-data="{ open: @entangle('showModal') }"
             x-show="open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-250"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click.self="open = false; $wire.resetForm()"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
             x-cloak>
             
             <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-2xl w-full border border-gray-100 dark:border-slate-800 shadow-2xl p-6 relative flex flex-col max-h-[92vh] overflow-y-auto"
                  x-show="open"
                  x-transition:enter="transition ease-out duration-300 transform"
                  x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                  x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                  x-transition:leave="transition ease-in duration-250 transform"
                  x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                  x-transition:leave-end="opacity-0 scale-95 translate-y-4">
                  
                  <!-- Close Icon button -->
                  <button type="button" @click="open = false; $wire.resetForm()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors focus:outline-none cursor-pointer">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                      </svg>
                  </button>

                  <h3 class="text-base font-bold text-gray-800 dark:text-white mb-5 border-b border-gray-100 dark:border-slate-800 pb-2 font-sans">
                      {{ $editingId ? 'ব্যবহারকারী সংশোধন করুন' : 'নতুন ব্যবহারকারী যুক্ত করুন' }}
                  </h3>
                  
                  <form wire:submit.prevent="save" class="space-y-4">
                      <!-- Name -->
                      <div>
                          <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">নাম</label>
                          <input type="text" wire:model="name" placeholder="যেমন: মোঃ মানিক মিয়া"
                                 class="w-full py-2.5 px-3 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-250/20 transition-all font-semibold">
                          @error('name') <span class="text-red-500 text-[10px] mt-1 block font-sans">{{ $message }}</span> @enderror
                      </div>

                      <!-- Email -->
                      <div>
                          <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">ইমেইল / ইউজারনেম</label>
                          <input type="email" wire:model="email" placeholder="যেমন: user@gmail.com"
                                 class="w-full py-2.5 px-3 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-250/20 transition-all font-semibold">
                          @error('email') <span class="text-red-500 text-[10px] mt-1 block font-sans">{{ $message }}</span> @enderror
                      </div>

                      <!-- Password with eye icon -->
                      <div x-data="{ showPass: false }">
                          <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">
                              {{ $editingId ? 'নতুন পাসওয়ার্ড (পরিবর্তন না করতে চাইলে খালি রাখুন)' : 'পাসওয়ার্ড' }}
                          </label>
                          <div class="relative">
                              <input :type="showPass ? 'text' : 'password'" wire:model="password" placeholder="কমপক্ষে ৮ অক্ষরের পাসওয়ার্ড"
                                     class="w-full py-2.5 pl-3 pr-10 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-250/20 transition-all font-semibold">
                              <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-emerald-600 focus:outline-none transition-colors">
                                  <svg x-show="showPass" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" x-cloak>
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                  </svg>
                                  <svg x-show="!showPass" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.024 10.024 0 013.859-4.877m2.138-2.138A9.974 9.974 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21M12 9a3 3 0 00-3 3m3 0a3 3 0 003-3M3 3l18 18"/>
                                  </svg>
                              </button>
                          </div>
                          @error('password') <span class="text-red-500 text-[10px] mt-1 block font-sans">{{ $message }}</span> @enderror
                      </div>

                      <!-- Role Dropdown -->
                      <div class="relative" x-data="{ dropdownOpen: false, roleVal: @entangle('role') }">
                          <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">ইউজার টাইপ (Role)</label>
                          
                          <!-- Selector Button -->
                          <button type="button" @click="dropdownOpen = !dropdownOpen"
                                  class="w-full flex items-center justify-between py-2.5 px-3 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs font-semibold text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-250/20 cursor-pointer text-left transition-all">
                              <span x-text="roleVal === 'admin' ? 'এডমিন (Admin)' : (roleVal === 'demo' ? 'ডেমো (Demo)' : 'ইউজার (User)')"></span>
                              <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': dropdownOpen }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                              </svg>
                          </button>
                          
                          <!-- Dropdown List Container -->
                          <div x-show="dropdownOpen" @click.away="dropdownOpen = false" x-transition
                               class="absolute left-0 right-0 mt-1.5 bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-gray-150 dark:border-slate-800 p-1.5 z-55 text-xs flex flex-col" x-cloak>
                              <button type="button" @click="roleVal = 'admin'; dropdownOpen = false" class="w-full text-left px-4 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-lg cursor-pointer">এডমিন (Admin)</button>
                              <button type="button" @click="roleVal = 'demo'; dropdownOpen = false" class="w-full text-left px-4 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-lg cursor-pointer">ডেমো (Demo)</button>
                              <button type="button" @click="roleVal = 'user'; dropdownOpen = false" class="w-full text-left px-4 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-lg cursor-pointer">ইউজার (User)</button>
                          </div>
                          @error('role') <span class="text-red-500 text-[10px] mt-1 block font-sans">{{ $message }}</span> @enderror
                      </div>

                      <!-- Spatie Permissions checklist (when role is not admin) -->
                      <div x-show="roleVal !== 'admin'" class="space-y-2 mt-4" x-cloak>
                          <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 font-sans">মেনু অ্যাক্সেস পারমিশনসমূহ</label>
                          <div class="grid grid-cols-3 gap-2 p-3 bg-gray-50 dark:bg-slate-950 border border-gray-155 dark:border-slate-800 rounded-xl">
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

                      <!-- Modal Actions -->
                      <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100 dark:border-slate-800">
                          <button type="button" @click="open = false; $wire.resetForm()" class="px-4 py-2 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-200 hover:text-gray-900 dark:hover:text-white text-xs font-semibold rounded-xl cursor-pointer transition-all font-sans">বাতিল</button>
                          <button type="submit" class="px-6 py-2 bg-primary text-white text-xs font-bold rounded-xl cursor-pointer shadow-md hover:bg-emerald-600 transition-all font-sans">সংরক্ষণ করুন</button>
                      </div>
                  </form>
             </div>
        </div>
    </template>
</div>
