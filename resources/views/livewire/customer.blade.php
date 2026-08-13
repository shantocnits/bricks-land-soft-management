@php
if (!function_exists('toBanglaNum')) {
    function toBanglaNum($num) {
        $eng = ['0','1','2','3','4','5','6','7','8','9'];
        $bn = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
        return str_replace($eng, $bn, $num);
    }
}
@endphp

<div class="w-full">

    {{-- Search and summary bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center gap-4 bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 px-4 sm:px-6 py-3 rounded-2xl transition-colors duration-300 mb-6 shadow-sm">
        <div class="flex items-center gap-2">
            <div class="border border-emerald-250 text-[#034C3C] dark:text-emerald-400 bg-emerald-50/20 dark:bg-emerald-950/10 px-3.5 py-1.5 rounded-xl text-xs font-black whitespace-nowrap">
                কাস্টমারঃ {{ toBanglaNum($count) }} জন
            </div>
        </div>
        
        <div class="relative flex-grow">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="কাস্টমারের নাম, ফোন বা ঠিকানা দিয়ে সার্চ করুন"
                   class="w-full pl-4 pr-10 py-2 text-xs font-semibold rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-808 dark:text-white placeholder-gray-400 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all font-sans">
            <button type="button" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-emerald-600 cursor-pointer focus:outline-none" title="সার্চ করুন">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        </div>
    </div>

    {{-- VIEW 1: Desktop View Table (hidden on small screen widths) --}}
    <div class="hidden md:block bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-3xl overflow-visible shadow-sm mb-6">
        <div class="overflow-x-auto rounded-t-3xl">
            <table class="w-full border-collapse text-left border border-gray-200 dark:border-slate-800" style="min-width: 1050px">
                <thead>
                    <tr class="bg-[#034C3C] text-white text-[11px] font-bold uppercase font-sans">
                        <th class="py-3 px-4 border-r border-white/10 text-center w-28">আইডি</th>
                        <th class="py-3 px-4 border-r border-white/10">নাম, ঠিকানা, ফোন নম্বর</th>
                        <th class="py-3 px-4 border-r border-white/10 w-52">ডেলিভারি</th>
                        <th class="py-3 px-4 border-r border-white/10 w-52">টাকা</th>
                        <th class="py-3 px-4 text-left w-64">বাকি পরিশোধের তারিখ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-150 dark:divide-slate-800/50 font-sans text-xs">
                    @forelse($customers as $c)
                        <tr class="hover:bg-emerald-50/40 dark:hover:bg-emerald-950/10 transition-colors cursor-pointer"
                            onclick="window.location.href='{{ route('challan.customer-profile', ['phone' => $c['phone'] ?: $c['name'], 'from' => 'customer']) }}'">
                            
                            {{-- ID vertical Badge --}}
                            <td class="py-3 px-4 border-r border-gray-150 dark:border-slate-800 text-center">
                                <div class="inline-flex flex-col items-center justify-center p-2.5 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-800 dark:text-emerald-400 border border-emerald-200/50 dark:border-emerald-900/40 rounded-2xl w-full">
                                    <span class="text-lg font-black">{{ toBanglaNum($c['id']) }}</span>
                                    <span class="text-[9px] font-bold mt-1 text-emerald-700/80 dark:text-emerald-400/80 whitespace-nowrap">সিজন-{{ \App\Models\Setting::get('season', '২৫-২৬') }}</span>
                                </div>
                            </td>

                            {{-- Name, Address, Phone --}}
                            <td class="py-3 px-4 border-r border-gray-150 dark:border-slate-800">
                                <div class="space-y-1.5">
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-gray-400 text-[10px] font-bold w-12">নাম</span>
                                        <span class="font-extrabold text-gray-808 dark:text-white text-xs">{{ $c['name'] }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-gray-400 text-[10px] font-bold w-12">ঠিকানা</span>
                                        <span class="font-semibold text-gray-700 dark:text-slate-300">{{ $c['address'] ?: '—' }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-gray-400 text-[10px] font-bold w-12">ফোন নম্বর</span>
                                        <span class="font-bold text-gray-850 dark:text-slate-200 font-mono">{{ toBanglaNum($c['phone']) ?: '—' }}</span>
                                    </div>
                                </div>
                            </td>

                            {{-- Delivery metrics --}}
                            <td class="py-3 px-4 border-r border-gray-150 dark:border-slate-800">
                                <div class="space-y-1.5 font-sans">
                                    <div class="flex items-center justify-between text-slate-500 dark:text-slate-400">
                                        <span class="text-[10px] font-bold">মোট ইট ক্রয়</span>
                                        <span class="font-extrabold text-gray-800 dark:text-white">{{ toBanglaNum(number_format($c['total_purchased'])) }}</span>
                                    </div>
                                    <div class="flex items-center justify-between text-slate-500 dark:text-slate-400">
                                        <span class="text-[10px] font-bold">ডেলিভারি</span>
                                        <span class="font-bold text-emerald-600 dark:text-emerald-400">{{ toBanglaNum(number_format($c['total_delivered'])) }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400">ডেলিভারি বাকি</span>
                                        <span class="font-extrabold {{ $c['delivery_due'] > 0 ? 'text-amber-500 dark:text-amber-400' : 'text-gray-600 dark:text-slate-300' }}">
                                            {{ toBanglaNum(number_format($c['delivery_due'])) }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            {{-- Money metrics --}}
                            <td class="py-3 px-4 border-r border-gray-150 dark:border-slate-800">
                                <div class="space-y-1.5 font-sans">
                                    <div class="flex items-center justify-between text-slate-500 dark:text-slate-400">
                                        <span class="text-[10px] font-bold">মোট মূল্য</span>
                                        <span class="font-extrabold text-gray-800 dark:text-white">৳{{ toBanglaNum(number_format($c['total_value'])) }}</span>
                                    </div>
                                    <div class="flex items-center justify-between text-slate-500 dark:text-slate-400">
                                        <span class="text-[10px] font-bold">পরিশোধ</span>
                                        <span class="font-bold text-emerald-600 dark:text-emerald-400">৳{{ toBanglaNum(number_format($c['total_paid'])) }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400">টাকা বাকি</span>
                                        <span class="font-extrabold {{ $c['total_due'] > 0 ? 'text-rose-500 dark:text-rose-400' : 'text-gray-600 dark:text-slate-300' }}">
                                            ৳{{ toBanglaNum(number_format($c['total_due'])) }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            {{-- Due Date & Action buttons --}}
                            <td class="py-3 px-4">
                                <div class="flex flex-col h-full justify-between gap-3">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-1 text-[10px] text-gray-500 dark:text-gray-400">
                                            <span class="font-bold">পরিশোধের তারিখ:</span>
                                            <span class="font-extrabold text-gray-800 dark:text-slate-200">
                                                {{ $c['due_date'] ? \Carbon\Carbon::parse($c['due_date'])->format('d-m-Y') : '—' }}
                                            </span>
                                        </div>
                                        <div class="flex items-start gap-1 text-[10px] text-gray-500 dark:text-gray-400">
                                            <span class="font-bold">নোট:</span>
                                            <span class="font-medium text-gray-700 dark:text-slate-300 italic truncate max-w-[150px]" title="{{ $c['notes'] }}">
                                                {{ $c['notes'] ?: '—' }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 mt-1.5" onclick="event.stopPropagation()">
                                        <button type="button" wire:click="openUpdateModal('{{ $c['phone'] }}', '{{ $c['name'] }}')"
                                                class="px-2.5 py-1.5 border border-gray-200 dark:border-slate-700 hover:bg-emerald-50 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-200 hover:text-emerald-700 dark:hover:text-emerald-400 font-bold rounded-lg text-[10px] transition-colors cursor-pointer">
                                            আপডেট কাস্টমার
                                        </button>
                                        <button type="button" wire:click="openDateModal('{{ $c['phone'] }}', '{{ $c['name'] }}')"
                                                class="px-2.5 py-1.5 border border-gray-200 dark:border-slate-700 hover:bg-emerald-50 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-200 hover:text-emerald-700 dark:hover:text-emerald-400 font-bold rounded-lg text-[10px] transition-colors cursor-pointer">
                                            আপডেট তারিখ
                                        </button>
                                    </div>
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-sm font-semibold text-gray-400 dark:text-slate-500 bg-white dark:bg-slate-900">
                                কোনো কাস্টমার রেকর্ড পাওয়া যায়নি।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer Paginator --}}
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 px-5 py-4 border-t border-gray-150 dark:border-slate-800 rounded-b-3xl bg-white dark:bg-slate-900">
            <div class="text-xs text-gray-500 dark:text-gray-400 font-sans font-semibold">
                মোট রেকর্ড সংখ্যা: <strong class="text-gray-808 dark:text-white">{{ toBanglaNum($customers->total()) }} টি</strong>
            </div>

            <div class="flex items-center gap-4">
                {{ $customers->links() }}

                {{-- Per Page Selector --}}
                <div x-data="{ open: false }" class="relative font-sans text-xs">
                    <button @click="open = !open" type="button"
                            class="flex items-center justify-between gap-1.5 px-3 py-1.5 bg-white dark:bg-slate-800 text-gray-800 dark:text-white font-bold rounded-lg text-xs border border-gray-200 dark:border-slate-700 cursor-pointer">
                        <span>{{ toBanglaNum($perPage) }} কাস্টমার / পেজ</span>
                        <svg class="w-3.5 h-3.5 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" @click.outside="open = false" x-cloak
                         class="absolute bottom-full mb-1.5 right-0 z-[999] w-40 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl shadow-xl overflow-hidden">
                        <div class="py-1">
                            @foreach([5, 10, 15, 20, 30, 50] as $size)
                                <button type="button" wire:click="$set('perPage', {{ $size }})" @click="open = false"
                                        class="w-full text-left px-3 py-2 text-xs font-bold text-gray-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-slate-800 hover:text-emerald-700 dark:hover:text-emerald-400 font-sans cursor-pointer">
                                    {{ toBanglaNum($size) }} কাস্টমার / পেজ
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- VIEW 2: Mobile View Box Cards (visible on smaller screens) --}}
    <div class="block md:hidden space-y-4 mb-6">
        @forelse($customers as $c)
            <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-3xl p-5 shadow-sm space-y-4 hover:shadow-md transition-shadow cursor-pointer"
                 onclick="window.location.href='{{ route('challan.customer-profile', ['phone' => $c['phone'] ?: $c['name'], 'from' => 'customer']) }}'">
                
                {{-- Top Badge row --}}
                <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-800/80 pb-3">
                    <div class="flex items-center gap-3">
                        <div class="inline-flex flex-col items-center justify-center px-3 py-1.5 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-800 dark:text-emerald-400 border border-emerald-250 dark:border-emerald-900/40 rounded-xl">
                            <span class="text-sm font-black">{{ toBanglaNum($c['id']) }}</span>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-gray-808 dark:text-white text-xs">{{ $c['name'] }}</h4>
                            <p class="text-[10px] text-gray-500 font-mono mt-0.5">{{ toBanglaNum($c['phone']) ?: '—' }}</p>
                        </div>
                    </div>
                    <span class="text-[9px] font-bold bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 px-2 py-0.5 rounded-lg border border-emerald-100 dark:border-emerald-900/40">সিজন-{{ \App\Models\Setting::get('season', '২৫-২৬') }}</span>
                </div>

                {{-- Address detail --}}
                @if($c['address'])
                    <div class="flex gap-1.5 text-xs">
                        <span class="text-gray-400 text-[10px] font-bold w-12">ঠিকানা:</span>
                        <span class="font-semibold text-gray-700 dark:text-slate-300">{{ $c['address'] }}</span>
                    </div>
                @endif

                {{-- Grid statistics --}}
                <div class="grid grid-cols-2 gap-4 bg-gray-50 dark:bg-slate-950/30 p-3.5 rounded-2xl border border-gray-100 dark:border-slate-800/50">
                    {{-- Delivery --}}
                    <div class="space-y-1.5">
                        <h5 class="text-[10px] font-black text-[#034C3C] dark:text-emerald-400 uppercase tracking-wide">ডেলিভারি</h5>
                        <div class="flex items-center justify-between text-[11px]">
                            <span class="text-gray-400">ক্রয়:</span>
                            <span class="font-extrabold text-gray-850 dark:text-slate-200">{{ toBanglaNum(number_format($c['total_purchased'])) }}</span>
                        </div>
                        <div class="flex items-center justify-between text-[11px]">
                            <span class="text-gray-400">ডেলিভারি:</span>
                            <span class="font-bold text-emerald-600">{{ toBanglaNum(number_format($c['total_delivered'])) }}</span>
                        </div>
                        <div class="flex items-center justify-between text-[11px]">
                            <span class="text-gray-400">বাকি:</span>
                            <span class="font-extrabold {{ $c['delivery_due'] > 0 ? 'text-amber-500' : 'text-gray-500' }}">
                                {{ toBanglaNum(number_format($c['delivery_due'])) }}
                            </span>
                        </div>
                    </div>

                    {{-- Money --}}
                    <div class="space-y-1.5 border-l border-gray-200/50 dark:border-slate-800/50 pl-3">
                        <h5 class="text-[10px] font-black text-[#034C3C] dark:text-emerald-400 uppercase tracking-wide">টাকা</h5>
                        <div class="flex items-center justify-between text-[11px]">
                            <span class="text-gray-400">মূল্য:</span>
                            <span class="font-extrabold text-gray-850 dark:text-slate-200">৳{{ toBanglaNum(number_format($c['total_value'])) }}</span>
                        </div>
                        <div class="flex items-center justify-between text-[11px]">
                            <span class="text-gray-400">পরিশোধ:</span>
                            <span class="font-bold text-emerald-600">৳{{ toBanglaNum(number_format($c['total_paid'])) }}</span>
                        </div>
                        <div class="flex items-center justify-between text-[11px]">
                            <span class="text-gray-400">বাকি:</span>
                            <span class="font-extrabold {{ $c['total_due'] > 0 ? 'text-rose-500' : 'text-gray-500' }}">
                                ৳{{ toBanglaNum(number_format($c['total_due'])) }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Due target row --}}
                <div class="flex flex-col gap-1 text-[11px] text-gray-550 dark:text-slate-450 border-t border-gray-100 dark:border-slate-800/80 pt-3">
                    <div class="flex items-center justify-between">
                        <span>পরিশোধের তারিখ:</span>
                        <strong class="text-gray-808 dark:text-slate-250">{{ $c['due_date'] ? \Carbon\Carbon::parse($c['due_date'])->format('d-m-Y') : '—' }}</strong>
                    </div>
                    @if($c['notes'])
                        <div class="flex items-start justify-between">
                            <span>নোট:</span>
                            <span class="italic text-gray-700 dark:text-slate-350 truncate max-w-[200px]">{{ $c['notes'] }}</span>
                        </div>
                    @endif
                </div>

                {{-- Action buttons --}}
                <div class="flex items-center gap-3 pt-1" onclick="event.stopPropagation()">
                    <button type="button" wire:click="openUpdateModal('{{ $c['phone'] }}', '{{ $c['name'] }}')"
                            class="flex-1 py-2 text-center border border-gray-200 dark:border-slate-700 hover:bg-emerald-50 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-200 hover:text-emerald-700 dark:hover:text-emerald-400 font-bold rounded-xl text-xs transition-colors cursor-pointer">
                        আপডেট কাস্টমার
                    </button>
                    <button type="button" wire:click="openDateModal('{{ $c['phone'] }}', '{{ $c['name'] }}')"
                            class="flex-1 py-2 text-center border border-gray-200 dark:border-slate-700 hover:bg-emerald-50 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-200 hover:text-emerald-700 dark:hover:text-emerald-400 font-bold rounded-xl text-xs transition-colors cursor-pointer">
                        আপডেট তারিখ
                    </button>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-3xl p-10 text-center text-gray-400 font-semibold text-xs">
                কোনো কাস্টমার রেকর্ড পাওয়া যায়নি।
            </div>
        @endforelse

        {{-- Mobile Pagination Simple view --}}
        <div class="pt-2">
            {{ $customers->links() }}
        </div>
    </div>

    {{-- MODAL 1: Update Customer Info (কাস্টমারের তথ্য আপডেট) --}}
    @if($showUpdateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm px-4">
            <div @click.outside="$wire.set('showUpdateModal', false)"
                 class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl p-6 shadow-2xl max-w-md w-full relative transition-colors duration-300">
                
                {{-- Close button --}}
                <button type="button" wire:click="$set('showUpdateModal', false)"
                        class="absolute right-4 top-4 text-gray-400 hover:text-rose-500 transition-colors focus:outline-none cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <h3 class="text-sm font-black text-gray-808 dark:text-white font-sans mb-5 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-650" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                    </svg>
                    কাস্টমারের তথ্য আপডেট
                </h3>

                <form wire:submit.prevent="saveCustomerInfo" class="space-y-4 text-xs font-sans">
                    <div class="grid grid-cols-2 gap-4">
                        {{-- Customer ID (disabled/readonly) --}}
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 dark:text-slate-400 mb-1.5 uppercase">আইডি</label>
                            <input type="text" wire:model="updateId" readonly disabled
                                   class="w-full px-3 py-2 border border-gray-200 dark:border-slate-800 rounded-xl bg-gray-50 dark:bg-slate-950/50 text-gray-400 dark:text-slate-500 font-bold focus:outline-none">
                        </div>

                        {{-- Name --}}
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 dark:text-slate-400 mb-1.5">নাম</label>
                            <input type="text" wire:model="updateName"
                                   class="w-full px-3 py-2 border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-gray-808 dark:text-white rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                            @error('updateName') <span class="text-rose-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- Address --}}
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 dark:text-slate-400 mb-1.5">ঠিকানা</label>
                            <input type="text" wire:model="updateAddress"
                                   class="w-full px-3 py-2 border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-gray-808 dark:text-white rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                            @error('updateAddress') <span class="text-rose-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Phone number --}}
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 dark:text-slate-400 mb-1.5">ফোন নম্বর</label>
                            <input type="text" wire:model="updatePhone"
                                   class="w-full px-3 py-2 border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-gray-808 dark:text-white rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 font-mono">
                            @error('updatePhone') <span class="text-rose-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3">
                        <button type="button" wire:click="clearUpdateForm"
                                class="px-4 py-2 border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-800 text-gray-600 dark:text-slate-300 font-bold rounded-xl cursor-pointer">
                            ক্লিয়ার
                        </button>
                        <button type="submit"
                                class="px-5 py-2.5 bg-primary hover:bg-emerald-600 text-white font-bold rounded-xl transition-all cursor-pointer flex items-center gap-1 active:scale-95 border-0">
                            সেভ করুন
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- MODAL 2: Change remaining payment date (বাকি পরিশোধের তারিখ পরিবর্তন করুন) --}}
    @if($showDateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm px-4">
            <div @click.outside="$wire.set('showDateModal', false)"
                 class="bg-white dark:bg-slate-900 border border-gray-155 dark:border-slate-800 rounded-3xl p-6 shadow-2xl max-w-sm w-full relative transition-colors duration-300">
                
                {{-- Close button --}}
                <button type="button" wire:click="$set('showDateModal', false)"
                        class="absolute right-4 top-4 text-gray-400 hover:text-rose-500 transition-colors focus:outline-none cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <h3 class="text-sm font-black text-gray-808 dark:text-white font-sans mb-5 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-650" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    বাকি পরিশোধের তারিখ পরিবর্তন করুন
                </h3>

                <form wire:submit.prevent="saveDueDate" class="space-y-4 text-xs font-sans">
                    @if(isset($selectedCustomerTotalDue) && $selectedCustomerTotalDue <= 0)
                        <div class="p-2.5 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/50 rounded-xl text-center">
                            <span class="text-rose-600 dark:text-rose-400 font-extrabold text-xs block font-sans">কোনো টাকা বাকি নেই</span>
                        </div>
                    @endif
                    {{-- Due payment date selector with Flatpickr --}}
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 dark:text-slate-400 mb-1.5">নতুন তারিখ লিখুন</label>
                        <div class="relative" wire:ignore>
                            <input type="text" data-flatpickr data-wire-prop="newDueDate" wire:model.lazy="newDueDate" placeholder="পরিশোধের তারিখ নির্বাচন করুন"
                                   @if(isset($selectedCustomerTotalDue) && $selectedCustomerTotalDue <= 0) disabled @endif
                                   class="w-full pl-3 pr-10 py-2 border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-gray-808 dark:text-white rounded-xl focus:outline-none focus:border-emerald-500 font-sans cursor-pointer disabled:bg-gray-100 dark:disabled:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-50">
                            <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </span>
                        </div>
                        @error('newDueDate') <span class="text-rose-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3">
                        <button type="button" wire:click="$set('showDateModal', false)"
                                class="px-4 py-2 border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-800 text-gray-600 dark:text-slate-300 font-bold rounded-xl cursor-pointer">
                            বাতিল
                        </button>
                        <button type="submit"
                                @if(isset($selectedCustomerTotalDue) && $selectedCustomerTotalDue <= 0) disabled @endif
                                class="px-5 py-2.5 bg-primary hover:bg-emerald-600 text-white font-bold rounded-xl transition-all cursor-pointer flex items-center gap-1 active:scale-95 border-0 disabled:bg-gray-400 disabled:cursor-not-allowed disabled:opacity-50">
                            পরিবর্তন
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
