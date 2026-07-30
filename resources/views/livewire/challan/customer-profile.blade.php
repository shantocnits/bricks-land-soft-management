<div>
    <!-- Breadcrumbs / Header -->
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6 font-sans">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-gray-800 dark:text-white flex items-center gap-2">
                কাস্টমার প্রোফাইল 👤
            </h1>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">কাস্টমারের বিস্তারিত বিবরণ এবং চালানের ইতিহাস দেখুন</p>
        </div>
        <div class="flex items-center gap-2.5">
            @if(request('from') === 'customer')
                <a href="{{ route('customer') }}" wire:navigate
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-200 text-xs font-bold rounded-xl cursor-pointer transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    কাস্টমার তালিকা
                </a>
            @else
                <a href="{{ route('challan.all') }}" wire:navigate
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-200 text-xs font-bold rounded-xl cursor-pointer transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    পিছনে যান
                </a>
            @endif
        </div>
    </div>

    <!-- Alert Message -->
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
             x-transition:leave="transition ease-in duration-300" x-transition:leave-end="opacity-0"
             class="mb-6 p-3.5 bg-primary-50 dark:bg-primary-950/20 border border-primary-200 dark:border-primary-900 text-primary-800 dark:text-primary-400 rounded-2xl text-xs font-medium font-sans" x-cloak>
            {{ session('message') }}
        </div>
    @endif

    <!-- Profile Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 font-sans mb-6">
        <!-- Left Sidebar: Customer Card -->
        <div class="lg:col-span-1">
            <!-- Green Card -->
            <div class="bg-primary text-white rounded-3xl p-6 shadow-xl flex flex-col items-center justify-between text-center relative overflow-hidden h-64">
                <div class="absolute -right-10 -bottom-10 opacity-10">
                    <svg class="w-40 h-40" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                </div>
                <div class="w-20 h-20 bg-primary-500 rounded-full flex items-center justify-center text-3xl font-black border-4 border-primary-400 shadow-md">
                    {{ mb_substr($customer_name, 0, 1) }}
                </div>
                <div class="mt-4">
                    <h3 class="text-lg font-black tracking-wide">{{ $customer_name }}</h3>
                    <p class="text-xs text-primary-100 font-medium mt-1 font-sans">{{ $customer_phone ?: 'কোনো ফোন নম্বর নেই' }}</p>
                </div>
                <button type="button" @click="window.print()" class="w-full mt-4 py-2.5 bg-white/20 hover:bg-white/30 text-white text-xs font-bold rounded-xl cursor-pointer transition-all flex items-center justify-center gap-1.5 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    প্রিন্ট
                </button>
            </div>
        </div>

        <!-- Meta details (Name, address, phone) in a 3-column Grid layout -->
        <div class="lg:col-span-3 bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-3xl p-6 shadow-sm flex flex-col justify-center">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-sm">
                <div class="border-b sm:border-b-0 sm:border-r border-gray-100 dark:border-slate-800 pb-4 sm:pb-0 sm:pr-6">
                    <span class="block text-gray-400 font-medium mb-1">নাম</span>
                    <span class="block font-black text-gray-800 dark:text-white mt-0.5 text-base">{{ $customer_name }}</span>
                </div>
                <div class="border-b sm:border-b-0 sm:border-r border-gray-100 dark:border-slate-800 pb-4 sm:pb-0 sm:pr-6">
                    <span class="block text-gray-400 font-medium mb-1">ফোন নম্বর</span>
                    <span class="block font-black text-gray-800 dark:text-white mt-0.5 text-base font-sans">{{ $customer_phone ?: '—' }}</span>
                </div>
                <div>
                    <span class="block text-gray-400 font-medium mb-1">ঠিকানা</span>
                    <span class="block font-black text-gray-800 dark:text-white mt-0.5 text-base">{{ $customer_address ?: '—' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Below Section: Full Width Tables & Stats -->
    <div class="space-y-6">
        <!-- Stats Grid (Full Width) -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-9 gap-4 font-sans">
            <!-- Stat 1 -->
            <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
                <span class="block text-xs font-bold text-gray-400">মোট ইট ক্রয়</span>
                <span class="block text-base font-black text-gray-800 dark:text-white mt-1">{{ number_format($stats['total_bricks']) }} টি</span>
            </div>
            <!-- Stat 2 -->
            <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
                <span class="block text-xs font-bold text-gray-400">ডেলিভারি</span>
                <span class="block text-base font-black text-primary dark:text-primary-404 mt-1">{{ number_format($stats['delivered']) }} টি</span>
            </div>
            <!-- Stat 3 -->
            <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
                <span class="block text-xs font-bold text-gray-400">ডেলিভারি বাকি</span>
                <span class="block text-base font-black text-red-500 mt-1">{{ number_format($stats['remaining']) }} টি</span>
            </div>
            <!-- Stat 4 -->
            <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
                <span class="block text-xs font-bold text-gray-400">মোট মূল্য</span>
                <span class="block text-base font-black text-gray-800 dark:text-white mt-1">৳{{ number_format($stats['total_value']) }}</span>
            </div>
            <!-- Stat 5 -->
            <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
                <span class="block text-xs font-bold text-gray-400">পরিশোধ</span>
                <span class="block text-base font-black text-primary dark:text-primary-404 mt-1">৳{{ number_format($stats['paid']) }}</span>
            </div>
            <!-- Stat 6 -->
            <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
                <span class="block text-xs font-bold text-gray-400">টাকা বাকি</span>
                <span class="block text-base font-black text-red-500 mt-1">৳{{ number_format($stats['due']) }}</span>
            </div>
            <!-- Stat 7 -->
            <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
                <span class="block text-xs font-bold text-gray-400">পরিশোধের তারিখ</span>
                <span class="block text-sm font-black text-gray-800 dark:text-white mt-1">—</span>
            </div>
            <!-- Stat 8 -->
            <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
                <span class="block text-xs font-bold text-gray-400">নোট</span>
                <span class="block text-sm font-black text-gray-800 dark:text-white mt-1">—</span>
            </div>
            <!-- Stat 9 -->
            <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
                <span class="block text-xs font-bold text-gray-400">সিজন</span>
                <span class="block text-sm font-black text-gray-800 dark:text-white mt-1">২৫-২৬</span>
            </div>
        </div>

        <!-- Tabs Section -->
        <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-3xl overflow-hidden shadow-sm">
            <!-- Tabs Row -->
            <div class="flex border-b border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950/20 p-2 gap-1 font-sans">
                <button type="button" wire:click="$set('activeTab', 'all_challan')" class="px-5 py-2.5 rounded-xl text-xs font-bold cursor-pointer transition-all" :class="$wire.activeTab === 'all_challan' ? 'bg-primary text-white shadow-sm' : 'text-gray-500 hover:text-gray-700 dark:hover:text-slate-300'">
                    সব চালান
                </button>
                <button type="button" wire:click="$set('activeTab', 'delivery_history')" class="px-5 py-2.5 rounded-xl text-xs font-bold cursor-pointer transition-all" :class="$wire.activeTab === 'delivery_history' ? 'bg-primary text-white shadow-sm' : 'text-gray-500 hover:text-gray-700 dark:hover:text-slate-300'">
                    ডেলিভারি হিস্ট্রি
                </button>
                <button type="button" wire:click="$set('activeTab', 'due_history')" class="px-5 py-2.5 rounded-xl text-xs font-bold cursor-pointer transition-all" :class="$wire.activeTab === 'due_history' ? 'bg-primary text-white shadow-sm' : 'text-gray-500 hover:text-gray-700 dark:hover:text-slate-300'">
                    বাকি জমা হিস্ট্রি
                </button>
            </div>

            <!-- Tab Content Area -->
            <div class="p-5 space-y-4">
                <!-- Filters Grid -->
                <div class="flex flex-wrap items-center justify-between gap-3 bg-gray-50/40 dark:bg-slate-950/10 p-3 rounded-2xl border border-gray-100 dark:border-slate-800">
                    <div class="flex flex-wrap items-center gap-3">
                        <div class="relative">
                            <input type="text" wire:model.live="search" placeholder="চালান নং দিয়ে খুজুন..." class="pl-4 pr-4 py-2 text-xs rounded-xl border border-gray-250 dark:border-slate-700 bg-white dark:bg-slate-900 text-gray-808 dark:text-white focus:outline-none focus:border-primary-505 transition-all font-sans font-semibold w-44">
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="text"
                                   data-flatpickr
                                   data-wire-prop="dateFrom"
                                   data-default="{{ $dateFrom }}"
                                   wire:model="dateFrom"
                                   readonly
                                   class="py-2 px-3 text-xs rounded-xl border border-gray-250 dark:border-slate-700 bg-white dark:bg-slate-900 text-gray-808 dark:text-white font-sans focus:outline-none focus:border-primary-505 cursor-pointer">
                            <span class="text-gray-400 text-xs">থেকে</span>
                            <input type="text"
                                   data-flatpickr
                                   data-wire-prop="dateTo"
                                   data-default="{{ $dateTo }}"
                                   wire:model="dateTo"
                                   readonly
                                   class="py-2 px-3 text-xs rounded-xl border border-gray-250 dark:border-slate-700 bg-white dark:bg-slate-900 text-gray-808 dark:text-white font-sans focus:outline-none focus:border-primary-505 cursor-pointer">
                        </div>
                    </div>
                    <button type="button" @click="window.print()" class="px-4 py-2 bg-primary hover:bg-primary-dark text-white text-xs font-bold rounded-xl cursor-pointer transition-all flex items-center gap-1.5 shadow-sm active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                        @if($activeTab === 'all_challan')
                            চালান প্রিন্ট
                        @elseif($activeTab === 'delivery_history')
                            ডেলিভারি প্রিন্ট
                        @elseif($activeTab === 'due_history')
                            বাকি জমা প্রিন্ট
                        @endif
                    </button>
                </div>

                <!-- History Tables -->
                <div class="overflow-x-auto custom-scrollbar">
                    @if($activeTab === 'all_challan')
                        <!-- All Challans Table -->
                        <table class="w-full text-left border-collapse border border-primary-100 dark:border-slate-800 rounded-xl overflow-hidden font-sans">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-slate-950 border-b border-gray-150 dark:border-slate-800 text-[10px] uppercase font-bold text-gray-500">
                                    <th class="px-3 py-3 text-center border-r border-gray-150 dark:border-slate-800 w-10">#</th>
                                    <th class="px-3 py-3 border-r border-gray-150 dark:border-slate-800 w-24">তারিখ</th>
                                    <th class="px-3 py-3 border-r border-gray-150 dark:border-slate-800">শ্রেণি</th>
                                    <th class="px-3 py-3 text-right border-r border-gray-150 dark:border-slate-800 w-20">পরিমাণ</th>
                                    <th class="px-3 py-3 text-right border-r border-gray-150 dark:border-slate-800 w-20">রেট</th>
                                    <th class="px-3 py-3 text-right border-r border-gray-150 dark:border-slate-800 w-24">মূল্য</th>
                                    <th class="px-3 py-3 text-right border-r border-gray-150 dark:border-slate-800 w-24">মোট মূল্য</th>
                                    <th class="px-3 py-3 text-right border-r border-gray-150 dark:border-slate-800 w-20">ছাড়</th>
                                    <th class="px-3 py-3 text-right border-r border-gray-150 dark:border-slate-800 w-20">ভাড়া</th>
                                    <th class="px-3 py-3 text-right border-r border-gray-150 dark:border-slate-800 w-24">সর্বমোট</th>
                                    <th class="px-3 py-3 text-right border-r border-gray-150 dark:border-slate-800 w-24">নগদ</th>
                                    <th class="px-3 py-3 text-right border-r border-gray-150 dark:border-slate-800 w-24">বাকি</th>
                                    <th class="px-3 py-3 text-center w-16">বাটন</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-slate-800 text-xs">
                                @forelse($challans as $i => $challan)
                                    <tr class="hover:bg-primary-50/20 dark:hover:bg-primary-950/5">
                                        <td class="px-3 py-3.5 text-center font-bold border-r border-gray-150 dark:border-slate-800 text-gray-500">{{ $challan->challan_no }}</td>
                                        <td class="px-3 py-3.5 border-r border-gray-150 dark:border-slate-800 font-sans">{{ $challan->date ? $challan->date->format('d-m-Y') : '' }}</td>
                                        <td class="px-3 py-3.5 border-r border-gray-150 dark:border-slate-800">
                                            @foreach($challan->items as $item)
                                                <span class="block font-bold text-primary-dark dark:text-primary-400">{{ $item->category_name }}</span>
                                            @endforeach
                                        </td>
                                        <td class="px-3 py-3.5 text-right border-r border-gray-150 dark:border-slate-800 font-semibold text-gray-700 dark:text-slate-300 font-sans">
                                            @foreach($challan->items as $item)
                                                <span class="block">{{ number_format($item->quantity) }}</span>
                                            @endforeach
                                        </td>
                                        <td class="px-3 py-3.5 text-right border-r border-gray-150 dark:border-slate-800 font-sans">
                                            @foreach($challan->items as $item)
                                                <span class="block">৳{{ number_format((float)($item->rate), (float)($item->rate) == (int)($item->rate) ? 0 : 2) }}</span>
                                            @endforeach
                                        </td>
                                        <td class="px-3 py-3.5 text-right border-r border-gray-150 dark:border-slate-800 font-sans font-bold">
                                            ৳{{ number_format((float)($challan->value), (float)($challan->value) == (int)($challan->value) ? 0 : 2) }}
                                        </td>
                                        <td class="px-3 py-3.5 text-right border-r border-gray-150 dark:border-slate-800 font-sans font-bold">
                                            ৳{{ number_format((float)($challan->value), (float)($challan->value) == (int)($challan->value) ? 0 : 2) }}
                                        </td>
                                        <td class="px-3 py-3.5 text-right border-r border-gray-150 dark:border-slate-800 text-orange-600 dark:text-orange-400 font-sans font-bold">৳{{ number_format((float)($challan->discount), (float)($challan->discount) == (int)($challan->discount) ? 0 : 2) }}</td>
                                        <td class="px-3 py-3.5 text-right border-r border-gray-150 dark:border-slate-800 font-sans font-bold text-blue-600 dark:text-blue-400">৳{{ number_format((float)($challan->transport_rent), (float)($challan->transport_rent) == (int)($challan->transport_rent) ? 0 : 2) }}</td>
                                        <td class="px-3 py-3.5 text-right border-r border-gray-150 dark:border-slate-800 font-bold text-purple-700 dark:text-purple-400 font-sans">৳{{ number_format((float)($challan->grand_total), (float)($challan->grand_total) == (int)($challan->grand_total) ? 0 : 2) }}</td>
                                        <td class="px-3 py-3.5 text-right border-r border-gray-150 dark:border-slate-800 font-semibold text-primary dark:text-primary-400 font-sans">৳{{ number_format((float)($challan->cash), (float)($challan->cash) == (int)($challan->cash) ? 0 : 2) }}</td>
                                        <td class="px-3 py-3.5 text-right border-r border-gray-150 dark:border-slate-800 font-sans">
                                            <span class="font-bold {{ $challan->due > 0 ? 'text-red-500' : 'text-gray-400' }}">৳{{ number_format((float)($challan->due), (float)($challan->due) == (int)($challan->due) ? 0 : 2) }}</span>
                                        </td>
                                        <td class="px-3 py-3.5 text-center relative" x-data="{ openDropdown: false, buttonRect: null }">
                                            <button type="button" @click="openDropdown = !openDropdown; buttonRect = $el.getBoundingClientRect()" class="p-1.5 text-gray-500 hover:text-primary focus:outline-none transition-all cursor-pointer">
                                                <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z"/></svg>
                                            </button>
                                            <template x-teleport="body">
                                                <div x-show="openDropdown" @click.away="openDropdown = false" x-transition
                                                     class="fixed w-48 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-2xl shadow-2xl p-1.5 z-[9999] text-left text-xs flex flex-col gap-0.5"
                                                     :style="buttonRect ? ('left: ' + (buttonRect.left - 140) + 'px; position: fixed; ' + (window.innerHeight - buttonRect.bottom < 140 ? 'bottom: ' + (window.innerHeight - buttonRect.top + 4) + 'px;' : 'top: ' + (buttonRect.bottom + 4) + 'px;')) : ''"
                                                     x-cloak>
                                                    <button type="button" @click="openDropdown = false" wire:click="openPrintModal({{ $challan->id }}, false)" class="w-full text-left px-3 py-2 hover:bg-primary-50 dark:hover:bg-primary-950/20 text-gray-700 dark:text-slate-200 hover:text-primary-dark dark:hover:text-primary-400 transition-all font-semibold rounded-xl cursor-pointer flex items-center gap-2">
                                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                                                        চালান প্রিন্ট
                                                    </button>
                                                    <button type="button" @click="openDropdown = false" wire:click="openDeliveryModal({{ $challan->id }})" class="w-full text-left px-3 py-2 hover:bg-primary-50 dark:hover:bg-primary-950/20 text-gray-700 dark:text-slate-200 hover:text-primary-dark dark:hover:text-primary-400 transition-all font-semibold rounded-xl cursor-pointer flex items-center gap-2">
                                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                                                        ডেলিভারি দিন
                                                    </button>
                                                    <button type="button" @click="openDropdown = false" wire:click="openChallanDetailsModal({{ $challan->id }})" class="w-full text-left px-3 py-2 hover:bg-primary-50 dark:hover:bg-primary-950/20 text-gray-700 dark:text-slate-200 hover:text-primary-dark dark:hover:text-primary-400 transition-all font-semibold rounded-xl cursor-pointer flex items-center gap-2">
                                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                        বিস্তারিত দেখুন
                                                    </button>
                                                </div>
                                            </template>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="13" class="px-4 py-8 text-center text-gray-450 dark:text-slate-500">কোনো চালান পাওয়া যায়নি</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <!-- Pagination -->
                        <div class="pt-4">
                            {{ $challans->links() }}
                        </div>
                    @elseif($activeTab === 'delivery_history')
                        <!-- Delivery History Table -->
                        <table class="w-full text-left border-collapse border border-primary-100 dark:border-slate-800 rounded-xl overflow-hidden font-sans">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-slate-950 border-b border-gray-150 dark:border-slate-800 text-[10px] uppercase font-bold text-gray-500">
                                    <th class="px-3 py-3 text-center border-r border-gray-150 dark:border-slate-800 w-10">#</th>
                                    <th class="px-3 py-3 border-r border-gray-150 dark:border-slate-800 w-24">চালান নং</th>
                                    <th class="px-3 py-3 border-r border-gray-150 dark:border-slate-800">কাস্টমার</th>
                                    <th class="px-3 py-3 border-r border-gray-150 dark:border-slate-800">ঠিকানা</th>
                                    <th class="px-3 py-3 border-r border-gray-150 dark:border-slate-800">শ্রেণি</th>
                                    <th class="px-3 py-3 text-right border-r border-gray-150 dark:border-slate-800 w-20">ক্রয়</th>
                                    <th class="px-3 py-3 text-right border-r border-gray-150 dark:border-slate-800 w-20">ডেলিভারি</th>
                                    <th class="px-3 py-3 text-right border-r border-gray-150 dark:border-slate-800 w-20">ডে.বাকি</th>
                                    <th class="px-3 py-3 text-right border-r border-gray-150 dark:border-slate-800 w-24">মোট ডেলিভারি</th>
                                    <th class="px-3 py-3 border-r border-gray-150 dark:border-slate-800">ড্রাইভার</th>
                                    <th class="px-3 py-3 border-r border-gray-150 dark:border-slate-800 w-24">তারিখ</th>
                                    <th class="px-3 py-3 text-center w-16">বাটন</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-slate-800 text-xs">
                                @forelse($printChallans as $i => $challan)
                                    @foreach($challan->items as $item)
                                    <tr class="hover:bg-primary-50/20 dark:hover:bg-primary-950/5">
                                        <td class="px-3 py-3.5 text-center font-bold border-r border-gray-150 dark:border-slate-800 text-gray-500 font-sans">{{ $loop->parent->iteration }}</td>
                                        <td class="px-3 py-3.5 border-r border-gray-150 dark:border-slate-800 font-bold font-sans text-gray-808 dark:text-white">{{ $challan->challan_no }}</td>
                                        <td class="px-3 py-3.5 border-r border-gray-150 dark:border-slate-800 font-bold text-gray-700 dark:text-slate-350">{{ $challan->customer_name }}</td>
                                        <td class="px-3 py-3.5 border-r border-gray-150 dark:border-slate-800 text-gray-600 dark:text-slate-400 font-sans">{{ $challan->customer_address ?: '—' }}</td>
                                        <td class="px-3 py-3.5 border-r border-gray-150 dark:border-slate-800 font-bold text-primary-dark dark:text-primary-404">{{ $item->category_name }}</td>
                                        <td class="px-3 py-3.5 text-right border-r border-gray-150 dark:border-slate-800 font-semibold font-sans">{{ number_format($item->quantity) }}</td>
                                        <td class="px-3 py-3.5 text-right border-r border-gray-150 dark:border-slate-800 font-bold font-sans text-primary dark:text-primary-400">{{ number_format($item->delivered_quantity) }}</td>
                                        <td class="px-3 py-3.5 text-right border-r border-gray-150 dark:border-slate-800 font-bold font-sans text-red-500">{{ number_format(max(0, $item->quantity - $item->delivered_quantity)) }}</td>
                                        <td class="px-3 py-3.5 text-right border-r border-gray-150 dark:border-slate-800 font-bold font-sans text-primary dark:text-primary-400">{{ number_format($item->delivered_quantity) }}</td>
                                        <td class="px-3 py-3.5 border-r border-gray-150 dark:border-slate-800 text-gray-600 dark:text-slate-400">Demo Driver</td>
                                        <td class="px-3 py-3.5 border-r border-gray-150 dark:border-slate-800 font-sans">{{ $challan->date ? $challan->date->format('d-m-Y') : '' }}</td>
                                        <td class="px-3 py-3.5 text-center relative" x-data="{ openDropdown: false, buttonRect: null }">
                                            <button type="button" @click="openDropdown = !openDropdown; buttonRect = $el.getBoundingClientRect()" class="p-1.5 text-gray-500 hover:text-primary focus:outline-none transition-all cursor-pointer">
                                                <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z"/></svg>
                                            </button>
                                            <template x-teleport="body">
                                                <div x-show="openDropdown" @click.away="openDropdown = false" x-transition
                                                     class="fixed w-48 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-2xl shadow-2xl p-1.5 z-[9999] text-left text-xs flex flex-col gap-0.5"
                                                     :style="buttonRect ? ('left: ' + (buttonRect.left - 140) + 'px; position: fixed; ' + (window.innerHeight - buttonRect.bottom < 140 ? 'bottom: ' + (window.innerHeight - buttonRect.top + 4) + 'px;' : 'top: ' + (buttonRect.bottom + 4) + 'px;')) : ''"
                                                     x-cloak>
                                                    <button type="button" @click="openDropdown = false" wire:click="openPrintModal({{ $challan->id }}, true)" class="w-full text-left px-3 py-2 hover:bg-primary-50 dark:hover:bg-primary-950/20 text-gray-700 dark:text-slate-200 hover:text-primary-dark dark:hover:text-primary-400 transition-all font-semibold rounded-xl cursor-pointer flex items-center gap-2">
                                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                                                        ডেলিভারি প্রিন্ট
                                                    </button>
                                                    <button type="button" @click="openDropdown = false" wire:click="openDeliveryDetailsModal({{ $challan->id }})" class="w-full text-left px-3 py-2 hover:bg-primary-50 dark:hover:bg-primary-950/20 text-gray-700 dark:text-slate-200 hover:text-primary-dark dark:hover:text-primary-400 transition-all font-semibold rounded-xl cursor-pointer flex items-center gap-2">
                                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                        বিস্তারিত দেখুন
                                                    </button>
                                                </div>
                                            </template>
                                        </td>
                                    </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="12" class="px-4 py-8 text-center text-gray-450 dark:text-slate-500">কোনো ডেলিভারি হিস্ট্রি পাওয়া যায়নি</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    @elseif($activeTab === 'due_history')
                        <!-- Due History Table -->
                        <table class="w-full text-left border-collapse border border-primary-100 dark:border-slate-800 rounded-xl overflow-hidden font-sans">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-slate-950 border-b border-gray-150 dark:border-slate-800 text-[10px] uppercase font-bold text-gray-500">
                                    <th class="px-3 py-3 border-r border-gray-150 dark:border-slate-800 w-28">তারিখ</th>
                                    <th class="px-3 py-3 border-r border-gray-150 dark:border-slate-800 w-20">আইডি</th>
                                    <th class="px-3 py-3 text-right border-r border-gray-150 dark:border-slate-800 w-32">টাকা বাকি ছিল</th>
                                    <th class="px-3 py-3 text-right border-r border-gray-150 dark:border-slate-800 w-32">জমা দেওয়া</th>
                                    <th class="px-3 py-3 text-right border-r border-gray-150 dark:border-slate-800 w-32">অবশিষ্ট বাকি</th>
                                    <th class="px-3 py-3 border-r border-gray-150 dark:border-slate-800">নোট</th>
                                    <th class="px-3 py-3 border-r border-gray-150 dark:border-slate-800 w-28">নতুন তারিখ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-slate-800 text-xs">
                                @forelse($printChallans as $i => $challan)
                                    <tr class="hover:bg-primary-50/20 dark:hover:bg-primary-950/5">
                                        <td class="px-3 py-3.5 border-r border-gray-150 dark:border-slate-800 font-sans">{{ $challan->date ? $challan->date->format('d-m-Y') : '' }}</td>
                                        <td class="px-3 py-3.5 border-r border-gray-150 dark:border-slate-800 font-bold font-sans text-gray-808 dark:text-white">{{ $challan->challan_no }}</td>
                                        <td class="px-3 py-3.5 text-right border-r border-gray-150 dark:border-slate-800 font-sans">৳{{ number_format((float)($challan->grand_total), (float)($challan->grand_total) == (int)($challan->grand_total) ? 0 : 2) }}</td>
                                        <td class="px-3 py-3.5 text-right border-r border-gray-150 dark:border-slate-800 font-bold text-primary dark:text-primary-400 font-sans">৳{{ number_format((float)($challan->cash), (float)($challan->cash) == (int)($challan->cash) ? 0 : 2) }}</td>
                                        <td class="px-3 py-3.5 text-right border-r border-gray-150 dark:border-slate-800 font-bold text-red-500 font-sans">৳{{ number_format((float)($challan->due), (float)($challan->due) == (int)($challan->due) ? 0 : 2) }}</td>
                                        <td class="px-3 py-3.5 border-r border-gray-150 dark:border-slate-800 text-gray-600 dark:text-slate-400 font-sans">{{ $challan->notes ?: '—' }}</td>
                                        <td class="px-3 py-3.5 border-r border-gray-150 dark:border-slate-800 font-sans font-semibold text-gray-800 dark:text-white">{{ $challan->due_payment_date ?: '—' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-8 text-center text-gray-450 dark:text-slate-500">কোনো বাকি জমা ইতিহাস পাওয়া যায়নি</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div><!-- ====================== PRINT AREA ====================== -->
    <div id="customer-profile-print-area" class="hidden">
        @php
            $companyName = \App\Models\Setting::get('company_name_bn', 'ডেমো ব্রিকস');
            $companyAddress = \App\Models\Setting::get('address', 'হিলালীপাড়া,কাটাবাড়ি,গোবিন্দগঞ্জ');
            $companyPhone = \App\Models\Setting::get('invoice_phones') ?: \App\Models\Setting::get('owner_phone', '01901349901,01901349906');
            $proprietor = \App\Models\Setting::get('owner_name', 'মোঃ মানিক মিয়া');
        @endphp

        <div class="print-page px-4 py-2">
            <!-- Company Header -->
            <div class="text-center pb-2 mb-2">
                <h1 class="text-3xl font-black text-gray-900 tracking-wide mb-1">{{ $companyName }}</h1>
                <p class="text-xs font-bold text-gray-700 mb-0.5">{{ $companyAddress }}</p>
                <p class="text-xs font-bold text-gray-700 mb-0.5">মোবাইল: {{ $companyPhone }}</p>
                <p class="text-xs font-bold text-gray-800">প্রোপাইটরঃ {{ $proprietor }}</p>
            </div>

            <!-- Title Banner -->
            <div class="bg-[#e5e7eb] text-center py-1.5 rounded-lg font-black text-sm text-gray-900 mb-4 tracking-wide">
                @if($activeTab === 'all_challan')
                    কাস্টমার স্টেটমেন্ট (সব তথ্য)
                @elseif($activeTab === 'delivery_history')
                    কাস্টমার স্টেটমেন্ট (ডেলিভারি তথ্য)
                @elseif($activeTab === 'due_history')
                    কাস্টমার স্টেটমেন্ট (বাকি জমা তথ্য)
                @endif
            </div>

            <!-- Customer Metadata Box -->
            <div class="flex justify-between items-start text-xs mb-4 font-sans border-l-4 border-gray-800 pl-2">
                <div class="space-y-0.5">
                    <h3 class="font-extrabold text-sm text-gray-900">{{ $customer_name }}</h3>
                    <p class="text-gray-700 font-semibold">{{ $customer_address ?: 'ঘোড়াঘাট' }} | মোবাইল: {{ $customer_phone ?: '০১৬৫৬৪৫৬৪৫৬' }}</p>
                    <p class="text-gray-600 font-semibold">আইডি: {{ $printChallans->first() ? $printChallans->first()->id : '১২' }}</p>
                </div>
                <div class="text-right space-y-0.5 font-bold text-gray-800 text-[11px]">
                    <p class="font-black text-gray-900 text-sm">সিজন: ২৫-২৬</p>
                    <p class="font-normal text-gray-600">প্রিন্ট তারিখ: {{ now()->format('d-m-Y') }}</p>
                    <p class="font-normal text-gray-600">সময়: {{ now()->format('h:i:s a') }}</p>
                </div>
            </div>

            <!-- Stat Summary Grid (Only for all_challan tab) -->
            @if($activeTab === 'all_challan')
                <div class="grid grid-cols-3 gap-0 text-center text-xs mb-4 font-bold border border-gray-300 rounded-xl overflow-hidden bg-gray-100">
                    <!-- Row 1 -->
                    <div class="p-2.5 border-r border-b border-gray-300 bg-gray-50/70">
                        <span class="block text-[11px] text-gray-600 font-bold mb-1">মোট ইট ক্রয়</span>
                        <span class="text-gray-900 text-sm font-black font-sans">{{ number_format($stats['total_bricks']) }}</span>
                    </div>
                    <div class="p-2.5 border-r border-b border-gray-300 bg-gray-50/70">
                        <span class="block text-[11px] text-gray-600 font-semibold mb-1">মোট ইট ডেলিভারি</span>
                        <span class="text-gray-900 text-sm font-black font-sans">{{ number_format($stats['delivered']) }}</span>
                    </div>
                    <div class="p-2.5 border-b border-gray-300 bg-gray-50/70">
                        <span class="block text-[11px] text-gray-600 font-semibold mb-1">ইট ডেলিভারি বাকি</span>
                        <span class="text-gray-900 text-sm font-black font-sans">{{ number_format($stats['remaining']) }}</span>
                    </div>
                    <!-- Row 2 -->
                    <div class="p-2.5 border-r border-gray-300 bg-gray-50/70">
                        <span class="block text-[11px] text-gray-600 font-semibold mb-1">মোট টাকা</span>
                        <span class="text-gray-900 text-sm font-black font-sans">৳{{ number_format($stats['total_value']) }}</span>
                    </div>
                    <div class="p-2.5 border-r border-gray-300 bg-gray-50/70">
                        <span class="block text-[11px] text-gray-600 font-semibold mb-1">মোট জমা</span>
                        <span class="text-gray-900 text-sm font-black font-sans">৳{{ number_format($stats['paid']) }}</span>
                    </div>
                    <div class="p-2.5 bg-gray-50/70">
                        <span class="block text-[11px] text-gray-600 font-semibold mb-1">মোট বকেয়া</span>
                        <span class="text-gray-900 text-sm font-black font-sans">৳{{ number_format($stats['due']) }}</span>
                    </div>
                </div>

                <!-- List Section Heading -->
                <div class="flex items-center gap-2 mb-3">
                    <span class="w-4 h-4 bg-black text-white rounded flex items-center justify-center font-bold text-[10px]">১</span>
                    <span class="font-black text-xs text-gray-900">চালান / ইনভয়েস তালিকা</span>
                </div>

                <table class="print-table">
                    <thead>
                        <tr class="print-thead-row">
                            <th class="pt-cell text-center" style="width:75px">তারিখ</th>
                            <th class="pt-cell text-center" style="width:40px">চালান</th>
                            <th class="pt-cell text-left" style="width:65px">শ্রেণি</th>
                            <th class="pt-cell text-right" style="width:45px">পরিমাণ</th>
                            <th class="pt-cell text-right" style="width:40px">দর</th>
                            <th class="pt-cell text-right" style="width:40px">ভাড়া</th>
                            <th class="pt-cell text-right" style="width:40px">ছাড়</th>
                            <th class="pt-cell text-right" style="width:55px">মোট</th>
                            <th class="pt-cell text-right" style="width:55px">জমা</th>
                            <th class="pt-cell text-right" style="width:45px">বাকি</th>
                            <th class="pt-cell text-left" style="width:60px">মন্তব্য</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($printChallans as $challan)
                            <tr class="print-row">
                                <td class="pt-cell text-center font-mono text-[10px]">{{ $challan->date ? $challan->date->format('d-m-Y h:i') : '' }}</td>
                                <td class="pt-cell text-center font-bold font-mono">{{ $challan->challan_no }}</td>
                                <td class="pt-cell text-left font-semibold">
                                    @foreach($challan->items as $item)
                                        <span class="block">{{ $item->category_name }}</span>
                                    @endforeach
                                </td>
                                <td class="pt-cell text-right font-mono font-bold">
                                    @foreach($challan->items as $item)
                                        <span class="block">{{ number_format($item->quantity) }}</span>
                                    @endforeach
                                </td>
                                <td class="pt-cell text-right font-mono">
                                    @foreach($challan->items as $item)
                                        <span class="block">৳{{ number_format((float)($item->rate), (float)($item->rate) == (int)($item->rate) ? 0 : 2) }}</span>
                                    @endforeach
                                </td>
                                <td class="pt-cell text-right font-mono">৳{{ number_format($challan->transport_rent ?: 0) }}</td>
                                <td class="pt-cell text-right font-mono">৳{{ number_format($challan->discount ?: 0) }}</td>
                                <td class="pt-cell text-right font-mono font-bold">৳{{ number_format($challan->grand_total) }}</td>
                                <td class="pt-cell text-right font-mono font-bold">৳{{ number_format($challan->cash) }}</td>
                                <td class="pt-cell text-right font-mono font-bold">৳{{ number_format($challan->due) }}</td>
                                <td class="pt-cell text-left font-sans text-[10px]">{{ $challan->notes ?: '—' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="11" style="text-align:center;padding:8px;font-size:9pt;">কোনো ডেটা নেই</td></tr>
                        @endforelse
                    </tbody>
                </table>

            @elseif($activeTab === 'delivery_history')
                <!-- List Section Heading -->
                <div class="flex items-center gap-2 mb-3">
                    <span class="w-4 h-4 bg-black text-white rounded flex items-center justify-center font-bold text-[10px]">২</span>
                    <span class="font-black text-xs text-gray-900">ডেলিভারি হিস্ট্রি</span>
                </div>

                <!-- Delivery History Print Table -->
                <table class="print-table">
                    <thead>
                        <tr class="print-thead-row">
                            <th class="pt-cell text-center" style="width:90px">তারিখ ও সময়</th>
                            <th class="pt-cell text-center" style="width:45px">ডেলি.নং</th>
                            <th class="pt-cell text-center" style="width:50px">চালান নং</th>
                            <th class="pt-cell text-left" style="width:70px">ইট শ্রেণি</th>
                            <th class="pt-cell text-right" style="width:55px">পরিমাণ</th>
                            <th class="pt-cell text-left" style="width:90px">ঠিকানা</th>
                            <th class="pt-cell text-left" style="width:80px">ড্রাইভার</th>
                            <th class="pt-cell text-left" style="width:70px">মন্তব্য</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $delCounter = 1; @endphp
                        @forelse($printChallans as $challan)
                            @foreach($challan->items as $item)
                                <tr class="print-row">
                                    <td class="pt-cell text-center font-mono text-[10px]">{{ $challan->date ? $challan->date->format('d-m-Y h:i') : '' }}</td>
                                    <td class="pt-cell text-center font-bold font-mono">{{ $delCounter++ }}</td>
                                    <td class="pt-cell text-center font-mono font-bold">{{ $challan->challan_no }}</td>
                                    <td class="pt-cell text-left font-semibold">{{ $item->category_name }}</td>
                                    <td class="pt-cell text-right font-mono font-bold">{{ number_format($item->delivered_quantity ?: $item->quantity) }}</td>
                                    <td class="pt-cell text-left">{{ $challan->customer_address ?: 'ঘোড়াঘাট' }}</td>
                                    <td class="pt-cell text-left">Demo Driver</td>
                                    <td class="pt-cell text-left font-sans text-[10px]">{{ $challan->notes ?: '—' }}</td>
                                </tr>
                            @endforeach
                        @empty
                            <tr><td colspan="8" style="text-align:center;padding:8px;font-size:9pt;">কোনো ডেটা নেই</td></tr>
                        @endforelse
                    </tbody>
                </table>

            @elseif($activeTab === 'due_history')
                <!-- List Section Heading -->
                <div class="flex items-center gap-2 mb-3">
                    <span class="w-4 h-4 bg-black text-white rounded flex items-center justify-center font-bold text-[10px]">৩</span>
                    <span class="font-black text-xs text-gray-900">বাকি জমা / কালেকশন হিস্ট্রি</span>
                </div>

                <!-- Due History Print Table -->
                <table class="print-table">
                    <thead>
                        <tr class="print-thead-row">
                            <th class="pt-cell text-center" style="width:95px">জমা তারিখ</th>
                            <th class="pt-cell text-center" style="width:50px">রশিদ নং</th>
                            <th class="pt-cell text-right" style="width:70px">আগের বাকি</th>
                            <th class="pt-cell text-right" style="width:70px">জমা দিয়েছেন</th>
                            <th class="pt-cell text-right" style="width:70px">অবশিষ্ট বাকি</th>
                            <th class="pt-cell text-left" style="width:80px">মন্তব্য</th>
                            <th class="pt-cell text-left" style="width:70px">সংগ্রহকারী</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($printChallans as $challan)
                            <tr class="print-row">
                                <td class="pt-cell text-center font-mono text-[10px]">{{ $challan->date ? $challan->date->format('d-m-Y h:i PM') : '' }}</td>
                                <td class="pt-cell text-center font-bold font-mono">{{ $challan->challan_no }}</td>
                                <td class="pt-cell text-right font-mono font-bold">৳{{ number_format($challan->grand_total) }}</td>
                                <td class="pt-cell text-right font-mono font-bold text-gray-900">৳{{ number_format($challan->cash) }}</td>
                                <td class="pt-cell text-right font-mono font-bold text-gray-900">৳{{ number_format($challan->due) }}</td>
                                <td class="pt-cell text-left font-sans text-[10px]">{{ $challan->notes ?: '—' }}</td>
                                <td class="pt-cell text-left">—</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" style="text-align:center;padding:8px;font-size:9pt;">কোনো ডেটা নেই</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @endif

            <!-- Signature Area -->
            <div class="print-signature-row flex justify-between items-center pt-12 mt-8 text-xs font-black text-gray-900">
                <div class="print-signature-box text-center w-52">
                    <div class="print-sig-line border-t-2 border-black mb-1"></div>
                    <p class="print-sig-label">কাস্টমার স্বাক্ষর</p>
                </div>
                <div class="print-signature-box text-center w-48">
                    <div class="print-sig-line border-t-2 border-black mb-1"></div>
                    <p class="print-sig-label">ম্যানেজার / কর্তৃপক্ষ</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="print-footer text-center text-[10px] text-gray-400 font-mono mt-8 border-t border-gray-200 pt-2">
                রিপোর্ট প্রিন্ট: {{ now()->format('d-m-Y h:i A') }} | Software by CODENEXTIT.COM
            </div>
        </div>
    </div>
<!-- ====================== NEW DELIVERY MODAL ====================== -->
    @if($showDeliveryModal)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex justify-center items-center p-4" x-data @click.self="$wire.set('showDeliveryModal', false)">
        <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-3xl w-full border border-gray-250 dark:border-slate-700 shadow-2xl p-6 relative max-h-[92vh] overflow-y-auto challan-modal-scroll animate-in fade-in zoom-in-95 duration-150">
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-gray-150 dark:border-slate-800 pb-4 mb-5">
                <h3 class="font-bold text-base font-sans text-gray-800 dark:text-white flex items-center gap-2">
                    নতুন ডেলিভারি 🚚
                </h3>
                <button type="button" wire:click="$set('showDeliveryModal', false)" class="p-1.5 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-xl cursor-pointer text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Form -->
            <form wire:submit.prevent="saveDelivery" class="space-y-4 text-xs font-semibold text-gray-600 dark:text-slate-400">
                <!-- Delivery Info Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block mb-1.5">ডেলিভারি নং</label>
                        <input type="text" wire:model="deliveryNo" class="w-full py-2 px-3 bg-gray-50 border border-gray-255 dark:border-slate-700 rounded-xl focus:outline-none dark:bg-slate-950 dark:text-white text-gray-800">
                    </div>
                    <div>
                        <label class="block mb-1.5">চালান নং</label>
                        <input type="text" wire:model="challan_no" disabled class="w-full py-2 px-3 bg-gray-100 border border-gray-255 dark:border-slate-700 rounded-xl text-gray-500 dark:bg-slate-900/50">
                    </div>
                    <div>
                        <label class="block mb-1.5">ডেলিভারি তারিখ</label>
                        <input type="date" wire:model="deliveryDate" class="w-full py-2 px-3 bg-gray-50 border border-gray-255 dark:border-slate-700 rounded-xl focus:outline-none dark:bg-slate-950 dark:text-white text-gray-800">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block mb-1.5">কাস্টমার নাম</label>
                        <input type="text" wire:model="customer_name" disabled class="w-full py-2 px-3 bg-gray-100 border border-gray-255 dark:border-slate-700 rounded-xl text-gray-500 dark:bg-slate-900/50">
                    </div>
                    <div>
                        <label class="block mb-1.5">ফোন নম্বর</label>
                        <input type="text" wire:model="customer_phone" disabled class="w-full py-2 px-3 bg-gray-100 border border-gray-255 dark:border-slate-700 rounded-xl text-gray-500 dark:bg-slate-900/50">
                    </div>
                    <div>
                        <label class="block mb-1.5">ডেলিভারি ঠিকানা</label>
                        <input type="text" wire:model="customer_address" disabled class="w-full py-2 px-3 bg-gray-100 border border-gray-255 dark:border-slate-700 rounded-xl text-gray-500 dark:bg-slate-900/50">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1.5">পরবর্তী ডেলিভারি তারিখ</label>
                        <input type="date" wire:model="nextDeliveryDate" class="w-full py-2 px-3 bg-gray-50 border border-gray-255 dark:border-slate-700 rounded-xl focus:outline-none dark:bg-slate-950 dark:text-white text-gray-800">
                    </div>
                    <div>
                        <label class="block mb-1.5">নোট</label>
                        <textarea wire:model="deliveryNotes" rows="1" class="w-full py-2 px-3 bg-gray-50 border border-gray-255 dark:border-slate-700 rounded-xl focus:outline-none dark:bg-slate-950 dark:text-white text-gray-800"></textarea>
                    </div>
                </div>

                <!-- Product specs grid -->
                <div class="bg-gray-50/50 dark:bg-slate-950/30 border border-gray-150 dark:border-slate-800 rounded-2xl p-4 space-y-3">
                    <div class="grid grid-cols-4 gap-3 text-center font-bold text-[11px] text-gray-500">
                        <div>শ্রেণি</div>
                        <div>ডেলিভারি পাবে</div>
                        <div>আজকের ডেলিভারি</div>
                        <div>ডেলিভারি বাকি</div>
                    </div>
                    <div class="grid grid-cols-4 gap-3 items-center">
                        <div>
                            <select wire:model.live="selectedChallanItemId" class="w-full py-2 px-3 bg-white dark:bg-slate-950 border border-gray-205 dark:border-slate-800 rounded-xl text-gray-800 dark:text-white font-semibold focus:ring-2 focus:ring-primary-500/20">
                                @if($challanItems)
                                    @foreach($challanItems as $chItem)
                                        <option value="{{ $chItem->id }}">{{ $chItem->category_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div>
                            <input type="text" value="{{ number_format(max(0, (int)$deliveryTotalQty - (int)$deliveredQtySoFar)) }}" disabled class="w-full py-2 px-3 bg-gray-100 border border-gray-200 dark:border-slate-800 rounded-xl text-center text-gray-500 dark:bg-slate-900/50 font-sans">
                        </div>
                        <div>
                            <input type="number" wire:model.live="todayDeliveryQty" class="w-full py-2 px-3 bg-white dark:bg-slate-950 border border-gray-300 dark:border-slate-700 rounded-xl text-center text-gray-800 dark:text-white font-bold font-sans focus:ring-2 focus:ring-primary-500/20" placeholder="0">
                        </div>
                        <div>
                            <input type="text" value="{{ number_format(max(0, (int)$deliveryTotalQty - (int)$deliveredQtySoFar - (int)$todayDeliveryQty)) }}" disabled class="w-full py-2 px-3 bg-gray-100 border border-gray-200 dark:border-slate-800 rounded-xl text-center text-gray-500 dark:bg-slate-900/50 font-sans">
                        </div>
                    </div>
                </div>

                <!-- Driver details & rent -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-2">
                    <div class="space-y-3">
                        <h4 class="font-bold text-[11px] text-gray-500 tracking-wider">ড্রাইভারের তথ্য</h4>
                        <div>
                            <input type="text" wire:model="driverName" placeholder="ড্রাইভারের নাম" class="w-full py-2 px-3 bg-gray-50 border border-gray-255 dark:border-slate-700 rounded-xl focus:outline-none dark:bg-slate-950 dark:text-white">
                        </div>
                        <div>
                            <input type="text" wire:model="driverPhone" placeholder="ড্রাইভারের ফোন নম্বর" class="w-full py-2 px-3 bg-gray-55 border border-gray-255 dark:border-slate-700 rounded-xl focus:outline-none dark:bg-slate-950 dark:text-white">
                        </div>
                        <div>
                            <input type="text" wire:model="vehicleNo" placeholder="গাড়ি নম্বর" class="w-full py-2 px-3 bg-gray-55 border border-gray-255 dark:border-slate-700 rounded-xl focus:outline-none dark:bg-slate-950 dark:text-white">
                        </div>
                    </div>
                    <div class="flex flex-col justify-between">
                        <div class="space-y-2">
                            <h4 class="font-bold text-[11px] text-gray-500 tracking-wider">গাড়ি ভাড়া</h4>
                            <div class="relative flex items-center justify-center">
                                <span class="absolute left-4 text-gray-400 text-lg">৳</span>
                                <input type="number" wire:model="vehicleRent" placeholder="ভাড়া" class="w-full py-4 pl-10 pr-4 bg-gray-50 border border-gray-255 dark:border-slate-700 rounded-2xl text-center text-2xl font-bold text-gray-800 dark:text-white focus:outline-none dark:bg-slate-950 font-sans">
                            </div>
                        </div>
                        <div class="flex items-center justify-between pt-4">
                            <span>কাস্টমারকে এসএমএস দিন</span>
                            <button type="button" @click="$wire.smsToCustomer = !$wire.smsToCustomer" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none" :class="$wire.smsToCustomer ? 'bg-primary' : 'bg-gray-200 dark:bg-slate-800'">
                                <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out" :class="$wire.smsToCustomer ? 'translate-x-5' : 'translate-x-0'"></span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Footer buttons -->
                <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-gray-150 dark:border-slate-800 mt-4">
                    <button type="button" wire:click="$set('showDeliveryModal', false)" class="px-5 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/20 border border-gray-200 dark:border-slate-700 rounded-xl cursor-pointer transition-all">ক্লিয়ার</button>
                    <button type="submit" class="px-6 py-2 bg-primary hover:bg-primary-dark text-white text-xs font-bold rounded-xl cursor-pointer transition-all shadow-md flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>সেভ করুন</button>
                    <button type="button" wire:click="saveDeliveryAndPrint" class="px-6 py-2 bg-primary hover:bg-primary-dark text-white text-xs font-bold rounded-xl cursor-pointer transition-all shadow-md flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>সেভ + প্রিন্ট ডেলিভারি</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- ====================== CHALLAN DETAILS MODAL ====================== -->
    @if($showChallanDetailsModal && $detailsChallan)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex justify-center items-center p-4" x-data @click.self="$wire.set('showChallanDetailsModal', false)">
        <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-4xl w-full border border-gray-250 dark:border-slate-700 shadow-2xl p-7 relative max-h-[92vh] overflow-y-auto challan-modal-scroll animate-in fade-in zoom-in-95 duration-150">
            <!-- Header Row -->
            <div class="flex items-center justify-between border-b border-gray-150 dark:border-slate-800 pb-3 mb-4 text-gray-800 dark:text-slate-200">
                <h3 class="font-bold text-base font-sans text-primary-dark dark:text-primary-400">
                    চালান এর বিস্তারিত
                </h3>
                <button type="button" wire:click="$set('showChallanDetailsModal', false)" class="p-1.5 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-xl cursor-pointer text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Modal Content -->
            <div class="space-y-5 text-gray-800 dark:text-slate-200">

                <!-- Top Meta Section -->
                <div class="flex flex-col sm:flex-row justify-between gap-4">
                    <div>
                        <h4 class="font-extrabold text-primary dark:text-primary-400 text-lg">চালান নং: {{ $detailsChallan->challan_no }}</h4>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 font-sans">চালান তৈরি করেছেন: Demo</p>
                    </div>
                    <div class="sm:text-right">
                        <h4 class="font-extrabold text-gray-800 dark:text-white text-base">ডেমো ব্রিকস</h4>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500">হিলালিপাড়া, কাটাবাড়ি, গোবিন্দগঞ্জ</p>
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-2 font-semibold text-xs text-gray-600 dark:text-slate-400">
                    <!-- Column 1 -->
                    <div class="bg-gray-50/50 dark:bg-slate-950/20 border border-gray-150 dark:border-slate-800 rounded-2xl p-4 space-y-2">
                        <div class="flex justify-between border-b border-gray-100 dark:border-slate-800 pb-1.5"><span>নাম</span> <span class="font-bold text-gray-800 dark:text-white">{{ $detailsChallan->customer_name }}</span></div>
                        <div class="flex justify-between border-b border-gray-100 dark:border-slate-800 pb-1.5"><span>ঠিকানা</span> <span class="text-gray-700 dark:text-slate-300">{{ $detailsChallan->customer_address }}</span></div>
                        <div class="flex justify-between"><span>মোবাইল</span> <span class="font-sans font-bold text-gray-800 dark:text-white">{{ $detailsChallan->customer_phone }}</span></div>
                    </div>
                    <!-- Column 2 -->
                    <div class="bg-gray-50/50 dark:bg-slate-950/20 border border-gray-150 dark:border-slate-800 rounded-2xl p-4 space-y-2">
                        <div class="flex justify-between border-b border-gray-100 dark:border-slate-800 pb-1.5"><span>কাস্টমার আইডি</span> <span class="font-sans font-bold text-gray-800 dark:text-white">{{ $detailsChallan->id }}</span></div>
                        <div class="flex justify-between border-b border-gray-100 dark:border-slate-800 pb-1.5"><span>ধরন</span> <span class="text-gray-800 dark:text-white font-bold">{{ in_array($detailsChallan->challan_type, ['অগ্রিম', 'অগ্রিম চালান']) ? 'অগ্রিম চালান' : 'রেগুলার চালান' }}</span></div>
                        <div class="flex justify-between"><span>ডেলিভারি তারিখ</span> <span class="font-sans text-gray-500">—</span></div>
                    </div>
                    <!-- Column 3 -->
                    <div class="bg-gray-50/50 dark:bg-slate-950/20 border border-gray-150 dark:border-slate-800 rounded-2xl p-4 space-y-2">
                        <div class="flex justify-between border-b border-gray-100 dark:border-slate-800 pb-1.5"><span>তারিখ</span> <span class="font-sans text-gray-800 dark:text-white">{{ $detailsChallan->date ? $detailsChallan->date->format('d-m-Y') : '' }}</span></div>
                        <div class="flex justify-between border-b border-gray-100 dark:border-slate-800 pb-1.5"><span>সময়</span> <span class="text-gray-700 dark:text-slate-300">বিকেল ৫:০৪</span></div>
                        <div class="flex justify-between"><span>সিজন</span> <span class="font-sans font-bold text-gray-800 dark:text-white">২০২৬</span></div>
                    </div>
                </div>

                <!-- Notes -->
                @if($detailsChallan->notes)
                <div class="bg-red-50/50 dark:bg-red-950/10 border border-red-100 dark:border-red-900/30 rounded-2xl p-3.5 text-xs">
                    <span class="font-bold text-red-600 dark:text-red-400 block mb-1">নোট</span>
                    <p class="text-red-700 dark:text-red-300 font-sans">{{ $detailsChallan->notes }}</p>
                </div>
                @endif

                <!-- Items Table -->
                <div class="border border-gray-150 dark:border-slate-800 rounded-2xl overflow-hidden text-xs">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-slate-950 border-b border-gray-150 dark:border-slate-800 text-[10px] uppercase font-bold text-gray-500">
                                <th class="px-4 py-3">শ্রেণি</th>
                                <th class="px-4 py-3 text-right">পরিমাণ</th>
                                <th class="px-4 py-3 text-right text-amber-600">ডেলিভারি</th>
                                <th class="px-4 py-3 text-right">দর</th>
                                <th class="px-4 py-3 text-right">মূল্য</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-800 font-semibold text-gray-700 dark:text-slate-300">
                            @foreach($detailsChallan->items as $item)
                            <tr>
                                <td class="px-4 py-3.5 font-bold text-primary-dark dark:text-primary-400">{{ $item->category_name }}</td>
                                <td class="px-4 py-3.5 text-right font-sans">{{ number_format($item->quantity) }}</td>
                                <td class="px-4 py-3.5 text-right font-sans text-amber-600 font-bold">{{ number_format($item->delivered_quantity ?? 0) }}</td>
                                <td class="px-4 py-3.5 text-right font-sans">৳{{ number_format((float)($item->rate), (float)($item->rate) == (int)($item->rate) ? 0 : 2) }}</td>
                                <td class="px-4 py-3.5 text-right font-sans font-bold">৳{{ number_format((float)($item->amount), (float)($item->amount) == (int)($item->amount) ? 0 : 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Bottom Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                    <!-- Left card: Payment state -->
                    @if($detailsChallan->due > 0)
                        <div class="border-2 border-red-500/80 dark:border-red-500/60 rounded-3xl p-5 flex flex-col items-center justify-center bg-red-50/20 dark:bg-red-950/10 space-y-2 min-h-[120px]">
                            <span class="text-2xl sm:text-3xl font-black text-red-600 dark:text-red-500 tracking-wide font-sans">
                                বাকি: ৳ {{ number_format($detailsChallan->due) }}
                            </span>
                            <span class="text-xs sm:text-sm font-bold text-red-600 dark:text-red-400 font-sans">
                                পরিশোধের তারিখ : {{ $detailsChallan->due_payment_date ? \Carbon\Carbon::parse($detailsChallan->due_payment_date)->format('d-m-Y') : '—' }}
                            </span>
                        </div>
                    @else
                        <div class="border border-primary-500/20 dark:border-primary-500/10 rounded-3xl p-6 flex flex-col items-center justify-center bg-primary-50/10 dark:bg-primary-950/5 min-h-[120px]">
                            <span class="text-4xl sm:text-5xl font-black text-primary dark:text-primary-400 tracking-wider">
                                পরিশোধ
                            </span>
                        </div>
                    @endif

                    <!-- Right card: Stats list -->
                    <div class="grid grid-cols-2 gap-3 text-xs font-bold font-sans">
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-950 border border-gray-100 dark:border-slate-800 rounded-2xl">
                            <span class="text-gray-500">মোট মূল্য</span>
                            <span class="text-gray-800 dark:text-white">৳{{ number_format($detailsChallan->value) }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-950 border border-gray-100 dark:border-slate-800 rounded-2xl">
                            <span class="text-orange-500">ছাড়</span>
                            <span class="text-orange-600 dark:text-orange-400">৳{{ number_format($detailsChallan->discount) }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-950 border border-gray-100 dark:border-slate-800 rounded-2xl">
                            <span class="text-blue-500">গাড়ি ভাড়া</span>
                            <span class="text-blue-600 dark:text-blue-400">৳{{ number_format($detailsChallan->transport_rent) }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-950 border border-gray-100 dark:border-slate-800 rounded-2xl">
                            <span class="text-purple-500">সর্বমোট</span>
                            <span class="text-purple-600 dark:text-purple-400">৳{{ number_format($detailsChallan->grand_total) }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-primary-50 dark:bg-primary-950/20 border border-primary-100/50 dark:border-primary-900/30 rounded-2xl col-span-2">
                            <span class="text-primary-dark dark:text-primary-400">জমা</span>
                            <span class="text-primary-dark dark:text-primary-400 text-sm">৳{{ number_format($detailsChallan->cash) }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-950/15 border border-red-100 dark:border-red-950/30 rounded-2xl col-span-2">
                            <span class="text-red-600 dark:text-red-400">বাকি</span>
                            <span class="text-red-600 dark:text-red-400 text-sm">৳{{ number_format($detailsChallan->due) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Footer Brand Note -->
                <div class="text-center text-[9px] text-gray-400 dark:text-gray-500 font-sans tracking-wide pt-4 border-t border-gray-100 dark:border-slate-800">
                    [ PAYRA TECH ] a sister concern of [ ORIOSIS LTD ]
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- ====================== DELIVERY DETAILS MODAL ====================== -->
    @if($showDeliveryDetailsModal && $detailsChallan)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex justify-center items-center p-4" x-data @click.self="$wire.set('showDeliveryDetailsModal', false)">
        <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-4xl w-full border border-gray-250 dark:border-slate-700 shadow-2xl p-7 relative max-h-[92vh] overflow-y-auto challan-modal-scroll animate-in fade-in zoom-in-95 duration-150">
            <!-- Header Row -->
            <div class="flex items-center justify-between border-b border-gray-150 dark:border-slate-800 pb-3 mb-4 text-gray-800 dark:text-slate-200">
                <h3 class="font-bold text-base font-sans text-primary-dark dark:text-primary-400">
                    ডেলিভারির বিস্তারিত
                </h3>
                <button type="button" wire:click="$set('showDeliveryDetailsModal', false)" class="p-1.5 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-xl cursor-pointer text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Modal Content -->
            <div class="space-y-5 text-gray-800 dark:text-slate-200 font-sans">
                <!-- Secondary Info Header -->
                <div class="flex flex-col sm:flex-row justify-between gap-4 border-b border-gray-100 dark:border-slate-800 pb-4">
                    <div>
                        <h3 class="font-extrabold text-primary dark:text-primary-400 text-lg">ডেলিভারি নং: {{ $detailsChallan->challan_no }}</h3>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500">ডেলিভারি দিয়েছেন: <span class="text-orange-500 font-bold">Demo</span></p>
                    </div>
                    <div class="sm:text-right">
                        <h4 class="font-extrabold text-gray-800 dark:text-white text-base">ডেমো ব্রিকস</h4>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500">হিলালিপাড়া, কাটাবাড়ি, গোবিন্দগঞ্জ</p>
                    </div>
                </div>

                <!-- Customer + Challan Info Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-semibold text-gray-600 dark:text-slate-400">
                    <div class="bg-gray-50/50 dark:bg-slate-950/20 border border-gray-150 dark:border-slate-800 rounded-2xl p-4 space-y-2">
                        <div class="flex justify-between border-b border-gray-100 dark:border-slate-800 pb-1.5"><span>নাম</span> <span class="font-bold text-gray-800 dark:text-white">{{ $detailsChallan->customer_name }}</span></div>
                        <div class="flex justify-between border-b border-gray-100 dark:border-slate-800 pb-1.5"><span>ঠিকানা</span> <span class="text-gray-700 dark:text-slate-300">{{ $detailsChallan->customer_address ?: '—' }}</span></div>
                        <div class="flex justify-between"><span>মোবাইল</span> <span class="font-sans font-bold text-gray-800 dark:text-white">{{ $detailsChallan->customer_phone }}</span></div>
                    </div>
                    <div class="bg-gray-50/50 dark:bg-slate-950/20 border border-gray-150 dark:border-slate-800 rounded-2xl p-4 space-y-2">
                        <div class="flex justify-between border-b border-gray-100 dark:border-slate-800 pb-1.5"><span>চালান নং</span> <span class="font-sans font-bold text-gray-800 dark:text-white">{{ $detailsChallan->challan_no }}</span></div>
                        <div class="flex justify-between border-b border-gray-100 dark:border-slate-800 pb-1.5"><span>চালানের তারিখ</span> <span class="font-sans text-gray-800 dark:text-white">{{ $detailsChallan->date ? $detailsChallan->date->format('d-m-Y') : '—' }}</span></div>
                        <div class="flex justify-between"><span>ডেলিভারির তারিখ</span> <span class="font-sans text-gray-500">{{ $detailsChallan->date ? $detailsChallan->date->format('d-m-Y') : '—' }}</span></div>
                    </div>
                </div>

                <!-- Items Delivery Table -->
                <div class="border border-gray-150 dark:border-slate-800 rounded-2xl overflow-hidden text-xs">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-slate-950 border-b border-gray-150 dark:border-slate-800 text-[10px] uppercase font-bold text-gray-500">
                                <th class="px-4 py-3">শ্রেণি</th>
                                <th class="px-4 py-3 text-right">ইট ক্রয়</th>
                                <th class="px-4 py-3 text-right text-primary">ডেলিভারি</th>
                                <th class="px-4 py-3 text-right text-red-500">বাকি</th>
                                <th class="px-4 py-3 text-right">সময়</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-800 font-semibold text-gray-700 dark:text-slate-300">
                            @foreach($detailsChallan->items as $item)
                            <tr>
                                <td class="px-4 py-3.5 font-bold text-primary-dark dark:text-primary-400">{{ $item->category_name }}</td>
                                <td class="px-4 py-3.5 text-right font-sans">{{ number_format($item->quantity) }}</td>
                                <td class="px-4 py-3.5 text-right font-sans font-bold text-primary dark:text-primary-400">{{ number_format($item->delivered_quantity) }}</td>
                                <td class="px-4 py-3.5 text-right font-sans font-bold text-red-500">{{ number_format(max(0, $item->quantity - $item->delivered_quantity)) }}</td>
                                <td class="px-4 py-3.5 text-right font-sans text-gray-500">{{ $detailsChallan->updated_at ? $detailsChallan->updated_at->format('g:i A') : '—' }}</td>
                            </tr>
                            @endforeach
                            <!-- Total Row -->
                            <tr class="bg-gray-50 dark:bg-slate-950 font-bold border-t border-gray-200 dark:border-slate-700">
                                <td class="px-4 py-3 text-gray-600 dark:text-slate-300">সর্বমোট</td>
                                <td class="px-4 py-3 text-right font-sans text-gray-800 dark:text-white">{{ number_format($detailsChallan->items->sum('quantity')) }}</td>
                                <td class="px-4 py-3 text-right font-sans text-primary dark:text-primary-400">{{ number_format($detailsChallan->items->sum('delivered_quantity')) }}</td>
                                <td class="px-4 py-3 text-right font-sans text-red-500">{{ number_format(max(0, $detailsChallan->items->sum('quantity') - $detailsChallan->items->sum('delivered_quantity'))) }}</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Driver Section -->
                <div class="bg-gray-50/50 dark:bg-slate-950/20 border border-gray-150 dark:border-slate-800 rounded-2xl p-4 text-xs font-semibold text-gray-600 dark:text-slate-400">
                    <span class="block font-bold text-gray-500 text-[10px] uppercase mb-2">ড্রাইভার ও গাড়ি</span>
                    <div class="grid grid-cols-3 gap-4">
                        <div><span class="text-gray-400">নাম: </span><span class="font-bold text-gray-800 dark:text-white">—</span></div>
                        <div><span class="text-gray-400">ফোন: </span><span class="font-sans font-bold text-gray-800 dark:text-white">—</span></div>
                        <div><span class="text-gray-400">গাড়ি নং: </span><span class="font-sans font-bold text-gray-800 dark:text-white">—</span></div>
                    </div>
                </div>

                <!-- Footer Brand Note -->
                <div class="text-center text-[9px] text-gray-400 dark:text-gray-500 font-sans tracking-wide pt-4 border-t border-gray-100 dark:border-slate-800">
                    [ PAYRA TECH ] a sister concern of [ ORIOSIS LTD ]
                </div>
            </div>
        </div>
    </div>
    @endif

    <style>
        .challan-modal-scroll::-webkit-scrollbar { width: 5px; }
        .challan-modal-scroll::-webkit-scrollbar-track { background: transparent; }
        .challan-modal-scroll::-webkit-scrollbar-thumb { background: #10b981; border-radius: 999px; }
        .challan-modal-scroll::-webkit-scrollbar-thumb:hover { background: #059669; }

        /* Custom Scrollbar for horizontal scrolling tables */
        .custom-scrollbar::-webkit-scrollbar {
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 999px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #10b981;
            border-radius: 999px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #059669;
        }

        /* Preview / Print stylesheet classes */
        .print-page {
            width: 100%;
            background: #fff;
            color: #111;
            font-family: 'Noto Serif Bengali', 'SolaimanLipi', 'Kalpurush', Arial, sans-serif;
            font-size: 8.5pt;
        }
        .print-header {
            text-align: center;
            margin-bottom: 8pt;
            border-bottom: 1.5pt solid #111;
            padding-bottom: 6pt;
        }
        .print-company {
            font-size: 20pt;
            font-weight: 900;
            margin: 0 0 2pt;
            letter-spacing: 0.5pt;
            color: #000;
        }
        .print-sub {
            font-size: 9.5pt;
            font-weight: 700;
            margin: 1.5pt 0;
            color: #222;
        }
        .print-meta-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 8pt 0;
            font-size: 9pt;
        }
        .print-meta-date { font-weight: 700; }
        .print-meta-title {
            font-size: 12pt;
            font-weight: 900;
            background-color: #e5e7eb;
            border-radius: 9999px;
            padding: 3pt 18pt;
            color: #000;
        }
        .print-meta-total { font-weight: 700; }
        .print-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12pt;
            table-layout: fixed;
        }
        .print-thead-row th {
            background: #f3f4f6;
            color: #000;
            font-size: 8.5pt;
            font-weight: 700;
            border: 0.5pt solid #9ca3af;
        }
        .pt-cell {
            padding: 4.5pt 4pt;
            border: 0.5pt solid #9ca3af;
            font-size: 8.5pt;
            vertical-align: top;
            color: #000;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: 700; }
        .font-semibold { font-weight: 600; }
        .block { display: block; }
        .print-row-even { background: #f9fafb; }
        .print-cat { color: #000; font-weight: 600; }
        .print-small { font-size: 7.5pt; color: #4b5563; }
        .print-amber { color: #000; }
        .print-green { color: #000; }
        .print-red { color: #000; font-weight: 700; }
        .print-tfoot-row td {
            background: #e8f5e9;
            font-weight: 700;
            border-top: 1.5pt solid #111;
            font-size: 8.5pt;
            color: #000;
        }
        .print-signature-row {
            display: flex;
            justify-content: space-between;
            margin-top: 36pt;
            padding: 0 16pt;
        }
        .print-signature-box {
            width: 120pt;
            text-align: center;
        }
        .print-sig-line {
            border-top: 1.2pt solid #111;
            margin-bottom: 4pt;
        }
        .print-sig-label {
            font-size: 9pt;
            font-weight: 700;
            color: #000;
        }
        .print-footer {
            text-align: center;
            font-size: 7.5pt;
            color: #6b7280;
            border-top: 0.5pt solid #d1d5db;
            margin-top: 12pt;
            padding-top: 6pt;
        }

        /* ========== PRINT RULES ========== */
        @media print {
            @page {
                size: A4 portrait;
                margin: 10mm;
            }
            html, body {
                background: #ffffff !important;
                margin: 0 !important;
                padding: 0 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            body * { visibility: hidden !important; }
            #customer-profile-print-area,
            #customer-profile-print-area * { visibility: visible !important; }
            #customer-profile-print-area {
                display: block !important;
                position: absolute !important;
                left: 0 !important;
                top: 0 !important;
                width: 100% !important;
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
                z-index: 99999 !important;
            }
            .print\:hidden {
                display: none !important;
            }
            .print-table {
                width: 100% !important;
                border-collapse: collapse !important;
            }
            .print-table thead { display: table-header-group !important; }
            .print-table tfoot { display: table-footer-group !important; }
            .print-table tbody { display: table-row-group !important; }
            .print-table tbody tr { page-break-inside: avoid !important; break-inside: avoid !important; }
            .print-signature-row { page-break-inside: avoid !important; break-inside: avoid !important; }
        }
    </style>

    <!-- Include Print Preview Modal for Single Challan/Delivery Row -->
    <x-print-modal :showPrintModal="$showPrintModal" :printChallan="$printChallan" :isDeliveryPrint="$isDeliveryPrint ?? false" />
</div>
