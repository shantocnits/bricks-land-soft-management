@php
if (!function_exists('toBanglaNum')) {
    function toBanglaNum($num) {
        $eng = ['0','1','2','3','4','5','6','7','8','9'];
        $bn = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
        return str_replace($eng, $bn, $num);
    }
}

if (!function_exists('toKhotianDateTimeParts')) {
    function toKhotianDateTimeParts($dateStr, $createdAt) {
        $day = '';
        $formattedDate = '';
        
        // Parse database date string (expected in d/m/Y format)
        try {
            $carbonDate = \Carbon\Carbon::createFromFormat('d/m/Y', $dateStr);
            $day = toBanglaNum($carbonDate->format('d'));
            $formattedDate = toBanglaNum($carbonDate->format('d-m-y'));
        } catch (\Exception $e) {
            try {
                $carbonDate = \Carbon\Carbon::parse($dateStr);
                $day = toBanglaNum($carbonDate->format('d'));
                $formattedDate = toBanglaNum($carbonDate->format('d-m-y'));
            } catch (\Exception $ex) {
                $day = toBanglaNum($createdAt ? $createdAt->format('d') : '');
                $formattedDate = toBanglaNum($createdAt ? $createdAt->format('d-m-y') : '');
            }
        }
        
        // Time slot calculation using created_at
        $timeStr = '';
        $part = 'সকাল';
        if ($createdAt) {
            $hour = (int)$createdAt->format('H');
            $timeStr = toBanglaNum($createdAt->format('h:i'));
            
            if ($hour >= 12 && $hour < 15) {
                $part = 'দুপুর';
            } elseif ($hour >= 15 && $hour < 18) {
                $part = 'বিকাল';
            } elseif ($hour >= 18 && $hour < 20) {
                $part = 'সন্ধ্যা';
            } elseif ($hour >= 20 || $hour < 5) {
                $part = 'রাত';
            }
        }
        
        return [
            'day' => $day,
            'part' => $part,
            'time' => $timeStr,
            'formattedDate' => $formattedDate
        ];
    }
}
@endphp

