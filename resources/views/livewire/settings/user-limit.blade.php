<div class="space-y-6">
    <!-- Header banner card -->
    <div class="flex items-start gap-4 p-4 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/50 rounded-2xl">
        <div class="flex-shrink-0 p-2 bg-emerald-600 text-white rounded-xl flex items-center justify-center">
            <!-- Alert Icon -->
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
            </svg>
        </div>
        <div>
            <h4 class="font-bold text-gray-800 dark:text-white text-sm">ইউজার লিমিট</h4>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">ব্যবহারকারীদের ডিসকাউন্ট, বাকি অথবা ডেলিভারি লিমিট নির্ধারণ করুন।</p>
        </div>
    </div>

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
                   placeholder="লিমিট খুঁজুন...">
        </div>

        <!-- Add Limit Button -->
        <button type="button" wire:click="openAddModal"
                class="px-5 py-2.5 bg-primary hover:bg-primary-light text-white text-xs font-bold rounded-xl transition-all shadow-md active:scale-95 cursor-pointer text-center font-sans flex items-center gap-2 justify-center">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            নতুন লিমিট সেট করুন
        </button>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl p-6 shadow-sm transition-colors duration-300">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-slate-950/50 border-b border-gray-150 dark:border-slate-800 text-gray-500 dark:text-slate-400 font-bold font-sans">
                        <th class="px-4 py-3.5">ইউজার নাম</th>
                        <th class="px-4 py-3.5">লিমিটের ধরণ</th>
                        <th class="px-4 py-3.5 text-right">লিমিট পরিমাণ</th>
                        <th class="px-4 py-3.5 text-right">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800/80 font-sans">
                    @forelse($activeLimits as $limit)
                        <tr class="hover:bg-gray-50/40 dark:hover:bg-slate-800/25 transition-colors">
                            <td class="px-4 py-4 font-semibold text-gray-800 dark:text-slate-200">
                                {{ $limit->user->name ?? '-' }}
                            </td>
                            <td class="px-4 py-4">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold
                                    bg-emerald-50 text-emerald-700 dark:bg-emerald-950/20 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/50">
                                    {{ $limit->limit_type === 'discount_limit' || $limit->limit_type === 'max_discount_limit' ? 'ডিসকাউন্ট লিমিট (Discount)' : ($limit->limit_type === 'due_limit' ? 'বাকি লিমিট (Due Limit)' : ($limit->limit_type === 'delivery_limit' ? 'ডেলিভারি লিমিট (Delivery)' : ($limit->limit_type === 'daily_invoice_limit' ? 'দৈনিক ইনভয়েস লিমিট' : 'দৈনিক ক্যাশ পেমেন্ট লিমিট'))) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-right font-bold text-gray-900 dark:text-white">
                                ৳ {{ number_format((float)($limit->amount), (float)($limit->amount) == (int)($limit->amount) ? 0 : 2) }}
                            </td>
                            <td class="px-4 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="editLimit({{ $limit->id }})"
                                            class="px-2.5 py-1.5 border border-gray-200 dark:border-slate-700 hover:border-emerald-500 text-gray-600 hover:text-emerald-600 dark:text-slate-300 dark:hover:text-emerald-400 rounded-lg transition-all cursor-pointer font-sans text-[11px] font-semibold flex items-center gap-1"
                                            title="এডিট">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                        </svg>
                                        এডিট
                                    </button>
                                    <button wire:click="confirmDelete({{ $limit->id }})"
                                            class="p-1.5 border border-red-100 dark:border-red-950/30 hover:bg-red-50 dark:hover:bg-red-950/20 text-red-500 rounded-lg transition-all cursor-pointer"
                                            title="মুছে ফেলুন">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-400 dark:text-gray-500 italic">
                                কোনো ইউজার লিমিট খুঁজে পাওয়া যায়নি।
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
             @click.self="open = false; $wire.cancelEdit()"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
             x-cloak>
             
             <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-md w-full border border-gray-100 dark:border-slate-800 shadow-2xl p-6 relative flex flex-col"
                  x-show="open"
                  x-transition:enter="transition ease-out duration-300 transform"
                  x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                  x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                  x-transition:leave="transition ease-in duration-250 transform"
                  x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                  x-transition:leave-end="opacity-0 scale-95 translate-y-4">
                  
                  <!-- Close Icon button -->
                  <button type="button" @click="open = false; $wire.cancelEdit()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors focus:outline-none cursor-pointer">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                      </svg>
                  </button>

                  <h3 class="text-base font-bold text-gray-800 dark:text-white mb-5 border-b border-gray-100 dark:border-slate-800 pb-2 font-sans">
                      {{ $editingLimitId ? 'ইউজার লিমিট সংশোধন করুন' : 'ইউজার লিমিট সেট করুন' }}
                  </h3>
                  
                  <form wire:submit.prevent="setLimit" class="space-y-4">
                      <!-- Select User -->
                      <div class="relative" x-data="{ dropdownOpen: false, searchVal: '', selectedUser: 'ইউজার নির্বাচন করুন', userId: @entangle('selectedUserId') }">
                          <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">ইউজার নির্বাচন</label>
                          
                          <!-- Selector Button -->
                          <button type="button" @click="dropdownOpen = !dropdownOpen"
                                  class="w-full flex items-center justify-between py-2.5 px-3 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs font-semibold text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-250/20 cursor-pointer text-left transition-all">
                              <span x-text="userId ? (document.getElementById('user-modal-opt-'+userId)?.dataset.name || selectedUser) : 'ইউজার নির্বাচন করুন'"></span>
                              <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': dropdownOpen }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                              </svg>
                          </button>
                          
                          <!-- Dropdown List Container -->
                          <div x-show="dropdownOpen" @click.away="dropdownOpen = false" x-transition
                               class="absolute left-0 right-0 mt-1.5 bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-gray-150 dark:border-slate-800 p-2 z-55 text-xs max-h-48 overflow-y-auto" x-cloak>
                              @foreach($users as $user)
                                  <button type="button" id="user-modal-opt-{{ $user->id }}" data-name="{{ $user->name }}" 
                                          @click="userId = {{ $user->id }}; selectedUser = '{{ $user->name }}'; dropdownOpen = false" 
                                          class="w-full text-left px-4 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-lg cursor-pointer">
                                      {{ $user->name }} ({{ $user->email }})
                                  </button>
                              @endforeach
                          </div>
                          @error('selectedUserId') <span class="text-red-500 text-[10px] mt-1 block font-sans">{{ $message }}</span> @enderror
                      </div>

                      <!-- Limit Type -->
                      <div class="relative" x-data="{ dropdownOpen2: false, typeVal: @entangle('limitType') }">
                          <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">লিমিটের ধরণ</label>
                          
                          <!-- Selector Button -->
                          <button type="button" @click="dropdownOpen2 = !dropdownOpen2"
                                  class="w-full flex items-center justify-between py-2.5 px-3 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs font-semibold text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-250/20 cursor-pointer text-left transition-all">
                              <span x-text="typeVal === 'discount_limit' ? 'ডিসকাউন্ট লিমিট (Discount)' : (typeVal === 'due_limit' ? 'বাকি লিমিট (Due Limit)' : (typeVal === 'delivery_limit' ? 'ডেলিভারি লিমিট (Delivery)' : (typeVal === 'max_discount_limit' ? 'ডিসকাউন্ট লিমিট (Discount)' : 'টাইপ সিলেক্ট করুন')))"></span>
                              <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': dropdownOpen2 }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                              </svg>
                          </button>
                          
                          <!-- Dropdown List Container -->
                          <div x-show="dropdownOpen2" @click.away="dropdownOpen2 = false" x-transition
                               class="absolute left-0 right-0 mt-1.5 bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-gray-155 dark:border-slate-800 p-1.5 z-55 text-xs flex flex-col" x-cloak>
                              <button type="button" @click="typeVal = 'discount_limit'; dropdownOpen2 = false" class="w-full text-left px-4 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-lg cursor-pointer">ডিসকাউন্ট লিমিট (Discount)</button>
                              <button type="button" @click="typeVal = 'due_limit'; dropdownOpen2 = false" class="w-full text-left px-4 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-lg cursor-pointer">বাকি লিমিট (Due Limit)</button>
                              <button type="button" @click="typeVal = 'delivery_limit'; dropdownOpen2 = false" class="w-full text-left px-4 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-lg cursor-pointer">ডেলিভারি লিমিট (Delivery)</button>
                          </div>
                      </div>

                      <!-- Amount Input -->
                      <div>
                          <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">নতুন পরিমাণ সেট করুন</label>
                          <input type="number" step="0.01" wire:model="amount" placeholder="৳ 0.00"
                                 class="w-full py-2.5 px-3 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-250/20 transition-all font-semibold">
                          @error('amount') <span class="text-red-500 text-[10px] mt-1 block font-sans">{{ $message }}</span> @enderror
                      </div>

                      <!-- Modal Actions -->
                      <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100 dark:border-slate-800">
                          <button type="button" @click="open = false; $wire.cancelEdit()" class="px-4 py-2 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-200 hover:text-gray-900 dark:hover:text-white text-xs font-semibold rounded-xl cursor-pointer transition-all font-sans">বাতিল</button>
                          <button type="submit" class="px-6 py-2 bg-primary text-white text-xs font-bold rounded-xl cursor-pointer shadow-md hover:bg-emerald-600 transition-all font-sans">সংরক্ষণ করুন</button>
                      </div>
                  </form>
             </div>
        </div>
    </template>

    {{-- Delete Confirmation Modal (Full Screen Teleport) --}}
    <template x-teleport="body">
        <div x-data="{ open: @entangle('confirmDeleteId').live }"
             x-show="open"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[999999] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
             x-cloak>
            <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-sm w-full p-6 shadow-2xl border border-gray-150 dark:border-slate-800 text-center font-sans">
                <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 mx-auto flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-gray-800 dark:text-white mb-2">আপনি কি নিশ্চিত?</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-6">
                    আপনি কি নিশ্চিত যে এই লিমিট সেটিংসটি মুছে ফেলতে চান?
                </p>
                <div class="flex items-center justify-center gap-3">
                    <button type="button"
                            wire:click="deleteConfirmed"
                            class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white font-bold text-xs rounded-xl shadow-md transition-all cursor-pointer">
                        হ্যাঁ, ডিলিট করুন
                    </button>
                    <button type="button"
                            wire:click="$set('confirmDeleteId', null)"
                            class="px-5 py-2 bg-gray-200 dark:bg-slate-800 hover:bg-gray-300 dark:hover:bg-slate-700 text-gray-700 dark:text-gray-300 font-bold text-xs rounded-xl transition-all cursor-pointer">
                        না
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>
