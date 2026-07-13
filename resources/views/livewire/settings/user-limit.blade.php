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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left: Form Card -->
        <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-3xl p-6 shadow-sm transition-colors duration-300">
            <div class="flex items-center gap-3 border-b border-gray-100 dark:border-slate-800 pb-4 mb-5">
                <h3 class="font-bold text-sm text-gray-800 dark:text-white">ইউজার লিমিট সেট করুন</h3>
            </div>

            <form wire:submit.prevent="setLimit" class="space-y-4">
                
                <!-- Select User -->
                <div class="relative" x-data="{ open: false, selectedUser: 'ইউজার নির্বাচন করুন', userId: @entangle('selectedUserId') }">
                    <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 mb-1.5 uppercase">ইউজার নির্বাচন</label>
                    <button type="button" @click="open = !open"
                            class="w-full flex items-center justify-between py-2.5 px-4 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none transition-all font-semibold text-left">
                        <span x-text="userId ? (document.getElementById('user-opt-'+userId)?.dataset.name || selectedUser) : 'ইউজার নির্বাচন করুন'"></span>
                        <svg class="w-4 h-4 text-emerald-700 dark:text-emerald-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition class="absolute left-0 right-0 mt-1.5 bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-gray-100 dark:border-slate-800 py-1 z-55 text-xs max-h-48 overflow-y-auto" x-cloak>
                        @foreach($users as $user)
                            <button type="button" id="user-opt-{{ $user->id }}" data-name="{{ $user->name }}" @click="userId = {{ $user->id }}; open = false" class="w-full text-left px-4 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold">
                                {{ $user->name }} ({{ $user->email }})
                            </button>
                        @endforeach
                    </div>
                    @error('selectedUserId') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Limit Type -->
                <div class="relative" x-data="{ open: false, limitType: @entangle('limitType') }">
                    <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 mb-1.5 uppercase">লিমিটের ধরণ</label>
                    <button type="button" @click="open = !open"
                            class="w-full flex items-center justify-between py-2.5 px-4 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none transition-all font-semibold text-left">
                        <span x-text="limitType === 'daily_invoice_limit' ? 'দৈনিক ইনভয়েস লিমিট' : (limitType === 'max_discount_limit' ? 'সর্বোচ্চ ডিসকাউন্ট লিমিট' : 'দৈনিক ক্যাশ পেমেন্ট লিমিট')"></span>
                        <svg class="w-4 h-4 text-emerald-700 dark:text-emerald-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition class="absolute left-0 right-0 mt-1.5 bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-gray-100 dark:border-slate-800 py-1 z-55 text-xs overflow-hidden" x-cloak>
                        <button type="button" @click="limitType = 'daily_invoice_limit'; open = false" class="w-full text-left px-4 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold">দৈনিক ইনভয়েস লিমিট</button>
                        <button type="button" @click="limitType = 'max_discount_limit'; open = false" class="w-full text-left px-4 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold">সর্বোচ্চ ডিসকাউন্ট লিমিট</button>
                        <button type="button" @click="limitType = 'daily_payment_limit'; open = false" class="w-full text-left px-4 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold">দৈনিক ক্যাশ পেমেন্ট লিমিট</button>
                    </div>
                </div>

                <!-- Amount -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 mb-1.5 uppercase">নতুন পরিমাণ সেট করুন</label>
                    <input type="number" step="0.01" wire:model="amount" placeholder="৳ 0.00"
                           class="w-full py-2.5 px-4 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-250/20 dark:focus:ring-emerald-950/30 transition-all font-semibold">
                    @error('amount') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex pt-2">
                    <button type="submit"
                            class="w-full py-2.5 bg-primary hover:bg-primary-light text-white text-xs font-bold rounded-xl transition-all shadow-md">
                        লিমিট সেট করুন
                    </button>
                </div>
            </form>
        </div>

        <!-- Right: Current Active Limits Table -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-3xl p-6 shadow-sm overflow-hidden flex flex-col transition-colors duration-300">
            <div class="flex items-center gap-3 border-b border-gray-100 dark:border-slate-800 pb-4 mb-4">
                <h3 class="font-bold text-sm text-gray-800 dark:text-white">সক্রিয় ইউজার লিমিটসমূহ</h3>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-emerald-50 dark:bg-emerald-950/20 text-emerald-900 dark:text-emerald-300 font-bold border-b border-gray-100 dark:border-slate-800">
                            <th class="px-4 py-3 text-center w-12">#</th>
                            <th class="px-4 py-3">ইউজার নাম</th>
                            <th class="px-4 py-3">লিমিটের ধরণ</th>
                            <th class="px-4 py-3 text-right">লিমিট পরিমাণ (৳)</th>
                            <th class="px-4 py-3 text-center w-20">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800/80">
                        @forelse($activeLimits as $index => $limit)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-950/30 text-gray-700 dark:text-slate-200 transition-all">
                                <td class="px-4 py-3.5 text-center font-semibold text-gray-400">{{ $index + 1 }}</td>
                                <td class="px-4 py-3.5 font-bold">{{ $limit->user->name }}</td>
                                <td class="px-4 py-3.5 font-semibold text-gray-600 dark:text-slate-300">
                                    @switch($limit->limit_type)
                                        @case('daily_invoice_limit') দৈনিক ইনভয়েস লিমিট @break
                                        @case('max_discount_limit') সর্বোচ্চ ডিসকাউন্ট লিমিট @break
                                        @case('daily_payment_limit') দৈনিক ক্যাশ পেমেন্ট লিমিট @break
                                    @endswitch
                                </td>
                                <td class="px-4 py-3.5 text-right font-extrabold text-gray-800 dark:text-white">৳ {{ number_format($limit->amount, 2) }}</td>
                                <td class="px-4 py-3.5 text-center">
                                    <button onclick="confirm('আপনি কি এই ইউজার লিমিটটি মুছে ফেলতে চান?') || event.stopImmediatePropagation()"
                                            wire:click="deleteLimit({{ $limit->id }})" class="p-1.5 text-red-650 hover:bg-red-50 dark:hover:bg-red-950/35 rounded-lg transition-colors cursor-pointer" title="ডিলিট">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-400">কোনো ইউজার লিমিট পাওয়া যায়নি।</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