<div class="w-full">
    <!-- View 1: Ledger Group Dashboard Grid -->
    @if(!$isDetail)
        {{-- Header Card --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 p-5 rounded-3xl mb-6 shadow-sm transition-colors duration-300">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-emerald-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-500/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-black text-gray-808 dark:text-white font-sans">খতিয়ান</h2>
                    <p class="text-[10px] text-gray-505 dark:text-gray-400 font-sans font-semibold mt-0.5">ভাটার সকল প্রকার লেজার খতিয়ান এবং পেমেন্টের সারসংক্ষেপ</p>
                </div>
            </div>
        </div>

        {{-- Search and summary bar --}}
        <div class="flex flex-col sm:flex-row sm:items-center gap-4 bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 px-4 sm:px-6 py-3 rounded-2xl transition-colors duration-300 mb-6 shadow-sm">
            <div class="flex items-center gap-2">
                <div class="border border-emerald-250 text-[#034C3C] dark:text-emerald-400 bg-emerald-50/20 dark:bg-emerald-950/10 px-3.5 py-1.5 rounded-xl text-xs font-black whitespace-nowrap">
                    মোটঃ {{ toBanglaNum($count) }} টি
                </div>
            </div>
            
            <div class="relative flex-grow">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="গ্রুপ বা খতিয়ান নাম দিয়ে সার্চ করুন"
                       class="w-full pl-4 pr-10 py-2 text-xs font-semibold rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white placeholder-gray-400 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all font-sans">
                <button type="button" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-emerald-600 cursor-pointer focus:outline-none" title="সার্চ করুন">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Ledger Groups Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($ledgersData as $data)
                <div wire:click="selectLedger('{{ $data->ledger }}')"
                     class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-3xl p-5 shadow-sm hover:shadow-md hover:scale-[1.01] active:scale-[0.99] transition-all cursor-pointer flex items-center gap-4">
                    <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-950/20 text-[#034C3C] dark:text-emerald-400 rounded-2xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-black text-gray-808 dark:text-white font-sans">{{ $data->ledger }}</h4>
                        <p class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 mt-1 font-mono">৳ {{ toBanglaNum(number_format($data->total_payment)) }}</p>
                    </div>
                </div>
            @empty
                <div class="col-span-3 bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-3xl p-12 text-center text-gray-400 font-semibold text-sm">
                    কোনো খতিয়ান রেকর্ড পাওয়া যায়নি।
                </div>
            @endforelse
        </div>

    <!-- View 2: Ledger Detail Page -->
    @else
        {{-- Control buttons & Summary badges bar --}}
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 p-5 rounded-3xl mb-6 shadow-sm transition-colors duration-300">
            <div class="flex flex-wrap items-center gap-3">
                <button type="button" wire:click="goBack"
                        class="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm shadow-emerald-500/20 active:scale-95 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                    খতিয়ান
                </button>
                
                <span class="flex items-center gap-2 px-4 py-2 border border-emerald-600 dark:border-emerald-505 text-emerald-606 dark:text-emerald-400 font-bold rounded-xl text-xs bg-emerald-50/20">
                    📖 {{ $selectedLedger }}
                </span>

                <!-- Aggregated Status Badges -->
                <div class="flex flex-wrap items-center gap-2 ml-2">
                    <span class="px-3.5 py-2 bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900/60 text-amber-700 dark:text-amber-400 rounded-xl text-xs font-black">
                        অগ্রিম: {{ toBanglaNum(number_format($totalAdvance)) }}
                    </span>
                    <span class="px-3.5 py-2 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-250 dark:border-emerald-900/60 text-emerald-700 dark:text-emerald-400 rounded-xl text-xs font-black">
                        পরিশোধঃ {{ toBanglaNum(number_format($totalPayment)) }}
                    </span>
                    <span class="px-3.5 py-2 {{ ($totalAdvance - $totalPayment) < 0 ? 'bg-rose-50 border-rose-200 text-rose-707 dark:bg-rose-950/20 dark:border-rose-900/60 dark:text-rose-400' : 'bg-emerald-50 border-emerald-200 text-emerald-707' }} border rounded-xl text-xs font-black">
                        অগ্রিম বাকি: {{ toBanglaNum(number_format($totalAdvance - $totalPayment)) }}
                    </span>
                </div>
            </div>

            {{-- Datepicker and print option --}}
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-2 font-sans">
                    <div class="relative" wire:ignore>
                        <input type="text" data-flatpickr data-wire-prop="startDate" wire:model.lazy="startDate" placeholder="শুরু তারিখ"
                               class="w-28 pl-3 pr-8 py-2 text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white font-sans font-bold cursor-pointer text-center">
                    </div>
                    <span class="text-gray-400 text-xs">→</span>
                    <div class="relative" wire:ignore>
                        <input type="text" data-flatpickr data-wire-prop="endDate" wire:model.lazy="endDate" placeholder="শেষ তারিখ"
                               class="w-28 pl-3 pr-8 py-2 text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white font-sans font-bold cursor-pointer text-center">
                    </div>
                </div>

                <button type="button" onclick="window.print()"
                        class="px-4 py-2 bg-[#034C3C] hover:bg-emerald-800 text-white rounded-xl text-xs font-bold shadow-sm transition-all flex items-center gap-1.5 active:scale-95 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/>
                    </svg>
                    প্রিন্ট করুন
                </button>
            </div>
        </div>

        {{-- Main Ledger Details List Table --}}
        <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-3xl overflow-visible shadow-sm flex flex-col">
            <div class="overflow-x-auto rounded-t-3xl">
                <table class="w-full border-collapse text-left border border-gray-200 dark:border-slate-800" style="min-width: 1100px">
                    <thead>
                        <tr class="bg-[#034C3C] text-white text-[11px] font-bold uppercase font-sans">
                            <th class="py-3 px-4 border-r border-white/10 text-center w-10">#</th>
                            <th class="py-3 px-4 border-r border-white/10 w-44">তারিখ</th>
                            <th class="py-3 px-4 border-r border-white/10">পেমেন্টের বিবরণ</th>
                            <th class="py-3 px-4 border-r border-white/10 w-36 text-center">পেমেন্টের ধরণ</th>
                            <th class="py-3 px-4 border-r border-white/10 text-right w-24">পরিমাণ</th>
                            <th class="py-3 px-4 border-r border-white/10 text-right w-20">রেট</th>
                            <th class="py-3 px-4 border-r border-white/10 text-right w-28">মোট বিল</th>
                            <th class="py-3 px-4 border-r border-white/10 text-right w-24">অগ্রিম</th>
                            <th class="py-3 px-4 border-r border-white/10 text-right w-24">কর্তন</th>
                            <th class="py-3 px-4 border-r border-white/10 text-right w-28">পেমেন্ট</th>
                            <th class="py-3 px-4 border-r border-white/10 text-right w-24">কম/বেশি</th>
                            <th class="py-3 px-4 text-center w-14">ডক</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-150 dark:divide-slate-800/50 font-sans text-xs">
                        @forelse($payments as $index => $pay)
                            @php
                                $dt = toKhotianDateTimeParts($pay->date, $pay->created_at);
                            @endphp
                            <tr class="hover:bg-emerald-50/40 dark:hover:bg-emerald-950/10 transition-colors">
                                <td class="py-3 px-4 text-center text-gray-500 dark:text-slate-400 font-semibold border-r border-gray-150 dark:border-slate-800">
                                    {{ toBanglaNum($payments->firstItem() + $index) }}
                                </td>
                                <td class="py-3 px-4 border-r border-gray-150 dark:border-slate-800">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-gray-800 dark:text-slate-200 text-xs">
                                            {{ $dt['day'] }} ({{ $dt['part'] }} {{ $dt['time'] }})
                                        </span>
                                        <span class="text-[10px] text-gray-400 mt-0.5">{{ $dt['formattedDate'] }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4 font-semibold text-gray-800 dark:text-slate-200 border-r border-gray-150 dark:border-slate-800 whitespace-pre-wrap max-w-xs">{{ $pay->desc }}</td>
                                <td class="py-3 px-4 text-center border-r border-gray-150 dark:border-slate-800">
                                    <span class="px-2 py-1 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 font-bold rounded-lg text-[10px]">
                                        রেগুলার পেমেন্ট
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-right font-mono font-semibold text-gray-500 border-r border-gray-150 dark:border-slate-800">
                                    {{ $pay->qty > 0 ? toBanglaNum(number_format($pay->qty)) : '০' }}
                                </td>
                                <td class="py-3 px-4 text-right font-mono font-semibold text-gray-600 dark:text-slate-400 border-r border-gray-150 dark:border-slate-800">
                                    {{ $pay->rate > 0 ? toBanglaNum(number_format($pay->rate)) : '০' }}
                                </td>
                                <td class="py-3 px-4 text-right font-mono font-bold text-gray-800 dark:text-slate-200 border-r border-gray-150 dark:border-slate-800">
                                    {{ $pay->total > 0 ? '৳' . toBanglaNum(number_format($pay->total)) : '৳০' }}
                                </td>
                                <td class="py-3 px-4 text-right font-mono font-semibold text-amber-600 dark:text-amber-400 border-r border-gray-150 dark:border-slate-800">
                                    {{ $pay->advance > 0 ? '৳' . toBanglaNum(number_format($pay->advance)) : '৳০' }}
                                </td>
                                <td class="py-3 px-4 text-right font-mono font-semibold text-rose-500 border-r border-gray-150 dark:border-slate-800">
                                    {{ $pay->deduction > 0 ? '৳' . toBanglaNum(number_format($pay->deduction)) : '৳০' }}
                                </td>
                                <td class="py-3 px-4 text-right font-mono font-black text-emerald-600 dark:text-emerald-400 border-r border-gray-150 dark:border-slate-800">
                                    {{ $pay->payment > 0 ? '৳' . toBanglaNum(number_format($pay->payment)) : '৳০' }}
                                </td>
                                <td class="py-3 px-4 text-right font-mono font-semibold text-gray-500 border-r border-gray-150 dark:border-slate-800">
                                    {{ $pay->purchase_receive > 0 ? '৳' . toBanglaNum(number_format($pay->purchase_receive)) : '৳০' }}
                                </td>
                                 <td class="py-3 px-4 text-center">
                                     @if($pay->has_doc && $pay->doc_url)
                                         @php
                                             $dUrl = $pay->doc_url;
                                             if (!str_starts_with($dUrl, 'http://') && !str_starts_with($dUrl, 'https://')) {
                                                 $dUrl = asset($dUrl);
                                             }
                                         @endphp
                                         <a href="{{ $dUrl }}" target="_blank" title="সংযুক্ত ডকুমেন্ট দেখুন"
                                            class="inline-flex items-center justify-center p-1.5 rounded-lg bg-orange-50 dark:bg-orange-950/20 text-orange-600 dark:text-orange-400 border border-orange-200 dark:border-orange-900/60 hover:bg-orange-100 transition-colors focus:outline-none">
                                             <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                                 <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                             </svg>
                                         </a>
                                     @else
                                         <span class="text-gray-300">-</span>
                                     @endif
                                 </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="py-12 text-center text-sm font-semibold text-gray-400 dark:text-slate-500">
                                    এই খতিয়ানে কোনো বিবরণ পাওয়া যায়নি।
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    
                    @if($payments->count() > 0)
                        <tfoot>
                            <tr class="bg-gray-50 dark:bg-slate-800 font-sans text-xs border-t-2 border-gray-200 dark:border-slate-700">
                                <td colspan="3" class="py-3 px-4 font-bold border-r border-gray-150 dark:border-slate-800 text-gray-700 dark:text-slate-300">
                                    মোট পেমেন্ট {{ toBanglaNum($count) }} টি | পরিমাণ {{ toBanglaNum(number_format($totalQty)) }} | মোট বিল ৳{{ toBanglaNum(number_format($totalBill)) }} | পেমেন্ট ৳{{ toBanglaNum(number_format($totalPayment)) }}
                                </td>
                                <td colspan="9" class="py-3 px-4"></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>

            {{-- Footer Pagination --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 px-5 py-4 border-t border-gray-150 dark:border-slate-800 rounded-b-3xl bg-white dark:bg-slate-900">
                <div class="text-xs text-gray-500 dark:text-gray-400 font-sans font-semibold">
                    মোট রেকর্ড সংখ্যা: <strong class="text-gray-800 dark:text-white">{{ toBanglaNum($payments->total()) }} টি</strong>
                </div>

                <div class="flex items-center gap-4">
                    {{-- Pagination Links --}}
                    {{ $payments->links() }}

                    {{-- Per Page Selector --}}
                    <div x-data="{ open: false }" class="relative font-sans">
                        <button @click="open = !open" type="button"
                                class="flex items-center justify-between gap-1.5 px-3 py-1.5 bg-white dark:bg-slate-800 text-gray-800 dark:text-white font-bold rounded-lg text-xs border border-gray-200 dark:border-slate-700 cursor-pointer">
                            <span>{{ toBanglaNum($perPage) }} পেমেন্ট / পেজ</span>
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
                                        {{ toBanglaNum($size) }} পেমেন্ট / পেজ
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
